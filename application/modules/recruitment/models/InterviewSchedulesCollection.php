<?php
    class InterviewSchedulesCollection extends Helper {
        var $select_column = null;
		public function __construct() {
			$this->load->model('HelperDao');
			$this->load->model('MessageRels');
			$this->load->model('SystemMessages');
			$this->load->model('ApplicantsCollection');
			ModelResponse::busy();
			$columns = $this->getColumns();
			foreach ($columns as $key => $value) {
				$this->select_column[] = $this->table.'.'.$value;
			}
		}

        // set orderable columns in interview schedule list
        var $table = "tblrecruitmentinterviewschedules";
		var $order_column = array(
		
			'',
			'tblrecruitmentinterviewschedules.id',
			'tblrecruitmentapplicants.first_name',
			'position',
			'tblrecruitmentinterviewschedules.schedule_date',
			'tblrecruitmentinterviewschedules.schedule_time',
			'tblrecruitmentinterviewschedules.remarks',
		);

        // set searchable parameters in tblrecruitmentinterviewschedules table
		public function getColumns() {
			$rows = array(
				'id',
				'schedule_date',
				'schedule_time',
				'remarks',
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

        // fetch list of interview schedule
		function make_query() {
			$this->select_column[] = 'tblfieldpositions.name';
			$this->db->select(
			'tblrecruitmentapplicants.first_name,  
			tblrecruitmentapplicants.middle_name,  
			tblrecruitmentapplicants.last_name, 
			tblrecruitmentapplicants.extension,
			tblrecruitmentapplicants.*,'.
			$this->table.'.*,
			tblfieldpositions.name as position');
			$this->db->from($this->table);

			$this->db->join('tblrecruitmentapplicants', $this->table.'.applicant_id = tblrecruitmentapplicants.id', 'left');
			$this->db->join('tblrecruitmentvacancies', 'tblrecruitmentapplicants.vacancy_id = tblrecruitmentvacancies.id', 'left');
			$this->db->join('tblfieldpositions', 'tblrecruitmentvacancies.position_id = tblfieldpositions.id', 'left');


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

			if(isset($_GET['VacancyID']) && $_GET['VacancyID'] != null)
				 $this->db->like('tblrecruitmentvacancies.id', $_GET['VacancyID']);
				 
			if(isset($_POST["order"])){  
					$this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
				}else{  
					$this->db->order_by('tblrecruitmentapplicants.first_name');
				}
		}

		public function getInterviewSchedulesRows($id) {
		    $ret = array();

		    // get related table date. sample only
		    // change variable name, foreign_table and foreign_key
		    $sample_data = $this->db->select("*")->from($this->table)->where('id', $id)->get()->result_array();

            $ret = array("Code"=>0,"Message"=>"Success!",
                "Data"=>array(
                    "sample_data" => $sample_data,
                )
            );
            return $ret;
		}

        // get count of interview schedule
		function get_all_data() {
			$this->db->select($this->table."*");
			$this->db->from($this->table);
			return $this->db->count_all_results();
		}

        // get count of filtered interview schedule
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
			$message = "Failed to add interview schedule";

			// set value for tblrecruitmentinterviewschedules table
			$applicant_id = $params['id'];
			$schedule_date = strtotime($params['schedule_date']);
			$schedule_date = date('Y-m-d', $schedule_date);
			$schedule_time = $params['schedule_time'];
			$remarks = $params['remarks'];

			$interview_schedule['applicant_id'] = $applicant_id;
			$interview_schedule['schedule_date'] = $schedule_date;
			$interview_schedule['schedule_time'] = $schedule_time;
			$interview_schedule['remarks'] = $remarks;

			$this->db->insert('tblrecruitmentinterviewschedules', $interview_schedule);
			$id = $this->db->insert_id();


			if($this->db->affected_rows() > 0) {
				$ret = new ApplicantsCollection();
				$applicant = $ret->getApplicantDetails($applicant_id);

				$full_name = "{$applicant->first_name} {$applicant->middle_name} {$applicant->last_name} {$applicant->extension}";
				$position = $applicant->position;
				$email = $applicant->email;
				$subject = 'National Water Resources Board Interview Details';

				// Compose a simple HTML email message
				$message = '<html><head><style>.row {width: 100%;}.col_1 {width: 20%;float: left;}.col_2 {width: 5%;float: left;}.col_3 {width: 75%;float: left;border-bottom: 1px solid #000;}.col_4{width: 15%;float: left;text-align: center;}.col_5{width: 27.5%;float: left;border-bottom: 1px solid #000;}.col_6{width: 75%;float: left;}</style></head><body>';
				$message .= '<img src="'. base_url() . 'assets/custom/images/nwrb.png' . '" style="display: block; margin-left: auto; margin-right: auto; height: 75px;">';
				$message .= '<h3>INTERVIEW NOTICE</h3>';
				$message .= '<div style="width: 70%;">';
				$message .= '<div class="row"><b>Name of Applicant:</b> '. $full_name .'</div>';
				$message .= '<div class="row"><b>Position Applied:</b> '. $position .'</div>';
				$message .= '<div class="row"><b>Date:</b> '. $schedule_date .'</div>';
				$message .= '<div class="row"><b>Time:</b> '. date("g:i a", strtotime($schedule_time)) .'</div>';
				$message .= '<div class="row"><b>Place:</b> 8th Floor, NIA Building, EDSA, Diliman. Quezon City</div>';
				$message .= '<div class="row"><b>Remarks:</b> '.(isset($params['remarks']) && $params['remarks'] != "" ? $params['remarks'] : "N/A").'</div>';
				$message .= '</div>';
				$message .= '<div style="width: 70%; padding-top: 120px">';
				$message .= '<p>Look for <u>JESUSA T. DELOS SANTOS</u> (8920-2793) of the Personnel and Records Section</p>';
				$message .= '<p>Please come on time.</p><p>Thank you.</p><br><p><strong>NWRB Human Resource Merit Promotion and Selection Board</strong></p>';
				$message .= '</div>';
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
			} else {
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		// update case
		public function updateRows($params) {
			$helperDao = new HelperDao();
			$code = "1";
			$params['modified_by'] = Helper::get('userid');
			$message = "Interview Schedule detail failed to update.";
			$sysmessages = new SystemMessages();

			// set value for tblrecruitmentinterviewschedules table
			$applicant_id = $params['applicant_id'];
			$schedule_date = strtotime($params['schedule_date']);
			$schedule_date = date('Y-m-d', $schedule_date);
			$schedule_time = $params['schedule_time'];
			$remarks = $params['remarks'];

			$schedule['id'] = $params['id'];
			$schedule['modified_by'] = $params['modified_by'];
			$schedule['schedule_date'] = $schedule_date;
			$schedule['schedule_time'] = $schedule_time;
			$schedule['remarks'] = $remarks;

			$this->db->where('id', $params['id']);
			if ($this->db->update('tblrecruitmentinterviewschedules',$schedule) === FALSE){
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			else {
				$ret = new ApplicantsCollection();
				$applicant = $ret->getApplicantDetails($applicant_id);

				$full_name = "{$applicant->first_name} {$applicant->middle_name} {$applicant->last_name} {$applicant->extension}";
				$position = $applicant->position;
				$email = $applicant->email;
				$subject = 'National Water Resources Board Interview Details';

				// Compose a simple HTML email message
				$message = '<html><head><style>.row {width: 100%;}.col_1 {width: 20%;float: left;}.col_2 {width: 5%;float: left;}.col_3 {width: 75%;float: left;border-bottom: 1px solid #000;}.col_4{width: 15%;float: left;text-align: center;}.col_5{width: 27.5%;float: left;border-bottom: 1px solid #000;}.col_6{width: 75%;float: left;}</style></head><body>';
				$message .= '<img src="'. base_url() . 'assets/custom/images/nwrb.png' . '" style="display: block; margin-left: auto; margin-right: auto; height: 75px;">';
				$message .= '<h3>INTERVIEW NOTICE</h3>';
				$message .= '<div style="width: 70%;">';
				$message .= '<div class="row"><b>Name of Applicant:</b> '. $full_name .'</div>';
				$message .= '<div class="row"><b>Position Applied:</b> '. $position .'</div>';
				$message .= '<div class="row"><b>Date:</b> '. $schedule_date .'</div>';
				$message .= '<div class="row"><b>Time:</b> '. date("g:i a", strtotime($schedule_time)) .'</div>';
				$message .= '<div class="row"><b>Place:</b> 8th Floor, NIA Building, EDSA, Diliman. Quezon City</div>';
				$message .= '<div class="row"><b>Remarks:</b> '.(isset($params['remarks']) && $params['remarks'] != "" ? $params['remarks'] : "N/A").'</div>';
				$message .= '</div>';
				$message .= '<div style="width: 70%; padding-top: 120px">';
				$message .= '<p>Look for <u>JESUSA T. DELOS SANTOS</u> (8920-2793) of the Personnel and Records Section</p>';
				$message .= '<p>Please come on time.</p><p>Thank you.</p><br><p><strong>NWRB Human Resource Merit Promotion and Selection Board</strong></p>';
				$message .= '</div>';
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
			return false;
		}
    }
?>
