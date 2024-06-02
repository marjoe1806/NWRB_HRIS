<?php
	class AllRecordCollection extends Helper {

		var $select_column = null;
		var $table = "tbltrainingmonitoring";   
		var $order_column = array('tbltrainingmonitoring.tm_start_date','tbltrainingmonitoring.tm_end_date','tbltrainingmonitoring.tm_seminar_training','tbltrainingmonitoring.sponsored_by','tbltrainingmonitoring.tm_place','tbltrainingmonitoring.tm_country');
		public function __construct() {
			$this->load->model('HelperDao');
			ModelResponse::busy();
			$columns = $this->getColumns();
			foreach ($columns as $key => $value) {
				$this->select_column[] = $this->table.'.'.$value['COLUMN_NAME'];
			}
		}
		  
      	public function getColumns(){
      		$sql = " SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='".$this->table."' AND TABLE_SCHEMA='".$this->db->database."' ";
			$query = $this->db->query($sql);
			$rows = $query->result_array();
			return $rows;
		}
		  
		function make_query() {
		    $this->db->select('*')->from($this->table);  
		    if(isset($_POST["search"]["value"])) {  
		    	$this->db->group_start();
		     	foreach ($this->select_column as $key => $value) {
		     		$this->db->or_like($value, $_POST["search"]["value"]);
		     	}
		        $this->db->group_end(); 
		    }
			if(isset($_GET['Country']) && $_GET['Country'] != NULL) $this->db->where($this->table.'.tm_country',$_GET['Country']);
			if(isset($_GET['Place']) && $_GET['Place'] != NULL) $this->db->where($this->table.'.tm_place',$_GET['Place']);
			if(isset($_POST["order"]))
				$this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
			else
				$this->db->order_by($this->table.".tm_created DESC");
		}
		
		function make_datatables(){  
		    $this->make_query();  
			if($_POST["length"] != -1) $this->db->limit($_POST['length'], $_POST['start']);

			$query = $this->db->get();
		    return $query->result();  
		}

		function get_filtered_data(){  
		     $this->make_query();
		     $query = $this->db->get();  
		     return $query->num_rows();  
		}

		function get_all_data() {  
		    $this->db->select($this->table."*");  
		    $this->db->from($this->table);
		    return $this->db->count_all_results();  
		}
		public function hasRows(){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT * FROM tbltrainingmonitoring";
			$query = $this->db->query($sql);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched training monitoring.";
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
		public function hasRowsByCountry($country){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT * FROM tbltrainingmonitoring WHERE tm_country = ?";
			$params = array('tm_country'=>$country);
			$query = $this->db->query($sql,$params);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched training monitoring.";
				$this->ModelResponse($code, $message, $data);	
				//$helperDao->auditTrails(Helper::get('userid'),$message);	
				return true;		
			}	
			else {
				$this->ModelResponse($code, $message);
				//$helperDao->auditTrails(Helper::get('userid'),$message);
			}
			return false;
		}
		public function addRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Record failed to insert.";
			$params['table1']['is_travel_time_inclusive'] = isset($params['table1']['is_travel_time_inclusive'])?"1":"0";
			$params['table1']['is_with_travel_report'] = isset($params['table1']['is_with_travel_report'])?"1":"0";
			$this->db->trans_begin();
			$this->db->insert('tbltrainingmonitoring', $params['table1']);
			$tm_id = $this->db->insert_id();
			foreach ($params['table2']['tmp_participant'] as $k => $v) {
				$participants = array(
					"tm_id"=> $tm_id,
					"tmp_participant"  => $params['table2']['tmp_participant'][$k],
					"tmp_participant_name"  => $params['table2']['tmp_participant_name'][$k],
				);
				//var_dump($participants);die();
				$this->db->insert('tbltrainingmonitoringparticipants', $participants);
			}
			if($this->db->trans_status() === TRUE){
				$code = "0";
				$this->db->trans_commit();
				$message = "Successfully inserted Record.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;		
			}	
			else {
				$this->db->trans_rollback();
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}
		public function updateRows($params){
			//var_dump($params);die();
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Record failed to insert.";
			$params['table1']['is_travel_time_inclusive'] = isset($params['table1']['is_travel_time_inclusive'])?"1":"0";
			$params['table1']['is_with_travel_report'] = isset($params['table1']['is_with_travel_report'])?"1":"0";
			$this->db->trans_begin();
			//Update Monitoring
			$this->db->where('tm_id', $params['table1']['tm_id']);
			$this->db->update('tbltrainingmonitoring', $params['table1']);
			//Delete participants
			$this->db->where('tm_id', $params['table1']['tm_id']);
			$this->db->delete('tbltrainingmonitoringparticipants');
			//Add Participants
			$tm_id = $params['table1']['tm_id'];
			foreach ($params['table2']['tmp_participant'] as $k => $v) {
				$participants = array(
					"tm_id"=> $tm_id,
					"tmp_participant"  => $params['table2']['tmp_participant'][$k],
					"tmp_participant_name"  => $params['table2']['tmp_participant_name'][$k],
				);
				//var_dump($participants);die();
				$this->db->insert('tbltrainingmonitoringparticipants', $participants);
			}
			if($this->db->trans_status() === TRUE){
				$code = "0";
				$this->db->trans_commit();
				$message = "Successfully updated Record.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;		
			}	
			else {
				$this->db->trans_rollback();
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}
		public function fetchParticipants($id){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT * FROM tbltrainingmonitoringparticipants WHERE tm_id = ?";
			$params = array('id'=>$id);
			$query = $this->db->query($sql,$id);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched participants.";
				$this->ModelResponse($code, $message, $data);	
				//$helperDao->auditTrails(Helper::get('userid'),$message);	
				return true;		
			}	
			else {
				$this->ModelResponse($code, $message);
				//$helperDao->auditTrails(Helper::get('userid'),$message);
			}
			return false;
		}

		public function hasRowsPlace(){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT COUNT(*) total, tm_place as place FROM tbltrainingmonitoring GROUP BY tm_place ASC";
			$query = $this->db->query($sql);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched places.";
				$this->ModelResponse($code, $message, $data);	
				//$helperDao->auditTrails(Helper::get('userid'),$message);	
				return true;		
			}	
			else {
				$this->ModelResponse($code, $message);
				//$helperDao->auditTrails(Helper::get('userid'),$message);
			}
			return false;
		}
	}
?>