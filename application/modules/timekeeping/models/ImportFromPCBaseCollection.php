<?php
	class ImportFromPCBaseCollection extends Helper {
		public function __construct() {
			$this->load->model('HelperDao');
			$this->load->model("Security");
			ModelResponse::busy();
		}

		public function uploadFiles($params){
			// Check if folder exists
			if (!file_exists('./assets/uploads/pcbase'))
				mkdir('./assets/uploads/pcbase', 0777, true);

			$chunksUploaded = 0;
			$helperDao 			= new HelperDao();
			$target_path 		= './assets/uploads/pcbase/';
			$tmp_name 			= @$params['file']['tmp_name'];
			$filename 			= @$params['file']['name'];
			$chunk_index 		= @$params['post']['dzchunkindex'];
			$num_chunks 		= @$params['post']['dztotalchunkcount'];
			$target_file 		= $target_path . $filename;

			// Delete old file if file exists
			if(file_exists($target_path . "masterfiles.mdb") == true && file_exists($target_path . "timekeeper.mdb") == true && file_exists($target_path . "translog.mdb") == true) {
				$folder_files = glob($target_path . '*.{mdb}', GLOB_BRACE);
				foreach($folder_files as $folder_file) {
					unlink($folder_file);
				}
			}
			// Check uploaded
			move_uploaded_file($tmp_name, $target_file . $chunk_index);
			for ($i = 1; $i <= $chunk_index; $i++) {
				if(file_exists($target_file . $i))
					++$chunksUploaded;
			}

			// Recreate using chunks
			if ($chunksUploaded === ($num_chunks - 1)) {
				for ($i = 0; $i <= ($num_chunks - 1); $i++) {
					$file = fopen($target_file . $i, 'rb');
					$buff = fread($file, 2097152);
					fclose($file);
					$final = fopen($target_file, 'ab');
					$write = fwrite($final, $buff);
					fclose($final);
					unlink($target_file . $i);
				}
			}

			$code = "0";
			$message = "Chunk Upload Success.";
			$this->ModelResponse($code, $message);
			return false;
		}

		public function importRawDTR(){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Daily Time Record Import Failed.";
			$target_path = './assets/uploads/pcbase/';

			if(file_exists($target_path . "masterfiles.mdb") == true && file_exists($target_path . "timekeeper.mdb") == true && file_exists($target_path . "translog.mdb") == true) {
				$result = array();
				$source = 'pc base';
				$this->db->trans_begin();

				// Open files then read databases
				$timekeeper_connect = new PDO('odbc:DRIVER={Microsoft Access Driver (*.mdb)}; DBQ='. realpath($target_path . "timekeeper.mdb"));
				$masterfile_connect = new PDO('odbc:DRIVER={Microsoft Access Driver (*.mdb)}; DBQ='. realpath($target_path . "masterfiles.mdb"));
				// $translog_connect 	= new PDO('odbc:DRIVER={Microsoft Access Driver (*.mdb)}; DBQ='. realpath($translog));
				//with password
				/*$timekeeper_connect = new PDO('odbc:DRIVER={Microsoft Access Driver (*.mdb)}; DBQ='. realpath($target_path . "timekeeper.mdb"),'Admin','@CCESSP@ssw0rd');
			    $masterfile_connect = new PDO('odbc:DRIVER={Microsoft Access Driver (*.mdb)}; DBQ='. realpath($target_path . "masterfiles.mdb"),'Admin','@CCESSP@ssw0rd');*/
			    

				// Fetch time records
				$timekeeper_sql  		= "SELECT * FROM timecard";
				$timekeeper_result 	= $timekeeper_connect->query($timekeeper_sql);
				$timekeeper_data 		= $timekeeper_result->fetchAll();

				if(isset($timekeeper_data) && sizeof($timekeeper_data) > 0) {
					// Loop through each timekeeping records
					$logs_params = array(
						'source_device'=> $source,
						'created_by'=> Helper::get('userid')
					);
					$this->db->insert('tbltimekeepinglogs', $logs_params);
					$log_id = $this->db->insert_id();

					foreach ($timekeeper_data as $k => $v) {
						switch ($v['trantype']) {
							case 'A':
								$type = 0;
								break;
							case 'B':
								$type = 2;
								break;
							case 'Z':
								$type = 1;
								break;
							default:
								$type = null;
								break;
						}
						$masterfile_sql  		= "SELECT idno FROM employee WHERE employeeid = " . $v['employeeid'];
						$masterfile_result 	= $masterfile_connect->query($masterfile_sql);
						$employee_id 				= $masterfile_result->fetch();
						$result[] = array(
							"log_id"						=> $log_id,
							"employee_number" 	=> $employee_id['idno'],
							"transaction_date" 	=> date("Y-m-d", strtotime($v["trandate"])),
							"transaction_time" 	=> date("H:i:s", strtotime($v["trantime"])),
							"transaction_type" 	=> $type
						);
					}

					$this->db->insert_batch('tbltimekeepingdailytimerecord', $result);

					// Close PDO connections
					$masterfile_connect = null;
					$timekeeper_connect = null;

				} else {

					// display error if different mdb file was uploaded
					$output['error'] = 'File format and contents are not compatible.';
					http_response_code (401);
					header( 'Content-Type: application/json; charset=utf-8' );
					echo json_encode($output);
					die();
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
					return false;
				}
			} else {

				// Delete folder contents if not compatible
				if(sizeof(glob($target_path . '*.{mdb}', GLOB_BRACE)) > 0) {
					$folder_files = glob($target_path . '*.{mdb}', GLOB_BRACE);
					foreach($folder_files as $folder_file) {
						unlink($folder_file);
					}
				}
				$code = "1";
				$message = "Import Failed.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return false;
			}
		}
	}
?>