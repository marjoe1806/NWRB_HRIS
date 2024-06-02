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
            $sub_array[] = $row->location_name;
            $sub_array[] = $row->employee_number;
            $sub_array[] = $row->first_name.' '.$row->last_name;  
            $sub_array[] = $row->position_name;
            $sub_array[] = $row->department_name;
            $sub_array[] = $row->contact_number;
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
				$location_id = @$_POST['location_id'];
				// var_dump($location_id);die();
				$ret =  new EmployeesMobileCollection();
				if($ret->downloadMobileEmployees($location_id)) {
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

}

?>