<?php
	class ImportMonthlyLeaveBalanceCollection extends Helper {
		public function __construct() {
			$this->load->model('HelperDao');
			$this->load->model("Security");
			ModelResponse::busy();
		}

		public function addRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Scanning no. ".$params["scanning_no"]." failed to import leave balance.";
			
			$employee = $this->db->query("SELECT id,DECRYPTER(employee_number,'sunev8clt1234567890',id) as scanning_no,DECRYPTER(first_name,'sunev8clt1234567890',id) as fname,DECRYPTER(last_name,'sunev8clt1234567890',id) as lname FROM tblemployees WHERE CAST(DECRYPTER(employee_number,'sunev8clt1234567890',id) AS INT) = CAST('".$params["scanning_no"]."' AS INT)")->row_array();
			if($employee){
				$insert_params["id"] = $employee["id"];
				$insert_params["scanning_number"] = $employee["scanning_no"];
				$insert_params["year"] = $params["year"];
				$insert_params["month"] = $params["month"];
				$insert_params["vl"] = $params["vl"];
                $insert_params["sl"] = $params["sl"];
                
                $hasrows = $this->db->query("SELECT * FROM tblleavebalancemonthlyasof WHERE month = ".(int)$params["month"]." AND year = '".$params["year"]."' AND id = '".$employee["id"]."' LIMIT 1")->row_array();

                if($hasrows) {
                    unset($insert_params["id"]);
                    $this->db->where('id', $employee["id"])->where('month', $params["month"])->where('year', $params["year"])->update("tblleavebalancemonthlyasof",$insert_params);
                }else $this->db->insert("tblleavebalancemonthlyasof",$insert_params);

				if($this->db->trans_status() === TRUE){
					$code = "0";
					$this->db->trans_commit();
					$message = $employee["fname"]." ".$employee["lname"] . " leave balance successfully imported.";
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message);
					return true;
				}
				else {
					$this->db->trans_rollback();
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message);
				}
			}
			return false;
		}

		function fetchEmployee($employee_number){
			$sql = 'SELECT division_id, position_id, id FROM tblemployees WHERE employee_number = ENCRYPTER("'.$employee_number.'","sunev8clt1234567890",tblemployees.id)';
			$query = $this->db->query($sql);
			return $query;
		}

		function validateLeaves($params){
			$sql = 'SELECT source_location, source_device FROM tblleavebalance WHERE scanning_number = "'.$params['scanning_number'].'" AND year = "'.$params['year'].'" ';
			$query = $this->db->query($sql);
			return $query;
		}

		
	}
?>