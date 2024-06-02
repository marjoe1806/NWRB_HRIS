<?php

class ProcessPayroll extends MX_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('ProcessPayrollCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::PROCESS_PAYROLL_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewProcessPayroll";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/processpayrolllist",$listData,TRUE);
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Payroll Processing');
			Helper::setMenu('templates/menu_template');
			Helper::setView('processpayroll',$viewData,FALSE);
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
        $fetch_data = $this->ProcessPayrollCollection->make_datatables();
        $data = array();

        foreach($fetch_data as $k => $row)
        {
        	$buttons = "";
        	$buttons_data = "";
        	$row->employee_number = $this->Helper->decrypt($row->employee_number,$row->employee_id);
        	$row->employee_id_number = (isset($row->employee_id_number))?$this->Helper->decrypt($row->employee_id_number,$row->id):"";
        	$row->first_name = $this->Helper->decrypt($row->first_name,$row->employee_id);
        	$row->middle_name = $this->Helper->decrypt($row->middle_name,$row->employee_id);
        	$row->last_name = $this->Helper->decrypt($row->last_name,$row->employee_id);
        	$row->extension = $this->Helper->decrypt($row->extension,$row->employee_id);
            $sub_array = array();
           	//Is approved

            //Status
            /*$status_color = "text-danger";
            if($row->is_active == "1"){
            	$status_color = "text-success";
            	$status = "ACTIVE";
            }
            else{
            	$status_color = "text-danger";
            	$status = "INACTIVE";
            }
            $sub_array[] = '<b><span class="'.$status_color.'">'.$status.'</span><b>';*/

            /*'<a  id="updateProcessPayrollForm"
                class="updateProcessPayrollForm btn btn-info btn-circle waves-effect waves-circle waves-float"
                href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/updateProcessPayrollForm'"  data-toggle="tooltip" data-placement="top" title="Update"
                data-id="'. $value->id.'"
                foreach ($value as $k => $v) {
                    ' data-'.$k.'="'.$v.'" ';
                } "
            >'

                <i class="material-icons">mode_edit</i>
            </a>*/
            foreach($row as $k1=>$v1){
            	$buttons_data .= ' data-'.$k1.'="'.$v1.'" ';
            }
            $payroll_period_id = isset($_GET['PayrollPeriod'])?$_GET['PayrollPeriod']:"";
            $buttons_data .= ' data-payroll_period_id="'.$payroll_period_id.'" ';
            $buttons_data .= ' data-extension="'.$row->extension.'" ';
            $buttons .= ' <a id="viewProcessPayrollForm" '
            		  . ' class="viewProcessPayrollForm" style="text-decoration: none;" '
            		  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/viewProcessPayrollForm" '
            		  . $buttons_data
            		  . ' > '
            		  . ' <button class="btn btn-info btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="View Payroll">'
            		  . ' <i class="material-icons">remove_red_eye</i> '
            		  . ' </button> '
            		  . ' </a> ';
           	//for testing only
            		//   $buttons .= ' <a id="viewPayrollForm" '
            		//   . ' class="viewPayrollForm" style="text-decoration: none;" '
            		//   . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/viewPayrollForm" '
            		//   . $buttons_data
            		//   . ' > '
            		//   . ' <button class="btn btn-default btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="View Payroll">'
            		//   . ' <i class="material-icons">remove_red_eye</i> '
            		//   . ' </button> '
            		//   . ' </a> ';
            /*if($row->is_active == "1"){
	            $buttons .= ' <a '
	            		  . ' class="deactivateProcessPayroll btn btn-danger btn-circle waves-effect waves-circle waves-float" '
	            		  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/deactivateProcessPayroll" '
	            		  . ' data-toggle="tooltip" data-placement="top" title="Deactivate" '
	            		  . ' data-id='.$row->id.' '
	            		  . ' > '
	            		  . ' <i class="material-icons">do_not_disturb</i> '
	            		  . ' </a> ';
	        }
	        else{
	        	$buttons .= ' <a '
	        			  . ' class="activateProcessPayroll btn btn-success btn-circle waves-effect waves-circle waves-float" '
	        			  . ' href="'. base_url().$this->uri->segment(1).'/'.$this->uri->segment(2).'/activateProcessPayroll" '
	        			  . ' data-toggle="tooltip" data-placement="top" title="Activate" '
	        			  . ' data-id='.$row->id.' '
	        			  . ' > '
	        			  . ' <i class="material-icons">done</i> '
	        			  . ' </a> ';
	        } */
	        $sub_array[] = $buttons;
            $sub_array[] = $row->employee_id_number;
            $sub_array[] = $row->last_name.', '.$row->first_name.' '.$row->middle_name.' '.$row->extension;
            $sub_array[] = $row->position_name;
            $sub_array[] = $row->department_name;
            $sub_array[] = number_format($row->salary,2);
            $sub_array[] = $row->contract;
            $data[] = $sub_array;
        }
        $output = array(
            "draw"                  =>     intval($_POST["draw"]),
            "recordsTotal"          =>      $this->ProcessPayrollCollection->get_all_data(),
            "recordsFiltered"     	=>     $this->ProcessPayrollCollection->get_filtered_data(),
            "data"                  =>     $data
        );
        echo json_encode($output);
    }
	public function viewProcessPayrollForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewProcessPayroll';
		if (!$this->input->is_ajax_request()) show_404();
		else{
			$ret =  new ProcessPayrollCollection();
			//Computations for Payroll
			$employee_id = isset($_POST['employee_id'])?$_POST['employee_id']:"";
			$payroll_period_id = isset($_POST['payroll_period_id'])?$_POST['payroll_period_id']:"";
			$formData['key'] = $result['key'];
			$formData['allowances'] = $ret->getAmortizedAllowances($employee_id,$payroll_period_id);
			$formData['otherBenefits'] = $ret->getAmortizedOtherBenefits($employee_id,$payroll_period_id);
			$formData['otherEarnings'] = $ret->getAmortizedOtherEarnings($employee_id,$payroll_period_id);
			$formData['otherDeductions'] = $ret->getAmortizedOtherDeductions($employee_id,$payroll_period_id);
			$formData['undertimeAndOvertime'] = $ret->getUTV2($employee_id,$payroll_period_id);
			$formData['ut'] = $ret->getUTV2($employee_id,$payroll_period_id);
			$formData['loanDeductions'] = $ret->getAmortizedLoans($employee_id,$payroll_period_id);
			$formData['payrollData'] = $ret->deductions($employee_id, $payroll_period_id);
			$formData['tax'] = $ret->get_witHoldingTax($employee_id, $payroll_period_id);
			//var_dump($formData['payrollData']);die();
			// $formData['payrollinfo'] = $ret->payrollinfo($employee_id);
			$result['form'] = $this->load->view('forms/processpayrollform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function viewPayrollForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewProcessPayroll';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{

			$ret =  new ProcessPayrollCollection();
			//Computations for Payroll
			$employee_id = isset($_POST['employee_id'])?$_POST['employee_id']:"";
			$payroll_period_id = isset($_POST['payroll_period_id'])?$_POST['payroll_period_id']:"";
			$formData['key'] = $result['key'];
			$formData['annualSalary'] = $ret->getAnnualSalary($employee_id,$payroll_period_id);
			$formData['annualPagibig'] = $ret->getAnnualPagibig($employee_id,$payroll_period_id);
			$formData['annualPhilhealth'] = $ret->getAnnualPhilhealth($employee_id,$payroll_period_id);
			$formData['annualGSIS'] = $ret->getAnnualGSIS($employee_id,$payroll_period_id);
			$formData['annualUnionDues'] = $ret->getAnnualUnionDues($employee_id,$payroll_period_id);
			$formData['annualTaxDue'] = $ret->getAnnualTaxDue($employee_id,$payroll_period_id);
			
			$formData['releasedBonus'] = $ret->getReleasedBonus($employee_id,$payroll_period_id);
			$formData['nonTaxableReleasedBonus'] = $ret->getNonTaxableReleasedBonus($employee_id,$payroll_period_id);
			$formData['allowances'] = $ret->getAmortizedAllowances($employee_id,$payroll_period_id);
			$formData['otherEarnings'] = $ret->getAmortizedOtherEarnings($employee_id,$payroll_period_id);
			$formData['otherDeductions'] = $ret->getAmortizedOtherDeductions($employee_id,$payroll_period_id);
			$formData['undertimeAndOvertime'] = $ret->getUndertimeAndOvertime($employee_id,$payroll_period_id);
			$formData['loanDeductions'] = $ret->getAmortizedLoans($employee_id,$payroll_period_id);
			$formData['datas'] = $ret->deductions($employee_id, $payroll_period_id);
			$result['form'] = $this->load->view('forms/payrollform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}
	public function computePayroll(){
		$ret =  new ProcessPayrollCollection();
		$page = "computePayroll";

		// for($i=0;$i<sizeof($_POST['employee_ids']);$i++) {
		// 	$result['undertimeAndOvertime'] = $this->computeUndertimeOvertime($employee_ids[$i], $employee_numbers[$i], $_POST['payroll_period_id'], $shift_ids[$i]);
		// }
		$payroll = $ret->getPayrollPeriodById($_POST["payroll_period_id"]);
		if(sizeof($payroll) > 0){
			$year = date("Y",strtotime($payroll[0]["payroll_period"]));
			$month = date("m",strtotime($payroll[0]["payroll_period"]));
		    //var_dump($month); die(); 
			// if($month != "01"){				
			// 	$month -= 1;
			// }
			//print_r($month); die(); 
			
			$_POST["period_year"] = $year;
			$_POST["period_month"] = $month;
			$no_days = $ret->calculateWorkingDaysInMonth($year,$month);
			$_POST["no_days"] = $no_days;
			//print_r($no_days); die(); 
			foreach($_POST["employee_ids"] as $k => $v){
				$get_employee_details = $ret->getEmployeeById($v);
				$employment_status = $get_employee_details[0]["employment_status"];
				$employee_id = $v;
				$pay_basis = $get_employee_details[0]["pay_basis"];
				$division_id = $get_employee_details[0]["division_id"];
				$location_id = $get_employee_details[0]["division_id"];
				$post_year = $_POST["period_year"];
				$date_of_permanent = $_POST["permanent_date"][$k];
				$present_day = $get_employee_details[0]["present_day"];
				$end_date = $get_employee_details[0]["end_date"];
				$start_date = $get_employee_details[0]["start_date"];
				$from_ledger = $this->generateLedgerData($employment_status,$employee_id,$pay_basis,$division_id,$location_id,$year,$post_year,$month,$date_of_permanent,$present_day, $end_date = "",$start_date);
				// print_r($from_ledger);
				if($month !== "01"){				
					$from_ledger_deduc = @$from_ledger["deductions"];
					$from_ledger = end($from_ledger[$year]);
					$_POST["wout_pay"][$k] = $from_ledger["vl_a_utime_wo_pay"];
					$_POST["process_absent"][$k] = @$from_ledger_deduc["process_absent"];
					$_POST["process_tardiness_hrs"][$k] = @$from_ledger_deduc["process_tardiness_hrs"];
					$_POST["process_tardiness_mins"][$k] = @$from_ledger_deduc["process_tardiness_mins"];
					$_POST["process_ut_hrs"][$k] = @$from_ledger_deduc["process_ut_hrs"];
					$_POST["process_ut_mins"][$k] = @$from_ledger_deduc["process_ut_mins"];
					$_POST["process_offset_days"][$k] = @$from_ledger_deduc["process_offset_days"];
					$_POST["process_offset_hrs"][$k] = @$from_ledger_deduc["process_offset_hrs"];
					$_POST["process_offset_mins"][$k] = @$from_ledger_deduc["process_offset_mins"];
					$_POST["process_leave_credits_used"][$k] = @$from_ledger_deduc["process_leave_credits_used"];
					$_POST["process_ut_conversion"][$k] = @$from_ledger_deduc["process_ut_conversion"];
				}else{
					$from_ledger_deduc = null;
					$from_ledger = end($from_ledger[$year]);
					$_POST["wout_pay"][$k] = 0;
					$_POST["process_absent"][$k] = 0;
					$_POST["process_tardiness_hrs"][$k] = 0;
					$_POST["process_tardiness_mins"][$k] = 0;
					$_POST["process_ut_hrs"][$k] = 0;
					$_POST["process_ut_mins"][$k] = 0;
					$_POST["process_offset_days"][$k] = 0;
					$_POST["process_offset_hrs"][$k] = 0;
					$_POST["process_offset_mins"][$k] = 0;
					$_POST["process_leave_credits_used"][$k] = 0;
					$_POST["process_ut_conversion"][$k] = 0;
				}
			}
		}
		
		if($ret->computePayrollProcess($_POST)) $res = new ModelResponse($ret->getCode(), $ret->getMessage());
		else $res = new ModelResponse($ret->getCode(), $ret->getMessage());

		$result = json_decode($res,true);
		$result['key'] = $page;
		$result['table'] = $this->load->view("helpers/processpayrolllist",array(),TRUE);
		echo json_encode($result);
	}
	public function getEmployeeByPayBasis(){
		$ret =  new ProcessPayrollCollection();
		//Computations for Payroll
		$page = "getEmployeeByPayBasis";
		if (!$this->input->is_ajax_request()) show_404();
		else {
			$pay_basis = isset($_GET['PayBasis'])?$_GET['PayBasis']:"";
			if($ret->hasRowsEmployeesByPayBasis($pay_basis)) {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
			} else {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			}
			$result = json_decode($res,true);
			$result['key'] = $page;
		}
		echo json_encode($result);
	}

	public function checkWeeklyPayrollPeriod() {
		if (!$this->input->is_ajax_request()) {
			show_404();
		}
		else {
			$model = new ProcessPayrollCollection();
			$result = $model->hasWeeklyPayrollPeriod($_GET['PayrollPeriodId']);
			echo $result;
		}
	}
	public function getLeaveCredits(){
		$result = array();
		$result['key'] = 'getLeaveCredits';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{

			$ret =  new ProcessPayrollCollection();
			$leave_credits = array();
			//Computations for Payroll
			$employee_id = isset($_POST['employee_id'])?$_POST['employee_id']:"";
			$payroll_period_id = isset($_POST['payroll_period_id'])?$_POST['payroll_period_id']:"";
			$payroll_period = $ret->getPayrollPeriodById($payroll_period_id);
			if(sizeof($payroll_period) > 0){
				$period = explode('-', $payroll_period[0]['payroll_period']);
				$month = $period[1];
				$year = $period[0];
				$leave_credits = $ret->getLeaveLedger($employee_id,$year,$month);
			}
			$result['data'] = $leave_credits;
			//Forms
		}
		echo json_encode($result);
	}
	public function convertTimeIntoFractions(){
		$result = array();
		$result['key'] = 'convertTimeIntoFractions';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{

			$ret =  new ProcessPayrollCollection();
			$total_equiv = 0;
			//Computations for Payroll
			$time_amount = isset($_POST['time'])?explode('.', $_POST['time']):array();
			if(sizeof($time_amount) > 1){
				$mins_equiv = $ret->getConversionFractions($time_amount[1],'min');
				if(sizeof($mins_equiv) > 0)
					$mins_eq = $mins_equiv[0]['equiv_day'];	
				else
					$mins_eq = 0;
			}
			else{
				$mins_eq = 0;
			}
				$hrs = $ret->getConversionFractions('1','hr');
				$hrs_equiv = $time_amount[0] * $hrs[0]['equiv_day']; 
				$total_equiv = $hrs_equiv + $mins_eq;
				$result['data'] = $total_equiv;
			//Forms
		}
		echo json_encode($result);
	}
	
	public function monetizeLeaveCredits(){
		$result = array();
		$page = 'monetizeLeaveCredits';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{

			$ret =  new ProcessPayrollCollection();
			$total_equiv = 0;
			$last_process = array();
			//Computations for Payroll
			$trans_id = isset($_POST['trans_id'])?$_POST['trans_id']:"";
			$employee_id = isset($_POST['employee_id'])?$_POST['employee_id']:"";
			$payroll_period_id = isset($_POST['payroll_period_id'])?$_POST['payroll_period_id']:"";
			$vacation_leave_hrs = isset($_POST['vacation_leave_hrs'])?$_POST['vacation_leave_hrs']:"";
			$vacation_leave_credits = isset($_POST['vacation_leave_credits'])?$_POST['vacation_leave_credits']:"";
			$sick_leave_hrs = isset($_POST['sick_leave_hrs'])?$_POST['sick_leave_hrs']:"";
			$sick_leave_credits = isset($_POST['sick_leave_credits'])?$_POST['sick_leave_credits']:"";

			$serv = $ret->addLeaveCreditsDeduction($trans_id,$employee_id,$payroll_period_id,$vacation_leave_hrs,$vacation_leave_credits,$sick_leave_hrs,$sick_leave_credits);
			if($serv) {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $ret->getData());
				$last_process = $ret->getLastProcess();
			} else {
				$res = new ModelResponse($ret->getCode(), $ret->getMessage());
			}
			$result = json_decode($res,true);
			$result['last_process'] = $last_process;
			$result['key'] = $page;
			//Forms
		}
		echo json_encode($result);
	}

	function countHollidayAttended($year, $month, $emp_shift_days, $employee_id) {
		$emp_shift_days = implode("','",$emp_shift_days);
		$query = $this->db->query("SELECT COUNT(*) as holiday_attended
			FROM tblfieldholidays AS h
			LEFT JOIN tbldtr AS a ON a.transaction_date = h.date
			LEFT JOIN tblemployees b ON CAST(a.scanning_no AS INT) = CAST(DECRYPTER(b.employee_number,'sunev8clt1234567890',b.id) AS INT)
			 WHERE DAYNAME(date) IN ('".$emp_shift_days."') AND MONTH(date) = '".$month."' AND YEAR(date) = '".$year."' AND (holiday_type != 'Suspension' OR (holiday_type = 'Suspension' && time_suspension = '08:00 AM'))
			AND b.id = '".$employee_id."'
			")->row_array();
		
		return $query["holiday_attended"];
	}

	public function generateLedgerData($employment_status,$employee_id,$pay_basis,$division_id,$location_id,$year,$post_year,$month,$date_of_permanent,$present_day, $end_date = "",$start_date){
		$ret =  new ProcessPayrollCollection();
		$month_codes = array("01","02","03","04","05","06","07","08","09","10","11","12");
		// if($year == 2021){
		// 	unset($month_codes[0]);
		// 	unset($month_codes[1]);
		// 	unset($month_codes[2]);
		// }
		$year_permanent = date("Y",strtotime($date_of_permanent));
		if($year_permanent > 2022) $year_range = range($year_permanent,$year);
		else $year_range = range(2022,$year);
		$year_range = range($year_permanent,$year);

		$total_vl_earned = 0;
		$total_sl_earned = 0;
		$total_balance = $ret->getLeaveBalance($employee_id, $year);
		if(sizeof($total_balance) > 0) {
			$total_vl_earned = @$total_balance[0]['vl'];
		    $total_sl_earned = @$total_balance[0]['sl'];
		}
		$ledger = array();
		// foreach($year_range as $yk => $year) {
			$current = strtotime($year.'-01-01');
	        $month_index = 0;
	        if(strtotime($date_of_permanent) > $current){
	        	// $month_of_permanent =(int) date('m',strtotime($date_of_permanent));
				// $month_index = $month_of_permanent - 1;
	        	// $total_vl_earned = 0;
				// $total_sl_earned = 0;
			}
			// $prev_curr_month = ((int)$month - 1);
			// $prev_curr_month = $month != 12 ? ((int)$month - 1):((int)$month);
			$selected_months = array();
			// for($i = $month_index; $i < $prev_curr_month; $i++){
			// // for($i = ($year == 2021) ? 3 : $month_index; $i < ((int) $month); $i++){
			// 	$selected_months[] = $month_codes[$i];
			// }
			for($i = $month_index; $i < ((int) $month); $i++){
				$selected_months[] = $month_codes[$i];
			}
			//Populate Leave Ledger
			
			$forced_count = 0;
			$first = true;
			$arrforce = $arrstudy = $arrmaternity = $arrsolo = $arrcalamity = $arrspecial = $arrrehab = $arrbenefits = $arrviolence = $arrmonet = $arrpaternity = $arrsick = $arrvacation = array();

			$terminal = $ret->getTerminal($year,$employee_id);
			$isTerminal = false;
			$dtfiled = "";
			if($terminal) {
				$isTerminal = true;
				$dtfiled = (int)date("m",strtotime($terminal['is_terminal']));
			}
			foreach ($selected_months as $key => $value) {
				if(($year.'-'.$value.'-01') < date("Y-m",strtotime($start_date)).'-01') continue;
				$total_leaves_per_month = $total_weekend_leaves_per_month = 0;
				$total_leaves_per_month_from_remarks = 0;
				$total_vl_from_monetized = $total_sl_from_monetized = $total_vl_force = 0;
				$haveVl = $haveSl = $total_vl = $total_sl = $time_suspension = 0;
				// if(isset($terminal) && (($dtfiled == (int)$value) && (int)date("Y",strtotime($terminal['date_filed'])) == $year)){
				// }else if(!$terminal || ($dtfiled > $value && date("Y",strtotime($terminal['date_filed'])) <= $year)){
					$tot_vl_leaves = $tot_sl_leaves = 0;
					//Monthly Earning Start
					$a_date = $year.'-'.$value.'-01';
					$z_date = date("t", strtotime($a_date));
					$monthly_data['period'] = date("M. t, Y", strtotime($a_date));
					$monthly_data['particulars'] = "";
					$monthly_data['vl_earned'] = 0;//1.250;
					$monthly_data['sl_earned'] = 0;//1.250;
					$isDTRRequired = $ret->isDTRRequired($employee_id);
					if($first){
						if(strtotime($date_of_permanent) > $current){
							$pemanent_day = date("d", strtotime($date_of_permanent));
							$monthly_data['period'] = date("M. t, Y", strtotime($a_date));
							$monthly_data['particulars'] = "";
							$permanent_date = str_replace('-', '', $date_of_permanent);
							$end_of_month = str_replace('-', '',date("Y-m-t", strtotime($date_of_permanent)));
							$days_of_present = $end_of_month - $permanent_date + 1;
	                        if($isDTRRequired){
	                        	if($isDTRRequired["is_dtr"] == 0){
	                        		$days_of_present = 22;
	                        	}
	                        }
							if($present_day == "half") $days_of_present -= .5;
							// $earned_conversion = $ret->getEarnedConversion($days_of_present);
							$earned_conversion = 0; // to have fixed 1.250 leave earned
							if(sizeof($earned_conversion) > 0){
								$monthly_data['vl_earned'] = $earned_conversion[0]['leave_credits_earned'];
								$monthly_data['sl_earned'] = $earned_conversion[0]['leave_credits_earned'];
							}
						}
					}
					
					// $ledger[$year][] = $monthly_data;
					
					$arrShiftSchedule = $arrShiftHistory = array();
					$shiftDetails = $ret->getShiftDetails($employee_id);
					$shiftType = $shiftId= 0;
					// shift date effectivity
					if(strtotime($year."-".$month."-31") >= strtotime(date("m",strtotime($shiftDetails["shift_date_effectivity"])))) {
						$shiftId = ($shiftDetails["regular_shift"] == 1) ? $shiftDetails["shift_id"] : $shiftDetails["flex_shift_id"];
						$shiftType = $shiftDetails["regular_shift"];
					}else{
						$arrShiftHistory = $ret->getShiftHistory($shiftDetails["id"]);
						foreach($arrShiftHistory as $k => $v){
							if($month >= date("m", strtotime($v["previous_date_effectivity"]))){
								$shiftId = $v["shift_id"];
								$shiftType = $v["shift_type"];
								break;
							}
						}
					}
					if($shiftType == 1) $arrShiftSchedule = $ret->getRegularShiftSchedule($shiftId);
					else $arrShiftSchedule = $ret->getFlexibleShiftSchedule($shiftId);

					$employee_working_days_schedule = array_column($arrShiftSchedule, 'week_day');
					//Monthly Earning End
					$get_working_days = $ret->countDays($year, $value, array(0, 6),$terminal,$employee_working_days_schedule);
					$holiday_attended = $ret->countHollidayAttended($year, $value,$employee_working_days_schedule,$employee_id);
					$ledger["deductions"]['working_days'] = $get_working_days;
					$tot_absent = $get_working_days;
					
					//UTime Posting Start
					$ut_time = $ret->getUndertTime($value,$employee_id,$year, $isTerminal, $dtfiled, @$terminal['is_terminal']);
					// $utime = $ret->getUTime($value,$employee_id,$year);
					$tot_ut_conversions = $tot_utime_mins = $tot_utime_hrs = $tot_utime_days = 0;
					$process_ut_conversion = $process_absent = $process_tardiness_hrs = $process_tardiness_mins = $process_ut_hrs = $process_ut_mins = $process_offset_days = $process_offset_hrs = $process_offset_mins = $process_leave_credits_used = 0;
					$tot_utime = "";
					$process_absent = $process_tardiness_hrs = $process_tardiness_mins = $process_ut_hrs = $process_ut_mins = $process_offset_days = $process_offset_hrs = $process_offset_mins = 0;
					
					if($ut_time["scanning_no"] != null){
						$tot_absent -= (abs($ut_time["no_days"]) + @$ut_time["offset_days"]);
						$tot_absent += $holiday_attended;
						
						// $tot_absent += @$ut_time["no_days_holiday"];
						$tot_absent = $tot_absent < 0 ? 0 : $tot_absent;
						$process_absent = $tot_absent;
						$tot_absent -= @$ut_time["offset_days"];
						$tot_utime_mins = fmod($ut_time["tardiness_mins"] + $ut_time["ut_mins"], 60);
						$tot_utime_hrs	= fmod(floor(($ut_time["tardiness_mins"] + $ut_time["ut_mins"])/60) + ($ut_time["tardiness_hrs"] + $ut_time["ut_hrs"]), 8);
						$tot_utime_days = floor((floor(($ut_time["tardiness_mins"] + $ut_time["ut_mins"])/60) + ($ut_time["tardiness_hrs"] + $ut_time["ut_hrs"])) /8);
						$tot_utime_mins -= $ut_time["offset_mins"];
						if($tot_utime_mins < 0){
                        	$tot_utime_mins = 60 - $tot_utime_mins;
                            $tot_utime_hrs -= 1;
                        }
                        // if($tot_utime_hrs > 0) $tot_utime_hrs -= $ut_time["offset_hrs"];
                        if($tot_utime_hrs < 0){
                        	$tot_utime_hrs = 8 - abs($tot_utime_hrs);
                        	$tot_utime_days -= 1;
                        }
                        if($tot_utime_days < 0) $tot_utime_days = 0;
                        if($isDTRRequired){
                        	if($isDTRRequired["is_dtr"] == 0){
                        		$tot_absent = $tot_utime_days = $tot_utime_hrs = $tot_utime_mins = 0;
                        	}
                        }
						$tot_utime = $tot_utime_days."° ".$tot_utime_hrs."' ".$tot_utime_mins.'"';
						$conversions = $ret->getUTConvertsions($tot_utime_hrs,$tot_utime_mins);
						$tot_ut_conversions = number_format($tot_utime_days + $conversions, 3);
						
						$process_offset_days = @$ut_time["offset_days"];
						$process_offset_hrs = $ut_time["offset_hrs"];
						$process_offset_mins = $ut_time["offset_mins"];
						$process_tardiness_hrs = $ut_time["tardiness_hrs"];
						$process_tardiness_mins = $ut_time["tardiness_mins"];
						$process_ut_hrs = $ut_time["ut_hrs"];
						$process_ut_mins = $ut_time["ut_mins"];
						$process_ut_conversion = $tot_absent + $tot_ut_conversions;
					}
					if($isDTRRequired){
						if($isDTRRequired["is_dtr"] == 0){
							$tot_absent = $tot_utime_days = $tot_utime_hrs = $tot_utime_mins = 0;
						}
					}
					$todays_year = date("Y");	
					$todays_month = date("m");
					// if($year == $todays_year && $value != $todays_month)$tot_ut_conversions = 0; $tot_absent = 0;
					$holidays = $ret->getHolidays($value,$employee_id,$year);
					$haveSuspension = $ret->getWorkSuspension($value,$year);
					$haveLeave = $ret->getLeaveIsMonth($value,$employee_id,$year);
					$haveUtime = false;
					$invalidEndDate = array("0000-00-00","1970-01-01");
					$monthly_data['vl_earned'] = ($employee_id == "5d9b767115646" || ($end_date && (!in_array($end_date, $invalidEndDate) && strtotime($end_date) <= strtotime(date("Y-m-d"))))) ? 0 : 1.250;
					$monthly_data['sl_earned'] = ($employee_id == "5d9b767115646" || ($end_date && (!in_array($end_date, $invalidEndDate) && strtotime($end_date) <= strtotime(date("Y-m-d"))))) ? 0 : 1.250;
					// $monthly_data['vl_earned'] = ($employment_status == "Active" || $employee_id == "5d9b767115646") ? 1.250 : 0;
					// $monthly_data['sl_earned'] = ($employment_status == "Active" || $employee_id == "5d9b767115646") ? 1.250 : 0;
					if(($tot_ut_conversions > 0) || sizeof($haveLeave) > 0){
						if($tot_ut_conversions > 0) $haveUtime = true;
					}
					//Leave Posting Start
					$leave_count = $leave_remarks_count = 0;
					$earned = 1.250;
					
	        		$month_of_permanent =(int) date('m',strtotime($date_of_permanent));
					if($month_of_permanent == $value && $year_permanent == $year){
						$last_day = $date = new DateTime('last day of '.$year.'-'.$value);
						$last_day = json_decode(json_encode($last_day), true);
						$last_day = $last_day['date'];
						$interval = $this->dateDiff($last_day,$date_of_permanent);						
						$ret_deduc_earned_conversion = $ret->getEarnedConversion($interval+1);
						$earned = $ret_deduc_earned_conversion[0]['leave_credits_earned'];
					}
					$tot_rehab_days = 0;
					$unique_day= [];
					$monet_unique_day = [];
					$rehab_unique_day = [];
					$spec_leave = 0;
					$vlsl = 0;
					$with_particulars_leaves = 0;
					if(end($selected_months) == $value){
						if($ut_time["no_days_upload_dtr"] == 0 && (int)date("d") < 4){
							$haveUtime = false;
							$monthly_data['vl_earned'] = $monthly_data['sl_earned'] = $tot_ut_conversions = $tot_absent = $earned = 0;
						}
					}

					$leave_types = array("maternity","paternity","solo","study","violence","benefits","special","covid","calamity","vacation","force","sick","monetization","rehab");
					foreach ($leave_types as $leave_key => $type) {
						$tot_leave_per_type = 0;
						$leaves = $ret->getLeave($value,$employee_id,$year,$type);
						$remarks = "";
						switch($type){
							case "vacation": $desc = "VL";break;
							case "sick": $desc = "SL"; break;
							case "force": $desc = "FL"; break;
							case "special": $desc = "SPL"; break;
							case "covid": $desc = "CV"; break;
							default: $desc = $type; break;
						}
						if($type == "monetization"){
							$monet = $ret->getMonetization($value,$year,$employee_id);
							if($monet && sizeof($monet)>0){
								$leave_count++;
								$vl_deduc = $sl_deduc = 0;
								foreach($monet as $k1 => $v1){

									$tot_vl_used = $tot_sl_used = 0;
									$tot_vl_rem = $tot_sl_rem = 0;
									$tot_vl_tmp = $tot_sl_tmp = 0;
									$tot_vl_putal = $tot_sl_putal = 0;
									$no_days = (int)$v1['number_of_days'];

									$tot_vl_putal = $total_vl_earned - floor($total_vl_earned);
									$tot_sl_putal = $total_sl_earned - floor($total_sl_earned);
									$tot_vl_tmp = $tot_vl_rem = floor($total_vl_earned);
									$tot_sl_tmp = $tot_sl_rem = floor($total_sl_earned);

									// monetized not for medical reason
									if($v1['isMedical'] == 0){
										$tot_vl_rem = $tot_vl_tmp - $no_days;
										$tot_vl_used = $tot_vl_rem <= 0 ? $tot_vl_tmp : $no_days;
										if($tot_vl_rem < 0 && $tot_sl_tmp > 0){
											$tot_sl_rem = $tot_sl_tmp - abs($tot_vl_rem);
											$tot_sl_used = $tot_sl_rem <= 0 ? $tot_sl_tmp : $tot_vl_rem;
										}
									}else{
										$tot_sl_rem = $tot_sl_tmp - $no_days;
										$tot_sl_used = $tot_sl_rem <= 0 ? $tot_sl_tmp : $no_days;
										if($tot_sl_rem < 0 && $tot_vl_tmp > 0){
											$tot_vl_rem = $tot_vl_tmp - abs($tot_sl_rem);
											$tot_vl_used = $tot_vl_rem <= 0 ? $tot_vl_tmp : $tot_sl_rem;
										}
									}
									$total_vl_from_monetized += $tot_vl_used;
									$total_sl_from_monetized += $tot_sl_used;
									$tot_vl_tmp = $tot_vl_rem <= 0 ? 0 : $tot_vl_rem;
									$tot_sl_tmp = $tot_sl_rem <= 0 ? 0 : $tot_sl_rem;

									$total_vl_earned = $tot_vl_tmp + $tot_vl_putal;
									$total_sl_earned = $tot_sl_tmp + $tot_sl_putal;
									$total_leaves_per_month_from_remarks += $tot_vl_used;
									// $total_leaves_per_month_from_remarks += $tot_sl_used;

									array_push($arrmonet,
										date('M. d, Y', strtotime($v1['date_filed']))."<br>".
										($tot_vl_used > 0 ? $tot_vl_used." days c/o VL<br>" : "").
										($tot_sl_used > 0 ? $tot_sl_used." days c/o SL<br>" : "").
										"<b>P ".$v1['amount_monetized']."</b>"
									);
								}
							}
						}else{
							if(sizeof($leaves) > 0){
								$leave_count++;
								$particulars = "<div style='width:100%;text-align:left;'>".$desc." ";
								foreach ($leaves as $k1 => $v1) {
									if($type == "force") $forced_count += $v1['number_of_days'];
									if($v1['status'] == "REJECTED") $remarks = $v1['remarks'];
									$days_of_leave = $ret->getLeaveDays($value,$year,$employee_id,$type);
									//Particulars for Different month
									if($type != "rehab" && $type != "maternity" && $type != "paternity" && $type != "study" && $type != "benefits" && $type != "violence" && $type != "monetization"){
										if(sizeof($days_of_leave) > 0){
											$first = true;
											foreach ($days_of_leave as $k2 => $v2) {
												switch($type){
													case "force": array_push($arrforce,date('M. d, Y', strtotime($v2['days_of_leave']))); break;
													case "solo": array_push($arrsolo,date('M. d, Y', strtotime($v2['days_of_leave']))); break;
													case "calamity": array_push($arrcalamity,date('M. d, Y', strtotime($v2['days_of_leave']))); break;
													case "special": array_push($arrspecial,date('M. d, Y', strtotime($v2['days_of_leave']))); break;
													case "sick": array_push($arrsick,date('M. d, Y', strtotime($v2['days_of_leave']))); break;
													case "vacation": array_push($arrvacation,date('M. d, Y', strtotime($v2['days_of_leave']))); break;
												}
												if($first){
													$particulars .= "(".sizeof($days_of_leave).") ".date('d', strtotime($v2['days_of_leave']));
													$first = false;
												}else $particulars.= ', '.date('d', strtotime($v2['days_of_leave']));
												$tot_vl_leaves += sizeof($days_of_leave);
												if($type != "rehab" && !in_array($value.date('d', strtotime($v2['days_of_leave'])), $unique_day)){
													$total_leaves_per_month++;
													if($type == "force") {
														// $total_vl_earned = (float)$total_vl_earned - 1; 
														$total_vl_force++;
														$tot_absent--;
													}
													if($type != "vacation" && $type != "sick" && $type != "force" && $type != "covid") $total_leaves_per_month_from_remarks++;
													// in_array($type, ['special','calamity','solo']);
													array_push($unique_day,$value.date('d', strtotime($v2['days_of_leave'])));
												}
											}
										}
									}else{
										$spf_dys = [];
										$unique_day= [];
										$days = 0;
										foreach ($days_of_leave as $k2 => $v2) {
											$days = 0;
											$spf_days = [];
											$rehabdates = explode(" - ",$days_of_leave[$k2]['days_of_leave']);
											if(sizeof($rehabdates) > 1){
												$start = new DateTime($rehabdates[0]);
												$end = new DateTime($rehabdates[1]);
												$end->modify('+1 day');
												$interval = $end->diff($start);
												// $days = $interval->days;
												$period = new DatePeriod($start, new DateInterval('P1D'), $end);
												// $holidays = array('2020-09-11');
												$fst = true;
												foreach($period as $dt) {
													$curr = $dt->format('l');
													if($dt->format('M') == date('M', strtotime(date('Y')."-".$value."-01"))){
														array_push($spf_days, $dt->format('d'));
														if($type != "rehab"){
															if(!in_array($value.$dt->format('d'),$unique_day)) {
																if(in_array($curr, $employee_working_days_schedule)){ 
																	$total_leaves_per_month++;
																	$total_leaves_per_month_from_remarks++;
																}
																array_push($unique_day,$value.$dt->format('d'));
															}
														}else if($type == "rehab") {
															if(in_array($curr, $employee_working_days_schedule) && !in_array($value.$dt->format('d'), $rehab_unique_day)) {
																$tot_rehab_days++;
																array_push($rehab_unique_day,$value.$dt->format('d'));
															}
														}
														$days++;
													}
													$fst = false;
												}
												$spfd = (sizeof($spf_days) == 1) ? reset($spf_days) : reset($spf_days) . " - " . end($spf_days);
												if(!in_array($spfd, $spf_dys)) array_push($spf_dys, $spfd);
											}else{
												if($type != "rehab"){
													if(!in_array($value.date("d",strtotime($rehabdates[0])),$unique_day)) {
														$total_leaves_per_month++;
														$total_leaves_per_month_from_remarks++;
														array_push($unique_day,$value.date("d",strtotime($rehabdates[0])));
													}
												}else if($type == "rehab") {
													if(!in_array($value.date("d",strtotime($rehabdates[0])), $rehab_unique_day)) {
														$tot_rehab_days++;
														array_push($rehab_unique_day,$value.$dt->format('d'));
													}
												}
												array_push($spf_dys, date("d",strtotime($rehabdates[0])));
												$days++;
											}
										}
										$particulars .= "(".$days.") ".implode(", ",$spf_dys);
									}
									$particulars.="</div>";
								}
								$monthly_data['period'] = date("M. t, Y", strtotime($a_date));//$monthly_data['period'] = "";
								$monthly_data['particulars'] = $particulars;
								if($type == "benefits" || $type == "rehab"){
									$with_particulars_leaves++;
									$deduc_earned_conversion = 0;
									if($type== "rehab" && $tot_rehab_days > 0) {
										//$ret_deduc_earned_conversion = $ret->getEarnedConversion($tot_rehab_days);
										$ret_deduc_earned_conversion = 0; // to have fixed 1.250 leave earned
										if($ret_deduc_earned_conversion) $deduc_earned_conversion = $ret_deduc_earned_conversion[0]['leave_credits_earned'];
										$earned -= $deduc_earned_conversion;
									}
									//VL
									if(sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0){
										$monthly_data['vl_earned'] = ($earned - $deduc_earned_conversion);
										$monthly_data['sl_earned'] = ($earned - $deduc_earned_conversion);
									}else{
										$monthly_data['vl_earned'] = "";
										$monthly_data['sl_earned'] = "";
									}
									// $monthly_data['vl_earned'] = (sizeof($haveLeave) == $leave_count && $haveUtime == false)? (1.25 - $deduc_earned_conversion): "";
									$total_vl_earned += (sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $monthly_data['vl_earned'] : 0;
									// $monthly_data['vl_a_utime_w_pay'] = ((sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $total_vl_from_monetized + $total_vl_force : 0) + $deduc_earned_conversion;
									$monthly_data['vl_a_utime_w_pay'] = ((sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $total_vl_from_monetized : 0) + $deduc_earned_conversion;
									$monthly_data['vl_balance'] = (sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $total_vl_earned : "";
									$monthly_data['vl_a_utime_wo_pay'] = "";
									//SL
									$monthly_data['sl_earned'] = (sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $earned : 0;
									$total_sl_earned += (sizeof($haveLeave) == $leave_count && $haveUtime == false)? $monthly_data['sl_earned'] : 0;
									$monthly_data['sl_a_utime_w_pay'] = ((sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $total_sl_from_monetized : 0) + $deduc_earned_conversion;
									$monthly_data['sl_balance'] = (sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $total_sl_earned : "";
									$monthly_data['sl_a_utime_wo_pay'] = "";
									$monthly_data['remarks'] = $remarks;

									if(sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0){
										$balanceasof = $ret->getBalanceAsOf($value,$employee_id,$year);
										if($balanceasof){$monthly_data['particulars'] = $monthly_data['vl_earned'] = $monthly_data['vl_a_utime_w_pay'] = $monthly_data['sl_earned'] = $monthly_data['sl_a_utime_w_pay'] = "";
											$total_vl_earned = $balanceasof["vl"];
											$total_sl_earned = $balanceasof["sl"];
											$monthly_data['vl_balance'] = $total_vl_earned;
											$monthly_data['sl_balance'] = $total_sl_earned;
											$monthly_data['vl_earned'] = "";
											$monthly_data['sl_earned'] = "";
											$monthly_data['vl_a_utime_wo_pay'] = "";
											$monthly_data['sl_a_utime_wo_pay'] = "";
										}
									}
									// if(($year == 2021 && $value >= 4) || $year > 2021)
										$ledger[$year][] = $monthly_data;
								} else{
									if($type == "vacation"){
										$with_particulars_leaves++;
										$haveVl++;
										foreach ($haveSuspension as $suspension) {
											if(in_array(date('M. d, Y', strtotime($suspension['date'])), $arrvacation)){
												if(strpos($suspension['time_suspension'], "AM") !== false){
													$time_suspension = 1;
												}else{
													$time_suspension = 0.5;
												}											
											}
										}
										//VL
										$converted_hr = $ret->getConversionFractions(8,"hr");
										$vl_a_utime_w_pay = ($v1['number_of_days'] * $converted_hr[0]['equiv_day']) - $time_suspension;
										// $monthly_data['vl_a_utime_w_pay'] = ((sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $total_vl_from_monetized + $total_vl_force : 0) + $vl_a_utime_w_pay;
										$monthly_data['vl_a_utime_w_pay'] = ((sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $total_vl_from_monetized : 0) + $vl_a_utime_w_pay;
										$total_vl = $monthly_data['vl_a_utime_w_pay'];
										$tot_absent -= $monthly_data['vl_a_utime_w_pay'];
										$vlsl += $monthly_data['vl_a_utime_w_pay'];

										$monthly_data['vl_earned'] = (sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $earned : "";
										$total_vl_earned += (sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $monthly_data['vl_earned'] : 0;

										$total_vl_earned -= $monthly_data['vl_a_utime_w_pay'];
										$vl_a_utime_wo_pay = "";
										if($total_vl_earned < 0){
											$total_vl_earned = 0;
										}										

										$monthly_data['vl_balance'] = $total_vl_earned;
										$monthly_data['vl_a_utime_wo_pay'] = $vl_a_utime_wo_pay;
										//SL
										$monthly_data['sl_a_utime_w_pay'] = ((sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $total_sl_from_monetized : 0);
										$monthly_data['sl_earned'] = (sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $earned : "";
										$total_sl_earned += (sizeof($haveLeave) == $leave_count && $haveUtime == false && $monthly_data['sl_earned'])? $monthly_data['sl_earned'] : 0;
										$monthly_data['sl_balance'] = (sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $total_sl_earned : "";
										$monthly_data['sl_a_utime_wo_pay'] = "";
										$monthly_data['remarks'] = $remarks;

										if(sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0){
											$balanceasof = $ret->getBalanceAsOf($value,$employee_id,$year);
											if($balanceasof){$monthly_data['particulars'] = $monthly_data['vl_earned'] = $monthly_data['vl_a_utime_w_pay'] = $monthly_data['sl_earned'] = $monthly_data['sl_a_utime_w_pay'] = "";
												$total_vl_earned = $balanceasof["vl"];
												$total_sl_earned = $balanceasof["sl"];
												$monthly_data['vl_balance'] = $total_vl_earned;
												$monthly_data['sl_balance'] = $total_sl_earned;
												$monthly_data['vl_earned'] = "";
												$monthly_data['sl_earned'] = "";
												$monthly_data['vl_a_utime_wo_pay'] = "";
												$monthly_data['sl_a_utime_wo_pay'] = "";
											}
										}
										// if(($year == 2021 && $value >= 4) || $year > 2021)
											$ledger[$year][] = $monthly_data;
										$tot_vl_leaves += $v1['number_of_days'];
									}
									else if($type == "force"){
										// $with_particulars_leaves++;
										// $haveVl++;
										foreach ($haveSuspension as $suspension) {
											if(in_array(date('M. d, Y', strtotime($suspension['date'])), $arrforce)){
												if(strpos($suspension['time_suspension'], "AM") !== false){
													$time_suspension = 1;
												}else{
													$time_suspension = 0.5;
												}											
											}
										}
										//FL
										$converted_hr = $ret->getConversionFractions(8,"hr");
										$vl_a_utime_w_pay = ($v1['number_of_days'] * $converted_hr[0]['equiv_day']) - $time_suspension;
										// $monthly_data['vl_a_utime_w_pay'] = ((sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $total_vl_from_monetized + $total_vl_force : 0) + $vl_a_utime_w_pay;
										$monthly_data['vl_a_utime_w_pay'] = ((sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $total_vl_from_monetized : 0) + $vl_a_utime_w_pay;
										$total_vl = $monthly_data['vl_a_utime_w_pay'];
										$tot_absent -= $monthly_data['vl_a_utime_w_pay'];
										$vlsl += $monthly_data['vl_a_utime_w_pay'];

										$monthly_data['vl_earned'] = (sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $earned : "";
										$total_vl_earned += (sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $monthly_data['vl_earned'] : 0;
										
										$total_vl_earned -= $monthly_data['vl_a_utime_w_pay'];
										$vl_a_utime_wo_pay = "";
										if($total_vl_earned < 0){
											$total_vl_earned = 0;
										}										
										$monthly_data['vl_balance'] = $total_vl_earned;
										$monthly_data['vl_a_utime_wo_pay'] = $vl_a_utime_wo_pay;
										//SL
										$monthly_data['sl_a_utime_w_pay'] = ((sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $total_sl_from_monetized : 0);
										$monthly_data['sl_earned'] = (sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $earned : "";
										$total_sl_earned += (sizeof($haveLeave) == $leave_count && $haveUtime == false && $monthly_data['sl_earned'])? $monthly_data['sl_earned'] : 0;
										$monthly_data['sl_balance'] = (sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $total_sl_earned : "";
										$monthly_data['sl_a_utime_wo_pay'] = "";
										$monthly_data['remarks'] = $remarks;

										if(sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0){
											$balanceasof = $ret->getBalanceAsOf($value,$employee_id,$year);
											if($balanceasof){$monthly_data['particulars'] = $monthly_data['vl_earned'] = $monthly_data['vl_a_utime_w_pay'] = $monthly_data['sl_earned'] = $monthly_data['sl_a_utime_w_pay'] = "";
												$total_vl_earned = $balanceasof["vl"];
												$total_sl_earned = $balanceasof["sl"];
												$monthly_data['vl_balance'] = $total_vl_earned;
												$monthly_data['sl_balance'] = $total_sl_earned;
												$monthly_data['vl_earned'] = "";
												$monthly_data['sl_earned'] = "";
												$monthly_data['vl_a_utime_wo_pay'] = "";
												$monthly_data['sl_a_utime_wo_pay'] = "";
											}
										}
										// if(($year == 2021 && $value >= 4) || $year > 2021)
											$ledger[$year][] = $monthly_data;
										// $tot_vl_leaves += $v1['number_of_days'];
									}
									//Sick Leave
									else if($type == "sick"){
										$with_particulars_leaves++;
										$haveSl++;
										foreach ($haveSuspension as $suspension) {
											if(in_array(date('M. d, Y', strtotime($suspension['date'])), $arrsick)){
												if(strpos($suspension['time_suspension'], "AM") !== false){
													$time_suspension = 1;
												}else{
													$time_suspension = 0.5;
												}											
											}
										}
										//SL
										$converted_hr = $ret->getConversionFractions(8,"hr");
										$sl_a_utime_w_pay = ($v1['number_of_days'] * $converted_hr[0]['equiv_day']) - $time_suspension;

										$tot_absent -= $sl_a_utime_w_pay;
										$vlsl += $sl_a_utime_w_pay;

										$monthly_data['sl_earned'] = (sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $earned : 0;
										$total_sl_earned += (sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $monthly_data['sl_earned'] : 0;
										$putal = $total_sl_earned - floor($total_sl_earned);
										$total_sl_earned = $tmp_total_sl_earned = floor($total_sl_earned);
										$total_sl_earned -= $sl_a_utime_w_pay;
										$rem_sl_tobe_deduc_to_vl = 0;

										//VL
										if($total_sl_earned < 0){
											$total_vl_earned -= abs($total_sl_earned);
											$rem_sl_tobe_deduc_to_vl = abs($total_sl_earned);
											if($total_vl_earned < 0){
												$total_vl_earned = 0;
												$sl_a_utime_wo_pay = abs($total_sl_earned);
											}
											$total_sl_earned = 0;
										}

										$monthly_data['vl_earned'] = (sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $earned : 0;
										$total_vl_earned += (sizeof($haveLeave) == $leave_count && $haveUtime == false)? $monthly_data['vl_earned'] : 0;
										$monthly_data['vl_a_utime_w_pay'] = $total_vl_earned <= 0 ? $tmp_total_sl_earned - $sl_a_utime_w_pay : $rem_sl_tobe_deduc_to_vl;
										// $monthly_data['vl_a_utime_w_pay'] += ((sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $total_vl_from_monetized + $total_vl_force : 0);
										$monthly_data['vl_a_utime_w_pay'] += ((sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $total_vl_from_monetized : 0);
										$monthly_data['vl_balance'] = (sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $total_vl_earned : "";
										$monthly_data['vl_a_utime_wo_pay'] = "";

										$sl_a_utime_wo_pay = "";
										$sl_a_utime_w_pay += ((sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0)? $total_sl_from_monetized : 0);
										$monthly_data['sl_a_utime_w_pay'] = $total_sl_earned <= 0 ? $tmp_total_sl_earned : $sl_a_utime_w_pay;
										$total_sl = $monthly_data['sl_a_utime_w_pay'];
										$total_sl_earned = $putal + $total_sl_earned;
										$monthly_data['sl_balance'] = $total_sl_earned;
										$monthly_data['sl_a_utime_wo_pay'] = $sl_a_utime_wo_pay;
										$monthly_data['remarks'] = $remarks;

										// var_dump($total_vl_force);
										if(sizeof($haveLeave) == $leave_count && $haveUtime == false && $tot_absent <= 0){
											$balanceasof = $ret->getBalanceAsOf($value,$employee_id,$year);
											if($balanceasof){$monthly_data['particulars'] = $monthly_data['vl_earned'] = $monthly_data['vl_a_utime_w_pay'] = $monthly_data['sl_earned'] = $monthly_data['sl_a_utime_w_pay'] = "";
												$total_vl_earned = $balanceasof["vl"];
												$total_sl_earned = $balanceasof["sl"];
												$monthly_data['vl_balance'] = $total_vl_earned;
												$monthly_data['sl_balance'] = $total_sl_earned;
												$monthly_data['vl_earned'] = "";
												$monthly_data['sl_earned'] = "";
												$monthly_data['vl_a_utime_wo_pay'] = "";
												$monthly_data['sl_a_utime_wo_pay'] = "";
											}
										}
										// if(($year == 2021 && $value >= 4) || $year > 2021)
											$ledger[$year][] = $monthly_data;
										$tot_sl_leaves += $v1['number_of_days'];
									}
								}
								
							}
						}
					}
					//Leave Posting End

					//UTime Posting Start
					$tmp_tot_ut_conversions = $tot_ut_conversions;
					if((float)$tot_ut_conversions > 0 || $tot_absent > 0){
						$process_ut_conversion = $tot_absent > 0 ? $process_ut_conversion - $tot_rehab_days : $process_ut_conversion;
						// $particulars = "<div style='width:100%;text-align:left'>". ($tot_absent > 0 ? "A ".($tot_absent - ($tot_rehab_days + $spec_leave))."<br>":"" ).($tot_utime != "" ?"UT ". $tot_utime:"") ."</div>";
						$particulars = "<div style='width:100%;text-align:left'>". ($tot_absent > 0 ? "A ".($tot_absent - ($tot_rehab_days + $spec_leave))."<br>":"" ).($tot_utime != "" && ($tot_utime_days != 0 || $tot_utime_hrs != 0 || $tot_utime_mins != 0) ?"UT ". $tot_utime:"") ."</div>";
						$monthly_data['period'] = date("M. t, Y", strtotime($a_date));//$monthly_data['period'] = "";
						$monthly_data['particulars'] = $particulars;
						$vl_a_utime_w_pay = 0;
						$vl_decimals = $sl_decimals = 0;
						$process_absent = $tot_absent - $tot_rehab_days;
						$process_absent = $process_absent < 0 ? 0 : $process_absent;
						$total_u_item_with_leaves = ($total_leaves_per_month - $vlsl);//$total_leaves_per_month  
						$total_u_item_with_leaves = ($total_u_item_with_leaves > 0) ? $total_u_item_with_leaves : 0;
						
						$monthly_data['vl_a_utime_w_pay'] = 0;
						$monthly_data['vl_a_utime_wo_pay'] =  0;
						// $eeerrr = $total_leaves_per_month + $ut_time["no_days"] + $get_working_days["ends"];
						// if($value == 2){ $eeerrr += 2;}
						// $add_earn_from_present = $ret->getEarnedConversion($eeerrr > 31 ? 31 : $eeerrr);
						// $earned = @$add_earn_from_present[0]['leave_credits_earned'];
						if($total_u_item_with_leaves > 0) {
							// $deduc_earn_from_absent = $ret->getEarnedConversion($total_u_item_with_leaves);
							$deduc_earn_from_absent = 0; // to have fixed 1.250 leave earned
							$earned -= @$deduc_earn_from_absent[0]['leave_credits_earned'];
						}
						$total_vl_earned += $earned;
						
						if($tot_ut_conversions > 0){
							if($total_vl_earned > 0){
									$tmp_vl = $total_vl_earned;
									$rem_tot_ut_conversion = $tot_ut_conversions -  $total_vl_earned;
									$total_vl_earned -= $tot_ut_conversions;
									$total_vl_earned = $total_vl_earned > 0 ? $total_vl_earned : 0;
									$monthly_data['vl_a_utime_w_pay'] = $total_vl_earned > 0 ? $tot_ut_conversions : $tot_ut_conversions + abs($rem_tot_ut_conversion);//$tmp_vl + $tot_ut_conversions;
									$tot_ut_conversions -= $total_vl_earned;
									if($tot_ut_conversions < 0) $tot_ut_conversions = 0;
									$monthly_data['vl_a_utime_wo_pay'] = $total_vl_earned > 0 ? 0 : abs($rem_tot_ut_conversion);//$tot_ut_conversions;
								$monthly_data['vl_balance'] = $total_vl_earned;
							}else $monthly_data['vl_a_utime_wo_pay'] = $tot_ut_conversions;
						}

						//VL
						// $total_vl_earned += $earned; // earn muna 1.25 bago ibawas ang mga absent
						$total_u_item_with_leaves_with_earned_with_leave_credits = $tmp_total_vl_earned = $tmp_total_u_item_with_leaves = 0;
						if($total_u_item_with_leaves > 0){
							$tmp_total_u_item_with_leaves = $total_u_item_with_leaves;
							$tmp_total_vl_earned = floor($total_vl_earned);
							if($tmp_total_vl_earned > 0){
								$vl_decimals = $total_vl_earned - floor($total_vl_earned);
								$total_vl_earned = $tmp_total_vl_earned - $total_u_item_with_leaves;
								$total_u_item_with_leaves = $total_u_item_with_leaves - $tmp_total_vl_earned;
								$total_u_item_with_leaves = $total_u_item_with_leaves > 0 ? $total_u_item_with_leaves : 0;
								$total_vl_earned = $total_vl_earned > 0 ? $total_vl_earned : 0;
							}else $monthly_data['vl_a_utime_wo_pay'] += $total_u_item_with_leaves;
						}
						$vl_a_utime_wo_pay = ($total_u_item_with_leaves > 0)?$total_u_item_with_leaves:0;
						$vl_a_utime_wo_pay += $tot_absent;
						$monthly_data['vl_a_utime_w_pay'] += $total_vl_from_monetized + ($total_u_item_with_leaves > 0 ? $tmp_total_vl_earned : $tmp_total_u_item_with_leaves);//$total_leaves_per_month_from_remarks + $vl_a_utime_w_pay;
						// if($haveVl>0) $monthly_data['vl_a_utime_w_pay'] -= $total_vl;
						if($total_vl_from_monetized != $total_leaves_per_month_from_remarks)
						$monthly_data['vl_a_utime_w_pay'] -= ($monthly_data['vl_a_utime_w_pay'] <= 0)? 0 : $total_leaves_per_month_from_remarks;
						$total_vl_earned += $vl_decimals;
						$monthly_data['vl_earned'] = $earned;
						$monthly_data['vl_balance'] = $total_vl_earned;
						
						//SL
						$sl_tot_deduc = $tmp_total_vl_earned = 0;
						// $total_sl_earned += 1.250; //earn muna bago ibawas ang mga absent
						$total_sl_earned += $earned;
						// if($vl_a_utime_wo_pay > 0 && $total_sl_earned > 0){
						// 	$sl_tot_deduc = $vl_a_utime_wo_pay;
						// 	$tmp_total_vl_earned = floor($total_sl_earned);
						// 	$sl_decimals = $total_sl_earned - floor($total_sl_earned);
						// 	$vl_a_utime_wo_pay -= floor($total_sl_earned);
						// 	$total_sl_earned = floor($total_sl_earned) - floor($sl_tot_deduc);
						// 	if($vl_a_utime_wo_pay < 0) $vl_a_utime_wo_pay = 0;
						// 	if($total_sl_earned < 0) $total_sl_earned = 0;
						// 	$total_sl_earned += $sl_decimals;
						// }
						
						$monthly_data['sl_earned'] = $earned;
						$monthly_data['sl_a_utime_w_pay'] = $total_sl_from_monetized + ($vl_a_utime_wo_pay > 0 ? $tmp_total_vl_earned : ($total_sl_earned > 0?$sl_tot_deduc:0));
						if($haveVl>0) $monthly_data['sl_a_utime_w_pay'] -= $total_sl;
						$monthly_data['sl_balance'] = $total_sl_earned;
						$monthly_data['sl_a_utime_wo_pay'] = "";
						$monthly_data['remarks'] = @$utime[0]['remarks'];
						
						if($total_vl_earned <= 10) {
							$total_vl_earned += $monthly_data['vl_a_utime_w_pay'];
							$total_sl_earned += $monthly_data['sl_a_utime_w_pay'];
							if($total_vl_earned >= 10){
								$monthly_data['vl_balance'] = 10;
								$vl_a_utime_w_pay = $total_vl_earned - 10;
								$vl_a_utime_wo_pay += ($monthly_data['vl_a_utime_w_pay'] - $vl_a_utime_w_pay);
								$monthly_data['vl_a_utime_w_pay'] = $vl_a_utime_w_pay;
							}else{
								$monthly_data['vl_balance'] = $total_vl_earned;
								$vl_a_utime_wo_pay += $monthly_data['vl_a_utime_w_pay'];
								$monthly_data['vl_a_utime_w_pay'] = 0;
							}					
							$total_vl_earned = $monthly_data['vl_balance'];
							$totalParticulars = $monthly_data['particulars'];					
						}
						$monthly_data['vl_a_utime_wo_pay'] = $vl_a_utime_wo_pay;

						// if($monthly_data['vl_a_utime_wo_pay'] >= $process_absent){
						// 	$process_absent = $process_absent - $monthly_data['vl_a_utime_wo_pay'];
						// 	$process_absent = $process_absent < 0 ? 0 : $process_absent;
						// }else{
						// 	$process_absent = 0;
						// }
						$process_leave_credits_used = $monthly_data["vl_a_utime_w_pay"];
						
						$balanceasof = $ret->getBalanceAsOf($value,$employee_id,$year);
						if($balanceasof){
							$monthly_data['particulars'] = $monthly_data['vl_earned'] = $monthly_data['vl_a_utime_w_pay'] = $monthly_data['sl_earned'] = $monthly_data['sl_a_utime_w_pay'] = "";
							$total_vl_earned = $balanceasof["vl"];
							$total_sl_earned = $balanceasof["sl"];
							$monthly_data['vl_balance'] = $total_vl_earned;
							$monthly_data['sl_balance'] = $total_sl_earned;
							$monthly_data['vl_earned'] = "";
							$monthly_data['sl_earned'] = "";
							$monthly_data['vl_a_utime_wo_pay'] = "";
							$monthly_data['sl_a_utime_wo_pay'] = "";
						}						
						$ledger[$year][] = $monthly_data;
					}

					//UTime Posting End
					if($with_particulars_leaves <= 0 && (float)$tmp_tot_ut_conversions <= 0 && $tot_absent <= 0){
						$monthly_data['particulars'] = "";
						//VL
						$monthly_data['vl_a_utime_w_pay'] = $total_vl_from_monetized;
						$total_vl_earned += $monthly_data['vl_earned'];
						$monthly_data['vl_balance'] = $total_vl_earned;
						$monthly_data['vl_a_utime_wo_pay'] = "";
						//SL
						
						$monthly_data['sl_a_utime_w_pay'] = $total_sl_from_monetized;
						$total_sl_earned += $monthly_data['sl_earned'];
						$monthly_data['sl_balance'] = $total_sl_earned;
						$monthly_data['sl_a_utime_wo_pay'] = "";
						
						$remarks = "";
						if($key == sizeof($selected_months) - 1) $remarks = $total_vl_earned + $total_sl_earned;
						$monthly_data['remarks'] = $remarks;

						$balanceasof = $ret->getBalanceAsOf($value,$employee_id,$year);
						if($balanceasof){
							$monthly_data['particulars'] = $monthly_data['vl_earned'] = $monthly_data['vl_a_utime_w_pay'] = $monthly_data['sl_earned'] = $monthly_data['sl_a_utime_w_pay'] = "";
							$total_vl_earned = $balanceasof["vl"];
							$total_sl_earned = $balanceasof["sl"];
							$monthly_data['vl_balance'] = $total_vl_earned;
							$monthly_data['sl_balance'] = $total_sl_earned;
							$monthly_data['vl_earned'] = "";
							$monthly_data['sl_earned'] = "";
							$monthly_data['vl_a_utime_wo_pay'] = "";
							$monthly_data['sl_a_utime_wo_pay'] = "";
						}
						$ledger[$year][] = $monthly_data;
					}
					$first = false;
					if(isset($terminal) && (($dtfiled == (int)$value) && (int)date("Y",strtotime($terminal['is_terminal'])) == $year)){
						$monthly_data['period'] = "<span style='display:none;'></span>";
						$monthly_data['particulars'] = "Retired effective: ".date("M d, Y", strtotime($terminal['is_terminal']));

						$monthly_data['vl_earned'] = "";
						$monthly_data['vl_a_utime_w_pay'] = "";//$vl_a_utime_w_pay;
						$vl_a_utime_wo_pay = "";
						$monthly_data['vl_balance'] = "";//$total_vl_earned;
						$monthly_data['vl_a_utime_wo_pay'] = "";//$vl_a_utime_wo_pay;
						//SL
						$monthly_data['sl_earned'] = "";
						$monthly_data['sl_a_utime_w_pay'] = "";
						$monthly_data['sl_balance'] = "";
						$monthly_data['sl_a_utime_wo_pay'] = "";
						$monthly_data['remarks'] = "";
						$ledger[$year][] = $monthly_data;
						break;
					}
					$ledger["deductions"]['process_absent'] = $process_absent;
					$ledger["deductions"]['process_tardiness_hrs'] = $process_tardiness_hrs;
					$ledger["deductions"]['process_tardiness_mins'] = $process_tardiness_mins;
					$ledger["deductions"]['process_ut_hrs'] = $process_ut_hrs;
					$ledger["deductions"]['process_ut_mins'] = $process_ut_mins;
					$ledger["deductions"]['process_offset_days'] = $process_offset_days;
					$ledger["deductions"]['process_offset_hrs'] = $process_offset_hrs;
					$ledger["deductions"]['process_offset_mins'] = $process_offset_mins;
					$ledger["deductions"]['process_leave_credits_used'] = $process_leave_credits_used;
					$ledger["deductions"]['process_ut_conversion'] = $process_ut_conversion;
			}
		return $ledger;
	}
	
	public function dateDiff($d1,$d2) {
		// Return the number of days between the two dates:    
		return round(abs(strtotime($d1) - strtotime($d2))/86400);	
	}

}

?>
