<?php
	class ImportDTRFromDeviceCollection extends Helper {
		public function __construct() {
			$this->load->model('HelperDao');
			$this->load->model("Security");
			ModelResponse::busy();
		}

		public function importRawDTR($csv_file){

			$this->db->trans_begin();
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Daily Time Record Import old Failed.";
		    if (($handle = fopen($csv_file, "r")) !== FALSE) {
		     	$params = null;
		     	$count = 0;
		        while (($data = fgetcsv($handle)) !== FALSE) {
					$count++;
					$data = preg_split('/\s+/', $data[0]);
					if($params == null) $params = "('" . $data[1] . "','" . $data[2] . "','" . $data[3] . "','" . $data[4] . "','" . $data[5] . "','" . $data[6] . "','" . $data[7] . "')";
					else $params = $params.", ('" . $data[1] . "','" . $data[2] . "','" . $data[3] . "','" . $data[4] . "','" . $data[5] . "','" . $data[6] . "','" . $data[7] . "')";
					if(fmod($count,5000) == 0) {
						$sql = "INSERT INTO tbltimekeepingdailytimerecord (employee_number, transaction_date, transaction_time, col4, transaction_type, col6, col7) VALUES " . $params;
						$query = $this->db->query($sql);
						$params = null;
					} 	
		      	}
      		   	fclose($handle);
			}
	     	if($params != null) {
				$sql = "INSERT INTO tbltimekeepingdailytimerecord (employee_number, transaction_date, transaction_time, col4, transaction_type, col6, col7) VALUES " . $params;
				$query = $this->db->query($sql);
			}
			if($this->db->trans_status() === TRUE) {
				$this->db->trans_commit();
				$code = "0";
				$message = "Import Successful.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;
			} else {
				$this->db->trans_rollback();
				$code = "1";
				$message = "Import Failed.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		public function import_employees_dtr($params, $ids, $dates){
			$helperDao = new HelperDao();
			$code = "1";
			$this->db->trans_begin();
			$message = "Daily Time Record Import Failed.";
			if($ids != null && $dates != null){
				$sql = "DELETE FROM tbldtr WHERE scanning_no IN (".$ids.") AND DATE(transaction_date) IN (".$dates.")";
				$query = $this->db->query($sql);
			}
			$sql = "INSERT INTO tbldtr (scanning_no, transaction_date, check_in, break_out, break_in, check_out, ot_in, ot_out, offset, approve_offset_hrs, approve_offset_mins, ot_hrs, ot_mins, monetized, tardiness_hrs, tardiness_mins, ut_hrs, ut_mins, remarks, source) VALUES " . $params;
			$query = $this->db->query($sql);
			if($this->db->trans_status() === TRUE) {
				$this->db->trans_commit();
				$code = "0";
				$message = "Import Successful.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;
			} else {
				$this->db->trans_rollback();
				$code = "1";
				$message = "Import Failed.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		public function uploadFileDetails($params){
			$helperDao = new HelperDao();
			$logs_params = array('source_device'=> $params["source_device"],'created_by'=> Helper::get('userid'));
			$this->db->insert('tbltimekeepinglogs', $logs_params);
			$log_id = $this->db->insert_id();
			$uploads_params = array('logs_id' => $log_id,'file_name' => $params['file_name'],'file_size' => $params['file_size'],'file_type' => $params['file_type']);
			$this->db->insert('tbltimekeepinguploads', $uploads_params);
			if($this->db->trans_status() === TRUE) {
				$this->db->trans_commit();
				$code = "0";
				$message = "Import Successful.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;
			} else {
				$this->db->trans_rollback();
				$code = "1";
				$message = "Import Failed.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}
	}
?>