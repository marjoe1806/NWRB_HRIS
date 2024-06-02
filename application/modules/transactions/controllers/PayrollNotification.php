<?php

class PayrollNotification extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('PayrollNotificationCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::PAYROLL_NOTIFICATION_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewPayrollNotification";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/payrollnotificationlist",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Payroll Notifications');
			Helper::setMenu('templates/menu_template');
			Helper::setView('payrollnotification',$viewData,FALSE);
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
        $fetch_data = $this->PayrollNotificationCollection->make_datatables();  
        $data = array();  
        
        foreach($fetch_data as $k => $row)  
        {  
        	$buttons = "";
        	$buttons_data = "";
            $sub_array = array();
            $sub_array[] = $row->pay_basis;
            $sub_array[] = $row->leave_grouping_name;
            $sub_array[] = date('F Y',strtotime($row->payroll_period));
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
            if(Helper::role(ModuleRels::PAYROLL_NOTIFICATION_VIEW_DETAILS)): 
            $buttons .= ' <a id="viewPayrollNotificationForm" ' 
            		  . ' class="viewPayrollNotificationForm" style="text-decoration: none;" '
            		  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/viewPayrollNotificationForm" '
            		  . $buttons_data
            		  . ' > '
            		  . ' <button class="btn btn-primary btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="View Details">'
            		  . ' <i class="material-icons">remove_red_eye</i> '
            		  . ' </button> '
            		  . ' </a> ';
            endif;
            if(Helper::role(ModuleRels::PAYROLL_NOTIFICATION_UPDATE_DETAILS)): 
            $buttons .= ' <a id="updatePayrollNotificationForm" ' 
            		  . ' class="updatePayrollNotificationForm" style="text-decoration: none;" '
            		  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/updatePayrollNotificationForm" '
            		  . $buttons_data
            		  . ' > '
            		  . ' <button class="btn btn-info btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Update">'
            		  . ' <i class="material-icons">mode_edit</i> '
            		  . ' </button> '
            		  . ' </a> ';
            endif;
            /*if(Helper::role(ModuleRels::PAYROLL_NOTIFICATION_HISTORY_DETAILS)): 
            $buttons .= ' <a id="viewPayrollNotificationUpdates" ' 
            		  . ' class="viewPayrollNotificationUpdates" style="text-decoration: none;" '
            		  . ' href="'. base_url().$this->uri->segment(1).'/PayrollNotificationUpdates/" '
            		  . $buttons_data
            		  . ' > '
            		  . ' <button class="btn btn-grey btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="History">'
            		  . ' <i class="material-icons">history</i> '
            		  . ' </button> '
            		  . ' </a> ';
            endif;*/
            /*if($row->is_active == "1"){
	            $buttons .= ' <a ' 
	            		  . ' class="deactivatePayrollNotification btn btn-danger btn-circle waves-effect waves-circle waves-float" '
	            		  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/deactivatePayrollNotification" '
	            		  . ' data-toggle="tooltip" data-placement="top" title="Deactivate" '
	            		  . ' data-id='.$row->id.' '
	            		  . ' > '
	            		  . ' <i class="material-icons">do_not_disturb</i> '
	            		  . ' </a> ';	
	        }
	        else{
	        	$buttons .= ' <a ' 
	        			  . ' class="activatePayrollNotification btn btn-success btn-circle waves-effect waves-circle waves-float" '
	        			  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/activatePayrollNotification" '
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
            "recordsTotal"          =>      $this->PayrollNotificationCollection->get_all_data(),  
            "recordsFiltered"     	=>     $this->PayrollNotificationCollection->get_filtered_data(),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
    }
    function fetchRowsUpdates(){ 
        $fetch_data = $this->PayrollNotificationCollection->make_datatables_updates();  
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
            "recordsTotal"          =>      $this->PayrollNotificationCollection->get_all_data_updates(),  
            "recordsFiltered"     	=>     $this->PayrollNotificationCollection->get_filtered_data_updates(),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
    }
	public function addPayrollNotificationForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'addPayrollNotification';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/payrollnotificationform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function updatePayrollNotificationForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'updatePayrollNotification';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/payrollnotificationform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function viewPayrollNotificationForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewPayrollNotification';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/payrollnotificationform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function addPayrollNotification(){
		$result = array();
		$page = 'addPayrollNotification';
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
				
				$ret =  new PayrollNotificationCollection();
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
	public function updatePayrollNotification(){
		$result = array();
		$page = 'updatePayrollNotification';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				// var_dump($post_data);die();
				// $post_data['salary'] = str_replace(",", "", $post_data['salary']);
				// $post_data['day_rate'] = str_replace(",", "", $post_data['day_rate']);
				// $post_data['hr_rate'] = str_replace(",", "", $post_data['hr_rate']);
				// $post_data['min_rate'] = str_replace(",", "", $post_data['min_rate']);
				// $post_data['period_earned'] = str_replace(",", "", $post_data['period_earned']);
				// $post_data['net_pay'] = str_replace(",", "", $post_data['net_pay']);
				$ret =  new PayrollNotificationCollection();
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
	
	public function activatePayrollNotification(){
		$result = array();
		$page = 'activatePayrollNotification';
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
				$ret =  new PayrollNotificationCollection();
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
	public function deactivatePayrollNotification(){
		$result = array();
		$page = 'deactivatePayrollNotification';
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
				$ret =  new PayrollNotificationCollection();
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
	public function getActivePayrollNotification(){
		$result = array();
		$page = 'getActivePayrollNotification';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new PayrollNotificationCollection();
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
	public function getPayrollNotificationById(){
		$result = array();
		$page = 'getPayrollNotificationById';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			if(isset($_GET['Id']) && $_GET['Id'] != null)
			{
				$ret =  new PayrollNotificationCollection();
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
	public function fetchEmployees(){
		$result = array();
		$page = 'fetchEmployees';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new PayrollNotificationCollection();
			if($ret->hasRowsEmployees()) {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
			} else {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			}
			$result = json_decode($res,true);
			$result['key'] = $page;
		}
		echo json_encode($result);
	}
}

?>