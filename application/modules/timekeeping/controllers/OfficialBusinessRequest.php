<?php 

class OfficialBusinessRequest extends MX_Controller {

	public $allowedfiles = array('application/pdf','image/jpg','image/jpeg', 'image/png');

	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('OfficialBusinessRequestCollection');
		$this->load->module('session');
		$this->load->library('upload');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}

	public function index() {
		$listData = array();
		$viewData = array();
		$page = "viewOfficialBusinessRequest";
		$listData['key'] = $page;
		// $ret = new OfficialBusinessRequestCollection();
		// if($ret->hasRows()){
		// 	$res = new ModelResponse($ret->getCode(), $ret->getMessage(),$ret->getData());
		// 	$respo = json_decode($res);
		// }
		// else{
		// 	$res = new ModelResponse($ret->getCode(), $ret->getMessage());
		// 	$respo = json_decode($res);
		// }
		// $listData['list'] = $respo;
		$viewData['table'] = $this->load->view("helpers/officialbusinessrequestlist",$listData,TRUE);
		$viewData['form'] = $this->load->view("forms/businesspermissionform",$listData,TRUE);

		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Personnel Locator Slip Forms');
			Helper::setMenu('templates/menu_template');
			Helper::setView('officialbusinessrequest',$viewData,FALSE);
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
		$ret =  new OfficialBusinessRequestCollection();
		$ret = $ret->make_datatables();
		foreach($ret as $k => $row){
        	$buttons = $buttons_data = $buttons_data2 = "";
            $sub_array = array();
        	$row->first_name = $this->Helper->decrypt($row->first_name,$row->employee_id);
        	$row->middle_name = $this->Helper->decrypt($row->middle_name,$row->employee_id);
        	$row->last_name = $this->Helper->decrypt($row->last_name,$row->employee_id);
        	$row->extension = $this->Helper->decrypt($row->extension,$row->employee_id);
			$status = "";
			switch($row->purpose) {
				case "personal" :	$purpose = "Personal";
									break;
				case "meeting" :	$purpose = "Meeting";
									break;
				case "training_program" :	$purpose = "Training Program";
									break;
				case "others" :		$purpose = "Others";
									break;
				case "seminar_conference" :	$purpose = "Seminar/Conference";
									break;
				case "gov_transaction" :	$purpose = "Government Transaction";
									break;
				default : 			$purpose = "Official";
									break;
			}
			

			switch($row->status) {
				case "PENDING" :	$class = "text-warning";$status = "FOR RECOMMENDATION (Supervisor)";
									break;
				case "FOR APPROVAL" :	$class = "text-warning";$status = "FOR APPROVAL";
									break;
				case "APPROVED" :	$class = "text-success";$status = "APPROVED<br><small> For assigning driver and vehicle </small>";
									break;
				case "REJECTED" :	$class = "text-danger";$status = "DISAPPROVED";
									break;
				case "CANCELLED" :	$class = "text-danger";$status = "CANCELLED";
									break;
				case "COMPLETED" :	$class = "text-success";$status = "COMPLETED";
									break;
				default : 	$class = "text-default";
									break;
			}
            foreach($row as $k1=>$v1) $buttons_data .= ' data-'.$k1.'="'.$v1.'" ';
            foreach($row as $k1=>$v1) {
				if($k1=="locator_time_in" || $k1=="locator_time_out" || $k1=="transaction_time" || $k1=="transaction_time_end") $v1 =  date("h:i A", strtotime($v1));
				$buttons_data2 .= ' data-'.$k1.'="'.$v1.'" ';
			}

			$strto = explode(" ",$row->date_created);
			$buttons .= '<a id="view_report" class="btn btn-success btn-circle waves-effect waves-circle waves-float" '.$buttons_data2.' data-toggle="modal" data-placement="right" title="Print" data-target = "#print_preview_modal"
				data-transaction_date_strto="'.date("F d, Y", strtotime($row->transaction_date)).'"
				data-transaction_date_end_strto="'.date("F d, Y", strtotime($row->transaction_date_end)).'"
				data-expected_time_return="'.date("F d, Y", strtotime($row->expected_time_return)).'"
				data-date_created_strto="'.date("F d, Y", strtotime($strto[0])) .' - '.date("H:i A", strtotime($strto[1])).'"><i class="material-icons">print</i></a>';
			
            if(Helper::role(ModuleRels::LOCATOR_SLIPS_VIEW_DETAILS)):
				$buttons .= '<a id="viewOfficialBusinessRequestForm" class="viewOfficialBusinessRequestForm btn btn-info btn-circle waves-effect waves-circle waves-float"
				href="viewOfficialBusinessRequestForm" data-toggle="tooltip" data-placement="right" title="View Details" data-id="'.$row->id.'"
				'.$buttons_data.'> <i class="material-icons">remove_red_eye</i> </a>';

				if($row->status != "APPROVED" && $row->status != "COMPLETED" && $row->status != "REJECTED" && $row->status != "CANCELLED"){
					$buttons .='<a  href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/cancelApplication"
					class="btn btn-danger btn-circle waves-effect waves-circle waves-float cancelOBRequestForm" data-id="'.$row->id.'"  data-toggle="tooltip" data-placement="right" title="Cancel"><i class="material-icons">do_not_disturb</i></a>';
				}
			endif;

	        $sub_array[] = $buttons;
	        $sub_array[] = str_replace("NWRB-", "", $row->control_no);
			$sub_array[] =  $row->transaction_date_end != "0000-00-00" ? date('Y-m-d', strtotime($row->transaction_date)) .' - '.date('Y-m-d', strtotime($row->transaction_date_end)) : date('Y-m-d', strtotime($row->transaction_date));
            $sub_array[] = $row->last_name.', '.$row->first_name.' '.$row->middle_name.' '.$row->extension;
			$sub_array[] = $purpose;
			$sub_array[] = @$row->location;
			$sub_array[] = $row->activity_name;
			$sub_array[] = @$row->department_name;
			$sub_array[] = $row->reject_remarks;
			$sub_array[] = '<label class="'.$class.'">'.$status.'</label>';
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"                  =>     intval($_GET["draw"]),  
            "recordsTotal"          =>     $this->OfficialBusinessRequestCollection->get_all_data(),  
            "recordsFiltered"     	=>     $this->OfficialBusinessRequestCollection->get_filtered_data(),  
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
				$ret =  new OfficialBusinessRequestCollection();
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

	public function addOfficialBusinessRequestForm(){
		$formData = array();
		$result = array();
		$canselectmultiple = '';
		$result['key'] = 'addOfficialBusinessRequest';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			
			if (in_array("1313", $_SESSION['sessionModules'])){
				$canselectmultiple = "yes";
			}else{ 
				$canselectmultiple = "no";
			}
			$formData['canselectmultiple'] = $canselectmultiple;
			$formData['division_id'] = $_SESSION['division_id'];
			$formData['employee_id'] = $_SESSION['employee_id'];
			$formData['userlevel_id'] = $_SESSION['userlevelid'];
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/officialbusinessrequestform.php', $formData, TRUE);
			// var_dump($formData);die();
		}
		echo json_encode($result); 
	}
	

	public function addOfficialBusinessRequest(){
		// var_dump($_POST, $_FILES); die();
		// print_r($this->input->post('employee_ids')); die();
		$result = array();
		$page = 'addOfficialBusinessRequest';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else{
			// Initialize an empty array to store form data
			$post_data = array();

			// Check if there is any POST data and if it's not null
			if ($this->input->post() && $this->input->post() != null) {
				// Check if a file was uploaded (with the input name "file")
				if ($_FILES["file"]["size"] > 0) {
					// Create a directory structure for the file based on the user's session ID
					$structure = './assets/uploads/locatorslips/' . $_SESSION["id"];
					if (!file_exists($structure)) mkdir($structure, 0777, true);
					chmod($structure, 0775);
					
					// Define the destination path for the uploaded file
					$destination = getcwd() . "/assets/uploads/locatorslips/" . $_SESSION["id"];
					
					// Move the uploaded file to the destination directory
					if (move_uploaded_file($_FILES["file"]["tmp_name"], $destination . '/' . $_FILES["file"]["name"])) {
						$post_data["file_name"] = $_FILES["file"]["name"];
						$post_data["file_size"] = $_FILES["file"]["size"];
					}
				} else {
					// If no file was uploaded, set empty values for file name and size
					$post_data["file_name"] = "";
					$post_data["file_size"] = "";
				}

				// Copy the data from "sig_file" to "uploadFile"
				$_FILES['uploadFile']['name'] = $_FILES['sig_file']['name'];
				$_FILES['uploadFile']['type'] = $_FILES['sig_file']['type'];
				$_FILES['uploadFile']['size'] = $_FILES['sig_file']['size'];
				$_FILES['uploadFile']['error'] = $_FILES['sig_file']['error'];
				$_FILES['uploadFile']['tmp_name'] = $_FILES['sig_file']['tmp_name'];

				// Split the "employee_ids" POST data into an array
				$row_id = explode(',', $_POST["employee_ids"]);
				//var_dump($row_id);die();
				// Create directories for each employee ID and configure file upload settings
				foreach ($row_id as $key => $value) {
					$uploadpath = './assets/uploads/obattachment/signature/' .$value;
					if (!file_exists($uploadpath)) mkdir($uploadpath, 0777, true);
					chmod($uploadpath, 0775);
					$config['upload_path'] = $uploadpath;
					$config['allowed_types'] = '*';
					$config['overwrite'] = TRUE;
					$config['remove_spaces'] = FALSE;
					$this->upload->initialize($config);
				

					// Attempt to upload the "sig_file"
					if ($this->upload->do_upload('uploadFile')) {
						$data = array('upload_data' => $this->upload->data());
					} else {
						$error = array('error' => $this->upload->display_errors());
					}
				}

				// Add the name and size of "sig_file" to the $post_data array
				$post_data = $this->array_push_assoc($post_data, 'sig_file_name', $_FILES['sig_file']['name']);
				$post_data = $this->array_push_assoc($post_data, 'sig_file_size', $_FILES['sig_file']['size']);

				// Loop through all POST data and add it to the $post_data array
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k, true);
				}

				// Create an instance of the OfficialBusinessRequestCollection class and add rows with $post_data
				$ret = new OfficialBusinessRequestCollection();
				if ($ret->addRows($post_data)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}

				// Convert the result to a JSON representation and store it in $result
				$result = json_decode($res, true);
			} else {
				// If there is no POST data, create an empty ModelResponse
				$res = new ModelResponse();
				$result = json_decode($res, true);
			}

			// Add a key named 'key' with the value of $page to the $result array
			$result['key'] = $page;

		}
		echo json_encode($result);
	}

	public function viewOfficialBusinessRequestForm() {
		$formData = array();
		$result = array();
		$result['key'] = 'viewOfficialBusinessRequest';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/officialbusinessrequestform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

	function array_push_assoc($array, $key, $value){
		$array[$key] = $value;
		return $array;
	}

	public function fetchUsersByLocatorID() {
        // Get the travel_id from the AJAX request
        $locator_id = $this->input->post('locator_id');

        // Load the model
		$this->load->model('OfficialBusinessRequestCollection');

        // Call the model function to get users with the provided travel_id
        $data['users'] = $this->OfficialBusinessRequestCollection->fetchUsersByLocatorID($locator_id);

        // Convert the data to JSON and send it back to the client
        header('Content-Type: application/json');
        echo json_encode($data);
    }

}

?>