<?php
	class CompetencyTestExamResultCollection extends Helper {
		var $select_column = null;
		public function __construct() {
			$this->load->model('HelperDao');
			ModelResponse::busy();
			$columns = $this->getColumns();
			foreach ($columns as $key => $value) {
				$this->select_column[] = $this->table.'.'.$value['COLUMN_NAME'];
			}
		}

		var $table = "tblcompetencyaccess";
      	var $order_column = array('reference');
      	public function getColumns(){
      		$sql = " SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='".$this->table."' AND TABLE_SCHEMA='".$this->db->database."' ";
			$query = $this->db->query($sql);
			$rows = $query->result_array();
			return $rows;
      	}
		function make_query($status){
		    $this->db->select(
				$this->table.'.*,
		    	COUNT(b.id) as total_examinees'
			);
		    $this->db->from($this->table);
			$this->db->join("tblcompetencyaccessemail b",$this->table.".id = b.access_id","left")->group_by('access_id');
			if($status != "ALL") $this->db->where($this->table.".status",$status);
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
		
		function make_datatables($status){
		    $this->make_query($status);
			if($_GET["length"] != -1) $this->db->limit($_GET['length'], $_GET['start']);
			
		    $query = $this->db->get();
		    return $query->result();
		}
		function get_filtered_data($status){
			$this->make_query($status);
			$query = $this->db->get();
		    return $query->num_rows();	
		}
		function get_all_data($status){
		    $this->db->select($this->table."*");
			$this->db->from($this->table);
			if($status != "ALL") $this->db->where($this->table.".status",$status);
		    return $this->db->count_all_results();
		}


		public function hasRows(){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT * FROM tblcompetencyaccess";
			$query = $this->db->query($sql);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched loans.";
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

		public function viewBatch($batch_id){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT *, tblcompetencyaccessemail.id as accessemail_id FROM tblcompetencyaccessemail 
			LEFT JOIN tblcompetencyexamresult 
			ON tblcompetencyaccessemail.id = tblcompetencyexamresult.accessemail_id 
			WHERE tblcompetencyaccessemail.access_id = ?";
			$query = $this->db->query($sql,array($batch_id));
			$userlevel_rows = $query->result_array();

			// print_r($userlevel_rows);
			
			$sql2 = " SELECT * FROM tblcompetencyaccess WHERE id = $batch_id";
			$query2 = $this->db->query($sql2);
			$result = $query2->result();
			$type_id = $result[0]->type_id;


			//Get Expected Total Score
			$points_sql = " SELECT SUM(points) as total_points FROM tblcompetencyquestions 
			WHERE competency_id = $type_id";
			$points_query = $this->db->query($points_sql);
			$total_points = $points_query->result_array();


			//  print_r($total_points[0]['total_points']);
			// $this->db->select('reference')->from('tblcompetencyaccess')->where('id',$batch_id);
			// $query2 = $this->db->get();

			$data['details'] = $userlevel_rows;
			$data['total_points'] = $total_points[0]['total_points'];
			$data['reference'] = $result[0]->reference;
			$data['date'] = $result[0]->date;

			


			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched sub loans.";
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

		public function hasQuestioner($batch_id){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			
			
			$sql2 = " SELECT * FROM tblcompetencyaccess WHERE id = $batch_id";
			$query2 = $this->db->query($sql2);
			$result = $query2->result();

			// print_r($result);
			$type_id = $result[0]->type_id;

			// print_r($type_id);

			$sql = " SELECT * FROM tblcompetencyquestions 
			LEFT JOIN tblcompetencychoices 
			ON tblcompetencyquestions.id = tblcompetencychoices.competency_question_id 
			WHERE tblcompetencyquestions.competency_id = $type_id";
			$query = $this->db->query($sql);
			$userlevel_rows = $query->result_array();
			

			// $this->db->select('reference')->from('tblcompetencyaccess')->where('id',$batch_id);
			// $query2 = $this->db->get();

			// print_r($userlevel_rows);
			$data['reference'] = $result[0]->reference;
			$data['date'] = $result[0]->date;
			$data['details'] = $userlevel_rows;



			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched sub loans.";
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
		public function updateScoreForm($batch_id){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			

			$sql2 = " SELECT * FROM tblcompetencyaccessemail WHERE id = $batch_id";
			$query2 = $this->db->query($sql2);
			$result = $query2->result();
			$access_id = $result[0]->access_id;

			
			$sql2 = " SELECT * FROM tblcompetencyaccess WHERE id = $access_id";
			$query2 = $this->db->query($sql2);
			$result = $query2->result();

			// print_r($result);
			$type_id = $result[0]->type_id;

			// print_r($type_id);

			$sql = " SELECT * FROM tblcompetencyquestions 
			LEFT JOIN tblcompetencychoices 
			ON tblcompetencyquestions.id = tblcompetencychoices.competency_question_id 
			WHERE tblcompetencyquestions.competency_id = $type_id 
			AND tblcompetencyquestions.exam_type != 'essay' ";
			$query = $this->db->query($sql);
			$userlevel_rows = $query->result_array();


			// $essay_sql = " SELECT * FROM tblcompetencyquestions 
			// LEFT JOIN tblcompetencychoices 
			// ON tblcompetencyquestions.id = tblcompetencychoices.competency_question_id 
			// WHERE tblcompetencyquestions.competency_id = $type_id 
			// AND tblcompetencyquestions.exam_type = 'essay' ";
			// $essay_query = $this->db->query($essay_sql);
			// $essay_question = $essay_query->result_array();

			
			$essay_sql = " SELECT *, tblcompetencyanswers.id as examinee_answer_id, tblcompetencyanswers.answer as examinee_answer, tblcompetencyquestions.answer as correct_answer FROM tblcompetencyanswers 
			LEFT JOIN tblcompetencyquestions 
			ON tblcompetencyanswers.question_id = tblcompetencyquestions.id 
			WHERE tblcompetencyquestions.competency_id = $type_id 
			AND tblcompetencyquestions.exam_type = 'essay' ";
			$essay_query = $this->db->query($essay_sql);
			$essay_question = $essay_query->result_array();
			
			

			// $this->db->select('reference')->from('tblcompetencyaccess')->where('id',$batch_id);
			// $query2 = $this->db->get();

			// print_r($essay_question);
			$data['reference'] = $result[0]->reference;
			$data['date'] = $result[0]->date;
			$data['details'] = $userlevel_rows;
			$data['essay'] = $essay_question;
			$data['access_id'] = $access_id;



			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched sub loans.";
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
		public function updateScore(){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "No data available.";
			$total_essay = $this->input->post('total_essay');
			$accessemail_id = $this->input->post('access_id');


			for ($x = 1; $x <= $total_essay; $x++) {
				$score_number = 'examinee_score'.$x;
				$id_number = 'examinee_id'.$x;
				// $this->input->post('total_essay');

				$data[$x]['score'] = $this->input->post($score_number);
				$data[$x]['id'] = $this->input->post($id_number);
			}
			$x = 1;
			$total_essay_score = 0;
			foreach($data as $k => $v){
				// print_r($v);
				$sql = "UPDATE tblcompetencyanswers SET
				score = $v[score]
				WHERE id = $v[id]";
				$this->db->query($sql);
				$x++;
				$total_essay_score = $total_essay_score + $v['score'];
			}

			// get Exam Result

			

			$sql2 = " SELECT * FROM tblcompetencyexamresult WHERE accessemail_id = $accessemail_id";
			$query2 = $this->db->query($sql2);
			$result = $query2->result();

			if($result){
				$sql_update = "UPDATE tblcompetencyexamresult SET
				essay_res = $total_essay_score
				WHERE accessemail_id = $accessemail_id";
				$this->db->query($sql_update);
			}



			if($total_essay){
				$code = "0";
				$message = "Successfully updated score.";
				$this->ModelResponse($code, $message);	
				//$helperDao->auditTrails(Helper::get('userid'),$message);	
				return true;		
			}	
			else {
				$this->ModelResponse($code, $message);
				//$helperDao->auditTrails(Helper::get('userid'),$message);
			}
			return false;

			
		}
		public function hasRowsActive(){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT * FROM tblcompetencyaccess where status = '1'";
			$query = $this->db->query($sql);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched loans.";
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
		public function hasRowsSubActive(){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$loan_id = isset($_GET['LoanId']) && $_GET['LoanId'] != ""? $_GET['LoanId']: "";
			$message = "No data available.";
			$sql = " SELECT * FROM tblcompetencyaccesssub where status = '1' AND loan_id = ?";
			$query = $this->db->query($sql,array($loan_id));
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched loans.";
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
		public function activeRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Loans level failed to activate.";
			$data['Id'] 	= isset($params['id'])?$params['id']:'';
			$data['Status'] 		= "1";
			$userlevel_sql = "	UPDATE tblcompetencyaccess SET
									status = ? 
								WHERE id = ? ";
			$userlevel_params = array($data['Status'],$data['Id']);
			$this->db->query($userlevel_sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully activated loan.";
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
			$message = "Loans level failed to deactivate.";
			$data['Id'] 	= isset($params['id'])?$params['id']:'';
			$data['Status'] 		= "0";
			$userlevel_sql = "	UPDATE tblcompetencyaccess SET
									status = ? 
								WHERE id = ? ";
			$userlevel_params = array($data['Status'],$data['Id']);
			$this->db->query($userlevel_sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully deactivated loan.";
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
		public function activeSubRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Sub Loans level failed to activate.";
			$data['Id'] 	= isset($params['id'])?$params['id']:'';
			$data['Status'] 		= "1";
			$userlevel_sql = "	UPDATE tblcompetencyaccesssub SET
									status = ? 
								WHERE id = ? ";
			$userlevel_params = array($data['Status'],$data['Id']);
			$this->db->query($userlevel_sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully activated sub loan.";
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
		public function inactiveSubRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Sub Loans level failed to deactivate.";
			$data['Id'] 	= isset($params['id'])?$params['id']:'';
			$data['Status'] 		= "0";
			$userlevel_sql = "	UPDATE tblcompetencyaccesssub SET
									status = ? 
								WHERE id = ? ";
			$userlevel_params = array($data['Status'],$data['Id']);
			$this->db->query($userlevel_sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully deactivated sub loan.";
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
			$helperDao = new HelperDao();
			$code = "1";
			$params['modified_by'] = Helper::get('userid');
			$message = "Loans failed to insert.";
			$this->db->insert('tblcompetencyaccess', $params);
			if($this->db->affected_rows() > 0)
			{
				$code = "0";
				$message = "Successfully inserted loan.";
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
		public function addSubRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$params['modified_by'] = Helper::get('userid');
			$message = "Loans failed to insert.";
			$this->db->insert('tblcompetencyaccesssub', $params);
			if($this->db->affected_rows() > 0)
			{
				$code = "0";
				$message = "Successfully inserted loan.";
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
		public function updateRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Loans failed to update.";
			$this->db->where('id', $params['id']);
			if ($this->db->update('tblcompetencyaccess',$params) === FALSE){
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}	
			else {
				$code = "0";
				$message = "Successfully updated loan.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;	
			}
			return false;
		}

	}
?>