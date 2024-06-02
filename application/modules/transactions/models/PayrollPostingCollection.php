<?php
	class PayrollPostingCollection extends Helper {
		var $select_column = null;
		public function __construct() {
			$this->load->model('HelperDao');
			ModelResponse::busy();
			$columns = $this->getColumns();
			foreach ($columns as $key => $value) {
				$this->select_column[] = $this->table.'.'.$value['COLUMN_NAME'];
			}
		}

		var $table = "tblfieldperiodsettings";
      	var $order_column = array('','payroll_period','start_date','period_id','is_active');
      	public function getColumns(){
      		$sql = " SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='".$this->table."' AND TABLE_SCHEMA='".$this->db->database."' ";
			$query = $this->db->query($sql);
			$rows = $query->result_array();
			return $rows;
      	}
		function make_query($month,$isPosted){
		    $this->db->select(
		    	$this->table.'.*'
		    );
		    $this->db->from($this->table);
			if($month != "ALL") $this->db->where("MONTH(".$this->table.".start_date)",$month);
			if($isPosted != "ALL") $this->db->where($this->table.".is_posted",$isPosted);
    	    if(isset($_GET["search"]["value"])){
		    	$this->db->group_start();
		     	foreach ($this->select_column as $key => $value) {
		     		$this->db->or_like($value, $_GET["search"]["value"]);
		     	}
		        $this->db->group_end();
		    }
		    if(isset($_GET["order"])){
				$this->db->order_by($this->order_column[$_GET['order']['0']['column']], $_GET['order']['0']['dir']);
		    }else{
				$this->db->order_by($this->table.'.id', 'ASC');
		    }
		}
		
		function make_datatables($month,$isPosted){
		    $this->make_query($month,$isPosted);
			if($_GET["length"] != -1) $this->db->limit($_GET['length'], $_GET['start']);
			
		    $query = $this->db->get();
		    return $query->result();
		}
		function get_filtered_data($month,$isPosted){
			$this->make_query($month,$isPosted);
			$query = $this->db->get();
		    return $query->num_rows();
		}
		function get_all_data($month,$isPosted){
		    $this->db->select($this->table."*");
			$this->db->from($this->table);
			if($month != "ALL") $this->db->where("MONTH(".$this->table.".start_date)",$month);
			if($isPosted != "ALL") $this->db->where($this->table.".is_posted",$isPosted);
		    return $this->db->count_all_results();
		}


		public function hasRowsMonthly(){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT * FROM tblfieldperiodsettings WHERE is_semi_monthly = 0 AND is_active = 1 ORDER BY payroll_period DESC";
			$query = $this->db->query($sql);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched period settings.";
				$this->ModelResponse($code, $message, $data);	
				$helperDao->auditTrails(Helper::get('userid'),$message);	
				return true;		
			}	
			else {
				$this->ModelResponse($code, $message);
				$helperDao->auditTrails(Helper::get('userid'),$message);
			}
			return false;
		}

		// public function hasRowsById(){
		// 	$id = isset($_GET["Id"]) ? $_GET["Id"] : "";
		// 	$helperDao = new HelperDao();
		// 	$data = array();
		// 	$code = "1";
		// 	$message = "No data available.";
		// 	$sql = " SELECT * FROM tblfieldperiodsettings where id = " . $id;
		// 	$query = $this->db->query($sql);
		// 	$userlevel_rows = $query->result_array();
		// 	$data['details'] = $userlevel_rows;
		// 	if(sizeof($userlevel_rows) > 0){
		// 		$code = "0";
		// 		$message = "Successfully fetched period setting.";
		// 		$this->ModelResponse($code, $message, $data);	
		// 		//$helperDao->auditTrails(Helper::get('userid'),$message);	
		// 		return true;		
		// 	}	
		// 	else {
		// 		$this->ModelResponse($code, $message);
		// 		//$helperDao->auditTrails(Helper::get('userid'),$message);
		// 	}
		// 	return false;
		// }

		// public function hasRowsActive($pay_basis = ""){
		// 	if($pay_basis != ""){
		// 		if($pay_basis == "Permanent" || $pay_basis == "Casual" || $pay_basis == "Consultant" || $pay_basis == "Congress"){
		// 			$sql2 = " AND period_id = 'Whole Period' AND period_id != 'Monthly Period' ";
		// 		} 
		// 		else{
		// 			$sql2 = " AND period_id != 'Whole Period' ";
		// 		}	
		// 	}
		// 	else{
		// 		$sql2 = "";
		// 	}
		// 	$helperDao = new HelperDao();
		// 	$data = array();
		// 	$code = "1";
		// 	$message = "No data available.";
		// 	$sql = " SELECT * FROM tblfieldperiodsettings where is_semi_monthly = 0 AND  is_active = '1' ".$sql2;
		// 	$sql.= " ORDER BY payroll_period DESC ";
		// 	$query = $this->db->query($sql);
		// 	$userlevel_rows = $query->result_array();
		// 	$data['details'] = $userlevel_rows;
		// 	if(sizeof($userlevel_rows) > 0){
		// 		foreach ($data['details'] as $key => $value) {
		// 			$data['details'][$key]['start_date'] = date("M d, Y",strtotime($value['start_date']));
		// 			$data['details'][$key]['end_date'] = date("M d, Y",strtotime($value['end_date']));
		// 		}
		// 		$code = "0";
		// 		$message = "Successfully fetched period setting.";
		// 		$this->ModelResponse($code, $message, $data);	
		// 		//$helperDao->auditTrails(Helper::get('userid'),$message);	
		// 		return true;		
		// 	}	
		// 	else {
		// 		$this->ModelResponse($code, $message);
		// 		//$helperDao->auditTrails(Helper::get('userid'),$message);
		// 	}
		// 	return false;
		// }

		// public function activePeriods($pay_basis = ""){
			
		// 	$helperDao = new HelperDao();
		// 	$data = array();
		// 	$code = "1";
		// 	$message = "No data available.";

		// 	$query = $this->db->select("b.period_id, b.id, b.payroll_period, CONCAT(b.start_date,' to ',b.end_date) payroll_date,b.start_date, b.end_date")
		// 	->from("tbltransactionsprocesspayroll a")
		// 	->join("tblfieldperiodsettings b", "b.id = a.payroll_period_id","left")
		// 	->group_by("b.id")
		// 	->order_by("a.date_created","DESC")
		// 	->get();

		// 	// $sql = " SELECT * FROM tblfieldperiodsettings where is_semi_monthly = 0 AND  is_active = '1' ".$sql2;
		// 	// $sql.= " ORDER BY payroll_period DESC ";
		// 	// $query = $this->db->query($sql);
		// 	$userlevel_rows = $query->result_array();
		// 	$data['details'] = $userlevel_rows;
		// 	if(sizeof($userlevel_rows) > 0){
		// 		foreach ($data['details'] as $key => $value) {
		// 			$data['details'][$key]['start_date'] = date("M d, Y",strtotime($value['start_date']));
		// 			$data['details'][$key]['end_date'] = date("M d, Y",strtotime($value['end_date']));
		// 		}
		// 		$code = "0";
		// 		$message = "Successfully fetched period setting.";
		// 		$this->ModelResponse($code, $message, $data);	
		// 		//$helperDao->auditTrails(Helper::get('userid'),$message);	
		// 		return true;		
		// 	}	
		// 	else {
		// 		$this->ModelResponse($code, $message);
		// 		//$helperDao->auditTrails(Helper::get('userid'),$message);
		// 	}
		// 	return false;
		// }

		public function hasRowsSemiMonthly(){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT * FROM tblfieldperiodsettings WHERE is_semi_monthly = 1 ORDER BY payroll_period ASC";
			$query = $this->db->query($sql);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched period settings.";
				$this->ModelResponse($code, $message, $data);	
				$helperDao->auditTrails(Helper::get('userid'),$message);	
				return true;		
			}	
			else {
				$this->ModelResponse($code, $message);
				$helperDao->auditTrails(Helper::get('userid'),$message);
			}
			return false;
		}

		public function lockRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Position level failed to activate.";
			$data['Id'] 	= isset($params['id'])?$params['id']:'';
			$data['Status'] 		= "1";
			$userlevel_sql = "UPDATE tblfieldperiodsettings SET
									is_posted = ? 
								WHERE id = ? ";
			$userlevel_params = array($data['Status'],$data['Id']);
			$this->db->query($userlevel_sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully locked period setting.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;		
			}	
			else {
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		// public function inactiveRows($params){
		// 	$helperDao = new HelperDao();
		// 	$code = "1";
		// 	$message = "Position level failed to deactivate.";
		// 	$data['Id'] 	= isset($params['id'])?$params['id']:'';
		// 	$data['Status'] 		= "0";
		// 	$userlevel_sql = "	UPDATE tblfieldperiodsettings SET
		// 							is_active = ? 
		// 						WHERE id = ? ";
		// 	$userlevel_params = array($data['Status'],$data['Id']);
		// 	$this->db->query($userlevel_sql,$userlevel_params);
		// 	if($this->db->affected_rows() > 0){
		// 		$code = "0";
		// 		$message = "Successfully deactivated period setting.";
		// 		$helperDao->auditTrails(Helper::get('userid'),$message);
		// 		$this->ModelResponse($code, $message);
		// 		return true;		
		// 	}	
		// 	else {
		// 		$helperDao->auditTrails(Helper::get('userid'),$message);
		// 		$this->ModelResponse($code, $message);
		// 	}
		// 	return false;
		// }

		// public function addRows($params){
		// 	$helperDao = new HelperDao();
		// 	$code = "1";
		// 	$params['modified_by'] = Helper::get('userid');
		// 	$message = "Position failed to insert.";
		// 	$params['is_posted'] = isset($params['is_posted'])? "1" : "0" ;
		// 	$params['is_semi_monthly'] = isset($params['is_semi_monthly'])? "1" : "0" ;
		// 	$this->db->insert('tblfieldperiodsettings', $params);
		// 	if($this->db->affected_rows() > 0)
		// 	{
		// 		$code = "0";
		// 		$message = "Successfully inserted period setting.";
		// 		$helperDao->auditTrails(Helper::get('userid'),$message);
		// 		$this->ModelResponse($code, $message);
		// 		return true;		
		// 	}	
		// 	else {
		// 		$helperDao->auditTrails(Helper::get('userid'),$message);
		// 		$this->ModelResponse($code, $message);
		// 	}
		// 	return false;
		// }

		// public function updateRows($params){
		// 	$helperDao = new HelperDao();
		// 	$code = "1";
		// 	$message = "Position failed to update.";
		// 	$params['is_posted'] = isset($params['is_posted'])? "1" : "0" ;
		// 	$params['is_semi_monthly'] = isset($params['is_semi_monthly'])? "1" : "0" ;
		// 	$this->db->where('id', $params['id']);
		// 	if ($this->db->update('tblfieldperiodsettings',$params) === FALSE){
		// 		$helperDao->auditTrails(Helper::get('userid'),$message);
		// 		$this->ModelResponse($code, $message);
		// 	}	
		// 	else {
		// 		$code = "0";
		// 		$message = "Successfully updated period setting.";
		// 		$helperDao->auditTrails(Helper::get('userid'),$message);
		// 		$this->ModelResponse($code, $message);
		// 		return true;	
		// 	}
		// 	return false;
		// }

		// public function activeRowsWeekly($params){
		// 	$helperDao = new HelperDao();
		// 	$code = "1";
		// 	$message = "Position level failed to activate.";
		// 	$data['Id'] 	= isset($params['id'])?$params['id']:'';
		// 	$data['Status'] 		= "1";
		// 	$userlevel_sql = "	UPDATE tblfieldperiodsettingsweekly SET
		// 							is_active = ? 
		// 						WHERE id = ? ";
		// 	$userlevel_params = array($data['Status'],$data['Id']);
		// 	$this->db->query($userlevel_sql,$userlevel_params);
		// 	if($this->db->affected_rows() > 0){
		// 		$code = "0";
		// 		$message = "Successfully activated period setting.";
		// 		$helperDao->auditTrails(Helper::get('userid'),$message);
		// 		$this->ModelResponse($code, $message);
		// 		return true;		
		// 	}	
		// 	else {
		// 		$helperDao->auditTrails(Helper::get('userid'),$message);
		// 		$this->ModelResponse($code, $message);
		// 	}
		// 	return false;
		// }

		// public function inactiveRowsWeekly($params){
		// 	$helperDao = new HelperDao();
		// 	$code = "1";
		// 	$message = "Position level failed to deactivate.";
		// 	$data['Id'] 	= isset($params['id'])?$params['id']:'';
		// 	$data['Status'] 		= "0";
		// 	$userlevel_sql = "	UPDATE tblfieldperiodsettingsweekly SET
		// 							is_active = ? 
		// 						WHERE id = ? ";
		// 	$userlevel_params = array($data['Status'],$data['Id']);
		// 	$this->db->query($userlevel_sql,$userlevel_params);
		// 	if($this->db->affected_rows() > 0){
		// 		$code = "0";
		// 		$message = "Successfully deactivated period setting.";
		// 		$helperDao->auditTrails(Helper::get('userid'),$message);
		// 		$this->ModelResponse($code, $message);
		// 		return true;		
		// 	}	
		// 	else {
		// 		$helperDao->auditTrails(Helper::get('userid'),$message);
		// 		$this->ModelResponse($code, $message);
		// 	}
		// 	return false;
		// }

		// public function addRowsWeekly($params){
		// 	$helperDao = new HelperDao();
		// 	$code = "1";
		// 	$params['modified_by'] = Helper::get('userid');
		// 	$message = "Position failed to insert.";
		// 	// $params['is_posted'] = isset($params['is_posted'])? "1" : "0" ;
		// 	// $params['is_semi_monthly'] = isset($params['is_semi_monthly'])? "1" : "0" ;
		// 	$this->db->insert('tblfieldperiodsettingsweekly', $params);
		// 	if($this->db->affected_rows() > 0)
		// 	{
		// 		$code = "0";
		// 		$message = "Successfully inserted period setting.";
		// 		$helperDao->auditTrails(Helper::get('userid'),$message);
		// 		$this->ModelResponse($code, $message);
		// 		return true;		
		// 	}	
		// 	else {
		// 		$helperDao->auditTrails(Helper::get('userid'),$message);
		// 		$this->ModelResponse($code, $message);
		// 	}
		// 	return false;
		// }

		// public function updateRowsWeekly($params){
		// 	$helperDao = new HelperDao();
		// 	$code = "1";
		// 	$message = "Position failed to update.";
		// 	// $params['is_posted'] = isset($params['is_posted'])? "1" : "0" ;
		// 	// $params['is_semi_monthly'] = isset($params['is_semi_monthly'])? "1" : "0" ;
		// 	$this->db->where('id', $params['id']);
		// 	if ($this->db->update('tblfieldperiodsettingsweekly',$params) === FALSE){
		// 		$helperDao->auditTrails(Helper::get('userid'),$message);
		// 		$this->ModelResponse($code, $message);
		// 	}	
		// 	else {
		// 		$code = "0";
		// 		$message = "Successfully updated period setting.";
		// 		$helperDao->auditTrails(Helper::get('userid'),$message);
		// 		$this->ModelResponse($code, $message);
		// 		return true;	
		// 	}
		// 	return false;
		// }

	}
?>