<?php
    class ManageTrainingsCollection extends Helper {
		var $select_column = null;
		public function __construct() {
			$this->load->model('HelperDao');
			ModelResponse::busy();
			$columns = $this->getColumns();
			foreach ($columns as $key => $value) {
				$this->select_column[] = $this->table.'.'.$value['COLUMN_NAME'];
			}
		}

        // set orderable columns in manage trainings list
        var $table = "tbltrainings";
		var $order_column = array("","title","sponsor","venue","no_of_hours","type","start_date","end_date","travel_order","office_order","status");
        // set searchable parameters in tbltrainings table
		public function getColumns(){
			$sql = " SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='".$this->table."' AND TABLE_SCHEMA='".$this->db->database."' ";
		  $query = $this->db->query($sql);
		  $rows = $query->result_array();
		  return $rows;
		}

        // set limit in datatable
		function make_datatables() {
			$this->make_query();
			if($_POST["length"] != -1) {
				$this->db->limit($_POST['length'], $_POST['start']);
			}

			$query = $this->db->get();
			return $query->result();
		}

        // fetch list of trainings
		function make_query() {
			// $this->select_column[] = 'tbltrainings.name';
			$this->db->select($this->table.'.*');
			$this->db->from($this->table);

			if(isset($_POST["search"]["value"])) {
				$this->db->group_start();
				foreach($this->select_column as $key => $value) {
					$this->db->or_like($value, $_POST["search"]["value"]);
				}
				$this->db->group_end();
			}
			if(isset($_GET['Title']) && $_GET['Title'] != null)
				$this->db->like('title', $_GET['Title']);
			if(isset($_GET['Sponsor']) && $_GET['Sponsor'] != null)
				$this->db->like('sponsor', $_GET['Sponsor']);
			if(isset($_GET['Venue']) && $_GET['Venue'] != null)
				$this->db->like('venue', $_GET['Venue']);
			if(isset($_GET['OfficeOrder']) && $_GET['OfficeOrder'] != null)
				$this->db->like('office_order', $_GET['OfficeOrder']);
			if(isset($_GET['Year']) && $_GET['Year'] != null)
				$this->db->like('YEAR(start_date)', $_GET['Year']);

			if(isset($_POST["order"])){
				$this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
			}else{
				$this->db->order_by($this->table.'.id', 'DESC');
			}
		}

		public function getTrainingsRows($id) {
		    $ret = array();

		     $this->db->select("*")->from($this->table)->where($this->table.'.id', $id);
		    // get related table date. sample only 
			// change foreign_table and foreign_key
			// $this->db->join('foreign_table', $this->table.'.id = foreign_table.foreign_key', 'left');
			
			$trainings = $this->db->get()->result_array();
            
			$ret = array("Code"=>0,"Message"=>"Success!",
                "Data"=>array(
                    "trainings" => $trainings,
                )
            );
            return $ret;
		}

        // get count of trainings
		function get_all_data() {
			$this->db->select($this->table."*");
			$this->db->from($this->table);
			return $this->db->count_all_results();
		}

        // get count of filtered trainings
		function get_filtered_data(){
			$this->make_query();
			$query = $this->db->get();
			return $query->num_rows();
		}

		// add case
		public function addRows($params) {
			$helperDao = new HelperDao();
			$code = "1";
			$params['modified_by'] = Helper::get('userid');
			$message = "Failed to add trainings.";

			// set value for tbltrainings table
			$start_date = strtotime($params['start_date']);
			$end_date = strtotime($params['end_date']);

			$start_date = date('Y-m-d', $start_date);
			$end_date = date('Y-m-d', $end_date);

			$manage_trainings['title'] = $params['title'];
			$manage_trainings['sponsor'] = $params['sponsor'];
			$manage_trainings['venue'] = $params['venue'];
			$manage_trainings['no_of_hours'] = $params['no_of_hours'];
			$manage_trainings['type'] = $params['type'];
			$manage_trainings['start_date'] = $start_date;
			$manage_trainings['end_date'] = $end_date;
			$manage_trainings['fees'] = $params['fees'];
			$manage_trainings['travel_order'] = $params['travel_order'];
			$manage_trainings['office_order'] = $params['office_order'];
			$manage_trainings['travel_allowance'] = $params['travel_allowance'];
			$attendees = isset($params['employee_ms']) && $params['employee_ms'] ? $params['employee_ms'] : 0;

			$this->db->insert('tbltrainings', $manage_trainings);
			$id = $this->db->insert_id();

			if($this->db->affected_rows() > 0) {
				// save attendees if given
				$data = array();
				if($attendees) {
					foreach($attendees as $key => $value) {
						$info['seminar_id'] = $id;
						$info['employee_id'] = $value;
						array_push($data, $info);
					}
					$this->db->insert_batch('tbltrainingsattendees', $data);
				}

				$code = "0";
				$message = "Successfully inserted trainings.";
				$helperDao->auditTrails(Helper::get('userid'), $message);
				$this->ModelResponse($code, $message);
				return true;
			} else {
				$helperDao->auditTrails(Helper::get('userid'), $message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		// update case
		public function updateRows($params) {
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Trainings details failed to update.";
			$params['modified_by'] = Helper::get('userid');
			$arr_employees = array();

			// set value for tbltrainings table
			$start_date = strtotime($params['start_date']);
			$end_date = strtotime($params['end_date']);
			$start_date = date('Y-m-d', $start_date);
			$end_date = date('Y-m-d', $end_date);
			$manage_trainings['title'] = $params['title'];
			$manage_trainings['sponsor'] = $params['sponsor'];
			$manage_trainings['venue'] = $params['venue'];
			$manage_trainings['no_of_hours'] = $params['no_of_hours'];
			$manage_trainings['type'] = $params['type'];
			$manage_trainings['start_date'] = $start_date;
			$manage_trainings['end_date'] = $end_date;
			$manage_trainings['fees'] = $params['fees'];
			$manage_trainings['travel_order'] = $params['travel_order'];
			$manage_trainings['office_order'] = $params['office_order'];
			$manage_trainings['travel_allowance'] = $params['travel_allowance'];
			$manage_trainings['modified_by'] = $params['modified_by'];
			
			foreach ($params['employee_ms'] as $employee) {
				$arr_employees[] = array(
					"seminar_id" => $params["id"],
					"employee_id" => $employee,
					"modified_by" => $params['modified_by']
				);
			}

			$this->db->trans_begin();
			$this->db->where('id', $params['id'])->update('tbltrainings',$manage_trainings);			
			$this->db->where('seminar_id', $params['id'])->delete('tbltrainingsattendees');
			$this->db->insert_batch('tbltrainingsattendees', $arr_employees);
			if($this->db->trans_status() === TRUE) {	
				$this->db->trans_commit();
				$code = "0";
				$message = "Successfully updated trainings.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;
			}
			else {
				$this->db->trans_rollback();
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		public function getTrainingsAttendees($id) {
			$this->db->select('tbltrainingsattendees.*,
				CONCAT(DECRYPTER(tblemployees.last_name,"sunev8clt1234567890",tblemployees.id),", ",DECRYPTER(tblemployees.first_name,"sunev8clt1234567890",tblemployees.id)," ",DECRYPTER(tblemployees.middle_name,"sunev8clt1234567890",tblemployees.id)) as name
			');
			$this->db->from('tbltrainingsattendees');
			$this->db->join('tblemployees', 'tbltrainingsattendees.employee_id = tblemployees.id', 'left');
			$this->db->where('seminar_id', $id);
			$result = $this->db->get()->result_array();
			if(sizeof($result) > 0){
				$code = "0";
				$message = "Successfully fetched attendees.";
				$this->ModelResponse($code, $message, $result);
				// $helperDao->auditTrails(Helper::get('userid'),$message);
				return true;		
			}else {
				$this->ModelResponse($code, $message);
				// $helperDao->auditTrails(Helper::get('userid'),$message);
			}
			return false;
		}

		public function getTrainingListsAttendees($id) {
			$emp = $this->db->select('
					tbltrainingsattendees.*,
					CONCAT(DECRYPTER(tblemployees.last_name,"sunev8clt1234567890",tblemployees.id),", ",DECRYPTER(tblemployees.first_name,"sunev8clt1234567890",tblemployees.id)," ",DECRYPTER(tblemployees.middle_name,"sunev8clt1234567890",tblemployees.id)) as name,
					tblfieldpositions.name as position_name,
					tblfielddepartments.department_name,
				')
				->from('tbltrainingsattendees')
				->join('tblemployees', 'tbltrainingsattendees.employee_id = tblemployees.id', 'left')
				->join("tblfieldpositions","tblemployees.position_id = tblfieldpositions.id","left")
				->join("tblfielddepartments","tblemployees.division_id = tblfielddepartments.id","left")
				->where('seminar_id', $id)
				->order_by('name ASC')
				->get()->result_array();
			$trainings = $this->db->select("a.*")
				->from("tbltrainings a")
				->where("a.id",$id)
				->get()->row_array();
			
			return array("employee"=>$emp, "trainings"=> $trainings);
		}

		public function getActiveEmployees() {
			$this->db->select('id, CONCAT(DECRYPTER(last_name,"sunev8clt1234567890",id), ", ", DECRYPTER(first_name,"sunev8clt1234567890",id), " ", DECRYPTER(middle_name,"sunev8clt1234567890",id)) as name');
			$this->db->from('tblemployees');
			$this->db->where('is_active', 1);
			$this->db->order_by('name');
			return $this->db->get()->result_array();
		}

		public function getEmployees($id) {
			$this->db->select('employee_id');
			$this->db->from('tbltrainingsattendees');
			$this->db->where('seminar_id', $id);
			$employees = $this->db->get()->result_array();
			$ids = array();
			if($employees) {
				foreach($employees as $key => $value) {
					$ids[] = $value['employee_id'];
				}
				$this->db->where_not_in('id', $ids);
			}

			$this->db->select('id, CONCAT(DECRYPTER(last_name,"sunev8clt1234567890",id), ", ", DECRYPTER(first_name,"sunev8clt1234567890",id), " ", DECRYPTER(middle_name,"sunev8clt1234567890",id)) as name');
			$this->db->from('tblemployees');
			$this->db->where('is_active', 1);
			$this->db->order_by('name');
			return $this->db->get()->result_array();

		}
    }
?>
