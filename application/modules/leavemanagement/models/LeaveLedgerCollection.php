<?php
	class LeaveLedgerCollection extends Helper {
      	var $select_column = null; 
		public function __construct() {
			$this->load->model('HelperDao');
			ModelResponse::busy();
			$columns = $this->getColumns();
			foreach ($columns as $key => $value) {
				$this->select_column[] = $this->table.'.'.$value['COLUMN_NAME'];
			}
		}

		//Fetch
		var $table = "tblemployees";   
      	var $order_column = array('','CAST(emp_number as INT)','last_name','tblfieldpositions.name','tblfielddepartments.department_name','tblemployees.contract');
      	
		public function getColumns(){
      		
      		$sql = " SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='".$this->table."' AND TABLE_SCHEMA='".$this->db->database."' ";
			$query = $this->db->query($sql);
			$rows = $query->result_array();
			return $rows; 
      	}
      	
		public function getEmployeeColumns(){
      		$sql = " SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='tblemployees' AND TABLE_SCHEMA='".$this->db->database."' ";
			$query = $this->db->query($sql);
			$rows = $query->result_array();
			return $rows; 
      	}
		
		function make_query($employment_status,$employee_id,$pay_basis,$division_id,$location_id,$year,$month,$self){  
			$this->select_column[] = 'tblemployees.first_name';
			$this->select_column[] = 'tblemployees.last_name';
			$this->select_column[] = 'tblemployees.middle_name';
			$this->select_column[] = 'tblemployees.extension';
			$this->select_column[] = 'tblemployees.employee_number';
			$this->select_column[] = 'tblemployees.employee_id_number';
			$this->select_column[] = 'tblemployees.salary';
			$this->select_column[] = 'tblfieldpositions.name';
			$this->select_column[] = 'tblfielddepartments.department_name';
		    $this->db->select(
				'DECRYPTER(tblemployees.first_name,"sunev8clt1234567890",tblemployees.id) as first_name,
                DECRYPTER(tblemployees.middle_name,"sunev8clt1234567890",tblemployees.id) as middle_name,
                DECRYPTER(tblemployees.last_name,"sunev8clt1234567890",tblemployees.id) as last_name,
                DECRYPTER(tblemployees.employee_id_number,"sunev8clt1234567890",tblemployees.id) as emp_number,
		    	tblemployees.*,
		    	tblemployees.id AS employee_id,
		    	tblfieldpositions.name AS position_name,
		    	tblemployees.position_id AS position_id,
		    	tblfielddepartments.department_name'
		    );  
		    $this->db->from($this->table);
		    $this->db->join("tblfieldpositions","tblemployees.position_id = tblfieldpositions.id","left");
			$this->db->join("tblfielddepartments","tblemployees.division_id = tblfielddepartments.id","left");
			$this->db->where('tblemployees.employment_status = "Active"');
			if($self){
				$this->db->where('tblemployees.id="'.Helper::get("id").'"');
			}else{
				
				$this->db->where("DATE(tblemployees.date_of_permanent) <= DATE('".$year."-".$month."-31')");
				if(isset($_POST["search"]["value"]))  
				{  
					$this->db->group_start();
					 foreach ($this->select_column as $key => $value) {
						 if($value == "tblemployees.first_name" || $value == "tblemployees.last_name" || $value == "tblemployees.middle_name" || $value == "tblemployees.employee_number" || $value == "tblemployees.employee_id_number")  {
							 $this->db->or_like("DECRYPTER($value,'sunev8clt1234567890',tblemployees.id)", $_POST["search"]["value"]);  
						 }
						 else{
							 if($value != "tblemployees.date_created") $this->db->or_like($value, $_POST["search"]["value"]);  
						 }
					 }
					 $this->db->or_like("CONCAT(DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id),', ',DECRYPTER(tblemployees.first_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.middle_name,'sunev8clt1234567890',tblemployees.id))"
						 ,$_POST["search"]["value"]);
					 $this->db->or_like("CONCAT(DECRYPTER(tblemployees.first_name,'sunev8clt1234567890',tblemployees.id),' ',DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',tblemployees.id))"
						 ,$_POST["search"]["value"]);
					$this->db->group_end(); 
				}
				if(isset($employee_id) && $employee_id != null) $this->db->where('tblemployees.id="'.$employee_id.'"');
				else{
					if((isset($division_id) && $division_id != null)) $this->db->where('tblemployees.division_id="'.$division_id.'"');
				}
				if(isset($_POST["order"])) $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
				else  $this->db->order_by("tblfielddepartments.department_name,tblemployees.first_name ASC");
			}

		}

		function make_datatables($employment_status,$employee_id,$pay_basis,$division_id,$location_id,$year,$month,$self){  
		    $this->make_query($employment_status,$employee_id,$pay_basis,$division_id,$location_id,$year,$month,$self);  
		    if($_POST["length"] != -1){  
		        $this->db->limit($_POST['length'], $_POST['start']);  
		    }
			$query = $this->db->get();
		    return $query->result();  
		}
		
		function get_filtered_data($employment_status,$employee_id,$pay_basis,$division_id,$location_id,$year,$month,$self){  
		     $this->make_query($employment_status,$employee_id,$pay_basis,$division_id,$location_id,$year,$month,$self);
		     $query = $this->db->get();  
		     return $query->num_rows();  
		}

		function get_all_data($employment_status,$employee_id,$pay_basis,$division_id,$year,$month,$self){  
		    $this->db->select($this->table."*");  
		    $this->db->from($this->table);
		    $this->db->join("tblfieldpositions","tblemployees.position_id = tblfieldpositions.id","left");
		    $this->db->join("tblfieldagencies","tblemployees.agency_id = tblfieldagencies.id","left");
		    $this->db->join("tblfieldoffices","tblemployees.office_id = tblfieldoffices.id","left");
		    $this->db->join("tblfielddepartments","tblemployees.division_id = tblfielddepartments.id","left");
		    $this->db->join("tblfieldlocations","tblemployees.location_id = tblfieldlocations.id","left");
		    $this->db->join("tblfieldfundsources","tblemployees.fund_source_id = tblfieldfundsources.id","left");
		    $this->db->join("tblfieldloans","tblemployees.loans_id = tblfieldloans.id","left");
		    $this->db->join("tblfieldbudgetclassifications","tblemployees.budget_classification_id = tblfieldbudgetclassifications.id","left");
			$this->db->where("DATE(tblemployees.date_of_permanent) <=","('".$year."-".$month."-01')");
			$this->db->where('tblemployees.employment_status = "Active"');

			if($self){
				$this->db->where('tblemployees.id="'.Helper::get("id").'"');
			}else{
				if(isset($employee_id) && $employee_id != null)
					$this->db->where('tblemployees.id="'.$employee_id.'"');
				if((isset($pay_basis) && $pay_basis != null))
					$this->db->where('tblemployees.pay_basis="'.$pay_basis.'"');
				if((isset($division_id) && $division_id != null))
					$this->db->where($this->table.'.division_id="'.$division_id.'"');
				else{
					$this->db->where('1=0');
				}
			}
		    return $this->db->count_all_results();  
		} 
		
		//End Fetch
		function getEmployee($employment_status,$employee_id,$pay_basis,$division_id,$location_id,$year,$month, $self){
			$this->make_query($employment_status,$employee_id,$pay_basis,$division_id,$location_id,$year,$month, $self);
			$query = $this->db->get();
			return $query->result_array();  
		}

		function getBalanceBrought($employee_id,$leave_year){
			$this->db->select("*");
			$this->db->from("tblleavebalanceposting");
			$this->db->where("employee_id = '".$employee_id."'"); 
			$this->db->where("leave_year = '".$leave_year."'"); 
			$this->db->where("is_active = 1");
			$query = $this->db->get();  
		    return $query->result_array();  
		}

		function getExhaustedBrought($employee_id,$leave_year,$leave_month){
			$this->db->select("*");
			$this->db->from("tblleaveexhaustedposting");
			$this->db->where("employee_id = '".$employee_id."'"); 
			$this->db->where("leave_year = '".$leave_year."'"); 
			$this->db->where("leave_month = '".$leave_month."'"); 
			$this->db->where("is_active = 1"); 
			$query = $this->db->get();  
		    return $query->result_array();  
		}

		function getEarnedConversion($present_day){
			$this->db->select("*");
			$this->db->from("tblleavecreditsearnedconversion");
			$this->db->where("present_days = $present_day"); 
			$query = $this->db->get();  
		    return $query->result_array();  
		}
		function getLeave($month,$employee_id,$year,$type){
			// if($type == "vacation"){
			// 	$sql = "SELECT a.*,SUM(number_of_days) -
			// 		(
			// 			select COUNT(*) FROM tbldtr dtr
			// 			left join tblemployees emp ON CAST(dtr.scanning_no AS INT) = CAST(DECRYPTER(emp.employee_number,'sunev8clt1234567890',emp.id) AS INT)
			// 			WHERE DATE(dtr.transaction_date) IN
			// 				(
			// 					select lvd.days_of_leave from tblleavemanagement lv right join tblleavemanagementdaysleave lvd on lv.id = lvd.id
			// 					where (MONTH(lvd.days_of_leave) = '".$month."'
			// 					AND YEAR(lvd.days_of_leave) = '".$year."')
			// 					AND employee_id = '".$employee_id."'
			// 					AND lv.status = 5
			// 					AND (lv.type = '".$type."' OR lv.type = 'force')
			// 				)
			// 			AND emp.id = '".$employee_id."'
			// 			AND (dtr.remarks != 'Absent' OR dtr.adjustment_remarks != 'Absent' OR dtr.remarks = 'Rest Day' OR dtr.adjustment_remarks = 'Rest Day')
			// 			AND (IFNULL(CAST(dtr.approve_offset_hrs AS INT),0) < 8)
			// 			AND (
			// 				(check_in  IS NOT NULL OR break_out  IS NOT NULL OR break_in  IS NOT NULL OR check_out  IS NOT NULL)
			// 				OR
			// 				(adjustment_check_in  IS NOT NULL OR adjustment_break_out  IS NOT NULL OR adjustment_break_in  IS NOT NULL OR adjustment_check_out  IS NOT NULL)
			// 				)
			// 		) as number_of_days
			// 		FROM tblleavemanagement a
			// 		WHERE	(
			// 					((SELECT MONTH(b.days_of_leave) FROM tblleavemanagementdaysleave b WHERE a.id = b.id AND YEAR(b.days_of_leave) = '".$year."' LIMIT 0,1) = '".$month."')
			// 					OR
			// 					((SELECT MONTH(SUBSTRING_INDEX(b.days_of_leave,' - ',-1)) FROM tblleavemanagementdaysleave b WHERE a.id = b.id AND YEAR(SUBSTRING_INDEX(b.days_of_leave,' - ',-1)) = '".$year."' LIMIT 0,1) = '".$month."' )
			// 				) AND employee_id = '".$employee_id."' AND (type='".$type."' OR type='force') AND a.is_active = 1 AND status = 5";
			// 				#GROUP BY type";
			// }else{
				$sql = "SELECT a.*,SUM(number_of_days) -
				(
					select COUNT(*) FROM tbldtr dtr
					left join tblemployees emp ON CAST(dtr.scanning_no AS INT) = CAST(DECRYPTER(emp.employee_number,'sunev8clt1234567890',emp.id) AS INT)
					WHERE DATE(dtr.transaction_date) IN
						(
							select lvd.days_of_leave from tblleavemanagement lv right join tblleavemanagementdaysleave lvd on lv.id = lvd.id
							where (MONTH(lvd.days_of_leave) = '".$month."'
							AND YEAR(lvd.days_of_leave) = '".$year."')
							AND employee_id = '".$employee_id."'
							AND lv.status = 5
							AND lv.type = '".$type."'
						)
					AND emp.id = '".$employee_id."'
					AND (dtr.remarks != 'Absent' OR dtr.adjustment_remarks != 'Absent' OR dtr.remarks = 'Rest Day' OR dtr.adjustment_remarks = 'Rest Day')
					AND (IFNULL(CAST(dtr.approve_offset_hrs AS INT),0) < 8)
					AND (
						(check_in  IS NOT NULL OR break_out  IS NOT NULL OR break_in  IS NOT NULL OR check_out  IS NOT NULL)
						OR
						(adjustment_check_in  IS NOT NULL OR adjustment_break_out  IS NOT NULL OR adjustment_break_in  IS NOT NULL OR adjustment_check_out  IS NOT NULL)
						)
				) as number_of_days
				FROM tblleavemanagement a
				WHERE	(
							((SELECT MONTH(b.days_of_leave) FROM tblleavemanagementdaysleave b WHERE a.id = b.id AND YEAR(b.days_of_leave) = '".$year."' LIMIT 0,1) = '".$month."')
							OR
							((SELECT MONTH(SUBSTRING_INDEX(b.days_of_leave,' - ',-1)) FROM tblleavemanagementdaysleave b WHERE a.id = b.id AND YEAR(SUBSTRING_INDEX(b.days_of_leave,' - ',-1)) = '".$year."' LIMIT 0,1) = '".$month."' )
						) AND employee_id = '".$employee_id."' AND type='".$type."' AND a.is_active = 1 AND status = 5 GROUP BY type";
			// }	
			$query = $this->db->query($sql)->result_array();

			if(sizeof($query) > 0){
				foreach($query as $k => $v){
					if($v["number_of_days"] == 0){
						unset($query[$k]);
					}
				}
			}

			return $query;
		}

		function getLeaveIsMonth($month,$employee_id,$year){
			$sql = "SELECT a.*,SUM(number_of_days) AS number_of_days FROM tblleavemanagement a
					WHERE	(
						((SELECT MONTH(b.days_of_leave) FROM tblleavemanagementdaysleave b WHERE a.id = b.id AND YEAR(b.days_of_leave) = '".$year."' LIMIT 0,1) = '".$month."')
								OR
								((SELECT MONTH(SUBSTRING_INDEX(b.days_of_leave,' - ',-1)) FROM tblleavemanagementdaysleave b WHERE a.id = b.id AND YEAR(SUBSTRING_INDEX(b.days_of_leave,' - ',-1)) = '".$year."' LIMIT 0,1) = '".$month."' )
							) AND (SELECT YEAR(b.days_of_leave) FROM tblleavemanagementdaysleave b WHERE a.id = b.id LIMIT 0,1) = '".$year."' AND employee_id = '".$employee_id."' AND a.is_active = 1 AND status = 5 GROUP BY type";
			$query = $this->db->query($sql);
			return $query->result_array();
		}

		function getUTConvertsions($hr, $min){
			$hr_sql = "SELECT equiv_day FROM tblleaveconversionfractions WHERE time_amount = ".$hr." AND time_type = 'hr'";
			$min_sql = "SELECT equiv_day FROM tblleaveconversionfractions WHERE time_amount = ".$min." AND time_type = 'min'";
			$hr_result = $this->db->query($hr_sql)->row_array();
			$min_result = $this->db->query($min_sql)->row_array();
			$total = ($hr_result != null ? $hr_result["equiv_day"] : 0.000) + ($min_result != null ? $min_result["equiv_day"] : 0.000);
			return $total;
		}

		// function countDays($year, $month, $ignore, $terminal, $emp_shift_days) {
		// 	$count = $ends = 0;
		// 	$arr = [];
		// 	$counter = mktime(0, 0, 0, $month, 1, $year);
		// 	while (date("n", $counter) == $month) {
		// 		if(in_array(date("l", $counter),$emp_shift_days)){
		// 			if($terminal) if(date("Y-m-d", $counter) == $terminal["is_terminal"]) break;
		// 			if (in_array(date("w", $counter), $ignore) == false) $count++;
		// 		}else{
		// 			$ends++;
		// 		}
		// 		$counter = strtotime("+1 day", $counter);
		// 	}
		// 	$emp_shift_days = implode("', '",$emp_shift_days);
		// 	$query = $this->db->query("SELECT COUNT(*) as no_days FROM tblfieldholidays WHERE DAYNAME(date) IN ('".$emp_shift_days."') AND MONTH(date) = '".$month."' AND YEAR(date) = '".$year."' AND is_active = 1")->row_array();
		// 	$arr["days"] = $count - $query["no_days"];
		// 	$arr["ends"] = $ends;
		// 	return $arr;
		// }

		function countDays($year, $month, $ignore, $terminal, $emp_shift_days) {
			$count = 0;
			$counter = mktime(0, 0, 0, $month, 1, $year);
			while (date("n", $counter) == $month) {
				if(in_array(date("l", $counter),$emp_shift_days)){
					if($terminal) if(date("Y-m-d", $counter) == $terminal["is_terminal"]) break;
					if (in_array(date("w", $counter), $ignore) == false) $count++;
				}
				$counter = strtotime("+1 day", $counter);
			}
			// $emp_shift_days = implode(", ",$emp_shift_days);
			$emp_shift_days = implode("','",$emp_shift_days);

			$query = $this->db->query("SELECT COUNT(*) as no_days FROM tblfieldholidays WHERE DAYNAME(date) IN ('".$emp_shift_days."') AND MONTH(date) = '".$month."' AND YEAR(date) = '".$year."' AND (holiday_type != 'Suspension' OR (holiday_type = 'Suspension' && time_suspension = '08:00 AM')) ")->row_array();
			
			$count -= $query["no_days"];

			return $count;
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
		
        function getUndertTime($month,$employee_id,$year, $isTerminal, $dtfiled, $terminal_date){
			$sql = "SELECT b.id, a.scanning_no, COUNT(*) as no_days, SUM(a.tardiness_hrs) as tardiness_hrs, SUM(a.tardiness_mins) as tardiness_mins, SUM(a.ut_hrs) as ut_hrs, SUM(a.ut_mins) as ut_mins, (SELECT COUNT(*) FROM tbldtr WHERE CAST(scanning_no AS INT) = CAST(DECRYPTER(b.employee_number,'sunev8clt1234567890',b.id) AS INT) AND approve_offset_hrs = 8 AND adjustment_remarks = 'offset' AND MONTH(transaction_date) = ".$month." AND YEAR(transaction_date) = ".$year.") as offset_days, SUM(a.approve_offset_hrs) as offset_hrs, SUM(a.approve_offset_mins) as offset_mins
			FROM tbldtr a
			LEFT JOIN tblemployees b ON CAST(a.scanning_no AS INT) = CAST(DECRYPTER(b.employee_number,'sunev8clt1234567890',b.id) AS INT)
			WHERE b.id = '".$employee_id."' AND MONTH(a.transaction_date) = ".$month." AND YEAR(a.transaction_date) = ".$year." AND (IFNULL(CAST(a.approve_offset_hrs AS INT),0) < 8)
			AND (remarks != 'Absent' OR adjustment_remarks != 'Absent')
			AND (remarks != 'Rest Day' OR adjustment_remarks != 'Rest Day')
			AND (remarks != 'Leave' OR adjustment_remarks != 'Leave')";
			$query = $this->db->query($sql)->row_array();
			// var_dump("1".json_encode($query));
			$sql2 = "SELECT b.id, a.scanning_no, COUNT(*) as no_days FROM tbldtr a
			LEFT JOIN tblemployees b ON CAST(a.scanning_no AS INT) = CAST(DECRYPTER(b.employee_number,'sunev8clt1234567890',b.id) AS INT)
			WHERE b.id = '".$employee_id."' AND MONTH(a.transaction_date) = ".$month." AND YEAR(a.transaction_date) = ".$year." AND (IFNULL(CAST(a.approve_offset_hrs AS INT),0) < 8)
			AND (remarks != 'Absent' OR adjustment_remarks != 'Absent')
			AND (remarks != 'Rest Day' OR adjustment_remarks != 'Rest Day')
			AND (remarks != 'Leave' OR adjustment_remarks != 'Leave')
			AND source = 'excel_dtr'";
			$query_upload = $this->db->query($sql2)->row_array();
 
			$sql_no_dtr = "SELECT COUNT(*) as no_days FROM tbldtr a
			LEFT JOIN tblemployees b ON CAST(a.scanning_no AS INT) = CAST(DECRYPTER(b.employee_number,'sunev8clt1234567890',b.id) AS INT)
			WHERE b.id = '".$employee_id."' AND MONTH(a.transaction_date) = ".$month." AND YEAR(a.transaction_date) = ".$year." AND COALESCE(a.check_in,a.break_out,break_in,a.check_out,a.adjustment_check_in,a.adjustment_break_out,a.adjustment_break_in,a.adjustment_check_out,a.remarks,'empty') = 'empty'";
			$sql_no_dtr .= " AND (remarks != 'Rest Day' OR adjustment_remarks != 'Rest Day')";
			// $sql_no_dtr .= " AND (remarks != 'Leave' OR adjustment_remarks != 'Leave')";
			$sql_no_dtr .= " AND (remarks != 'Holiday' OR adjustment_remarks != 'Holiday')";
			// if($isTerminal == true && $dtfiled == $month)
			// 	$sql_no_dtr .= " AND DATE(transaction_date) < DATE('".$terminal_date."')";
			$query_no_dtr = $this->db->query($sql_no_dtr)->row_array();
			// var_dump("2".json_encode($query_no_dtr));
			

			$sql_terminated_dtr = "SELECT COUNT(*) as no_days FROM tbldtr a
			LEFT JOIN tblemployees b ON CAST(a.scanning_no AS INT) = CAST(DECRYPTER(b.employee_number,'sunev8clt1234567890',b.id) AS INT)
			WHERE b.id = '".$employee_id."' AND MONTH(a.transaction_date) = ".$month." AND YEAR(a.transaction_date) = ".$year." AND COALESCE(a.check_in,a.break_out,break_in,a.check_out,a.adjustment_check_in,a.adjustment_break_out,a.adjustment_break_in,a.adjustment_check_out,a.remarks,'empty') = 'empty'
			AND (remarks != 'Rest Day' OR adjustment_remarks != 'Rest Day')
			AND (remarks != 'Leave' OR adjustment_remarks != 'Leave')
			AND (remarks != 'Holiday' OR adjustment_remarks != 'Holiday')";
			if($isTerminal == true && $dtfiled == $month)
				$sql_terminated_dtr .= " AND DATE(transaction_date) > DATE('".$terminal_date."')";
			$query_terminated_dtr = $this->db->query($sql_terminated_dtr)->row_array();
			

			$sql_holiday_dtr = "SELECT COUNT(*) as no_days FROM tbldtr a
			LEFT JOIN tblemployees b ON CAST(a.scanning_no AS INT) = CAST(DECRYPTER(b.employee_number,'sunev8clt1234567890',b.id) AS INT)
			WHERE b.id = '".$employee_id."' AND MONTH(a.transaction_date) = ".$month." AND YEAR(a.transaction_date) = ".$year." AND COALESCE(a.check_in,a.break_out,break_in,a.check_out,a.adjustment_check_in,a.adjustment_break_out,a.adjustment_break_in,a.adjustment_check_out,a.remarks,'empty') = 'empty'
			AND (remarks != 'Holiday' OR adjustment_remarks = 'Holiday')";
			if($isTerminal == true && $dtfiled == $month)
				$sql_holiday_dtr .= " AND DATE(transaction_date) > DATE('".$terminal_date."')";
			$query_holiday_dtr = $this->db->query($sql_holiday_dtr)->row_array();
			
			// $query["no_days"] = $query_no_dtr["no_days"] > 0 ? (int)$query["no_days"] - (int)$query_no_dtr["no_days"] : 0;
			$query["no_days_absent"] = $query_no_dtr["no_days"];
			$query["no_days_upload_dtr"] = $query_upload["no_days"];
			$query["no_days_holiday"] = $query_holiday_dtr["no_days"];
			$query["no_days_terminated"] = ($isTerminal == true && $dtfiled == $month) ? $query_terminated_dtr["no_days"] + 1 : 0;
			// $query["ut_hrs"] = $query["ut_hrs"] - (($isTerminal == true && $dtfiled == $month) ? $sql_terminated_dtr["no_days"] * 8 : 0);
			// var_dump("3".json_encode($query));
			return $query;
		}
		
        function getUndertTimeOld($month,$employee_id,$year){
			$sql = "SELECT b.id, a.scanning_no, COUNT(*) as no_days, SUM(a.tardiness_hrs) as tardiness_hrs, SUM(a.tardiness_mins) as tardiness_mins, SUM(a.ut_hrs) as ut_hrs, SUM(a.ut_mins) as ut_mins, (SELECT COUNT(*) FROM tbldtr WHERE CAST(scanning_no AS INT) = CAST(DECRYPTER(b.employee_number,'sunev8clt1234567890',b.id) AS INT) AND approve_offset_hrs = 8 AND adjustment_remarks = 'offset') as offset_days, SUM(a.approve_offset_hrs) as offset_hrs, SUM(a.approve_offset_mins) as offset_mins
			FROM tbldtr a
			LEFT JOIN tblemployees b ON CAST(a.scanning_no AS INT) = CAST(DECRYPTER(b.employee_number,'sunev8clt1234567890',b.id) AS INT)
			WHERE b.id = '".$employee_id."' AND MONTH(a.transaction_date) = ".$month." AND YEAR(a.transaction_date) = ".$year." AND (remarks != 'Absent' OR adjustment_remarks != 'Absent') AND (IFNULL(CAST(a.approve_offset_hrs AS INT),0) < 8)
			AND (remarks != 'Rest Day' OR adjustment_remarks != 'Rest Day')";
			$query = $this->db->query($sql)->row_array();

			$sql_no_dtr = "SELECT COUNT(*) as no_days FROM tbldtr a
			LEFT JOIN tblemployees b ON CAST(a.scanning_no AS INT) = CAST(DECRYPTER(b.employee_number,'sunev8clt1234567890',b.id) AS INT)
			WHERE b.id = '".$employee_id."' AND MONTH(a.transaction_date) = ".$month." AND YEAR(a.transaction_date) = ".$year." AND COALESCE(a.check_in,a.break_out,break_in,a.check_out,a.adjustment_check_in,a.adjustment_break_out,a.adjustment_break_in,a.adjustment_check_out,a.remarks,'empty') = 'empty'
			AND (remarks != 'Rest Day' OR adjustment_remarks != 'Rest Day')
			AND (remarks != 'Absent' OR adjustment_remarks != 'Absent')
			AND (remarks != 'Holiday' OR adjustment_remarks != 'Holiday')";
			$query_no_dtr = $this->db->query($sql_no_dtr)->row_array();$query["no_days"] = $query_no_dtr["no_days"] > 0 ? (int)$query["no_days"] - (int)$query_no_dtr["no_days"] : 0;
			return $query;
		}
		
        function getBalanceAsOf($month,$employee_id,$year){
			$sql = "SELECT * FROM tblleavebalancemonthlyasof WHERE id = '".$employee_id."' AND month = ".(int)$month." AND year = ".$year." LIMIT 1";
			$query = $this->db->query($sql)->row_array();
			return $query;
		}
		
		function getUTime($month,$employee_id,$year){
			$sql = '
			SELECT CONCAT(
			CASE
			WHEN (SELECT SUM(cnt) FROM (SELECT 1 AS cnt FROM (SELECT log_id,transaction_date FROM tbltimekeepingdailytimerecord WHERE employee_number = (SELECT DECRYPTER(employee_number,"sunev8clt1234567890",id) FROM tblemployees WHERE id = "'.$employee_id.'") AND MONTH(transaction_date) = "'.$month.'" AND YEAR(transaction_date) = "'.$year.'" GROUP BY transaction_date UNION ALL SELECT leave_id,transaction_date FROM tbltimekeepingdailytimerecordadjustments WHERE employee_number = (SELECT DECRYPTER(employee_number,"sunev8clt1234567890",id) FROM tblemployees WHERE id = "'.$employee_id.'") AND MONTH(transaction_date) = "'.$month.'" AND YEAR(transaction_date) = "'.$year.'" GROUP BY transaction_date) AS attendance GROUP BY transaction_date) AS attendance_cnt) >= 22 THEN "0"
			WHEN (SELECT SUM(cnt) FROM (SELECT 1 AS cnt FROM (SELECT log_id,transaction_date FROM tbltimekeepingdailytimerecord WHERE employee_number = (SELECT DECRYPTER(employee_number,"sunev8clt1234567890",id) FROM tblemployees WHERE id = "'.$employee_id.'") AND MONTH(transaction_date) = "'.$month.'" AND YEAR(transaction_date) = "'.$year.'" GROUP BY transaction_date UNION ALL SELECT leave_id,transaction_date FROM tbltimekeepingdailytimerecordadjustments WHERE employee_number = (SELECT DECRYPTER(employee_number,"sunev8clt1234567890",id) FROM tblemployees WHERE id = "'.$employee_id.'") AND MONTH(transaction_date) = "'.$month.'" AND YEAR(transaction_date) = "'.$year.'" GROUP BY transaction_date) AS attendance GROUP BY transaction_date) AS attendance_cnt) IS NULL THEN "22"
			ELSE 22 - (SELECT SUM(cnt) FROM (SELECT 1 AS cnt FROM (SELECT log_id,transaction_date FROM tbltimekeepingdailytimerecord WHERE employee_number = (SELECT DECRYPTER(employee_number,"sunev8clt1234567890",id) FROM tblemployees WHERE id = "'.$employee_id.'") AND MONTH(transaction_date) = "'.$month.'" AND YEAR(transaction_date) = "'.$year.'" GROUP BY transaction_date UNION ALL SELECT leave_id,transaction_date FROM tbltimekeepingdailytimerecordadjustments WHERE employee_number = (SELECT DECRYPTER(employee_number,"sunev8clt1234567890",id) FROM tblemployees WHERE id = "'.$employee_id.'") AND MONTH(transaction_date) = "'.$month.'" AND YEAR(transaction_date) = "'.$year.'" GROUP BY transaction_date) AS attendance GROUP BY transaction_date) AS attendance_cnt)
			END,":",a.total_ut_hrs,":",a.total_ut_min) AS utime_amt FROM tbltransactionsprocesspayrolldeductions AS a LEFT JOIN tblfieldperiodsettings AS b ON a.payroll_period_id = b.id WHERE employee_id = "'.$employee_id.'" AND YEAR(b.payroll_period) = "'.$year.'" AND MONTH(b.payroll_period) = "'.$month.'"
			';
			$query = $this->db->query($sql);
			return $query->result_array();
		}

		function getHolidays($month, $employee_id, $year) {
			$sql = 'SELECT COUNT(1) AS addtl_days FROM tblfieldholidays WHERE MONTH(date) = "'.$month.'" AND YEAR(date) = "'.$year.'" AND date NOT IN (SELECT transaction_date FROM tbltimekeepingdailytimerecord WHERE employee_number = CAST((SELECT DECRYPTER(tblemployees.employee_id_number,"sunev8clt1234567890",tblemployees.id) FROM tblemployees WHERE id = "'.$employee_id.'") AS SIGNED)) AND date NOT IN (SELECT transaction_date FROM tbltimekeepingdailytimerecordadjustments WHERE employee_number = CAST((SELECT DECRYPTER(tblemployees.employee_id_number,"sunev8clt1234567890",tblemployees.id) FROM tblemployees WHERE id = "'.$employee_id.'") AS SIGNED))';
			$query = $this->db->query($sql);
			return $query->result_array();
		}
		public function getWorkSuspension($month,$year){
			$sql = "SELECT * FROM tblfieldholidays a WHERE is_active = 1 AND holiday_type='Suspension' AND MONTH(a.date) = ".$month." AND YEAR(a.date)= ".$year."";
			$query = $this->db->query($sql);
			return $query->result_array();
		}

		function getLeaveBalance($employee_id,$year){
			$sql = "SELECT * FROM tblleavebalance WHERE id = '".$employee_id."' AND year = '".$year."'";
			$query = $this->db->query($sql);
			return $query->result_array();
		}

		function getLeaveDays($month,$year,$employee_id,$type){
			// if($type == "vacation"){
			// 	$sql = "	SELECT
			// 			a.*,
			// 			(SELECT dtr.transaction_date FROM tbldtr dtr
			// 			LEFT JOIN tblemployees emp ON CAST(dtr.scanning_no AS INT) = CAST(DECRYPTER(emp.employee_number,'sunev8clt1234567890',emp.id) AS INT)
			// 			WHERE emp.id = '".$employee_id."' AND dtr.transaction_date = a.days_of_leave
			// 			AND (dtr.remarks != 'Absent' OR dtr.adjustment_remarks != 'Absent' OR dtr.remarks = 'Rest Day' OR dtr.adjustment_remarks = 'Rest Day')
			// 			AND (IFNULL(CAST(dtr.approve_offset_hrs AS INT),0) < 8)
			// 			AND (
			// 				(check_in  IS NOT NULL OR break_out  IS NOT NULL OR break_in  IS NOT NULL OR check_out  IS NOT NULL)
			// 				OR
			// 				(adjustment_check_in  IS NOT NULL OR adjustment_break_out  IS NOT NULL OR adjustment_break_in  IS NOT NULL OR adjustment_check_out  IS NOT NULL)
			// 				)
			// 			) as match_date
			// 		FROM tblleavemanagementdaysleave a 
			// 		WHERE	(
			// 					(MONTH(days_of_leave) = '".$month."' AND YEAR(days_of_leave) = '".$year."')
			// 					OR
			// 					(MONTH(SUBSTRING_INDEX(days_of_leave,' - ',-1)) = '".$month."' AND YEAR(days_of_leave) = '".$year."')
			// 				)  
			// 		AND (SELECT employee_id FROM tblleavemanagement b WHERE b.id=a.id) = '".$employee_id."'
			// 		AND ((SELECT type FROM tblleavemanagement b WHERE b.id=a.id) = '".$type."' OR (SELECT type FROM tblleavemanagement b WHERE b.id=a.id) = 'force')
			// 		AND a.is_active = 1 AND (SELECT b.status FROM tblleavemanagement b WHERE b.id=a.id ) = 5";
			// }else{
				$sql = "	SELECT
						a.*,
						(SELECT dtr.transaction_date FROM tbldtr dtr
						LEFT JOIN tblemployees emp ON CAST(dtr.scanning_no AS INT) = CAST(DECRYPTER(emp.employee_number,'sunev8clt1234567890',emp.id) AS INT)
						WHERE emp.id = '".$employee_id."' AND dtr.transaction_date = a.days_of_leave
						AND (dtr.remarks != 'Absent' OR dtr.adjustment_remarks != 'Absent' OR dtr.remarks = 'Rest Day' OR dtr.adjustment_remarks = 'Rest Day')
						AND (IFNULL(CAST(dtr.approve_offset_hrs AS INT),0) < 8)
						AND (
							(check_in  IS NOT NULL OR break_out  IS NOT NULL OR break_in  IS NOT NULL OR check_out  IS NOT NULL)
							OR
							(adjustment_check_in  IS NOT NULL OR adjustment_break_out  IS NOT NULL OR adjustment_break_in  IS NOT NULL OR adjustment_check_out  IS NOT NULL)
							)
						) as match_date
					FROM tblleavemanagementdaysleave a 
					WHERE	(
								(MONTH(days_of_leave) = '".$month."' AND YEAR(days_of_leave) = '".$year."')
								OR
								(MONTH(SUBSTRING_INDEX(days_of_leave,' - ',-1)) = '".$month."' AND YEAR(days_of_leave) = '".$year."')
							)  
					AND (SELECT employee_id FROM tblleavemanagement b WHERE b.id=a.id) = '".$employee_id."'
					AND (SELECT type FROM tblleavemanagement b WHERE b.id=a.id) = '".$type."'
					AND a.is_active = 1 AND (SELECT b.status FROM tblleavemanagement b WHERE b.id=a.id ) = 5";
			// }
			$query = $this->db->query($sql)->result_array();
			if(sizeof($query) > 0){
				foreach($query as $k => $v){
					if($v["match_date"] != "" || $v["match_date"] != null){
						unset($query[$k]);
					}
				}
			}
			return $query;
		}

		function getMonetization($month,$year,$employee_id){
			$sql = "SELECT * FROM tblleavemanagement WHERE employee_id = '".$employee_id."' AND type = 'monetization' AND status = 5 AND MONTH(date_filed) = '".$month."' AND YEAR(date_filed) = ".$year;
			$query = $this->db->query($sql);
			return $query->result_array();
		}

		function getTerminal($year,$employee_id){
			$sql = "SELECT * FROM tblleavemanagement WHERE employee_id = '".$employee_id."' AND type = 'terminal' AND status = 5";// AND YEAR(is_terminal) = ".$year;
			$query = $this->db->query($sql);
			return $query->row_array();
		}

		function getConversionFractions($time_amount,$time_type){
			$query = $this->db->select('*')->from('tblleaveconversionfractions')->where(array('time_amount'=>$time_amount,'time_type'=>$time_type))->get();
			$data = $query->result_array();
			return $data;
		}

		public function getEmployeeList($pay_basis,$location_id,$division_id = null, $specific = null, $leave_grouping_id = null, $payroll_grouping_id = null) {
			$this->select_column[] = 'c.agency_name';
			$this->select_column[] = 'd.name';
			$this->select_column[] = 'e.department_name';
			$this->select_column[] = 'f.name';
			$sql = "SELECT tblemployees.id, tblemployees.employee_number, tblemployees.employee_id_number, tblemployees.first_name, tblemployees.middle_name, tblemployees.last_name, tblemployees.shift_id, c.code AS agency_code, d.name AS office_name, e.department_name AS department_name, f.name AS location_name,
				tblemployees.employment_status,tblemployees.date_of_permanent,tblemployees.present_day,tblemployees.pay_basis,tblemployees.location_id,tblemployees.division_id,tblemployees.leave_grouping_id,tblemployees.payroll_grouping_id
			FROM tblemployees
			LEFT JOIN tblfieldagencies c ON tblemployees.agency_id = c.id
			LEFT JOIN tblfieldoffices d ON tblemployees.office_id = d.id
			LEFT JOIN tblfielddepartments e ON tblemployees.division_id = e.id
			LEFT JOIN tblfieldlocations f ON tblemployees.location_id = f.id";
			$sql .= isset($pay_basis) ? " WHERE tblemployees.pay_basis='" . $pay_basis . "'" : "";
			$sql .= " AND tblemployees.employment_status = 'Active' ";
			if(isset($location_id) && $location_id != null && $location_id != "")
				$sql .= " AND tblemployees.location_id = ".$location_id." ";
			if(isset($leave_grouping_id) && $leave_grouping_id != null && $leave_grouping_id != "")
				$sql .= " AND tblemployees.leave_grouping_id = ".$leave_grouping_id." ";
			if(isset($payroll_grouping_id) && $payroll_grouping_id != null && $payroll_grouping_id != "")
				$sql .= " AND tblemployees.payroll_grouping_id = ".$payroll_grouping_id." ";
			$sql .= isset($division_id) && $division_id != null && $division_id != "" ? " AND tblemployees.division_id='" . $division_id . "' " : "";
			if(isset($specific) && $specific != null && $specific != "")  
			{  

				$sql.= "AND (";
				$first = true;
			 	foreach ($this->select_column as $key => $value) {
			 		if($value == "tblemployees.first_name" || $value == "tblemployees.last_name" || $value == "tblemployees.middle_name" || $value == "tblemployees.employee_number" || $value == "tblemployees.employee_id_number")  {
			 			if($first){
			 				$sql .= " DECRYPTER($value,'sunev8clt1234567890',$this->table.id) = '$specific' ";
			 			}
			 			else{
			 				$sql .= " OR DECRYPTER($value,'sunev8clt1234567890',$this->table.id) = '$specific' ";
			 			}
			 			 

			 		}
			 		else{
			 			if($first){
			 				$sql .= " $value = '$specific' ";
			 			}
			 			else{
			 				$sql .= " OR $value = '$specific' ";
			 			}
			 			
			 		}
			 		$first = false;
			 	}
			 	$sql .= " OR CONCAT(DECRYPTER(tblemployees.first_name,'sunev8clt1234567890',$this->table.id),' ',DECRYPTER(tblemployees.middle_name,'sunev8clt1234567890',$this->table.id),' ',DECRYPTER(tblemployees.last_name,'sunev8clt1234567890',$this->table.id)) = '$specific' "; 
			    $sql.= ")";
			}
			$query = $this->db->query($sql);
			$rows = $query->result_array();
			return $rows;
		}

		

		public function getShiftDetails($id){
			$sql = "SELECT DECRYPTER(tblemployees.employee_number,'sunev8clt1234567890',tblemployees.id) as scan_no,tblemployees.shift_date_effectivity as shiftDate,tblemployees.* FROM tblemployees WHERE id = '".$id."'";
			$query = $this->db->query($sql);
			return $query->row_array();
		}

		public function isDTRRequired($id){
			$sql = "SELECT * FROM tblfieldpositions a LEFT JOIN tblemployees b ON a.id = b.position_id WHERE b.id = '".$id."'";
			$query = $this->db->query($sql);
			return $query->row_array();
		}

		public function getShiftHistory($id){
			$query = $this->db->select("*")->from("tblemployeesshifthistory")->where("employee_id", $id)->order_by("previous_date_effectivity","DESC")->get();
			return $query->result_array();
		}

		public function getRegularShiftSchedule($id){
			$query = $this->db->select("*")->from("tbltimekeepingemployeeschedulesweekly a")->join("tbltimekeepingemployeeschedules b","b.id = a.shift_code_id","left")->where("b.id", $id)->where("a.is_restday", 0)->get();
			return $query->result_array();
		}

		public function getFlexibleShiftSchedule($id){
			$query = $this->db->select("*")->from("tbltimekeepingemployeeflexibleschedulesweekly a")->join("tbltimekeepingemployeeflexibleschedules b","b.id = a.shift_code_id","left")->where("b.id", $id)->where("a.is_restday", 0)->get();
			return $query->result_array();
		}

		public function getEmployeesList(){
			$query = $this->db->select("*")->from("tblemployees")->where("employment_status", "Active")->get();
			return $query->result_array();
		}

		public function addbalance($params){
			$code = "1";
			$message = "Failed to accumulate leave credits.";
			$this->db->trans_begin();
			$sql = "SELECT * FROM tblleavebalance WHERE employee_id = '".$params["employee_id"]."' AND scanning_no = '".$params["scanning_no"]."' AND year = '".$params["year"]."'";
			$isBalanceExist = $this->db->query($sql)->row_array();
			if($isBalanceExist) $this->db->where("employee_id", $params["employee_id"])->where("scanning_no",$params["scanning_no"])->where("year",$params["year"])->update("tblleavebalance",$data);
			else $this->db->insert('tblleavebalance', $params);
			if($this->db->trans_status() === TRUE){
				$code = "0";
				$this->db->trans_commit();
				$message = "Successfully accumulate leave credits.";
				$this->ModelResponse($code, $message);
				return true;		
			}	
			else {
				$this->db->trans_rollback();
				$this->ModelResponse($code, $message);
			}
			return false;
		}
		
		function saveRemainingBal($employee_id,$year,$vl,$sl){
			$employee = $this->getEmployeeById($employee_id);
			$params = array(
				"id"=> $employee_id,
				"scanning_no"=> $employee[0]['scanning_no'],
				"source_location"=> "server",
				"year"=> $year + 1,
				"vl"=> $vl,
				"sl"=> $sl,
				"total"=> number_format($vl + $sl, 3)
			);

			$code = "1";
			$message = "Failed to insert remaining leave credits.";
			$this->db->trans_begin();
			$sql = "SELECT * FROM tblleavebalance WHERE id = '".$params["id"]."' AND scanning_no = '".$params["scanning_no"]."' AND year = '".$params["year"]."'";
			$isBalanceExist = $this->db->query($sql)->row_array();

			if($isBalanceExist && $isBalanceExist['source_location'] == "excel_import"){
				// do nothing
			}else if($isBalanceExist && $isBalanceExist['source_location'] != "excel_import"){
				$this->db->where("id", $params["id"])->where("scanning_no",$params["scanning_no"])->where("year",$params["year"])->update("tblleavebalance",$params);
			}else{
				$this->db->insert('tblleavebalance', $params);
			}

			if($this->db->trans_status() === TRUE){
				$code = "0";
				$this->db->trans_commit();
				$message = "Successfully inserted leave credits.";
				$this->ModelResponse($code, $message);
				return true;		
			}	
			else {
				$this->db->trans_rollback();
				$this->ModelResponse($code, $message);
			}
			return false;
		}
		
		function getEmployeeById($employee_id) {
			$params = array($employee_id);
			$sql = "SELECT *, DECRYPTER(employee_number,'sunev8clt1234567890',id) as scanning_no FROM tblemployees WHERE id = ? AND is_active = 1";
			$query = $this->db->query($sql, $params);
			$employee = $query->result_array();
			return $employee;
		}

		function leaveAvailable($id){
			$sql = 'SELECT
					COUNT(lv.number_of_days) as nodays
				FROM tblleavemanagementdaysleave lvd
				LEFT JOIN tblleavemanagement lv on lv.id = lvd.id
				where lv.employee_id = "'.$id.'" AND lv.status = 5 AND lv.type = "special" AND YEAR(lvd.days_of_leave) = '.date("Y").' GROUP BY lvd.id';
			$result = $this->db->query($sql)->result_array();
			$sql2 = 'SELECT
					COUNT(lv.number_of_days) as nodays
				FROM tblleavemanagementdaysleave lvd
				LEFT JOIN tblleavemanagement lv on lv.id = lvd.id
				where lv.employee_id = "'.$id.'" AND lv.status = 5 AND lv.type = "force" AND YEAR(lvd.days_of_leave) = '.date("Y").' GROUP BY lvd.id';
			$result2 = $this->db->query($sql2)->result_array();
			return array("availablespecial"=> 3-array_sum(array_column($result, "nodays")),"availableforce"=> 5-array_sum(array_column($result2, "nodays")));
		}
	}
?>