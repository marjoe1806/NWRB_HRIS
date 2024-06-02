<?php
	class LocatorSlipsCollection extends Helper {
		public function __construct() {
			$this->load->model('HelperDao');
			ModelResponse::busy();
		}
		// Step 1: Configuration

		// Declare variables for selecting columns, table name, and ordering columns.
		var $select_column = null;
		var $table = "tbltimekeepinglocatorslips";
		var $order_column = array(
			'',
			'control_no',
			'tbltimekeepinglocatorslips.transaction_date',
			'tblemployees.first_name',
			'tblfielddepartments.department_name',
			'tbltimekeepinglocatorslips.purpose',
			'tbltimekeepinglocatorslips.location',
			'tbltimekeepinglocatorslips.activity_name',
			'tbltimekeepinglocatorslips.status',
			'tbltimekeepinglocatorslips.remarks'
		);

		// Step 2: make_query() Function

		// This function constructs a SQL query to retrieve data.
		function make_query() {
			// Step 2.1: Define which columns to select.
			$this->select_column[] = 'tblemployees.first_name';
			$this->select_column[] = $this->table.'.filing_date';
			$this->select_column[] = $this->table.'.purpose';
			$this->select_column[] = $this->table.'.activity_name';
			$this->select_column[] = $this->table.'.transaction_date';
			$this->select_column[] = $this->table.'.status';
			$this->select_column[] = $this->table.'.remarks';
			$this->select_column[] = $this->table.'.control_no';
			// Step 2.2: Construct a complex SQL query.
			$this->db->select('DISTINCT (SELECT COUNT(*) FROM tblemployeesobapprovers a LEFT JOIN tblemployees b ON a.employee_id = b.id where b.employment_status = "Active" AND a.is_active = 1 AND a.approver = "'.$_SESSION["id"].'" AND a.employee_id = tbltimekeepinglocatorslips.employee_id AND a.approve_type = 4) as approve,(SELECT COUNT(*) FROM tblemployeesobapprovers a LEFT JOIN tblemployees b ON a.employee_id = b.id where b.employment_status = "Active" AND a.is_active = 1 AND a.approver = "'.$_SESSION["id"].'" AND a.employee_id = tbltimekeepinglocatorslips.employee_id AND a.approve_type = 5) as secondapprove, (SELECT COUNT(*) FROM tblemployeesobapprovers a LEFT JOIN tblemployees b ON a.employee_id = b.id where b.employment_status = "Active" AND a.is_active = 1 AND a.approver = "'.$_SESSION["id"].'" AND a.employee_id = tbltimekeepinglocatorslips.employee_id AND a.approve_type = 2) as recom,tblemployees.*,'.$this->table.'.*,tblemployees.id as salt, DECRYPTER(tblemployees.employee_id_number, "sunev8clt1234567890", tblemployees.id) as emp_id, tblfieldpositions.name as position_name, tblfielddepartments.department_name as department_name');
			$this->db->from($this->table);
			$this->db->join("tblemployees","tblemployees.id = ".$this->table.".employee_id","left");
			$this->db->join("tblfieldpositions", "tblemployees.position_id = tblfieldpositions.id","left");
			$this->db->join("tblfielddepartments", $this->table.".division_id = tblfielddepartments.id","left");
		
			if(isset($_POST["search"]["value"])) {
				$this->db->group_start();
				foreach ($this->select_column as $key => $value) {
					if($value == "tblemployees.first_name" || $value == "tblemployees.last_name" || $value == "tblemployees.middle_name" || $value == "tblemployees.extension")  {
						$this->db->or_like("CONCAT(DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.extension,'sunev8clt1234567890',tblemployees.id),', ',DECRYPTER(tblemployees.first_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.middle_name,'sunev8clt1234567890',tblemployees.id))",$_POST["search"]["value"]);
					} else {
						$this->db->or_like($value, $_POST["search"]["value"]);
					}
				}
				$this->db->or_like("CONCAT(DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.extension,'sunev8clt1234567890',tblemployees.id),', ',DECRYPTER(tblemployees.first_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.middle_name,'sunev8clt1234567890',tblemployees.id))",$_POST["search"]["value"]);
				$this->db->group_end();
			}
			
			if(!Helper::role(ModuleRels::OB_VIEW_ALL_TRANSACTIONS)) {
				$this->db->where('(tblemployees.division_id = '.$_SESSION["division_id"].' OR (SELECT COUNT(*) FROM tblemployeesobapprovers a LEFT JOIN tblemployees b ON a.employee_id = b.id where b.employment_status = "Active" AND a.is_active = 1 AND a.approver = "'.$_SESSION["id"].'" AND a.employee_id = tbltimekeepinglocatorslips.employee_id AND a.approve_type = 3) > 0)');
			}

				// tblemployees.division_id = '.$_SESSION["division_id"].' OR 
			$this->db->where('
				(SELECT COUNT(*) FROM tblemployeesobapprovers a LEFT JOIN tblemployees b ON a.employee_id = b.id where b.employment_status = "Active" AND a.is_active = 1 AND a.approver = "'.$_SESSION["id"].'" AND a.employee_id = tbltimekeepinglocatorslips.employee_id AND a.approve_type = 4) > 0 
				OR (SELECT COUNT(*) FROM tblemployeesobapprovers a LEFT JOIN tblemployees b ON a.employee_id = b.id where b.employment_status = "Active" AND a.is_active = 1 AND a.approver = "'.$_SESSION["id"].'" AND a.employee_id = tbltimekeepinglocatorslips.employee_id AND a.approve_type = 5) > 0
				OR (SELECT COUNT(*) FROM tblemployeesobapprovers a LEFT JOIN tblemployees b ON a.employee_id = b.id where b.employment_status = "Active" AND a.is_active = 1 AND a.approver = "'.$_SESSION["id"].'" AND a.employee_id = tbltimekeepinglocatorslips.employee_id AND a.approve_type = 2) > 0
			');
			
			if(isset($_POST['status']) && $_POST['status'] != '') {
				$this->db->where($this->table.".status",$_POST['status']);
			}
			
			if(isset($_POST["order"])) {
				$this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
			}
			
			$this->db->order_by($this->table.'.date_created',"desc");
			//$this->db->order_by($this->table.'.transaction_date',"DESC");
			$this->db->where($this->table.".status !=", null);
		}
		
		// Step 3: make_datatables() Function
		// This function executes the query and returns results for display.
		function make_datatables() {
			$this->make_query();
			if ($_POST["length"] != -1) $this->db->limit($_POST['length'], $_POST['start']);
			$query = $this->db->get();
			return $query->result();
		}

		// Step 4: get_filtered_data() Function

		// This function returns the count of filtered rows without limiting the result set.
		function get_filtered_data() {
			$this->make_query();
			$query = $this->db->get();
			return $query->num_rows();
		}

		// Step 5: get_all_data() Function

		// This function returns the total count of rows in the table without filtering.
		function get_all_data() {
			$this->db->select($this->table . "*");
			$this->db->from($this->table);
			return $this->db->count_all_results();
		}

		public function hasRows(){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = "SELECT tbltimekeepinglocatorslips.*, DECRYPTER(tbltimekeepinglocatorslips.employee_id, 'sunev8clt1234567890', tbltimekeepinglocatorslips.employee_id) as emp_id, tblfieldpositions.name as position_name 
					FROM tbltimekeepinglocatorslips 
					LEFT JOIN tblfieldpositions 
					ON tbltimekeepinglocatorslips.position_id = tblfieldpositions.id";
			$query = $this->db->query($sql);
			$data['details']= $query->result_array();
			if(sizeof($data['details']) > 0){
				foreach ($data['details'] as $k => $v) {
					$employee = $this->getEmployeeById($v['employee_id']);
					$data['details'][$k]['employee_firstname'] = @$this->Helper->decrypt($employee[0]['first_name'], $employee[0]['id']);
					$data['details'][$k]['employee_middlename'] = @$this->Helper->decrypt($employee[0]['middle_name'], $employee[0]['id']);
					$data['details'][$k]['employee_lastname'] = @$this->Helper->decrypt($employee[0]['last_name'], $employee[0]['id']);
					$employee_number = @$this->Helper->decrypt($employee[0]['employee_number'], $employee[0]['id']);
					$dtr = $this->getDTRAdjustmentsForRecommendation($employee_number, $data['details'][$k]['transaction_date']);
					foreach ($dtr as $k2 => $v2) {
						switch ($v2['transaction_type']) {
							case '0':
								$data['details'][$k]['locator_time_in'] 	= $v2['transaction_time'];
								break;
							case '3':
								$data['details'][$k]['locator_break_in'] = $v2['transaction_time'];
								break;	
							case '2':
								$data['details'][$k]['locator_break_out'] = $v2['transaction_time'];
								break;
							case '1':
								$data['details'][$k]['locator_time_out'] = $v2['transaction_time'];
								break;
						}
					}
				}
			}
			
			if(sizeof($data['details']) > 0){
				$code = "0";
				$message = "Successfully fetched Official Business Forms.";
				$this->ModelResponse($code, $message, $data);
				// $helperDao->auditTrails(Helper::get('userid'),$message);
				return true;
			}
			else {
				$this->ModelResponse($code, $message);
				// $helperDao->auditTrails(Helper::get('userid'),$message);
			}
			return false;
		}

		public function hasRowsActive(){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT * FROM tbltimekeepinglocatorslips where is_active = '1'";
			$query = $this->db->query($sql);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched positions.";
				$this->ModelResponse($code, $message, $data);
				return true;
			}
			else {
				$this->ModelResponse($code, $message);
			} 
			return false;
		} 

		public function approveRows($params){
			// var_dump($params);die();
			
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Locator slip request failed to approve.";
			$getcode = $this->db->select("GETNUMBERSEQUENCE('NWRB') as tcode")->get();
			$rowcode = $getcode->row_array();
			$rowcode["tcode"] = "LS-".$rowcode["tcode"];
			$data['Id'] 	= isset($params['id'])?$params['id']:'';
			$data['Transaction_Date'] 	= isset($params['transaction_date'])?$params['transaction_date']:'';
			$data['Employee_Number'] 	= isset($params['employee_id'])?$this->getScanningNumber($params['employee_id']):'';
			$data['Is_Active'] 		= 1;
			$data['Status'] 		= "APPROVED";
			$users = $this->db->select('id')->from("tbltimekeepinglocatorslips")->where('locator_id', $params['locator_id'])->get()->result_array();		

			$approverData = array(); // Initialize an array to hold all approver data

			foreach ($users as $user) {
				$approver = array(
					"request_id" => $user['id'], // Use $user['id'] instead of $ids array
					"approval_type" => 3,
					"position" => Helper::get("position_id"),
					"employee_id" => Helper::get("employee_id"),
					"approved_by" => Helper::get("userid"),
					"name" => Helper::get("first_name") . " " . Helper::get("middle_name") . " " . Helper::get("last_name"),
					"remarks" => "",
					"file_name" => $params['file_name'],
					"file_size" => $params['file_size']
				);

				$approverData[] = $approver; // Store each $approver array in $approverData array
			}

			$this->db->trans_start(); // Start the transaction

			$this->db->insert_batch("tblobapprovals", $approverData); // Insert all approver data at once

			$this->db->trans_complete(); // Complete the transaction

			if ($this->db->trans_status() === false) {
				$this->db->trans_rollback(); // Rollback the transaction if it failed
			} else {
				$this->db->trans_commit(); // Commit the transaction
			}

			$location_id = $params['locator_id'];
			$checked_by = $_SESSION['last_name'] . ', ' . $_SESSION['first_name']. ' ' . $_SESSION['middle_name'] . ' ' . $_SESSION['extension'];
			$locatorslip = "UPDATE tbltimekeepinglocatorslips SET control_no = ?, checked_by = ?, is_active = ?, status = ? WHERE locator_id = ? ";

			// if no vehicle
			if($params['is_vehicle'] == 3){

				$locatorslipsdata = array($rowcode["tcode"],$checked_by,0,"COMPLETED",$location_id);
			}
			//end of no vehecle
			//if with vehicle
			else{
				$locatorslipsdata = array($rowcode["tcode"],$checked_by,0,"APPROVED",$location_id);
			}
			//end
			$query = $this->db->query($locatorslip,$locatorslipsdata);

			if($query > 0){

				if($params['is_vehicle'] == 3){	
					if($this->db->trans_status() === TRUE){
						$this->db->trans_commit();
						$code = "0";
						$message = "Successfully complete locator slip request.";
						$helperDao->auditTrails(Helper::get('userid'),$message);
						$this->ModelResponse($code, $message);
		
						$dates = $params['transaction_date'].' - '.$params['transaction_date_end'];
						$transac_date = explode(' - ', $dates);				
						
						if ($transac_date[0] == $transac_date[1] || $transac_date[1] == "0000-00-00"){

							// Get employee IDs from tbltimekeepinglocatorslips
							$user_dtr = $this->db
							->select('employee_id')
							->from('tbltimekeepinglocatorslips')
							->where('locator_id', $params['locator_id'])
							->get()
							->result_array();

							if (!$user_dtr) {
							// Handle query error here
							return false;
							}

							$employee_ids = array_column($user_dtr, 'employee_id');

							if (!empty($employee_ids)) {
							// Decrypt employee numbers from tblemployees
							$this->db
								->select('DECRYPTER(employee_number, "sunev8clt1234567890", id) AS empl_id', FALSE)
								->from('tblemployees')
								->where_in('id', $employee_ids);

							$query = $this->db->get();

							if ($query) {
								$result = $query->result_array();
								$dtrdate = date("Y-m-d", strtotime($transac_date[0]));
								$scanning_no = array_column($result, 'empl_id');
								//This code will convert each value in the 'empl_id' column (extracted by array_column) to an integer using intval. After running this code, the values in the $scanning_no array should be integers.
								$scanning_no = array_map('intval', $scanning_no);

								if (!empty($scanning_no)) {
									// Check if records exist in tbldtr
									$this->db
										->select('*')
										->from('tbldtr')
										->where_in('scanning_no', $scanning_no)
										->where('transaction_date', $dtrdate);

									$querydtr = $this->db->get();

									$existing_scanning_numbers = [];

									if ($querydtr && $querydtr->num_rows() > 0) {
										// Collect existing scanning numbers
										foreach ($querydtr->result() as $row) {
											$existing_scanning_numbers[] = $row->scanning_no;
										}
									}

									// Update existing records in tbldtr
									if (!empty($existing_scanning_numbers)) {
										foreach ($existing_scanning_numbers as $existing_scanning_no) {
											$dtr_sql = "UPDATE tbldtr 
														SET source = ?, 
															adjustment_remarks = ?, 
															tardiness_mins = 0, 
															tardiness_hrs = 0, 
															ut_mins = 0, 
															ut_hrs = 0 
														WHERE scanning_no = ? 
														AND transaction_date = ?";
											$dtrparams = array('manual', $rowcode["tcode"], $existing_scanning_no, $dtrdate);

											$this->db->query($dtr_sql, $dtrparams);
										}
									}

									// Insert new records into tbldtr
									$new_scanning_numbers = array_diff($scanning_no, $existing_scanning_numbers);

									if (!empty($new_scanning_numbers)) {
										$insert_data = [];

										foreach ($new_scanning_numbers as $new_scanning_no) {
											$insert_data[] = array(
												"source" => 'manual',
												"adjustment_remarks" => $rowcode["tcode"],
												"transaction_date" => $dtrdate,
												"scanning_no" => $new_scanning_no
											);
										}

										$this->db->insert_batch('tbldtr', $insert_data);
									}
								} else {
									echo "No employee IDs found to process.";
								}
							} else {
								// Handle query error here
								return false;
							}
							} else {
							echo "No employee IDs found to process.";
							}
						}else{
							$no_of_days = (int)$params['no_days'];

							$arraydates = $this->getAvailabledays($transac_date);
							$arraydates = array_values($arraydates);

							for ($i=0; $i < sizeof($arraydates) ; $i++) { 
								$dtrdate = date("Y-m-d", strtotime($arraydates[$i]));

								// Step 2: Query for employee IDs from tbltimekeepinglocatorslips for the current day
								$user_dtr = $this->db->select('employee_id')
									->from('tbltimekeepinglocatorslips')
									->where('locator_id', $params['locator_id'])
									->get()
									->result_array();

								if (!$user_dtr) {
									// Handle query error here
									return false;
								}

								$employee_ids = array_column($user_dtr, 'employee_id');

								// Step 3: Query for decrypted employee numbers from tblemployees for the current day
								$this->db->select('DECRYPTER(employee_number, "sunev8clt1234567890", id) AS empl_id', FALSE)
									->from('tblemployees')
									->where_in('id', $employee_ids);

								$query = $this->db->get();

								if (!$query) {
									// Handle query error here
									return false;
								}

								$result = $query->result_array();

								$scanning_no = array_column($result, 'empl_id');
								//This code will convert each value in the 'empl_id' column (extracted by array_column) to an integer using intval. After running this code, the values in the $scanning_no array should be integers.
								$scanning_no = array_map('intval', $scanning_no);
								//var_dump($scanning_no);die();

								if (!empty($scanning_no)) {
									// Step 4: Check if data exists in tbldtr for the current day
									$this->db->select('*')
										->from('tbldtr')
										->where_in('scanning_no', $scanning_no)
										->where('transaction_date', $dtrdate);

									$querydtr = $this->db->get();

									if ($querydtr && $querydtr->num_rows() > 0) {
										// Step 5: Data exists, update it
										$row = $querydtr->row();

										$dtr_sql = "UPDATE tbldtr 
													SET source = ?, 
														adjustment_remarks = ?, 
														tardiness_mins = 0, 
														tardiness_hrs = 0, 
														ut_mins = 0, 
														ut_hrs = 0 
													WHERE scanning_no = ? 
													AND transaction_date = ?";
										$dtrparams = array('manual', $rowcode["tcode"], $row->scanning_no, $row->transaction_date);

										$this->db->query($dtr_sql, $dtrparams);
									} else {
										// Step 6: Data doesn't exist, insert it

										// Prepare data for insertion
										$insert_data = [];

										foreach ($scanning_no as $scanning) {
											$insert_data[] = array(
												"source" => 'manual',
												"adjustment_remarks" => $rowcode["tcode"],
												"transaction_date" => $dtrdate,
												"scanning_no" => $scanning
											);
										}

										// Insert all data at once
										$this->db->insert_batch('tbldtr', $insert_data);
									}
								} else {
									// Step 7: Handle case where no employee IDs are found to process for the current day
									echo "No employee IDs found to process.";
								}
							}
						}
				
						return true;
					}
					else {
						$helperDao->auditTrails(Helper::get('userid'),$message);
						$this->ModelResponse($code, $message);
					}
					return false;
				}
				else{
					
					if($this->db->trans_status() === TRUE){
						$this->db->trans_commit();
				
						// $approve_sql = "	UPDATE tbltimekeepingdailytimerecordadjustments SET is_active = ? WHERE employee_number = ? AND transaction_date = ?";
						// $approve_params = array($data['Is_Active'],$data['Employee_Number'],$data['Transaction_Date']);
						// $this->db->query($approve_sql,$approve_params);
						$code = "0";
						$message = "Successfully approved Locator slip request.";
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
			
			
		}

		function make_query_summary($employee_id, $employee_number, $payroll_period, $payroll_period_id, $shift_id) {
			$dtr = array();
			$get_record = array();
			$attendance = array();
			$no_of_days = date('t',strtotime($payroll_period));
			$payroll_date = explode("-", $payroll_period);

			for ($day=1; $day <= $no_of_days; $day++) {
				$current_day = $payroll_date[0] . '-' . $payroll_date[1] . '-' . (($day > 9) ? $day : '0'. $day);
				$time_record = $this->getDailyTimeRecordData($current_day, $employee_number);
				$get_record = $time_record['actual_data'];
				if(sizeof($get_record) > 0) {
						foreach ($get_record as $key => $value) {
							$dtr['records'][$current_day][$value['transaction_type']] = $value;
						}
				}
				if(sizeof($time_record['adjustments']) > 0){
					$get_record = $time_record['adjustments'];
					if(sizeof($get_record) > 0) {
						foreach ($get_record as $key => $value) {
							$dtr['adjustments'][$current_day][$value['transaction_type']] = $value;
						}
					}
				}

			}
			$dtr['employee'] = $this->getEmployeeById($employee_id);
			$dtr['details'] = $this->getDepartmentById($dtr['employee'][0]['office_id']);
			$dtr['payroll_period'] = $payroll_period;
			return $dtr;

		}

		function getDailyTimeRecordDataAdjustments($date, $employee_number) {
			$attendance = array();
			$params = array($date, $employee_number);
			$sql = "SELECT * FROM tbltimekeepingdailytimerecordadjustments WHERE transaction_date = ? AND employee_number = ?";
			$query = $this->db->query($sql, $params);
			$attendance = $query->result_array();
			return $attendance;
		}

		function getDailyTimeRecordData($date, $employee_number) {
			$attendance = array();
			$adjustments = $this->getDailyTimeRecordDataAdjustments($date, $employee_number);
			$attendance['adjustments'] = $adjustments;
			$params = array($date, $employee_number);
			$sql = "SELECT * FROM tbltimekeepingdailytimerecord  WHERE transaction_date = ? AND employee_number = ?";
			$query = $this->db->query($sql, $params);
			$attendance['actual_data'] = $query->result_array();
			return $attendance;
		}

		public function rejectRows($params){
			// var_dump($params['remarks']); die();	
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Locator slip request failed to reject.";
			$data['Id'] 	= isset($params['id'])?$params['id']:'';
			$data['Is_Active'] 		= "0";
			$data['Remarks'] 		= $params['reject_remarks)'];
			$data['Status'] 		= "REJECTED";
			$location_id = $params['locator_id'];
			$userlevel_sql = "	UPDATE tbltimekeepinglocatorslips SET is_active = ?, status = ?, reject_remarks = ? WHERE locator_id = ? ";
			$userlevel_params = array($data['Is_Active'],$data['Status'], $data['Remarks'], $location_id);
			// $sql = "UPDATE tbltimekeepingdailytimerecordadjustments SET is_active = ? W"
			$this->db->query($userlevel_sql,$userlevel_params);
			// var_dump($data['Id']); die();
			if($this->db->trans_status() === TRUE){
				$this->db->trans_commit();
				$code = "0";
				$message = "Successfully disapproved locator slip request.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;
			}
			else {
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		public function recommendation($params){
			// var_dump($params['remarks']); die();	
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Locator slip request failed to reject.";
			$data['Id'] 	= isset($params['id'])?$params['id']:'';
			$data['Is_Active'] 		= "0";
			$data['Remarks'] 		= $params['reject_remarks'];
			$data['Status'] 		= "FOR APPROVAL";
			$location_id = $params['locator_id'];
			// var_dump($params['locator_id']);die();
			$userlevel_sql = "	UPDATE tbltimekeepinglocatorslips SET is_active = ?, status = ?, reject_remarks = ? WHERE locator_id = ? ";
			$userlevel_params = array($data['Is_Active'],$data['Status'], $data['Remarks'], $location_id);
			// $sql = "UPDATE tbltimekeepingdailytimerecordadjustments SET is_active = ? W"
			$this->db->query($userlevel_sql,$userlevel_params);
			// var_dump($data['Id']); die();
			if($this->db->trans_status() === TRUE){
				$this->db->trans_commit();
				$code = "0";
				$message = "Successfully recommended locator slip request.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;
			}
			else {
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		public function AssignDriverVehicle($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Locator slip request failed to assign.";

			$data['Id'] 	= isset($params['id'])?$params['id']:'';
			$data['Is_Active'] 		= "1";
			$data['driver'] 		= $params['driver'];
			$data['vehicle'] 		= $params['vehicle'];
			$data['Status'] 		= "COMPLETED";
			$location_id = $params['locator_id'];
			$userlevel_sql = "	UPDATE tbltimekeepinglocatorslips SET is_active = ?, status = ?, driver = ? , vehicle = ? WHERE locator_id = ? ";
			$userlevel_params = array($data['Is_Active'],$data['Status'], $data['driver'], $data['vehicle'], $location_id);
			$this->db->query($userlevel_sql,$userlevel_params);
			if($this->db->trans_status() === TRUE){
				$this->db->trans_commit();
				$code = "0";
				$message = "Successfully complete locator slip request.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);

				$dates = $params['transaction_date'].' - '.$params['transaction_date_end'];
				$transac_date = explode(' - ', $dates);
				if ($transac_date[0] == $transac_date[1] || $transac_date[1] == "0000-00-00"){
					$emp_num = $this->getEmpNum($params['employee_id']);
					$dtrdate = date("Y-m-d", strtotime($transac_date[0]));

					$sqldtr = 'SELECT * FROM tbldtr WHERE scanning_no = "'.(int)$emp_num['empl_id'].'" AND transaction_date = "'.$dtrdate.'"';
						$querydtr = $this->db->query($sqldtr);
						if($querydtr->num_rows() > 0){
							$dtr_sql = "UPDATE tbldtr SET source = ? , adjustment_remarks = ? , tardiness_mins = 0 , tardiness_hrs = 0 , ut_mins = 0 , ut_hrs = 0 WHERE scanning_no = ? AND transaction_date = ?";
							$dtrparams = array('manual', $params['remarkstable'], (int)$emp_num['empl_id'], $querydtr->row()->transaction_date);
							// var_dump($dtrparams);die();
							$this->db->query($dtr_sql,$dtrparams);	
						}else{
							$this->db->insert('tbldtr', array("source" => 'manual',"adjustment_remarks" => $params['remarkstable'],  "transaction_date" => $dtrdate, "scanning_no" => (int)$emp_num['empl_id']));
						}	
				}else{
					$no_of_days = (int)$params['no_days'];
					for ($i=0; $i < $no_of_days ; $i++) { 
						$emp_num = $this->getEmpNum($params['employee_id']);
						$dtrdate = date("Y-m-d", strtotime($transac_date[0].' + '.$i.' day'));
						
						$sqldtr = 'SELECT *
						FROM tbldtr 
						WHERE scanning_no = "'.(int)$emp_num['empl_id'].'"
								  AND transaction_date = "'.$dtrdate.'"';
						$querydtr = $this->db->query($sqldtr);
						if($querydtr->num_rows() > 0){
							
							$dtr_sql = "UPDATE tbldtr SET source = ? , adjustment_remarks = ? , tardiness_mins = 0 , tardiness_hrs = 0 , ut_mins = 0 , ut_hrs = 0 WHERE scanning_no = ? AND transaction_date = ?";
							$dtrparams = array('manual',$params['remarkstable'], (int)$emp_num['empl_id'], $querydtr->row()->transaction_date);
							// var_dump($dtrparams);die();
							$this->db->query($dtr_sql,$dtrparams);	
						}else{
							$this->db->insert('tbldtr', array("source" => 'manual',"adjustment_remarks" => $params['remarkstable'],  "transaction_date" => $dtrdate, "scanning_no" => (int)$emp_num->row()->empl_id));
						}
					}
				}
			
				return true;
			}
			else {
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		function getLocationById($location_id) {
			$params = array($location_id);
			$sql = "SELECT * FROM tblfieldlocations WHERE id = ? AND is_active = 1";
			$query = $this->db->query($sql, $params);
			$location_id = $query->result_array();
			return @$location_id[0];
		}

		function getEmployeeById($employee_id) {
			$params = array($employee_id);
			$sql = "SELECT * FROM tblemployees WHERE id = ? AND is_active = 1";
			$query = $this->db->query($sql, $params);
			$employee = $query->result_array();
			return $employee;
		}

		function getScanningNumber($employee_id) {
			$params = array($employee_id);
			$sql = "SELECT employee_number FROM tblemployees WHERE id = ? AND is_active = 1";
			$query = $this->db->query($sql, $params);
			$employee = $query->result_array();
			return @$this->Helper->decrypt($employee[0]['employee_number'], $employee_id);
		}

		function getDailyTimeRecord($employee_number, $date) {
			$params = array($employee_number, $date);
			$sql = "SELECT * FROM tbltimekeepingdailytimerecord WHERE employee_number = ? AND transaction_date = ? AND is_active = 1";
			$query = $this->db->query($sql, $params);
			$employee = $query->result_array();
			return $employee;
		}

		function getDTRAdjustmentsForRecommendation($employee_number, $date) {
			$params = array($employee_number, $date);
			$sql = "SELECT * FROM tbltimekeepingdailytimerecordadjustments WHERE employee_number = ? AND transaction_date = ? AND is_active = 0";
			$query = $this->db->query($sql, $params);
			$employee = $query->result_array();
			return $employee;
		}

		// public function addRows($params){
		// 	$helperDao = new HelperDao();
		// 	$code = "1";
		// 	$message = "Official Business request failed to insert.";
		// 	$this->db->trans_begin();
		// 	$locator_slip = array(
		// 		'purpose'						=> $params['purpose'],
		// 		'filename'					=> $params['file_name'],
		// 		'filesize'					=> $params['file_size'],
		// 		'employee_id' 			=> $params['employee_id'],
		// 		'division_id' 			=> $params['division_id'],
		// 		'location' 					=> @$params['location'],
		// 		'checked_by' 				=> $_SESSION['last_name'] . ', ' . $_SESSION['first_name'],
		// 		'received_by' 			=> $_SESSION['employee_id'],
		// 		'transaction_date' 	=> $params['transaction_date'],
		// 		'remarks' 	=> $params['remarks']
		// 	);

		// 	$this->db->where('transaction_date', $params['transaction_date']);
		// 	$this->db->where('employee_id', $params['employee_id']);
		// 	$this->db->delete('tbltimekeepinglocatorslips');
			
		// 	if($this->db->insert('tbltimekeepinglocatorslips', $locator_slip) > 0) {
		// 		$this->db->where('transaction_date', $params['transaction_date']);
		// 		$this->db->where('employee_number', $params['employee_number']);
		// 		$this->db->delete('tbltimekeepingdailytimerecordadjustments');

		// 		foreach ($params['locator_transaction_time'] as $k => $v) {
		// 			$params2 = array();
		// 			$params2['remarks'] = $params['remarks'];
		// 			$params2['source_device'] = 'manual input';
		// 			$params2['source_location'] = 'manual input';
		// 			$params2['modified_by'] = Helper::get('userid');
		// 			$params2['transaction_date'] = $params['transaction_date'];
		// 			$params2['transaction_type'] = $params['locator_transaction_type'][$k];
		// 			$params2['transaction_time'] = $params['locator_transaction_time'][$k] != "" ? $params['locator_transaction_time'][$k] : null;
		// 			$params2['employee_number'] = $this->getScanningNumber($params['employee_id']);
		// 			$this->db->insert('tbltimekeepingdailytimerecordadjustments',$params2);
		// 		}
		// 	}
		// 	if($this->db->trans_status() === TRUE){
		// 		$code = "0";
		// 		$this->db->trans_commit();
		// 		$message = "Official Business Form successfully inserted.";
		// 		$helperDao->auditTrails(Helper::get('userid'),$message);
		// 		$this->ModelResponse($code, $message);
		// 		return true;
		// 	}
		// 	else {
		// 		$this->db->trans_rollback();
		// 		$helperDao->auditTrails(Helper::get('userid'),$message);
		// 		$this->ModelResponse($code, $message);
		// 	}
		// 	return false;
		// }

		// public function updateRows($params){
		// 	// var_dump($params); die();
		// 	$helperDao = new HelperDao();
		// 	$code = "1";
		// 	$message = "Official Business request failed to update.";
		// 	$this->db->trans_begin();
		// 	$locator_slip = array(
		// 		'purpose'						=> $params['purpose'],
		// 		'location' 					=> $params['location'],
		// 		'checked_by' 				=> $_SESSION['last_name'] . ', ' . $_SESSION['first_name'],
		// 		'received_by' 			=> $_SESSION['employee_id'],
		// 		'employee_id' 			=> $params['employee_id'],
		// 		// 'location_id' 			=> $params['location_id'],
		// 		'division_id' 					=> $params['division_id'],
		// 		'transaction_date' 	=> $params['transaction_date'],
		// 		'is_active'				=> $params['is_active']
		// 	);
		// 	if(isset($params['file_name']) && $params['file_name'] != "") {
		// 		$locator_slip["filename"] = $params['file_name'];
		// 		$locator_slip["filesize"] = $params['file_size'];
		// 	}
		// 	$this->db->where('id', $params['id']);
		// 	if ($this->db->update('tbltimekeepinglocatorslips',$locator_slip) === FALSE){
		// 		$helperDao->auditTrails(Helper::get('userid'),$message);
		// 		$this->ModelResponse($code, $message);
		// 	}
		// 	else {
		// 			$this->db->where('transaction_date', $params['transaction_date']);
		// 			$this->db->delete('tbltimekeepingdailytimerecordadjustments');
		// 			foreach ($params['locator_transaction_time'] as $k => $v) {
		// 				$params2 = array();
		// 				$params2['remarks'] = 'locator slip';
		// 				$params2['source_device'] = 'manual input';
		// 				$params2['source_location'] = 'manual input';
		// 				$params2['modified_by'] = Helper::get('userid');
		// 				$params2['transaction_date'] = $params['transaction_date'];
		// 				$params2['transaction_type'] = $params['locator_transaction_type'][$k];
		// 				$params2['transaction_time'] = $params['locator_transaction_time'][$k] != "" ? $params['locator_transaction_time'][$k] : null;
		// 				$params2['employee_number'] = $this->getScanningNumber($params['employee_id']);
		// 				$this->db->insert('tbltimekeepingdailytimerecordadjustments',$params2);
		// 			}
		// 		if($this->db->trans_status() === TRUE){
		// 			$code = "0";
		// 			$this->db->trans_commit();
		// 			$message = "Official Business request successfully updated.";
		// 			$helperDao->auditTrails(Helper::get('userid'),$message);
		// 			$this->ModelResponse($code, $message);
		// 			return true;
		// 		}
		// 		else {
		// 			$this->db->trans_rollback();
		// 			$helperDao->auditTrails(Helper::get('userid'),$message);
		// 			$this->ModelResponse($code, $message);
		// 		}
		// 	}
		// 	return false;
		// }

		public function fetchOBApprovals($id, $employee_id){
			// $helperDao = new HelperDao();
			// $data = array();
			// $code = "1";
			// $message = "No data available.";
			// $this->sql = "CALL sp_get_employee_ob_approvals(?)";
			// $query = $this->db->query($this->sql,array($id));
			// $result = $query->result();
			// mysqli_next_result($this->db->conn_id);
			// $data['approvers'] = $result;
			// if(sizeof($result) > 0){
			// 	$code = "0";
			// 	$message = "Successfully fetched ls approvals.";
			// 	$this->ModelResponse($code, $message, $data);	
			// 	$helperDao->auditTrails(Helper::get('userid'),$message);	
			// 	return true;		
			// }	
			// else {
			// 	$this->ModelResponse($code, $message);
			// 	$helperDao->auditTrails(Helper::get('userid'),$message);
			// }
			// return false;
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
				DECRYPTER(c.extension, 'sunev8clt1234567890', c.id) as extension
				FROM 
				tblemployeesobapprovers AS a
				LEFT JOIN tblemployees c ON a.approver = c.id
				LEFT JOIN tblfieldpositions b ON c.position_id = b.id
				WHERE a.employee_id = ?
			";
			// $query = $this->db->query($this->sql,array($id));
			$query = $this->db->query($this->sql,array($employee_id));
			$result = $query->result();
			// mysqli_next_result($this->db->conn_id);
			$data['approvers'] = $result;
			if(sizeof($result) > 0){
				$this->sql = "SELECT
					c.position_designation,
					b.name as position_title,
					c.position_id,
					a.approval_type,
					DECRYPTER(c.first_name, 'sunev8clt1234567890', c.id) as first_name,
					DECRYPTER(c.middle_name, 'sunev8clt1234567890', c.id) AS middle_name,
					DECRYPTER(c.last_name, 'sunev8clt1234567890', c.id) as last_name,
					a.file_name,
					a.approved_by
				FROM tblobapprovals a 
				left join tblfieldpositions b on a.position = b.id
				left join tblemployees c on a.employee_id = c.id
				WHERE a.request_id = ?;
				";
				$query = $this->db->query($this->sql,array($id));
				$result = $query->result();
				$data['approved'] = $result;

				$code = "0";
				$message = "Successfully fetched leave approvals.";
				$this->ModelResponse($code, $message, $data);	
				// $helperDao->auditTrails(Helper::get('userid'),$message);	
				return true;		
			}	
			else {
				$this->ModelResponse($code, $message);
				// $helperDao->auditTrails(Helper::get('userid'),$message);
			}
			return false;
		}

		function getEmpNum($employee_id){			

			 $employee_id = $this->db->escape_str($employee_id);

			$this->db->select('*');
			$this->db->select('DECRYPTER(employee_number, "sunev8clt1234567890", id) AS empl_id', FALSE);
			$this->db->from('tblemployees');
			$this->db->where('id', $employee_id);
			
			$query = $this->db->get();

			if (!$query) {
				// Handle query error here
				return false;
			}

			// Assuming the query returns a single row
			$result = $query->row_array();

			return $result;
		}

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
		

	}
?>