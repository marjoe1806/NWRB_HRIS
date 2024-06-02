<?php
	class PositionsCollection extends Helper {
		var $select_column = null;
		public function __construct() {
			$this->load->model('HelperDao');
			ModelResponse::busy();
			$columns = $this->getColumns();
			foreach ($columns as $key => $value) {
				$this->select_column[] = $this->table.'.'.$value['COLUMN_NAME'];
			}
		}

		var $table = "tblfieldpositions";
      	var $order_column = array(null,'code','name','is_break','pay_basis','tblfieldsalarygrades.grade','is_active');
      	public function getColumns(){
      		$sql = " SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='".$this->table."' AND TABLE_SCHEMA='".$this->db->database."' ";
			$query = $this->db->query($sql);
			$rows = $query->result_array();
			return $rows;
      	}
		function make_query($grade){
		    $this->db->select(
		    	$this->table.'.*,
		    	tblfieldsalarygrades.grade,
		    	tblfieldsalarygradesteps.step,
		    	tblfieldsalarygradesteps.salary'
		    );
		    $this->db->from($this->table);
		    $this->db->join("tblfieldsalarygrades","tblfieldsalarygrades.grade = ".$this->table.".salary_grade_id AND tblfieldsalarygrades.is_active = 1","left");
			$this->db->join("tblfieldsalarygradesteps","tblfieldsalarygrades.grade = tblfieldsalarygradesteps.grade_id AND tblfieldsalarygradesteps.step = ".$this->table.".salary_grade_step_id AND tblfieldsalarygradesteps.is_active = 1","left");
			if($grade != "ALL") $this->db->where("tblfieldsalarygrades.grade",$grade);
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
				$this->db->order_by('tblfieldpositions.id', 'ASC');
		    }
		}
		
		function make_datatables($grade){
		    $this->make_query($grade);
			if($_GET["length"] != -1) $this->db->limit($_GET['length'], $_GET['start']);
			
		    $query = $this->db->get();
		    return $query->result();
		}
		function get_filtered_data($grade){
			$this->make_query($grade);
			$query = $this->db->get();
		    return $query->num_rows();
		}
		function get_all_data($grade){
		    $this->db->select($this->table."*");
			$this->db->from($this->table);
			if($grade != "ALL") $this->db->where("salary_grade_id",$grade);
		    return $this->db->count_all_results();
		}


		public function hasRows(){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " 	SELECT a.*,b.grade,c.step,c.salary FROM tblfieldpositions a 
						LEFT JOIN tblfieldsalarygrades b ON b.grade = a.salary_grade_id AND b.is_active = 1
						LEFT JOIN tblfieldsalarygradesteps c ON b.grade = c.grade_id AND c.step = a.salary_grade_step_id AND c.is_active = 1
						GROUP BY a.id
				";
			$query = $this->db->query($sql);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			// var_dump($this->db->last_query()); die();
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched positions.";
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
		public function hasRowsById($id){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " 	SELECT a.*,b.grade,b.id AS grade_id,c.step,c.salary,c.id AS grade_step_id, d.rep_allowance,d.transpo_allowance FROM tblfieldpositions a 
						LEFT JOIN tblfieldsalarygrades b ON b.grade = a.salary_grade_id AND b.pay_basis = a.pay_basis
						LEFT JOIN tblfieldsalarygradesteps c ON c.step = a.salary_grade_step_id AND c.grade_id = b.grade AND a.pay_basis = c.pay_basis
						LEFT JOIN tblfieldratasettings d ON d.position_id = a.id AND d.is_active = 1
						WHERE a.id = ? AND b.is_active = 1 #AND c.is_active = 1 
				";
			$query = $this->db->query($sql,array($id));
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched positions.";
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
			$sql = " SELECT * FROM tblfieldpositions where is_active = '1' ";
			if(isset($_POST['pay_basis']) && $_POST['pay_basis'] != null) $sql .= " AND pay_basis = '".$_POST['pay_basis']."'";
			if(isset($_POST['salary_grade_id']) && $_POST['salary_grade_id'] != null) $sql .= " AND salary_grade_id = '".$_POST['salary_grade_id']."'";
			$query = $this->db->query($sql);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched positions.";
				$this->ModelResponse($code, $message, $data);	
				return true;		
			}	
			else $this->ModelResponse($code, $message);
			return false;
		}
		public function hasRowsActiveByName(){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = " SELECT * FROM tblfieldpositions where is_active = '1' GROUP BY name";
			// if(isset($_POST['pay_basis']) && $_POST['pay_basis'] != null) $sql .= " AND pay_basis = '".$_POST['pay_basis']."'";
			// if(isset($_POST['salary_grade_id']) && $_POST['salary_grade_id'] != null) $sql .= " AND salary_grade_id = '".$_POST['salary_grade_id']."'";
			$query = $this->db->query($sql);
			$userlevel_rows = $query->result_array();
			$data['details'] = $userlevel_rows;
			if(sizeof($userlevel_rows) > 0){
				$code = "0";
				$message = "Successfully fetched positions.";
				$this->ModelResponse($code, $message, $data);	
				return true;		
			}	
			else $this->ModelResponse($code, $message);
			return false;
		}
		public function activeRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Position level failed to activate.";
			$data['Id'] 	= isset($params['id'])?$params['id']:'';
			$data['Status'] 		= "1";
			$userlevel_sql = "	UPDATE tblfieldpositions SET
									is_active = ? 
								WHERE id = ? ";
			$userlevel_params = array($data['Status'],$data['Id']);
			$this->db->query($userlevel_sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully activated position.";
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
			$message = "Position level failed to deactivate.";
			$data['Id'] 	= isset($params['id'])?$params['id']:'';
			$data['Status'] 		= "0";
			$userlevel_sql = "	UPDATE tblfieldpositions SET
									is_active = ? 
								WHERE id = ? ";
			$userlevel_params = array($data['Status'],$data['Id']);
			$this->db->query($userlevel_sql,$userlevel_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully deactivated position.";
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
			$params['is_break'] = isset($params['is_break']) && $params['is_break'] == "on" ? 1 : 0;
			$params['is_dtr'] = isset($params['is_dtr']) && $params['is_dtr'] == "on" ? 1 : 0;
			$params['is_driver'] = isset($params['is_driver']) && $params['is_driver'] == "on" ? 1 : 0;
			$params['modified_by'] = Helper::get('userid');

			$message = "Position failed to insert.";
			$this->db->insert('tblfieldpositions', $params);
			if($this->db->affected_rows() > 0)
			{
				$code = "0";
				$message = "Successfully inserted position.";
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
			// var_dump(json_encode($params));die();
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Position failed to update.";
			$params['is_break'] = isset($params['is_break']) ? 1 : 0;
			$params['is_leave_recom'] = isset($params['is_leave_recom']) ? 1 : 0;
			$params['is_dtr'] = isset($params['is_dtr']) ? 1 : 0;
			$params['is_driver'] = isset($params['is_driver'])? 1 : 0;
			$params['modified_by'] = Helper::get('userid');
			// var_dump($params); die();
			$this->db->where('id', $params['id']);
			if ($this->db->update('tblfieldpositions',$params) === FALSE){
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}	
			else {
				$code = "0";
				$message = "Successfully updated position.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;	
			}
			return false;
		}

	}
?>