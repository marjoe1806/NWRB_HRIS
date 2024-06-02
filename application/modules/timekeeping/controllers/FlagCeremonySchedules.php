<?php
class FlagCeremonySchedules extends MX_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('FlagCeremonySchedulesCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}

	public function index() {
		$listData = array();
		$viewData = array();
		$page = "viewFlagCeremonySchedules";
		$listData['key'] = $page;
		$ret = new FlagCeremonySchedulesCollection();
		if($ret->hasRows()){
			// var_dump($ret); die();
			$res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
			$respo = json_decode($res);
		} else{
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			$respo = json_decode($res);
		}
		$listData['list'] = $respo;

		$listData['holidays'] = $ret->getHolidays();

		$viewData['table'] = $this->load->view("helpers/flagceremonylist",$listData,TRUE);

		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Flag Ceremony Schedules');
			Helper::setMenu('templates/menu_template');
			Helper::setView('flagceremonyschedules',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		}
		else{
			$result['key'] = $listData['key'];
			$result['table'] = $viewData['table'];
			echo json_encode($result);
		}
		Session::checksession();
	}

	public function flagCeremonySchedulesForm(){
		$formData = array();
		$result = array();
		// var_dump($_POST); die();
		$result['key'] = $_POST['key'];
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$formData['params'] = $this->input->post();
			$result['form'] = $this->load->view('forms/flagceremonyschedulesform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}




	// public function addFlagCeremonySchedules() {

	// 	$result = array();
	// 	$page = 'updateFlagCeremonySchedules';
	// 	if (!$this->input->is_ajax_request()) {
	// 	   show_404();
	// 	}
	// 	else
	// 	{
	// 		if($this->input->post() && $this->input->post() != null)
	// 		{
	// 			$post_data = array();
	// 			foreach ($this->input->post() as $k => $v) {
	// 				$post_data[$k] = $this->input->post($k,true);
	// 			}
	// 			$ret =  new FlagCeremonySchedulesCollection();
	// 			if($ret->addRows($post_data)) {
	// 				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
	// 			} else {
	// 				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
	// 			}
	// 			$result = json_decode($res,true);
	// 		}
	// 		else
	// 		{
	// 			$res = new ModelResponse();
	// 			$result = json_decode($res,true);
	// 		}
	// 		$result['key'] = $page;
	// 	}
	// 	echo json_encode($result);
	// }

	public function updateFlagCeremonySchedules() {
		// var_dump($_POST); die();
		$result = array();
		$page = 'updateFlagCeremonySchedules';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			if($this->input->post() && $this->input->post() != null)
			{
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new FlagCeremonySchedulesCollection();
				if($ret->addRows($post_data)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
				$result = json_decode($res,true);
			}
			else
			{
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
			$result['key'] = $page;
		}
		echo json_encode($result);
	}

	public function activateFlagCeremonySchedules(){
		// die("hiit");
		$result = array();
		$page = 'activateFlagCeremonySchedules';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new FlagCeremonySchedulesCollection();
				if($ret->activeRowsFlag($post_data)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
				$result = json_decode($res,true);
			}
			else {
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
			$result['key'] = $page;
		}
		echo json_encode($result);
	}

	public function deactivateFlagCeremonySchedules(){
		$result = array();
		$page = 'deactivateFlagCeremonySchedules';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new FlagCeremonySchedulesCollection();
				if($ret->deactiveRowsFlag($post_data)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
				$result = json_decode($res,true);
			}
			else {
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
			$result['key'] = $page;
		}
		echo json_encode($result);
	}
}

?>