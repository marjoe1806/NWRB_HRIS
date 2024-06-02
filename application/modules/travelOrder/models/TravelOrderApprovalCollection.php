<?php
	class TravelOrderApprovalCollection extends Helper {
		var $select_column = null; 
		var $sql = "";
		var $selectRequestParams = array();
		public function __construct() {
			$this->load->model('Helper');
			$this->load->model('HelperDao');
			ModelResponse::busy();
		}

		var $table = "tbltravelorder";
      	var $order_column = array('','a.travel_order_no','duration','last_name','location','officialpurpose','a.division','a.status');
      	public function getColumns(){
      		$sql = " SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='".$this->table."' AND TABLE_SCHEMA='".$this->db->database."' ";
			$query = $this->db->query($sql);
			$rows = $query->result_array();
			return $rows;
      	}
		function make_query(){
			$this->select_column[] = 'a.travel_order_no';
			$this->select_column[] = 'c.last_name';
			$this->select_column[] = 'c.first_name';
			$this->select_column[] = 'c.middle_name';
			$this->select_column[] = 'a.duration';
			$this->select_column[] = 'a.driver';
			$this->select_column[] = 'a.location';
			$this->select_column[] = 'a.officialpurpose';
			$this->select_column[] = 'b.department_name';
			$this->select_column[] = 'a.status';
			
			$this->db->select(
				'DISTINCT 
				(SELECT COUNT(*) FROM tbltravelorderapprover e LEFT JOIN tblemployees b ON e.employee_id = b.id where b.employment_status = "Active" AND e.is_active = 1 AND e.approver = "'.$_SESSION["id"].'" AND e.employee_id = a.employee_id AND e.approve_type = 4) as for_driver, 
				(SELECT COUNT(*) FROM tbltravelorderapprover e LEFT JOIN tblemployees b ON e.employee_id = b.id where b.employment_status = "Active" AND e.is_active = 1 AND e.approver = "'.$_SESSION["id"].'" AND e.employee_id = a.employee_id AND e.approve_type = 3) as approval,
				(SELECT COUNT(*) FROM tbltravelorderapprover e LEFT JOIN tblemployees b ON e.employee_id = b.id where b.employment_status = "Active" AND e.is_active = 1 AND e.approver = "'.$_SESSION["id"].'" AND e.employee_id = a.employee_id AND e.approve_type = 2) as certify,
				(SELECT COUNT(*) FROM tbltravelorderapprover e LEFT JOIN tblemployees b ON e.employee_id = b.id where b.employment_status = "Active" AND e.is_active = 1 AND e.approver = "'.$_SESSION["id"].'" AND e.employee_id = a.employee_id AND e.approve_type = 1) as division_head,
				(SELECT COUNT(*) FROM tbltravelorderapprover e LEFT JOIN tblemployees b ON e.employee_id = b.id where b.employment_status = "Active" AND e.is_active = 1 AND e.approver = "'.$_SESSION["id"].'" AND e.employee_id = a.employee_id AND e.approve_type = 0) as section_head,
				a.*,a.status,b.department_name,
				DECRYPTER(c.first_name,"sunev8clt1234567890",c.id) as first_name,
				DECRYPTER(c.middle_name,"sunev8clt1234567890",c.id) as middle_name,
				DECRYPTER(c.last_name,"sunev8clt1234567890",c.id) as last_name,
				DECRYPTER(c.extension,"sunev8clt1234567890",c.id) as extension');
		    $this->db->from($this->table." a");
			$this->db->join("tblfielddepartments b", "a.division_id = b.id","left");
			$this->db->join("tblemployees c", "a.employee_id = c.id","left");
			// $this->db->join("tbltravelorderapprover d", "a.status = d.approve_type","left");
		    // $this->db->join("tbltravelorderapprover d", "a.employee_id = d.employee_id","left");
		    // $this->db->join("tbltravelapprovetype e", "d.approve_type = e.id","left");			
			// $joinsql = "d.employee_id = c.id AND a.status = d.approve_type AND d.is_active = 1";
			// $this->db->join("tbltravelorderapprover d", $joinsql,"left");
			// $joinsql1 = "e.id = a.status AND e.id = d.approve_type AND d.is_active = 1";
			// $this->db->join("tbltravelapprovetype e", $joinsql1,"left");
		    // $this->db->where("d.approve_type",'a.status');
		    // $this->db->where("d.is_active",'1');
			
			$joinsql = "d.employee_id = c.id  AND d.is_active = 1";
			$this->db->join("tbltravelorderapprover d", $joinsql,"left");

    		//For search
			// if(isset($_GET["search"]["value"])){
			// 	$this->db->group_start();
			// 	 foreach ($this->select_column as $key => $value) {
			// 		 $this->db->or_like($value, $_GET["search"]["value"]);
			// 	 }
			// 	$this->db->group_end();
			// }
			if(isset($_GET["search"]["value"])){
				$this->db->group_start();
				foreach ($this->select_column as $key => $value) {
					if($value == "c.first_name" || $value == "c.last_name" || $value == "c.middle_name" || $value == "c.extension")  {
						$this->db->or_like("CONCAT(DECRYPTER(c.last_name,'sunev8clt1234567890',c.id),' ',DECRYPTER(c.extension,'sunev8clt1234567890',c.id),', ',DECRYPTER(c.first_name,'sunev8clt1234567890',c.id),' ',DECRYPTER(c.middle_name,'sunev8clt1234567890',c.id))",$_GET["search"]["value"]); 
					}else{
						$this->db->or_like($value, $_GET["search"]["value"]);  
					}
				}
				$this->db->group_end();
				
			}
			$this->db->where("d.approver",$_SESSION["id"]);
			// end
			//for order column
			if($_GET["status"] != "") $this->db->where("a.status",$_GET["status"]);
		    if(isset($_GET["order"])){
				$this->db->order_by($this->order_column[$_GET['order']['0']['column']], $_GET['order']['0']['dir']);
		    }else{
				$this->db->order_by("a.date_created", 'DESC');				
				$this->db->where("a.status !=", null);
		    }
			// end
		}
		
		function make_datatables(){
		    $this->make_query();
			if($_GET["length"] != -1) $this->db->limit($_GET['length'], $_GET['start']);
		    $query = $this->db->get();
		    // var_dump($this->db->last_query($query)); die();
		    return $query->result();
		}

		function get_filtered_data(){
			$this->make_query();
			$query = $this->db->get();
		    return $query->num_rows();
		}
		function get_all_data(){
		    $this->db->select($this->table."*");
			$this->db->from($this->table);
			if($_GET["status"] != "") $this->db->where($this->table.".status",$_GET["status"]);
		    return $this->db->count_all_results();
		}

		public function updateTravelOrder($params){
			// print_r($params); die();
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Travel order failed to update.";

			$params_travel = array(

					'division_id' => $params['division_id'],
					'employee_id' => $params['employee_id'],
					'location' => $params['location'],
					// 'destination' => $params['destination'],
					'purpose' => $params['purpose'],
					'reason' => $params['reason'],
					'duration' => $params['duration'],
					'travel_order_no' => $params['travel_order_no'],
					'driver' => $params['driver'],
					'vehicle_no' => $params['vehicle_no'],
					'no_days' => $params['no_days'],
					'officialpurpose' => $params['officialpurpose'],
					'modified_by' => $_SESSION["id"]
				);

				// var_dump($params_travel).die();

			$this->db->where('id', $params['id']);
			$this->db->update('tbltravelorder',$params_travel);

			$params_travel2 = array(

					'location' => $params['location'],
					// 'destination' => $params['destination'],
					'purpose' => $params['purpose'],
					'reason' => $params['reason'],
					'duration' => $params['duration'],
					'travel_order_no' => $params['travel_order_no'],
					'driver' => $params['driver'],
					'vehicle_no' => $params['vehicle_no'],
					'no_days' => $params['no_days'],
					'officialpurpose' => $params['officialpurpose'],
					'modified_by' => $_SESSION["id"]
				);
			// print_r($params_travel2); die();
			$this->db->where('travel_id', $params['travel_id']);
			if ($this->db->update('tbltravelorder',$params_travel2) === FALSE){
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}	
			else {
				$code = "0";
				$message = "Successfully updated travel order.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;	
			}
			return false;
		}

		public function firstRecommendation($params){			
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Travel order failed to approve.";
			$this->db->trans_begin();
			$last_approver_name = $_SESSION['last_name'].", ".$_SESSION['middle_name']." ".$_SESSION['first_name'];
			$sql_data = array(1, $params['travel_id']);
			//var_dump($sql_data);die();
			$sql = "UPDATE tbltravelorder SET status = ?  WHERE travel_id = ? ";
			$this->db->query($sql,$sql_data);
			if($this->db->trans_status() === TRUE){
				$this->db->trans_commit();
				$code = "0";
				$message = "Successfully approved travel order.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;
			} else{
				$this->db->trans_rollback();
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}


		public function secondRecommendation($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Travel order failed to approve.";
			$this->db->trans_begin();

			$last_approver_name = $_SESSION["first_name"] ." ". $_SESSION["last_name"];
            if($_SESSION["middle_name"]){
				$last_approver_name = $_SESSION["first_name"] ." ". mb_substr($_SESSION["middle_name"], 0, 1) .". ". $_SESSION["last_name"];
            }

			$date = date('Y-m-d H:i:s');

			$section_head = false;
			$division_head = false;
			$deputy = false;

			if($this->fetchTravelOrderApprovals($params['employee_id'])) {
				$res = new ModelResponse($this->getCode(), $this->getMessage(), $this->getData());
				$approvers = json_decode($res,true);

				if($approvers['Code'] == "0"){
					$app = $approvers['Data']['approvers'];

					foreach ($app as $k => $v) {
						$id = $v['id'];
						$approve_type = $v['approve_type'];

						if($approve_type == "0"){
							$section_head = true;
						}
						if($approve_type == "1"){
							$division_head = true;
						}

						if($approve_type == "2"){
							$deputy = true;
						}
					}
				}
			}

			$status = 2;
			if($division_head && !$deputy){
				$status = 3;
			}

			$sql_data = array($status,$last_approver_name,$params['file_name'],$date,$params['travel_id']);
			$sql = "UPDATE tbltravelorder SET status = ? , prev_recomm_name = ?, division_head_sign = ?, division_head_sign_date = ? WHERE travel_id = ? ";
			$this->db->query($sql,$sql_data);
			if($this->db->trans_status() === TRUE){
				$this->db->trans_commit();
				$code = "0";
				$message = "Successfully approved travel order.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;
			} else{
				$this->db->trans_rollback();
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		public function forCertification($params){
			//var_dump(json_encode($params)); die();
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Travel order failed to approve.";
			$this->db->trans_begin();
			// $last_approver_name = $_SESSION['last_name'].", ".$_SESSION['middle_name']." ".$_SESSION['first_name'];
			$last_approver_name = $_SESSION["first_name"] ." ". $_SESSION["last_name"];
            if($_SESSION["middle_name"]){
				$last_approver_name = $_SESSION["first_name"] ." ". mb_substr($_SESSION["middle_name"], 0, 1) .". ". $_SESSION["last_name"];
            }

			// $sql_data = array(3,$last_approver_name,$params['file_name'],$params['id']);
			$sql_data = array(3,$last_approver_name,$params['travel_id']);
			//var_dump($sql_data);die();
			// $sql = "UPDATE tbltravelorder SET status = ? , sec_recomm_name = ?, deputy_sign = ? WHERE id = ? ";
			$sql = "UPDATE tbltravelorder SET status = ? , sec_recomm_name = ? WHERE travel_id = ? ";
			$this->db->query($sql,$sql_data);
			if($this->db->trans_status() === TRUE){
				$this->db->trans_commit();
				$code = "0";
				$message = "Successfully approved travel order.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;
			} else{
				$this->db->trans_rollback();
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}
		
		public function forApproval($params){
			$year = date ( "Y" );
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Travel order failed to approve.";
		
			$id = $params['id'];
			$travel_id = $params['travel_id'];
			//var_dump($travel_id);die();
			$vehicle = $this->db->query("SELECT * FROM tbltravelorder WHERE id = $id")->row_array();			

			$last_approver_name = $_SESSION["first_name"] ." ". $_SESSION["last_name"];
            if($_SESSION["middle_name"]){
				$last_approver_name = $_SESSION["first_name"] ." ". mb_substr($_SESSION["middle_name"], 0, 1) .". ". $_SESSION["last_name"];
            }
			if($_SESSION["extension"]){
				$last_approver_name = $_SESSION["first_name"] ." ". mb_substr($_SESSION["middle_name"], 0, 1) .". ". $_SESSION["last_name"]." ". $_SESSION["extension"];
            }
			
			if($vehicle['is_vehicle'] == NULL){
				$sql_data = array(5,$last_approver_name,$params['file_name'],$params['travel_id']);		
			}
			elseif($vehicle['is_vehicle']  == 3){
				$sql_data = array(5,$last_approver_name,$params['file_name'],$params['travel_id']);
			}else{
				$sql_data = array(4,$last_approver_name,$params['file_name'],$params['travel_id']);	
			}

			$query = $this->db->query("SELECT `travel_order_no` FROM tbltravelorder WHERE travel_order_no != '' ORDER BY `id` DESC LIMIT 1");
			$result = $query->row_array();
			// var_dump($result);
			// die();


			if (!empty($result)) {
				$travel_order_no = $result;
				$exploded_values = explode('-', $result['travel_order_no']); // Replace ',' with the actual delimiter used in your data
			
			
					//$travel_order = $result;
					// $split_result = explode('-', $result['travel_order_no']);
					//var_dump($split_result[2] + 1);die();
					//$last_TO_add_one = intval($split_result[2]) + 1;		

					//var_dump(intval($exploded_values[2]) + 1);die();
					$last_TO_add_one = intval($exploded_values[2]) + 1;
					$to_num = "TO-".$year."-".'0'.$last_TO_add_one;			
		
					$param = array($to_num,$travel_id);
					$set_to_num = "UPDATE tbltravelorder SET travel_order_no = ? WHERE travel_id = ? ";
					$this->db->query($set_to_num,$param);
		
					$sql = "UPDATE tbltravelorder SET status = ? , last_approve_name = ?, director_sign = ? WHERE travel_id = ? ";
					$this->db->query($sql,$sql_data);
						if($this->db->trans_status() === TRUE){
							$this->db->trans_commit();
							$code = "0";
							$message = "Successfully approved travel order.";
							$helperDao->auditTrails(Helper::get('userid'),$message);
							$this->ModelResponse($code, $message);
							return true;
						} else{
							$this->db->trans_rollback();
							$helperDao->auditTrails(Helper::get('userid'),$message);
							$this->ModelResponse($code, $message);
						}
					return false;
			}else{
				$travel_order_no  = "0001";
				//$to_num = "TO-".$year."-".$split_result[2] + 1;	
				$to_num = "TO-".$year."-".$travel_order_no;	
				//var_dump($result);var_dump($to_num);die();
				
				$param = array($to_num,$travel_id);
				$set_to_num = "UPDATE tbltravelorder SET travel_order_no = ? WHERE travel_id = ? ";
				$this->db->query($set_to_num,$param);
	
				$sql = "UPDATE tbltravelorder SET status = ? , last_approve_name = ?, director_sign = ? WHERE travel_id = ? ";
				$this->db->query($sql,$sql_data);
					if($this->db->trans_status() === TRUE){
						$this->db->trans_commit();
						$code = "0";
						$message = "Successfully approved travel order.";
						$helperDao->auditTrails(Helper::get('userid'),$message);
						$this->ModelResponse($code, $message);
						return true;
					} else{
						$this->db->trans_rollback();
						$helperDao->auditTrails(Helper::get('userid'),$message);
						$this->ModelResponse($code, $message);
					}
				return false;
			}		
			
		}

		public function forDriverVehicle($params){
			$transac_date = explode(' - ', $params['duration']);
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Travel order failed to approve.";
			$this->db->trans_begin();
			$last_approver_name = $_SESSION['last_name'].", ".$_SESSION['middle_name']." ".$_SESSION['first_name'];
			$sql_data = array(5,$params['travel_id']);
			$sql = "UPDATE tbltravelorder SET status = ?  WHERE travel_id = ? ";
			$this->db->query($sql,$sql_data);

			$getdriver_vehicle = $this->getDriverVehicle($params['id']);
			$driver = $getdriver_vehicle->row()->driver;
			$vehicle_no = $getdriver_vehicle->row()->vehicle_no;

			
			if(isset($driver) && isset($vehicle_no) && $driver !== "" && $vehicle_no !== ""){
				
				if($this->db->trans_status() === TRUE){
					$this->db->trans_commit();
					$code = "0";
					$message = "Successfully approved travel order.";
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message);

					if ($transac_date[0] == $transac_date[1]){
						$emp_num = $this->getEmpNum($params['employee_id']);
						$dtrdate = date("Y-m-d", strtotime($transac_date[0]));

						$sqldtr = 'SELECT *
							FROM tbldtr 
							WHERE scanning_no = "'.(int)$emp_num->row()->empl_id.'"
									  AND transaction_date = "'.$dtrdate.'"';
							$querydtr = $this->db->query($sqldtr);
							if($querydtr->num_rows() > 0){
								
								$dtr_sql = "UPDATE tbldtr SET source = ? , adjustment_remarks = ? , tardiness_mins = 0 , tardiness_hrs = 0 , ut_mins = 0 , ut_hrs = 0 WHERE scanning_no = ? AND transaction_date = ?";
								$dtrparams = array('manual',($params['travel_order_no'] != "" && $params['travel_order_no'] != null ? $params['travel_order_no'] : "0000").'', (int)$emp_num->row()->empl_id, $querydtr->row()->transaction_date);
								$this->db->query($dtr_sql,$dtrparams);	
								
							}else{
								$this->db->insert('tbldtr', array("source" => 'manual',"adjustment_remarks" => ($params['travel_order_no'] != "" && $params['travel_order_no'] != null ? $params['travel_order_no'] : "0000").'',  "transaction_date" => $dtrdate, "scanning_no" => (int)$emp_num->row()->empl_id));
							}	
					}else{
						// $no_of_days = (int)$params['no_days'];

						// for ($i=0; $i < $no_of_days ; $i++) { 
						// 	$emp_num = $this->getEmpNum($params['employee_id']);
						// 	$dtrdate = date("Y-m-d", strtotime($transac_date[0].' + '.$i.' day'));
							
						// 	$sqldtr = 'SELECT *
						// 	FROM tbldtr 
						// 	WHERE scanning_no = "'.(int)$emp_num->row()->empl_id.'"
						// 			  AND transaction_date = "'.$dtrdate.'"';
						// 	$querydtr = $this->db->query($sqldtr);
						// 	if($querydtr->num_rows() > 0){
								
						// 		$dtr_sql = "UPDATE tbldtr SET source = ? , adjustment_remarks = ? , tardiness_mins = 0 , tardiness_hrs = 0 , ut_mins = 0 , ut_hrs = 0 WHERE scanning_no = ? AND transaction_date = ?";
						// 		$dtrparams = array('manual', ($params['travel_order_no'] != "" && $params['travel_order_no'] != null ? $params['travel_order_no'] : "0000").'', (int)$emp_num->row()->empl_id, $querydtr->row()->transaction_date);
						// 		$this->db->query($dtr_sql,$dtrparams);	
								
						// 	}else{
						// 		$this->db->insert('tbldtr', array("source" => 'manual', "adjustment_remarks" => ($params['travel_order_no'] != "" && $params['travel_order_no'] != null ? $params['travel_order_no'] : "0000").'',  "transaction_date" => $dtrdate, "scanning_no" => (int)$emp_num->row()->empl_id));	
						// 	}
						// }
						$arraydates = $this->getAvailabledays($transac_date);
						$arraydates = array_values($arraydates);

						for ($i=0; $i < sizeof($arraydates) ; $i++) { 
							$emp_num = $this->getEmpNum($params['employee_id']);

							$dtrdate = date("Y-m-d", strtotime($arraydates[$i]));
							$sqldtr = 'SELECT *
							FROM tbldtr 
							WHERE scanning_no = "'.(int)$emp_num->row()->empl_id.'"
									  AND transaction_date = "'.$dtrdate.'"';
							$querydtr = $this->db->query($sqldtr);
							if($querydtr->num_rows() > 0){
								
								$dtr_sql = "UPDATE tbldtr SET source = ? , adjustment_remarks = ? , tardiness_mins = 0 , tardiness_hrs = 0 , ut_mins = 0 , ut_hrs = 0 WHERE scanning_no = ? AND transaction_date = ?";
								$dtrparams = array('manual', ($params['travel_order_no'] != "" && $params['travel_order_no'] != null ? $params['travel_order_no'] : "0000").'', (int)$emp_num->row()->empl_id, $querydtr->row()->transaction_date);
								$this->db->query($dtr_sql,$dtrparams);	
								
							}else{
								$this->db->insert('tbldtr', array("source" => 'manual', "adjustment_remarks" => ($params['travel_order_no'] != "" && $params['travel_order_no'] != null ? $params['travel_order_no'] : "0000").'',  "transaction_date" => $dtrdate, "scanning_no" => (int)$emp_num->row()->empl_id));	
							}
						}
					}
					return true;
				} else{
					$this->db->trans_rollback();
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message);
				}
			}else{

				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, "Please select driver and vehicle first.");

			}

				
			return false;
		} 

		public function rejectTravelOrder($params){
			//var_dump($params).die();
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Travel order failed to reject.";
			$this->db->trans_begin();
			$sql_data = array(6,$params['status'],$params['remarks'],$params['travel_id']);
			$sql = "UPDATE tbltravelorder SET status = ? , status_before_reject = ? , remarks = ? WHERE travel_id = ? ";
			$this->db->query($sql,$sql_data);
				if($this->db->trans_status() === TRUE){
					$this->db->trans_commit();
					$code = "0";
					$message = "Successfully reject travel order.";
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message);
					return true;
				} else{
					$this->db->trans_rollback();
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message);
				}
			return false;
		}

		function getLatestTOnum($year){
			$sql = 'SELECT * FROM tbltravelorder WHERE YEAR(date_created) = '.$year.' AND travel_order_no != ""'  ;
			$query = $this->db->query($sql);
		    return $query;
		}

		function getDriverVehicle($id){
			$sql = 'SELECT driver,vehicle_no FROM tbltravelorder WHERE id = '.$id.'';
			$query = $this->db->query($sql);

			return $query;

		}

		function getEmpNum($employee_id){
			$sql = 'SELECT DECRYPTER(employee_number,"sunev8clt1234567890",id) as empl_id
					FROM tblemployees
					WHERE id = "'.$employee_id.'"';
					
			$query = $this->db->query($sql);

			return $query;

		}

		public function getUsersByTravelID($travel_id) {
			$this->db->select('DECRYPTER(tblemployees.first_name, "sunev8clt1234567890", tblemployees.id) AS decrypted_first_name, 
			DECRYPTER(tblemployees.last_name, "sunev8clt1234567890", tblemployees.id) AS decrypted_last_name');
			$this->db->from('tbltravelorder');
			$this->db->join('tblemployees', 'tblemployees.id = tbltravelorder.employee_id');
			$this->db->where('tbltravelorder.travel_id', $travel_id);
			
			$query = $this->db->get();
	
			if ($query->num_rows() > 0) {
				return $query->result_array();
			} else {
				return array();
			}
		}

		// public function fetchTravelID($travel_id) {
		// 	$this->db->where('travel_id', $travel_id);
		// 	$query = $this->db->get('tbltravelorder'); // Assuming the table name is 'travelOrderApproval'
		// 	return $query->result_array();
		// }
		
		function getAvailabledays($dates) {
			$start = new DateTime($dates[0]);
			$end = new DateTime($dates[1]);
			$oneday = new DateInterval("P1D");
			$weekdays = array();

			foreach(new DatePeriod($start, $oneday, $end->add($oneday)) as $day) {
				$day_num = $day->format("N"); /* 'N' number days 1 (mon) to 7 (sun) */
				if($day_num < 6) { /* weekday */
					array_push($weekdays, $day->format("Y-m-d"));
				} 
			} 

			$days = implode("', '",$weekdays);
			$query = $this->db->query("SELECT date as holiday_dates FROM tblfieldholidays WHERE date IN ('".$days."') AND (holiday_type != 'Suspension' OR (holiday_type = 'Suspension' && time_suspension = '08:00 AM')) ")->result_array();
			
			$holidays = array();
			for ($i=0; $i < sizeof($query); $i++) { 
				array_push($holidays, $query[$i]['holiday_dates']);
			}

			$remaining_weekdays = array_diff($weekdays, $holidays);
			return $remaining_weekdays;
		}

		public function fetchTravelOrderApprovals($employee_id){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$this->sql = "SELECT c.position_designation,
				b.name as position_title,
				c.position_id,
				a.approver,
				a.approve_type,
				DECRYPTER(c.first_name, 'sunev8clt1234567890', c.id) as first_name,
				DECRYPTER(c.middle_name, 'sunev8clt1234567890', c.id) AS middle_name,
				DECRYPTER(c.last_name, 'sunev8clt1234567890', c.id) as last_name,
				c.id
				FROM 
				tbltravelorderapprover AS a
				LEFT JOIN tblemployees c ON a.approver = c.id
				LEFT JOIN tblfieldpositions b ON c.position_id = b.id
				WHERE a.employee_id = ?
			";
			$query = $this->db->query($this->sql,array($employee_id));
			$result = $query->result();
			$data['approvers'] = $result;
			if(sizeof($result) > 0){
				$code = "0";
				$message = "Successfully fetched travel order approvals.";
				$this->ModelResponse($code, $message, $data);	
				return true;		
			}	
			else {
				$this->ModelResponse($code, $message);
			}
			return false;
		}
		
	}
?>