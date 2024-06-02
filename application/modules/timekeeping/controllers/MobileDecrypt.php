<?php

class MobileDecrypt extends MX_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('MobileDecryptCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}

	public function index() {
		$page = "addMobileDecryptPayrollSetup";
		$listData['key'] = $page;
		$viewData['regular_form'] = $this->load->view("forms/mobiledecryptform",$listData,TRUE);
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Import Raw DTR File');
			Helper::setMenu('templates/menu_template');
			Helper::setView('mobiledecrypt',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		}
		Session::checksession();
	}

	public function addMobileDecryptPayrollSetup(){
		$result = array();
		$page = 'addMobileDecryptPayrollSetup';
		if(!$this->input->is_ajax_request()) {
		   show_404();
		}
		else {
			$ret =  new MobileDecryptCollection();
			$ret->importRawDTR($_FILES['file']);
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			$result = json_decode($res,true);
			$result['key'] = $page;
		}
		echo json_encode($result);
	}

	function array_push_assoc($array, $key, $value){
		$array[$key] = $value;
		return $array;
	}

}

?>