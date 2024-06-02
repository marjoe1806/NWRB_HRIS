<?php

class Home extends MX_Controller {
	
	public function __construct() {
		parent::__construct();		
		$this->load->model('Helper');
		$this->load->model('ModuleRels');
		$this->load->model('Collections');
		$this->load->module('session');
		//Helper::sessionEndedHook('session');
	}
	
	public function index() {		
		$viewData = array();
		$viewData['tablepromotion'] = $this->load->view("helpers/promotionlist"," ",TRUE);
		Helper::setTitle('Dashboard | NWRB - CHRIS');	

		Helper::setMenu('templates/menu_template');
		Helper::setView('home',$viewData,FALSE);
		Helper::setTemplate('templates/master_template');
		Session::checksession();
	}
	public function map() {		
		Helper::setTitle('Dashboard | NWRB - CHRIS');	

		Helper::setMenu('templates/menu_template');
		Helper::setView('home_map','',FALSE);
		Helper::setTemplate('templates/blank_template');
		Session::checksession();
	}
	public function get_address() {
		$lat = $this->input->post('lat');
		$lng = $this->input->post('lng');

		$geocodeFromLatLong = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?latlng='.$lat.','.$lng.'&sensor=false');
        $output = json_decode($geocodeFromLatLong);
        $status = $output->status;

        if($status == "OK"){
			echo json_encode($output);
        }else{
        	echo "empty";
        }
	}

	public function get_latlng() {
		$address = $this->input->post('address');
		$key = "AIzaSyCiaCn76w3qFRQCHaAv3tqGhYoS7jN-PHA";

		// $geocodeFromLatLong = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address='.$address.'&sensor=false');
		$url = sprintf('https://maps.googleapis.com/maps/api/geocode/json?address=%s&key=%s', urlencode($address), urlencode($key));
   	 	$geocodeFromLatLong = file_get_contents($url);

        $output = json_decode($geocodeFromLatLong);
        $status = $output->status;
        if($status == "OK"){
			echo json_encode($output);
        }else{
        	echo "empty";
        }
	}

