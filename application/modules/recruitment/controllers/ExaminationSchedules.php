<?php
//                                            EXAMINATION_SCHEDULES_SUB_MENU
// Add menu in applications\models\ModuleRels ^
class ExaminationSchedules extends MX_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Helper');
        $this->load->module('session');
        $this->load->model('ExaminationSchedulesCollection');
        $this->load->model('ModuleRels');
        Helper::sessionEndedHook('session');
    }

    public function index() {
        Helper::rolehook(ModuleRels::EXAMINATION_SCHEDULES_SUB_MENU);
        $listData = array();
        $viewData = array();

        $page = "viewExaminationSchedules";
        $listData['key'] = $page;
        $viewData['table'] = $this->load->view("helpers/examinationscheduleslist", $listData, TRUE);

        if (!$this->input->is_ajax_request()) {
            Helper::setTitle('Examination Schedule');
            Helper::setMenu('templates/menu_template');
            Helper::setView('examinationschedules', $viewData, FALSE);
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
    	$ret = new ExaminationSchedulesCollection();
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

				switch(strtoupper($row->status)) {
					case '0' :
						$status_color 	= "text-warning";
						$status_msg 	= "PENDING";
						$flag 			= 1;
						break;
					case '1' :
						$status_color 	= "text-success";
						$status_msg 	= "PASSED";
						$flag 			= 0;
						break;
					case '999' :
						$status_color 	= "text-danger";
						$status_msg 	= "FAILED";
						$flag 			= 0;
						break;
				}

                $sub_array = array();

                foreach($row as $k1=>$v1) {
                    $buttons_data .= ' data-'.$k1.'="'.$v1.'" ';
                }

                $buttons_action .= '<a id="viewExaminationSchedulesForm" '
					. ' class="viewExaminationSchedulesForm" style="text-decoration: none;" '
					. ' href="' . base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . '/viewExaminationSchedulesForm" '
					. $buttons_data
					. ' > '
					. ' <button class="btn btn-primary btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="View Detail">'
					. ' <i class="material-icons">list</i>'
					. ' </button> '
					. ' </a>';

                $buttons_action .= '<a id="updateExaminationSchedulesForm"'
                    . ' class="updateExaminationSchedulesForm" style="text-decoration: none"'
                    . ' href=' . base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . '/updateExaminationSchedulesForm '
                    . $buttons_data
                    . ' > '
                    . ' <button class="btn btn-primary btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Update examination schedule">'
                    . ' <i class="material-icons">edit</i>'
                    . '</button> '
                    . '</a>';

				if($flag) {
					$buttons_action .= '<a id="passedExamination" '
						. ' class="passedExamination" style="text-decoration: none;" '
						. ' href="' . base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . '/passedExamination" '
						. $buttons_data
						. ' > '
						. ' <button class="btn btn-success btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Pass Examination">'
						. ' <i class="material-icons">check_circle</i>'
						. ' </button> '
						. ' </a>';

					$buttons_action .= '<a id="failedExamination" '
						. ' class="failedExamination" style="text-decoration: none;" '
						. ' href="' . base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . '/failedExamination" '
						. $buttons_data
						. ' > '
						. ' <button class="btn btn-danger btn-circle wave-effect waves waves-float" data-toggle="tooltip" data-placement="top" title="Fail Examination">'
						. ' <i class="material-icons">remove_circle</i>'
						. ' </button> '
						. ' </a>';
				}

                $sub_array[] = $buttons_action;
                $sub_array[] = $row->id;
                $sub_array[] = $row->name;
                $sub_array[] = $row->position;
                $sub_array[] = $row->schedule_date;
                $sub_array[] = $row->schedule_time;
				$sub_array[] = '<b><span class="'.$status_color.'">'.strtoupper($status_msg).'</span><b>';
                $sub_array[] = $row->remarks;
                $data[] = $sub_array;

            }
        
        $output = array(
            "draw"					=>		intval($_POST["draw"]),
            "recordsTotal"			=>		$this->ExaminationSchedulesCollection->get_all_data(),
            "recordsFiltered"		=>		$this->ExaminationSchedulesCollection->get_filtered_data(),
            "data"					=>		$data
        );
        echo json_encode($output);
    }

    public function getExaminationScheduleTables() {
        if(!$this->input->is_ajax_request()) show_404();

        $ret = $this->ExaminationSchedulesCollection->getExaminationSchedulesRows($_POST["id"]);
        echo json_encode($ret);
    }

    public function addExaminationSchedulesForm() {
    	$formData = array();
    	$result = array();
    	$result['key'] = 'addExaminationSchedule';
    	if (!$this->input->is_ajax_request()) show_404();
    	else {
    		$formData['key'] = $result['key'];
    		$result['form'] = $this->load->view('forms/examinationscheduleform.php', $formData, TRUE);
		}
    	echo json_encode($result);
	}

	// function to add examination schedule
	public function addExaminationSchedule() {
		$result = array();
		$page = 'addExaminationSchedule';
		if (!$this->input->is_ajax_request()) {
			show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k, true);
				}

				$ret = new ExaminationSchedulesCollection();
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

	public function updateExaminationSchedulesForm() {
    	$formData = array();
    	$result = array();
    	$result['key'] = 'updateExaminationSchedule';
    	if (!$this->input->is_ajax_request()) show_404();
    	else {
    		$formData['key'] = $result['key'];
    		$result['form'] = $this->load->view('forms/examinationscheduleform.php', $formData, TRUE);
		}
    	echo json_encode($result);
	}

	// function to update examination schedule
	public function updateExaminationSchedule(){
		$result = array();
		$page = 'updateExaminationSchedule';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new ExaminationSchedulesCollection();
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
	public function viewExaminationSchedulesForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewExaminationSchedule';
		if (!$this->input->is_ajax_request()) show_404();
		else {
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/examinationscheduleform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	// pass examination
	public function passedExamination() {
		$result = array();
		$page = 'passedExamination';
		if (!$this->input->is_ajax_request()) {
			show_404();
		} else {
			if ($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k, true);
				}

				$ret = new ExaminationSchedulesCollection();
				if ($ret->passedExamination($post_data)) {
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

	// failed examination
	public function failedExamination() {
		$result = array();
		$page = 'failedExamination';
		if (!$this->input->is_ajax_request()) {
			show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}

				$ret =  new ExaminationSchedulesCollection();
				if($ret->failedExamination($post_data)) {
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
