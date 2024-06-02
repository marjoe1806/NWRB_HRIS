<?php

class OvertimeApplications extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('OvertimeApplicationsCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::OVERTIME_APPLICATIONS_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewOvertimeApplications";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/overtimeapplicationslist",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Overtime Applications');
			Helper::setMenu('templates/menu_template');
			Helper::setView('overtimeapplications',$viewData,FALSE);
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
        $fetch_data = $this->OvertimeApplicationsCollection->make_datatables();  
        $data = array();  
        
        foreach($fetch_data as $k => $row)  
        {  
        	$buttons = "";
        	$buttons_data = "";
        	$row->first_name = $this->Helper->decrypt($row->first_name,$row->employee_id);
        	$row->middle_name = $this->Helper->decrypt($row->middle_name,$row->employee_id);
        	$row->last_name = $this->Helper->decrypt($row->last_name,$row->employee_id);
            $sub_array = array();
            $fullname = $row->last_name.", ".$row->first_name;
            if($row->middle_name != null)
            	$fullname .= " ".$row->middle_name[0].".";
            $sub_array[] = $fullname;
            if($row->pay_basis == "Permanent" || $row->pay_basis == "Casual"){
	            $sub_array[] = $row->hrs_15;
	            $sub_array[] = $row->mins_15;
	            $sub_array[] = $row->hrs_125;
	            $sub_array[] = $row->mins_125;
	        }
	        else{
	        	$sub_array[] = $row->hrs_1;
	            $sub_array[] = $row->mins_1;
	            $sub_array[] = $row->hrs_1625;
	            $sub_array[] = $row->mins_1625;
	        }
            $sub_array[] = $row->ot_percent;
            $sub_array[] = $row->tax;
            $status_color = "text-danger";
            if($row->is_active == "1"){
            	$status_color = "text-success";
            	$status = "ACTIVE";
            }
            else{
            	$status_color = "text-danger";
            	$status = "INACTIVE";
            }
            $sub_array[] = '<b><span class="'.$status_color.'">'.$status.'</span><b>';
            $sub_array[] = $row->username;
           	//Is approved
            /*$status_color = "text-danger";
            if($row->is_approved == "1"){
            	$status_color = "text-success";
            	$is_approved = "APPROVED";
            }
            else{
            	$status_color = "text-danger";
            	$is_approved = "PENDING";
            }
            $sub_array[] = '<b><span class="'.$status_color.'">'.$is_approved.'</span><b>';*/
            foreach($row as $k1=>$v1){
            	$buttons_data .= ' data-'.$k1.'="'.$v1.'" ';
            }
            if(Helper::role(ModuleRels::OVERTIME_APPLICATIONS_VIEW_DETAILS)): 
            $buttons .= ' <a id="viewOvertimeApplicationsForm" ' 
            		  . ' class="viewOvertimeApplicationsForm" style="text-decoration: none;" '
            		  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/viewOvertimeApplicationsForm" '
            		  . $buttons_data
            		  . ' > '
            		  . ' <button class="btn btn-primary btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="View Details">'
            		  . ' <i class="material-icons">remove_red_eye</i> '
            		  . ' </button> '
            		  . ' </a> ';
            endif;
            if(Helper::role(ModuleRels::OVERTIME_APPLICATIONS_UPDATE_DETAILS)): 
            $buttons .= ' <a id="updateOvertimeApplicationsForm" ' 
            		  . ' class="updateOvertimeApplicationsForm" style="text-decoration: none;" '
            		  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/updateOvertimeApplicationsForm" '
            		  . $buttons_data
            		  . ' > '
            		  . ' <button class="btn btn-info btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Update">'
            		  . ' <i class="material-icons">mode_edit</i> '
            		  . ' </button> '
            		  . ' </a> ';
            endif;
            if(Helper::role(ModuleRels::OVERTIME_APPLICATIONS_HISTORY_DETAILS)): 
            $buttons .= ' <a id="viewOvertimeApplicationsUpdates" ' 
            		  . ' class="viewOvertimeApplicationsUpdates" style="text-decoration: none;" '
            		  . ' href="'. base_url().$this->uri->segment(1).'/OvertimeApplicationsUpdates/" '
            		  . $buttons_data
            		  . ' > '
            		  . ' <button class="btn btn-grey btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="History">'
            		  . ' <i class="material-icons">history</i> '
            		  . ' </button> '
            		  . ' </a> ';
            endif;
            /*if($row->is_active == "1"){
	            $buttons .= ' <a ' 
	            		  . ' class="deactivateOvertimeApplications btn btn-danger btn-circle waves-effect waves-circle waves-float" '
	            		  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/deactivateOvertimeApplications" '
	            		  . ' data-toggle="tooltip" data-placement="top" title="Deactivate" '
	            		  . ' data-id='.$row->id.' '
	            		  . ' > '
	            		  . ' <i class="material-icons">do_not_disturb</i> '
	            		  . ' </a> ';	
	        }
	        else{
	        	$buttons .= ' <a ' 
	        			  . ' class="activateOvertimeApplications btn btn-success btn-circle waves-effect waves-circle waves-float" '
	        			  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/activateOvertimeApplications" '
	        			  . ' data-toggle="tooltip" data-placement="top" title="Activate" '
	        			  . ' data-id='.$row->id.' '
	        			  . ' > '
	        			  . ' <i class="material-icons">done</i> '
	        			  . ' </a> ';
	        } */
	        $sub_array[] = $buttons;
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"                  =>     intval($_POST["draw"]),  
            "recordsTotal"          =>      $this->OvertimeApplicationsCollection->get_all_data(),  
            "recordsFiltered"     	=>     $this->OvertimeApplicationsCollection->get_filtered_data(),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
    }
    function fetchRowsUpdates(){ 
        $fetch_data = $this->OvertimeApplicationsCollection->make_datatables_updates();  
        $data = array();  
        
        foreach($fetch_data as $k => $row)  
        {  
        	$buttons = "";
        	$buttons_data = "";
        	$row->first_name = $this->Helper->decrypt($row->first_name,$row->employee_id);
        	$row->middle_name = $this->Helper->decrypt($row->middle_name,$row->employee_id);
        	$row->last_name = $this->Helper->decrypt($row->last_name,$row->employee_id);
            $sub_array = array();
            $fullname = $row->last_name.", ".$row->first_name;
            if($row->middle_name != null)
            	$fullname .= " ".$row->middle_name[0].".";
            $sub_array[] = $fullname;
            $sub_array[] = $row->hrs_15;
            $sub_array[] = $row->mins_15;
            $sub_array[] = $row->hrs_125;
            $sub_array[] = $row->mins_125;
            $sub_array[] = $row->ot_percent;
            $sub_array[] = $row->tax;
            $status_color = "text-danger";
            if($row->is_active == "1"){
            	$status_color = "text-success";
            	$status = "ACTIVE";
            }
            else{
            	$status_color = "text-danger";
            	$status = "INACTIVE";
            }
            $sub_array[] = '<b><span class="'.$status_color.'">'.$status.'</span><b>';
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"                  =>     intval($_POST["draw"]),  
            "recordsTotal"          =>      $this->OvertimeApplicationsCollection->get_all_data_updates(),  
            "recordsFiltered"     	=>     $this->OvertimeApplicationsCollection->get_filtered_data_updates(),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
    }
	public function addOvertimeApplicationsForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'addOvertimeApplications';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/overtimeapplicationsform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function updateOvertimeApplicationsForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'updateOvertimeApplications';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/overtimeapplicationsform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function viewOvertimeApplicationsForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewOvertimeApplications';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/overtimeapplicationsform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function addOvertimeApplications(){
		//var_dump($_POST);die();
		$result = array();
		$page = 'addOvertimeApplications';
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
				$post_data['salary'] = str_replace(",", "", $post_data['salary']);
				$post_data['day_rate'] = str_replace(",", "", $post_data['day_rate']);
				$post_data['hr_rate'] = str_replace(",", "", $post_data['hr_rate']);
				$post_data['min_rate'] = str_replace(",", "", $post_data['min_rate']);
				$post_data['period_earned'] = str_replace(",", "", $post_data['period_earned']);
				$post_data['net_pay'] = str_replace(",", "", $post_data['net_pay']);
				$ret =  new OvertimeApplicationsCollection();
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
	public function updateOvertimeApplications(){

		//var_dump($_POST);die();
		$result = array();
		$page = 'updateOvertimeApplications';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				// var_dump($post_data);die();
				$post_data['salary'] = str_replace(",", "", $post_data['salary']);
				$post_data['day_rate'] = str_replace(",", "", $post_data['day_rate']);
				$post_data['hr_rate'] = str_replace(",", "", $post_data['hr_rate']);
				$post_data['min_rate'] = str_replace(",", "", $post_data['min_rate']);
				$post_data['period_earned'] = str_replace(",", "", $post_data['period_earned']);
				$post_data['net_pay'] = str_replace(",", "", $post_data['net_pay']);
				$ret =  new OvertimeApplicationsCollection();
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
	
	public function activateOvertimeApplications(){
		$result = array();
		$page = 'activateOvertimeApplications';
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
				$ret =  new OvertimeApplicationsCollection();
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
	public function deactivateOvertimeApplications(){
		$result = array();
		$page = 'deactivateOvertimeApplications';
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
				$ret =  new OvertimeApplicationsCollection();
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
	public function getActiveOvertimeApplications(){
		$result = array();
		$page = 'getActiveOvertimeApplications';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new OvertimeApplicationsCollection();
			if($ret->hasRows()) {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
				//var_dump($res);die();
				
			} else {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			}
			$result = json_decode($res,true);
			if(isset($result['Data'])){
				foreach ($result['Data']['details'] as $k1 => $v1) {
					$result['Data']['details'][$k1]['employee_number'] = $this->Helper->decrypt($v1['employee_number'],$v1['id']);
					$result['Data']['details'][$k1]['first_name'] = $this->Helper->decrypt($v1['first_name'],$v1['id']);
    				$result['Data']['details'][$k1]['middle_name'] = $this->Helper->decrypt($v1['middle_name'],$v1['id']);
    				$result['Data']['details'][$k1]['last_name'] = $this->Helper->decrypt($v1['last_name'],$v1['id']);
				}
			}
			$result['key'] = $page;
		}
		echo json_encode($result);
	}
	public function getOvertimeApplicationsById(){
		$result = array();
		$page = 'getOvertimeApplicationsById';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			if(isset($_GET['Id']) && $_GET['Id'] != null)
			{
				$ret =  new OvertimeApplicationsCollection();
				if($ret->hasRows()) {
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
}

?>