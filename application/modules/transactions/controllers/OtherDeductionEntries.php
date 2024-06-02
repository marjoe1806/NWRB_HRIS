<?php

class OtherDeductionEntries extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('OtherDeductionEntriesCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::OTHER_DEDUCTION_ENTRY_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewOtherDeductionEntries";
		$listData['key'] = $page;
		
		$viewData['table'] = $this->load->view("helpers/otherdeductionentrieslist",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Other Deduction Entries');
			Helper::setMenu('templates/menu_template');
			Helper::setView('otherdeductionentries',$viewData,FALSE);
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
        $fetch_data = $this->OtherDeductionEntriesCollection->make_datatables();  
        $data = array();  
        
        foreach($fetch_data as $k => $row)  
        {  
        	$buttons = "";
        	$buttons_data = "";
        	$row->first_name = $this->Helper->decrypt($row->first_name,$row->employee_id);
        	$row->middle_name = $this->Helper->decrypt($row->middle_name,$row->employee_id);
        	$row->last_name = $this->Helper->decrypt($row->last_name,$row->employee_id);
        	$row->extension = $this->Helper->decrypt($row->extension,$row->employee_id);
        	
        	$fullname = $row->l_name.", ".$row->first_name;
            $sub_array = array();    
            if($row->middle_name != null)
            	$fullname .= " ".$row->middle_name[0].".";
            $status_color = "text-danger";
            if($row->is_active == "1"){
            	$status_color = "text-success";
            	$status = "ACTIVE";
            }
            else{
            	$status_color = "text-danger";
            	$status = "INACTIVE";
            }

            foreach($row as $k1=>$v1){
            	$buttons_data .= ' data-'.$k1.'="'.$v1.'" ';
            }
            $buttons .= ' <a id="viewOtherDeductionEntriesForm" ' 
            		  . ' class="viewOtherDeductionEntriesForm" style="text-decoration: none;" '
            		  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/viewOtherDeductionEntriesForm" '
            		  . $buttons_data
            		  . ' > '
            		  . ' <button class="btn btn-primary btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="View Details">'
            		  . ' <i class="material-icons">remove_red_eye</i> '
            		  . ' </button> '
            		  . ' </a> ';
            if(Helper::role(ModuleRels::OTHER_DEDUCTION_UPDATE_DETAILS)): 
            $buttons .= ' <a id="updateOtherDeductionEntriesForm" ' 
            		  . ' class="updateOtherDeductionEntriesForm" style="text-decoration: none;" '
            		  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/updateOtherDeductionEntriesForm" '
            		  . $buttons_data
            		  . ' > '
            		  . ' <button class="btn btn-info btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Update">'
            		  . ' <i class="material-icons">mode_edit</i> '
            		  . ' </button> '
            		  . ' </a> ';
            endif;
            //if(Helper::role(ModuleRels::OTHER_DEDUCTION_HISTORY_DETAILS)): 
            $buttons .= ' <a id="viewOtherDeductionEntriesUpdates" ' 
            		  . ' class="viewOtherDeductionEntriesUpdates" style="text-decoration: none;" '
            		  . ' href="'. base_url().$this->uri->segment(1).'/OtherDeductionEntriesUpdates/" '
            		  . $buttons_data
            		  . ' > '
            		  . ' <button class="btn btn-grey btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="History">'
            		  . ' <i class="material-icons">history</i> '
            		  . ' </button> '
            		  . ' </a> ';
            // endif;
            if(Helper::role(ModuleRels::OTHER_DEDUCTION_ACTIVATION)): 
	            if($row->is_active == "1"){
		            $buttons .= ' <a ' 
		            		  . ' class="deactivateOtherDeductionEntries btn btn-danger btn-circle waves-effect waves-circle waves-float" '
		            		  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/deactivateOtherDeductionEntries" '
		            		  . ' data-toggle="tooltip" data-placement="top" title="Deactivate" '
		            		  . ' data-id='.$row->id.' '
		            		  . ' > '
		            		  . ' <i class="material-icons">do_not_disturb</i> '
		            		  . ' </a> ';	
		        }
		        else{
		        	$buttons .= ' <a ' 
		        			  . ' class="activateactivateOtherDeductionEntriesOtherBenefitEntries btn btn-success btn-circle waves-effect waves-circle waves-float" '
		        			  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/activateOtherDeductionEntries" '
		        			  . ' data-toggle="tooltip" data-placement="top" title="Activate" '
		        			  . ' data-id='.$row->id.' '
		        			  . ' > '
		        			  . ' <i class="material-icons">done</i> '
		        			  . ' </a> ';
		        } 
		    endif;
	        $sub_array[] = $buttons;
			$sub_array[] = $row->last_name.', '.$row->first_name.' '.$row->middle_name.' '.$row->extension;
            $sub_array[] = $row->deduct_description;
            $sub_array[] = number_format($row->amount,2);
            $sub_array[] = '<b><span class="'.$status_color.'">'.$status.'</span><b>';
            $sub_array[] = $row->username;
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"                  =>     intval($_POST["draw"]),  
            "recordsTotal"          =>      $this->OtherDeductionEntriesCollection->get_all_data(),  
            "recordsFiltered"     	=>     $this->OtherDeductionEntriesCollection->get_filtered_data(),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
    }
	public function addOtherDeductionEntriesForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'addOtherDeductionEntries';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/otherdeductionentriesform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function updateOtherDeductionEntriesForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'updateOtherDeductionEntries';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/otherdeductionentriesform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function viewOtherDeductionEntriesForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewOtherDeductionEntries';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/otherdeductionentriesform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function addOtherDeductionEntries(){
		//var_dump($_POST);die();
		$result = array();
		$page = 'addOtherDeductionEntries';
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
				$ret =  new OtherDeductionEntriesCollection();
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
	public function updateOtherDeductionEntries(){

		//var_dump($_POST);die();
		$result = array();
		$page = 'updateOtherDeductionEntries';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new OtherDeductionEntriesCollection();
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
	
	public function activateOtherDeductionEntries(){
		$result = array();
		$page = 'activateOtherDeductionEntries';
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
				$ret =  new OtherDeductionEntriesCollection();
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
	public function deactivateOtherDeductionEntries(){
		$result = array();
		$page = 'deactivateOtherDeductionEntries';
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
				$ret =  new OtherDeductionEntriesCollection();
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
	public function getActiveOtherDeductionEntries(){
		$result = array();
		$page = 'getActiveOtherDeductionEntries';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new OtherDeductionEntriesCollection();
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
	public function getOtherDeductionEntriesById(){
		$result = array();
		$page = 'getOtherDeductionEntriesById';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			if(isset($_GET['Id']) && $_GET['Id'] != null)
			{
				$ret =  new OtherDeductionEntriesCollection();
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