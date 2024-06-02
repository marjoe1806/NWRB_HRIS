<?php

class MobileUserConfig extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('MobileUserConfigCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}

	//load initial page of employee management module
	public function index() {
		Helper::rolehook(ModuleRels::USER_MOBILE_ACCOUNTS_SUB_MENU);
		$listData = array();
		$viewData = array();

		$page = "viewMobileUserConfig";
		$listData['key'] = $page;

		$listData['locations'] = $this->MobileUserConfigCollection->getActiveLocations();
		$listData['departments'] = $this->MobileUserConfigCollection->getActiveDepartments();

		$viewData['table'] = $this->load->view("helpers/mobileuserconfiglist",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Mobile User Accounts');
			Helper::setMenu('templates/menu_template');
			Helper::setView('mobileuserconfig',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		}
		else{
			$result['key'] = $listData['key'];
			$result['table'] = $viewData['table'];
			echo json_encode($result);
		}
		Session::checksession();
	}

	//accessing mobile user form to be filled out. 
	public function addMobileUserConfigForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'addMobileUserConfig';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else {
			$formData['locations'] = $this->MobileUserConfigCollection->getActiveLocations();

			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/mobileuserconfigform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	//accessing mobile user form for viewing of records
	public function viewMobileUserConfigForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewMobileUserConfig';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			$formData['locations'] = $this->MobileUserConfigCollection->getActiveLocations();

			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/mobileuserconfigform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	//function responsible for inserting records
	public function addMobileUserConfig(){
		$result = array();
		$page = 'addMobileUserConfig';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					if($k != "employee_id_photo") {
						$post_data[$k] = $this->input->post($k,true);
					}
				}
				$ret =  new MobileUserConfigCollection();
				if($ret->addMobileUserConfig($post_data)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}				
			} else {
				$res = new ModelResponse();
			}
			$result = json_decode($res,true);
			$result['key'] = $page;
		}
		echo json_encode($result);
	}

	// //function responsible for fetching employee details
	function fetchRows() { 
        $fetch_data = $this->MobileUserConfigCollection->make_datatables();  
        $data = array();  
        foreach($fetch_data as $k => $row)  
        {  
        	$buttons = "";
        	$buttons_data = "";
        	$row->employee_number = $this->Helper->decrypt($row->employee_number,$row->employee_id);
			$row->first_name = $this->Helper->decrypt($row->first_name,$row->employee_id);
			$row->middle_name = $this->Helper->decrypt($row->middle_name,$row->employee_id);
			$row->last_name = $this->Helper->decrypt($row->last_name,$row->employee_id);
            $sub_array = array();    

            foreach($row as $k1=>$v1){
            	$buttons_data .= ' data-'.$k1.'="'.$v1.'" ';
            }
            // if(Helper::role(ModuleRels::EMPLOYEE_VIEW_DETAILS)): 
            $buttons .= ' <a id="viewMobileUserConfigForm" ' 
            		  . ' class="viewMobileUserConfigForm" style="text-decoration: none;" '
            		  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/viewMobileUserConfigForm" '
            		  . $buttons_data
            		  . ' > '
            		  . ' <button class="btn btn-primary btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="View Details">'
            		  . ' <i class="material-icons">remove_red_eye</i> '
            		  . ' </button> '
            		  . ' </a> ';
            // endif;
			if($row->isfirstlogin == '0'):
            	$buttons  .= ' <a class="resetPassword btn btn-warning btn-circle waves-effect waves-circle waves-float" '
            		  . ' data-toggle="tooltip" data-placement="top" title="Reset Password" data-id="'.$row->userid.'" data-employee-id="'.$row->employee_id.'" '
                      . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/resetPassword"> '
                      . ' <i class="material-icons">lock_open</i> '
                      . ' </a> ';
			endif;
           	if($row->status == "ACTIVE") {
	            $buttons .= ' <a class="deactivateMobileUserConfig btn btn-danger btn-circle waves-effect waves-circle waves-float" '
	            		  . ' data-toggle="tooltip" data-placement="top" title="Deactivate" data-id="'.$row->userid.'"  data-employee-id="'.$row->employee_id.'" '
	                      . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/deactivateMobileUserConfig"> '
	                      . ' <i class="material-icons">do_not_disturb</i> '
	                      . ' </a> ';
           	} else {
           		$buttons .= ' <a class="activateMobileUserConfig btn btn-success btn-circle waves-effect waves-circle waves-float" '
	            		  . ' data-toggle="tooltip" data-placement="top" title="Activate" data-id="'.$row->userid.'"  data-employee-id="'.$row->employee_id.'" '
	                      . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/activateMobileUserConfig";> '
	                      . ' <i class="material-icons">done</i> '
	                      . ' </a> ';
           	}

	        $sub_array[] = $buttons;
            $sub_array[] = $row->emp_number;
			$sub_array[] = $row->last_name.', '.$row->first_name.' '.$row->middle_name;
            $sub_array[] = $row->location_name;
            if($row->isfirstlogin == '1')
            	$sub_array[] = '<label class="text-warning">DEFAULT</label>';
            else 
            	$sub_array[] = '<label class="text-success">UPDATED</label>';
            if($row->status == 'ACTIVE')
            	$sub_array[] = '<label class="text-success">ACTIVE</label>';
            else 
            	$sub_array[] = '<label class="text-danger">INACTIVE</label>';
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"                  =>     intval($_POST["draw"]),  
            "recordsTotal"          =>      $this->MobileUserConfigCollection->get_all_data(),  
            "recordsFiltered"     	=>     $this->MobileUserConfigCollection->get_filtered_data(),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
    }

    //function responsible for activating records
	public function activateMobileUserConfig(){
		$result = array();
		$page = 'activateMobileUserConfig';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new MobileUserConfigCollection();
				if($ret->activeRows($post_data)) {
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

	//function responsible for deactivating records
	public function deactivateMobileUserConfig(){
		$result = array();
		$page = 'deactivateMobileUserConfig';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new MobileUserConfigCollection();
				if($ret->inactiveRows($post_data)) {
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

	//function responsible for resetting mobile user password
	public function resetPassword(){
		$result = array();
		$page = 'resetPassword';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new MobileUserConfigCollection();
				if($ret->resetPassword($post_data)) {
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
}

?>