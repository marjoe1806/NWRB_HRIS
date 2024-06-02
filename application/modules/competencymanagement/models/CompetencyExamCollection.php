<?php
	class CompetencyExamCollection extends Helper {

		public function __construct() {
			$this->load->model('HelperDao');
			ModelResponse::busy();
		}
		public function get_data($param) {
			 // Define the custom order of exam_type values
			 $custom_order = array('enumeration', 'fill', 'essay', 'multiplication');
    
			 // Use the CASE statement in a custom SQL string for sorting
			 $custom_order_sql = '(CASE ';
			 foreach ($custom_order as $index => $value) {
				 $custom_order_sql .= 'WHEN exam_type = ' . $this->db->escape($value) . ' THEN ' . $index . ' ';
			 }
			 $custom_order_sql .= 'END)';
			 
			 $competency_id = $param['Data']['type_id'];
			 // Select columns with the custom_order appended for sorting
			 $this->db->select('*');
			 $this->db->select($custom_order_sql . ' AS custom_order', FALSE); // Use FALSE to prevent CI from adding backticks to the custom SQL
			 $this->db->from('tblcompetencyquestions');
			 $this->db->where('competency_id', $competency_id);
			 $this->db->order_by('custom_order', 'ASC'); // Sort based on custom_order ascending
			 $query = $this->db->get();
			 
			 return $query->result();
		}

		public function get_competency_choices($param) {
			$competency_id = $param['Data']['type_id'];
			$sql = "tblcompetencychoices";
			$this->db->where('competency_id', $competency_id);
			$query = $this->db->get($sql);
			return $query->result();
		}
		public function insert_data_to_result($data) {
			// Assuming you have a table named 'data_table' to store this data
			$this->db->insert('tblcompetencyexamresult', $data);
		  }

		  public function insert_data_to_answer($data) {
			// var_dump($data); die();
			// Assuming you have a table named 'data_table' to store this data
			$this->db->insert('tblcompetencyexamresult', $data);
		  }

		public function validateAccess($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Invalid access link.";
			$this->db->select(
		    	'tblcompetencyaccess.*,
				tblcompetencyaccessemail.*,
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

    }
?>