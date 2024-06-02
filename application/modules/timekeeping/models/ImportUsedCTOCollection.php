<?php
	class ImportUsedCTOCollection extends Helper {
		public function __construct() {
			$this->load->model('HelperDao');
			$this->load->model("Security");
			ModelResponse::busy();
		}

		public function addRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Failed to import CTO from excel.";
			
			$query = $this->fetchEmployee($params['table-1']['employee_number']);
			if ($query->num_rows() == 0 ) { 
				$message = "Employee with scanning number doesn't exist.";
				$this->ModelResponse($code, $message);
				return false;
			} else {
				unset($params['table-1']["employee_number"]);				
				$params['table-1']['employee_id'] = $params['table-2']['employee_id'] = $query->row()->id;
				$params['table-1']['position_id'] = $params['table-2']['position'] = $query->row()->position_id;
				$params['table-1']['division_id'] = $query->row()->division_id;
				$params['table-1']['salary'] = $query->row()->salary;
				if($params['table-1']['type'] == "monetization")
					$params['table-1']['amount_monetized'] = round($params['table-1']['salary'] * $params['table-1']['number_of_days'] * .0481927,2);

				$isExist_query = $this->validateLeaves($params['table-1']);				
				$this->db->trans_begin();
				if($isExist_query->num_rows() > 0){
					$request_id = $isExist_query->row()->id;
					$this->db->where('id', $request_id)->delete('tbloffsetting');
					$this->db->where('request_id', $request_id)->delete('tbloffsettingapprovals');
				}
				
				//update offset Balance
				$offsetTotal = $params['table-1']['offset_hrs'] > 0 || $params['table-1']['offset_mins']> 0 ?  ($params['table-1']['offset_hrs'] * 60) + $params['table-1']['offset_mins'] : 0;
				$dtrlist = $this->db->select("*")->from("tbldtr")
				->where("transaction_date >=", date('Y-m-d', strtotime(date($params['table-1']['date_filed']).'-1 year')))
				->where("transaction_date <=", date($params['table-1']['date_filed']))
				->where("CAST(scanning_no AS INT)=", (int)$query->row()->empl_id)->order_by('transaction_date', 'ASC')->get()->result_array();
				$convertedOffset = ( number_format($offsetTotal * .0020833333333333, 3, '.', ''));

				$validateAdjustementOffset = array_filter($dtrlist, function ($var){
				    return ($var['adjustment_offset'] > 0);
				});
				foreach ($dtrlist as $k => $v) {
					$offset = count($validateAdjustementOffset) > 0 ? $v['adjustment_offset'] : $v['offset']; 
					$adjustOffset = $offset;

					if($convertedOffset > 0 && $offset > 0) $adjustOffset =  $offset - $convertedOffset <= 0 ? 0 : $offset - $convertedOffset;
						$this->db->set('adjustment_offset', $adjustOffset);
						$this->db->where('id', $v['id']);
						$this->db->update('tbldtr');
						$convertedOffset = $convertedOffset - $offset;
				}
				//update ut balance
				$dtrlistvalidate = $this->db->select("*")->from("tbldtr")
				->where("transaction_date", $params['table-1']['offset_date_effectivity'])
				->where("CAST(scanning_no AS INT)=", (int)$query->row()->empl_id)->get()->result_array();

				// Insert offset to DTR
				$dtrdate = date("Y-m-d", strtotime($params['table-1']['offset_date_effectivity']));
				$offset_hrs = $params['table-1']['offset_hrs'];
				$offset_mins = $params['table-1']['offset_mins'];
				$sqldtr = 'SELECT * FROM tbldtr WHERE scanning_no = "'.(int)$query->row()->empl_id.'" AND transaction_date = "'.$dtrdate.'"';
				$querydtr = $this->db->query($sqldtr);
				if($querydtr->num_rows() > 0){
					$leavetype = strtoupper($params['table-1']['type']);	
					$dtr_sql = "UPDATE tbldtr SET approve_offset_hrs = ?, approve_offset_mins = ?, adjustment_remarks = ? , tardiness_mins = 0 , tardiness_hrs = 0 , ut_mins = 0 , ut_hrs = 0 WHERE scanning_no = ? AND transaction_date = ?";
					$dtrparams = array($offset_hrs, $offset_mins, $leavetype , (int)$query->row()->empl_id, $querydtr->row()->transaction_date);
					$this->db->query($dtr_sql,$dtrparams);	
				}else{
					$leavetype = strtoupper($params['table-1']['type']);	
					$this->db->insert('tbldtr', array("approve_offset_hrs" => $offset_hrs,"approve_offset_mins" => $offset_mins,"adjustment_remarks" => $leavetype ,  "transaction_date" => $dtrdate, "scanning_no" => (int)$query->row()->empl_id));
				}

				$this->db->insert('tbloffsetting',$params['table-1']);
				$params['table-2']['request_id'] = $this->db->insert_id();
				$days2 = explode(', ', $params['table-1']['inclusive_dates']);
				$this->db->insert('tbloffsettingapprovals',$params['table-2']);

				if($this->db->trans_status() === TRUE){
					$code = "0";
					$this->db->trans_commit();
					$message = "CTO successfully imported from excel.";
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message);
					return true;
				}
				else {
					$this->db->trans_rollback();
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message);
				}
				return false;
			}
		}

		function fetchEmployee($employee_number){
			$sql = 'SELECT division_id, position_id, salary, id, DECRYPTER(employee_number,"sunev8clt1234567890",id) as empl_id
					FROM tblemployees
					WHERE 
					CAST(DECRYPTER(employee_number,"sunev8clt1234567890",tblemployees.id) AS INT) = CAST("'.$employee_number.'" AS INT)';
			$query = $this->db->query($sql);
			return $query;
		}
		
		function validateLeaves($params){
			$sql = 'SELECT id 
					FROM tbloffsetting 
					WHERE employee_id = "'.$params['employee_id'].'"
						  AND source_location = "excel_import" 
						  AND source_device != "'.$params['source_device'].'"
						  AND date_filed = "'.$params['date_filed'].'"
						  AND offset_date_effectivity = "'.$params['offset_date_effectivity'].'"';
			$query = $this->db->query($sql);
			return $query;
		}
	}
?>