<?php

class ActualAttendance extends MX_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('Helper');
    $this->load->model('ActualAttendanceCollection');
    $this->load->module('session');
    $this->load->model('ModuleRels');
    Helper::sessionEndedHook('session');
  }

  public function index() {
		$listData = array();
		$viewData = array();
		$page = "viewActualAttendance";
		$listData['key'] = $page;
		// $viewData['table'] = $this->load->view("helpers/actualattendancelist",$listData,TRUE);
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Daily Time Record Summary');
			Helper::setMenu('templates/menu_template');
			Helper::setView('actualattendance',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		}
		else{
			$result['key'] = $listData['key'];
			$result['table'] = $viewData['table'];
			echo json_encode($result);
		}
		Session::checksession();
	}

}

?>