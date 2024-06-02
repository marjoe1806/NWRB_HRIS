<?php

class SpecialPayroll extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('SpecialPayrollCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::SPECIAL_PAYROLL_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewSpecialPayroll";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/specialpayrolllist",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Special Payroll');
			Helper::setMenu('templates/menu_template');
			Helper::setView('specialpayroll',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		}
		else{
			$result['key'] = $listData['key'];
			$result['table'] = $viewData['table'];
			echo json_encode($result);
		}
		Session::checksession();
	}

	public function getList(){ 
		$division_id = $this->input->post('division_id');
		$bonus_type = $this->input->post('bonus_type');
		$year = $this->input->post('year');

        $res['data'] = $this->SpecialPayrollCollection->getList($division_id,$bonus_type,$year);  
        $res['table'] = $this->load->view("helpers/specialpayrolllist",$res,TRUE); 
        echo json_encode($res);  
    }

    public function saveBonus(){
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
				$ret =  new SpecialPayrollCollection();
				if($ret->saveBonus($post_data)) {
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
		}
		echo json_encode($result);
	}
}

?>