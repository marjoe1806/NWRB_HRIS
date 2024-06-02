<?php
	class CompetencyExaminationCollection extends Helper {

		public function __construct() {
			$this->load->model('HelperDao');
			ModelResponse::busy();
		}
		public function getExamination($param) {
			 $competency_id = $param['Data']['type_id'];

			 $this->db->select(
		    	'tblcompetencyquestions.*,
				tblcompetencychoices.*,
				tblcompetencyquestions.id AS question_id'
		    );
		    $this->db->from('tblcompetencyquestions');
		    $this->db->join("tblcompetencychoices", "tblcompetencychoices.competency_question_id = tblcompetencyquestions.id","left");
			$this->db->where('tblcompetencyquestions.competency_id', $competency_id);
			$query = $this->db->get();
			return $query->result();
		}

		public function validateAccess($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Invalid access link.";
			$this->db->select(
		    	'tblcompetencyaccess.*,
				tblcompetencyaccessemail.*,
				tblcompetencyaccessemail.id AS accessemail_id,
				tblcompetencyaccessemail.status AS exam_status
				'
		    );
			$this->db->from('tblcompetencyaccessemail');
		    $this->db->join("tblcompetencyaccess","tblcompetencyaccessemail.access_id = tblcompetencyaccess.id","left");
			$this->db->where('tblcompetencyaccessemail.access_id', $params['access_id']);
			$this->db->where('tblcompetencyaccessemail.emailaddress', $params['emailaddress']);
			$query = $this->db->get();

			if ($query === FALSE){
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}else {
				$code = "0";
				$message = "Successfully fetch type name.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return $query->result();
			}
			return false;
		}
		public function checkAnswer($question_id, $answer){
			$answer = strtolower($answer);

			$helperDao = new HelperDao();
			$code = "1";
			$message = "Wrong answer.";
			
			$this->db->from('tblcompetencyquestions');
			$this->db->where('id', $question_id);
			$query = $this->db->get();

			if ($query === FALSE){
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}	
			else {
				$code = "0";
				$message = "Successfully fetch type name.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return $query->result();
			}
			return false;
		}
		
		public function saveAnswers($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Answer failed to submit.";
			$this->db->insert_batch('tblcompetencyanswers', $params);
			if($this->db->affected_rows() > 0)
			{	
				$code = "0";
				$message = "Successfully submit answer.";
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

		public function saveResult($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Answer failed to submit.";
			$this->db->insert('tblcompetencyexamresult', $params);
			if($this->db->affected_rows() > 0)
			{	
				$code = "0";
				$message = "Successfully submit answer.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);		
			}	
			else {
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		public function updateAccessEmailStatus($params){
			$helperDao = new HelperDao();
			$this->db->set('status', 1);
			$this->db->where('id', $params);
			$this->db->update('tblcompetencyaccess'); 

			if($this->db->affected_rows() > 0)
			{				
				return true;
			}	
			return false;
		}

    }
?>