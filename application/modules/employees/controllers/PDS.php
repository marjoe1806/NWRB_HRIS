<?php

class PDS extends MX_Controller {
	
	public $allowedfiles = array('application/pdf','image/jpg','image/jpeg', 'image/png');
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('PDSCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		// $ret = $this->PDSCollection->checkAccess($_SESSION["id"]);
		// if($ret) Helper::rolehook(ModuleRels::EMPLOYEES_PDS_SUB_MENU);
		// else Helper::rolehook(false);
		$listData = array();
		$viewData = array();
		$page = "pds";
		$listData['key'] = $page;
		// $listData['access'] = $ret;
		$viewData['table'] = $this->load->view("helpers/employeeslist",$listData,TRUE);
		$viewData['form'] = $this->load->view("forms/employeesform",$listData,TRUE); 		
		$ret = $this->PDSCollection->get_details();
		foreach($ret as $k => $row){  
			$viewData['buttons_data'] = "";	
			$row->employee_number = (isset($row->employee_number))?$this->Helper->decrypt($row->employee_number,$row->id):"";
			$row->employee_id_number = (isset($row->employee_id_number))?$this->Helper->decrypt($row->employee_id_number,$row->id):"";
			$row->extension = (isset($row->extension))?$this->Helper->decrypt($row->extension,$row->id):"";
			$row->first_name = (isset($row->first_name))?$this->Helper->decrypt($row->first_name,$row->id):"";
			$row->middle_name = (isset($row->middle_name))?$this->Helper->decrypt($row->middle_name,$row->id):"";
			$row->last_name = (isset($row->last_name))?$this->Helper->decrypt($row->last_name,$row->id):"";
			if($row->employee_number != "") $row->employee_number = str_pad($row->employee_number, 4, '0', STR_PAD_LEFT); 
			foreach($row as $k1=>$v1) {
				if($k1 == "date_of_permanent" || $k1 == "end_date" || $k1 == "start_date"){
					if($v1 != "0000-00-00" && $v1 != "") $v1 = date("m-d-Y", strtotime($v1));
					else $v1 = "";
				}
				$viewData['buttons_data'] .= ' data-'.$k1.'="'.str_replace(" 00:00:00","",$v1).'" ';
			}
		}
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('My PDS');
			Helper::setMenu('templates/menu_template');
			Helper::setView('pds',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		}
		else{
			$result['key'] = $listData['key'];
			$result['table'] = $viewData['table'];
			echo json_encode($result);
		}
		Session::checksession();
    } 
	
