<?php
	class CompetencyTestCollection extends Helper {
		var $select_column = null;
		public function __construct() {
			$this->load->model('HelperDao');
			ModelResponse::busy();
			$columns = $this->getColumns();
			foreach ($columns as $key => $value) {
				$this->select_column[] = $this->table.'.'.$value['COLUMN_NAME'];
			}
		}

		var $table = "tblcompetency";
      	var $order_column = array('','date_created','type','created_by','is_active');
      	public function getColumns(){
      		$sql = " SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='".$this->table."' AND TABLE_SCHEMA='".$this->db->database."' ";
			$query = $this->db->query($sql);
			$rows = $query->result_array();
			return $rows;
      	}
		function make_query($status){
		    $this->db->select(
		    	$this->table.'.*'
				
		    );
			$this->db->select('CONCAT(DECRYPTER(e.last_name,"sunev8clt1234567890",e.id),", ",DECRYPTER(e.first_name,"sunev8clt1234567890",e.id)," ",DECRYPTER(e.middle_name,"sunev8clt1234567890",e.id)) AS "employee_name"');
		    $this->db->from($this->table);
		    $this->db->join("tblcompetencyquestions b",$this->table.".id = b.competency_id","left");
		    $this->db->join("tblcompetencychoices c","b.id = c.competency_question_id","left");
		    $this->db->join("tblwebusers d",$this->table.".created_by = d.userid","left");
		    $this->db->join("tblemployees e","d.employee_id = e.id","left");
			if($status != "ALL") $this->db->where($this->table.".is_active",$status);
    	    if(isset($_GET["search"]["value"])){
		    	$this->db->group_start();
		     	foreach ($this->select_column as $key => $value) {
		     		$this->db->or_like($value, $_GET["search"]["value"]);
		     	}
		        $this->db->group_end();
		    }
			$this->db->group_by($this->table.'.id');
		    if(isset($_GET["order"])){
				$this->db->order_by($this->order_column[$_GET['order']['0']['column']], $_GET['order']['0']['dir']);
		    }else{
				$this->db->order_by($this->table.'.id', 'DESC');
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
			if($status != "ALL") $this->db->where($this->table.".is_active",$status);
		    return $this->db->count_all_results();
		}
		
		public function hasRows(){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT * FROM tblcompetency";
			$query = $this->db->query($sql);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched competency.";
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
		public function hasRowsActive(){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT * FROM tblcompetency where is_active = '1' ORDER BY competency_name ASC";
			$query = $this->db->query($sql);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched competency.";
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
			$message = "Department failed to activate.";
			$data['Id'] 	= isset($params['id'])?$params['id']:'';
			$data['Status'] 		= "1";
			$userlevel_sql = "	UPDATE tblcompetency SET
									is_active = ? 
								WHERE id = ? ";
			$userlevel_params = array($data['Status'],$data['Id']);
			$this->db->query($userlevel_sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully activated competency.";
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
			$message = "Department failed to deactivate.";
			$data['Id'] 	= isset($params['id'])?$params['id']:'';
			$data['Status'] 		= "0";
			$userlevel_sql = "	UPDATE tblcompetency SET
									is_active = ? 
								WHERE id = ? ";
			$userlevel_params = array($data['Status'],$data['Id']);
			$this->db->query($userlevel_sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully deactivated competency.";
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
			$message = "Failed to add ompentency test.";
			unset($params['id']);
			$quest_insert_id = 0;
			
			// Initialize an empty array to hold the grouped data			
			$data = $data_choices = $arr_questions = $arr_choices = $unique_choices = $itemsToRemove = array();
			$competency = array(
				"type" => $params['type'],
				"created_by" => Helper::get('userid')
			);
			
			$this->db->insert($this->table, $competency);
			if($this->db->affected_rows() > 0) {
				if($this->db->trans_status() === TRUE){					
					$comp_id = $this->db->insert_id();
					// Loop through the POST data
					foreach ($params as $index => $values) {
						// Exclude the "id" from the grouping process
						if ($index !== 'id' && $index !== 'order_no' && $index !== 'type' && $index !== 'choices') {
							// Loop through the "enumeration" and "multiple" sub-arrays
							foreach ($params[$index] as $type => $v) {
								// Store the data in the grouped array using the "name" as the index
								if($type == 'enumeration' || $type == 'fill' || $type == 'multiple'){
									$name = $type;
									$data[$name]['id'] = $comp_id;
									$data[$name]['order_no'] = $params['order_no'][$type];
									$data[$name]['sequence'] = $params['sequence'][$type];
									$data[$name]['points'] = ($type == 'multiple') ? 1 : $params['points'][$type];
									$data[$name]['question'] = $params['question'][$type];
									$data[$name]['answer'] = $params['answer'][$type];
								} else if ($type == 'essay'){
									$name = $type;
									$data[$name]['id'] = $comp_id;
									$data[$name]['order_no'] = $params['order_no'][$type];
									$data[$name]['sequence'] = $params['sequence'][$type];
									$data[$name]['points'] = $params['points'][$type];
									$data[$name]['question'] = $params['question'][$type];
								}
							}
						}
					}

					if(isset($data) && sizeof($data) > 0){
						foreach ($data as $key => $value) {
							foreach ($value['order_no'] as $type => $orderValue) {
								// Assuming $value['sequence'] contains ["enumeration" => [0 => "1"], "multiple" => [0 => "1"]]
								if($key == 'enumeration' || $key == 'fill' || $key == 'multiple'){
									$arr_questions[] = array(
										"competency_id" => $value['id'], 
										"exam_type" => $key, // This will be "enumeration" and "multiple"
										"order_no" => $orderValue[0], // Access the value (e.g., "1") from the sub-array
										"sequence" => $value['sequence'][$type], // Access the sequence value based on $type
										"points" => ($key == 'multiple') ? 1 : $value['points'][$type], // Access the points value based on $type
										"question" => $value['question'][$type], // Access the question value based on $type
										"answer" => $value['answer'][$type], // Access the answer value based on $type
									);
								}
								if ($key == 'essay'){
									$arr_questions[] = array(
										"competency_id" => $value['id'], 
										"exam_type" => $key, // This will be "enumeration" and "multiple"
										"order_no" => $orderValue[0], // Access the value (e.g., "1") from the sub-array
										"sequence" => $value['sequence'][$type], // Access the sequence value based on $type
										"points" => $value['points'][$type], // Access the points value based on $type
										"question" => $value['question'][$type], // Access the question value based on $type
										"answer" => ""
									);
								}
							}
						}
					}

					foreach ($arr_questions as $question) {
						$this->db->insert($this->table.'questions',$question);
						if($question['exam_type'] == 'multiple'){
							$quest_id = $this->db->insert_id();
							$quest_insert_id = $quest_id;
							foreach ($params['choices'] as $choices) {
								// Convert the array of choices to a comma-separated string with double quotes
								$choicesString = '"' . implode('","', $choices) . '"';	
								$arr_choices[] = array(
									"competency_id" => $comp_id,
									"competency_question_id" => $quest_id,
									"choices" => $choicesString
								);
							}
						}
					}

					// Loop through the $arr_choices array
					foreach ($arr_choices as $index => $choice) {
						// Check if the 'competency_question_id' matches the current $id
						if (isset($choice['competency_question_id']) && $choice['competency_question_id'] === $quest_insert_id) {
							// Add the index to the $itemsToRemove array
							$itemsToRemove[] = $index;
						}
					}
					// Remove the items from the $arr_choices array
					foreach ($itemsToRemove as $index) {
						unset($arr_choices[$index-1]);
					}
					
					$this->db->insert_batch($this->table . 'choices', $arr_choices);
					$this->db->trans_commit();
					$code = "0";
					$message = "Successfully added competency test.";
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

		public function updateRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Compentency test failed to update.";			
			$insert_id = 0;

			// Initialize an empty array to hold the grouped data			
			$data = $data_choices = $arr_questions = $arr_choices = $unique_choices = array();

			// Loop through the POST data
			foreach ($params as $index => $values) {
				// Exclude the "id" from the grouping process
				if ($index !== 'id' && $index !== 'order_no' && $index !== 'type' && $index !== 'choices') {
					// Loop through the "enumeration" and "multiple" sub-arrays
					foreach ($params[$index] as $type => $v) {
						// Store the data in the grouped array using the "name" as the index
						if($type == 'enumeration' || $type == 'fill' || $type == 'multiple'){
							$name = $type;
							$data[$name]['id'] = $params['id'];
							$data[$name]['order_no'] = $params['order_no'][$type];
							$data[$name]['sequence'] = $params['sequence'][$type];
							$data[$name]['points'] = ($type == 'multiple') ? 1 : $params['points'][$type];
							$data[$name]['question'] = $params['question'][$type];
							$data[$name]['answer'] = $params['answer'][$type];
						} else if ($type == 'essay'){
							$name = $type;
							$data[$name]['id'] = $params['id'];
							$data[$name]['order_no'] = $params['order_no'][$type];
							$data[$name]['sequence'] = $params['sequence'][$type];
							$data[$name]['points'] = $params['points'][$type];
							$data[$name]['question'] = $params['question'][$type];
						}
					}
				}
			}

			if(isset($data) && sizeof($data) > 0){
				foreach ($data as $key => $value) {
					foreach ($value['order_no'] as $type => $orderValue) {
						// Assuming $value['sequence'] contains ["enumeration" => [0 => "1"], "multiple" => [0 => "1"]]
						if($key == 'enumeration' || $key == 'fill' || $key == 'multiple'){
							$arr_questions[] = array(
								"competency_id" => $value['id'], 
								"exam_type" => $key, // This will be "enumeration" and "multiple"
								"order_no" => $orderValue[0], // Access the value (e.g., "1") from the sub-array
								"sequence" => $value['sequence'][$type], // Access the sequence value based on $type
								"points" => ($key == 'multiple') ? 1 : $value['points'][$type], // Access the points value based on $type
								"question" => $value['question'][$type], // Access the question value based on $type
								"answer" => $value['answer'][$type], // Access the answer value based on $type
							);
						}
						if ($key == 'essay'){
							$arr_questions[] = array(
								"competency_id" => $value['id'], 
								"exam_type" => $key, // This will be "enumeration" and "multiple"
								"order_no" => $orderValue[0], // Access the value (e.g., "1") from the sub-array
								"sequence" => $value['sequence'][$type], // Access the sequence value based on $type
								"points" => $value['points'][$type], // Access the points value based on $type
								"question" => $value['question'][$type], // Access the question value based on $type
								"answer" => ""
							);
						}
					}
				}
			}
			$this->db->trans_begin();
			$this->db->where('competency_id', $params['id'])->delete($this->table.'questions');
			foreach ($arr_questions as $question) {
				$this->db->insert($this->table.'questions',$question);	
				if($question['exam_type'] == 'multiple'){				
					// print_r($question['exam_type']);
					$id = $this->db->insert_id();
					$insert_id = $id;
					$this->db->where('competency_id', $params['id'])->delete($this->table.'choices');
					foreach ($params['choices'] as $choices) {
						// Convert the array of choices to a comma-separated string with double quotes
						$choicesString = '"' . implode('","', $choices) . '"';	
						$arr_choices[] = array(
							"competency_id" => $params["id"],
							"competency_question_id" => $id,
							"choices" => $choicesString
						);
					}
				}
			}
			// Create an array to store the items that need to be removed
			$itemsToRemove = [];
			// Loop through the $arr_choices array
			foreach ($arr_choices as $index => $choice) {
				// Check if the 'competency_question_id' matches the current $id
				if (isset($choice['competency_question_id']) && $choice['competency_question_id'] === $insert_id) {
					// Add the index to the $itemsToRemove array
					$itemsToRemove[] = $index;
				}
			}
			// Remove the items from the $arr_choices array
			foreach ($itemsToRemove as $index) {
				unset($arr_choices[$index-1]);
			}
			$this->db->insert_batch($this->table . 'choices', $arr_choices);

			if($this->db->trans_status() === TRUE) {		
				$this->db->trans_commit();
				$code = "0";
				$message = "Successfully updated competency test.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;
			} else {
				$this->db->trans_rollback();
				$code = "1";
				$message = "Failed to updated compentency test.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}
		
		public function getQuestionRows($id) {
			$ret = array();
			$enumeration = $this->db->select("*")
				->from($this->table.'questions')
				->where("competency_id", $id)
				->where("exam_type", 'enumeration')
				->get()
				->result_array();

			$fill = $this->db->select("*")
				->from($this->table.'questions')
				->where("competency_id", $id)
				->where("exam_type", 'fill')
				->get()
				->result_array();

			$essay = $this->db->select("*")
				->from($this->table.'questions')
				->where("competency_id", $id)
				->where("exam_type", 'essay')
				->get()
				->result_array();

			$multiplechoice = $this->db->select("*")
				->from($this->table.'questions')
				->where("competency_id", $id)
				->where("exam_type", 'multiple')
				->get()
				->result_array();
		
			// Fetch choices for each question and add them to the corresponding question entry in the $multiplechoice array
			foreach ($multiplechoice as $key => $question) {
				$choices = $this->db->select("*")
					->from($this->table.'choices')
					->where("competency_question_id", $question['id'])
					->get()
					->result_array();
					
				// Explode the "choices" string into an array using the comma (,) as the delimiter
				$choicesArray = explode('","', $choices[0]["choices"]);

				// Remove the starting and ending double quotes from the first and last elements of the array
				$choicesArray[0] = trim($choicesArray[0], '"');
				$lastIndex = count($choicesArray) - 1;
				$choicesArray[$lastIndex] = trim($choicesArray[$lastIndex], '"');
				
				// Add the choices array to the current question entry in $multiplechoice
				$multiplechoice[$key]['choices'] = $choicesArray;
			}

			$ret = array(
				"Code" => 0,
				"Message" => "Success!",
				"Data" => array(
					"enumeration" => $enumeration,
					"fill" => $fill,
					"essay" => $essay,
					"multiplechoice" => $multiplechoice,
				)
			);
		
			return $ret;
		}

	}
?>