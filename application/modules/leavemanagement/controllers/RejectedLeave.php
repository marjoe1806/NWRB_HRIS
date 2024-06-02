<?php

class RejectedLeave extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('LeaveCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		//Helper::rolehook(ModuleRels::VIEW_APPROVED_ARCHIVES);
		$listData = array();
		$viewData = array();
		$page = "viewRejectedLeave";
		$listData['key'] = $page;
		$ret = new LeaveCollection();
		if($ret->hasRows("REJECTED")){
			$res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
			$respo = json_decode($res);
		}
		else{
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			$respo = json_decode($res);
		}
		$listData['list'] = $respo;
		$viewData['table'] = $this->load->view("helpers/rejectedleavelist",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Rejected Leaves');
			Helper::setMenu('templates/menu_template');
			Helper::setView('rejectedleave',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		}
		else{
			$result['key'] = $listData['key'];
			$result['table'] = $viewData['table'];
			echo json_encode($result);
		}
		Session::checksession();
	}
	//Regular Leave
	public function viewRejectedLeaveRegularDetails(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewRejectedLeaveRegularDetails';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{

			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/pendingleaveregularform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	//Special Leave
	public function viewRejectedLeaveSpecialDetails(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewRejectedLeaveSpecialDetails';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{

			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/pendingleavespecialform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function getLeaveDates(){
		//var_dump($_POST);die();
		$result = array();
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
				$ret =  new LeaveCollection();
				if($ret->fetchLeaveDates($post_data['id'])) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
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
		}
		echo json_encode($result);
	}
}

?>