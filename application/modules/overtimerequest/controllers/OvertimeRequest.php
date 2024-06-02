<?php 
///////NOTE: FUNCTIONALITY REFERENCE ---> OB TRACKING MODULE
class OvertimeRequest extends MX_Controller {

	public $allowedfiles = array('application/pdf','image/jpg','image/jpeg', 'image/png');

	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('OvertimeRequestCollection');
		$this->load->module('session');
		$this->load->library('upload');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}

	public function index() {
		$listData = array();
		$viewData = array();
		$page = "viewOvertimeRequest";
		$listData['key'] = $page;
		// $ret = new OvertimeRequestCollection();
		// if($ret->hasRows()){
		// 	$res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
		// 	$respo = json_decode($res);
		// }
		// else{
		// 	$res = new ModelResponse($ret->getCode(), $ret->getMessage());
		// 	$respo = json_decode($res);
		// }
		// $listData['list'] = $respo;
		$viewData['table'] = $this->load->view("helpers/overtimerequestlist",$listData,TRUE);
		$viewData['form'] = $this->load->view("forms/businesspermissionform",$listData,TRUE);

		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Overtime Request Forms');
			Helper::setMenu('templates/menu_template');
			Helper::setView('overtimerequest',$viewData,FALSE);
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
        $data = array(); 
		$ret =  new OvertimeRequestCollection();
		$ret = $ret->make_datatables();
		foreach($ret as $k => $row){
        	$buttons = $buttons_data = $buttons_data2 = "";
            $sub_array = array();
        	$row->first_name = $this->Helper->decrypt($row->first_name,$row->employee_id);
        	$row->middle_name = $this->Helper->decrypt($row->middle_name,$row->employee_id);
        	$row->last_name = $this->Helper->decrypt($row->last_name,$row->employee_id);
        	$row->extension = $this->Helper->decrypt($row->extension,$row->employee_id);
			$time_in = date("h:i A", strtotime($row->time_in));
			$time_out = date("h:i A", strtotime($row->time_out));
			$status = "";
			switch($row->purpose) {
				case "paid" :	$purpose = "With PAY";
									break;
				case "cto" :	$purpose = "For CTO";
									break;
				default : 		$purpose = "Overtime Request";
									break;
			}

			switch($row->status) {
				case "PENDING" :	$class = "text-warning";$status = "PENDING";
									break;
				case "APPROVED" :	$class = "text-success";$status = "APPROVED";
									break;
				case "REJECTED" :	$class = "text-danger";$status = "DISAPPROVED";
									break;
				case "COMPLETED" :	$class = "text-success";$status = "COMPLETED";
									break;
				default : 	$class = "text-default";
									break;
			} 
            foreach($row as $k1=>$v1) $buttons_data .= ' data-'.$k1.'="'.$v1.'" ';
            foreach($row as $k1=>$v1) {
				if($k1=="time_in" || $k1=="time_out" || $k1=="transaction_time") $v1 =  date("h:i A", strtotime($v1));
				$buttons_data2 .= ' data-'.$k1.'="'.$v1.'" ';
			}
            if(Helper::role(ModuleRels::OVERTIME_APPLICATIONS_VIEW_DETAILS)):
				$strto = explode(" ",$row->date_created);
				$buttons .= '<a id="viewOvertimeRequestForm" class="viewOvertimeRequestForm btn btn-info btn-circle waves-effect waves-circle waves-float"
				href="viewOvertimeRequestForm" data-toggle="tooltip" data-placement="right" title="View Details" data-id="'.$row->id.'"
				'.$buttons_data.'> <i class="material-icons">remove_red_eye</i> </a>';
				// $buttons .= '<a id="view_report" class="btn btn-success btn-circle waves-effect waves-circle waves-float"
				// data-toggle = "modal" data-target = "#print_preview_modal" title="Print" '.$buttons_data2.'
				// data-transaction_date_strto="'.date("F d, Y", strtotime($row->transaction_date)).'"
				// data-date_created_strto="'.date("F d, Y", strtotime($strto[0])) .' - '.date("H:i A", strtotime($strto[1])).'"><i class="material-icons">print</i></a>';
				if($row->status != "CANCELLED"){
					$buttons .='<a  href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/cancelApplication"
					class="btn btn-danger btn-circle waves-effect waves-circle waves-float cancelOBRequestForm" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="right" title="Cancel"><i class="material-icons">do_not_disturb</i></a>';
							}


			endif;
            
	        $sub_array[] = $buttons;
            $sub_array[] = $row->last_name.', '.$row->first_name." ".$row->middle_name." ".$row->extension;
			$sub_array[] = date('M, j Y', strtotime($row->filing_date));
			$sub_array[] = @$purpose;
			$sub_array[] = @$row->activity_name;
			$sub_array[] = $time_in != date('00:00 A') ? $time_in . ' - ' . $time_out : $time_out;
			$sub_array[] = '<label class="'.$class.'">'.$status.'</label>'; 
			$sub_array[] = @$row->reject_remarks;
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"                  =>     intval($_GET["draw"]),  
            "recordsTotal"          =>     $this->OvertimeRequestCollection->get_all_data(),  
            "recordsFiltered"     	=>     $this->OvertimeRequestCollection->get_filtered_data(),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
    }
	
	public function cancelApplication(){
		$result = array();
		if (!$this->input->is_ajax_request())
		   show_404();
		else{
			if($this->input->post() && $this->input->post() != null){
				$post_data = array();
				foreach ($this->input->post() as $k => $v) $post_data[$k] = $this->input->post($k,true);
				$ret =  new OvertimeRequestCollection();
				if($ret->cancelApplication($post_data)) $res = new ModelResponse($ret->getCode(), $ret->getMessage());
				else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
				$result = json_decode($res,true);
			}else{
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
		}
		echo json_encode($result);
	}

	public function addOvertimeRequestForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'addOvertimeRequest';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/overtimerequestform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	public function addOvertimeRequest(){
		// var_dump($_POST, $_FILES); die();
		$result = array();
		$page = 'addOvertimeRequest';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else{

			
			if($this->input->post() && $this->input->post() != null) {
				if($_FILES["file"]["size"]>0){
					$structure = './assets/uploads/locatorslips/'.$_SESSION["id"];
					if(!file_exists($structure)) mkdir($structure, 0777, true);
					chmod($structure, 0775);
					$destination = getcwd(). "/assets/uploads/locatorslips/" . $_SESSION["id"];
					if(move_uploaded_file($_FILES["file"]["tmp_name"], $destination.'/'.$_FILES["file"]["name"])){
						$post_data["file_name"] = $_FILES["file"]["name"];
						$post_data["file_size"] = $_FILES["file"]["size"];
					}
				}else{
					
					$post_data["file_name"] = "";
					$post_data["file_size"] = "";
				}
				foreach ($this->input->post() as $k => $v) $post_data[$k] = $this->input->post($k,true);
				// var_dump($post_data).die();
				$ret =  new OvertimeRequestCollection();
				if($ret->addRows($post_data)) $res = new ModelResponse($ret->getCode(), $ret->getMessage());
				else $res = new ModelResponse($ret->getCode(), $ret->getMessage());
				$result = json_decode($res,true);
				// if (!file_exists('./assets/uploads/locatorslips'))
				// 	mkdir('./assets/uploads/locatorslips', 0777, true);
				// $config['upload_path'] = './assets/uploads/locatorslips/';
				// $config['allowed_types'] = '*';
				// $config['overwrite'] = TRUE;
				// $config['remove_spaces'] = FALSE;
				// $this->upload->initialize($config);
				// if ($this->upload->do_upload('uploadFile')):
				// 	$data = array('upload_data' => $this->upload->data());
				// else:
				// 	$error = array('error' => $this->upload->display_errors());
				// endif;
				// $post_data = array();
				// $post_data  = $this->array_push_assoc($post_data, 'file_name', $_FILES['file']['name']);
				// $post_data  = $this->array_push_assoc($post_data, 'file_size', $_FILES['file']['size']);
			}else{
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
			$result['key'] = $page;
		}
		echo json_encode($result);
	}

	public function viewOvertimeRequestForm() {
		$formData = array();
		$result = array();
		$result['key'] = 'viewOvertimeRequest';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/overtimerequestform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	function array_push_assoc($array, $key, $value){
		$array[$key] = $value;
		return $array;
	}

}

?>