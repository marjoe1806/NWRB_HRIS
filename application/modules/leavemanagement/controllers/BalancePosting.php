<?php

class BalancePosting extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('BalancePostingCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::LEAVE_LEDGER_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewBalancePosting";
		$listData['key'] = $page;
		$ret = new BalancePostingCollection();
		$viewData['table'] = $this->load->view("helpers/balancepostinglist",$listData,TRUE);
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Balance Posting');
			Helper::setMenu('templates/menu_template');
			Helper::setView('balanceposting',$viewData,FALSE);
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
        $fetch_data = $this->BalancePostingCollection->make_datatables();  
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

            /*'<a  id="updateBalancePostingForm" 
                class="updateBalancePostingForm btn btn-info btn-circle waves-effect waves-circle waves-float"
                href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/updateBalancePostingForm'"  data-toggle="tooltip" data-placement="top" title="Update" 
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
            if(Helper::role(ModuleRels::BALANCE_UPDATE_DETAILS)): 
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
            if(Helper::role(ModuleRels::BALANCE_ACTIVATION)): 
	            if($row->is_active == "1"){
		            $buttons .= ' <a ' 
		            		  . ' class="deactivateBalancePosting btn btn-danger btn-circle waves-effect waves-circle waves-float" '
		            		  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/deactivateBalancePosting" '
		            		  . ' data-toggle="tooltip" data-placement="top" title="Deactivate" '
		            		  . ' data-id='.$row->balance_id.' '
		            		  . ' > '
		            		  . ' <i class="material-icons">do_not_disturb</i> '
		            		  . ' </a> ';	
		        }
		        else{
		        	$buttons .= ' <a ' 
		        			  . ' class="activateBalancePosting btn btn-success btn-circle waves-effect waves-circle waves-float" '
		        			  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/activateBalancePosting" '
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
            "recordsTotal"          =>      $this->BalancePostingCollection->get_all_data(),  
            "recordsFiltered"     	=>     $this->BalancePostingCollection->get_filtered_data(),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
    }
	public function employeeBalancePosting(){
		$formData = array();
		$result = array();
		$result['key'] = 'employeeBalancePosting';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new BalancePostingCollection();
			// var_dump($_POST);die();
			if($ret->fetchBalancePosting($_POST['employee_id'],$_POST['year'])) {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
				$res = json_decode($res,true);
				// var_dump($res);die();

			} else {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				$res = json_decode($res,true);
			}
			$helperData['ledger'] = $res;
			$helperData['key'] = $result['key'];
			$result['table'] = $this->load->view('helpers/employeebalanceposting.php', $helperData, TRUE);
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
			$ret =  new BalancePostingCollection();
			if($ret->fetchMonths()) {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());

			} else {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			}
			$formData['months'] = json_decode($res,true);
			
			$result['form'] = $this->load->view('forms/balancepostingform.php', $formData, TRUE);
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
			$ret =  new BalancePostingCollection();
			if($ret->fetchMonths()) {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());

			} else {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			}
			$formData['months'] = json_decode($res,true);
			
			$result['form'] = $this->load->view('forms/balancepostingform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function activateBalancePosting(){
		$result = array();
		$page = 'activateBalancePosting';
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
				$ret =  new BalancePostingCollection();
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
	public function deactivateBalancePosting(){
		$result = array();
		$page = 'deactivateBalancePosting';
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
				$ret =  new BalancePostingCollection();
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
				$ret =  new BalancePostingCollection();
				if($ret->addBalancePosting($post_data)) {
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
				$ret =  new BalancePostingCollection();
				if($ret->updateBalancePosting($post_data)) {
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
				$ret =  new BalancePostingCollection();
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
				$ret =  new BalancePostingCollection();
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