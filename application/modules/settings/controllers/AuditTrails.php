<?php

class AuditTrails extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('AuditTrailsCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::AUDIT_TRAILS_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewAuditTrails";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/audittrailslist",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Audit Trails');
			Helper::setMenu('templates/menu_template');
			Helper::setView('audittrails',$viewData,FALSE);
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

		$day = "";
		if(isset($_GET["day"]) && isset($_GET["month"]) && isset($_GET["year"])) $day = $_GET["year"]."-".$_GET["month"]."-".$_GET["day"];
		else $day = date("Y-m-d");

        $fetch_data = $this->AuditTrailsCollection->make_datatables($day);  
        $data = array();  
        foreach($fetch_data as $k => $row)  
        {  
        	$buttons = "";
        	$buttons_data = "";
        	if($row->userid != 0){
	        	$row->employee_number = $this->Helper->decrypt($row->employee_number,$row->employee_id);
	        	$row->employee_id_number = $this->Helper->decrypt($row->employee_id_number,$row->employee_id);
	        	$row->first_name = $this->Helper->decrypt($row->first_name,$row->employee_id);
	        	$row->middle_name = $this->Helper->decrypt($row->middle_name,$row->employee_id);
	        	$row->last_name = $this->Helper->decrypt($row->last_name,$row->employee_id);
	        }
	        else{
	        	$row->employee_number = "N/A";
	        	$row->employee_id_number = "N/A";
	        	$row->first_name = "Mobile";
	        	$row->middle_name = "";
	        	$row->last_name = "Sync";
	        }
            $sub_array = array();    
            $sub_array[] = $row->employee_id_number;
            $sub_array[] = $row->first_name.' '.$row->last_name;  
            $sub_array[] = $row->log;
            $sub_array[] = $row->ip;
            $sub_array[] = $row->source_device;
            $sub_array[] = date('m/d/Y h:i:s',strtotime($row->timestamp));
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"                  =>     intval($_GET["draw"]),  
            "recordsTotal"          =>      $this->AuditTrailsCollection->get_all_data($day),  
            "recordsFiltered"     	=>     $this->AuditTrailsCollection->get_filtered_data($day),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
    }
}

?>