	public function viewEmployeesForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewEmployees';
		if (!$this->input->is_ajax_request()) show_404();
		else {
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/employeeprintpreview.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
    
    function getEmpDetails(){ 
        $result = array();
        if (!$this->input->is_ajax_request()) {
            show_404();
         } else {
            if($this->input->post() && $this->input->post() != null) {
				$ret = $this->PDSCollection->make_datatables($this->input->post("id"));
				$data = array();
				if(sizeof($ret)>0){
					foreach($ret[0] as $k1=>$v1) {
						if($k1 === "employee_number" || $k1 === "employee_id_number" || $k1 === "first_name" || $k1 === "middle_name" || $k1 === "last_name" || $k1 === "extension")
							$data[$k1] = $this->Helper->decrypt($v1,$ret[0]->id);
						else
							$data[$k1] = str_replace(" 00:00:00","",$v1);
					}
				}
				$result = array("data"=>$data);
            }

        }
        echo json_encode($result);  
    }

    public function getEmpTables(){
		if (!$this->input->is_ajax_request())
		   show_404();
		$ret = $this->PDSCollection->getEmpRows($_POST["id"]);
		echo json_encode($ret);
    }
    
	public function getEmpAttachments(){
		if (!$this->input->is_ajax_request())
		   show_404();
		
		$ret = new PDSCollection();
		if($ret->getEmpAtthcmentRows($_POST["id"])) {
			$res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
		} else {
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
		}
		$result = json_decode($res,true);
		echo json_encode($result);
	}
	
	public function updatePDS(){
		$result = array();
		$page = 'addEmployees';
		if (!$this->input->is_ajax_request()) show_404();
		else {
			if($this->input->post() && $this->input->post() != null) {
				$files = (isset($_FILES))?$_FILES:array();
					$post_data = array();
					foreach ($this->input->post() as $k => $v) $post_data[$k] = $this->input->post($k,true);
					foreach ($files as $k => $v) $post_data[$k] = $files[$k];
					$ret =  new PDSCollection();
					if($ret->updateRows($post_data)) {
						$res = new ModelResponse($ret->getCode(), $ret->getMessage());
						$countNewFile = 0;
						$foldername = $post_data["id"];
						$structure = './uploads/employees/'.$foldername;
						$folder_files = glob($structure.'/*.*');
						if(!file_exists($structure)){
							mkdir($structure, 0777, true);
							chmod($structure, 0775);
						}
						$destination = getcwd(). "/uploads/employees/" . $foldername;
						$errorDelete = $errorRename = $errorUpload = $errorFormat = 0;
						if(isset($post_data["file_title"]) && sizeof($post_data["file_title"]) > 0){
							foreach($folder_files as $k => $v){
								$ex = explode("/",$v);
								if(!in_array($ex[4],$post_data["cur_file"])) unlink($structure."/".$ex[4]);
							}
							foreach($post_data["file_title"] as $key => $value){
								if($files["new_file"]["size"][$key] > 0){
									if(isset($post_data["cur_file"][$key]) && $value !== $post_data["cur_file_name"][$key] && $post_data["new_file"]["size"][$key] <= 0) if(!rename($structure."/".$post_data["cur_file"][$key],$structure."/".$value.substr(($files["new_file"]["name"][$key]!=="")?$files["new_file"]["name"][$key]:$post_data["cur_file"][$key],($files["new_file"]["type"][$key]==="image/jpeg")?-5:-4))) $errorRename++; // rename new file
									if(
										(isset($post_data["cur_file"][$key]) && $value !== $post_data["cur_file_name"][$key] && $post_data["new_file"]["size"][$key] > 0) || 
										($post_data["new_file"]["size"][$key] > 0 && $value === $post_data["cur_file_name"][$key] && isset($post_data["cur_file"][$key])) || 
										($post_data["new_file"]["size"][$key] > 0 && !isset($post_data["cur_file"][$key]))){ // upload new file
											if(isset($post_data["cur_file"][$key]) && $post_data["cur_file"][$key] != ""){
												if(unlink($structure."/".$post_data["cur_file"][$key])){
													if(!move_uploaded_file($files["new_file"]["tmp_name"][$key], $destination.'/'.$files["new_file"]["name"][$key])) $errorUpload++;
												}else $errorDelete++;
											}else{
												if(!move_uploaded_file($files["new_file"]["tmp_name"][$key], $destination.'/'.$files["new_file"]["name"][$key])) $errorUpload++;
											}
									}
								}
							}
						}
						$erros = $errorDelete + $errorRename + $errorUpload + $errorFormat;
						if($erros > 0) $res = new ModelResponse($ret->getCode(), "Successfully update employee but something<br>went wrong uploading files.");
						else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
					}else{
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

	public function updatePDSbackup(){
		$result = array();
		$page = 'updateEmployees';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		} else {
			if($this->input->post() && $this->input->post() != null) {
				$files = (isset($_FILES))?$_FILES:array();
				$post_data = array();
				foreach ($this->input->post() as $k => $v) $post_data[$k] = $this->input->post($k,true);
				foreach ($files as $k => $v) $post_data[$k] = $files[$k];
				$ret =  new PDSCollection();
				//update Employee
				// var_dump($post_data); die();
				if($ret->updateRows($post_data)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
					$countNewFile = 0;
					$foldername = $post_data["id"];
					$structure = './uploads/employees/'.$foldername;
					$folder_files = glob($structure.'/*.*');
					if(!file_exists($structure)){
						mkdir($structure, 0777, true);
						chmod($structure, 0775);
					}else{
						chmod($structure, 0775);
					}
					$destination = getcwd(). "/uploads/employees/" . $foldername;
					foreach($folder_files as $k => $v){
						$ex = explode("/",$v);
						if(!in_array($ex[4],$post_data["cur_file"])) unlink($structure."/".$ex[4]);
					}
					$errorDelete = $errorRename = $errorUpload = $errorFormat = 0;
					// var_dump($post_data["new_file"] ); die();
					if(isset($post_data["file_title"]) && sizeof($post_data["file_title"]) > 0){
						foreach($post_data["file_title"] as $key => $value){

							// if(isset($post_data["cur_file"][$key]) && $value !== $post_data["cur_file_name"][$key] && $post_data["new_file"]["size"][$key] == 0) if(!rename($structure."/".$post_data["cur_file"][$key],$structure."/".$value.substr(($files["new_file"]["name"][$key]!=="")?$files["new_file"]["name"][$key]:$post_data["cur_file"][$key],($files["new_file"]["type"][$key]==="image/jpeg")?-5:-4))) $errorRename++; // rename new file
							if(
								(isset($post_data["cur_file"][$key]) && $value !== $post_data["cur_file_name"] && $post_data["new_file"]["size"][$key] > 0) || 
								($post_data["new_file"]["size"][$key] > 0 && $value === $post_data["cur_file_name"] && isset($post_data["cur_file"][$key])) || 
								($post_data["new_file"]["size"][$key] > 0 && !isset($post_data["cur_file"][$key]))){ // upload new file
								if(!in_array($post_data["new_file"]["type"][$key],$this->allowedfiles)){
									$errorFormat++;
								}else {
									if(isset($post_data["cur_file"][$key])){
										if(unlink($structure."/".$post_data["cur_file"][$key])){
											if(!move_uploaded_file($files["new_file"]["tmp_name"][$key], $destination.'/'.$post_data["file_title"][$key].substr(($files["new_file"]["name"][$key] !== "")?$files["new_file"]["name"][$key]:$post_data["cur_file"][$key],($files["new_file"]["type"][$key]==="image/jpeg")?-5:-4))) $errorUpload++;
										}else $errorDelete++;
									}else{
										if(!move_uploaded_file($files["new_file"]["tmp_name"][$key], $destination.'/'.$post_data["file_title"][$key].substr(($files["new_file"]["name"][$key] !== "")?$files["new_file"]["name"][$key]:$post_data["cur_file"][$key],($files["new_file"]["type"][$key]==="image/jpeg")?-5:-4))) $errorUpload++;										
									}
								}
							}
						}
					}
					$erros = $errorDelete + $errorRename + $errorUpload + $errorFormat;
					if($erros > 0) $res = new ModelResponse($ret->getCode(), "Successfully update employee but something<br>went wrong uploading files.");
					else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}else{
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
				//end of update Employee
				$result = json_decode($res,true);
			} else {
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
			$result['key'] = $page;
		}
		echo json_encode($result);
	}
	public function getActivePhotoByEmployeeId(){
		$result = array();
		$page = 'getActivePhotoByEmployeeId';
		if (!$this->input->is_ajax_request()) show_404();
		else{
			$employee_id = isset($_POST['employee_id'])?$_POST['employee_id']:"";
			$ret =  new PDSCollection();
			if($ret->hasRowsPhotos($employee_id)) {
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