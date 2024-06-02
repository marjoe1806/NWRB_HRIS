<?php

class SpecialPayrollUpdates extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('SpecialPayrollUpdatesCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		$listData = array();
		$viewData = array();
		$page = "viewSpecialPayrollUpdates";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/specialpayrolllist_updates",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Bonus Updates');
			Helper::setMenu('templates/menu_template');
			Helper::setView('specialpayroll',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		}
		else{
			$result['key'] = $listData['key'];
			$result['table'] = $viewData['table'];
			echo json_encode($result);
		}
	}
	function fetchRows(){ 
        $fetch_data = $this->SpecialPayrollUpdatesCollection->make_datatables();  
        $data = array(); 
        foreach($fetch_data as $k => $row)  
        {  
        	$buttons = "";
        	$buttons_data = "";
        	$row->first_name = $this->Helper->decrypt($row->first_name,$row->employee_id);
        	$row->middle_name = $this->Helper->decrypt($row->middle_name,$row->employee_id);
        	$row->last_name = $this->Helper->decrypt($row->last_name,$row->employee_id);
            $sub_array = array();
            $sub_array[] = $row->rowNumber;
            $fullname = $row->last_name.", ".$row->first_name;
            if($row->middle_name != null)
            	$fullname .= " ".$row->middle_name[0].".";
            $sub_array[] = $fullname;
            $sub_array[] = $row->department_name;
            // switch($row->bonus_type) {
            //     case "Anniversary"  :   
            //         $bonus_type = "Anniversary Bonus";
            //         break;
            //     case "PBB"  :   
            //         $bonus_type = "PBB";
            //         break;
            //     case "MidYear"  :   
            //         $bonus_type = "Mid Year Bonus";
            //         break;
            //     case "YearEnd"  :   
            //         $bonus_type = "Year End Bonus";
            //         break;
            //     case "ProductivityIncentive"    :   
            //         $bonus_type = "Productivity Incentive Bonus";
            //         break;
            //     case "PerformanceIncentive" :   
            //         $bonus_type = "Performance Incentive Bonus";
            //         break;
            //     case "WSPEI"    :   
            //         $bonus_type = "Whole Salary P.E.I. Bonus";
            //         break;
            //     case "PEI"  :   
            //         $bonus_type = "P.E.I. Bonus";
            //         break;
            //     case "Clothing" :   
            //         $bonus_type = "Clothing Allowance";
            //         break;
            //     case "CNA"  :   
            //         $bonus_type = "CNA";
            //         break;
            //     default :
            //         //do nothing
            //         break;
            // }
            $sub_array[] = $row->bonus_type;
            $sub_array[] = number_format($row->amount,2);
            $sub_array[] = $row->cash_gift;
            $sub_array[] = $row->in_kind;
            $sub_array[] = $row->union_fees;
            $sub_array[] = $row->year;
            $status_color = "text-danger";
            if($row->is_active == "1"){
            	$status_color = "text-success";
            	$status = "ACTIVE";
            }
            else{
            	$status_color = "text-danger";
            	$status = "INACTIVE";
            }
            $sub_array[] = '<b><span class="'.$status_color.'">'.$status.'</span><b>';
            $sub_array[] = $row->username;
            $sub_array[] = date("F d, Y h:ia", strtotime($row->date_created));
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"                  =>     intval($_POST["draw"]),  
            "recordsTotal"          =>      $this->SpecialPayrollUpdatesCollection->get_all_data(),  
            "recordsFiltered"     	=>     $this->SpecialPayrollUpdatesCollection->get_filtered_data(),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
    }
}

?>