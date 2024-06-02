<?php

class ManageTrainings extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Helper');
        $this->load->model('ManageTrainingsCollection');
        $this->load->module('session');
        $this->load->model('ModuleRels');
        Helper::sessionEndedHook('session');
    }

    public function index() {
        // Helper::rolehook(ModuleRels::MANAGE_TRAININGS_SUB_MENU);
        $listData = array();
        $viewData = array();
        $page = "viewManageTrainings";
        $listData['key'] = $page;
        $viewData['table'] = $this->load->view("helpers/trainingslist", $listData, TRUE);

        if (!$this->input->is_ajax_request()) {
            Helper::setTitle('Manage Trainings');
            Helper::setMenu('templates/menu_template');
            Helper::setView('trainings', $viewData, FALSE);
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
    	$ret = new ManageTrainingsCollection();
    	$ret = $ret->make_datatables();
            foreach($ret as $k => $row) {
                $buttons_action = "";
                $buttons_data = "";

				switch($row->status) {
					case '0' :
						$status_color 	= "text-success";
						$status_msg 	= "ACTIVE";
						$flag 			= 1;
						break;
					// case '1' :
					// 	$status_color 	= "text-success";
					// 	$status_msg 	= "DONE";
					// 	$flag 			= 0;
					// 	break;
					// case '999' :
					// 	$status_color 	= "text-danger";
					// 	$status_msg 	= "CANCELLED";
					// 	$flag 			= 0;
					// 	break;
				}

                $sub_array = array();

                foreach($row as $k1=>$v1) {
                    $buttons_data .= ' data-'.$k1.'="'.$v1.'" ';
                }

                $buttons_action .= '<a id="viewManageTrainingsForm" '
					. ' class="viewManageTrainingsForm" style="text-decoration: none;" '
					. ' href="' . base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . '/viewManageTrainingsForm" '
					. $buttons_data
					. ' > '
					. ' <button class="btn btn-primary btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="right" title="View Details">'
					. ' <i class="material-icons">remove_red_eye</i>'
					. ' </button> '
					. ' </a>';

                $buttons_action .= '<a id="updateManageTrainingsForm"'
                    . ' class="updateManageTrainingsForm" style="text-decoration: none"'
                    . ' href=' . base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . '/updateManageTrainingsForm '
                    . $buttons_data
                    . ' > '
                    . ' <button class="btn btn-info btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="right" title="Update Trainings">'
                    . ' <i class="material-icons">edit</i>'
                    . '</button> '
                    . '</a>';

                $sub_array[] = $buttons_action;
                $sub_array[] = $row->title;
                $sub_array[] = $row->sponsor;
                $sub_array[] = $row->venue;
                $sub_array[] = $row->no_of_hours;
                $sub_array[] = $row->type;
                $sub_array[] = $row->start_date;
                $sub_array[] = $row->end_date;
                $sub_array[] = $row->travel_order;
                $sub_array[] = $row->office_order;
                $sub_array[] ='<b><span class="'.$status_color.'">'.strtoupper($status_msg).'</span><b>';;
                $data[] = $sub_array;

            }
        
        $output = array(
            "draw"					=>		intval($_POST["draw"]),
            "recordsTotal"			=>		$this->ManageTrainingsCollection->get_all_data(),
            "recordsFiltered"		=>		$this->ManageTrainingsCollection->get_filtered_data(),
            "data"					=>		$data
        );
        echo json_encode($output);
    }

    public function getManageTrainingsTables() {
        if(!$this->input->is_ajax_request()) show_404();

        $ret = $this->ManageTrainingsCollection->getManageTrainingsRows($_POST["id"]);
        echo json_encode($ret);
    }

    public function addManageTrainingsForm() {
    	$formData = array();
    	$result = array();
    	$result['key'] = 'addManageTrainings';
    	if (!$this->input->is_ajax_request()) show_404();
    	else {
    		$formData['key'] = $result['key'];
			$ret = new ManageTrainingsCollection();
			$formData["employees"] = $ret->getActiveEmployees();
    		$result['form'] = $this->load->view('forms/trainingform.php', $formData, TRUE);
		}
    	echo json_encode($result);
	}

	// function to add manage trainings
	public function addManageTrainings() {
		$result = array();
		$page = 'addManageTrainings';
		if (!$this->input->is_ajax_request()) {
			show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k, true);
				}

				$ret = new ManageTrainingsCollection();
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

	public function updateManageTrainingsForm() {
    	$formData = array();
    	$result = array();
    	$result['key'] = 'updateManageTrainings';
    	if (!$this->input->is_ajax_request()) show_404();
    	else {
    		$formData['key'] = $result['key'];
			$ret = new ManageTrainingsCollection();
			$formData["employees"] = $ret->getActiveEmployees();
    		$result['form'] = $this->load->view('forms/trainingform.php', $formData, TRUE);
		}
    	echo json_encode($result);
	}

	// function to update trainings
	public function updateManageTrainings(){
		$result = array();
		$page = 'updateManageTrainings';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new ManageTrainingsCollection();
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
	public function viewManageTrainingsForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewManageTrainings';
		if (!$this->input->is_ajax_request()) show_404();
		else {
			$formData['key'] = $result['key'];
			$ret = new ManageTrainingsCollection();
			$formData["employees"] = $ret->getActiveEmployees();
			$result['form'] = $this->load->view('forms/trainingform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	public function getManageTrainingsAttendees(){
		$result = array();
		$page = 'getManageTrainingsAttendees';
		if (!$this->input->is_ajax_request()) show_404();
		else{
			$seminar_id = isset($_POST['seminar_id'])?$_POST['seminar_id']:"";
			$ret =  new ManageTrainingsCollection();
			if($ret->getTrainingsAttendees($seminar_id)) {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
			} else {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			}
			$result = json_decode($res,true);
			$result['key'] = $page;
		}
		echo json_encode($result);
	}
}
?>
