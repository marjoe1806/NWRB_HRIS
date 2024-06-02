<?php
    class ApplicantChecklistsCollection extends Helper {
        var $select_column = null;
		
		public function __construct() {
			$this->load->model('HelperDao');
			ModelResponse::busy();
			$columns = $this->getColumns();
			foreach ($columns as $key => $value) {
				$this->select_column[] = $this->table.'.'.$value;
			}
		}

        // set orderable columns in applicant checklist list
        var $table = "tblrecruitmentchecklists";
		var $order_column = array(
			"tblrecruitmentchecklists.id",
		);

        // set searchable parameters in tblrecruitmentchecklists table
		public function getColumns() {
			$rows = array(
				'id',
				'applicant_id',
				'position_id',
				'supervisor_id',
				'start_date',
			);
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

        // fetch list of applicant checklist
		function make_query() {
			$this->select_column[] = 'CONCAT(tblrecruitmentapplicants.first_name, " ", tblrecruitmentapplicants.middle_name, " ", tblrecruitmentapplicants.last_name, " ", tblrecruitmentapplicants.extension)';
			$this->select_column[] = 'CONCAT(tblrecruitmentapplicants.first_name, " ", tblrecruitmentapplicants.last_name)';
			$this->db->select($this->table.'.*,
				tblfieldpositions.name as position,
				supervisor_id as employee_id,
				tblrecruitmentapplicants.first_name, tblrecruitmentapplicants.middle_name, tblrecruitmentapplicants.last_name, tblrecruitmentapplicants.extension,
				CONCAT(DECRYPTER(tblemployees.last_name,"sunev8clt1234567890",tblemployees.id),", ",DECRYPTER(tblemployees.first_name,"sunev8clt1234567890",tblemployees.id)," ",DECRYPTER(tblemployees.middle_name,"sunev8clt1234567890",tblemployees.id)) as supervisor
			');

			$this->db->from($this->table);

			$this->db->join('tblrecruitmentapplicants', $this->table.'.applicant_id = tblrecruitmentapplicants.id', 'left');
			$this->db->join('tblfieldpositions', $this->table.'.position_id = tblfieldpositions.id', 'left');
			$this->db->join('tblemployees', $this->table.'.supervisor_id = tblemployees.id', 'left');
			$this->db->group_by($this->table.'.id');

			if(isset($_POST["search"]["value"])) {
				$this->db->group_start();
				foreach($this->select_column as $key => $value) {
					$this->db->or_like($value, $_POST["search"]["value"]);
				}
				$this->db->group_end();
			}

            if(isset($_GET['Name']) && $_GET['Name'] != null) {
				$full_name = 'CONCAT(tblrecruitmentapplicants.first_name, " ", tblrecruitmentapplicants.middle_name, " ", tblrecruitmentapplicants.last_name, " ", tblrecruitmentapplicants.extension)';
				$clean_name = 'CONCAT(tblrecruitmentapplicants.first_name, " ", tblrecruitmentapplicants.last_name)';

				$this->db->group_start();
				$this->db->like('tblrecruitmentapplicants.last_name', $_GET['Name']);
				$this->db->or_like('tblrecruitmentapplicants.last_name', $_GET['Name'], 'both');
				$this->db->or_like('tblrecruitmentapplicants.middle_name', $_GET['Name'], 'both');
				$this->db->or_like('tblrecruitmentapplicants.extension', $_GET['Name'], 'both');
				$this->db->or_like($full_name, $_GET['Name'], 'both');
				$this->db->or_like($clean_name, $_GET['Name'], 'both');
				$this->db->group_end();
			}

			if(isset($_GET['Position']) && $_GET['Position'] != null)
				$this->db->where($this->table.'.position_id = "'.$_GET['Position'].'"');

			if(isset($_GET['Supervisor']) && $_GET['Supervisor'] != null)
				$this->db->where($this->table.'.supervisor_id = "'.$_GET['Supervisor'].'"');
		}

		public function getApplicantChecklistsRows($id) {
		    $ret = array();
			$this->db->select("*")->from($this->table)->where($this->table.'.id', $id);
			$applicantchecklists = $this->db->get()->result_array();
            
			$ret = array("Code"=>0,"Message"=>"Success!",
                "Data"=>array(
                    "applicantchecklists" => $applicantchecklists,
                )
            );
            return $ret;
		}

		public function getApplicantChecklistItemRows($id) {
			$ret = array();
			$this->db->select("tblfieldguidelines.id, 
				tblrecruitmentchecklistitems.checklist_id,
				tblfieldguidelines.name,
				tblfieldguidelines.category,
				tblrecruitmentchecklistitems.guideline_id,
				tblrecruitmentchecklistitems.status,
				tblrecruitmentchecklistitems.modified_by");
			$this->db->from('tblrecruitmentchecklistitems')->where('checklist_id', $id)->order_by('guideline_id', 'ASC');
			$this->db->join('tblfieldguidelines', 'tblrecruitmentchecklistitems.guideline_id = tblfieldguidelines.id', 'left');

			$details = $this->db->get()->result_array();

			// get guideline items
			$this->db->select("*")->from('tblfieldguidelineitems')->where('tblfieldguidelineitems.status', 'ACTIVE')->order_by('guideline_id', 'ASC')->order_by('tblfieldguidelineitems.ctr', 'ASC');
			$items = $this->db->get()->result_array();
			$ids = array();
			foreach($details as $key => $value) {
				$ids[] = $value['id'];
				if($value['status'] == "1") {
					$details[$key]['is_checked'] = "checked";
				} else {
					$details[$key]['is_checked'] = "";
				}
			}

			// get guidlines
			$this->db->from('tblfieldguidelines');
			$this->db->where_not_in('id', $ids);
			$this->db->where('status', 'ACTIVE');
			$this->db->order_by('id', 'ASC');
			$guidelines = $this->db->get()->result_array();

			foreach($guidelines as $key => $value) {
				$details[] = $value;
			}

			$ret = array("Code"=>"0", "Message"=>"Success!",
				"Data"=>array(
					"details" => $details,
					"items" => $items,
				)
			);
			return $ret;
		}

        // get count of applicant checklist
		function get_all_data() {
			$this->db->select($this->table."*");
			$this->db->from($this->table);
			return $this->db->count_all_results();
		}

        // get count of filtered applicant checklist
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
			$message = "Failed to add applicant checklist";

			// set value for tblrecruitmentchecklists table
			$applicant_checklist['applicant_id'] = $params['applicant_id'];
			$applicant_checklist['position_id'] = $params['position_id'];
			$applicant_checklist['supervisor_id'] = $params['employee_id'];
			$start_date = strtotime($params['start_date']);
			$applicant_checklist['start_date'] = date('Y-m-d', $start_date);

			$this->db->insert('tblrecruitmentchecklists', $applicant_checklist);
			$id = $this->db->insert_id();

			// add checklist guidelines
			$data['id'] = $id;
			$data['first_day'] = isset($params['first_day']) && $params['first_day'] ? $params['first_day'] : 0;
			$data['policies'] = isset($params['policies']) && $params['policies'] ? $params['policies'] : 0;
			$data['administrative_procedure'] = isset($params['administrative_procedure']) && $params['administrative_procedure'] ? $params['administrative_procedure'] : 0;
			$data['general_orientation'] = isset($params['general_orientation']) && $params['general_orientation'] ? $params['general_orientation'] : 0;
			$data['position_information'] = isset($params['position_information']) && $params['position_information'] ? $params['position_information'] : 0;

			$this->addApplicationChecklistItems($data);

			if($this->db->affected_rows() > 0) {
				$code = "0";
				$message = "Successfully inserted applicant checklist";
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
			$message = "Applicant Checklist detail failed to update.";
			$params['modified_by'] = Helper::get('userid');

			// set value for tblrecruitmentchecklists table
			$applicant_checklist['applicant_id'] = $params['applicant_id'];
			$applicant_checklist['position_id'] = $params['position_id'];
			$applicant_checklist['supervisor_id'] = $params['employee_id'];
			$start_date = strtotime($params['start_date']);
			$applicant_checklist['start_date'] = date('Y-m-d', $start_date);

			$this->db->where('id', $params['id']);
			if ($this->db->update('tblrecruitmentchecklists',$applicant_checklist) === FALSE){
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			else {
				$code = "0";
				$message = "Successfully updated applicant checklist";

				$data['id'] = $params['id'];
				$data['first_day'] = isset($params['first_day']) && $params['first_day'] ? $params['first_day'] : 0;
				$data['policies'] = isset($params['policies']) && $params['policies'] ? $params['policies'] : 0;
				$data['administrative_procedure'] = isset($params['administrative_procedure']) && $params['administrative_procedure'] ? $params['administrative_procedure'] : 0;
				$data['general_orientation'] = isset($params['general_orientation']) && $params['general_orientation'] ? $params['general_orientation'] : 0;
				$data['position_information'] = isset($params['position_information']) && $params['position_information'] ? $params['position_information'] : 0;
				$data['params'] = $params;

				$this->updateApplicationChecklistItems($data);

				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;
			}
			return false;
		}

		// add checklist items
		public function addApplicationChecklistItems($params) {
			$checklist_id = $params['id'];

			if ($params['first_day']) {
				foreach($params['first_day'] as $key => $value) {
					$data['checklist_id'] = $checklist_id;
					$data['guideline_id'] = $key;
					$data['status'] = $value;

					$this->db->insert('tblrecruitmentchecklistitems', $data);
				}
			}
			if ($params['policies']) {
				foreach($params['policies'] as $key => $value) {
					$data['checklist_id'] = $checklist_id;
					$data['guideline_id'] = $key;
					$data['status'] = $value;

					$this->db->insert('tblrecruitmentchecklistitems', $data);
				}
			}
			if ($params['administrative_procedure']) {
				foreach($params['administrative_procedure'] as $key => $value) {
					$data['checklist_id'] = $checklist_id;
					$data['guideline_id'] = $key;
					$data['status'] = $value;

					$this->db->insert('tblrecruitmentchecklistitems', $data);
				}
			}
			if ($params['general_orientation']) {
				foreach($params['general_orientation'] as $key => $value) {
					$data['checklist_id'] = $checklist_id;
					$data['guideline_id'] = $key;
					$data['status'] = $value;

					$this->db->insert('tblrecruitmentchecklistitems', $data);
				}
			}
			if ($params['position_information']) {
				foreach($params['position_information'] as $key => $value) {
					$data['checklist_id'] = $checklist_id;
					$data['guideline_id'] = $key;
					$data['status'] = $value;

					$this->db->insert('tblrecruitmentchecklistitems', $data);
				}
			}
		}

		public function updateApplicationChecklistItems($params) {
			$checklist_id = $params['id'];

			if ($params['first_day']) {
				foreach($params['first_day'] as $key => $value) {
					$data['checklist_id'] = $checklist_id;
					$data['guideline_id'] = $key;
					$data['status'] = $value;

					$this->db->from('tblrecruitmentchecklistitems');
					$this->db->where('checklist_id', $checklist_id);
					$this->db->where('guideline_id', $key);
					$query = $this->db->get();
					if($query->num_rows() > 0) {
						$this->db->set('status', $value);
						$this->db->where(array('checklist_id' => $checklist_id, 'guideline_id' => $key));
						$this->db->update('tblrecruitmentchecklistitems');
					} else {
						$this->db->insert('tblrecruitmentchecklistitems', $data);
					}
				}
			}
			if ($params['policies']) {
				foreach($params['policies'] as $key => $value) {
					$data['checklist_id'] = $checklist_id;
					$data['guideline_id'] = $key;
					$data['status'] = $value;

					$this->db->from('tblrecruitmentchecklistitems');
					$this->db->where('checklist_id', $checklist_id);
					$this->db->where('guideline_id', $key);
					$query = $this->db->get();
					if($query->num_rows() > 0) {
						$this->db->set('status', $value);
						$this->db->where(array('checklist_id' => $checklist_id, 'guideline_id' => $key));
						$this->db->update('tblrecruitmentchecklistitems');
					} else {
						$this->db->insert('tblrecruitmentchecklistitems', $data);
					}
				}
			}
			if ($params['administrative_procedure']) {
				foreach($params['administrative_procedure'] as $key => $value) {
					$data['checklist_id'] = $checklist_id;
					$data['guideline_id'] = $key;
					$data['status'] = $value;

					$this->db->from('tblrecruitmentchecklistitems');
					$this->db->where('checklist_id', $checklist_id);
					$this->db->where('guideline_id', $key);
					$query = $this->db->get();
					if($query->num_rows() > 0) {
						$this->db->set('status', $value);
						$this->db->where(array('checklist_id' => $checklist_id, 'guideline_id' => $key));
						$this->db->update('tblrecruitmentchecklistitems');
					} else {
						$this->db->insert('tblrecruitmentchecklistitems', $data);
					}
				}
			}
			if ($params['general_orientation']) {
				foreach($params['general_orientation'] as $key => $value) {
					$data['checklist_id'] = $checklist_id;
					$data['guideline_id'] = $key;
					$data['status'] = $value;

					$this->db->from('tblrecruitmentchecklistitems');
					$this->db->where('checklist_id', $checklist_id);
					$this->db->where('guideline_id', $key);
					$query = $this->db->get();
					if($query->num_rows() > 0) {
						$this->db->set('status', $value);
						$this->db->where(array('checklist_id' => $checklist_id, 'guideline_id' => $key));
						$this->db->update('tblrecruitmentchecklistitems');
					} else {
						$this->db->insert('tblrecruitmentchecklistitems', $data);
					}
				}
			}
			if ($params['position_information']) {
				foreach($params['position_information'] as $key => $value) {
					$data['checklist_id'] = $checklist_id;
					$data['guideline_id'] = $key;
					$data['status'] = $value;

					$this->db->from('tblrecruitmentchecklistitems');
					$this->db->where('checklist_id', $checklist_id);
					$this->db->where('guideline_id', $key);
					$query = $this->db->get();
					if($query->num_rows() > 0) {
						$this->db->set('status', $value);
						$this->db->where(array('checklist_id' => $checklist_id, 'guideline_id' => $key));
						$this->db->update('tblrecruitmentchecklistitems');
					} else {
						$this->db->insert('tblrecruitmentchecklistitems', $data);
					}
				}
			}

			// Check if all checklist items' status is 1
			$cleared = $this->checkApplicantOnboardingStatus($checklist_id);

			if($cleared) {
				$id = $params['params']['id'];
				$applicant_id = $params['params']['applicant_id'];

				$this->completeApplicantOnboarding($id, $applicant_id);;
			}
		}

		public function checkApplicantOnboardingStatus($checklist_id) {

			$sql = "SELECT DISTINCT status FROM tblrecruitmentchecklistitems WHERE checklist_id = ?";
			$sql_params = $checklist_id;

			$query = $this->db->query($sql, $sql_params);

			$res = $query->result();

			$status = false;

			if(count($res) == 1 && $res[0]->status == 1)  {
				$status =  true;
			}

			return $status;
		}

		public function completeApplicantOnboarding($id, $applicant_id) {

			$helperDao = new HelperDao();

			$applicant = $this->db->select('
					last_name,
					middle_name,
					first_name,
					extension,
					email
					')
					->from('tblrecruitmentapplicants')->where('id', $applicant_id)->get()->result_array();
				$applicant = $applicant[0];

				if($applicant['email']) {
					$today = date("F j, Y");
					$full_name = "{$applicant['first_name']} {$applicant['middle_name']} {$applicant['last_name']} {$applicant['extension']}";
					$email = $applicant['email'];
					$subject = 'NWRB Onboarding Update';

					// Compose a simple HTML email message
					$message = '<html><style>p {font-size:12px;} </style><body>';
					$message .= '<img src="'. base_url() . 'assets/custom/images/nwrb.png' . '" style="display: block; margin-left: auto; margin-right: auto; height: 75px;">';
					$message .= '<p>' . $today . '</p><br><br>';
					$message .= '<p style="font-weight:bold">' . $full_name . '</p><br>';
					$message .= '<p>Good day ' . $applicant['first_name'] . ',</p>';
					$message .= '<p>Thank you for reaching out regarding your onboarding with National Water Resources Board</p>';
					$message .= '<p>** do-not-reply sample template only</p><br>';
					$message .= '<p>Thank you, </p>';
					$message .= '<p style="font-weight: bold;">Human Resource Marit Promotion and Selection Board</p>';
					$message .= '</body></html>';

					// Sending email
					$data = $helperDao->sendMail2($subject, $email, $message);
					$code = $data['code'];
					$message = $data['message'];

					if($code == "1"){
						$helperDao->auditTrails(Helper::get('userid'),$message);
						$this->ModelResponse($code, $message);
						return true;
					} else {
						$helperDao->auditTrails(Helper::get('userid'),$message);
						$this->ModelResponse($code, $message);
						return false;
					}
				}

		}

    }
?>
