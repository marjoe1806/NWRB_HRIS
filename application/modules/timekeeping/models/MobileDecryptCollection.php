<?php
	class MobileDecryptCollection extends Helper {
		public function __construct() {
			$this->load->model('HelperDao');
			$this->load->model("Security");
			ModelResponse::busy();
		}

		public function importRawDTR($params){

			$helperDao = new HelperDao();
			$code = "1";
			$message = "Daily Time Record Import Failed.";
			$timestamp = new DateTime();
			$securityKey = "TheBestSecretKey";
			$security = new Security();

			// create folder if it doesn't exist
			if (!file_exists('./assets/uploads/mobilepos'))
				mkdir('./assets/uploads/mobilepos', 0777, true);

			$this->db->trans_begin();
			$mobile_flag 	= false;
			$file_details = array();
			$file_details = explode(".", $params['name'][0]);

			// decrypt if extracted from mobile P.O.S. device
			if(substr($params['name'][0], 0, 3) == "mob") {
				$encrypted_params = file_get_contents($params['tmp_name'][0]);
				$decrypted_params = $security->decrypt($encrypted_params, $securityKey);
				$mobile_flag = TRUE;
			}
			// var_dump($encrypted_params);
			// var_dump($decrypted_params); die();

			$source  = "standalone";
			if($mobile_flag == TRUE)
				$source = "mobile";
			$logs_params = array(
				'source_device' => $source,
				'created_by' => Helper::get('userid')
			);
			$this->db->insert('tbltimekeepinglogs', $logs_params);
			$log_id = $this->db->insert_id();
			// clone a copy of the file on uploads directory
			$file = fopen("./assets/uploads/mobilepos/dtr". $timestamp->format('Ymd') . $timestamp->format('His') .".dat", "w");
			$file_contents = isset($decrypted_params) ? $decrypted_params : file_get_contents($params['tmp_name'][0]);
			$trimmed = preg_replace( '/\h+/', "\t", $file_contents);
			foreach (preg_split("/((\r?\n)|(\r\n))/", $trimmed) as $k => $v ) {
				$rowdata = explode("\t", $v);
				$empnumlen = strlen(ltrim(@$rowdata[1], '0'));
				$rowline = array(
					0 => $empnumlen <= 4 ? ' ' . ltrim(@$rowdata[1], '0') : ltrim(@$rowdata[1], '0'),
					1 => @$rowdata[2] . ' ' . @$rowdata[3],
					2 => @$rowdata[4],
					3 => @$rowdata[5],
					4 => @$rowdata[6],
					5 => @$rowdata[7]
				);
				$line[] = implode("\t", $rowline);
			}
			$trimmed = implode("\r\n", $line);
			$working_date = explode("-", array_slice(explode(" ", strtok($trimmed, "\n")), 2)[0]);
			fwrite($file, $trimmed);
			fclose($file);

			if($this->db->trans_status() === TRUE) {
				$this->db->trans_commit();
				$code = "0";
				$message = "Import Successful.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				// $path = 'C:\\xampp\\';
				// exec("explorer '" . $path . "'");
				return true;
			} else {
				$this->db->trans_rollback();
				$code = "1";
				$message = "Import Failed.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return false;
			}
		}
	}
?>