<?php

class EmployeeTracking extends MX_Controller {
	
	public function __construct() {
		parent::__construct();		
		$this->load->model('Helper');
		$this->load->model('ModuleRels');
		$this->load->model('EmployeeTrackingCollections');
		$this->load->module('session');
		Helper::sessionEndedHook('session');
	}
	
	public function index() {		
		Helper::setTitle('Employee Tracking | MMDA - PAYROLL');	

		Helper::setMenu('templates/menu_template');
		Helper::setView('employeetracking','',FALSE);
		Helper::setTemplate('templates/blank_template');
		Session::checksession();
	}
	public function map() {		
		Helper::setTitle('Employee Tracking | MMDA - PAYROLL');	

		Helper::setMenu('templates/menu_template');
		Helper::setView('home_map','',FALSE);
		Helper::setTemplate('templates/blank_template');
		Session::checksession();
	}
	public function viewAttendingEmployees(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewAttendingEmployees';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('helpers/employeelist.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	function fetchEmployeeRows(){ 
        $fetch_data = $this->EmployeeTrackingCollections->make_datatables();  
        $data = array();  
        foreach($fetch_data as $k => $row)  
        {  
        	
        	$row->employee_number = $this->Helper->decrypt($row->employee_number,$row->id);
        	$row->employee_id_number = $this->Helper->decrypt($row->employee_id_number,$row->id);
        	$row->first_name = $this->Helper->decrypt($row->first_name,$row->id);
        	$row->middle_name = $this->Helper->decrypt($row->middle_name,$row->id);
        	$row->last_name = $this->Helper->decrypt($row->last_name,$row->id);
            $sub_array = array();    
            $sub_array[] = $row->employee_id_number;
            $sub_array[] = $row->first_name.' '.$row->middle_name.' '.$row->last_name;
            $sub_array[] = date("h:i:s",strtotime($row->time_in));
            $sub_array[] = date("h:i:s",strtotime($row->time_out));
            $sub_array[] = $row->location_name;
            $sub_array[] = $row->source_device;
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"                  =>     intval($_POST["draw"]),  
            "recordsTotal"          =>      $this->EmployeeTrackingCollections->get_all_data(),  
            "recordsFiltered"     	=>     $this->EmployeeTrackingCollections->get_filtered_data(),  
            "data"                  =>     $data  
        );  
        echo json_encode($output); 
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
	public function fetchDashboard(){
		$data = array();
		$ret = new EmployeeTrackingCollections();
		$data['total_employees'] = $ret->getTotalEmployees();
		$data['total_job_orders'] = $ret->getTotalEmployeesByContract("JOB ORDER");
		$data['total_contractuals'] = $ret->getTotalEmployeesByContract("CONTRACTUAL");
		$data['total_permanents'] = $ret->getTotalEmployeesByContract("PERMANENT");	
		$data['payroll'] = $ret->getPayrollStatistics();
		echo json_encode($data);
	}
	public function getActiveLocations(){
		$result = array();
		$page = 'getActiveLocations';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$ret =  new EmployeeTrackingCollections();
			if($ret->hasRowsLocations()) {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
			} else {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			}
			$result = json_decode($res,true);
			
			$result['key'] = $page;
		}
		echo json_encode($result);
	} 
	public function getEmployeeCounts(){
		$result = array();
		$page = 'getActiveLocations';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$lat1 = isset($_POST['lat1'])?$_POST['lat1']:"";
			$lat2 = isset($_POST['lat2'])?$_POST['lat2']:"";
			$long1 = isset($_POST['long1'])?$_POST['long1']:"";
			$long2 = isset($_POST['long2'])?$_POST['long2']:"";
			$ret =  new EmployeeTrackingCollections();
			if($ret->getTotalEmployeesByLocations($lat1,$lat2,$long1,$long2)) {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
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