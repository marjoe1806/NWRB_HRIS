<?php

class OvertimeServiceRendered extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('OvertimeServiceRenderedCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		// Helper::rolehook(ModuleRels::EMPLOYEES_SERVICE_RECORDS_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewPayslip";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/overtimeservicerenderedlist",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Overtime Service Rendered');
			Helper::setMenu('templates/menu_template');
			Helper::setView('overtimeservicerendered',$viewData,FALSE);
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
        $division_id = (isset($_GET['DivisionId']))?@$_GET['DivisionId']:"";
		$fetch_data = $this->OvertimeServiceRenderedCollection->make_datatables($division_id);
        $data = array();  
        foreach($fetch_data as $k => $row) {  
        	$buttons = "";
        	$buttons_data = "";
			$emp_id_num = "";
			$fullname = "";
			$emp_id = $row->salt;
        	// $row->employee_number = $this->Helper->decrypt((string)$row->employee_number,(string)$row->employee_id);
        	$emp_id_num = $this->Helper->decrypt((string)$row->employee_id_number,$emp_id);
        	$row->first_name = $this->Helper->decrypt((string)$row->first_name,$emp_id);
        	$row->middle_name = $this->Helper->decrypt((string)$row->middle_name,$emp_id);
        	$row->last_name = $this->Helper->decrypt((string)$row->last_name,$emp_id);
            $sub_array = array();    
            $sub_array[] = $emp_id_num;
            $sub_array[] = $row->first_name.' '.$row->last_name; 
            $sub_array[] = $row->position_name;
            $sub_array[] = $row->department_name;
            $sub_array[] = number_format($row->salary,2);
            $sub_array[] = $row->contract;
           	foreach($row as $k1=>$v1)
				$buttons_data .= ' data-'.$k1.'="'.$v1.'" ';
				
            $buttons .= ' <a class="viewServiceRecord" data-employee_id="'.$emp_id.'" style="text-decoration: none;" '
            		  . ' href="'. base_url().$this->uri->segment(1).'/viewServiceRecord" > '
            		  . ' <button class="btn btn-info btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="View Service Record">'
            		  . ' <i class="material-icons">remove_red_eye</i> '
            		  . ' </button> '
            		  . ' </a> ';
	        $sub_array[] = $buttons;
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"                  =>     intval($_POST["draw"]),  
            "recordsTotal"          =>     $this->OvertimeServiceRenderedCollection->get_all_data(),  
            "recordsFiltered"     	=>     $this->OvertimeServiceRenderedCollection->get_filtered_data($division_id),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
	}
	
	public function viewServiceRecord(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewServiceRecord';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$emp_id = isset($_POST['employee_id'])?$_POST['employee_id']:"";
			$ret = $this->OvertimeServiceRenderedCollection->get_service_record($emp_id);
			$formData['key'] = $result['key'];
			if(sizeof($ret['employee']) > 0){
				$formData['employee']['employee_number'] = $this->Helper->decrypt($ret['employee']['employee_number'],$emp_id);
				$formData['employee']['first_name'] = $this->Helper->decrypt($ret['employee']['first_name'],$emp_id);
				$formData['employee']['last_name'] = $this->Helper->decrypt($ret['employee']['last_name'],$emp_id);
				$formData['employee']['middle_name'] = $this->Helper->decrypt($ret['employee']['middle_name'],$emp_id);
				$formData['employee']['extension'] = $this->Helper->decrypt($ret['employee']['extension'],$emp_id);
				$formData['employee']['birthday'] = $this->Helper->decrypt($ret['employee']['birthday'],$emp_id);
				$formData['employee']['birth_place'] = $this->Helper->decrypt($ret['employee']['birth_place'],$emp_id);
				$formData['employee']['employee_id_number'] = $this->Helper->decrypt($ret['employee']['employee_id_number'],$emp_id);
			}
			$formData["Experience"] = $ret["experience"];
			$data["Data"] = $formData;
			$result['form'] = $this->load->view('helpers/statementform.php', $data, TRUE);
			$result['data'] = $formData['employee'];
		}
		echo json_encode($result);
	}
}

?>