<?php

class EmployeeFlexibleSchedules extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('EmployeeFlexibleSchedulesCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}

	public function index() {

		$listData = array();
		$viewData = array();
		$page = "viewEmployeeSchedules";
		$listData['key'] = $page;
		$ret = new EmployeeFlexibleSchedulesCollection();
		if($ret->hasRowsRegular()){
			$res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
			$respo = json_decode($res);
		}
		else{
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			$respo = json_decode($res);
		}
		$listData['list'] = $respo;
		$viewData['table'] = $this->load->view("helpers/employeeflexiblescheduleslist",$listData,TRUE);

		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Employee Flexible Schedules');
			Helper::setMenu('templates/menu_template');
			Helper::setView('employeesflexiblechedules',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		}
		else{
			$result['key'] = $listData['key'];
			$result['table'] = $viewData['table'];
			echo json_encode($result);
		}
		Session::checksession();
	}

	public function addEmployeeSchedulesForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'addEmployeeSchedules';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/employeeflexibleschedulesform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}


	
	public function updateEmployeeSchedulesForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'updateEmployeeSchedules';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/employeeflexibleschedulesform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	public function addEmployeeSchedules() {
		$result = array();
		$page = 'addEmployeeSchedules';
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
				$ret =  new EmployeeFlexibleSchedulesCollection();
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

	public function updateEmployeeSchedules() {
		$result = array();
		$page = 'updateEmployeeSchedules';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new EmployeeFlexibleSchedulesCollection();
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

	public function activateEmployeeSchedules(){
		$result = array();
		$page = 'activateEmployeeSchedules';
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
				$ret =  new EmployeeFlexibleSchedulesCollection();
				if($ret->activeRows($post_data)) {
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
	public function deactivateEmployeeSchedules(){
		$result = array();
		$page = 'deactivateEmployeeSchedules';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			if($this->input->post() && $this->input->post() != null)
			{
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k, true);
				}
				$ret =  new EmployeeFlexibleSchedulesCollection();
				if($ret->inactiveRows($post_data)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
				$result = json_decode($res, true);
			}
			else
			{
				$res = new ModelResponse();
				$result = json_decode($res, true);
			}
			$result['key'] = $page;
		}
		echo json_encode($result);
	}

	public function weeklyEmployeeSchedulesForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'weeklyEmployeeSchedules';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new EmployeeFlexibleSchedulesCollection();
			if($ret->hasRowsWeeklyEmployeeSchedules($_POST['id'])) {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
				$respo = json_decode($res);
			} else {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				$respo = json_decode($res);
			}
			$formData['list'] = $respo;
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('helpers/weeklyemployeescheduleslist.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	public function addWeeklyEmployeeSchedulesForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'addWeeklyEmployeeSchedules';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else {
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/weeklyemployeeschedulesmodalform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	public function updateWeeklyEmployeeSchedulesForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'updateWeeklyEmployeeSchedules';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else {
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/weeklyemployeeschedulesmodalform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	public function addWeeklyEmployeeSchedules(){
		$result = array();
		$page = 'addWeeklyEmployeeSchedules';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else {
			if($this->input->post() && $this->input->post() != null)
			{
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new EmployeeFlexibleSchedulesCollection();
				if($ret->addRowsWeekly($post_data)) {
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
	
	public function updateWeeklyEmployeeSchedules(){
		$result = array();
		$page = 'updateWeeklyEmployeeSchedules';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new EmployeeFlexibleSchedulesCollection();
				if($ret->updateRowsWeekly($post_data)) {
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

	public function activateWeeklyEmployeeSchedules(){
		$result = array();
		$page = 'activateWeeklyEmployeeSchedules';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new EmployeeFlexibleSchedulesCollection();
				if($ret->activeRowsWeekly($post_data)) {
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
	
	public function deactivateWeeklyEmployeeSchedules(){
		$result = array();
		$page = 'deactivateWeeklyEmployeeSchedules';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new EmployeeFlexibleSchedulesCollection();
				if($ret->inactiveRowsWeekly($post_data)) {
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

	public function getActiveEmployeeSchedules(){
		$result = array();
		$page = 'getActiveEmployeeSchedules';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else {
			$ret =  new EmployeeFlexibleSchedulesCollection();
			if($ret->hasRowsActive()) {
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