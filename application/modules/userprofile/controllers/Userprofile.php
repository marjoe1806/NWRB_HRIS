<?php

class Userprofile extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('Userprofile_model');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		$viewData = array();
		$profile = new Userprofile_model();
		//var_dump($_SESSION);die();
		Helper::setTitle('User Profile');
		Helper::setMenu('templates/menu_template');
		Helper::setView('userprofile',$viewData,FALSE);
		Helper::setTemplate('templates/master_template');
		//Session::checksession();
	}
	public function changePasswordForm(){
		$result['error'] = 0;
		$result['key'] = 'changePass';
		if (!$this->input->is_ajax_request()) {
		   exit('No direct script access allowed');
		   $result['error'] = 1;
		}
		else
		{
			$formData['key'] = $result['key'];
			$formData['section_id'] = $this->input->post('id');
			$result['form'] = $this->load->view('forms/changepassform.php', $formData, true);
		}
		echo json_encode($result);
	}
	public function changePass() {
		$result['error'] = 0;
		$page = 'changePass';
		if (!$this->input->is_ajax_request()) {
		   exit('No direct script access allowed');
		   $result['error'] = 1;
		}
		else
		{
			$ret = new Userprofile_model();
			$oldpass = isset($_REQUEST['oldpass']) ? $_REQUEST['oldpass'] : "";
			$newpass = isset($_REQUEST['newpass']) ? $_REQUEST['newpass'] : "";
			$newpass2 = isset($_REQUEST['newpass2']) ? $_REQUEST['newpass2'] : "";
			if($ret->cPass($oldpass,$newpass,$newpass2)) {
				$ret = new ModelResponse($ret->getCode(), $ret->getMessage());
			} else {
				$ret = new ModelResponse($ret->getCode(), $ret->getMessage());
				$result['error'] = 1;
			}
			$result = json_decode($ret,true);
			$result['key'] = $page;
			
		}
		echo json_encode($result);
	}
	public function updateUserProfile(){
		$result = array();
		$page = 'updateUserProfile';
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
				$ret =  new Userprofile_model();
				if($ret->updateRows($post_data)) {
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
	public function updateUserPhoto(){
		$result = array();
		$page = 'updateUserPhoto';
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
				$ext = explode(".",$_FILES['files']['name']);
				$filename = Helper::get('username').'.'.$ext[sizeof($ext) - 1];
				//var_dump($filename);die();
				$checkUpload = $this->uploadProfilePhoto($filename);
				if(isset($checkUpload['error']) && $checkUpload['error'] != null) {
					$res = new ModelResponse("1", $checkUpload['error']);
				} else {
					$post_data['photopath'] = $checkUpload;
					$ret =  new Userprofile_model();
					if($ret->updateUserPhoto($post_data)) {
						$res = new ModelResponse($ret->getCode(), $ret->getMessage(),$post_data['photopath']);
					} else {
						$res = new ModelResponse($ret->getCode(), $ret->getMessage());
					}
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
	public function uploadProfilePhoto($filename){
		$config['upload_path']       	= FCPATH.'assets/profile-photos/';
		$config['file_name']            = $filename;
        $config['allowed_types']        = 'jpeg|jpg|png|svg';
        $config['max_size']             = 100000;
        $config['overwrite']            = true;
        /*$config['max_width']            = 1366;
        $config['max_height']           = 768;*/
        $this->load->library('upload', $config);
        if($_FILES && sizeof($_FILES) > 0){
        	if ( ! $this->upload->do_upload('files'))
	        {
	            $error = array('error' => $this->upload->display_errors());
	            //var_dump($error);die();
	            return $error; 
	        }
	        else
	        {
	            $data = array('upload_data' => $this->upload->data());
	            if(chmod($data['upload_data']['full_path'],0777)){
	            	$folderPath = $data['upload_data']['file_path'].'thumbnails/';
	            	if(! file_exists($folderPath)){
	            		if(mkdir($folderPath)){
							chmod($folderPath, 0777);
						}
	            	}
	            	$newpath = $folderPath;
	            	$this->load->library('image_lib');
	            	$config['image_library'] 	= 'gd2';
					$config['source_image'] 	= $data['upload_data']['full_path'];
					$config['create_thumb'] 	= FALSE;
					$config['maintain_ratio'] 	= FALSE;
					$config['quality']       	= "90%";
					$config['file_permissions'] = 0777;
					$config['new_image'] 		= $newpath.$filename;
					$config['width']         	= 600;
					$config['height']       	= 600;
					//var_dump($config['source_image']);die();
					$this->load->library('image_lib', $config);
					$this->image_lib->initialize($config);
					if ( ! $this->image_lib->resize())
					{
						$error = array('error' => $this->image_lib->display_errors());
						return $error;
					}
					else{
						return base_url().'assets/profile-photos/thumbnails/'.$filename;
					}
	            }
	            else{
	            	$error = array('error' => 'Failed to upload file.');
	            	return $error;
	            }
				
	        }	
        }
        
        //load the view along with data
	}
}

?>