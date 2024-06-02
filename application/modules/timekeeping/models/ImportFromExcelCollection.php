<?php
	class ImportFromExcelCollection extends Helper {
		public function __construct() {
			$this->load->model('HelperDao');
			$this->load->model("Security");
			ModelResponse::busy();
		}

		public function getShiftDetails($id){
			$sql = "SELECT DECRYPTER(tblemployees.employee_number,'sunev8clt1234567890',tblemployees.id) as scan_no,tblemployees.shift_date_effectivity as shiftDate,tblemployees.* FROM tblemployees WHERE CAST(DECRYPTER(employee_number,'sunev8clt1234567890',id) AS INT) = CAST('".$id."' AS INT)";
			$query = $this->db->query($sql);
			return $query->row_array();
		}

		public function getShiftHistory($id){
			$query = $this->db->select("*")->from("tblemployeesshifthistory")->where("employee_id", $id)->order_by("previous_date_effectivity","DESC")->get();
			return $query->result_array();
		}

		public function getRegularShiftSchedule($id){
			$query = $this->db->select("*")->from("tbltimekeepingemployeeschedulesweekly a")->join("tbltimekeepingemployeeschedules b","b.id = a.shift_code_id","left")->where("b.id", $id)->get();
			return $query->result_array();
		}

		public function getFlexibleShiftSchedule($id){
			$query = $this->db->select("*")->from("tbltimekeepingemployeeflexibleschedulesweekly a")->join("tbltimekeepingemployeeflexibleschedules b","b.id = a.shift_code_id","left")->where("b.id", $id)->get();
			return $query->result_array();
		}

		public function getFlagCeremony($date){
			$sql = "SELECT * FROM tbltimekeepingflagceremonyschedules WHERE DATE(flagdateceremony) = DATE('".$date."') AND is_active = 1";
			$query = $this->db->query($sql);
			return $query->row_array();
		}

		public function getPositionBreak($id){
			$sql = "SELECT b.is_break FROM tblemployees a LEFT JOIN tblfieldpositions b ON a.position_id = b.id WHERE CAST(DECRYPTER(a.employee_number,'sunev8clt1234567890',a.id) AS INT) = CAST('".$id."' AS INT)";
			$query = $this->db->query($sql);
			return $query->row_array();
		}

		public function getHoliday($date){
			$sql = "SELECT * FROM tblfieldholidays a WHERE is_active = 1 AND DATE(a.date) = DATE('".$date."')";
			$query = $this->db->query($sql);
			return $query->row_array();
		}

		public function getOB($id,$date){
			$sql = "SELECT * FROM tbltimekeepinglocatorslips a LEFT JOIN tblemployees b ON a.employee_id = b.id WHERE (a.status = 'APPROVED' OR a.status = 'COMPLETED') AND DATE(a.transaction_date) = DATE('".$date."') AND CAST(DECRYPTER(b.employee_number,'sunev8clt1234567890',b.id) AS INT) = CAST('".$id."' AS INT)";
			$query = $this->db->query($sql);
			return $query->row_array();
		}

		public function getOffset($id,$date){
			$sql = "SELECT * FROM tbloffsetting a LEFT JOIN tblemployees b ON a.employee_id = b.id WHERE DATE(a.offset_date_effectivity) = DATE('".$date."') AND a.status = 5 AND CAST(DECRYPTER(b.employee_number,'sunev8clt1234567890',b.id) AS INT) = CAST('".$id."' AS INT)";
			$query = $this->db->query($sql);
			return $query->row_array();
		}

		public function getIfDriver($id){
			$sql = "SELECT * FROM tblemployees a LEFT JOIN tblfieldpositions b ON a.position_id = b.id WHERE CAST(DECRYPTER(a.employee_number,'sunev8clt1234567890',a.id) AS INT) = CAST('".$id."' AS INT) AND is_driver = 1";
			$query = $this->db->query($sql);
			return $query->row_array();
		}

		public function getCheckifOTCertification($id, $date){
			$sql = "SELECT * FROM tbldtr WHERE CAST(scanning_no as INT) = CAST('".$id."' AS INT) AND transaction_date = '".$date."' AND (remarks = 'OT W/ Certification' OR adjustment_remarks = 'OT W/ Certification')";
			$query = $this->db->query($sql);
			return $query->row_array();
		}

		public function insert_daily_dtr($params){
			$data = array(
				"scanning_no" => $params["SCANNING_NUMBER"],
				"transaction_date" => $params["TRANSACTION_DATE"],
				"source" => 'excel_dtr',
				"adjustment_check_in" => $params["ACTUAL_AM_ARRIVAL"],
				"adjustment_break_out" => $params["ACTUAL_AM_DEPARTURE"],
				"adjustment_break_in" => $params["ACTUAL_PM_ARRIVAL"],
				"adjustment_check_out" => $params["ACTUAL_PM_DEPARTURE"],
				"adjustment_ot_in" => $params["OT_ARRIVAL"],
				"adjustment_ot_out" => $params["OT_DEPARTURE"],
				"offset" => $params["OFFSET"],
				"approve_offset_hrs" => @$params["APPROVE_OFFSET_HRS"],
				"approve_offset_mins" => @$params["APPROVE_OFFSET_MINS"],
				"ot_hrs" => $params["OT_HRS"],
				"ot_mins" => $params["OT_MINS"],
				"adjustment_monetized" => $params["MONETIZED"],
				"tardiness_hrs" => $params["TARDINESS_HRS"],
				"tardiness_mins" => $params["TARDINESS_MINS"],
				"ut_hrs" => $params["UT_HRS"],
				"ut_mins" => $params["UT_MINS"],
				"adjustment_remarks" => $params["ADJUSTMENT_REMARKS"]
			);
			$sql = "SELECT * FROM tbldtr WHERE DATE(transaction_date) = DATE('".$params["TRANSACTION_DATE"]."') AND scanning_no = '".$params["SCANNING_NUMBER"]."'";
			$isTransExist = $this->db->query($sql)->row_array();
			if($isTransExist) $this->db->where("DATE(transaction_date)", $params["TRANSACTION_DATE"])->where("scanning_no",$params["SCANNING_NUMBER"])->update("tbldtr",$data);
			else $this->db->insert('tbldtr',$data);
			$helperDao = new HelperDao();
			if($this->db->trans_status() === TRUE){
				$code = "0";
				$this->db->trans_commit();
				$message = "Timelogs successfully imported from excel.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;
			}else {
				$this->db->trans_rollback();
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		public function addRows($data){
			

			$helperDao = new HelperDao();
			$code = "1";
			$message = "Failed to import timelogs from excel.";

			// $employeeNumber = $data['employee_number'];
			// $sql = "SELECT leave_grouping_id, pay_basis, id FROM tblemployees WHERE CAST(DECRYPTER(employee_number, 'sunev8clt1234567890', id) AS UNSIGNED) = $employeeNumber";
			$sql = 'SELECT leave_grouping_id, pay_basis, id FROM tblemployees WHERE employee_number = ENCRYPTER("'.$data['employee_number'].'","sunev8clt1234567890",tblemployees.id)';
			$query = $this->db->query($sql);
			
			if ($query->num_rows() == 0) { 
				$message = "Employee with scanning number ".$data['employee_number'].", doesn't exist.";
				$this->ModelResponse($code, $message);
				return false;
			} else {
				$leave_id = $query->row()->leave_grouping_id;
				$employee_id = $query->row()->id;
				
				$data['leave_id'] = 0;
	 			if($leave_id != null) 
	 				$data['leave_id'] = $leave_id;
				
				$this->db->trans_begin();
				$pay_basis = $query->row()->pay_basis;
				
				if($pay_basis == 'Permanent') {
					$d = date_parse_from_format("Y-m-d", $data['transaction_date']);
					$this->db->where('MONTH(transaction_date)', $d["month"]);
				} else {
					$transaction_date = date('Y-m-d', strtotime($data['transaction_date'])); 
					$upperlimit = date("m",strtotime($data['transaction_date'])).'/01/'.date('Y', strtotime($data['transaction_date']));
					$middlelimit = date("m",strtotime($data['transaction_date'])).'/15/'.date('Y', strtotime($data['transaction_date']));
					$lowerlimit = date("m",strtotime($data['transaction_date'])).'/31/'.date('Y', strtotime($data['transaction_date']));


					$upperlimit = date('Y-m-d', strtotime($upperlimit));
					$middlelimit = date('Y-m-d', strtotime($middlelimit));
					$lowerlimit = date('Y-m-d', strtotime($lowerlimit));

					if (($transaction_date >= $upperlimit) && ($transaction_date <= $middlelimit)){
					    $this->db->where('transaction_date >=', $upperlimit);
						$this->db->where('transaction_date <=', $middlelimit);
					} else {
					    $this->db->where('transaction_date >', $middlelimit);
						$this->db->where('transaction_date <=', $lowerlimit);
					}
				}				
					$this->db->where('employee_number', $data['employee_number']);
					$this->db->where('source_location', 'excel_import');
					$this->db->where('source_device !=', $data['source_device']);
					$this->db->delete('tbltimekeepingdailytimerecordadjustments');

				$sql_data = array( $data['num_hrs'], $employee_id);
				$updatesql = "UPDATE tblovertimebalance SET num_hrs = ? WHERE employee_id = ? ";
				$this->db->query($updatesql,$sql_data);
				unset($data["num_hrs"]); 
				
				$this->db->insert('tbltimekeepingdailytimerecordadjustments',$data);
				if($this->db->trans_status() === TRUE){
					$code = "0";
					$this->db->trans_commit();
					$message = "Timelogs successfully imported from excel.";
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

		
	}
?>