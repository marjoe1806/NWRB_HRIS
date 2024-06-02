<?php

class PayrollPosting extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('PayrollPostingCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::PAYROLL_POSTING_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewPeriodSettings";
		//Monthly
		$listData['key'] = $page;
		// $ret = new PayrollPostingCollection();
		// if($ret->hasRowsMonthly()){
		// 	$res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
		// 	$respo = json_decode($res);
		// }
		// else{
		// 	$res = new ModelResponse($ret->getCode(), $ret->getMessage());
		// 	$respo = json_decode($res);
		// }
		// $listData['list'] = $respo;
		$viewData['table_monthly'] = $this->load->view("helpers/payrollpostingmonthlylist",$listData,TRUE);
		//Semi Monthly
		// if($ret->hasRowsSemiMonthly()){
		// 	$res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
		// 	$respo = json_decode($res);
		// }
		// else{
		// 	$res = new ModelResponse($ret->getCode(), $ret->getMessage());
		// 	$respo = json_decode($res);
		// }
		// $listData2['key'] = "viewPeriodSettingsSemiMonthly";
		// $listData2['list'] = $respo; 
		// $viewData['table_semimonthly'] = $this->load->view("helpers/payrollpostingsemimonthlylist",$listData2,TRUE); 

		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Payroll Posting');
			Helper::setMenu('templates/menu_template');
			Helper::setView('payrollposting',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		}
		else{
			$result['key'] = $listData['key'];
			$result['table_monthly'] = $viewData['table_monthly'];
			$result['table_semimonthly'] = $viewData['table_semimonthly'];
			echo json_encode($result);
		}
		Session::checksession();
	}
	function fetchRows(){ 

		$month = "";
		$isPosted = "";
		if(isset($_GET["month"])) $month = $_GET["month"];
		if(isset($_GET["isPosted"])) $isPosted = $_GET["isPosted"];
		$fetch_data = $this->PayrollPostingCollection->make_datatables($month,$isPosted);
        $data = array();  
        foreach($fetch_data as $k => $row)  
        {  
        	$buttons = "";
        	$buttons_data = "";
            $sub_array = array();    
			$dt = "";
			foreach ($row as $k => $v) $dt = ' data-'.$k.'="'.$v.'" ';
			$btns = "";
			$btns = '<a class="lockPeriodSettings btn '. ($row->is_posted == 1? 'btn-danger' : 'btn-success').' btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip"
			data-placement="top" title="lock" data-id="'.$row->id.'" data-is-posted="'.$row->is_posted.'" href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/lockPeriodSettings" '.($row->is_posted == 1? "disabled" : "").'>
			   <i class="material-icons">lock</i>
		   </a>';
			$sub_array[] = $btns;
            $sub_array[] = date("F Y", strtotime($row->payroll_period));
            $sub_array[] = date("M", strtotime($row->start_date)) . ' ' . date("d", strtotime($row->start_date)) . ' to ' . date("d", strtotime($row->end_date));  
            $sub_array[] = $row->period_id;
            $sub_array[] = ($row->is_active == "1")?'<label class="text-success">ACTIVE</label>':'<label class="text-danger">INACTIVE</label>';
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"                  =>     intval($_GET["draw"]),  
            "recordsTotal"          =>      $this->PayrollPostingCollection->get_all_data($month,$isPosted),  
            "recordsFiltered"     	=>     $this->PayrollPostingCollection->get_filtered_data($month,$isPosted),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
	}
	// public function addPeriodSettingsForm(){
	// 	$formData = array();
	// 	$result = array();
	// 	$result['key'] = 'addPeriodSettings';
	// 	if (!$this->input->is_ajax_request()) {
	// 	   show_404();
	// 	}
	// 	else
	// 	{
	// 		$formData['key'] = $result['key'];
	// 		$result['form'] = $this->load->view('forms/periodsettingsform.php', $formData, TRUE);
	// 	}
	// 	echo json_encode($result);
	// }
	// public function updatePeriodSettingsForm(){
	// 	$formData = array();
	// 	$result = array();
	// 	$result['key'] = 'updatePeriodSettings';
	// 	if (!$this->input->is_ajax_request()) {
	// 	   show_404();
	// 	}
	// 	else
	// 	{
	// 		$formData['key'] = $result['key'];
	// 		$result['form'] = $this->load->view('forms/periodsettingsform.php', $formData, TRUE);
	// 	}
	// 	echo json_encode($result);
	// }

	// public function addWeeklyPeriodSettingsForm(){
	// 	$formData = array();
	// 	$result = array();
	// 	$result['key'] = 'addWeeklyPeriodSettings';
	// 	if (!$this->input->is_ajax_request()) {
	// 	   show_404();
	// 	}
	// 	else
	// 	{
	// 		$formData['key'] = $result['key'];
	// 		$result['form'] = $this->load->view('forms/weeklyperiodsettingsmodalform.php', $formData, TRUE);
	// 	}
	// 	echo json_encode($result);
	// }

	// public function updateWeeklyPeriodSettingsForm(){
	// 	$formData = array();
	// 	$result = array();
	// 	$result['key'] = 'updateWeeklyPeriodSettings';
	// 	if (!$this->input->is_ajax_request()) {
	// 	   show_404();
	// 	}
	// 	else
	// 	{
	// 		$formData['key'] = $result['key'];
	// 		$result['form'] = $this->load->view('forms/weeklyperiodsettingsmodalform.php', $formData, TRUE);
	// 	}
	// 	echo json_encode($result);
	// }


	// public function weeklyPeriodSettingsForm(){
	// 	$formData = array();
	// 	$result = array();
	// 	$result['key'] = 'weeklyPeriodSettings';
	// 	if (!$this->input->is_ajax_request()) {
	// 	   show_404();
	// 	}
	// 	else
	// 	{
	// 		$ret =  new PayrollPostingCollection();
	// 		if($ret->hasRowsWeeklyPeriodSettings()) {
	// 			$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
	// 			$respo = json_decode($res);
	// 		} else {
	// 			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
	// 			$respo = json_decode($res);
	// 		}
	// 		$formData['list'] = $respo;
	// 		$formData['key'] = $result['key'];
	// 		$result['form'] = $this->load->view('forms/weeklyperiodsettingsform.php', $formData, TRUE);
	// 	}
	// 	echo json_encode($result);
	// }

	// public function addPeriodSettings(){
	// 	//var_dump($_POST);die();
	// 	$result = array();
	// 	$page = 'addPeriodSettings';
	// 	if (!$this->input->is_ajax_request()) {
	// 	   show_404();
	// 	}
	// 	else
	// 	{
	// 		if($this->input->post() && $this->input->post() != null)
	// 		{
	// 			$post_data = array();
	// 			foreach ($this->input->post() as $k => $v) {
	// 				$post_data[$k] = $this->input->post($k,true);
	// 			}
	// 			$ret =  new PayrollPostingCollection();
	// 			if($ret->addRows($post_data)) {
	// 				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
	// 			} else {
	// 				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
	// 			}
	// 			$result = json_decode($res,true);
	// 		}
	// 		else
	// 		{
	// 			$res = new ModelResponse();
	// 			$result = json_decode($res,true);
	// 		}
	// 		$result['key'] = $page;
	// 	}
	// 	echo json_encode($result);
	// }

	
	// public function updatePeriodSettings(){
	// 	$result = array();
	// 	$page = 'updatePeriodSettings';
	// 	if (!$this->input->is_ajax_request()) {
	// 	   show_404();
	// 	} else {
	// 		if($this->input->post() && $this->input->post() != null) {
	// 			$post_data = array();
	// 			foreach ($this->input->post() as $k => $v) {
	// 				$post_data[$k] = $this->input->post($k,true);
	// 			}
	// 			$ret =  new PayrollPostingCollection();
	// 			if($ret->updateRows($post_data)) {
	// 				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
	// 			} else {
	// 				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
	// 			}
	// 			$result = json_decode($res,true);
	// 		} else {
	// 			$res = new ModelResponse();
	// 			$result = json_decode($res,true);
	// 		}
	// 		$result['key'] = $page;
	// 	}
	// 	echo json_encode($result);
	// }
	
	public function lockPeriodSettings(){
		$result = array();
		$page = 'activatePeriodSettings';
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
				$ret =  new PayrollPostingCollection();
				if($ret->lockRows($post_data)) {
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
	// public function deactivatePeriodSettings(){
	// 	$result = array();
	// 	$page = 'deactivatePeriodSettings';
	// 	if (!$this->input->is_ajax_request()) {
	// 	   show_404();
	// 	}
	// 	else
	// 	{
	// 		if($this->input->post() && $this->input->post() != null)
	// 		{
	// 			$post_data = array();
	// 			foreach ($this->input->post() as $k => $v) {
	// 				$post_data[$k] = $this->input->post($k,true);
	// 			}
	// 			$ret =  new PayrollPostingCollection();
	// 			if($ret->inactiveRows($post_data)) {
	// 				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
	// 			} else {
	// 				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
	// 			}
	// 			$result = json_decode($res,true);
	// 		}
	// 		else
	// 		{
	// 			$res = new ModelResponse();
	// 			$result = json_decode($res,true);
	// 		}
	// 		$result['key'] = $page;
	// 	}
	// 	echo json_encode($result);
	// }
	// public function getActivePeriodSettings(){
	// 	$result = array();
	// 	$page = 'getActivePeriodSettings';
	// 	if (!$this->input->is_ajax_request()) {
	// 	   show_404();
	// 	}
	// 	else
	// 	{
	// 		$ret =  new PayrollPostingCollection();
	// 		if($ret->hasRowsActive()) {
	// 			$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
	// 		} else {
	// 			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
	// 		}
	// 		$result = json_decode($res,true);
			
	// 		$result['key'] = $page;
	// 	}
	// 	echo json_encode($result);
	// }

	// public function getPeriodSettingsById(){
	// 	$result = array();
	// 	$page = 'getPeriodSettingsById';
	// 	if (!$this->input->is_ajax_request()) {
	// 	   show_404();
	// 	}
	// 	else
	// 	{
	// 		$ret =  new PayrollPostingCollection();
	// 		if($ret->hasRowsById()) {
	// 			$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
	// 		} else {
	// 			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
	// 		}
	// 		$result = json_decode($res,true);
			
	// 		$result['key'] = $page;
	// 	}
	// 	echo json_encode($result);
	// }

	// public function getActivePeriodSettingsCutOff(){
	// 	$pay_basis = $this->input->get('PayBasis');
	// 	$result = array();
	// 	$page = 'getActivePeriodSettingsCutOff';
	// 	if (!$this->input->is_ajax_request()) {
	// 	   show_404();
	// 	}
	// 	else
	// 	{
	// 		$ret =  new PayrollPostingCollection();
	// 		if($ret->hasRowsActive($pay_basis)) {
	// 			$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
	// 		} else {
	// 			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
	// 		}
	// 		$result = json_decode($res,true);
			
	// 		$result['key'] = $page;
	// 	}
	// 	echo json_encode($result);
	// }

	// public function getActivePeriods(){
	// 	// $pay_basis = $this->input->get('PayBasis');
	// 	$result = array();
	// 	$page = 'getActivePeriodSettingsCutOff';
	// 	if (!$this->input->is_ajax_request()) show_404();
	// 	else{
	// 		$ret =  new PayrollPostingCollection();
	// 		if($ret->activePeriods()) $res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
	// 		else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
	// 		$result = json_decode($res,true);
			
	// 		$result['key'] = $page;
	// 	}
	// 	echo json_encode($result);
	// }
	
	// public function addWeeklyPeriodSettings(){
	// 	$result = array();
	// 	$page = 'addWeeklyPeriodSettings';
	// 	if (!$this->input->is_ajax_request()) {
	// 	   show_404();
	// 	}
	// 	else
	// 	{
	// 		if($this->input->post() && $this->input->post() != null)
	// 		{
	// 			$post_data = array();
	// 			foreach ($this->input->post() as $k => $v) {
	// 				$post_data[$k] = $this->input->post($k,true);
	// 			}
	// 			$ret =  new PayrollPostingCollection();
	// 			if($ret->addRowsWeekly($post_data)) {
	// 				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
	// 			} else {
	// 				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
	// 			}
	// 			$result = json_decode($res,true);
	// 		}
	// 		else
	// 		{
	// 			$res = new ModelResponse();
	// 			$result = json_decode($res,true);
	// 		}
	// 		$result['key'] = $page;
	// 	}
	// 	echo json_encode($result);
	// }
	// public function updateWeeklyPeriodSettings(){
	// 	$result = array();
	// 	$page = 'updateWeeklyPeriodSettings';
	// 	if (!$this->input->is_ajax_request()) {
	// 	   show_404();
	// 	} else {
	// 		if($this->input->post() && $this->input->post() != null) {
	// 			$post_data = array();
	// 			foreach ($this->input->post() as $k => $v) {
	// 				$post_data[$k] = $this->input->post($k,true);
	// 			}
	// 			$ret =  new PayrollPostingCollection();
	// 			if($ret->updateRowsWeekly($post_data)) {
	// 				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
	// 			} else {
	// 				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
	// 			}
	// 			$result = json_decode($res,true);
	// 		} else {
	// 			$res = new ModelResponse();
	// 			$result = json_decode($res,true);
	// 		}
	// 		$result['key'] = $page;
	// 	}
	// 	echo json_encode($result);
	// }
	
	// public function activateWeeklyPeriodSettings(){
	// 	$result = array();
	// 	$page = 'activateWeeklyPeriodSettings';
	// 	if (!$this->input->is_ajax_request()) {
	// 	   show_404();
	// 	}
	// 	else
	// 	{
	// 		if($this->input->post() && $this->input->post() != null)
	// 		{
	// 			$post_data = array();
	// 			foreach ($this->input->post() as $k => $v) {
	// 				$post_data[$k] = $this->input->post($k,true);
	// 			}
	// 			$ret =  new PayrollPostingCollection();
	// 			if($ret->activeRowsWeekly($post_data)) {
	// 				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
	// 			} else {
	// 				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
	// 			}
	// 			$result = json_decode($res,true);
	// 		}
	// 		else
	// 		{
	// 			$res = new ModelResponse();
	// 			$result = json_decode($res,true);
	// 		}
	// 		$result['key'] = $page;
	// 	}
	// 	echo json_encode($result);
	// }
	// public function deactivateWeeklyPeriodSettings(){
	// 	$result = array();
	// 	$page = 'deactivateWeeklyPeriodSettings';
	// 	if (!$this->input->is_ajax_request()) {
	// 	   show_404();
	// 	}
	// 	else
	// 	{
	// 		if($this->input->post() && $this->input->post() != null)
	// 		{
	// 			$post_data = array();
	// 			foreach ($this->input->post() as $k => $v) {
	// 				$post_data[$k] = $this->input->post($k,true);
	// 			}
	// 			$ret =  new PayrollPostingCollection();
	// 			if($ret->inactiveRowsWeekly($post_data)) {
	// 				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
	// 			} else {
	// 				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
	// 			}
	// 			$result = json_decode($res,true);
	// 		}
	// 		else
	// 		{
	// 			$res = new ModelResponse();
	// 			$result = json_decode($res,true);
	// 		}
	// 		$result['key'] = $page;
	// 	}
	// 	echo json_encode($result);
	// }
	// public function getActiveWeeklyPeriodSettings(){
	// 	$result = array();
	// 	$page = 'getActiveWeeklyPeriodSettings';
	// 	if (!$this->input->is_ajax_request()) {
	// 	   show_404();
	// 	}
	// 	else
	// 	{
	// 		$ret =  new PayrollPostingCollection();
	// 		if($ret->hasRowsActiveWeekly()) {
	// 			$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
	// 		} else {
	// 			$res = new ModelResponse($ret->getCode(), $ret->getMessage());
	// 		}
	// 		$result = json_decode($res,true);
			
	// 		$result['key'] = $page;
	// 	}
	// 	echo json_encode($result);
	// }
	// public function getWeeklyPeriodByPeriodId(){
	// 	$result = array();
	// 	$page = 'getActiveWeeklyPeriodSettings';
	// 	if (!$this->input->is_ajax_request()) {
	// 	   show_404();
	// 	}
	// 	else
	// 	{
	// 		if($this->input->post() && $this->input->post() != null)
	// 		{
	// 			$ret =  new PayrollPostingCollection();
	// 			if($ret->hasRowsWeeklyPeriodSettings()) {
	// 				$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
	// 			} else {
	// 				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
	// 			}
	// 			$result = json_decode($res,true);
	// 		}
	// 		else{
	// 			$res = new ModelResponse();
	// 			$result = json_decode($res,true);
	// 		}
	// 		$result['key'] = $page;
	// 	}
	// 	echo json_encode($result);
	// }

	// public function generateWeekRange(){
	// 	$result = array();
	// 	if (!$this->input->is_ajax_request()) {
	// 	   show_404();
	// 	}
	// 	else
	// 	{
	// 		$week_range = array();
	// 		$month = $this->input->post('month');
	// 		$year = $this->input->post('year');

	// 		$week = date("W", strtotime($year . "-" . $month ."-01")); // weeknumber of first day of month

	// 		$unix = strtotime($year."W".$week ."+1 week");

	// 		$date['start'] = date("Y-m-d", strtotime($year . "-" . $month ."-01"));
	// 		While(date("m", $unix) == $month){ // keep looping/output of while it's correct month
	// 		   $date['end'] = date("Y-m-d", $unix-86400*2);
			   
	// 		   array_push($week_range,$date);
	// 		   $date['start'] = date("Y-m-d", $unix-86400);
	// 		   $unix = $unix + (86400*7);
	// 		}
	// 		$date['end'] = date("Y-m-d", strtotime("last day of ".$year . "-" . $month)); 

	// 		array_push($week_range,$date);
	// 		$result = $week_range;
	// 	}
	// 	echo json_encode($result);
	// }

}

?>