<?php

class ExhaustedPosting extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('ExhaustedPostingCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::LEAVE_LEDGER_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewExhaustedPosting";
		$listData['key'] = $page;
		$ret = new ExhaustedPostingCollection();
		$viewData['table'] = $this->load->view("helpers/exhaustedpostinglist",$listData,TRUE);
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Exhausted Posting');
			Helper::setMenu('templates/menu_template');
			Helper::setView('exhaustedposting',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		}
		else{
			$result['key'] = $listData['key'];
			$result['table'] = $viewData['table'];
			echo json_encode($result);
		}
		Session::checksession();
	}
	function monthList($code) {
		$months = array(
    		'01'=>'January',
    		'02'=>'February',
    		'03'=>'March',
    		'04'=>'April',
    		'05'=>'May',
    		'06'=>'June',
    		'07'=>'July',
    		'08'=>'August',
    		'09'=>'September',
    		'10'=>'October',
    		'11'=>'November',
    		'12'=>'December'
    	);
    	return $months[$code];
	}

	function fetchRows(){ 
        $fetch_data = $this->ExhaustedPostingCollection->make_datatables();  
        $data = array();  
        
        foreach($fetch_data as $k => $row)  
        {  
        	$buttons = "";
        	$buttons_data = "";
        	$row->first_name = $this->Helper->decrypt($row->first_name,$row->employee_id);
        	$row->middle_name = $this->Helper->decrypt($row->middle_name,$row->employee_id);
        	$row->last_name = $this->Helper->decrypt($row->last_name,$row->employee_id);
            $sub_array = array();  
            $sub_array[] = $row->last_name.", ".$row->first_name." ".$row->middle_name; 
            $sub_array[] = $row->leave_year;
            $sub_array[] = $this->monthList($row->leave_month);
            $sub_array[] = $row->vl_balance_amt; 
            $sub_array[] = $row->sl_balance_amt;  
            $sub_array[] = $row->date_created;
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

            /*'<a  id="updateExhaustedPostingForm" 
                class="updateExhaustedPostingForm btn btn-info btn-circle waves-effect waves-circle waves-float"
                href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/updateExhaustedPostingForm'"  data-toggle="tooltip" data-placement="top" title="Update" 
                data-id="'. $value->id.'"
                foreach ($value as $k => $v) {
                    ' data-'.$k.'="'.$v.'" ';
                } "
            >'

                <i class="material-icons">mode_edit</i>
            </a>*/
            foreach($row as $k1=>$v1){
            	$buttons_data .= ' data-'.$k1.'="'.$v1.'" ';
            }
            if(Helper::role(ModuleRels::EXHAUSTED_UPDATE_DETAILS)): 
            $buttons .= ' <a id="updateEmployeeBalanceForm" ' 
            		  . ' class="updateEmployeeBalanceForm" style="text-decoration: none;" '
            		  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/updateEmployeeBalanceForm" '
            		  . $buttons_data
            		  . ' > '
            		  . ' <button class="btn btn-info btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Update">'
            		  . ' <i class="material-icons">mode_edit</i> '
            		  . ' </button> '
            		  . ' </a> ';
            endif;
            if(Helper::role(ModuleRels::EXHAUSTED_ACTIVATION)): 
	            if($row->is_active == "1"){
		            $buttons .= ' <a ' 
		            		  . ' class="deactivateExhaustedPosting btn btn-danger btn-circle waves-effect waves-circle waves-float" '
		            		  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/deactivateExhaustedPosting" '
		            		  . ' data-toggle="tooltip" data-placement="top" title="Deactivate" '
		            		  . ' data-id='.$row->balance_id.' '
		            		  . ' > '
		            		  . ' <i class="material-icons">do_not_disturb</i> '
		            		  . ' </a> ';	
		        }
		        else{
		        	$buttons .= ' <a ' 
		        			  . ' class="activateExhaustedPosting btn btn-success btn-circle waves-effect waves-circle waves-float" '
		        			  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/activateExhaustedPosting" '
		        			  . ' data-toggle="tooltip" data-placement="top" title="Activate" '
		        			  . ' data-id='.$row->balance_id.' '
		        			  . ' > '
		        			  . ' <i class="material-icons">done</i> '
		        			  . ' </a> ';
		        } 
		    endif;
	        $sub_array[] = $buttons;
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"                  =>     intval($_POST["draw"]),  
            "recordsTotal"          =>      $this->ExhaustedPostingCollection->get_all_data(),  
            "recordsFiltered"     	=>     $this->ExhaustedPostingCollection->get_filtered_data(),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
    }
	public function employeeExhaustedPosting(){
		$formData = array();
		$result = array();
		$result['key'] = 'employeeExhaustedPosting';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new ExhaustedPostingCollection();
			// var_dump($_POST);die();
			if($ret->fetchExhaustedPosting($_POST['employee_id'],$_POST['year'])) {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
				$res = json_decode($res,true);
				// var_dump($res);die();

			} else {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				$res = json_decode($res,true);
			}
			$helperData['ledger'] = $res;
			$helperData['key'] = $result['key'];
			$result['table'] = $this->load->view('helpers/employeeexhaustedposting.php', $helperData, TRUE);
		}
		echo json_encode($result);
	}
	public function addEmployeeBalanceForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'addEmployeeBalance';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{

			$formData['key'] = $result['key'];
			$ret =  new ExhaustedPostingCollection();
			if($ret->fetchMonths()) {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());

			} else {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			}
			$formData['months'] = json_decode($res,true);
			
			$result['form'] = $this->load->view('forms/exhaustedpostingform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function updateEmployeeBalanceForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'updateEmployeeBalance';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{

			$formData['key'] = $result['key'];
			$ret =  new ExhaustedPostingCollection();
			if($ret->fetchMonths()) {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());

			} else {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			}
			$formData['months'] = json_decode($res,true);
			
			$result['form'] = $this->load->view('forms/exhaustedpostingform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function activateExhaustedPosting(){
		$result = array();
		$page = 'activateExhaustedPosting';
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
				$ret =  new ExhaustedPostingCollection();
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
	public function deactivateExhaustedPosting(){
		$result = array();
		$page = 'deactivateExhaustedPosting';
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
				$ret =  new ExhaustedPostingCollection();
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
	public function addEmployeeBalance(){
		//var_dump($_POST);die();
		$result = array();
		$page = 'addEmployeeBalance';
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
				$ret =  new ExhaustedPostingCollection();
				if($ret->addExhaustedPosting($post_data)) {
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
	public function updateEmployeeBalance(){
		//var_dump($_POST);die();
		$result = array();
		$page = 'updateEmployeeBalance';
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
				$ret =  new ExhaustedPostingCollection();
				if($ret->updateExhaustedPosting($post_data)) {
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
	public function getLeaveDates(){
		//var_dump($_POST);die();
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
				$ret =  new ExhaustedPostingCollection();
				if($ret->fetchLeaveDates($post_data['id'])) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
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
	public function getBalanceBrought(){
		//var_dump($_POST);die();
		$result = array();
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			//var_dump($this->input->post());die();
			if($this->input->post() && $this->input->post() != null)
			{
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new ExhaustedPostingCollection();
				$year =  (int)$post_data['year'] - 1;
				if($ret->fetchBalanceBrought($year,$post_data['employee_id'])) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
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