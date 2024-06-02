<?php
class Applicants extends MX_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Helper');
        $this->load->module('session');
        $this->load->model('ApplicantsCollection');
        $this->load->model('ModuleRels');
        $this->load->library('upload');
        Helper::sessionEndedHook('session');
    }

    public function index() {
        Helper::rolehook(ModuleRels::APPLICANTS_SUB_MENU);
        $listData = array();
        $viewData = array();

        $page = "viewApplicants";
        $listData['key'] = $page;
        $viewData['table'] = $this->load->view("helpers/applicantslist", $listData, TRUE);

        if (!$this->input->is_ajax_request()) {
            Helper::setTitle('Applicant');
            Helper::setMenu('templates/menu_template');
            Helper::setView('applicants', $viewData, FALSE);
            Helper::setTemplate('templates/master_template');
        } else {
            $result['key'] = $listData['key'];
            $result['table'] = $viewData['table'];
            echo json_encode($result);
        }
        Session::checksession();
    }

    // initialize table
	function fetchRows() {
    	$data = array();
    	$ret = new ApplicantsCollection();
    	$ret = $ret->make_datatables();
            foreach($ret as $k => $row) {
                $buttons_action = "";
                $buttons_data = "";

				if($row->extension){
					$row->name = $row->first_name . " " . $row->middle_name . " " . $row->last_name . " " . $row->extension;
				}else{
					$row->name = $row->first_name . " " . $row->middle_name . " " . $row->last_name;
				}
				$row->schedule_date = date('m/d/Y', strtotime($row->schedule_date));
				$row->schedule_time = date('h:i', strtotime($row->schedule_time));
				$row->examination_schedule_date = date('m/d/Y', strtotime($row->examination_schedule_date));
				$row->examination_schedule_time = date('h:i', strtotime($row->examination_schedule_time));

                $sub_array = array();

				switch(strtoupper($row->application_status)) {
					case 'PENDING' :
						$status_color 	= "text-warning";
						$status_msg 	= "PENDING";
						$flag = 1;
						break;
					case 'ACCEPTED' :
						$status_color 	= "text-success";
						$status_msg 	= "ACCEPTED";
						$flag = 1;
						break;
					case 'REJECTED' :
						$status_color 	= "text-danger";
						$status_msg 	= "REJECTED";
						$flag = 1;
						break;
					case 'REJECTED AFTER DELIBERATION' :
						$status_color 	= "text-danger";
						$status_msg 	= "REJECTED AFTER DELIBERATION";
						$flag = 0;
						break;
					case 'WITHDRAWN' :
						$status_color	= 'text-gray';
						$status_msg		= 'WITHDRAWN';
						$flag = 0;
						break;
					case 'BLOCKED' :
						$status_color	= 'text-danger';
						$status_msg		= 'BLOCKED';
						$flag = 0;
						break;
				}

                foreach($row as $k1=>$v1) {
                    $buttons_data .= ' data-'.$k1.'="'.$v1.'" ';
                }

                $buttons_action .= '<a id="updateApplicantsForm"'
                    . ' class="updateApplicantsForm" style="text-decoration: none"'
                    . ' href=' . base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . '/updateApplicantsForm '
                    . $buttons_data
                    . ' > '
                    . ' <button class="btn btn-primary btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Update applicant">'
                    . ' <i class="material-icons">mode_edit</i>'
                    . '</button> '
                    . '</a>';

				$buttons_action .= '<a id="viewApplicantsForm" '
					. ' class="viewApplicantsForm" style="text-decoration: none;" '
					. ' href="' . base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . '/viewApplicantsForm" '
					. $buttons_data
					. ' > '
					. ' <button class="btn btn-primary btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="View Applicant">'
					. ' <i class="material-icons">list</i>'
					. ' </button> '
					. ' </a>';

				if($row->application_status == 'PENDING') {
					if(!$row->examination_schedule_id) {
						$buttons_action .= '<a id="addExaminationSchedulesForm" '
							. ' class="addExaminationSchedulesForm" style="text-decoration: none;" '
							. ' href="' . base_url() . $this->uri->segment(1) . '/ExaminationSchedules/addExaminationSchedulesForm" '
							. $buttons_data
							. ' > '
							. ' <button class="btn btn-primary btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Send Examination Schedule">'
							. ' <i class="material-icons">mail</i>'
							. ' </button> '
							. ' </a>';

					} else {
						$buttons_action .= '<a id="viewExaminationSchedulesForm" '
							. ' class="viewExaminationSchedulesForm" style="text-decoration: none;" '
							. ' href="' . base_url() . $this->uri->segment(1) . '/ExaminationSchedules/viewExaminationSchedulesForm" '
							. $buttons_data
							. ' > '
							. ' <button class="btn btn-primary btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="View Examination Schedule">'
							. ' <i class="material-icons">today</i>'
							. ' </button> '
							. ' </a>';
					}
						// if($row->examination_schedule_status) {
							if(!$row->schedule_id) {
								$buttons_action .= '<a id="addInterviewSchedulesForm" '
									. ' class="addInterviewSchedulesForm" style="text-decoration: none;" '
									. ' href="' . base_url() . $this->uri->segment(1) . '/InterviewSchedules/addInterviewSchedulesForm" '
									. $buttons_data
									. ' > '
									. ' <button class="btn btn-primary btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Send Interview Schedule">'
									. ' <i class="material-icons">mail</i>'
									. ' </button> '
									. ' </a>';
							} else {
								$buttons_action .= '<a id="viewInterviewSchedulesForm" '
									. ' class="viewInterviewSchedulesForm" style="text-decoration: none;" '
									. ' href="' . base_url() . $this->uri->segment(1) . '/InterviewSchedules/viewInterviewSchedulesForm" '
									. $buttons_data
									. ' > '
									. ' <button class="btn btn-primary btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="View Interview Schedule">'
									. ' <i class="material-icons">today</i>'
									. ' </button> '
									. ' </a>';
							}
						// }
					
					

					$buttons_action .= '<a id="disapproveApplicant" '
						. ' class="disapproveApplicant" style="text-decoration: none;" '
						. ' href="' . base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . '/disapproveApplicant" '
						. $buttons_data
						. ' > '
						. ' <button class="btn btn-danger btn-circle wave-effect waves waves-float" data-toggle="tooltip" data-placement="top" title="Reject Applicant">'
						. ' <i class="material-icons">remove_circle</i>'
						. ' </button> '
						. ' </a>';

					$buttons_action .= '<a id="disapproveAfterDeliberation" '
						. ' class="disapproveApplicant" style="text-decoration: none;" '
						. ' href="' . base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . '/disapproveAfterDeliberation" '
						. $buttons_data
						. ' > '
						. ' <button class="btn btn-danger btn-circle wave-effect waves waves-float" data-toggle="tooltip" data-placement="top" title="Reject after Deliberation">'
						. ' <i class="material-icons">thumb_down_alt</i>'
						. ' </button> '
						. ' </a>';

					$buttons_action .= '<a id="acceptApplicant" '
						. ' class="acceptApplicant" style="text-decoration: none;" '
						. ' href="' . base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . '/acceptApplicant" '
						. $buttons_data
						. ' > '
						. ' <button class="btn btn-success btn-circle wave-effect waves waves-float" data-toggle="tooltip" data-placement="top" title="Accept Applicant">'
						. ' <i class="material-icons">thumb_up_alt</i>'
						. ' </button> '
						. ' </a>';

					$buttons_action .= '<a id="withdrawApplicant" '
						. ' class="withdrawApplicant" style="text-decoration: none;" '
						. ' href="' . base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . '/withdrawApplicant" '
						. $buttons_data
						. ' > '
						. ' <button class="btn btn-warning btn-circle wave-effect waves waves-float" data-toggle="tooltip" data-placement="top" title="Withdraw Applicant">'
						. ' <i class="material-icons">cancel_presentation</i>'
						. ' </button> '
						. ' </a>';

					$buttons_action .= '<a id="blockApplicant" '
						. ' class="blockApplicant" style="text-decoration: none;" '
						. ' href="' . base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . '/blockApplicant" '
						. $buttons_data
						. ' > '
						. ' <button class="btn btn-danger btn-circle wave-effect waves waves-float" data-toggle="tooltip" data-placement="top" title="Block Applicant">'
						. ' <i class="material-icons">block</i>'
						. ' </button> '
						. ' </a>';
				}

				if($row->application_status != 'PENDING' && $row->application_status != 'ACCEPTED' && $row->application_status != 'BLOCKED') {
					$buttons_action .= '<a id="recommendJobForm" '
						. ' class="recommendJobForm" style="text-decoration: none;" '
						. ' href="' . base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . '/recommendJobForm" '
						. $buttons_data
						. ' > '
						. ' <button class="btn btn-primary btn-circle wave-effect waves waves-float" data-toggle="tooltip" data-placement="top" title="Recommend Job Opening">'
						. ' <i class="material-icons">mail</i>'
						. ' </button> '
						. ' </a>';
				}

				// add employee detail
				if($row->application_status == 'ACCEPTED' && !$row->employee_number) {
					$buttons_action .= '<a id="addEmployeesForm" '
						. ' class="addEmployeesForm" style="text-decoration: none;" '
						. ' href="' . base_url() . '/employees/Employees/addEmployeesForm" '
						. $buttons_data
						. ' > '
						. ' <button class="btn btn-success btn-circle wave-effect waves waves-float" data-toggle="tooltip" data-placement="top" title="Add Employee">'
						. ' <i class="material-icons">save</i>'
						. ' </button> '
						. ' </a>';
				}

				// send job recommendation
				if($row->application_status == 'ACCEPTED' && !$row->checklist ) {
					$buttons_action .= '<a id="addApplicantChecklistsForm" '
						. ' class="addApplicantChecklistsForm" style="text-decoration: none;" '
						. ' href="' . base_url() . $this->uri->segment(1) . '/ApplicantChecklists/addApplicantChecklistsForm" '
						. $buttons_data
						. ' > '
						. ' <button class="btn btn-success btn-circle wave-effect waves waves-float" data-toggle="tooltip" data-placement="top" title="Add Employee Checklist">'
						. ' <i class="material-icons">format_list_bulleted</i>'
						. ' </button> '
						. ' </a>';
				}

                $sub_array[] = $buttons_action;
                $sub_array[] = $row->id;
                $sub_array[] = $row->name;
                $sub_array[] = $row->position;
                $sub_array[] = $row->email;
                $sub_array[] = $row->contact_number;
				$sub_array[] = '<b><span class="'.$status_color.'">'.strtoupper($status_msg).'</span><b>';
                $data[] = $sub_array;

            }
        
        $output = array(
            "draw"					=>		intval($_POST["draw"]),
            "recordsTotal"			=>		$this->ApplicantsCollection->get_all_data(),
            "recordsFiltered"		=>		$this->ApplicantsCollection->get_filtered_data(),
            "data"					=>		$data
        );
        echo json_encode($output);
    }

    public function getApplicantTables() {
        if(!$this->input->is_ajax_request()) show_404();

        $ret = $this->ApplicantsCollection->getApplicantsRows($_POST["id"]);
        echo json_encode($ret);
    }

	//for viewing form
    public function addApplicantsForm() {
    	$formData = array();
    	$result = array();
    	$result['key'] = 'addApplicant';
    	if (!$this->input->is_ajax_request()) show_404();
    	else {
    		$formData['key'] = $result['key'];
    		$result['form'] = $this->load->view('forms/applicantform.php', $formData, TRUE);
		}
    	echo json_encode($result);
	}

	public function updateApplicantsForm(){
		$formData = array();
		$result = array();
		$post = $this->input->post();
		$ret = new ApplicantsCollection();
		$filenames = $ret->getfiles($post['id']);

		$result['key'] = 'updateApplicant';
		if (!$this->input->is_ajax_request()) {
			show_404();
		}
		else
		{	
			$formData['filenames'] = $filenames;
			$formData['last_name'] = $post['last_name'];
			$formData['first_name'] = $post['first_name'];
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/applicantform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	public function viewApplicantsForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewApplicant';
		$post = $this->input->post();
		$ret = new ApplicantsCollection();
		$filenames = $ret->getfiles($post['id']);
		if (!$this->input->is_ajax_request()) show_404();
		else {
			$formData['filenames'] = $filenames;
			$formData['last_name'] = $post['last_name'];
			$formData['first_name'] = $post['first_name'];
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/applicantform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	public function recommendJobForm() {
    	$formData = array();
    	$result = array();
    	$result['key'] = 'recommendJob';
		if (!$this->input->is_ajax_request()) show_404();
		else {
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/recommendjobform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	// function to add applicant
	public function addApplicant() {
    	$post = $this->input->post();
    	$applicant_folder = $post['last_name'] . "_" . $post['first_name'];
		$result = array();
		$arrfiles = array();
		$page = 'addApplicant';
		if (!$this->input->is_ajax_request()) {
			show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				if(isset($_FILES)) {
					for ($i=0; $i <count($_FILES['file_upload']['name']) ; $i++) { 
						$_FILES['uploadFile']['name'] 		= $_FILES['file_upload']['name'][$i];
						$_FILES['uploadFile']['type'] 		= $_FILES['file_upload']['type'][$i];
						$_FILES['uploadFile']['size'] 		= $_FILES['file_upload']['size'][$i];
						$_FILES['uploadFile']['error'] 		= $_FILES['file_upload']['error'][$i];
						$_FILES['uploadFile']['tmp_name'] 	= $_FILES['file_upload']['tmp_name'][$i];
						array_push($arrfiles, $_FILES['file_upload']['name'][$i]);
						if (!file_exists('./assets/uploads/applicants/'.$applicant_folder))
							mkdir('./assets/uploads/applicants/'.$applicant_folder, 0777, true);
						$config['upload_path'] = './assets/uploads/applicants/'.$applicant_folder;
						$config['allowed_types'] = '*';
						$config['overwrite'] = TRUE;
						$config['remove_spaces'] = FALSE;
						$this->upload->initialize($config);
						if ($this->upload->do_upload('uploadFile')):
							$data = array('upload_data' => $this->upload->data());
						else:
							$error = array('error' => $this->upload->display_errors());
						endif;
					}
				}

				$post_data = array();
				foreach($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k, true);
				}
				$post_data['filename'] = $arrfiles;

				$ret = new ApplicantsCollection();
				if($ret->addRows($post_data)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
				$result = json_decode($res, true);
			} else {
				$res = new ModelResponse();
				$result = json_decode($res, true);
			}
			$result['key'] = $page;
		}
		echo json_encode($result);
	}

	// function to update applicant files
	public function updateApplicant(){
		$post = $this->input->post();
		$result = array();
		$page = 'updateApplicant';
		$applicant_folder = $post['last_name'] . "_" . $post['first_name'];
		$array_id = array();
		$arrfiles = array();
		$newfile = array();
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				if(isset($_FILES) && count($_FILES) > 0) {
					$keys = array_keys($_FILES);
					foreach ($keys as $key => $value) {
						$getid = explode('_', $value);
						if(isset($getid[2])){
							$id = $getid[2];
							array_push($array_id, $id);
							$_FILES['uploadFile']['name'] 		= $_FILES['file_upload_'.$id.'']['name'];
							$_FILES['uploadFile']['type'] 		= $_FILES['file_upload_'.$id.'']['type'];
							$_FILES['uploadFile']['size'] 		= $_FILES['file_upload_'.$id.'']['size'];
							$_FILES['uploadFile']['error'] 		= $_FILES['file_upload_'.$id.'']['error'];
							$_FILES['uploadFile']['tmp_name'] 	= $_FILES['file_upload_'.$id.'']['tmp_name'];
							array_push($arrfiles, $_FILES['file_upload_'.$id.'']['name']);
							if (!file_exists('./assets/uploads/applicants/'.$applicant_folder))
								mkdir('./assets/uploads/applicants/'.$applicant_folder, 0777, true);
							$config['upload_path'] = './assets/uploads/applicants/'.$applicant_folder;
							$config['allowed_types'] = '*';
							$config['overwrite'] = TRUE;
							$config['remove_spaces'] = FALSE;
							$this->upload->initialize($config);
							if ($this->upload->do_upload('uploadFile')):
								$data = array('upload_data' => $this->upload->data());
							else:
								$error = array('error' => $this->upload->display_errors());
							endif;
						
					}
				}

				if(isset($_FILES['file']) && count($_FILES['file']['name']) > 0) {
					for ($i=0; $i < count($_FILES['file']['name']) ; $i++) { 
	
						$_FILES['uploadFile']['name'] 		= $_FILES['file']['name'][$i];
						$_FILES['uploadFile']['type'] 		= $_FILES['file']['type'][$i];
						$_FILES['uploadFile']['size'] 		= $_FILES['file']['size'][$i];
						$_FILES['uploadFile']['error'] 		= $_FILES['file']['error'][$i];
						$_FILES['uploadFile']['tmp_name'] 	= $_FILES['file']['tmp_name'][$i];
						array_push($newfile, $_FILES['file']['name'][$i]);
						if (!file_exists('./assets/uploads/applicants/'.$applicant_folder))
							mkdir('./assets/uploads/applicants/'.$applicant_folder, 0777, true);
						$config['upload_path'] = './assets/uploads/applicants/'.$applicant_folder;
						$config['allowed_types'] = '*';
						$config['overwrite'] = TRUE;
						$config['remove_spaces'] = FALSE;
						$this->upload->initialize($config);
						if ($this->upload->do_upload('uploadFile')):
							$data = array('upload_data' => $this->upload->data());
						else:
							$error = array('error' => $this->upload->display_errors());
						endif;
					}
				}
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new ApplicantsCollection();
				$post_data['filename'] = $arrfiles;
				$post_data['newfile'] = $newfile;
				$post_data['ids'] = $array_id;
				if($ret->updateRows($post_data)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
				$result = json_decode($res,true);
			} else {
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
			$result['key'] = $page;
		}
		echo json_encode($result);
	}
}

	// // add new file
	// public function addNewFile() {
 //    	$result = array();
 //    	$page = 'addNewFile';
 //    	if (!$this->input->is_ajax_request()) {
 //    		show_404();
	// 	} else {
	// 		if ($this->input->post() && $this->input->post() != null) {
	// 			$post_data = array();
	// 			foreach ($this->input->post() as $k => $v) {
	// 				$post_data[$k] = $this->input->post($k, true);
	// 			}

	// 			$ret = new ApplicantsCollection();
	// 			if ($ret->addNewFile($post_data)) {
	// 				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
	// 			} else {
	// 				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
	// 			}
	// 			$result = json_decode($res, true);
	// 		} else {
	// 			$res = new ModelResponse();
	// 			$result = json_decode($res, true);
	// 		}
	// 		$result['key'] = $page;
	// 	}
	// 	echo json_encode($result);
	// }

	// accept applicant
	public function acceptApplicant() {
    	$result = array();
    	$page = 'acceptApplicant';
    	if (!$this->input->is_ajax_request()) {
    		show_404();
		} else {
			if ($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k, true);
				}

				$ret = new ApplicantsCollection();
				if ($ret->acceptApplicant($post_data)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
				$result = json_decode($res, true);
			} else {
				$res = new ModelResponse();
				$result = json_decode($res, true);
			}
			$result['key'] = $page;
		}
		echo json_encode($result);
	}

	// disapprove applicant
	public function disapproveApplicant() {
		$result = array();
		$page = 'disapproveApplicant';
		if (!$this->input->is_ajax_request()) {
			show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}

				$ret =  new ApplicantsCollection();
				if($ret->disapproveApplicant($post_data)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
				$result = json_decode($res,true);
			} else {
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
			$result['key'] = $page;
		}
		echo json_encode($result);
	}

	// delete applicant file
	public function deletefile() {
    	$result = array();
    	$page = 'deletefile';
    	if (!$this->input->is_ajax_request()) {
    		show_404();
		} else {
			if ($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k, true);
				}

				$ret = new ApplicantsCollection();
				if ($ret->deletefile($post_data)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
				$result = json_decode($res, true);
			} else {
				$res = new ModelResponse();
				$result = json_decode($res, true);
			}
			$result['key'] = $page;
		}
		echo json_encode($result);
	}

	// disapprove applicant after deliberation
	public function disapproveAfterDeliberation() {
		$result = array();
		$page = 'disapproveAfterDeliberation';
		if (!$this->input->is_ajax_request()) {
			show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}

				$ret =  new ApplicantsCollection();
				if($ret->disapproveAfterDeliberation($post_data)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
				$result = json_decode($res,true);
			} else {
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
			$result['key'] = $page;
		}
		echo json_encode($result);
	}

	// disapprove applicant after deliberation
	public function withdrawApplicant() {
		$result = array();
		$page = 'withdrawApplicant';
		if (!$this->input->is_ajax_request()) {
			show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}

				$ret =  new ApplicantsCollection();
				if($ret->withdrawApplicant($post_data)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
				$result = json_decode($res,true);
			} else {
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
			$result['key'] = $page;
		}
		echo json_encode($result);
	}

	// block applicant
	public function blockApplicant() {
		$result = array();
		$page = 'blockApplicant';
		if (!$this->input->is_ajax_request()) {
			show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}

				$ret =  new ApplicantsCollection();
				if($ret->blockApplicant($post_data)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
				$result = json_decode($res,true);
			} else {
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
			$result['key'] = $page;
		}
		echo json_encode($result);
	}

	// recommend job opening
	public function recommendJob() {
		$result = array();
		$page = 'recommendJob';
		if (!$this->input->is_ajax_request()) {
			show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}

				$ret =  new ApplicantsCollection();
				if($ret->recommendJob($post_data)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
				$result = json_decode($res,true);
			} else {
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
			$result['key'] = $page;
		}
		echo json_encode($result);
	}
}
?>
