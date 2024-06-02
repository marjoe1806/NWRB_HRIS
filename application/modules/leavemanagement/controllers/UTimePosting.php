<?php

class UTimePosting extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('UTimePostingCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::LEAVE_LEDGER_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewUTimePosting";
		$listData['key'] = $page;
		$ret = new UTimePostingCollection();
		$viewData['table'] = $this->load->view("helpers/utimepostinglist",$listData,TRUE);
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Under Time Posting');
			Helper::setMenu('templates/menu_template');
			Helper::setView('utimeposting',$viewData,FALSE);
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
        $fetch_data = $this->UTimePostingCollection->make_datatables();  
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
            $sub_array[] = $row->month_name;
            $sub_array[] = $row->utime_amt;  
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

            /*'<a  id="updateUTimePostingForm" 
                class="updateUTimePostingForm btn btn-info btn-circle waves-effect waves-circle waves-float"
                href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/updateUTimePostingForm'"  data-toggle="tooltip" data-placement="top" title="Update" 
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
            if(Helper::role(ModuleRels::UTIME_UPDATE_DETAILS)): 
            $buttons .= ' <a id="updateEmployeeUTimeForm" ' 
            		  . ' class="updateEmployeeUTimeForm" style="text-decoration: none;" '
            		  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/updateEmployeeUTimeForm" '
            		  . $buttons_data
            		  . ' > '
            		  . ' <button class="btn btn-info btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Update">'
            		  . ' <i class="material-icons">mode_edit</i> '
            		  . ' </button> '
            		  . ' </a> ';
            endif;
            if(Helper::role(ModuleRels::UTIME_ACTIVATION)): 
            if($row->is_active == "1"){
	            $buttons .= ' <a ' 
	            		  . ' class="deactivateUTimePosting btn btn-danger btn-circle waves-effect waves-circle waves-float" '
	            		  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/deactivateUTimePosting" '
	            		  . ' data-toggle="tooltip" data-placement="top" title="Deactivate" '
	            		  . ' data-id='.$row->utime_id.' '
	            		  . ' > '
	            		  . ' <i class="material-icons">do_not_disturb</i> '
	            		  . ' </a> ';	
	        }
	        else{
	        	$buttons .= ' <a ' 
	        			  . ' class="activateUTimePosting btn btn-success btn-circle waves-effect waves-circle waves-float" '
	        			  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/activateUTimePosting" '
	        			  . ' data-toggle="tooltip" data-placement="top" title="Activate" '
	        			  . ' data-id='.$row->utime_id.' '
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
            "recordsTotal"          =>      $this->UTimePostingCollection->get_all_data(),  
            "recordsFiltered"     	=>     $this->UTimePostingCollection->get_filtered_data(),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
    }
	public function employeeUTimePosting(){
		$formData = array();
		$result = array();
		$result['key'] = 'employeeUTimePosting';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new UTimePostingCollection();
			// var_dump($_POST);die();
			if($ret->fetchUTimePosting($_POST['employee_id'],$_POST['year'])) {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
				$res = json_decode($res,true);
				// var_dump($res);die();

			} else {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				$res = json_decode($res,true);
			}
			$helperData['ledger'] = $res;
			$helperData['key'] = $result['key'];
			$result['table'] = $this->load->view('helpers/employeeutimeposting.php', $helperData, TRUE);
		}
		echo json_encode($result);
	}
	public function addEmployeeUTimeForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'addEmployeeUTime';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{

			$formData['key'] = $result['key'];
			$ret =  new UTimePostingCollection();
			if($ret->fetchMonths()) {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());

			} else {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			}
			$formData['months'] = json_decode($res,true);
			
			$result['form'] = $this->load->view('forms/utimepostingform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function updateEmployeeUTimeForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'updateEmployeeUTime';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{

			$formData['key'] = $result['key'];
			$ret =  new UTimePostingCollection();
			if($ret->fetchMonths()) {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());

			} else {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			}
			$formData['months'] = json_decode($res,true);
			
			$result['form'] = $this->load->view('forms/utimepostingform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function activateUTimePosting(){
		$result = array();
		$page = 'activateUTimePosting';
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
				$ret =  new UTimePostingCollection();
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
	public function deactivateUTimePosting(){
		$result = array();
		$page = 'deactivateUTimePosting';
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
				$ret =  new UTimePostingCollection();
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
	public function addEmployeeUTime(){
		//var_dump($_POST);die();
		$result = array();
		$page = 'addEmployeeUTime';
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
				$ret =  new UTimePostingCollection();
				if($ret->addUTimePosting($post_data)) {
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
	public function updateEmployeeUTime(){
		//var_dump($_POST);die();
		$result = array();
		$page = 'updateEmployeeUTime';
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
				$ret =  new UTimePostingCollection();
				if($ret->updateUTimePosting($post_data)) {
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
				$ret =  new UTimePostingCollection();
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
				$ret =  new UTimePostingCollection();
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