<?php
class JobOpenings extends MX_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Helper');
        $this->load->module('session');
        $this->load->model('JobOpeningsCollection');
        $this->load->model('ModuleRels');
        Helper::sessionEndedHook('session');
    }

    public function index() {
        Helper::rolehook(ModuleRels::JOB_OPENINGS_SUB_MENU);
        $listData = array();
        $viewData = array();

        $page = "viewJobOpenings";
        $listData['key'] = $page;
        $viewData['table'] = $this->load->view("helpers/jobopeningslist", $listData, TRUE);

        if (!$this->input->is_ajax_request()) {
            Helper::setTitle('Job Opening');
            Helper::setMenu('templates/menu_template');
            Helper::setView('jobopenings', $viewData, FALSE);
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
    	$ret = new JobOpeningsCollection();
    	$ret = $ret->make_datatables();
            foreach($ret as $k => $row) {
				$buttons_qualification_info = "";
                $buttons_action = "";
                $buttons_data = "";

                switch($row->is_approve) {
					case 1 :
						$is_approve = 'APPROVED';
						$status_color = "text-success";
						$flag = 0;
						break;

					case 999 :
						$is_approve = 'DISAPPROVED';
						$status_color = "text-danger";
						$flag = 0;
						break;

					default:
						$is_approve = 'PENDING';
						$status_color = "text-warning";
						$flag = 1;
						break;
				}

                $is_active = isset($row->is_active) && $row->is_active ? 'ACTIVE' : 'INACTIVE';
				// $row->expiration_date = date('m/d/Y', strtotime($row->expiration_date));

                $sub_array = array();
                $is_active == "ACTIVE" ? $status_color = "text-success" : $status_color = "text-danger";


                foreach($row as $k1=>$v1) {
                    $buttons_data .= ' data-'.$k1.'="'.$v1.'" ';
                }

				$buttons_action .= '<a id="viewJobQualification" '
					. ' class="viewJobQualification" style="text-decoration: none;" '
					. ' href="' . base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . '/viewJobQualificationsForm"'
					. $buttons_data
					. ' > '
					. ' <button class="btn btn-primary btn-circle wave-effect waves waves-float" data-toggle="tooltip" data-placement="top" title="View Qualifications">'
					. ' <i class="material-icons">list</i>'
					. ' </button> '
					. ' </a>';

				if($is_approve !== 'DISAPPROVED') {
					$buttons_action .= '<a id="updateExpirationDateForm"'
						. ' class="updateExpirationDateForm" style="text-decoration: none"'
						. ' href=' . base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . '/updateExpirationDateForm '
						. $buttons_data
						. ' > '
						. ' <button class="btn btn-primary btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Extend Job Posting">'
						. ' <i class="material-icons">edit</i>'
						. '</button> '
						. '</a>';
				}

				$buttons_action .= '<a id="viewJobOpeningsForm" '
					. ' class="viewJobOpeningsForm" style="text-decoration: none;" '
					. ' href="' . base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . '/viewJobOpeningsForm" '
					. $buttons_data
					. ' > '
					. ' <button class="btn btn-success btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Print Preview">'
					. ' <i class="material-icons">print</i>'
					. ' </button> '
					. ' </a>';

				if($flag) {
					$buttons_action .= '<a id="approveJobOpening" '
						. ' class="approveJobOpening" style="text-decoration: none;" '
						. ' href="' . base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . '/approveJobOpening" '
						. $buttons_data
						. ' > '
						. ' <button class="btn btn-warning btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Approve Job Opening">'
						. ' <i class="material-icons">check_circle</i>'
						. ' </button> '
						. ' </a>';

					$buttons_action .= '<a id="disapprovedJobOpening" '
						. ' class="disapprovedJobOpening" style="text-decoration: none;" '
						. ' href="' . base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . '/disapprovedJobOpening" '
						. $buttons_data
						. ' > '
						. ' <button class="btn btn-danger btn-circle wave-effect waves waves-float" data-toggle="tooltip" data-placement="top" title="Disapprove Job Opening">'
						. ' <i class="material-icons">remove_circle</i>'
						. ' </button> '
						. ' </a>';
				}

				if($is_active == 'ACTIVE' && $is_approve == 'APPROVED') {
					$buttons_action .= '<a id="addApplicantsForm" '
						. ' class="addApplicantsForm" style="text-decoration: none;" '
						. ' href="' . base_url() . $this->uri->segment(1) . '/Applicants/addApplicantsForm" '
						. $buttons_data
						. ' > '
						. ' <button class="btn btn-success btn-circle wave-effect waves waves-float" data-toggle="tooltip" data-placement="top" title="Add Applicant">'
						. ' <i class="material-icons">add</i>'
						. ' </button> '
						. ' </a>';
				}

				if($is_active == 'ACTIVE') {
					$buttons_action .= '<a id="deactivateJobOpening" '
						. ' class="deactivateJobOpening" style="text-decoration: none;" '
						. ' href="' . base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . '/deactivateJobOpening" '
						. $buttons_data
						. ' > '
						. ' <button class="btn btn-danger btn-circle wave-effect waves waves-float" data-toggle="tooltip" data-placement="top" title="Deactivate">'
						. ' <i class="material-icons">do_not_disturb</i>'
						. ' </button> '
						. ' </a>';
				} else {
					$buttons_action .= '<a id="activateJobOpening" '
						. ' class="activateJobOpening" style="text-decoration: none;" '
						. ' href="' . base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . '/activateJobOpening" '
						. $buttons_data
						. ' > '
						. ' <button class="btn btn-success btn-circle wave-effect waves waves-float" data-toggle="tooltip" data-placement="top" title="Activate">'
						. ' <i class="material-icons">done</i>'
						. ' </button> '
						. ' </a>';
				}


                $sub_array[] = $buttons_action;
                $sub_array[] = $row->id;
                $sub_array[] = $row->name;
                $sub_array[] = $row->code;
                $sub_array[] = $row->grade;
                $sub_array[] = $row->salary;
                $sub_array[] = $row->place;
                $sub_array[] = $row->expiration_date;
				$sub_array[] = '<b><span class="'. $status_color . '">' . $is_approve . '</span></b>';
                $sub_array[] = '<b><span class="'. $status_color . '">' . $is_active . '</span></b>';
                $data[] = $sub_array;

            }
        
        $output = array(
            "draw"					=>		intval($_POST["draw"]),
            "recordsTotal"			=>		$this->JobOpeningsCollection->get_all_data(),
            "recordsFiltered"		=>		$this->JobOpeningsCollection->get_filtered_data(),
            "data"					=>		$data
        );
        echo json_encode($output);
    }

    public function getJobOpeningTables() {
    	if(!$this->input->is_ajax_request()) show_404();

    	$ret = $this->JobOpeningsCollection->getJobOpeningsRows($_POST["id"]);
    	echo json_encode($ret);
	}

	public function deleteQualifications() {
    	if(!$this->input->is_ajax_request()) show_404();
    	// print_r($_POST); die();
    	$ret = $this->JobOpeningsCollection->deleteQualifications($_POST["id"],$_POST["name"],$_POST["category"]);
    	echo json_encode($ret);
	}
	

    public function addJobOpeningsForm() {
    	$formData = array();
    	$result = array();
    	$result['key'] = 'addJobOpening';
    	if (!$this->input->is_ajax_request()) show_404();
    	else {
    		$formData['key'] = $result['key'];
    		$result['form'] = $this->load->view('forms/jobopeningform.php', $formData, TRUE);
		}
    	echo json_encode($result);
	}

    public function updateExpirationDateForm() {
    	$formData = array();
    	$result = array();
    	$result['key'] = 'updateExpirationDate';
    	if (!$this->input->is_ajax_request()) show_404();
    	else {
    		$formData['key'] = $result['key'];
    		$result['form'] = $this->load->view('forms/jobopeningform.php', $formData, TRUE);
		}
    	echo json_encode($result);
	}

    // function to add job opening
	public function addJobOpening() {
		$result = array();
		$page = 'addJobOpening';
		if (!$this->input->is_ajax_request()) {
			show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k, true);
				}

				$ret = new JobOpeningsCollection();
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

    // function to update job opening expiration date
	public function updateExpirationDate() {
		$result = array();
		$page = 'updateExpirationDate';
		if (!$this->input->is_ajax_request()) {
			show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k, true);
				}

				$ret = new JobOpeningsCollection();
				if($ret->updateExpirationDate($post_data)) {
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

	// approve job opening
	public function approveJobOpening() {
    	$result = array();
    	$page = 'approveJobOpening';
    	if (!$this->input->is_ajax_request()) {
    		show_404();
		} else {
    		if ($this->input->post() && $this->input->post() != null) {
    			$post_data = array();
    			foreach ($this->input->post() as $k => $v) {
    				$post_data[$k] = $this->input->post($k, true);
				}

    			$ret = new JobOpeningsCollection();
    			if ($ret->approveJobOpening($post_data)) {
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

	// disapprove job opening
	public function disapprovedJobOpening() {
		$result = array();
		$page = 'disapprovedJobOpening';
		if (!$this->input->is_ajax_request()) {
			show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}

				$ret =  new JobOpeningsCollection();
				if($ret->disapprovedJobOpening($post_data)) {
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

	// for viewing form
	public function viewJobOpeningsForm() {
		$formData = array();
		$result = array();
		$result['key'] = 'viewJobOpening';
		if (!$this->input->is_ajax_request()) show_404();
		else {
			$ret =  new JobOpeningsCollection();
			$formData['key'] = $result['key'];
			$formData['signatories'] = $ret->getSignatories();
			// print_r($formData['signatories']);
			$result['form'] = $this->load->view('forms/jobopeningprintpreview.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	// for viewing job qualifications
	public function viewJobQualificationsForm() {
    	$formData = array();
    	$result = array();
    	$result['key'] = 'viewJobQualifications';
    	if (!$this->input->is_ajax_request()) show_404();
    	else {
    		$formData['key'] = $result['key'];
    		$result['form'] = $this->load->view('forms/jobqualificationform.php', $formData, TRUE);
		}
    	echo json_encode($result);
	}

	public function getActiveJobOpenings(){
		$result = array();
		$page = 'getActiveJobOpenings';
		if (!$this->input->is_ajax_request()) {
			show_404();
		}
		else
		{
			$ret =  new JobOpeningsCollection();
			if($ret->hasRowsActive()) {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
			} else {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			}
			$result = json_decode($res,true);

			$result['key'] = $page;
		}
		echo json_encode($result);
	}

	// activate job opening
	public function activateJobOpening(){
		$result = array();
		$page = 'activateJobOpening';
		if (!$this->input->is_ajax_request()) {
			show_404();
		} else {
			if ($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k, true);
				}

				$ret = new JobOpeningsCollection();
				if ($ret->activateJobOpening($post_data)) {
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

	// deactivate job opening
	public function deactivateJobOpening(){
		$result = array();
		$page = 'deactivateJobOpening';
		if (!$this->input->is_ajax_request()) {
			show_404();
		} else {
			if ($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k, true);
				}

				$ret = new JobOpeningsCollection();
				if ($ret->deactivateJobOpening($post_data)) {
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

	// job opening list
	public function jobOpeningAPI(){
		$result = array();
		$page = 'jobOpeningAPI';
		if (!$this->input->is_ajax_request()) {
			show_404();
		} else {
			$ret = new JobOpeningsCollection();
			if ($ret->authorizedJobOpenings()) {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
			} else {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			}
			$result = json_decode($res, true);
			$result['key'] = $page;
		}
		echo json_encode($result);
	}
}
?>
