<?php

class UserConfig extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('UserConfigCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		//Helper::rolehook(ModuleRels::VIEW_APPROVED_ARCHIVES);
		$this->load->library('pagination');
		$listData = array();
		$viewData = array();
		$status   = isset($_GET['Status'])?$_GET['Status']:'ACTIVE';
		$specific   = isset($_GET['specific'])?$_GET['specific']:'';
		$listData['page'] = "viewUserConfig";
		$ret = new UserConfigCollection();
		// if($ret->hasRowsCount($_GET)){
		// 	$res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
		// 	$rowsCount = json_decode($res);
		// }
		// else{
		// 	$res = new ModelResponse($ret->getCode(), $ret->getMessage());
		// 	$rowsCount = json_decode($res);
		// }
		// //var_dump($rowsCount->Data->count);die();
		// //Pagination Configuration
		// $total_row = (isset($rowsCount->Data->count))?$rowsCount->Data->count:0;
		// $base_url = base_url() . $this->uri->segment(1).'/'. $this->uri->segment(2).'/'."index";
		// //$suffix = '?tenementtype='.$tenementtype.'&sections='.$section.'&daterange='.$daterange.'&specific='.$specific;
		// $suffix = '?Status='.$status.'&specific='.$specific;
		// $this->load->model('Mypagination');
		// $mypagination = new Mypagination();
		// $this->pagination->initialize($mypagination->getUserConfig($base_url,$total_row,$suffix));
		// if($this->uri->segment(4)){
		// 	$page = (int) $this->uri->segment(4);
		// }
		// else{
		// 	$page = 1;
		// }
		// $str_links = $this->pagination->create_links();
		// //var_dump($str_links);die();
		// $listData["pagination"] = $str_links;
		// //var_dump($listData["pagination"]);die();
		// $limit =(string) 12;
		// $offset = (string) (($page - 1) * 12 + 1 - 1);
		//EndPagination Config
		if($ret->hasRows($_POST)){
			$res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
			$respo = json_decode($res);
			foreach ($respo->Data->details as $k1 => $v1) {
				$respo->Data->details[$k1]->first_name = $this->Helper->decrypt($v1->first_name,$v1->employee_id);
				$respo->Data->details[$k1]->middle_name = $this->Helper->decrypt($v1->middle_name,$v1->employee_id);
				$respo->Data->details[$k1]->last_name = $this->Helper->decrypt($v1->last_name,$v1->employee_id);
				$respo->Data->details[$k1]->email = $this->Helper->decrypt($v1->email,$v1->username);
				$respo->Data->details[$k1]->password = $this->Helper->decrypt($v1->password,$v1->username);
			}
			
		}
		else{
			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			$respo = json_decode($res);
		}
		$listData['list'] = $respo;
		//var_dump($listData['list']);die();
		$viewData['table'] = $this->load->view("helpers/userconfiglist",$listData,TRUE);
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('User Accounts');
			Helper::setMenu('templates/menu_template');
			Helper::setView('userconfig',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		}
		else{
			$result['key'] = $listData['page'];
			$result['table'] = $viewData['table'];
			echo json_encode($result);
		}
		Session::checksession();
	}
	public function addUserConfigForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'addUserConfig';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$model = new UserConfigCollection();
			$formData['userlevels'] = $model->getUserLevels("ACTIVE");
			//var_dump($formData['userlevels']);die();
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/userconfigform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function updateUserConfigForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'updateUserConfig';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$ret = new UserConfigCollection();
			if($ret->getUser($this->input->post('id'))){
				$res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
				$respo = json_decode($res);

				foreach ($respo->Data->details as $k1 => $v1) {
					$respo->Data->details[$k1]->first_name = $this->Helper->decrypt($v1->first_name,$v1->employee_id);
					$respo->Data->details[$k1]->middle_name = $this->Helper->decrypt($v1->middle_name,$v1->employee_id);
					$respo->Data->details[$k1]->last_name = $this->Helper->decrypt($v1->last_name,$v1->employee_id);
					$respo->Data->details[$k1]->email = $this->Helper->decrypt($v1->email,$v1->username);
					$respo->Data->details[$k1]->password = $this->Helper->decrypt($v1->password,$v1->username);
				}
			}
			else{
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				$respo = json_decode($res);
			}
			$formData['userlevels'] = $ret->getUserLevels("ACTIVE");
			$formData['data'] = $respo;

			//var_dump($formData['data']->Data->details[0]);die();
			$result['form'] = $this->load->view('forms/userconfigform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function addUserConfig(){
		$result = array();
		$page = 'addUserConfig';
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
				$ret =  new UserConfigCollection();
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
	public function updateUserConfig(){
		$result = array();
		$page = 'updateUserConfig';
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
				$ret =  new UserConfigCollection();
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
	public function changePass(){
		$result = array();
		$page = 'changePass';
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
				$ret =  new UserConfigCollection();
				if($ret->cpass($post_data)) {
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
	public function activateUserConfig(){
		$result = array();
		$page = 'activateUserConfig';
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
				$ret =  new UserConfigCollection();
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
	public function unlockUserConfig(){
		$result = array();
		$page = 'unlockUserConfig';
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
				$ret =  new UserConfigCollection();
				if($ret->activeRows($post_data)) {
					$res = new ModelResponse($ret->getCode(), "Account has been successfully unlocked."/*$ret->getMessage()*/);
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
	public function logoutUser(){
		$result = array();
		$page = 'logoutUser';
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
				$ret =  new UserConfigCollection();
				if($ret->logoutUser($post_data)) {
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
	
	public function deactivateUserConfig(){
		$result = array();
		$page = 'deactivateUserConfig';
		if (!$this->input->is_ajax_request()) show_404();
		else{
			if($this->input->post() && $this->input->post() != null){
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new UserConfigCollection();
				if($ret->inactiveRows($post_data)) $res = new ModelResponse($ret->getCode(), $ret->getMessage());
				else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
				$result = json_decode($res,true);
			}else{
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
			$result['key'] = $page;
		}
		echo json_encode($result);
	}
	
	public function grantAccessUserConfig(){
		$result = array();
		$page = 'grantAccessUserConfig';
		if (!$this->input->is_ajax_request()) show_404();
		else{
			if($this->input->post() && $this->input->post() != null){
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new UserConfigCollection();
				if($ret->grantAccess($post_data)) $res = new ModelResponse($ret->getCode(), $ret->getMessage());
				else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
				$result = json_decode($res,true);
			}else{
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
			$result['key'] = $page;
		}
		echo json_encode($result);
	}

	public function forgotUserPassword(){
		$result = array();
		$page = 'forgotUserPassword';
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
				$ret =  new UserConfigCollection();
				if($ret->fpass($post_data)) {
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
}

?>