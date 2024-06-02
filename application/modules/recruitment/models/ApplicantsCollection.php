<?php
    class ApplicantsCollection extends Helper {
        var $select_column = null;
		public function __construct() {
			$this->load->model('HelperDao');
			$this->load->model('MessageRels');
			$this->load->model('SystemMessages');
			$this->load->model('JobOpeningsCollection');
			ModelResponse::busy();
			$columns = $this->getColumns();
			foreach ($columns as $key => $value) {
				$this->select_column[] = $this->table.'.'.$value;
			}
		}

        // set orderable columns in applicant list
        var $table = "tblrecruitmentapplicants";
		var $order_column = array(null,'id','first_name','position','email','contact_number','application_status');
        // set searchable parameters in tblrecruitmentapplicants table
		public function getColumns() {
			$rows = array('id','last_name','email','contact_number','application_status');
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

        // fetch list of applicant
		function make_query() {
			$this->select_column[] = 'tblrecruitmentapplicants.id';
			$this->select_column[] = 'CONCAT(tblrecruitmentapplicants.first_name, " ", tblrecruitmentapplicants.middle_name, " ", tblrecruitmentapplicants.last_name, " ", tblrecruitmentapplicants.extension)';
			$this->select_column[] = 'tblfieldpositions.name';
			$this->select_column[] = 'tblrecruitmentapplicants.email';
			$this->select_column[] = 'tblrecruitmentapplicants.contact_number';
			$this->select_column[] = 'tblrecruitmentapplicants.application_status';
			$this->select_column[] = 'CONCAT(tblrecruitmentapplicants.first_name, " ", tblrecruitmentapplicants.last_name)';

			$this->db->select(
				
				$this->table.'.*, 
				tblfieldpositions.name as position,
				tblfieldpositions.id as position_id,
				tblrecruitmentinterviewschedules.id as schedule_id,
				tblrecruitmentinterviewschedules.schedule_date,
				tblrecruitmentinterviewschedules.schedule_time,
				tblrecruitmentinterviewschedules.remarks,
				tblrecruitmentexaminationschedules.id as examination_schedule_id,
				tblrecruitmentexaminationschedules.status as examination_schedule_status,
				tblrecruitmentexaminationschedules.schedule_date as examination_schedule_date,
				tblrecruitmentexaminationschedules.schedule_time as examination_schedule_time,
				tblrecruitmentexaminationschedules.remarks as examination_remarks,
				tblrecruitmentchecklists.id as checklist,
				tblrecruitmentapplicants.id as applicant_id,
				tblemployees.employee_number'

			);
			$this->db->from($this->table);
			$this->db->join('tblrecruitmentvacancies', $this->table.'.vacancy_id = tblrecruitmentvacancies.id', 'left');
			$this->db->join('tblfieldpositions', 'tblrecruitmentvacancies.position_id = tblfieldpositions.id', 'left');
			$this->db->join('tblrecruitmentinterviewschedules', $this->table.'.id = tblrecruitmentinterviewschedules.applicant_id', 'left');
			$this->db->join('tblrecruitmentexaminationschedules', $this->table.'.id = tblrecruitmentexaminationschedules.applicant_id', 'left');
			$this->db->join('tblrecruitmentchecklists', $this->table.'.id = tblrecruitmentchecklists.applicant_id', 'left');
			$this->db->join('tblemployees', $this->table.'.id = tblemployees.applicant_id', 'left');

			$this->db->group_by($this->table.'.id');

			if(isset($_POST["search"]["value"])) {
				$this->db->group_start();
				foreach($this->select_column as $key => $value) {
					$this->db->or_like($value, $_POST["search"]["value"]);
				}
				$this->db->group_end();
			}

			if(isset($_GET['VacancyID']) && $_GET['VacancyID'] != null)
				$this->db->where($this->table.'.vacancy_id = "'.$_GET['VacancyID'].'"');

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

            if(isset($_GET['Status']) && $_GET['Status'] != null) {
            	$this->db->like($this->table.'.application_status', $_GET['Status']);
			}
			
			if(isset($_POST["order"])){  
				$this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
			}else{  
				// $this->db->order_by("DECRYPTER(tblemployees.first_name,'sunev8clt1234567890',tblemployees.id)");
				$this->db->order_by($this->table.'.id', 'DESC');
			}
		}

		public function getApplicantsRows($id) {
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

        // get count of applicant
		function get_all_data() {
			$this->db->select($this->table."*");
			$this->db->from($this->table);
			return $this->db->count_all_results();
		}

        // get count of filtered applicant
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
			$message = "Failed to add applicant";

			// set value for tblrecruitmentapplicants table
			$applicant['vacancy_id'] = $params['vacancy_id'];
			$applicant['last_name'] = $params['last_name'];
			$applicant['first_name'] = $params['first_name'];
			$applicant['middle_name'] = $params['middle_name'];
			$applicant['extension'] = $params['extension'];
			$applicant['email'] = $params['email'];
			$applicant['contact_number'] = $params['contact_number'];
			// $applicant['filename'] = $params['filename'];

			$this->db->insert('tblrecruitmentapplicants', $applicant);
			$id = $this->db->insert_id();

			foreach ($params['filename'] as $key => $value) {
				$data = array(
					'recruitmentapplicants_id' => $id,
					'filename' => $value
				);
				$this->db->insert('tblrecruitmentapplicantsupload', $data);
			}

			if($this->db->affected_rows() > 0) {
				$code = "0";
				$message = "Successfully inserted applicant";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;
			} else {
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		//update file
		public function updateRows($params) {
			// print_r($params); die();
			$helperDao = new HelperDao();
			$code = "1";
			$paramsupdate['modified_by'] = Helper::get('userid');
			$paramsupdate['vacancy_id'] = $params['vacancy_id'];
			$paramsupdate['first_name'] = $params['first_name'];
			$paramsupdate['middle_name'] = $params['middle_name'];
			$paramsupdate['last_name'] = $params['last_name'];
			$paramsupdate['extension'] = $params['extension'];
			$paramsupdate['email'] = $params['email'];
			$paramsupdate['contact_number'] = $params['contact_number'];
			$message = "Applicant detail failed to update.";
			$this->db->where('id', $params['id']);
			if ($this->db->update('tblrecruitmentapplicants',$paramsupdate) === FALSE){
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			else {
				$code = "0";
				$message = "Successfully updated applicant.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);

				for ($i=0; $i < count($params['filename']) ; $i++) { 
					if ($params['filename'][$i] != ""){
						$data['filename'] = $params['filename'][$i];
						$sql = "UPDATE tblrecruitmentapplicantsupload SET filename = ? WHERE id = ? ";
						$sql_params = array($data['filename'],$params['ids'][$i]);
						$this->db->query($sql,$sql_params);
					}
				}

				for ($i=0; $i < count($params['newfile']) ; $i++) { 
					if ($params['newfile'][$i] != ""){
						$data['filename'] = $params['newfile'][$i];
						$sql = "INSERT INTO tblrecruitmentapplicantsupload (recruitmentapplicants_id, filename) VALUES (?,?)";
						$sql_params = array($params['id'],$data['filename']);
						$this->db->query($sql,$sql_params);
					}
				}

				return true;
			}
			return false;
		}

		//delete file
		public function deletefile($params) {
			$helperDao = new HelperDao();
			$code = "1";
			$message = "failed to delete file.";
			$this->db->delete('tblrecruitmentapplicantsupload', array('id' => $params['id']));
			if ($this->db->trans_status() === TRUE){
				$this->db->trans_commit();
				$code = "0";
				$message = "Successfully deleted file.";
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



		// get applicant status
		public function getApplicantStatus($id) {
			$this->db->select('*');
			$this->db->from('tblrecruitmentapplicants');
			$this->db->where('id', $id);

			$query = $this->db->get();

			if($query->num_rows() > 0) {
				$ret = $query->row();
				return $ret->application_status;
			}
			return false;
		}

		// get applicant detail
		public function getApplicantDetails($id) {
			$this->db->select($this->table.'.*, tblfieldpositions.name as position, tblrecruitmentvacancies.place');
			$this->db->from('tblrecruitmentapplicants');
			$this->db->join('tblrecruitmentvacancies', $this->table.'.vacancy_id = tblrecruitmentvacancies.id', 'left');
			$this->db->join('tblfieldpositions', 'tblrecruitmentvacancies.position_id = tblfieldpositions.id', 'left');
			$this->db->where($this->table.'.id', $id);

			$query = $this->db->get();

			if($query->num_rows() > 0) {
				$ret = $query->row();
				return $ret;
			}
			return false;
		}

		// accept applicant
		public function acceptApplicant($params) {
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Failed to approve applicant";

			$data['id'] = isset($params['id'])?$params['id']:'';
			$data['status'] = 'ACCEPTED';
			$data['modified_by'] = Helper::get('userid');

			$sql = "UPDATE tblrecruitmentapplicants SET application_status = ?, modified_by = ? WHERE id = ?";
			$sql_params = array($data['status'],$data['modified_by'],$data['id']);

			$this->db->query($sql, $sql_params);
			if($this->db->affected_rows() > 0){
				$today = date("F j, Y");
				$applicant = $this->getApplicantDetails($params['id']);
				$full_name = "{$applicant->first_name} {$applicant->middle_name} {$applicant->last_name} {$applicant->extension}";
				$position = $applicant->position;
				$email = $applicant->email;
				$contact_number = $applicant->contact_number;
				$subject = 'NWRB Job Application Status';

				// old email format
				// $message = '<html><style>p {font-size:12px;} </style><body>';
				// $message .= '<img src="'. base_url() . 'assets/custom/images/nwrb.png' . '" style="display: block; margin-left: auto; margin-right: auto; height: 75px;">';
				// $message .= '<p>' . $today . '</p><br><br>';
				// $message .= '<p>' . $full_name . '</p><br>';
				// $message .= '<p>Dear ' . $applicant->first_name . ',</p>';
				// $message .= '<p>CONGRATULATIONS, this is to formally inform you that you are hired as '. $position . ' at the National Water Resources Board.</p>';
				// $message .= '<p>Kindly coordinate with the Human Resource Section upon receipt of this letter for the necessary instructions and other details.</p>';
				// $message .= '<p>We are happy to welcome you to our organization. Please contact these numbers for any clarifications. personnel@nwrb.gov.ph, (632)8 920-2724, (632)8 920 2834.</p><br>';
				// $message .= '<p>Sincerely yours,</p><br><br>';
				// $message .= '<p style="font-weight: bold;">JESUSA T. DELOS SANTOS</p>';
				// $message .= '<p>Administrative Officer V - PRS</p>';
				// $message .= '</body></html>';

				// new email format
				$message = '<html><style>p {font-size:12px;} </style><body>';
				$message .= '<img src="'. base_url() . 'assets/custom/images/nwrb-header.png' . '" style="display: block; margin-left: auto; margin-right: auto; height: 75px; float: center;">';
				$message .= '<p>' . $today . '</p><br>';
				$message .= '<b>' . $full_name . '</b><br>';
				$message .= '' . $email . '<br>';
				$message .= '' . ($contact_number != "N/A" ? $contact_number : "") . '<br><br>';
				$message .= '<p>Dear ' . $applicant->first_name . ',</p>';
				$message .= '<p>Greetings!</p>';
				$message .= '<p>This refers to your application for the position of Accountant II in the Administrative and Financial Division. After a thorough assessment and careful consideration of the qualifications and professional experiences of all the applicants and the job requirements of the position by the NWRB HRMPSB, we are pleased to inform you that you have been selected by the Appointing Authority for the position.</p>';
				$message .= '<p>In view thereof, please submit the following documents for the processing of your appointment, to wit:</p>';
				$message .= '<p>1. Original copy of the authenticated <b>certificate of eligibility/rating/license</b> for <b><i>original</i></b> appointment or <b><i>promotion</i></b>.</p>';
				$message .= '<p><b>Valid professional license</b> issued by the Professional Regulation Commission (PRC).</p>';
				$message .= '<p>2.	<b>Medical Certificate (CS Form No. 211, Revised 2018).</b> A Medical Certificate issued by a licensed government physician which states that the appointee is fit for employment is required for <b><i>original</i></b> appointment.</p>';
				$message .= '<p>The results of the Pre-employment Medical-Physical-<b>Psychological</b> examinations consisting of Blood Test, Urinalysis, Chest X-ray, Drug Test, Psychological Test, shall be attached to the medical certificate for employment.</p>';
				$message .= '<p>3.	<b>Certificate of Live Birth</b> duly authenticated by the PSA or the LCR of the municipality or city where the birth was registered or recorded Certificate of Live Birth.</p>';
				$message .= '<p>4.	For married employees, <b>Marriage Contract/Certificate</b> duly authenticated by the PSA or the LCR of the municipality or city where the marriage was registered or recorded.</p>';
				$message .= '<p>5.	A valid <b>National Bureau of Investigation (NBI) Clearance</b> is required for original appointment.</p>';
				$message .= '<p>6.	The certified true copies of scholastic/academic record such as <b>diploma</b> and <b>transcript of records (TOR)</b> or, if necessary, a Certification from the Department of Education (DepED) and/or Commission on Higher Education (CHED) on the authenticity and equivalency of the subjects/courses taken.</p>';
				$message .= '<p>7.	Personal Data Sheet (PDS) (CS Form No. 212, Revised 2017) with work experience sheet <i>(3 pcs)</i>.</p><br>';
				$message .= '<p>Please note that we are constrained in processing the appointment until complete submission of all the required documents mentioned above.</p><br>';
				$message .= '<p>Sincerely yours,</p><br>';
				$message .= '<b>JESUSA T. DELOS SANTOS</b><br>';
				$message .= 'Administrative Officer V';
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

		// disapprove applicant
		public function disapproveApplicant($params) {
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Failed to disapprove applicant.";

			$data['id'] 	= isset($params['id'])?$params['id']:'';
			$data['status']	= 'REJECTED';
			$data['modified_by'] = Helper::get('userid');

			$sql = "UPDATE tblrecruitmentapplicants SET application_status = ?, modified_by = ? WHERE id = ?";
			$sql_params = array($data['status'],$data['modified_by'],$data['id']);

			$this->db->query($sql,$sql_params);
			if($this->db->affected_rows() > 0){
				$today = date("F j, Y");
				$applicant = $this->getApplicantDetails($params['id']);
				$full_name = "{$applicant->first_name} {$applicant->middle_name} {$applicant->last_name} {$applicant->extension}";
				$position = $applicant->position;
				$email = $applicant->email;
				$subject = 'NWRB Job Application Status';

				// Compose a simple HTML email message
				$message = '<html><style>p {font-size:12px;} </style><body>';
				$message .= '<img src="'. base_url() . 'assets/custom/images/nwrb.png' . '" style="display: block; margin-left: auto; margin-right: auto; height: 75px;">';
				$message .= '<p>' . $today . '</p><br><br>';
				$message .= '<p>' . $full_name . '</p><br>';
				$message .= '<p>Dear ' . $applicant->first_name . ',</p>';
				$message .= '<p>Thank you for taking the time to apply ('. $position . ') at the National Water Resources Board. We really appreciate the effort you put into this. We received and have reviewed a number of applications and after reviewing them thoroughly, we felt that other applicants were better suited for the job.</p>';
				$message .= '<p>At this time, we are declining to move forward your application.</p>';
				$message .= '<p>Best of luck on your personal and professional success in your future endeavors.</p><br>';
				$message .= '<p>Sincerely,</p><br><br>';
				$message .= '<p style="font-weight: bold;">JESUSA T. DELOS SANTOS</p>';
				$message .= '<p>Administrative Officer V - PRS</p>';
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

		// disapprove applicant after deliberation
		public function disapproveAfterDeliberation($params) {
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Failed to disapprove applicant.";

			$data['id'] 	= isset($params['id'])?$params['id']:'';
			$data['status']	= 'REJECTED AFTER DELIBERATION';
			$data['modified_by'] = Helper::get('userid');

			$sql = "UPDATE tblrecruitmentapplicants SET application_status = ?, modified_by = ? WHERE id = ?";
			$sql_params = array($data['status'],$data['modified_by'],$data['id']);

			$this->db->query($sql,$sql_params);
			if($this->db->affected_rows() > 0){
				$today = date("F j, Y");
				$applicant = $this->getApplicantDetails($params['id']);
				$full_name = "{$applicant->first_name} {$applicant->middle_name} {$applicant->last_name} {$applicant->extension}";
				$position = $applicant->position;
				$place = $applicant->place;
				$email = $applicant->email;
				$contact_number = $applicant->contact_number;
				$subject = 'NWRB Job Application Status';

				// old email format
				// $message = '<html><style>p {font-size:12px;} </style><body>';
				// $message .= '<img src="'. base_url() . 'assets/custom/images/nwrb.png' . '" style="display: block; margin-left: auto; margin-right: auto; height: 75px;">';
				// $message .= '<p>' . $today . '</p><br><br>';
				// $message .= '<p>' . $full_name . '</p><br>';
				// $message .= '<p>Dear ' . $applicant->first_name . ',</p>';
				// $message .= '<p>Thank you for taking the time to apply for the position of ' . $position . 'under the ' . $place . ' Division of the National Water Resources Board. We appreciated the opportunity to know you and learn more about your qualifications.</p>';
				// $message .= '<p>It is always difficult to choose among the qualified applicants whom we interviewed. However, we regret to inform you, after a careful consideration, we have selected a candidate that more closely matches our requirement for this position. We will keep your resume on file, should another position become available.<br>';
				// $message .= '<p>Thank you again for your interest in the National Water Resources Board.</p><br>';
				// $message .= '<p>Sincerely yours,</p><br><br>';
				// $message .= '<p style="font-weight: bold;">JESUSA T. DELOS SANTOS</p>';
				// $message .= '<p>Administrative Officer V - PRS</p>';
				// $message .= '</body></html>';

				//new email format
				$message = '<html><style>p {font-size:12px;} </style><body>';
				$message .= '<img src="'. base_url() . 'assets/custom/images/nwrb-header.png' . '" style="display: block; margin-left: auto; margin-right: auto; height: 75px; float: center;">';
				$message .= '<p>' . $today . '</p><br>';
				$message .= '<b>' . $full_name . '</b><br>';
				$message .= '' . $email . '<br>';
				$message .= '' . ($contact_number != "N/A" ? $contact_number : "") . '<br><br>';
				$message .= '<p>Dear ' . $applicant->first_name . ',</p>';
				$message .= '<p>Thank you for applying to the position of Accountant III of the National Water Resources Board.</p>';
				$message .= '<p>We highly appreciate your interest and effort during the entire assessment process. However, after a thorough assessment and careful consideration of the qualifications and professional experiences of all the applicants and the job requirements of the position, we regret to inform you that you are not considered for Accountant III of the Administrative and Financial Division. You are most welcome to apply again to another position once there is an opening.</p>';
				$message .= '<p>Again, thank you and we wish you all the best in your future endeavors. </p><br>';			
				$message .= '<p>Sincerely yours,</p><br>';
				$message .= '<b>JESUSA T. DELOS SANTOS</b><br>';
				$message .= 'Administrative Officer V';
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

		// withdraw applicant
		public function withdrawApplicant($params) {
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Failed to withdraw applicant.";

			$data['id'] 	= isset($params['id'])?$params['id']:'';
			$data['status']	= 'WITHDRAWN';
			$data['modified_by'] = Helper::get('userid');

			$sql = "UPDATE tblrecruitmentapplicants SET application_status = ?, modified_by = ? WHERE id = ?";
			$sql_params = array($data['status'],$data['modified_by'],$data['id']);

			$this->db->query($sql,$sql_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully withdrawn applicant.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;
			} else {
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		// block applicant
		public function blockApplicant($params) {
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Failed to block applicant.";

			$data['id'] 	= isset($params['id'])?$params['id']:'';
			$data['status']	= 'BLOCKED';
			$data['modified_by'] = Helper::get('userid');

			$sql = "UPDATE tblrecruitmentapplicants SET application_status = ?, modified_by = ? WHERE id = ?";
			$sql_params = array($data['status'],$data['modified_by'],$data['id']);

			$this->db->query($sql,$sql_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully blocked applicant.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;
			} else {
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		// recommend job
		public function recommendJob($params) {
			$helperDao = new HelperDao();

			$data['id'] 	= isset($params['id'])?$params['id']:'';
			$today = date("F j, Y");
			$applicant = $this->getApplicantDetails($data['id']);
			$ret = new JobOpeningsCollection();
			$job_opening = $ret->getJobOpeningsRows($params['vacancy_id']);
			$full_name = "{$applicant->first_name} {$applicant->middle_name} {$applicant->last_name} {$applicant->extension}";
			$position = $job_opening['Data']['job_opening'][0]['name'];
			$email = $applicant->email;
			$subject = 'NWRB Job Recommendation';

			// Compose a simple HTML email message
			$message = '<html><style>p {font-size:12px;} </style><body>';
			$message .= '<img src="'. base_url() . 'assets/custom/images/nwrb.png' . '" style="display: block; margin-left: auto; margin-right: auto; height: 75px;">';
			$message .= '<p>' . $today . '</p><br><br>';
			$message .= '<p style="font-weight:bold">' . $full_name . '</p><br>';
			$message .= '<p>Dear ' . $applicant->first_name . ',</p>';
			$message .= '<p>I am pleased to inform you that National Water Resources Board is opening a job position for ' . $position . '</p>';
			$message .= '<p>If you wish to apply, you can contact NWRB Human Resource Merit Promotion on <strong>(632)8 920-2793</strong>.</p><br>';
			$message .= '<p>Thank you, </p>';
			$message .= '<p style="font-weight: bold;">Human Resource Merit Promotion and Selection Board</p>';
			$message .= '</body></html>';

			// Sending email
			$data = $helperDao->sendMail2($subject, $email, $message);
			$code = $data['code'];
			$message = $data['message'];

			if($code == "1"){
				$this->ModelResponse($code, $message);
				return true;
			} else{
				$this->ModelResponse($code, $message);
				return true;
			}
		}

		public function getfiles($id){
			$sql = "SELECT * from tblrecruitmentapplicantsupload where recruitmentapplicants_id = ?";
			$sql_params = array($id);
			$res = $this->db->query($sql,$sql_params)->result_array();
			// print_r($res); die();
			return $res;
		}
    }
?>
