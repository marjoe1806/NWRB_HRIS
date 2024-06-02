<?php
	class ImportEmployeesCollection extends Helper {
		public function __construct() {
			$this->load->model('HelperDao');
			$this->load->model("Security");
			ModelResponse::busy();
		}

		public function addRows($params){            
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Employee failed to insert.";
			$id = uniqid();
			$rows1 = $rows2 = $rows3 = $rows4 = $rows5 = $rows6 = $rows7 = $rows8 = $rows9 = $rows10 = $rows11 = $rows12 = $rows13 = $rows14 = $rows15 = $govids = $qarr = $emparr = $educarr = $famarr = array();
			// if(!$this->isEmployeeNumberExist($params['employee_number'])){
				$isEmpSql = $this->db->select("*")->from("tblemployees")
				->where('DECRYPTER(first_name,"sunev8clt1234567890",id)',$params['first_name'])
				->where('DECRYPTER(middle_name,"sunev8clt1234567890",id)',$params['middle_name'])
				->where('DECRYPTER(last_name,"sunev8clt1234567890",id)',$params['last_name'])
				->where('DECRYPTER(extension,"sunev8clt1234567890",id)',$params['extension'])->limit(1)->get();
				$isEmployee = $isEmpSql->row_array();
				if($isEmployee){
					$message = "Name already exists.";
				}else{
					// additional employee fields
					// $emparr["bloodtype"] = strtoupper($params["bloodtype"]);
					// $emparr["sss"] = str_replace("_","0",strtoupper($params["sss"]));
					// $emparr["ageny_employee_no"] = strtoupper($params["ageny_employee_no"]);
					// $emparr["tel_no"] = strtoupper($params["tel_no"]);
					// end of additional employee fields
					
					$emparr['id'] = $id;
					$emparr['employee_id_number'] = Helper::encrypt(strtoupper($params['employee_number']),$id);
					$emparr['employee_number'] = Helper::encrypt(strtoupper($params['scanning_no']),$id);
					$emparr['first_name'] = Helper::encrypt(strtoupper($params['first_name']),$id);
					$emparr['middle_name'] = Helper::encrypt(strtoupper($params['middle_name']),$id);
					$emparr['last_name'] = Helper::encrypt(strtoupper($params['last_name']),$id);
					$emparr["extension"] = Helper::encrypt(strtoupper($params['extension']),$id);;
					$emparr['modified_by'] = Helper::get('userid');
					$division = $this->getDivisionId($params["division_id"]);
					$position = $this->getPositionId($params["position_id"]);
					$emparr["division_id"] = $division[0]['id'];
					$emparr["position_id"] = $position[0]['id'];
					$emparr["birthday"] = $params["birthday"];
					// $emparr["birth_place"] = strtoupper($params["birth_place"]);
					$emparr["gender"] = $params["gender"];
					$emparr["pay_basis"] = $params["pay_basis"];
					$emparr["start_date"] = $params["start_date"];
					$emparr["flex_shift_id"] = $params["flex_shift_id"];
					// $emparr["civil_status"] = $params["civil_status"];
					// $emparr["civil_status_others"] = (($params["civil_status"] == "Others")?$params["civil_status_others"]:"");
					// $emparr["height"] = $params["height"];
					// $emparr["weight"] = $params["weight"];
					// $emparr["gsis"] = str_replace("_","0",$params["gsis"]);
					// $emparr["pagibig"] = str_replace("_","0",$params["pagibig"]);
					// $emparr["philhealth"] = str_replace("_","0",$params["philhealth"]);
					// $emparr["tin"] = str_replace("_","0",$params["tin"]);
					// $emparr["nationality"] = $params["nationality"];
					// $emparr["nationality_details"] = $params["nationality_details"];
					// $emparr["nationality_country"] = (($params["nationality"] != "Filipino")?strtoupper($params["nationality_country"]):"");
					// $emparr["house_number"] = ((isset($params["house_number"]))?strtoupper($params["house_number"]):"");
					// $emparr["street"] = ((isset($params["street"]))?strtoupper($params["street"]):"");
					// $emparr["village"] = ((isset($params["village"]))?strtoupper($params["village"]):"");
					// $emparr["barangay"] = ((isset($params["barangay"]))?strtoupper($params["barangay"]):"");
					// $emparr["municipality"] = ((isset($params["municipality"]))?strtoupper($params["municipality"]):"");
					// $emparr["province"] = ((isset($params["province"]))?strtoupper($params["province"]):"");
					// $emparr["permanent_house_number"] = ((isset($params["permanent_house_number"]))?strtoupper($params["permanent_house_number"]):"");
					// $emparr["permanent_street"] = ((isset($params["permanent_street"]))?strtoupper($params["permanent_street"]):"");
					// $emparr["permanent_village"] = ((isset($params["permanent_village"]))?strtoupper($params["permanent_village"]):"");
					// $emparr["permanent_barangay"] = ((isset($params["permanent_barangay"]))?strtoupper($params["permanent_barangay"]):"");
					// $emparr["permanent_municipality"] = ((isset($params["permanent_municipality"]))?strtoupper($params["permanent_municipality"]):"");
					// $emparr["permanent_province"] = ((isset($params["permanent_province"]))?strtoupper($params["permanent_province"]):"");
					// $emparr["zip_code"] = strtoupper($params["zip_code"]);
					// $emparr["permanent_zip_code"] = strtoupper($params["permanent_zip_code"]);
					$emparr["contact_number"] = strtoupper($params["mobile_no"]);
					$emparr["email"] = $params["email"];
                    
					$this->db->trans_begin();
					$this->db->insert("tblemployees", $emparr);
					if($this->db->affected_rows() > 0) {
						$this->db->insert('tblleavebalance', array("id"=>$id,"scanning_no"=>$params['employee_number'],"source_location"=>"manual_input","year"=>(int)date("Y")-1,"vl"=>0,"sl"=>0,"total"=>0));
						if($this->db->trans_status() === TRUE){
							$this->db->trans_commit();
							$code = "0";
							$message = "Successfully inserted employee.";
							$helperDao->auditTrails(Helper::get('userid'),$message);
							$this->ModelResponse($code, $message, $id);
							return true;
						}else{

							$this->db->trans_rollback();
							$helperDao->auditTrails(Helper::get('userid'),$message);
							$this->ModelResponse($code, $message);
						}		
					}	
					else {
						$helperDao->auditTrails(Helper::get('userid'),$message);
					}
				
				}
			// }
			// else{
			// 	$message = "Scanning No. already exists.";
			// }
			$this->ModelResponse($code, $message);
		}
		public function isEmployeeNumberExist($num){
			$this->sql = "CALL sp_is_Employee_num_exist(?)";
			$query = $this->db->query($this->sql,array($num));
			$rows = $query->result();
			mysqli_next_result($this->db->conn_id);
			// $this->db->select("*");
			// $this->db->from($this->table);
			// $this->db->where("DECRYPTER(tblemployees.employee_number,'sunev8clt1234567890',tblemployees.id) = '".$num."'");
			if(sizeof($rows) > 0){
				return true;
			}
			return false;
		}

		function getDivisionId($code){
			$sql = "SELECT * FROM tblfielddepartments WHERE code = ?";
			$query = $this->db->query($sql,array($code));
			$data = $query->result_array();
			return $data;
		}	

		function getPositionId($code){
			$sql = "SELECT * FROM tblfieldpositions WHERE code = ?";
			$query = $this->db->query($sql,array($code));
			$data = $query->result_array();
			return $data;
		}	
	}
?>