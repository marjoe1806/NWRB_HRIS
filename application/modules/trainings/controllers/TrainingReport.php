<?php

class TrainingReport extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('TrainingReportCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::LEAVE_CREDITS_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewTrainingReport";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/trainingreportlist",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Training Report');
			Helper::setMenu('templates/menu_template');
			Helper::setView('trainingreport',$viewData,FALSE);
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
		$fetch_data = $this->TrainingReportCollection->make_datatables($division_id);
        $data = array();  
        foreach($fetch_data as $k => $row) {  
        	$buttons = "";
        	$buttons_data = "";
			$emp_id_num = "";
			$fullname = "";
			$emp_id = $row->salt;
        	$emp_id_num = $this->Helper->decrypt((string)$row->employee_id_number,$emp_id);
        	$row->first_name = $this->Helper->decrypt((string)$row->first_name,$emp_id);
        	$row->middle_name = $this->Helper->decrypt((string)$row->middle_name,$emp_id);
        	$row->last_name = $this->Helper->decrypt((string)$row->last_name,$emp_id);
        	$row->extension = ($row->extension == null || $row->extension == "")?"":$this->Helper->decrypt((string)$row->extension,$emp_id);
            $sub_array = array();    
           	foreach($row as $k1=>$v1)
				$buttons_data .= ' data-'.$k1.'="'.$v1.'" ';
				
            $buttons .= ' <a class="viewTrainingRecord" data-employee_id="'.$emp_id.'" style="text-decoration: none;" '
            		  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/viewTrainingReport" > '
            		  . ' <button class="btn btn-info btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="right" title="View Trainings">'
            		  . ' <i class="material-icons">remove_red_eye</i> '
            		  . ' </button> '
            		  . ' </a> ';
	        $sub_array[] = $buttons;
            $sub_array[] = $emp_id_num;
            $sub_array[] = $row->last_name. ', '. $row->first_name .' '.$row->middle_name .' '.$row->extension;
            $sub_array[] = $row->position_name;
            $sub_array[] = $row->department_name;
            $sub_array[] = number_format($row->salary,2);
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"                  =>     intval($_POST["draw"]),  
            "recordsTotal"          =>     $this->TrainingReportCollection->get_all_data(),  
            "recordsFiltered"     	=>     $this->TrainingReportCollection->get_filtered_data($division_id),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
	}
    
	public function formContainer(){
		$formData = array();
		$result = array();
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
            $result['key'] = $_POST["key"];
            $formData['key'] = $result['key'];
            $data["data"] = $formData;
			$result['form'] = $this->load->view('forms/trainingreportform.php', $data, TRUE);
		}
		echo json_encode($result);
	}

	public function viewTrainingReport(){
		$formData = array();
		$result = array();
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
            $result['key'] = $_POST["key"];
			$employee_id = isset($_POST['employee_id'])?$_POST['employee_id']:"";
			$year = isset($_POST['year'])?$_POST['year']:"";
			$ret = $this->TrainingReportCollection->get_service_record($employee_id, $year);
			$formData['key'] = $result['key'];
			if(count($ret['employee']) > 0){
				$formData['employee']['employee_number'] = $this->Helper->decrypt($ret['employee']['employee_number'],$employee_id);
				$formData['employee']['first_name'] = $this->Helper->decrypt($ret['employee']['first_name'],$employee_id);
				$formData['employee']['last_name'] = $this->Helper->decrypt($ret['employee']['last_name'],$employee_id);
				$formData['employee']['middle_name'] = $this->Helper->decrypt($ret['employee']['middle_name'],$employee_id);
				$formData['employee']['extension'] = ($ret['employee']['extension'] == null || $ret['employee']['extension'] == "")?"":$this->Helper->decrypt($ret['employee']['extension'],$employee_id);
				$formData['employee']['birthday'] = $ret['employee']['birthday'];
				$formData['employee']['birth_place'] = $ret['employee']['birth_place'];
				$formData['employee']['position_name'] = $ret['employee']['position_name'];
				$formData['employee']['branch'] = $ret['employee']['dpt_code'].' - '.$ret['employee']['dpt_name'];
				$formData['employee']['employee_id_number'] = $this->Helper->decrypt($ret['employee']['employee_id_number'],$employee_id);
				$formData["trainings"] = $ret["trainings"];
			}
			$data["queue"] = (isset($_POST["queue"]))?$_POST["queue"]:0;
			$data["data"] = $formData;
			$result['form'] = $this->load->view('forms/trainingreportform.php', $data, TRUE);
			$result['data'] = $formData['employee'];
		}
		echo json_encode($result);
	}
}

?>