<?php

class OtherEarningEntries extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('OtherEarningEntriesCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::OTHER_EARNING_ENTRY_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewOtherEarningEntries";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/otherearningentrieslist",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Other Earning Entries');
			Helper::setMenu('templates/menu_template');
			Helper::setView('otherearningentries',$viewData,FALSE);
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
        $fetch_data = $this->OtherEarningEntriesCollection->make_datatables();  
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
            /*'<a  id="updateOtherEarningEntriesForm" 
                class="updateOtherEarningEntriesForm btn btn-info btn-circle waves-effect waves-circle waves-float"
                href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/updateOtherEarningEntriesForm'"  data-toggle="tooltip" data-placement="top" title="Update" 
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
            $buttons .= ' <a id="viewOtherEarningEntriesForm" ' 
            		  . ' class="viewOtherEarningEntriesForm" style="text-decoration: none;" '
            		  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/viewOtherEarningEntriesForm" '
            		  . $buttons_data
            		  . ' > '
            		  . ' <button class="btn btn-primary btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="View Details">'
            		  . ' <i class="material-icons">remove_red_eye</i> '
            		  . ' </button> '
            		  . ' </a> ';
            if(Helper::role(ModuleRels::OTHER_EARNING_UPDATE_DETAILS)): 
            $buttons .= ' <a id="updateOtherEarningEntriesForm" ' 
            		  . ' class="updateOtherEarningEntriesForm" style="text-decoration: none;" '
            		  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/updateOtherEarningEntriesForm" '
            		  . $buttons_data
            		  . ' > '
            		  . ' <button class="btn btn-info btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Update">'
            		  . ' <i class="material-icons">mode_edit</i> '
            		  . ' </button> '
            		  . ' </a> ';
            endif;
            // if(Helper::role(ModuleRels::OTHER_EARNING_HISTORY_DETAILS)): 
            $buttons .= ' <a id="viewOtherEarningEntriesUpdates" ' 
            		  . ' class="viewOtherEarningEntriesUpdates" style="text-decoration: none;" '
            		  . ' href="'. base_url().$this->uri->segment(1).'/OtherEarningEntriesUpdates/" '
            		  . $buttons_data
            		  . ' > '
            		  . ' <button class="btn btn-grey btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="History">'
            		  . ' <i class="material-icons">history</i> '
            		  . ' </button> '
            		  . ' </a> ';
            // endif;
            if(Helper::role(ModuleRels::OTHER_EARNING_ACTIVATION)): 
	            if($row->is_active == "1"){
		            $buttons .= ' <a ' 
		            		  . ' class="deactivateOtherEarningEntries btn btn-danger btn-circle waves-effect waves-circle waves-float" '
		            		  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/deactivateOtherEarningEntries" '
		            		  . ' data-toggle="tooltip" data-placement="top" title="Deactivate" '
		            		  . ' data-id='.$row->id.' '
		            		  . ' > '
		            		  . ' <i class="material-icons">do_not_disturb</i> '
		            		  . ' </a> ';	
		        }
		        else{
		        	$buttons .= ' <a ' 
		        			  . ' class="activateOtherEarningEntries btn btn-success btn-circle waves-effect waves-circle waves-float" '
		        			  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/activateOtherEarningEntries" '
		        			  . ' data-toggle="tooltip" data-placement="top" title="Activate" '
		        			  . ' data-id='.$row->id.' '
		        			  . ' > '
		        			  . ' <i class="material-icons">done</i> '
		        			  . ' </a> ';
		        } 
		    endif;
	        $sub_array[] = $buttons;
			$sub_array[] = $row->last_name.', '.$row->first_name.' '.$row->middle_name.' '.$row->extension;
            $sub_array[] = $row->earning_description;
            $sub_array[] = $row->amount;
            $sub_array[] = '<b><span class="'.$status_color.'">'.$status.'</span><b>';
            $sub_array[] = $row->username;
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"                  =>     intval($_POST["draw"]),  
            "recordsTotal"          =>      $this->OtherEarningEntriesCollection->get_all_data(),  
            "recordsFiltered"     	=>     $this->OtherEarningEntriesCollection->get_filtered_data(),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
	}
	
	public function getAvailableMonetized(){
		$result = array();
		$result['key'] = 'addOtherEarningEntries';
		if (!$this->input->is_ajax_request()) show_404();
		else{
			$ret =  new OtherEarningEntriesCollection();
			$ot = $ret->availableMonetized($_POST['id'],$_POST['period']);
		}
		echo json_encode($result);
	}

	public function addOtherEarningEntriesForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'addOtherEarningEntries';
		if (!$this->input->is_ajax_request()) show_404();
		else{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/otherearningentriesform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function updateOtherEarningEntriesForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'updateOtherEarningEntries';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/otherearningentriesform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function viewOtherEarningEntriesForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewOtherEarningEntries';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/otherearningentriesform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function addOtherEarningEntries(){
		//var_dump($_POST);die();
		$result = array();
		$page = 'addOtherEarningEntries';
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
				$ret =  new OtherEarningEntriesCollection();
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
	public function updateOtherEarningEntries(){

		//var_dump($_POST);die();
		$result = array();
		$page = 'updateOtherEarningEntries';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new OtherEarningEntriesCollection();
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
	
	public function activateOtherEarningEntries(){
		$result = array();
		$page = 'activateOtherEarningEntries';
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
				$ret =  new OtherEarningEntriesCollection();
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
	public function deactivateOtherEarningEntries(){
		$result = array();
		$page = 'deactivateOtherEarningEntries';
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
				$ret =  new OtherEarningEntriesCollection();
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
	public function getActiveOtherEarningEntries(){
		$result = array();
		$page = 'getActiveOtherEarningEntries';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new OtherEarningEntriesCollection();
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
	public function getOtherEarningEntriesById(){
		$result = array();
		$page = 'getOtherEarningEntriesById';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			if(isset($_GET['Id']) && $_GET['Id'] != null)
			{
				$ret =  new OtherEarningEntriesCollection();
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