	function fetchRowspromotion(){
		$data = array();
		$ret =  new Collections();
		$ret = $ret->make_datatablespromotion();
		$current_date = date("Y-m-d");
		$split = explode('-', $current_date);
		$current_year = $split[0];
			foreach($ret as $k => $row){  
				$buttons_action = "";
				$buttons_data = "";

				$row->employee_number = (isset($row->employee_number))?$this->Helper->decrypt($row->employee_number,$row->id):"";
				$row->employee_id_number = (isset($row->employee_id_number))?$this->Helper->decrypt($row->employee_id_number,$row->id):"";
				$row->extension = (isset($row->extension))?$this->Helper->decrypt($row->extension,$row->id):"";
				$row->first_name = (isset($row->first_name))?$this->Helper->decrypt($row->first_name,$row->id):"";
				$row->middle_name = (isset($row->middle_name))?$this->Helper->decrypt($row->middle_name,$row->id):"";
				$row->last_name = (isset($row->last_name))?$this->Helper->decrypt($row->last_name,$row->id):"";

				$sub_array = array();    
				$sub_array[] = $row->employee_id_number; //empl id no
				$sub_array[] = $row->last_name.', '.$row->first_name." ".$row->middle_name;  //emp name
				$sub_array[] = $row->pay_basis;  //pay_basis
				$sub_array[] = $row->department_name; // division
				$sub_array[] = $row->start_date; // start_date
				$sub_array[] = $row->date_of_permanent; // date_of_permanent

				
				if($row->start_date !== "0000-00-00" && $row->start_date !== ""){
					$start = new DateTime($row->start_date);
					$end = new DateTime(($row->end_date != "" && $row->end_date == "0000-00-00" ? $current_date : $row->end_date));
					$interval =  $start->diff($end);
					$month = $interval->m;
					$days = $interval->d;
					$years  = $interval->y;
					if ($years >= 3){
						$exp = "<span class='text-success'><b>".($years != "" ? $years : "0")." Year/s, ".($month != "" ? $month : "0")." Month/s and ".($days != "" ? $days : "0")." Day/s </b></span>";
					}else{
						$exp = ($years != "" ? $years : "0")." Year/s, ".($month != "" ? $month : "0")." Month/s and ".($days != "" ? $days : "0")." Day/s";
					}	
					$sub_array[] = $exp; // exp
				    $data[] = $sub_array;	
					
				}
				
			}
			// var_dump($count);
        $output = array(  
            "draw"                  =>     intval($_POST["draw"]),  
            "recordsTotal"          =>     $this->Collections->get_all_data(),  
            "recordsFiltered"     	=>     $this->Collections->get_filtered_data(),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
    }

	public function fetchDashboard(){
		$data = array();
		$ret = new Collections();
		$data['payroll'] = $ret->getPayrollStatistics();
		echo json_encode($data);
	}
	
	public function fetchDashboardEmployees(){
		$data = array();
		$ret = new Collections();
		$data['total_employees'] = $ret->getTotalEmployees();
		echo json_encode($data);
	}
	public function fetchDashboardApprovals(){
		$data = array();
		$ret = new Collections();
		$data['total_cto'] = $ret->getTotalCTO();
		$data['total_leave'] = $ret->getTotalLeave();
		$data['total_locator'] = $ret->getTotalLocator();
		$data['total_to'] = $ret->getTotalTO();
		$data['total_pending_cto'] = count($ret->fetchTotalPendingCTO());
		$data['total_pending_leave'] = count($ret->fetchTotalPendingLeave());
		$data['total_pending_ob'] = count($ret->fetchTotalPendingOB());
		$data['total_pending_to'] = count($ret->fetchTotalPendingTO());
		$data['total_approved_cto'] = count($ret->fetchTotalApprovedCTO());
		$data['total_approved_leave'] = count($ret->fetchTotalApprovedLeave());
		$data['total_approved_ob'] = count($ret->fetchTotalApprovedOB());
		$data['total_approved_to'] = count($ret->fetchTotalApprovedTO());
		echo json_encode($data);
	}
	public function DonwloadMobileApp(){
		$data = array();
		$viewData['table'] = $this->load->view("helpers/downloadapplist.php",'',TRUE); 
		$data['form'] = $viewData['table'];
		
		echo json_encode($data);
	}

	public function fetchYrService(){
		$data = array();
		$viewData['table'] = $this->load->view("helpers/promotionlist.php",'',TRUE); 
		$data['form'] = $viewData['table'];		
		echo json_encode($data);
	}

	public function fetchPending(){		
		$data = array();
		$viewData['table'] = $this->load->view("helpers/totalapplicationslist.php",'',TRUE); 
		$data['form'] = $viewData['table'];		
		echo json_encode($data);
	}
	public function fetchApproved(){		
		$data = array();
		$viewData['table'] = $this->load->view("helpers/approvedlist.php",'',TRUE); 
		$data['form'] = $viewData['table'];		
		echo json_encode($data);
	}
	
	function fetchRowsPendingCTO(){
		$data = array();
		$ret =  new Collections();
		$ret = $ret->make_datatables_pending_cto();
		$count = $_POST['start'];
		foreach($ret as $k => $row){  
			$count++;
			$buttons_action = "";
			$buttons_data = "";

			$row->employee_number = (isset($row->employee_number))?$this->Helper->decrypt($row->employee_number,$row->emp_id):"";
			$row->employee_id_number = (isset($row->employee_id_number))?$this->Helper->decrypt($row->employee_id_number,$row->emp_id):"";
			$row->extension = (isset($row->extension))?$this->Helper->decrypt($row->extension,$row->emp_id):"";
			$row->first_name = (isset($row->first_name))?$this->Helper->decrypt($row->first_name,$row->emp_id):"";
			$row->middle_name = (isset($row->middle_name))?$this->Helper->decrypt($row->middle_name,$row->emp_id):"";
			$row->last_name = (isset($row->last_name))?$this->Helper->decrypt($row->last_name,$row->emp_id):"";
			
			if($row->status == 5) $status_color = "text-success";
            else if($row->status == 1 || $row->status == 2 || $row->status == 3) $status_color = "text-warning";
			else if($row->status == 4) $status_color = "text-info";
			else $status_color = "text-danger";

			$sub_array = array();    
			$sub_array[] = $count;
			$sub_array[] = $row->last_name.', '.$row->first_name." ".$row->middle_name;
			$sub_array[] = $row->date_filed;
			$sub_array[] = $row->inclusive_dates;
			$sub_array[] = '<b><span class="'.$status_color.'">'.$row->type.'</span><b>';
			$data[] = $sub_array;	
			
		}
        $output = array(  
            "draw"                  =>     intval($_POST["draw"]),  
            "recordsTotal"          =>     $this->Collections->get_all_data_pending_cto(),  
            "recordsFiltered"     	=>     $this->Collections->get_filtered_data_pending_cto(),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
    }

	function fetchRowsPendingLeave(){
		$data = array();
		$ret =  new Collections();
		$ret = $ret->make_datatables_pending_leave();
		$count = $_POST['start'];
		foreach($ret as $k => $row){  
			$count++;
			$buttons_action = "";
			$buttons_data = "";

			$row->employee_number = (isset($row->employee_number))?$this->Helper->decrypt($row->employee_number,$row->emp_id):"";
			$row->employee_id_number = (isset($row->employee_id_number))?$this->Helper->decrypt($row->employee_id_number,$row->emp_id):"";
			$row->extension = (isset($row->extension))?$this->Helper->decrypt($row->extension,$row->emp_id):"";
			$row->first_name = (isset($row->first_name))?$this->Helper->decrypt($row->first_name,$row->emp_id):"";
			$row->middle_name = (isset($row->middle_name))?$this->Helper->decrypt($row->middle_name,$row->emp_id):"";
			$row->last_name = (isset($row->last_name))?$this->Helper->decrypt($row->last_name,$row->emp_id):"";
			
			if($row->status == 5) $status_color = "text-success";
            else if($row->status == 1 || $row->status == 2 || $row->status == 3) $status_color = "text-warning";
			else if($row->status == 4) $status_color = "text-info";
			else $status_color = "text-danger";

			// $supervisor = false;
			// $division_head = false;
			// $deputy = false;

			// $ret =  new Collections();
			// if($ret->fetchLeaveApprovals($row->employee_id)) {
			// 	$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
			// 	$approvers = json_decode($res,true);

			// 	if($approvers['Code'] == "0"){
			// 		$app = $approvers['Data']['approvers'];

			// 		foreach ($app as $k => $v) {
			// 			$id = $v['id'];
			// 			$approve_type = $v['approve_type'];

			// 			if($approve_type == "2"){
			// 				$supervisor = true;
			// 			}
			// 			if($approve_type == "3"){
			// 				$division_head = true;
			// 			}

			// 			if($approve_type == "8"){
			// 				$deputy = true;
			// 			}
			// 		}
			// 	}
			// }
			
			// if($division_head && !$deputy){
			// 	if($row->division_head > 0): //LEAVE_RECOMMEND
			// 		if($row->status == 2){
			// 			$row->type = "FOR RECOMMENDATION (Division Hea1d)";
			// 		}
			// 	endif;
			// }else if(!$division_head && $deputy){
			// 	if($row->deputy > 0): //LEAVE_RECOMMEND
			// 		if($row->status == 2){
			// 			$row->type = "FOR RECOMMENDATION (Deputy)";
			// 		}
			// 	endif;
			// }

			$sub_array = array();    
			$sub_array[] = $count;
			$sub_array[] = $row->last_name.', '.$row->first_name." ".$row->middle_name;
			$sub_array[] = $row->date_filed;
			$sub_array[] = $row->inclusive_dates;
			$sub_array[] = '<b><span class="'.$status_color.'">'.$row->type.'</span><b>';
			$data[] = $sub_array;	
			
		}
        $output = array(  
            "draw"                  =>     intval($_POST["draw"]),  
            "recordsTotal"          =>     $this->Collections->get_all_data_pending_leave(),  
            "recordsFiltered"     	=>     $this->Collections->get_filtered_data_pending_leave(),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
    }
	
	function fetchRowsPendingOB(){
		$data = array();
		$ret =  new Collections();
		$ret = $ret->make_datatables_pending_ob();
		$count = $_POST['start'];
		foreach($ret as $k => $row){  
			$count++;
			$buttons_action = "";
			$buttons_data = "";

			$row->employee_number = (isset($row->employee_number))?$this->Helper->decrypt($row->employee_number,$row->emp_id):"";
			$row->employee_id_number = (isset($row->employee_id_number))?$this->Helper->decrypt($row->employee_id_number,$row->emp_id):"";
			$row->extension = (isset($row->extension))?$this->Helper->decrypt($row->extension,$row->emp_id):"";
			$row->first_name = (isset($row->first_name))?$this->Helper->decrypt($row->first_name,$row->emp_id):"";
			$row->middle_name = (isset($row->middle_name))?$this->Helper->decrypt($row->middle_name,$row->emp_id):"";
			$row->last_name = (isset($row->last_name))?$this->Helper->decrypt($row->last_name,$row->emp_id):"";

			// if($row->status == "COMPLETED" || $row->status == "APPROVED") $status_color = "text-success";
            // else if($row->status == "PENDING") $status_color = "text-warning";
			// else if($row->status == "FOR APPROVAL") $status_color = "text-info";
			// else $status_color = "text-danger";

			switch($row->status) {
				case "PENDING" :	$status_color = "text-warning";$row->status = "FOR RECOMMENDATION (Supervisor)";
									break;
				case "FOR APPROVAL" :	$status_color = "text-warning";$row->status = "FOR APPROVAL";
									break;
				case "APPROVED" :	$status_color = "text-success";$row->status = "APPROVED<br><small> For assigning driver and vehicle </small>";
									break;
				case "REJECTED" :	$status_color = "text-danger";$row->status = "DISAPPROVED";
									break;
				case "CANCELLED" :	$status_color = "text-danger";$row->status = "CANCELLED";
									break;
				case "COMPLETED" :	$status_color = "text-success";$row->status = "COMPLETED";
									break;
				default : 	$status_color = "text-default";
									break;
			}

			$sub_array = array();    
			$sub_array[] = $count;
			$sub_array[] = $row->last_name.', '.$row->first_name." ".$row->middle_name;
			$sub_array[] = date("Y-m-d", strtotime($row->filing_date));
			if($row->transaction_date_end != "0000-00-00") {
				$sub_array[] = $row->transaction_date.' - '.$row->transaction_date_end;
			}else{
				$sub_array[] = $row->transaction_date;
			}
			$sub_array[] = '<b><span class="'.$status_color.'">'.$row->status.'</span><b>';
			$data[] = $sub_array;	
			
		}
        $output = array(  
            "draw"                  =>     intval($_POST["draw"]),  
            "recordsTotal"          =>     $this->Collections->get_all_data_pending_ob(),  
            "recordsFiltered"     	=>     $this->Collections->get_filtered_data_pending_ob(),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
    }
	
	function fetchRowsPendingTO(){
		$data = array();
		$ret =  new Collections();
		$ret = $ret->make_datatables_pending_to();
		$count = $_POST['start'];
		foreach($ret as $k => $row){  
			$count++;
			$buttons_action = "";
			$buttons_data = "";

			$row->employee_number = (isset($row->employee_number))?$this->Helper->decrypt($row->employee_number,$row->emp_id):"";
			$row->employee_id_number = (isset($row->employee_id_number))?$this->Helper->decrypt($row->employee_id_number,$row->emp_id):"";
			$row->extension = (isset($row->extension))?$this->Helper->decrypt($row->extension,$row->emp_id):"";
			$row->first_name = (isset($row->first_name))?$this->Helper->decrypt($row->first_name,$row->emp_id):"";
			$row->middle_name = (isset($row->middle_name))?$this->Helper->decrypt($row->middle_name,$row->emp_id):"";
			$row->last_name = (isset($row->last_name))?$this->Helper->decrypt($row->last_name,$row->emp_id):"";

			if($row->status == 4 || $row->status == 5) $status_color = "text-success";
            else if($row->status == 0 || $row->status == 1 || $row->status == 2) $status_color = "text-warning";
			else if($row->status == 3) $status_color = "text-info";
			else $status_color = "text-danger";

			$sub_array = array();    
			$sub_array[] = $count;
			$sub_array[] = $row->last_name.', '.$row->first_name." ".$row->middle_name;
			$sub_array[] = date("Y-m-d", strtotime($row->date_created));
			$sub_array[] = $row->duration;
			$sub_array[] = '<b><span class="'.$status_color.'">'.$row->type.'</span><b>';
			$data[] = $sub_array;	
			
		}
        $output = array(  
            "draw"                  =>     intval($_POST["draw"]),  
            "recordsTotal"          =>     $this->Collections->get_all_data_pending_to(),  
            "recordsFiltered"     	=>     $this->Collections->get_filtered_data_pending_to(),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
    }
	function fetchRowsApprovedCTO(){
		$data = array();
		$ret =  new Collections();
		$ret = $ret->make_datatables_approved_cto();
		$count = $_POST['start'];
		foreach($ret as $k => $row){  
			$count++;
			$buttons_action = "";
			$buttons_data = "";

			$row->employee_number = (isset($row->employee_number))?$this->Helper->decrypt($row->employee_number,$row->emp_id):"";
			$row->employee_id_number = (isset($row->employee_id_number))?$this->Helper->decrypt($row->employee_id_number,$row->emp_id):"";
			$row->extension = (isset($row->extension))?$this->Helper->decrypt($row->extension,$row->emp_id):"";
			$row->first_name = (isset($row->first_name))?$this->Helper->decrypt($row->first_name,$row->emp_id):"";
			$row->middle_name = (isset($row->middle_name))?$this->Helper->decrypt($row->middle_name,$row->emp_id):"";
			$row->last_name = (isset($row->last_name))?$this->Helper->decrypt($row->last_name,$row->emp_id):"";

			$sub_array = array();    
			$sub_array[] = $count;
			$sub_array[] = $row->last_name.', '.$row->first_name." ".$row->middle_name;
			$sub_array[] = $row->date_filed;
			$sub_array[] = $row->inclusive_dates;
			$sub_array[] = '<b><span class="text-success">'.$row->type.'</span><b>';
			$data[] = $sub_array;	
			
		}
        $output = array(  
            "draw"                  =>     intval($_POST["draw"]),  
            "recordsTotal"          =>     $this->Collections->get_all_data_approved_cto(),  
            "recordsFiltered"     	=>     $this->Collections->get_filtered_data_approved_cto(),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
    }

	function fetchRowsApprovedLeave(){
		$data = array();
		$ret =  new Collections();
		$ret = $ret->make_datatables_approved_leave();
		$count = $_POST['start'];
		foreach($ret as $k => $row){  
			$count++;
			$buttons_action = "";
			$buttons_data = "";

			$row->employee_number = (isset($row->employee_number))?$this->Helper->decrypt($row->employee_number,$row->emp_id):"";
			$row->employee_id_number = (isset($row->employee_id_number))?$this->Helper->decrypt($row->employee_id_number,$row->emp_id):"";
			$row->extension = (isset($row->extension))?$this->Helper->decrypt($row->extension,$row->emp_id):"";
			$row->first_name = (isset($row->first_name))?$this->Helper->decrypt($row->first_name,$row->emp_id):"";
			$row->middle_name = (isset($row->middle_name))?$this->Helper->decrypt($row->middle_name,$row->emp_id):"";
			$row->last_name = (isset($row->last_name))?$this->Helper->decrypt($row->last_name,$row->emp_id):"";

			$sub_array = array();    
			$sub_array[] = $count;
			$sub_array[] = $row->last_name.', '.$row->first_name." ".$row->middle_name;
			$sub_array[] = $row->date_filed;
			$sub_array[] = $row->inclusive_dates;
			$row->type = ($row->type == 'FOR ASSIGNING DRIVER AND VEHICLE') ? $row->type='APPROVED' : "";
			$sub_array[] = '<b><span class="text-success">'.$row->type.'</span><b>';
			$data[] = $sub_array;	
			
		}
        $output = array(  
            "draw"                  =>     intval($_POST["draw"]),  
            "recordsTotal"          =>     $this->Collections->get_all_data_approved_leave(),  
            "recordsFiltered"     	=>     $this->Collections->get_filtered_data_approved_leave(),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
    }
	
	function fetchRowsApprovedOB(){
		$data = array();
		$ret =  new Collections();
		$ret = $ret->make_datatables_approved_ob();
		$count = $_POST['start'];
		foreach($ret as $k => $row){  
			$count++;
			$buttons_action = "";
			$buttons_data = "";

			$row->employee_number = (isset($row->employee_number))?$this->Helper->decrypt($row->employee_number,$row->emp_id):"";
			$row->employee_id_number = (isset($row->employee_id_number))?$this->Helper->decrypt($row->employee_id_number,$row->emp_id):"";
			$row->extension = (isset($row->extension))?$this->Helper->decrypt($row->extension,$row->emp_id):"";
			$row->first_name = (isset($row->first_name))?$this->Helper->decrypt($row->first_name,$row->emp_id):"";
			$row->middle_name = (isset($row->middle_name))?$this->Helper->decrypt($row->middle_name,$row->emp_id):"";
			$row->last_name = (isset($row->last_name))?$this->Helper->decrypt($row->last_name,$row->emp_id):"";

			$sub_array = array();    
			$sub_array[] = $count;
			$sub_array[] = $row->last_name.', '.$row->first_name." ".$row->middle_name;
			$sub_array[] = date("Y-m-d", strtotime($row->filing_date));
			if($row->transaction_date_end != "0000-00-00") {
				$sub_array[] = $row->transaction_date.' - '.$row->transaction_date_end;
			}else{
				$sub_array[] = $row->transaction_date;
			}
			$sub_array[] = '<b><span class="text-success">'.$row->status.'</span><b>';
			$data[] = $sub_array;	
			
		}
        $output = array(  
            "draw"                  =>     intval($_POST["draw"]),  
            "recordsTotal"          =>     $this->Collections->get_all_data_approved_ob(),  
            "recordsFiltered"     	=>     $this->Collections->get_filtered_data_approved_ob(),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
    }
	
	function fetchRowsApprovedTO(){
		$data = array();
		$ret =  new Collections();
		$ret = $ret->make_datatables_approved_to();
		$count = $_POST['start'];
		foreach($ret as $k => $row){  
			$count++;
			$buttons_action = "";
			$buttons_data = "";

			$row->employee_number = (isset($row->employee_number))?$this->Helper->decrypt($row->employee_number,$row->emp_id):"";
			$row->employee_id_number = (isset($row->employee_id_number))?$this->Helper->decrypt($row->employee_id_number,$row->emp_id):"";
			$row->extension = (isset($row->extension))?$this->Helper->decrypt($row->extension,$row->emp_id):"";
			$row->first_name = (isset($row->first_name))?$this->Helper->decrypt($row->first_name,$row->emp_id):"";
			$row->middle_name = (isset($row->middle_name))?$this->Helper->decrypt($row->middle_name,$row->emp_id):"";
			$row->last_name = (isset($row->last_name))?$this->Helper->decrypt($row->last_name,$row->emp_id):"";

			$sub_array = array();    
			$sub_array[] = $count;
			$sub_array[] = $row->last_name.', '.$row->first_name." ".$row->middle_name;
			$sub_array[] = date("Y-m-d", strtotime($row->date_created));
			$sub_array[] = $row->duration;
			$sub_array[] = '<b><span class="text-success">'.$row->type.'</span><b>';
			$data[] = $sub_array;	
			
		}
        $output = array(  
            "draw"                  =>     intval($_POST["draw"]),  
            "recordsTotal"          =>     $this->Collections->get_all_data_approved_to(),  
            "recordsFiltered"     	=>     $this->Collections->get_filtered_data_approved_to(),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
    }
}
?>