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
		Helper::rolehook(ModuleRels::SALARY_GRADES_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewSalaryGrades";
		$listData['key'] = $page;
		$ret = new SalaryGradesCollection();
		$viewData['table'] = $this->load->view("helpers/salarygradeslist",$listData,TRUE); 
		$viewData['form'] = $this->load->view("forms/salarygradesform",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Salary Grades');
			Helper::setMenu('templates/menu_template');
			Helper::setView('salarygrades',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		}
		else{
			$result['key'] = $listData['key'];
			$result['table'] = $viewData['table'];
			echo json_encode($result);
		}
		Session::checksession();
	}
	public function getActiveSalaryGradesSteps(){
		$result = array();
		$page = 'getActiveSalaryGradesSteps';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$pay_basis = isset($_REQUEST['pay_basis'])?$_REQUEST['pay_basis']:"";
			$effectivity = isset($_REQUEST['effectivity'])?$_REQUEST['effectivity']:"";
			$ret =  new SalaryGradesCollection();
			if($ret->hasRows($pay_basis,$effectivity)) {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
			} else {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			}
			$result = json_decode($res,true);
			
			$result['key'] = $page;
		}
		echo json_encode($result);
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
	public function addSalaryGrades(){
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

	public function getActiveSalaryGrades(){
		$result = array();
		$page = 'getActiveSalaryGrades';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$pay_basis = isset($_POST['pay_basis'])?$_POST['pay_basis']:"";
			$ret =  new SalaryGradesCollection();
			if($ret->hasRowsActive($pay_basis)) {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
			} else {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			}
			$result = json_decode($res,true);
			
			$result['key'] = $page;
		}
		echo json_encode($result);
	} 
	public function getSalaryGradesBySalary(){
		$result = array();
		$page = 'getSalaryGradesBySalary';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$salary = isset($_POST['salary'])?$_POST['salary']:"";
			$ret =  new SalaryGradesCollection();
			if($ret->hasRowsBySalary($salary)) {
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
			$grade_id = isset($_GET['id'])?$_GET['id']:"";
			$pay_basis = @$_GET['pay_basis'];
			$ret =  new SalaryGradesCollection();
			if($ret->hasRowsStepsByGrade($grade_id,$pay_basis)) {
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