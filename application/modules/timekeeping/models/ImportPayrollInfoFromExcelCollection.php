<?php
	class ImportPayrollInfoFromExcelCollection extends Helper {
		public function __construct() {
			$this->load->model('HelperDao');
			$this->load->model("Security");
			ModelResponse::busy();
		}

		public function getShiftDetails($id){
			$sql = "SELECT DECRYPTER(tblemployees.employee_number,'sunev8clt1234567890',tblemployees.id) as scan_no,tblemployees.shift_date_effectivity as shiftDate,tblemployees.* FROM tblemployees WHERE CAST(DECRYPTER(employee_number,'sunev8clt1234567890',id) AS INT) = CAST('".$id."' AS INT)";
			$query = $this->db->query($sql);
			return $query->row_array();
		}

		public function getShiftHistory($id){
			$query = $this->db->select("*")->from("tblemployeesshifthistory")->where("employee_id", $id)->order_by("previous_date_effectivity","DESC")->get();
			return $query->result_array();
		}

		public function getRegularShiftSchedule($id){
			$query = $this->db->select("*")->from("tbltimekeepingemployeeschedulesweekly a")->join("tbltimekeepingemployeeschedules b","b.id = a.shift_code_id","left")->where("b.id", $id)->get();
			return $query->result_array();
		}

		public function getFlexibleShiftSchedule($id){
			$query = $this->db->select("*")->from("tbltimekeepingemployeeflexibleschedulesweekly a")->join("tbltimekeepingemployeeflexibleschedules b","b.id = a.shift_code_id","left")->where("b.id", $id)->get();
			return $query->result_array();
		}

		public function getFlagCeremony($date){
			$sql = "SELECT * FROM tbltimekeepingflagceremonyschedules WHERE DATE(flagdateceremony) = DATE('".$date."') AND is_active = 1";
			$query = $this->db->query($sql);
			return $query->row_array();
		}

		public function getPositionBreak($id){
			$sql = "SELECT b.is_break FROM tblemployees a LEFT JOIN tblfieldpositions b ON a.position_id = b.id WHERE CAST(DECRYPTER(a.employee_number,'sunev8clt1234567890',a.id) AS INT) = CAST('".$id."' AS INT)";
			$query = $this->db->query($sql);
			return $query->row_array();
		}

		public function getHoliday($date){
			$sql = "SELECT * FROM tblfieldholidays a WHERE is_active = 1 AND DATE(a.date) = DATE('".$date."')";
			$query = $this->db->query($sql);
			return $query->row_array();
		}

		public function getOB($id,$date){
			$sql = "SELECT * FROM tbltimekeepinglocatorslips a LEFT JOIN tblemployees b ON a.employee_id = b.id WHERE a.status = 'APPROVED' AND DATE(a.transaction_date) = DATE('".$date."') AND CAST(DECRYPTER(b.employee_number,'sunev8clt1234567890',b.id) AS INT) = CAST('".$id."' AS INT)";
			$query = $this->db->query($sql);
			return $query->row_array();
		}

		public function getOffset($id,$date){
			$sql = "SELECT * FROM tblleavemanagement a LEFT JOIN tblemployees b ON a.employee_id = b.id WHERE DATE(a.offset_date_effectivity) = DATE('".$date."') AND a.status = 5 AND CAST(DECRYPTER(b.employee_number,'sunev8clt1234567890',b.id) AS INT) = CAST('".$id."' AS INT)";
			$query = $this->db->query($sql);
			return $query->row_array();
		}

		public function getIfDriver($id){
			$sql = "SELECT * FROM tblemployees a LEFT JOIN tblfieldpositions b ON a.position_id = b.id WHERE CAST(DECRYPTER(a.employee_number,'sunev8clt1234567890',a.id) AS INT) = CAST('".$id."' AS INT) AND is_driver = 1";
			$query = $this->db->query($sql);
			return $query->row_array();
		}

		public function getCheckifOTCertification($id, $date){
			$sql = "SELECT * FROM tbldtr WHERE CAST(scanning_no as INT) = CAST('".$id."' AS INT) AND transaction_date = '".$date."' AND (remarks = 'OT W/ Certification' OR adjustment_remarks = 'OT W/ Certification')";
			$query = $this->db->query($sql);
			return $query->row_array();
		}

		public function insert_daily_dtr($params){
			$data = array(
				"scanning_no" => $params["SCANNING_NUMBER"],
				"transaction_date" => $params["TRANSACTION_DATE"],
				"adjustment_check_in" => $params["ACTUAL_AM_ARRIVAL"],
				"adjustment_break_out" => $params["ACTUAL_AM_DEPARTURE"],
				"adjustment_break_in" => $params["ACTUAL_PM_ARRIVAL"],
				"adjustment_check_out" => $params["ACTUAL_PM_DEPARTURE"],
				"adjustment_ot_in" => $params["OT_ARRIVAL"],
				"adjustment_ot_out" => $params["OT_DEPARTURE"],
				"offset" => $params["OFFSET"],
				"approve_offset_hrs" => @$params["APPROVE_OFFSET_HRS"],
				"approve_offset_mins" => @$params["APPROVE_OFFSET_MINS"],
				"ot_hrs" => $params["OT_HRS"],
				"ot_mins" => $params["OT_MINS"],
				"adjustment_monetized" => $params["MONETIZED"],
				"tardiness_hrs" => $params["TARDINESS_HRS"],
				"tardiness_mins" => $params["TARDINESS_MINS"],
				"ut_hrs" => $params["UT_HRS"],
				"ut_mins" => $params["UT_MINS"],
				"adjustment_remarks" => $params["ADJUSTMENT_REMARKS"]
			);
			$sql = "SELECT * FROM tbldtr WHERE DATE(transaction_date) = DATE('".$params["TRANSACTION_DATE"]."') AND scanning_no = '".$params["SCANNING_NUMBER"]."'";
			$isTransExist = $this->db->query($sql)->row_array();
			if($isTransExist) $this->db->where("DATE(transaction_date)", $params["TRANSACTION_DATE"])->where("scanning_no",$params["SCANNING_NUMBER"])->update("tbldtr",$data);
			else $this->db->insert('tbldtr',$data);
			$helperDao = new HelperDao();
			if($this->db->trans_status() === TRUE){
				$code = "0";
				$this->db->trans_commit();
				$message = "Timelogs successfully imported from excel.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;
			}else {
				$this->db->trans_rollback();
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		public function updatePayrollInfo($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Scanning no. ".$params["scanning_no"]." failed to update payroll information.";
			
			$employee = $this->db->query("SELECT *,DECRYPTER(first_name,'sunev8clt1234567890',id) as fname,DECRYPTER(last_name,'sunev8clt1234567890',id) as lname FROM tblemployees WHERE CAST(DECRYPTER(employee_number,'sunev8clt1234567890',id) AS INT) = CAST('".$params["scanning_no"]."' AS INT)")->row_array();
			if($employee){
				$insert_params["start_date"] = date("m/d/Y", strtotime($params["start_date"]));
				$pay_basis = $params["payroll_schedule"] == "weekly" ? "Permanent" : "Permanent (Probationary)";
				$insert_params["pay_basis"] = $pay_basis;
				$position = $this->db->query("SELECT * FROM tblfieldpositions WHERE code = '".$params["plantilla"]."' LIMIT 1")->row_array();
				$salary = $this->db->query("SELECT * FROM tblfieldsalarygradesteps WHERE grade_id = ".$position["salary_grade_id"]." AND step = ".$position["salary_grade_step_id"]." AND is_active = 1 LIMIT 1")->row_array();

				$insert_params["position_id"] = $position["id"];
				$insert_params["salary"] = $salary["salary"];
				$percutoff = (float)$salary["salary"] / ($pay_basis == "Permanent" ? 4 : 2);
				if($pay_basis == "Permanent"){
					$insert_params["cut_off_1"] = $percutoff;
					$insert_params["cut_off_2"] = $percutoff;
					$insert_params["cut_off_3"] = $percutoff;
					$insert_params["cut_off_4"] = $percutoff;
				}else{
					$insert_params["position_id"] = $percutoff;
					$insert_params["position_id"] = $percutoff;
					$insert_params["position_id"] = 0;
					$insert_params["position_id"] = 0;
				}
				$insert_params["regular_shift"] = $params["shift_type"];
				if($params["shift_type"] == 1){
					$insert_params["shift_id"] = $params["shift_schedule"];
					$insert_params["flex_shift_id"] = 0;
				}else{
					$insert_params["shift_id"] = 0;
					$insert_params["flex_shift_id"] = $params["shift_schedule"];
				}
				$insert_params["shift_date_effectivity"] = date("m/d/Y",strtotime($params["shift_date_effectivity"]));
				$insert_params["with_gsis"] = $params["with_gsis"];
				$insert_params["with_sss"] = $params["with_sss"];
				$insert_params["with_pera"] = $params["with_pera"];
				$insert_params["with_philhealth_contribution"] = $params["with_philhealth_contribution"];
				$insert_params["with_union_dues"] = $params["with_union_dues"];
				$insert_params["with_pagibig_contribution"] = $params["with_pagibig_contribution"];
				$insert_params["pagibig_contribution"] = $params["pagibig_contribution"];
				$insert_params["position_designation"] = isset($params["position_designation"]) ? $params["position_designation"] : "";
				$insert_params["division_designation"] = isset($params["division_designation"]) ? $params["division_designation"] : "";

				$this->db->where('id', $employee["id"])->update("tblemployees",$insert_params);

				if($this->db->trans_status() === TRUE){
					$code = "0";
					$this->db->trans_commit();
					$message = $employee["fname"]." ".$employee["fname"] . " payroll information successfully updated.";
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message);
					return true;
				}
				else {
					$this->db->trans_rollback();
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message);
				}
			}

			return false;
		}

		
	}
?>