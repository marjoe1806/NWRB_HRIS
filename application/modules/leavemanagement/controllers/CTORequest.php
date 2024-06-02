<?php 

class CTORequest extends MX_Controller {

	public $allowedfiles = array('application/pdf','image/jpg','image/jpeg', 'image/png');

	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('CTORequestCollection');
		$this->load->module('session');
		$this->load->library('upload');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}

	public function index() {
		$listData = array();
		$viewData = array();
		$page = "viewCTORequest";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/ctorequestlist",$listData,TRUE);
		$viewData['form'] = $this->load->view("forms/ctoprintpreview",$listData,TRUE);

		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Compensatory Time Off Request');
			Helper::setMenu('templates/menu_template');
			Helper::setView('ctorequest',$viewData,FALSE);
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
		$ret =  new CTORequestCollection();
		$ret = $ret->make_datatables();
		foreach($ret as $k => $row){
        	$buttons = $buttons_data = $buttons_data2 = "";
            $sub_array = array();
        	$row->first_name = $this->Helper->decrypt($row->first_name,$row->employee_id);
        	$row->middle_name = $this->Helper->decrypt($row->middle_name,$row->employee_id);
        	$row->last_name = $this->Helper->decrypt($row->last_name,$row->employee_id);
        	$row->extension = $this->Helper->decrypt($row->extension,$row->employee_id);

			// $ret_offset = $this->CTORequestCollection->get_service_record($row->employee_id);
			// $offset =  @$ret_offset['employee'][0]['totaloffset'] != ""? ($ret_offset['employee'][0]['totaloffset'] / 0.00208) / 60 : 0;
			// $offset =  @$ret_offset['employee'][0]['totaloffset'] != ""? $ret_offset['employee'][0]['totaloffset']: 0;
			// $offset =  $this->convertTime($offset);
			$offset =  explode(":",$row->total_offset);
			$offsetHrs = floor($offset[0]);
			$offsetMins = floor($offset[1]);
			// $offsetHrs = floor($offset);
			// $offsetMins = floor((($offset - $offsetHrs)*60));
			
			if($row->type) $row->type = 'CTO';
			$status = "";
			$isRange= explode(" - ",$row->inclusive_dates);
			if($row->inclusive_dates != ""){
				if(sizeof($isRange) == 2) $row->inclusive_dates = date("F d, Y", strtotime($isRange[0])) . " - " . date("F d, Y", strtotime($isRange[1]));
				else{
					$dates = explode(", ",$row->inclusive_dates);
					$stdates = array();
					foreach($dates as $k2 => $v2) $stdates[] = date("F d, Y", strtotime($v2));
					$row->inclusive_dates = implode(", ", $stdates);
				}
			}
            if($row->status == 5) $status_color = "text-success";
            else if($row->status == 1 || $row->status == 2 || $row->status == 3) $status_color = "text-warning";
			else if($row->status == 4) $status_color = "text-info";
			else $status_color = "text-danger";
			
            if($row->status_name == "REJECTED"){
				$status_name = "DISAPPROVED";
			}else{
				$status_name = $row->status_name;
			}
            foreach($row as $k1=>$v1) $buttons_data .= ' data-'.$k1.'="'.$v1.'" ';
            foreach($row as $k1=>$v1) {
				$buttons_data2.= ' data-offset-hrs="'.$offsetHrs.'" ';
				$buttons_data2.= ' data-offset-mins="'.$offsetMins.'" ';
				$buttons_data2 .= ' data-'.$k1.'="'.$v1.'" ';
			}
            // if(Helper::role(ModuleRels::LOCATOR_SLIPS_VIEW_DETAILS)):
				$strto = explode(" ",$row->date_created);
				$buttons .= '<a id="viewCTORequestForm" class="viewCTORequestForm btn btn-info btn-circle waves-effect waves-circle waves-float"
				href="viewCTORequestForm" data-toggle="tooltip" data-placement="top" title="View Details" data-id="'.$row->id.'"
				'.$buttons_data.'> <i class="material-icons">remove_red_eye</i> </a>
				<a id="view_report" class="btn btn-success btn-circle waves-effect waves-circle waves-float"
				data-toggle = "modal" data-target = "#print_preview_modal" title="Print" '.$buttons_data2.'
				data-transaction_date="'.date("F d, Y", strtotime($row->offset_date_effectivity)).'"
				data-date_created_strto="'.date("F d, Y", strtotime($strto[0])) .' - '.date("H:i A", strtotime($strto[1])).'"><i class="material-icons">print</i></a>';

				if($row->status < 4){
					$buttons .='<a style="margin-left:2px" href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/cancelApplication"
					class="btn btn-danger btn-circle waves-effect waves-circle waves-float cancelOBRequestForm" data-id="'.$row->id.'" title="Cancel"><i class="material-icons">do_not_disturb</i></a>';
				}


			// endif;
            
	        $sub_array[] = $buttons;
            $sub_array[] = $row->last_name.', '.$row->first_name." ".$row->middle_name." ".$row->extension;
            $sub_array[] = $row->filing_date;
            $sub_array[] = ucwords(str_replace("_"," ",$row->type));
			$sub_array[] = $row->offset_date_effectivity;
            $sub_array[] = '<b><span class="'.$status_color.'">'.$status_name.'</span><b>';
            $sub_array[] = $row->remarks;
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"                  =>     intval($_GET["draw"]),  
            "recordsTotal"          =>     $this->CTORequestCollection->get_all_data(),  
            "recordsFiltered"     	=>     $this->CTORequestCollection->get_filtered_data(),  
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
				$ret =  new CTORequestCollection();
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

	public function addCTORequestForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'addCTORequest';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$employee_id = isset($_SESSION['id'])?$_SESSION['id']:"";
			$ret = $this->CTORequestCollection->get_service_record($employee_id);
			// var_dump(@$ret['employee'][0]['totaloffset']);die();
			$offset =  @$ret['employee'][0]['totaloffset'] != ""? ($ret['employee'][0]['totaloffset'] / 0.0020833333333333) / 60 : 0;
			$offsetHrs = floor($offset);
			$offsetMins = round(($offset - $offsetHrs)*60);
			$empData['employee']['totaloffsetHrs'] = $offsetHrs;
			$empData['employee']['totaloffsetMins'] = $offsetMins;
			$formData["data"] = $empData;
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/ctorequestform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	public function addCTORequest(){
		$result = array();
		$page = 'addCTORequest';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else{
			$post_data = array();
			if($this->input->post() && $this->input->post() != null){
				$_FILES['uploadFile']['name'] 		= $_FILES['sig_file']['name'];
				$_FILES['uploadFile']['type'] 		= $_FILES['sig_file']['type'];
				$_FILES['uploadFile']['size'] 		= $_FILES['sig_file']['size'];
				$_FILES['uploadFile']['error'] 		= $_FILES['sig_file']['error'];
				$_FILES['uploadFile']['tmp_name'] = $_FILES['sig_file']['tmp_name'];
				$uploadpath = './assets/uploads/ctoattachment/signature/'.$_POST["employee_id"];
				if(!file_exists($uploadpath)) mkdir($uploadpath, 0777, true);
				chmod($uploadpath, 0775);
				$config['upload_path'] = $uploadpath;
				$config['allowed_types'] = '*';
				$config['overwrite'] = TRUE;
				$config['remove_spaces'] = FALSE;
				$this->upload->initialize($config);
				if ($this->upload->do_upload('uploadFile')):
					$data = array('upload_data' => $this->upload->data());
				else:
					$error = array('error' => $this->upload->display_errors());
				endif;
				$post_data  = $this->array_push_assoc($post_data, 'sig_file_name', $_FILES['sig_file']['name']);
				$post_data  = $this->array_push_assoc($post_data, 'sig_file_size', $_FILES['sig_file']['size']);
				foreach ($this->input->post() as $k => $v) $post_data[$k] = $this->input->post($k,true);
				$ret =  new CTORequestCollection();
				if($ret->addRows($post_data)) $res = new ModelResponse($ret->getCode(), $ret->getMessage());
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

	public function viewCTORequestForm() {
		$formData = array();
		$result = array();
		$result['key'] = 'viewCTORequest';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/ctorequestform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	function array_push_assoc($array, $key, $value){
		$array[$key] = $value;
		return $array;
	}

}

?>