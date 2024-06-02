<?php

class ApplicantChecklists extends MX_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Helper');
        $this->load->module('session');
        $this->load->model('ApplicantChecklistsCollection');
        $this->load->model('ModuleRels');
        Helper::sessionEndedHook('session');
    }

    public function index() {
        Helper::rolehook(ModuleRels::APPLICANT_CHECKLISTS_SUB_MENU);
        $listData = array();
        $viewData = array();

        $page = "viewApplicantChecklists";
        $listData['key'] = $page;
        $viewData['table'] = $this->load->view("helpers/applicantchecklistslist", $listData, TRUE);

        if (!$this->input->is_ajax_request()) {
            Helper::setTitle('Applicant Checklist');
            Helper::setMenu('templates/menu_template');
            Helper::setView('applicantchecklists', $viewData, FALSE);
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
    	$ret = new ApplicantChecklistsCollection();
    	$ret = $ret->make_datatables();
            foreach($ret as $k => $row) {
                $buttons_action = "";
                $buttons_data = "";
				
				if($row->extension){
					$row->name = $row->first_name . " " . $row->middle_name . " " . $row->last_name . " " . $row->extension;
				}else{
					$row->name = $row->first_name . " " . $row->middle_name . " " . $row->last_name;
				}
				$row->start_date = date('m/d/Y', strtotime($row->start_date));

                $sub_array = array();

                foreach($row as $k1=>$v1) {
                    $buttons_data .= ' data-'.$k1.'="'.$v1.'" ';
                }

                $buttons_action .= '<a id="viewApplicantChecklistsForm" '
					. ' class="viewApplicantChecklistsForm" style="text-decoration: none;" '
					. ' href="' . base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . '/viewApplicantChecklistsForm" '
					. $buttons_data
					. ' > '
					. ' <button class="btn btn-primary btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="View Detail">'
					. ' <i class="material-icons">list</i>'
					. ' </button> '
					. ' </a>';

                $buttons_action .= '<a id="updateApplicantChecklistsForm"'
                    . ' class="updateApplicantChecklistsForm" style="text-decoration: none"'
                    . ' href=' . base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . '/updateApplicantChecklistsForm '
                    . $buttons_data
                    . ' > '
                    . ' <button class="btn btn-primary btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Update applicant checklist">'
                    . ' <i class="material-icons">edit</i>'
                    . '</button> '
                    . '</a>';

                $sub_array[] = $buttons_action;
                $sub_array[] = $row->id;
                $sub_array[] = $row->name;
                $sub_array[] = $row->position;
                $sub_array[] = $row->start_date;
                $sub_array[] = $row->supervisor;
                $data[] = $sub_array;

            }
        
        $output = array(
            "draw"					=>		intval($_POST["draw"]),
            "recordsTotal"			=>		$this->ApplicantChecklistsCollection->get_all_data(),
            "recordsFiltered"		=>		$this->ApplicantChecklistsCollection->get_filtered_data(),
            "data"					=>		$data
        );
        echo json_encode($output);
    }

    public function getApplicantChecklistTables() {
        if(!$this->input->is_ajax_request()) show_404();

        $ret = $this->ApplicantChecklistsCollection->getApplicantChecklistsRows($_POST["id"]);
        echo json_encode($ret);
    }

    public function getApplicantChecklistItems() {
		if(!$this->input->is_ajax_request()) show_404();

		$ret = $this->ApplicantChecklistsCollection->getApplicantChecklistItemRows($_POST["id"]);
		echo json_encode($ret);
	}

    public function addApplicantChecklistsForm() {
    	$formData = array();
    	$result = array();
    	$result['key'] = 'addApplicantChecklist';
    	if (!$this->input->is_ajax_request()) show_404();
    	else {
    		$formData['key'] = $result['key'];
    		$result['form'] = $this->load->view('forms/applicantchecklistform.php', $formData, TRUE);
		}
    	echo json_encode($result);
	}

	// function to add applicant checklist
	public function addApplicantChecklist() {
		$result = array();
		$page = 'addApplicantChecklist';
		if (!$this->input->is_ajax_request()) {
			show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k, true);
				}

				$ret = new ApplicantChecklistsCollection();
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

	public function updateApplicantChecklistsForm() {
    	$formData = array();
    	$result = array();
    	$result['key'] = 'updateApplicantChecklist';
    	if (!$this->input->is_ajax_request()) show_404();
    	else {
    		$formData['key'] = $result['key'];
    		$result['form'] = $this->load->view('forms/applicantchecklistform.php', $formData, TRUE);
		}
    	echo json_encode($result);
	}

	// function to update applicant checklist
	public function updateApplicantChecklist(){
		$result = array();
		$page = 'updateApplicantChecklist';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new ApplicantChecklistsCollection();
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

	//for viewing form
	public function viewApplicantChecklistsForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewApplicantChecklist';
		if (!$this->input->is_ajax_request()) show_404();
		else {
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/applicantchecklistform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
}
?>
