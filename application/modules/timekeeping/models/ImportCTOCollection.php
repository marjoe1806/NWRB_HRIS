<?php
	class ImportCTOCollection extends Helper {
		public function __construct() {
			$this->load->model('HelperDao');
			$this->load->model("Security");
			ModelResponse::busy();
		}

		public function addRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Scanning no. ".$params["Scanning_no"]." failed to import CTO.";
			
			$employee = $this->db->query("SELECT id,DECRYPTER(employee_number,'sunev8clt1234567890',id) as scanning_no,DECRYPTER(first_name,'sunev8clt1234567890',id) as fname,DECRYPTER(last_name,'sunev8clt1234567890',id) as lname FROM tblemployees WHERE CAST(DECRYPTER(employee_number,'sunev8clt1234567890',id) AS INT) = CAST('".$params["Scanning_no"]."' AS INT)")->row_array();
			if($employee){
				$d = date("Y-m-d", strtotime($params["Date_Earned"]));
				$offset_hrs = isset($params["Hours_earned"]) ? (($params["Hours_earned"] * 60) * 0.0020833333333333) : 0;
				$offset_mins = isset($params["Minutes_earned"]) ? $params["Minutes_earned"]  : 0;
				switch($offset_mins){
					case 1 : $offset_mins = 0.002; break;
					case 2 : $offset_mins = 0.004; break;
					case 3 : $offset_mins = 0.006; break;
					case 4 : $offset_mins = 0.008; break;
					case 5 : $offset_mins = 0.01; break;
					case 6 : $offset_mins = 0.012; break;
					case 7 : $offset_mins = 0.015; break;
					case 8 : $offset_mins = 0.017; break;
					case 9 : $offset_mins = 0.019; break;
					case 10 : $offset_mins = 0.021; break;
					case 11 : $offset_mins = 0.023; break;
					case 12 : $offset_mins = 0.025; break;
					case 13 : $offset_mins = 0.027; break;
					case 14 : $offset_mins = 0.029; break;
					case 15 : $offset_mins = 0.031; break;
					case 16 : $offset_mins = 0.033; break;
					case 17 : $offset_mins = 0.035; break;
					case 18 : $offset_mins = 0.037; break;
					case 19 : $offset_mins = 0.04; break;
					case 20 : $offset_mins = 0.042; break;
					case 21 : $offset_mins = 0.044; break;
					case 22 : $offset_mins = 0.046; break;
					case 23 : $offset_mins = 0.048; break;
					case 24 : $offset_mins = 0.05; break;
					case 25 : $offset_mins = 0.052; break;
					case 26 : $offset_mins = 0.054; break;
					case 27 : $offset_mins = 0.056; break;
					case 28 : $offset_mins = 0.058; break;
					case 29 : $offset_mins = 0.06; break;
					case 30 : $offset_mins = 0.062; break;
					case 31 : $offset_mins = 0.065; break;
					case 32 : $offset_mins = 0.067; break;
					case 33 : $offset_mins = 0.069; break;
					case 34 : $offset_mins = 0.071; break;
					case 35 : $offset_mins = 0.073; break;
					case 36 : $offset_mins = 0.075; break;
					case 37 : $offset_mins = 0.077; break;
					case 38 : $offset_mins = 0.079; break;
					case 39 : $offset_mins = 0.081; break;
					case 40 : $offset_mins = 0.083; break;
					case 41 : $offset_mins = 0.085; break;
					case 42 : $offset_mins = 0.087; break;
					case 43 : $offset_mins = 0.09; break;
					case 44 : $offset_mins = 0.092; break;
					case 45 : $offset_mins = 0.094; break;
					case 46 : $offset_mins = 0.096; break;
					case 47 : $offset_mins = 0.098; break;
					case 48 : $offset_mins = 0.1; break;
					case 49 : $offset_mins = 0.102; break;
					case 50 : $offset_mins = 0.104; break;
					case 51 : $offset_mins = 0.106; break;
					case 52 : $offset_mins = 0.108; break;
					case 53 : $offset_mins = 0.11; break;
					case 54 : $offset_mins = 0.112; break;
					case 55 : $offset_mins = 0.115; break;
					case 56 : $offset_mins = 0.117; break;
					case 57 : $offset_mins = 0.119; break;
					case 58 : $offset_mins = 0.121; break;
					case 59 : $offset_mins = 0.123; break;
					case 60 : $offset_mins = 0.125; break;
					default : 0;

				}
				$offset = $offset_hrs + $offset_mins;

				$insert_params["scanning_no"] = $employee["scanning_no"];
				$insert_params["offset"] = $offset;
				$insert_params["transaction_date"] = $d;
				$insert_params["remarks"] = "";
                
                $hasrows = $this->db->query("SELECT * FROM tbldtr WHERE DATE(transaction_date) = DATE('".$d."') AND CAST(scanning_no AS INT) = CAST('".$employee["scanning_no"]."' AS INT) LIMIT 1")->row_array();

                if($hasrows) {
                    $this->db->where('scanning_no', $employee["scanning_no"])->where("DATE(transaction_date) = DATE('".$d."')")->update("tbldtr",$insert_params);
                }else $this->db->insert("tbldtr",$insert_params);

				if($this->db->trans_status() === TRUE){
					$code = "0";
					$this->db->trans_commit();
					$message = $employee["fname"]." ".$employee["lname"] . " CTO successfully imported.";
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