<?php

class TrainingList extends MX_Controller {

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
        $page = "viewTrainingList";
        $listData['key'] = $page;
        $viewData['table'] = $this->load->view("helpers/traininglist", $listData, TRUE);

        if (!$this->input->is_ajax_request()) {
            Helper::setTitle('Training Report');
            Helper::setMenu('templates/menu_template');
            Helper::setView('traininglist', $viewData, FALSE);
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

                $sub_array = array();

                foreach($row as $k1=>$v1) {
                    $buttons_data .= ' data-'.$k1.'="'.$v1.'" ';
                }

                $buttons_action .= '<a id="viewTrainingListForm" '
					. ' class="viewTrainingListForm" style="text-decoration: none;" '
					. ' href="' . base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . '/viewTrainingListForm" '
					. $buttons_data
					. ' > '
					. ' <button class="btn btn-primary btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="right" title="View Details">'
					. ' <i class="material-icons">remove_red_eye</i>'
					. ' </button> '
					. ' </a>';
                $buttons_action .= '<a id="viewTrainingForm" '
                    . ' class="viewTrainingForm" style="text-decoration: none;" '
                    . ' href="' . base_url() . $this->uri->segment(1) . "/" . $this->uri->segment(2) . '/viewTrainingForm" '
                    . $buttons_data
                    . ' > '
                    . ' <button class="btn btn-success btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="right" title="Print Preview">'
                    . ' <i class="material-icons">print</i>'
                    . ' </button> '
                    . ' </a>';

                $sub_array[] = $buttons_action;
                $sub_array[] = $row->title;
                $sub_array[] = date("Y", strtotime($row->start_date));
                $sub_array[] = $row->sponsor;
                $sub_array[] = $row->venue;
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

    public function getTrainingListTables() {
        if(!$this->input->is_ajax_request()) show_404();

        $ret = $this->ManageTrainingsCollection->getTrainingListRows($_POST["id"]);
        echo json_encode($ret);
    }

	//for viewing form
	public function viewTrainingListForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewTrainingList';
		if (!$this->input->is_ajax_request()) show_404();
		else {
			$formData['key'] = $result['key'];
			$ret = new ManageTrainingsCollection();
			$formData["employees"] = $ret->getActiveEmployees();
			$result['form'] = $this->load->view('forms/trainingform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

    public function viewTrainingForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewTraining';
		if (!$this->input->is_ajax_request()) show_404();
		else {
            $seminar_id = isset($_POST['seminar_id'])?$_POST['seminar_id']:"";
			$ret =  new ManageTrainingsCollection();
            $formData['list'] = $ret->getTrainingListsAttendees($seminar_id);
			if(sizeof($formData['list']) > 0) {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
			} else {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			}
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('helpers/trainingprintpreview.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	public function getTrainingListAttendees(){
		$result = array();
		$page = 'getTrainingListAttendees';
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
