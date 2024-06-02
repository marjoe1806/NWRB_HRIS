<?php
	class ForeignRecordCollection extends Helper {
		public function __construct() {
			$this->load->model('HelperDao');
			ModelResponse::busy();
		}
		public function hasRows(){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT * FROM tbltrainingmonitoring WHERE tm_country <> 'Philippines'";
			$query = $this->db->query($sql);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched appearance.";
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
		public function addRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Foreign Record failed to insert.";
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
				$message = "Successfully inserted Foreign Record.";
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
			$message = "Foreign Record failed to insert.";
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
				$message = "Successfully updated Foreign Record.";
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
				$helperDao->auditTrails(Helper::get('userid'),$message);	
				return true;		
			}	
			else {
				$this->ModelResponse($code, $message);
				$helperDao->auditTrails(Helper::get('userid'),$message);
			}
			return false;
		}
	}
?>