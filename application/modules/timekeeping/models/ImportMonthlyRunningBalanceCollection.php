<?php
	class ImportMonthlyRunningBalanceCollection extends Helper {
		public function __construct() {
			$this->load->model('HelperDao');
			$this->load->model("Security");
			ModelResponse::busy();
		}

		public function addRows($params){

			unset($params['__rowNum__']);
			unset($params['SOURCE_DEVICE']);
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Scanning no. ".$params["scanning_no"]." failed to import balance.";
			
			$employee = $this->db->query("SELECT id,DECRYPTER(employee_number,'sunev8clt1234567890',id) as scanning_no,DECRYPTER(first_name,'sunev8clt1234567890',id) as fname,DECRYPTER(last_name,'sunev8clt1234567890',id) as lname FROM tblemployees WHERE CAST(DECRYPTER(employee_number,'sunev8clt1234567890',id) AS INT) = CAST('".$params["scanning_no"]."' AS INT)")->row_array();
			if($employee){
				$params["scanning_no"] = $employee["scanning_no"];
                
                $hasrows = $this->db->query("SELECT * FROM tblemployeesmonthlypayrollbalance WHERE month = ".(int)$params["month"]." AND year = '".$params["year"]."' AND scanning_no = '".$employee["scanning_no"]."' LIMIT 1")->row_array();

                if($hasrows) {
                    $this->db->where('scanning_no', $employee["scanning_no"])->where('month', $params["month"])->where('year', $params["year"])->update("tblemployeesmonthlypayrollbalance",$params);
                }else $this->db->insert("tblemployeesmonthlypayrollbalance",$params);

				if($this->db->trans_status() === TRUE){
					$code = "0";
					$this->db->trans_commit();
					$message = $employee["fname"]." ".$employee["lname"] . " balance successfully imported.";
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

		
	}
?>