<?php
	class SalaryGradesCollection extends Helper {
		public function __construct() {
			$this->load->model('HelperDao');
			ModelResponse::busy();
		}
		public function hasRows($pay_basis,$effectivity){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$steps = $this->getSalaryGradeSteps($pay_basis,$effectivity);
			$effectivity = $this->getMaxEffectivity($pay_basis);
			$sql = "
				select b.grade,a.step,a.salary
				from tblfieldsalarygradesteps a
				LEFT JOIN tblfieldsalarygrades b ON b.grade = a.grade_id
				WHERE b.is_active = 1 AND a.is_active = 1 AND b.effectivity = ".$effectivity." AND a.effectivity = ".$effectivity."
			";
			$query = $this->db->query($sql);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			// var_dump($data['details']);die();
			if(sizeof($userlevel_rows) > 0){ 
				$code = "0";
				$message = "Successfully fetched salary grades.";

				// die('hit');
				$this->ModelResponse($code, $message, $data);	
				// $helperDao->auditTrails(Helper::get('userid'),$message);	
				return true;		
			}	
			else {
				// $this->getMaxEffectivity($pay_basis);
				$this->ModelResponse($code, $message);
				// $helperDao->auditTrails(Helper::get('userid'),$message);
			}
			return false;
		}
		function getMaxEffectivity($pay_basis){
			$sql = "SELECT MAX(effectivity) AS effectivity FROM tblfieldsalarygrades WHERE pay_basis = ? LIMIT 1 ";
			$query = $this->db->query($sql,array($pay_basis));
			$data = $query->result_array();
			$effectivity = "";
			if(is_array($data) && sizeof($data) > 0){
				$effectivity = $data[0]['effectivity'];
			}
			return $effectivity;
		}
		function getSalaryGradeSteps($pay_basis,$effectivity){
			$sql = " SELECT DISTINCT a.step,a.salary FROM tblfieldsalarygradesteps a LEFT JOIN tblfieldsalarygrades b ON b.id = a.grade_id WHERE b.pay_basis = ?";
			$query = $this->db->query($sql,array($pay_basis));
		    return $query->result_array();  
		}
		public function hasRowsActive($pay_basis){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT * FROM tblfieldsalarygrades 
					 WHERE effectivity = (SELECT MAX(effectivity) FROM tblfieldsalarygrades) AND is_active = 1 ORDER BY grade DESC";
			$query = $this->db->query($sql);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched salary grades.";
				$this->ModelResponse($code, $message, $data);	
				return true;		
			}	
			else {
				$this->ModelResponse($code, $message);
			}
			return false;
		}
		public function hasRowsStepsByGrade($id,$pay_basis){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT * FROM tblfieldsalarygradesteps where grade_id = ? AND is_active = 1";
			$query = $this->db->query($sql,array($id));
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched Salary Grades.";
				$this->ModelResponse($code, $message, $data);	
				return true;		
			}	
			else {
				$this->ModelResponse($code, $message);
			}
			return false;
		}
		public function activeRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Salary Grades failed to activate.";
			$data['id'] 	= isset($params['id'])?$params['id']:'';
			$data['Status'] 		= "1";
			$userlevel_sql = "	UPDATE tblwithholdingtaxtable SET
									is_active = ? 
								WHERE id = ? ";
			$userlevel_params = array($data['Status'],$data['id']);
			$this->db->query($userlevel_sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully activated Salary Grades.";
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
		public function inactiveRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Salary Grades failed to deactivate.";
			$data['id'] 	= isset($params['id'])?$params['id']:'';
			$data['Status'] 		= "0";
			$userlevel_sql = "	UPDATE tblwithholdingtaxtable SET
									is_active = ? 
								WHERE id = ? ";
			$userlevel_params = array($data['Status'],$data['id']);
			$this->db->query($userlevel_sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully deactivated Salary Grades.";
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
		public function addRows($params){
			// var_dump(json_encode($params));die();
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Salary Grades failed to insert.";
			$effectivity = $params['effectivity'];
			$pay_basis = ($params['pay_basis'] != null) ? $params['pay_basis'] : 'Permanent';

			$this->db->trans_begin();
			//Delete same pay_basis and effectivity
			// var_dump(json_encode($params));die();
			$sql = " UPDATE tblfieldsalarygrades SET is_active = 0 WHERE effectivity = ? ";
			$this->db->query($sql,array($effectivity));
			$sql = " UPDATE tblfieldsalarygradesteps SET is_active = 0 WHERE effectivity = ? ";
			$this->db->query($sql,array($effectivity));
			foreach ($params['salary'] as $k => $v) {
				$params2 = array();
				$params2['pay_basis'] = $pay_basis;
				$params2['effectivity'] = $effectivity;
				$params2['grade'] = $v['grade'];
				$params2['modified_by'] = Helper::get('userid');
				$this->db->insert('tblfieldsalarygrades',$params2);
				$last_id = $this->db->insert_id();
				foreach ($v['step'] as $k1 => $v1) {
					$params3 = array();
					$params3['grade_id'] = $params2['grade'];
					$params3['step'] = $k1 + 1;
					$params3['salary'] = $v1;//str_replace(',', '', $v1);
					$params3['pay_basis'] = $pay_basis;
					$params3['effectivity'] = $effectivity;
					$this->db->insert('tblfieldsalarygradesteps',$params3);
				}
			}
			if($this->db->trans_status() === TRUE){
				$code = "0";
				$this->db->trans_commit();
				$message = "Successfully inserted Salary Grades.";
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
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Salary Grades failed to update.";
			$this->db->where('id', $params['id']);
			if ($this->db->update('tblwithholdingtaxtable',$params) === FALSE){
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}	
			else {
				$code = "0";
				$message = "Successfully updated Salary Grades.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;	
			}
			return false;
		}

	}
?>