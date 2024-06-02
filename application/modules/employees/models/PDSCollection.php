<?php
	class PDSCollection extends Helper {
      	var $select_column = null; 
		var $selectEmployeesParams = array();
		var $sql = "";
		public function __construct() {
			$this->load->model('HelperDao');
			ModelResponse::busy();
			$columns = $this->getColumns();
			foreach ($columns as $key => $value) $this->select_column[] = $this->table.'.'.$value;
		}
		//Fetch
		var $table = "tblemployees";    
      	var $order_column = array(
      				"DECRYPTER(tblemployees.employee_id_number,'sunev8clt1234567890',tblemployees.id)",
      				"CAST(DECRYPTER(tblemployees.employee_number,'sunev8clt1234567890',tblemployees.id) AS INT)",
      				"DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id)",
      				"tblfieldpositions.name",
      				"tblfielddepartments.department_name",
      				"tblfieldlocations.name",
      				"tblemployees.employment_status",
      				"tblemployees.date_created",
      				""
      	);
      	public function getColumns(){
			$rows = array(
				'employee_id_number',
				'employee_number',
				'last_name',
				'middle_name',
				'first_name',
				'contact_number',
				'employment_status'
			);
			return $rows; 
          }
          
		function make_query(){
			$this->selectEmployeesParams = array(
				(isset($_POST["search"]["value"]))?$_POST["search"]["value"]:"",
				(isset($_GET['PayBasis']))?$_GET['PayBasis']:"",
				(isset($_GET['DivisionId']))?$_GET['DivisionId']:"",
				(isset($_GET['SalaryGrade']))?$_GET['SalaryGrade']:"",
				(isset($_GET['PositionId']))?$_GET['PositionId']:"",
				(isset($_GET['EmploymentStatus']))?$_GET['EmploymentStatus']:"Active",
				(isset($_POST['id']))?$_POST['id']:"",
				(isset($_GET['order']))?$_GET['order']:"",
				(isset($_POST['order']['0']['column']) && isset($this->order_column[$_POST['order']['0']['column']]))?$this->order_column[$_POST['order']['0']['column']]:"",
				(isset($_POST['order']['0']['dir']))?$_POST['order']['0']['dir']:"",
				(isset($_POST['length']))?$_POST['length']:"",
				(isset($_POST['start']))?$_POST['start']:""
			);
			$this->sql = "CALL sp_get_employees(?,?,?,?,?,?,?,?,?,?,?,?)";
		}

		function get_details(){
			$this->selectEmployeesParams = array(
				(isset($_POST["search"]["value"]))?$_POST["search"]["value"]:"",
				(isset($_GET['PayBasis']))?$_GET['PayBasis']:"",
				(isset($_GET['DivisionId']))?$_GET['DivisionId']:"",
				(isset($_GET['SalaryGrade']))?$_GET['SalaryGrade']:"",
				(isset($_GET['PositionId']))?$_GET['PositionId']:"",
				(isset($_GET['EmploymentStatus']))?$_GET['EmploymentStatus']:"Active",
				(isset($_SESSION['id']))?$_SESSION['id']:"",
				(isset($_GET['order']))?$_GET['order']:"",
				(isset($_POST['order']['0']['column']) && isset($this->order_column[$_POST['order']['0']['column']]))?$this->order_column[$_POST['order']['0']['column']]:"",
				(isset($_POST['order']['0']['dir']))?$_POST['order']['0']['dir']:"",
				(isset($_POST['length']))?$_POST['length']:"",
				(isset($_POST['start']))?$_POST['start']:""
			);
			$this->sql = "CALL sp_get_employees(?,?,?,?,?,?,?,?,?,?,?,?)";
			
			$query = $this->db->query($this->sql,$this->selectEmployeesParams);
			$result = $query->result();
			mysqli_next_result($this->db->conn_id);
		    return $result;
		}

	    public function getEmpRows($id){
	        $ret = array();
	        $familybackgroundchildrens = $this->db->select("*")->from($this->table.'familybackgroundchildrens')->where("employee_id",$id)->get()->result_array();
			$civilserviceeligibility = $this->db->select("*")->from($this->table.'civilserviceeligibility')->where("employee_id",$id)->get()->result_array();
			$workexperience = $this->db->select("*")->from($this->table.'workexperience')->where("employee_id",$id)->get()->result_array();
			$voluntarywork = $this->db->select("*")->from($this->table.'voluntarywork')->where("employee_id",$id)->get()->result_array();
			$learningdevelopment = $this->db->select("*")->from($this->table.'learningdevelopment')->where("employee_id",$id)->get()->result_array();
			$trainings = $this->db->select("
						a.id,title as training,
					start_date as traning_from,
					end_date as training_to,
					no_of_hours as training_number_hours,
					type as training_type,
					sponsor as training_sponsored_by,
					date_format(str_to_date(start_date, '%m/%d/%Y'), '%Y-%m-%d') as date")
				->from('tbltrainings a')->join('tbltrainingsattendees b',"b.seminar_id = a.id","left")->where("b.employee_id",$id)
				->get()->result_array();
			$specialskills = $this->db->select("*")->from($this->table.'specialskills')->where("employee_id",$id)->get()->result_array();
			$recognitions = $this->db->select("*")->from($this->table.'recognitions')->where("employee_id",$id)->get()->result_array();
			$organizations = $this->db->select("*")->from($this->table.'organizations')->where("employee_id",$id)->get()->result_array();
			$references = $this->db->select("*")->from($this->table.'references')->where("employee_id",$id)->get()->result_array();
			$educbgelementary = $this->db->select("*")->from($this->table.'educbgelementary')->where("employee_id",$id)->get()->result_array();
			$educbgsecondary = $this->db->select("*")->from($this->table.'educbgsecondary')->where("employee_id",$id)->get()->result_array();
			$educbgcolleges = $this->db->select("*")->from($this->table.'educbgcolleges')->where("employee_id",$id)->get()->result_array();
			$educbggradstuds = $this->db->select("*")->from($this->table.'educbggradstuds')->where("employee_id",$id)->get()->result_array();
			$educbgvocationals = $this->db->select("*")->from($this->table.'educbgvocationals')->where("employee_id",$id)->get()->result_array();
			// Push each training record into the $learningdevelopment array
			// foreach ($trainings as $training) {
			// 	array_push($learningdevelopment, $training);
			// }
	        $ret = array("Code"=>0,"Message"=>"Success!",
	            "Data"=>array(
	                "familybackgroundchildrens" => $familybackgroundchildrens,
					"civilserviceeligibility" => $civilserviceeligibility,
					"workexperience" => $workexperience,
					"voluntarywork" => $voluntarywork,
					"learningdevelopment" => $learningdevelopment,
					"specialskills" => $specialskills,
					"recognitions" => $recognitions,
					"organizations" => $organizations,
					"references" => $references,
					"educbgelementary" => $educbgelementary,
					"educbgsecondary" => $educbgsecondary,
					"educbgcolleges" => $educbgcolleges,
					"educbggradstuds" => $educbggradstuds,
					"educbgvocationals" => $educbgvocationals
	            )
	        );
	        return $ret;
		}

	    public function getEmpAtthcmentRows($id){
			$code = "1";
			$message = "No data available.";
			$this->sql = "CALL sp_get_employee_attactments(?)";
			$query = $this->db->query($this->sql,$id);
			$result = $query->result();
			mysqli_next_result($this->db->conn_id);
		    if(sizeof($result) > 0){
				$code = "0";
				$message = "Successfully fetched Employee PDS Attachments.";
				$data = $query->result_array();
				$this->ModelResponse($code, $message, $data);
				return true;		
            }else $this->ModelResponse($code, $message);
			return false;
		}

		function make_datatables(){   
			$this->make_query();
			$query = $this->db->query($this->sql,$this->selectEmployeesParams);
			$result = $query->result();
			mysqli_next_result($this->db->conn_id);
		    return $result;
        }  
        
		function hasRows(){
			$code = "1";
			$message = "No data available.";
			$this->sql = "CALL sp_get_active_employees()";
			$query = $this->db->query($this->sql);
			$rows = $query->result();
			mysqli_next_result($this->db->conn_id);
		    if(sizeof($rows) > 0){
				$code = "0";
				$message = "Successfully fetched employee.";
				$data['details'] = $rows;
				$this->ModelResponse($code, $message, $data);
				return true;		
			}else $this->ModelResponse($code, $message);
			return false;
		}

		
		function hasRowsById(){
			$code = "1";
			$message = "No data available.";
			$this->make_query(); 
			$query = $this->db->query($this->sql,$this->selectEmployeesParams);
			$rows = $query->result();
			mysqli_next_result($this->db->conn_id);
		    if(sizeof($rows) > 0){
				$code = "0";
				$message = "Successfully fetched employee.";
				$data['details'] = $rows;
				$this->ModelResponse($code, $message, $data);
				return true;		
			}else $this->ModelResponse($code, $message);
			return false;
        }
        
		function get_filtered_data(){  
			$this->make_query();
			$query = $this->db->query($this->sql,$this->selectEmployeesParams);
			$result = $query->result();
			mysqli_next_result($this->db->conn_id); 
			return sizeof($result); 
        }       
        
		function get_all_data(){  
		    $this->db->select($this->table."*");  
		    $this->db->from($this->table);
		    if(isset($_GET['EmploymentStatus']) && $_GET['EmploymentStatus'] != null) $this->db->where($this->table.'.employment_status',$_GET['EmploymentStatus']);
		    return $this->db->count_all_results();  
		}  
		//End Fetch

		public function checkAccess($id){
			$code = "0";
			$message = "Check employee pds access failed.";
			$this->sql = "CALL sp_check_employee_access(?)";
			$query = $this->db->query($this->sql,array($id));
			$rows = $query->result();
			mysqli_next_result($this->db->conn_id);
		    if(sizeof($rows) > 0){
				$code = "0";
				$message = "Successfully checked employee pds access.";
				$data = $query->row_array();
				$this->ModelResponse($code, $message, $data);
				return true;		
            }else $this->ModelResponse($code, $message);
			return false;
		}

		public function updateRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Employee failed to update.";
			$id = $params["id"];
			$rows1 = $rows2 = $rows3 = $rows4 = $rows5 = $rows6 = $rows7 = $rows8 = $rows9 = $rows10 = $rows11 = $rows12 = $rows13 = $rows14 = $rows15 = $govids = $qarr = $emparr = $educarr = $famarr = array();
			// additional table family background
				$famarr["spouse_last_name"] = strtoupper($params["spouse_last_name"]);
				$famarr["spouse_first_name"] = strtoupper($params["spouse_first_name"]);
				$famarr["spouse_extension"] = strtoupper($params["spouse_extension"]);
				$famarr["spouse_middle_name"] = strtoupper($params["spouse_middle_name"]);
				$famarr["spouse_occupation"] = strtoupper($params["spouse_occupation"]);
				$famarr["spouse_employer_name"] = strtoupper($params["spouse_employer_name"]);
				$famarr["spouse_business_address"] = strtoupper($params["spouse_business_address"]);
				$famarr["spouse_tel_no"] = strtoupper($params["spouse_tel_no"]);
				$famarr["father_last_name"] = strtoupper($params["father_last_name"]);
				$famarr["father_first_name"] = strtoupper($params["father_first_name"]);
				$famarr["father_extension"] = strtoupper($params["father_extension"]);
				$famarr["father_middle_name"] = strtoupper($params["father_middle_name"]);
				$famarr["mother_last_name"] = strtoupper($params["mother_last_name"]);
				$famarr["mother_first_name"] = strtoupper($params["mother_first_name"]);
				$famarr["mother_extension"] = strtoupper($params["mother_extension"]);
				$famarr["mother_middle_name"] = strtoupper($params["mother_middle_name"]);
				// end of additional table family background
				// additional table govid
				$govids['gov_issued_id'] = strtoupper(@$params["gov_issued_id"]);
				$govids['valid_id'] = strtoupper(@$params["valid_id"]);
				$govids['place_issuance'] = strtoupper(@$params["place_issuance"]);
				//end of additional table govid
				// additional table for questions
				$qarr["radio_input_01"] = @$params["radio_input_01"];
				$qarr["if_yes_01"] = ((@$params["radio_input_01"] == "Yes")?strtoupper(@$params["if_yes_01"]):"");
				$qarr["radio_input_02"] = @$params["radio_input_02"];
				$qarr["if_yes_02"] = ((@$params["radio_input_02"] == "Yes")?strtoupper(@$params["if_yes_02"]):"");
				$qarr["radio_input_03"] = @$params["radio_input_03"];
				$qarr["if_yes_03"] = ((@$params["radio_input_03"] == "Yes")?strtoupper(@$params["if_yes_03"]):"");
				$qarr["radio_input_04"] = @$params["radio_input_04"];
				$qarr["if_yes_04"] = ((@$params["radio_input_04"] == "Yes")?strtoupper(@$params["if_yes_04"]):"");
				$qarr["if_yes_case_status_04"] = ((@$params["radio_input_04"] == "Yes")?strtoupper(@$params["if_yes_case_status_04"]):"");
				$qarr["radio_input_05"] = @$params["radio_input_05"];
				$qarr["if_yes_05"] = ((@$params["radio_input_05"] == "Yes")?strtoupper(@$params["if_yes_05"]):"");
				$qarr["radio_input_06"] = @$params["radio_input_06"];
				$qarr["if_yes_06"] = ((@$params["radio_input_06"] == "Yes")?strtoupper(@$params["if_yes_06"]):"");
				$qarr["radio_input_07"] = @$params["radio_input_07"];
				$qarr["if_yes_07"] = ((@$params["radio_input_07"] == "Yes")?strtoupper(@$params["if_yes_07"]):"");
				$qarr["radio_input_08"] = @$params["radio_input_08"];
				$qarr["if_yes_08"] = ((@$params["radio_input_08"] == "Yes")?strtoupper(@$params["if_yes_08"]):"");
				$qarr["radio_input_09"] = @$params["radio_input_09"];
				$qarr["if_yes_09"] = ((@$params["radio_input_09"] == "Yes")?strtoupper(@$params["if_yes_09"]):"");
				$qarr["radio_input_10"] = @$params["radio_input_10"];
				$qarr["if_yes_10"] = ((@$params["radio_input_10"] == "Yes")?strtoupper(@$params["if_yes_10"]):"");
				$qarr["radio_input_11"] = @$params["radio_input_11"];
				$qarr["if_yes_11"] = ((@$params["radio_input_11"] == "Yes")?strtoupper(@$params["if_yes_11"]):"");
				$qarr["radio_input_12"] = @$params["radio_input_12"];
				$qarr["if_yes_12"] = ((@$params["radio_input_12"] == "Yes")?strtoupper(@$params["if_yes_12"]):"");
				// end of additional table for questions
				// employee table fields
				// additional employee fields
				$emparr["bloodtype"] = strtoupper($params["bloodtype"]);
				$emparr["sss"] = str_replace("_","0",strtoupper($params["sss"]));
				// $emparr["ageny_employee_no"] = strtoupper($params["ageny_employee_no"]);
				$emparr["tel_no"] = strtoupper($params["tel_no"]);
				// end of additional employee fields
				// $emparr['employee_id_number'] = Helper::encrypt(strtoupper($params['employee_id_number']),$id);
				// $emparr['employee_number'] = Helper::encrypt(strtoupper($params['employee_number']),$id);
				$emparr['first_name'] = Helper::encrypt(strtoupper($params['first_name']),$id);
				$emparr['middle_name'] = Helper::encrypt(strtoupper($params['middle_name']),$id);
				$emparr['last_name'] = Helper::encrypt(strtoupper($params['last_name']),$id);
				$emparr["extension"] = Helper::encrypt(strtoupper($params['extension']),$id);;
				$emparr['modified_by'] = Helper::get('userid');
				$emparr["division_id"] = $params["division_id"];
				$emparr["birthday"] = $params["birthday"];
				$emparr["birth_place"] = strtoupper($params["birth_place"]);
				$emparr["gender"] = $params["gender"];
				$emparr["civil_status"] = $params["civil_status"];
				$emparr["civil_status_others"] = (($params["civil_status"] == "Others")?strtoupper($params["civil_status_others"]):"");
				$emparr["height"] = $params["height"];
				$emparr["weight"] = $params["weight"];
				$emparr["gsis"] = str_replace("_","0",$params["gsis"]);
				$emparr["pagibig"] = str_replace("_","0",$params["pagibig"]);
				$emparr["philhealth"] = str_replace("_","0",$params["philhealth"]);
				$emparr["tin"] = str_replace("_","0",$params["tin"]);
				$emparr["nationality"] = $params["nationality"];
				$emparr["nationality_details"] = $params["nationality_details"];
				$emparr["nationality_country"] = (($params["nationality"] != "Filipino")?strtoupper($params["nationality_country"]):"");
				$emparr["house_number"] = ((isset($params["house_number"]))?strtoupper($params["house_number"]):"");
				$emparr["street"] = ((isset($params["street"]))?strtoupper($params["street"]):"");
				$emparr["village"] = ((isset($params["village"]))?strtoupper($params["village"]):"");
				$emparr["barangay"] = ((isset($params["barangay"]))?strtoupper($params["barangay"]):"");
				$emparr["municipality"] = ((isset($params["municipality"]))?strtoupper($params["municipality"]):"");
				$emparr["province"] = ((isset($params["province"]))?strtoupper($params["province"]):"");
				$emparr["permanent_house_number"] = ((isset($params["permanent_house_number"]))?strtoupper($params["permanent_house_number"]):"");
				$emparr["permanent_street"] = ((isset($params["permanent_street"]))?strtoupper($params["permanent_street"]):"");
				$emparr["permanent_village"] = ((isset($params["permanent_village"]))?strtoupper($params["permanent_village"]):"");
				$emparr["permanent_barangay"] = ((isset($params["permanent_barangay"]))?strtoupper($params["permanent_barangay"]):"");
				$emparr["permanent_municipality"] = ((isset($params["permanent_municipality"]))?strtoupper($params["permanent_municipality"]):"");
				$emparr["permanent_province"] = ((isset($params["permanent_province"]))?strtoupper($params["permanent_province"]):"");
				$emparr["zip_code"] = strtoupper($params["zip_code"]);
				$emparr["permanent_zip_code"] = strtoupper($params["permanent_zip_code"]);
				$emparr["contact_number"] = strtoupper($params["contact_number"]);
				$emparr["email"] = $params["email"];
				$emparr["is_access"] = 0;
				// end of employee table fields
			$this->db->trans_begin();
			// $this->db->where('id', $id)->update($this->table,$emparr);
			// if($this->db->affected_rows() > 0) {
			// if($this->db->where('id', $id)->update($this->table,$emparr)){
				$this->db->where('id', $id)->update($this->table,$emparr);
				$isFam = $this->db->select("*")->from($this->table."familybackground")->where("employee_id",$id)->limit(1)->get()->row_array();
				$isQ = $this->db->select("*")->from($this->table."questions")->where("employee_id",$id)->limit(1)->get()->row_array();
				$isGIds = $this->db->select("*")->from($this->table."governmentid")->where("employee_id",$id)->limit(1)->get()->row_array();
				if(isset($isFam)) $this->db->where('employee_id', $id)->update($this->table.'familybackground', $famarr);
				else $famarr['employee_id'] = $id; $this->db->insert($this->table.'familybackground', $famarr);
				if(isset($isQ)) $this->db->where('employee_id', $id)->update($this->table.'questions', $qarr);
				else $qarr['employee_id'] = $id; $this->db->insert($this->table.'questions', $qarr);
				if(isset($isGIds)) $this->db->where('employee_id', $id)->update($this->table.'governmentid', $govids);
				else $govids['employee_id'] = $id; $this->db->insert($this->table.'governmentid', $govids);

				$this->db->where('employee_id', $id)->delete($this->table.'familybackgroundchildrens');
				if(isset($params["children_name"]) && sizeof($params["children_name"]) > 0){
					foreach ($params["children_name"] as $key => $value) $rows1[] = array("employee_id"=>$id, "children_name"=> strtoupper($value), "children_birthday"=>$params["children_birthday"][$key]);
					$this->db->insert_batch($this->table.'familybackgroundchildrens',$rows1);
				}
				$this->db->where('employee_id', $id)->delete($this->table.'civilserviceeligibility');
				if(isset($params["civil_service_eligibility"]) && sizeof($params["civil_service_eligibility"]) > 0){
					foreach ($params["civil_service_eligibility"] as $key => $value) $rows2[] = array("employee_id"=>$id, "civil_service_eligibility" => strtoupper($value), "rating" => strtoupper($params["rating"][$key]), "date_conferment" => $params["date_conferment"][$key], "place_examination" => strtoupper($params["place_examination"][$key]), "license_number" => strtoupper($params["license_number"][$key]), "license_validity" => $params["license_validity"][$key]);
					$this->db->insert_batch($this->table.'civilserviceeligibility',$rows2);
				}
				$this->db->where('employee_id', $id)->delete($this->table.'workexperience');

				if(isset($params["position"]) && sizeof($params["position"]) > 0){
					foreach ($params["position"] as $key => $value) {
						// $key = sizeof($params["position"]) - $key;
						if(@$params["day_check"][$key]){
							$per_day = 'checked';
						}else{
							$per_day = '';
						}
						if(@$params["work_to"][$key] == 'on' || @$params["work_to"][$key] == 'PRESENT'){
							@$params["work_to"][$key] = 'PRESENT';
						}
						$rows3[] = array("employee_id"=>$id, "work_from" => @$params["work_from"][$key], "work_to" => @$params["work_to"][$key], "position" => strtoupper(@$params["position"][$key]), "company" => strtoupper(@$params["company"][$key]), "monthly_salary" => @$params["monthly_salary"][$key],  "per_day" => $per_day, "grade" => strtoupper(@$params["grade"][$key]), "status_appointment" => strtoupper(@$params["status_appointment"][$key]), "gov_service" => strtoupper(@$params["gov_service"][$key]));
					}


					$this->db->insert_batch($this->table.'workexperience',$rows3);
				}
				$this->db->where('employee_id', $id)->delete($this->table.'voluntarywork');
				if(isset($params["organization"]) && sizeof($params["organization"]) > 0){
					foreach ($params["organization"] as $key => $value) $rows4[] = array("employee_id"=>$id, "organization" => strtoupper(@$value), "organization_work_from" => @$params["organization_work_from"][$key], "organization_work_to" => @$params["organization_work_to"][$key], "organization_number_hours" => @$params["organization_number_hours"][$key], "organization_work_nature" => strtoupper(@$params["organization_work_nature"][$key]));
					$this->db->insert_batch($this->table.'voluntarywork',$rows4);
				}
				$this->db->where('employee_id', $id)->delete($this->table.'learningdevelopment');
				if(isset($params["training"]) && sizeof($params["training"]) > 0){
					foreach ($params["training"] as $key => $value) {
						// $key = sizeof($params["work_from"]) - $key;
						$rows5[] = array("employee_id"=>$id, "training" => strtoupper(@$params["training"][$key]), "traning_from" => @$params["traning_from"][$key], "training_to" => @$params["training_to"][$key], "training_number_hours" => @$params["training_number_hours"][$key], "training_type" => strtoupper(@$params["training_type"][$key]), "training_sponsored_by" => strtoupper(@$params["training_sponsored_by"][$key]));
					}
					$this->db->insert_batch($this->table.'learningdevelopment',$rows5);
				}
				$this->db->where('employee_id', $id)->delete($this->table.'specialskills');
				if(isset($params["special_skills"]) && sizeof($params["special_skills"]) > 0){
					foreach ($params["special_skills"] as $key => $value) $rows6[] = array("employee_id"=>$id, "special_skills" => strtoupper(@$value));
					$this->db->insert_batch($this->table.'specialskills',$rows6);
				}
				$this->db->where('employee_id', $id)->delete($this->table.'recognitions');
				if(isset($params["recognitions"]) && sizeof($params["recognitions"]) > 0){
					foreach ($params["recognitions"] as $key => $value) $rows7[] = array("employee_id"=>$id, "recognitions" =>strtoupper(@$value));
					$this->db->insert_batch($this->table.'recognitions',$rows7);
				}
				$this->db->where('employee_id', $id)->delete($this->table.'organizations');
				if(isset($params["membership"]) && sizeof($params["membership"]) > 0){
					foreach ($params["membership"] as $key => $value) $rows8[] = array("employee_id"=>$id, "organization" => strtoupper(@$value));
					$this->db->insert_batch($this->table.'organizations',$rows8);
				}
				$this->db->where('employee_id', $id)->delete($this->table.'references');
				if(isset($params["reference_name"]) && sizeof($params["reference_name"]) > 0){
					foreach ($params["reference_name"] as $key => $value) $rows9[] = array("employee_id"=>$id, "reference_name" => strtoupper(@$value), "reference_address" => strtoupper(@$params["reference_address"][$key]), "reference_tel_no" => strtoupper(@$params["reference_tel_no"][$key]));
					$this->db->insert_batch($this->table.'references',$rows9);
				}
				$this->db->where('employee_id', $id)->delete($this->table.'educbgelementary');
				if(isset($params["elementary_school"]) && sizeof($params["elementary_school"]) > 0){
					foreach ($params["elementary_school"] as $key => $value) $rows10[] = array("employee_id"=>$id,"school" => strtoupper(@$value),"degree" => strtoupper(@$params["elementary_degree"][$key]),"period_from" => @$params["elementary_period_from"][$key],"period_to" => @$params["elementary_period_to"][$key],"highest_level" => strtoupper(@$params["elementary_highest_level"][$key]),"year_graduated" => $params["elementary_year_graduated"][$key],"received" => strtoupper(@$params["elementary_received"][$key]));
					$this->db->insert_batch($this->table.'educbgelementary',$rows10);
				}
				$this->db->where('employee_id', $id)->delete($this->table.'educbgsecondary');
				if(isset($params["secondary_school"]) && sizeof($params["secondary_school"]) > 0){
					foreach ($params["secondary_school"] as $key => $value) $rows11[] = array("employee_id"=>$id, "school" => strtoupper(@$value),"degree" => strtoupper(@$params["secondary_degree"][$key]),"period_from" => @$params["secondary_period_from"][$key],"period_to" => @$params["secondary_period_to"][$key],"highest_level" => strtoupper(@$params["secondary_highest_level"][$key]),"year_graduated" => $params["secondary_year_graduated"][$key],"received" => strtoupper(@$params["secondary_received"][$key]));
					$this->db->insert_batch($this->table.'educbgsecondary',$rows11);
				}
				$this->db->where('employee_id', $id)->delete($this->table.'educbgvocationals');
				if(isset($params["vocational_school"]) && sizeof($params["vocational_school"]) > 0){
					foreach ($params["vocational_school"] as $key => $value) $rows12[] = array("employee_id"=>$id,"school" => strtoupper(@$value),"degree" => strtoupper(@$params["vocational_degree"][$key]),"period_from" => @$params["vocational_period_from"][$key],"period_to" => @$params["vocational_period_to"][$key],"highest_level" => strtoupper(@$params["vocational_highest_level"][$key]),"year_graduated" => $params["vocational_year_graduated"][$key],"received" => strtoupper(@$params["vocational_received"][$key]));
					$this->db->insert_batch($this->table.'educbgvocationals',$rows12);
				}
				$this->db->where('employee_id', $id)->delete($this->table.'educbgcolleges');
				if(isset($params["college_school"]) && sizeof($params["college_school"]) > 0){
					foreach ($params["college_school"] as $key => $value) $rows13[] = array("employee_id"=>$id,"school" => strtoupper(@$value),"degree" => strtoupper(@$params["college_degree"][$key]),"period_from" => @$params["college_period_from"][$key],"period_to" => @$params["college_period_to"][$key],"highest_level" => strtoupper(@$params["college_highest_level"][$key]),"year_graduated" => $params["college_year_graduated"][$key],"received" => strtoupper(@$params["college_received"][$key]));
					$this->db->insert_batch($this->table.'educbgcolleges',$rows13);
				}
				$this->db->where('employee_id', $id)->delete($this->table.'educbggradstuds');
				if(isset($params["grad_stud_school"]) && sizeof($params["grad_stud_school"]) > 0){
					foreach ($params["grad_stud_school"] as $key => $value) $rows14[] = array("employee_id"=>$id, "school" => strtoupper(@$value),"degree" => strtoupper(@$params["grad_stud_degree"][$key]),"period_from" => @$params["grad_stud_period_from"][$key],"period_to" => @$params["grad_stud_period_to"][$key],"highest_level" => strtoupper(@$params["grad_stud_highest_level"][$key]),"year_graduated" => $params["grad_stud_year_graduated"][$key],"received" => strtoupper(@$params["grad_stud_received"][$key]));
					$this->db->insert_batch($this->table.'educbggradstuds',$rows14);
				}
				
				// $employee_by_id = $this->getEmployeeByFilter($params['id']);
				// foreach ($emparr as $k2 => $v2) {
				// 	if($emparr[$k2] != $employee_by_id[0]->$k2) $this->db->insert($this->table.'updates',array('employee_id'=>$params['id'], 'column_name'=>$k2, 'previous_value'=>$employee_by_id[0][$k2], 'new_value'=>$emparr[$k2], 'modified_by'=>Helper::get('userid')));
				// }

				$update_params = array();
				$add_params = array();
				if(isset($params["file_title"]) && sizeof($params["file_title"]) > 0){
					foreach ($params["file_title"] as $key => $value){
						$arr = array();
						if(isset($params["index_id"][$key])){
							$arr["id"] = $params["index_id"][$key];
							$arr["file_title"] = $value;
							if($params["new_file"]["name"][$key] !== "" ) $arr["uploaded_file"] = $params["new_file"]["name"][$key];
							if($params["new_file"]["type"][$key] !== "" ) $arr["file_type"] = $params["new_file"]["type"][$key];
							if($params["new_file"]["size"][$key] !== 0 ) $arr["file_size"] = $params["new_file"]["size"][$key];
							if($params["cur_file"][$key] !== 0 ) $arr["file_name"] = $params["file_title"][$key].substr(($params["new_file"]["name"][$key] !== "")?$params["new_file"]["name"][$key]:$params["cur_file"][$key],($params["new_file"]["type"][$key] === "image/jpeg") ? -5 : -4);
							$update_params[] = $arr;
						}else{
							$arr["employee_id"] = $params["id"];
							$arr["file_title"] = $value;
							if($params["new_file"]["name"][$key] !== "" ) $arr["uploaded_file"] = $params["new_file"]["name"][$key];
							if($params["new_file"]["type"][$key] !== "" ) $arr["file_type"] = $params["new_file"]["type"][$key];
							if($params["new_file"]["size"][$key] !== 0 ) $arr["file_size"] = $params["new_file"]["size"][$key];
							$arr["file_name"] = $params["file_title"][$key].substr($params["new_file"]["name"][$key],($params["new_file"]["type"][$key] === "image/jpeg") ? -5 : -4);
							$add_params[] = $arr;
						}
					}
				}
				if(isset($params["file_title"]) && sizeof($params["file_title"]) > 0){
					foreach ($params["file_title"] as $key => $value){
						$arr = array();
						if(isset($params["index_id"][$key]) && $params["index_id"][$key] != ""){
							$arr["id"] = $params["index_id"][$key];
							$arr["file_title"] = $value;
							if($params["new_file"]["name"][$key] !== "" ) $arr["uploaded_file"] = $params["new_file"]["name"][$key];
							if($params["new_file"]["type"][$key] !== "" ) $arr["file_type"] = $params["new_file"]["type"][$key];
							if($params["new_file"]["size"][$key] !== 0 ) $arr["file_size"] = $params["new_file"]["size"][$key];
							if($params["new_file"]["size"][$key] > 0 ) $arr["file_name"] = $params["new_file"]["name"][$key];
							$update_params[] = $arr;
						}else{
							$arr["employee_id"] = $params["id"];
							$arr["file_title"] = $value;
							if($params["new_file"]["name"][$key] !== "" ) $arr["uploaded_file"] = $params["new_file"]["name"][$key];
							if($params["new_file"]["type"][$key] !== "" ) $arr["file_type"] = $params["new_file"]["type"][$key];
							if($params["new_file"]["size"][$key] !== 0 ) $arr["file_size"] = $params["new_file"]["size"][$key];
							$arr["file_name"] = $params["new_file"]["name"][$key];
							$add_params[] = $arr;
						}
					}
					$this->db->where_not_in('id', $params["index_id"])->where("employee_id",$params["id"])->delete($this->table.'attatchments');
					$this->db->update_batch($this->table.'attatchments',$update_params,"id");
					if(sizeof($add_params)>0) $this->db->insert_batch($this->table.'attatchments',$add_params);
				}
				
				//Remove existing photo
				$this->db->where('employee_id', $id);
				$this->db->delete($this->table.'photos');
				//Insert photo
				$params2 = array("employee_id" => $id, "employee_id_photo" => $_POST['employee_id_photo'] );
				$this->db->insert($this->table.'photos', $params2);

				if($this->db->trans_status() === TRUE){
					$code = "0";
					$message = "Successfully updated employee.";
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message);
					$this->db->trans_commit();
					return true;		
				} else {
					$this->db->trans_rollback();
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message);
				}
			// }
			return false;
		}

		public function updateRowsBackup($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Employee failed to update pds.";
			$id = $params["id"];
			$rows1 = $rows2 = $rows3 = $rows4 = $rows5 = $rows6 = $rows7 = $rows8 = $rows9 = $row10 = $row11 = $row12 = $govids = $qarr = $emparr = $educarr = $famarr = array();
			

			


			// additional table family background
				$famarr["spouse_last_name"] = $params["spouse_last_name"];
				$famarr["spouse_first_name"] = $params["spouse_first_name"];
				$famarr["spouse_extension"] = $params["spouse_extension"];
				$famarr["spouse_middle_name"] = $params["spouse_middle_name"];
				$famarr["spouse_occupation"] = $params["spouse_occupation"];
				$famarr["spouse_employer_name"] = $params["spouse_employer_name"];
				$famarr["spouse_business_address"] = $params["spouse_business_address"];
				$famarr["spouse_tel_no"] = $params["spouse_tel_no"];
				$famarr["father_last_name"] = $params["father_last_name"];
				$famarr["father_first_name"] = $params["father_first_name"];
				$famarr["father_extension"] = $params["father_extension"];
				$famarr["father_middle_name"] = $params["father_middle_name"];
				$famarr["mother_last_name"] = $params["mother_last_name"];
				$famarr["mother_first_name"] = $params["mother_first_name"];
				$famarr["mother_extension"] = $params["mother_extension"];
				$famarr["mother_middle_name"] = $params["mother_middle_name"];
				// end of additional table family background
				// additional table govid
				$govids['gov_issued_id'] = $params["gov_issued_id"];
				$govids['valid_id'] = $params["valid_id"];
				$govids['place_issuance'] = $params["place_issuance"];
				//end of additional table govid
				// additional table for questions
				$qarr['employee_id'] = $id;
				$qarr["radio_input_01"] = $params["radio_input_01"];
				// $qarr["if_yes_01"] = (($params["radio_input_01"] == "Yes")?$params["if_yes_01"]:"");
				$qarr["radio_input_02"] = $params["radio_input_02"];
				$qarr["if_yes_02"] = (($params["radio_input_02"] == "Yes")?$params["if_yes_02"]:"");
				$qarr["radio_input_03"] = $params["radio_input_03"];
				$qarr["if_yes_03"] = (($params["radio_input_03"] == "Yes")?$params["if_yes_03"]:"");
				$qarr["radio_input_04"] = $params["radio_input_04"];
				$qarr["if_yes_04"] = (($params["radio_input_04"] == "Yes")?$params["if_yes_04"]:"");
				$qarr["if_yes_case_status_04"] = (($params["radio_input_04"] == "Yes")?$params["if_yes_case_status_04"]:"");
				$qarr["radio_input_05"] = $params["radio_input_05"];
				$qarr["if_yes_05"] = (($params["radio_input_05"] == "Yes")?$params["if_yes_05"]:"");
				$qarr["radio_input_06"] = $params["radio_input_06"];
				$qarr["if_yes_06"] = (($params["radio_input_06"] == "Yes")?$params["if_yes_06"]:"");
				$qarr["radio_input_07"] = $params["radio_input_07"];
				$qarr["if_yes_07"] = (($params["radio_input_07"] == "Yes")?$params["if_yes_07"]:"");
				$qarr["radio_input_08"] = $params["radio_input_08"];
				$qarr["if_yes_08"] = (($params["radio_input_08"] == "Yes")?$params["if_yes_08"]:"");
				$qarr["radio_input_09"] = $params["radio_input_09"];
				$qarr["if_yes_09"] = (($params["radio_input_09"] == "Yes")?$params["if_yes_09"]:"");
				$qarr["radio_input_10"] = $params["radio_input_10"];
				$qarr["if_yes_10"] = (($params["radio_input_10"] == "Yes")?$params["if_yes_10"]:"");
				$qarr["radio_input_11"] = $params["radio_input_11"];
				$qarr["if_yes_11"] = (($params["radio_input_11"] == "Yes")?$params["if_yes_11"]:"");
				$qarr["radio_input_12"] = $params["radio_input_12"];
				$qarr["if_yes_12"] = (($params["radio_input_12"] == "Yes")?$params["if_yes_12"]:"");
				// end of additional table for questions
				// employee table fields
				// additional employee fields
				$emparr["bloodtype"] = $params["bloodtype"];
				$emparr["sss"] = str_replace("_","0",$params["sss"]);
				$emparr["ageny_employee_no"] = $params["ageny_employee_no"];
				$emparr["tel_no"] = $params["tel_no"];
				// end of additional employee fields
				$emparr['employee_id_number'] = Helper::encrypt($params['employee_id_number'],$id);
				$emparr['employee_number'] = Helper::encrypt($params['employee_number'],$id);
				$emparr['first_name'] = Helper::encrypt($params['first_name'],$id);
				$emparr['middle_name'] = Helper::encrypt($params['middle_name'],$id);
				$emparr['last_name'] = Helper::encrypt($params['last_name'],$id);
				$emparr["extension"] = Helper::encrypt($params['extension'],$id);;
				$emparr['modified_by'] = Helper::get('userid');
				$emparr["division_id"] = $params["division_id"];
				$emparr["birthday"] = "11/11/2011";//$params["birthday"];
				$emparr["birth_place"] = $params["birth_place"];
				$emparr["gender"] = $params["gender"];
				$emparr["civil_status"] = $params["civil_status"];
				$emparr["civil_status_others"] = (($params["civil_status"] == "Others")?$params["civil_status_others"]:"");
				$emparr["height"] = $params["height"];
				$emparr["weight"] = $params["weight"];
				$emparr["gsis"] = str_replace("_","0",$params["gsis"]);
				$emparr["pagibig"] = str_replace("_","0",$params["pagibig"]);
				$emparr["philhealth"] = str_replace("_","0",$params["philhealth"]);
				$emparr["tin"] = str_replace("_","0",$params["tin"]);
				$emparr["nationality"] = $params["nationality"];
				$emparr["nationality_details"] = @$params["nationality_details"];
				$emparr["nationality_country"] = (($params["nationality"] != "Filipino")?$params["nationality_country"]:"");
				$emparr["house_number"] = ((isset($params["house_number"]))?$params["house_number"]:"");
				$emparr["street"] = ((isset($params["street"]))?$params["street"]:"");
				$emparr["village"] = ((isset($params["village"]))?$params["village"]:"");
				$emparr["barangay"] = ((isset($params["barangay"]))?$params["barangay"]:"");
				$emparr["municipality"] = ((isset($params["municipality"]))?$params["municipality"]:"");
				$emparr["province"] = ((isset($params["province"]))?$params["province"]:"");
				$emparr["permanent_house_number"] = ((isset($params["permanent_house_number"]))?$params["permanent_house_number"]:"");
				$emparr["permanent_street"] = ((isset($params["permanent_street"]))?$params["permanent_street"]:"");
				$emparr["permanent_village"] = ((isset($params["permanent_village"]))?$params["permanent_village"]:"");
				$emparr["permanent_barangay"] = ((isset($params["permanent_barangay"]))?$params["permanent_barangay"]:"");
				$emparr["permanent_municipality"] = ((isset($params["permanent_municipality"]))?$params["permanent_municipality"]:"");
				$emparr["permanent_province"] = ((isset($params["permanent_province"]))?$params["permanent_province"]:"");
				$emparr["zip_code"] = $params["zip_code"];
				$emparr["permanent_zip_code"] = $params["permanent_zip_code"];
				$emparr["contact_number"] = $params["contact_number"];
				$emparr["email"] = $params["email"];
				$emparr["is_access"] = 1;
				// end of employee table fields
			// try{
			// 	$this->db->where('id', $id);
			// 	$this->db->update($this->table,$emparr);
			// }catch(Exception $e) {
  			// 			echo 'Message: ' .$e->getMessage(); die();
			// }


			$this->db->trans_begin();
			$this->db->where('id', $id);
			$this->db->update($this->table,$emparr);

			//if($this->db->affected_rows() > 0) {

				// $this->db->where('employee_id', $id)->update($this->table.'educationalbackground', $educarr);
				$this->db->where('employee_id', $id)->update($this->table.'familybackground', $famarr);
				$this->db->where('employee_id', $id)->update($this->table.'questions', $qarr);
				$this->db->where('employee_id', $id)->update($this->table.'governmentid', $govids);

				$this->db->where('employee_id', $id)->delete($this->table.'familybackgroundchildrens');
				if(isset($params["children_name"]) && sizeof($params["children_name"]) > 0 && $params["children_name"][0] != null && $params["children_name"][0] != ""){
					foreach ($params["children_name"] as $key => $value) $rows1[] = array("employee_id"=>$id, "children_name"=> $value, "children_birthday"=>$params["children_birthday"][$key]);
					$this->db->insert_batch($this->table.'familybackgroundchildrens',$rows1);
				}
				$this->db->where('employee_id', $id)->delete($this->table.'civilserviceeligibility');
				if(isset($params["civil_service_eligibility"]) && sizeof($params["civil_service_eligibility"]) > 0 && $params["civil_service_eligibility"][0] != null && $params["civil_service_eligibility"][0] != ""){
					foreach ($params["civil_service_eligibility"] as $key => $value) $rows2[] = array("employee_id"=>$id, "civil_service_eligibility" => $value, "rating" => $params["rating"][$key], "date_conferment" => $params["date_conferment"][$key], "place_examination" => $params["place_examination"][$key], "license_number" => $params["license_number"][$key], "license_validity" => $params["license_validity"][$key]);
					$this->db->insert_batch($this->table.'civilserviceeligibility',$rows2);
				}
				$this->db->where('employee_id', $id)->delete($this->table.'workexperience');
				if(isset($params["work_from"]) && sizeof($params["work_from"]) > 0 && $params["work_from"][0] != null && $params["work_from"][0] != ""){
					foreach ($params["work_from"] as $key => $value) $rows3[] = array("employee_id"=>$id, "work_from" => $value, "work_to" => $params["work_to"][$key], "position" => $params["position"][$key], "company" => $params["company"][$key], "monthly_salary" => $params["monthly_salary"][$key], "grade" => $params["grade"][$key], "status_appointment" => $params["status_appointment"][$key], "gov_service" => $params["gov_service"][$key]);
					$this->db->insert_batch($this->table.'workexperience',$rows3);
				}
				$this->db->where('employee_id', $id)->delete($this->table.'voluntarywork');
				if(isset($params["organization"]) && sizeof($params["organization"]) > 0 && $params["organization"][0] != null && $params["organization"][0] != ""){
					foreach ($params["organization"] as $key => $value) $rows4[] = array("employee_id"=>$id, "organization" => $value, "organization_work_from" => $params["organization_work_from"][$key], "organization_work_to" => $params["organization_work_to"][$key], "organization_number_hours" => $params["organization_number_hours"][$key], "organization_work_nature" => $params["organization_work_nature"][$key]);
					$this->db->insert_batch($this->table.'voluntarywork',$rows4);
				}
				$this->db->where('employee_id', $id)->delete($this->table.'learningdevelopment');
				if(isset($params["training"]) && sizeof($params["training"]) > 0 && $params["training"][0] != null && $params["training"][0] != ""){
					foreach ($params["training"] as $key => $value) $rows5[] = array("employee_id"=>$id, "training" => $value, "traning_from" => $params["traning_from"][$key], "training_to" => $params["training_to"][$key], "training_number_hours" => $params["training_number_hours"][$key], "training_type" => $params["training_type"][$key], "training_sponsored_by" => $params["training_sponsored_by"][$key]);
					$this->db->insert_batch($this->table.'learningdevelopment',$rows5);
				}
				$this->db->where('employee_id', $id)->delete($this->table.'specialskills');
				// var_dump(json_encode()$params); die();
				if(isset($params["special_skills"]) && sizeof($params["special_skills"]) > 0){
					foreach ($params["special_skills"] as $key => $value) $rows6[] = array("employee_id"=>$id, "special_skills" => $value);
					$this->db->insert_batch($this->table.'specialskills',$rows6);
				}
				$this->db->where('employee_id', $id)->delete($this->table.'recognitions');
				if(isset($params["recognitions"]) && sizeof($params["recognitions"]) > 0){
					foreach ($params["recognitions"] as $key => $value) $rows7[] = array("employee_id"=>$id, "recognitions" => $value);
					$this->db->insert_batch($this->table.'recognitions',$rows7);
				}
				$this->db->where('employee_id', $id)->delete($this->table.'organizations');
				if(isset($params["membership"]) && sizeof($params["membership"]) > 0){
					foreach ($params["membership"] as $key => $value) $rows8[] = array("employee_id"=>$id, "organization" => $value);
					$this->db->insert_batch($this->table.'organizations',$rows8);
				}
				$this->db->where('employee_id', $id)->delete($this->table.'references');
				if(isset($params["reference_name"]) && sizeof($params["reference_name"]) > 0){
					foreach ($params["reference_name"] as $key => $value) $rows9[] = array("employee_id"=>$id, "reference_name" => $value, "reference_address" => $params["reference_address"][$key], "reference_tel_no" => $params["reference_tel_no"][$key]);
					$this->db->insert_batch($this->table.'references',$rows9);
				}
				$this->db->where('employee_id', $id)->delete($this->table.'educbgelementary');
				if(isset($params["elementary_school"]) && sizeof($params["elementary_school"]) > 0){
					foreach ($params["elementary_school"] as $key => $value) $rows10[] = array("employee_id"=>$id,"school" => $value,"degree" => $params["elementary_degree"][$key],"period_from" => $params["elementary_period_from"][$key],"period_to" => $params["elementary_period_to"][$key],"highest_level" => $params["elementary_highest_level"][$key],"year_graduated" => $params["elementary_year_graduated"][$key],"received" => $params["elementary_received"][$key]);
					$this->db->insert_batch($this->table.'educbgelementary',$rows10);
				}
				$this->db->where('employee_id', $id)->delete($this->table.'educbgsecondary');
				if(isset($params["secondary_school"]) && sizeof($params["secondary_school"]) > 0){
					foreach ($params["secondary_school"] as $key => $value) $rows11[] = array("employee_id"=>$id, "school" => $value,"degree" => $params["secondary_degree"][$key],"period_from" => $params["secondary_period_from"][$key],"period_to" => $params["secondary_period_to"][$key],"highest_level" => $params["secondary_highest_level"][$key],"year_graduated" => $params["secondary_year_graduated"][$key],"received" => $params["secondary_received"][$key]);
					$this->db->insert_batch($this->table.'educbgsecondary',$rows11);
				}
				$this->db->where('employee_id', $id)->delete($this->table.'educbgvocationals');
				if(isset($params["vocational_school"]) && sizeof($params["vocational_school"]) > 0){
					foreach ($params["vocational_school"] as $key => $value) $rows12[] = array("employee_id"=>$id,"school" => $value,"degree" => $params["vocational_degree"][$key],"period_from" => $params["vocational_period_from"][$key],"period_to" => $params["vocational_period_to"][$key],"highest_level" => $params["vocational_highest_level"][$key],"year_graduated" => $params["vocational_year_graduated"][$key],"received" => $params["vocational_received"][$key]);
					$this->db->insert_batch($this->table.'educbgvocationals',$rows12);
				}
				$this->db->where('employee_id', $id)->delete($this->table.'educbgcolleges');
				if(isset($params["college_school"]) && sizeof($params["college_school"]) > 0){
					foreach ($params["college_school"] as $key => $value) $rows13[] = array("employee_id"=>$id,"school" => $value,"degree" => $params["college_degree"][$key],"period_from" => $params["college_period_from"][$key],"period_to" => $params["college_period_to"][$key],"highest_level" => $params["college_highest_level"][$key],"year_graduated" => $params["college_year_graduated"][$key],"received" => $params["college_received"][$key]);
					$this->db->insert_batch($this->table.'educbgcolleges',$rows13);
				}
				$this->db->where('employee_id', $id)->delete($this->table.'educbggradstuds');
				if(isset($params["grad_stud_school"]) && sizeof($params["grad_stud_school"]) > 0){
					foreach ($params["grad_stud_school"] as $key => $value) $rows14[] = array("employee_id"=>$id, "school" => $value,"degree" => $params["grad_stud_degree"][$key],"period_from" => $params["grad_stud_period_from"][$key],"period_to" => $params["grad_stud_period_to"][$key],"highest_level" => $params["grad_stud_highest_level"][$key],"year_graduated" => $params["grad_stud_year_graduated"][$key],"received" => $params["grad_stud_received"][$key]);
					$this->db->insert_batch($this->table.'educbggradstuds',$rows14);
				}
				//Remove existing photo
				$this->db->where('employee_id', $id);
				$this->db->delete($this->table.'photos');
				//Insert photo
				$params2 = array("employee_id" => $id, "employee_id_photo" => $_POST['employee_id_photo'] );
				$this->db->insert($this->table.'photos', $params2);

				$raw_employee_by_id = $this->getEmployeeByFilter(array('id'=>$params['id']));
				$employee_by_id  = json_decode( json_encode($raw_employee_by_id), true);

				// var_dump(json_decode( json_encode($employee_by_id), true)); die();

				foreach ($emparr as $k2 => $v2) {
					if($emparr[$k2] != $employee_by_id[0][$k2]) $this->db->insert($this->table.'updates',array('employee_id'=>$params['id'], 'column_name'=>$k2, 'previous_value'=>$employee_by_id[0][$k2], 'new_value'=>$emparr[$k2], 'modified_by'=>Helper::get('userid')));
				}

				$update_params = array();
				$add_params = array();
				if(isset($params["file_title"]) && sizeof($params["file_title"]) > 0){
					foreach ($params["file_title"] as $key => $value){
						$arr = array();
						if(isset($params["index_id"][$key])){
							$arr["id"] = $params["index_id"][$key];
							$arr["file_title"] = $value;
							if($params["new_file"]["name"][$key] !== "" ) $arr["uploaded_file"] = $params["new_file"]["name"][$key];
							if($params["new_file"]["type"][$key] !== "" ) $arr["file_type"] = $params["new_file"]["type"][$key];
							if($params["new_file"]["size"][$key] !== 0 ) $arr["file_size"] = $params["new_file"]["size"][$key];
							if($params["cur_file"][$key] !== 0 ) $arr["file_name"] = $params["file_title"][$key].substr(($params["new_file"]["name"][$key] !== "")?$params["new_file"]["name"][$key]:$params["cur_file"][$key],($params["new_file"]["type"][$key] === "image/jpeg") ? -5 : -4);
							$update_params[] = $arr;
						}else{
							$arr["employee_id"] = $params["id"];
							$arr["file_title"] = $value;
							if($params["new_file"]["name"][$key] !== "" ) $arr["uploaded_file"] = $params["new_file"]["name"][$key];
							if($params["new_file"]["type"][$key] !== "" ) $arr["file_type"] = $params["new_file"]["type"][$key];
							if($params["new_file"]["size"][$key] !== 0 ) $arr["file_size"] = $params["new_file"]["size"][$key];
							$arr["file_name"] = $params["file_title"][$key].substr($params["new_file"]["name"][$key],($params["new_file"]["type"][$key] === "image/jpeg") ? -5 : -4);
							$add_params[] = $arr;
						}
					}
				}
				$this->db->where_not_in('id', $params["index_id"])->where("employee_id",$params["id"])->delete($this->table.'attatchments');
				$this->db->update_batch($this->table.'attatchments',$update_params,"id");
				if(sizeof($add_params)>0) $this->db->insert_batch($this->table.'attatchments',$add_params);

				if($this->db->trans_status() === TRUE){
					$code = "0";
					$message = "Successfully updated employee pds.";
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message);
					$this->db->trans_commit();
					return true;		
				} else {
					$this->db->trans_rollback();
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message);
				}
			//}
			// return false;
		}

		public function hasRowsPhotos($employee_id){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$this->sql = " CALL sp_employee_photo_details(?)";
			$query = $this->db->query($this->sql,array($employee_id));
			$result = $query->result();
			mysqli_next_result($this->db->conn_id);
			$data['details'] = $result;
			if(sizeof($result) > 0){
				$code = "0";
				$message = "Successfully fetched photo.";
				$this->ModelResponse($code, $message, $data);
				// $helperDao->auditTrails(Helper::get('userid'),$message);
				return true;		
			}else {
				$this->ModelResponse($code, $message);
				// $helperDao->auditTrails(Helper::get('userid'),$message);
			}
			return false;
		}

		public function getEmployeeByFilter($filter){
			// var_dump($filter); die();
			$this->sql = "CALL sp_get_employee_by_filter(?)";
			$query = $this->db->query($this->sql,array($filter));
			$rows = $query->result();
			mysqli_next_result($this->db->conn_id);
			return $rows;
		}

	}
?>