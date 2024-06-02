<?php
	class FlagCeremonySchedulesCollection extends Helper {
		public function __construct() {
			$this->load->model('HelperDao');
			ModelResponse::busy();
		}

		public function hasRows(){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT * FROM tbltimekeepingflagceremonyschedules WHERE year = '".date("Y")."' ";
			$query = $this->db->query($sql);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched Flag Ceremony Schedules.";
				$this->ModelResponse($code, $message, $data);	
				// $helperDao->auditTrails(Helper::get('userid'),$message);	
				return true;		
			}	
			else {
				$this->ModelResponse($code, $message);
				// $helperDao->auditTrails(Helper::get('userid'),$message);
			}
			return false;
		}



		// public function addRows($params){
		// 	// var_dump(date("m", strtotime($params['flagdateceremony'])));
		// 	// var_dump(date("Y", strtotime($params['flagdateceremony'])));

		// 	//  die();
		// 	// var_dump($_SESSION); die();

		// 	$helperDao = new HelperDao();
		// 	$isDescriotionExist = $this->db->select("*")->from("tbltimekeepingflagceremonyschedules")
		// 						 ->where("MONTH(flagdateceremony)",date("m", strtotime($params['flagdateceremony'])))
		// 						 ->where("YEAR(flagdateceremony)",date("Y", strtotime($params['flagdateceremony'])))
		// 						 ->where("is_active", "1")
		// 					     ->get()->num_rows();
			

		// 	$code = "1";
		// 	$params['modified_by'] = Helper::get('userid');
		// 	$message = $isDescriotionExist < 1 ? "Flag Ceremony failed to insert." : "Flag Ceremony already exist.";
		// 	$this->db->insert('tbltimekeepingemployeeschedules', array( "month" => date("m", strtotime($params['flagdateceremony'])), "year" => date("Y", strtotime($params['flagdateceremony'])), "flagdateceremony"=>$params['flagdateceremony'], "created" => $_SESSION['id'] ));
		// 	if($isDescriotionExist < 1){
		// 		if($this->db->affected_rows() > 0)
		// 		{
		// 			$code = "0";
		// 			$message = "Successfully inserted Employee Schedule.";
		// 			$helperDao->auditTrails(Helper::get('userid'),$message);
		// 			// $this->ModelResponse($code, $message);
		// 			return true;		
		// 		}	
		// 		else {
		// 			// $helperDao->auditTrails(Helper::get('userid'),$message);
		// 			$this->ModelResponse($code, $message);
		// 		}
		// 	}
		// 	$this->ModelResponse($code, $message);
		// 	return false;















		// 	// $helperDao = new HelperDao();
		// 	// $code = "1";
		// 	// $params['created_by'] = Helper::get('userid');

		// 	// //delete existing schedule
		// 	// $this->db->where('month', $params['month']);
		// 	// $this->db->where('year', $params['year']);
		// 	// $this->db->delete('tbltimekeepingflagceremonyschedules');

		// 	// $message = "Flag Ceremony Schedule failed to insert.";
		// 	// $this->db->insert('tbltimekeepingflagceremonyschedules', $params);
		// 	// if($this->db->affected_rows() > 0)
		// 	// {
		// 	// 	$code = "0";
		// 	// 	$message = "Successfully inserted Flag Ceremony Schedule.";
		// 	// 	$helperDao->auditTrails(Helper::get('userid'),$message);
		// 	// 	$this->ModelResponse($code, $message);
		// 	// 	return true;		
		// 	// }	
		// 	// else {
		// 	// 	$helperDao->auditTrails(Helper::get('userid'),$message);
		// 	// 	$this->ModelResponse($code, $message);
		// 	// }
		// 	// return false;
		// }

		public function addRows($params){
			
			$helperDao = new HelperDao();
			$code = "1";
			$params['created_by'] = Helper::get('userid');

			//delete existing schedule
			$this->db->where('month', $params['month']);
			$this->db->where('year', $params['year']);
			$this->db->delete('tbltimekeepingflagceremonyschedules');

			$message = "Flag Ceremony Schedule failed to insert.";
			$this->db->insert('tbltimekeepingflagceremonyschedules', $params);
			if($this->db->affected_rows() > 0)
			{
				$code = "0";
				$message = "Successfully inserted Flag Ceremony Schedule.";
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


		public function activeRowsFlag($params){

			$helperDao = new HelperDao();
			$code = "1";
			$params['created_by'] = Helper::get('userid');
			$params['is_active'] = '1'; 

			//delete existing schedule
			$this->db->where('month', $params['month']);
			$this->db->where('year', $params['year']);
			$this->db->delete('tbltimekeepingflagceremonyschedules');

			$message = "Flag Ceremony Schedule failed to insert.";
			$this->db->insert('tbltimekeepingflagceremonyschedules', $params);
			if($this->db->affected_rows() > 0)
			{
				$code = "0";
				$message = "Successfully inserted Flag Ceremony Schedule.";
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

		public function deactiveRowsFlag($params){

			$helperDao = new HelperDao();
			$code = "1";
			$params['created_by'] = Helper::get('userid');
			$params['is_active'] = '0'; 

			//delete existing schedule
			$this->db->where('month', $params['month']);
			$this->db->where('year', $params['year']);
			$this->db->delete('tbltimekeepingflagceremonyschedules');

			$message = "Flag Ceremony Schedule failed to insert.";
			$this->db->insert('tbltimekeepingflagceremonyschedules', $params);
			if($this->db->affected_rows() > 0)
			{
				$code = "0";
				$message = "Successfully inserted Flag Ceremony Schedule.";
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
		




		function getHolidays(){
			$year = date("Y");
			$params = array($year);
			$sql = "SELECT * FROM tblfieldholidays WHERE YEAR(date) = ? AND (holiday_type = 'Legal' OR holiday_type = 'Special') AND is_active = 1";
			$query = $this->db->query($sql, $params);
			$holidays = $query->result_array();
			return $holidays;
		}
	}
?>