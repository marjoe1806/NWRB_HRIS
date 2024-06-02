<?php

class InterviewSchedules extends MX_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Helper');
        $this->load->module('session');
        $this->load->model('InterviewSchedulesCollection');
        $this->load->model('ModuleRels');
        Helper::sessionEndedHook('session');
    }

    public function index() {
        Helper::rolehook(ModuleRels::INTERVIEW_SCHEDULES_SUB_MENU);
        $listData = array();
        $viewData = array();

        $page = "viewInterviewSchedules";
        $listData['key'] = $page;
        $viewData['table'] = $this->load->view("helpers/interviewscheduleslist", $listData, TRUE);

        if (!$this->input->is_ajax_request()) {
            Helper::setTitle('Interview Schedule');
            Helper::setMenu('templates/menu_template');
            Helper::setView('interviewschedules', $viewData, FALSE);
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
    	$ret = new InterviewSchedulesCollection();
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

                $sub_array = array();

                foreach($row as $k1=>$v1) {
                    $buttons_data .= ' data-'.$k1.'="'.$v1.'" ';
                }

				$buttons_action .= '<a id="updateInterviewSchedulesForm"'
					. ' class="updateInterviewSchedulesForm" style="text-decoration: none"'
					. ' href=' . base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . '/updateInterviewSchedulesForm '
					. $buttons_data
					. ' > '
					. ' <button class="btn btn-primary btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Update applicant">'
					. ' <i class="material-icons">mode_edit</i>'
					. '</button> '
					. '</a>';

                $buttons_action .= '<a id="viewInterviewSchedulesForm" '
					. ' class="viewInterviewSchedulesForm" style="text-decoration: none;" '
					. ' href="' . base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . '/viewInterviewSchedulesForm" '
					. $buttons_data
					. ' > '
					. ' <button class="btn btn-primary btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="View Details">'
					. ' <i class="material-icons">list</i>'
					. ' </button> '
					. ' </a>';

                $sub_array[] = $buttons_action;
                $sub_array[] = $row->id;
                $sub_array[] = $row->name;
                $sub_array[] = $row->position;
                $sub_array[] = $row->schedule_date;
                $sub_array[] = $row->schedule_time;
                $sub_array[] = $row->remarks;
                $data[] = $sub_array;

            }
        
        $output = array(
            "draw"					=>		intval($_POST["draw"]),
            "recordsTotal"			=>		$this->InterviewSchedulesCollection->get_all_data(),
            "recordsFiltered"		=>		$this->InterviewSchedulesCollection->get_filtered_data(),
            "data"					=>		$data
        );
        echo json_encode($output);
    }

    public function getInterviewScheduleTables() {
        if(!$this->input->is_ajax_request()) show_404();

        $ret = $this->InterviewSchedulesCollection->getInterviewSchedulesRows($_POST["id"]);
        echo json_encode($ret);
    }

    public function addInterviewSchedulesForm() {
    	$formData = array();
    	$result = array();
    	$result['key'] = 'addInterviewSchedule';
    	if (!$this->input->is_ajax_request()) show_404();
    	else {
    		$formData['key'] = $result['key'];
    		$result['form'] = $this->load->view('forms/interviewscheduleform.php', $formData, TRUE);
		}
    	echo json_encode($result);
	}

	// function to add interview schedule
	public function addInterviewSchedule() {
		$result = array();
		$page = 'addInterviewSchedule';
		if (!$this->input->is_ajax_request()) {
			show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k, true);
				}

				$ret = new InterviewSchedulesCollection();
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

	public function updateInterviewSchedulesForm() {
		$formData = array();
		$result = array();
		$result['key'] = 'updateInterviewSchedule';
		if (!$this->input->is_ajax_request()) show_404();
		else {
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/interviewscheduleform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	// function to update interview schedule
	public function updateInterviewSchedule(){

		//var_dump($_POST);die();
		$result = array();
		$page = 'updateInterviewSchedule';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new InterviewSchedulesCollection();
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
	public function viewInterviewSchedulesForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewInterviewSchedule';
		if (!$this->input->is_ajax_request()) show_404();
		else {
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/interviewscheduleform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
}
?>
