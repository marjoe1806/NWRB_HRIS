<?php

class EmployeesMobile extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('EmployeesMobileCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::DOWNLOAD_MOBILE_EMPLOYEES_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewEmployeesMobile";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/employeesmobilelist",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Employees Mobile');
			Helper::setMenu('templates/menu_template');
			Helper::setView('employeemobile',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		}
		else{
			$result['key'] = $listData['key'];
			$result['table'] = $viewData['table'];
			echo json_encode($result);
		}
		Session::checksession();
	}
	function fetchRows(){ 
        $fetch_data = $this->EmployeesMobileCollection->make_datatables();  
        $data = array();  
        foreach($fetch_data as $k => $row)  
        {    
        	$sub_array = array();
            $sub_array[] = $row->description;
            $sub_array[] = $row->log;
            $sub_array[] = $row->username;  
            $sub_array[] = $row->date_created;
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"                  =>     intval($_POST["draw"]),  
            "recordsTotal"          =>      $this->EmployeesMobileCollection->get_all_data(),  
            "recordsFiltered"     	=>     $this->EmployeesMobileCollection->get_filtered_data(),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
    }
    public function downloadEmployeesMobile(){
    	//var_dump($_POST);die();
		$result = array();
		$page = 'downloadEmployeesMobile';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			if($this->input->post() && $this->input->post() != null)
			{
				$mobile_id = @$_POST['mobile_id'];
				// var_dump($location_id);die();
				$ret =  new EmployeesMobileCollection();
				if($ret->downloadMobileEmployees($mobile_id)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
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
    public function test(){
    	$securityKey = "TheBestSecretKey";
		$this->load->model("Security");
		$security = new Security();
		$text = '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';
		$decrypted = $security->decrypt($text,$securityKey);
		echo $decrypted;
    }

}

?>