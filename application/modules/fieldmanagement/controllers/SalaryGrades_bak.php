<?php

class SalaryGrades extends MX_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('SalaryGradesCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		// Helper::rolehook(ModuleRels::SALARY_GRADES_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewSalaryGrades";
		// Settings
		$listData['key'] = $page;
		$ret = new SalaryGradesCollection();
		if($ret->hasRowsMonthly()){
			$res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
			$respo = json_decode($res);
		}
		else{
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			$respo = json_decode($res);
		}
		$listData['list'] = $respo;
		$viewData['table_monthly'] = $this->load->view("helpers/salarygradesmonthlylist",$listData,TRUE);
		// Overview
		if($ret->hasRowsMonthlyActive()){
			$res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
			$respo = json_decode($res);
			foreach ($respo->Data->details as $k => $v) {
				$step_details = $ret->getSalaryGrades($v->id);
				$steps = isset($step_details['details'][0]) ? $step_details['details'][0] : null;
				$respo->Data->details[$k]->steps = $steps;
			}
		}
		else{
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			$respo = json_decode($res);
		}
		$listData2['key'] = "viewSalaryGradesSemiMonthly";
		$listData2['list'] = $respo;
		$viewData['table_semimonthly'] = $this->load->view("helpers/salarygradessemimonthlylist",$listData2,TRUE);

		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Salary Grades');
			Helper::setMenu('templates/menu_template');
			Helper::setView('salarygrades',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		}
		else{
			$result['key'] = $listData['key'];
			$result['table_monthly'] = $viewData['table_monthly'];
			$result['table_semimonthly'] = $viewData['table_semimonthly'];
			echo json_encode($result);
		}
		Session::checksession();
	}
	public function addSalaryGradesForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'addSalaryGrades';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/salarygradesform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	public function updateSalaryGradesForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'updateSalaryGrades';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/salarygradesform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	public function addWeeklySalaryGradesForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'addWeeklySalaryGrades';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/weeklysalarygradesmodalform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	public function updateWeeklySalaryGradesForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'updateWeeklySalaryGrades';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/weeklysalarygradesmodalform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}


	public function weeklySalaryGradesForm(){
		// var_dump($_POST); die();
		$formData = array();
		$result = array();
		$result['key'] = 'weeklySalaryGrades';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new SalaryGradesCollection();
			if($ret->hasRowsWeeklySalaryGrades($_POST['id'])) {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
				$respo = json_decode($res);
			} else {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				$respo = json_decode($res);
			}
			$formData['list'] = $respo;

			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/weeklysalarygradesform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	public function addSalaryGrades(){
		//var_dump($_POST);die();
		$result = array();
		$page = 'addSalaryGrades';
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
				$ret =  new SalaryGradesCollection();
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
	public function updateSalaryGrades(){
		$result = array();
		$page = 'updateSalaryGrades';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new SalaryGradesCollection();
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

	public function activateSalaryGrades(){
		$result = array();
		$page = 'activateSalaryGrades';
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
				$ret =  new SalaryGradesCollection();
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
	public function deactivateSalaryGrades(){
		$result = array();
		$page = 'deactivateSalaryGrades';
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
				$ret =  new SalaryGradesCollection();
				if($ret->inactiveRows($post_data)) {
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
	public function getSalaryGradesById(){
		$result = array();
		$page = 'getSalaryGradesById';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new SalaryGradesCollection();
			if($ret->hasRowsById()) {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
			} else {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			}
			$result = json_decode($res,true);

			$result['key'] = $page;
		}
		echo json_encode($result);
	}
	public function getSalaryGradesStepsById(){
		$result = array();
		$page = 'getSalaryGradesStepsById';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$id = isset($_GET['id'])?$_GET['id']:"";
			$ret =  new SalaryGradesCollection();
			//var_dump($id);die();
			if($ret->hasRowsStepsByGradeId($id)) {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
			} else {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			}
			$result = json_decode($res,true);

			$result['key'] = $page;
		}
		echo json_encode($result);
	}
	public function getActiveSalaryGradesCutOff(){
		$cutoff = $this->input->get('CutOff');
		$result = array();
		$page = 'getActiveSalaryGradesCutOff';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new SalaryGradesCollection();
			if($cutoff == "Monthly") {
				if($ret->hasRowsMonthly()) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
			}
			else {
				if($ret->hasRowsSemiMonthly()) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
			}

			$result = json_decode($res,true);

			$result['key'] = $page;
		}
		echo json_encode($result);
	}





	public function addWeeklySalaryGrades(){
		$result = array();
		$page = 'addWeeklySalaryGrades';
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
				$ret =  new SalaryGradesCollection();
				if($ret->addRowsWeekly($post_data)) {
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
	public function updateWeeklySalaryGrades(){
		$result = array();
		$page = 'updateWeeklySalaryGrades';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new SalaryGradesCollection();
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

	public function activateWeeklySalaryGrades(){
		$result = array();
		$page = 'activateWeeklySalaryGrades';
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
				$ret =  new SalaryGradesCollection();
				if($ret->activeRowsWeekly($post_data)) {
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
	public function deactivateWeeklySalaryGrades(){
		$result = array();
		$page = 'deactivateWeeklySalaryGrades';
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
				$ret =  new SalaryGradesCollection();
				if($ret->inactiveRowsWeekly($post_data)) {
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
	public function getActiveWeeklySalaryGrades(){
		$result = array();
		$page = 'getActiveWeeklySalaryGrades';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new SalaryGradesCollection();
			if($ret->hasRowsActiveWeekly()) {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
			} else {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			}
			$result = json_decode($res,true);

			$result['key'] = $page;
		}
		echo json_encode($result);
	}
	public function getWeeklyPeriodByPeriodId(){
		$result = array();
		$page = 'getActiveWeeklySalaryGrades';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			if($this->input->post() && $this->input->post() != null)
			{
				$ret =  new SalaryGradesCollection();
				if($ret->hasRowsWeeklySalaryGrades()) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
				$result = json_decode($res,true);
			}
			else{
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
			$result['key'] = $page;
		}
		echo json_encode($result);
	}

	public function saveSalaryGradeSteps(){
		$result = array();
		if (!$this->input->is_ajax_request()) {
			show_404();
		}
		else
		{
			if($this->input->post() && $this->input->post() != null)
			{
				$params = $_POST;
				$ret =  new SalaryGradesCollection();
				if($ret->getSalaryGradeSteps($params)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
				$result = json_decode($res,true);
			}
			else{
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
		}
		echo json_encode($result);
	}
	public function getActiveSalaryGrades(){
		$result = array();
		$page = 'getActiveSalaryGrades';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new SalaryGradesCollection();
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
}

?>