<?php
	class TravelOrderCollection extends Helper {
		var $select_column = null; 
		var $sql = "";
		var $selectRequestParams = array();
		public function __construct() {
			$this->load->model('Helper');
			$this->load->model('HelperDao');
			ModelResponse::busy();
		}

		var $table = "tbltravelorder";
      	var $order_column = array('','a.travel_order_no','a.duration','c.last_name','a.location','a.officialpurpose','b.department_name','a.status');
      	public function getColumns(){
      		$sql = " SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='".$this->table."' AND TABLE_SCHEMA='".$this->db->database."' ";
			$query = $this->db->query($sql);
			$rows = $query->result_array();
			return $rows;
      	}
		function make_query(){
			$this->select_column[] = 'c.last_name';
			$this->select_column[] = 'c.first_name';
			$this->select_column[] = 'c.middle_name';
			$this->select_column[] = 'a.vehicle_no';
			$this->select_column[] = 'a.location';
			$this->select_column[] = 'a.officialpurpose';
			$this->select_column[] = 'a.duration';
			$this->select_column[] = 'b.department_name';
			$this->select_column[] = 'a.travel_order_no';
			$this->select_column[] = 'a.status';
			$this->db->select('a.*,a.id AS t_id, c.*, b.*');
		    $this->db->join("tblfielddepartments b", "a.division_id = b.id","left");
		    $this->db->join("tblemployees c", "a.employee_id = c.id","left");
		    $this->db->from($this->table." a");
		    $this->db->where("(a.employee_id = '".$_SESSION["id"]."' OR a.created_by = '".$_SESSION["id"]."' )");
			if($_GET["status"] != "") $this->db->where("a.status",$_GET["status"]);

    	    // if(isset($_GET["search"]["value"])){
		    // 	$this->db->group_start();
		    //  	foreach ($this->select_column as $key => $value) {
		    //  		$this->db->or_like($value, $_GET["search"]["value"]);
		    //  	}
		    //     $this->db->group_end();
		    // }

			// for column search
			if(isset($_GET["search"]["value"])){
		    	$this->db->group_start();
		     	foreach ($this->select_column as $key => $value) {
					if($value == "c.first_name" || $value == "c.last_name" || $value == "c.middle_name" || $value == "c.extension")  {
						$this->db->or_like("CONCAT(DECRYPTER(c.last_name,'sunev8clt1234567890',c.id),' ',DECRYPTER(c.extension,'sunev8clt1234567890',c.id),', ',DECRYPTER(c.first_name,'sunev8clt1234567890',c.id),' ',DECRYPTER(c.middle_name,'sunev8clt1234567890',c.id))",$_GET["search"]["value"]); 
					}else{
						$this->db->or_like($value, $_GET["search"]["value"]);  
					}
		     	}
		        $this->db->group_end();
				
		    }
			// end


		    if(isset($_GET["order"])){
				$this->db->order_by($this->order_column[$_GET['order']['0']['column']], $_GET['order']['0']['dir']);
		    }else{
				$this->db->order_by("a.date_created", 'DESC');
				$this->db->where("a.status !=", null);
		    }
		}
		
		function make_datatables(){
		    $this->make_query();
			if($_GET["length"] != -1) $this->db->limit($_GET['length'], $_GET['start']);
		    $query = $this->db->get();
		    return $query->result();
		}

		function get_filtered_data(){
			$this->make_query();
			$query = $this->db->get();
		    return $query->num_rows();
		}
		function get_all_data(){
		    $this->db->select($this->table."*");
			$this->db->from($this->table);
			if($_GET["status"] != "") $this->db->where($this->table.".status",$_GET["status"]);
		    return $this->db->count_all_results();
		}

		public function addRows($params){
			$emp_ids = explode(',', $params['employee_ids']); 
			// $divi_ids = explode(',', $params['division_ids']);
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Travel order failed to insert.";
			$this->db->trans_begin();
			
			// foreach ($divi_ids as $key => $value2) {
			// 	$division_ids = $value2;
			// }
		
			foreach ($emp_ids as $key => $value) {

				$params_travel = array(
					'division_id' => $params['division_id'],
					'employee_id' => $value,
					'location' => $params['location'],
					'travel_order_no' => $params['travel_order_no'],
					// 'destination' => $params['destination'],
					'purpose' => $params['purpose'],
					'reason' => $params['reason'],
					'duration' => $params['duration'],
					'no_days' => $params['no_days'],
					'officialpurpose' => $params['officialpurpose'],
					'travel_id' => $params['travel_id'],
					'created_by' => $_SESSION["id"],
					'source_location' => "manual_input",
					'is_vehicle' => $params['is_vehicle'],
				);
				//var_dump($params_travel);die();
				$this->db->insert('tbltravelorder', $params_travel);		
			}		
			
			if($this->db->trans_status() === TRUE){
				$code = "0";
				$this->db->trans_commit();
				$message = "Travel order successfully inserted.";
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

		public function updateTravelOrder($params){
			// print_r($params); die();
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Travel order failed to update.";

			$params_travel = array(

					'division_id' => $params['division_id'],
					'employee_id' => $params['employee_id'],
					'location' => $params['location'],
					// 'destination' => $params['destination'],
					'purpose' => $params['purpose'],
					'reason' => $params['reason'],
					'duration' => $params['duration'],
					'travel_order_no' => $params['travel_order_no'],
					'driver' => $params['driver'],
					'vehicle_no' => $params['vehicle_no'],
					'no_days' => $params['no_days'],
					'officialpurpose' => $params['officialpurpose'],
					'modified_by' => $_SESSION["id"]
				);
			
			$this->db->where('id', $params['id']);
			$this->db->update('tbltravelorder',$params_travel);

			$params_travel2 = array(

					'location' => $params['location'],
					// 'destination' => $params['destination'],
					'purpose' => $params['purpose'],
					'reason' => $params['reason'],
					'duration' => $params['duration'],
					'travel_order_no' => $params['travel_order_no'],
					'driver' => $params['driver'],
					'vehicle_no' => $params['vehicle_no'],
					'no_days' => $params['no_days'],
					'officialpurpose' => $params['officialpurpose'],
					'modified_by' => $_SESSION["id"]
				);

			$this->db->where('travel_id', $params['travel_id']);
			if ($this->db->update('tbltravelorder',$params_travel2) === FALSE){
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}	
			else {
				$code = "0";
				$message = "Successfully updated travel order.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;	
			}
			return false;
		}

		public function cancelApplication($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Application failed to cancel.";
			$data['id'] 	= isset($params['id'])?$params['id']:'';
			$sql = "UPDATE tbltravelorder SET status = ? WHERE id = ? ";
			$params = array(7,(int)$data['id']);
			$this->db->query($sql,$params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully cancelled application.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;		
			}else {
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		public function fetchTOApprovals($id, $employee_id){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "No data available.";
			// $this->sql = "CALL sp_get_employee_leave_approvals(?)";
			$this->sql = "SELECT c.position_designation,
				b.name as position_title,
				c.position_id,
				a.approver,
				a.approve_type,
				DECRYPTER(c.first_name, 'sunev8clt1234567890', c.id) as first_name,
				DECRYPTER(c.middle_name, 'sunev8clt1234567890', c.id) AS middle_name,
				DECRYPTER(c.last_name, 'sunev8clt1234567890', c.id) as last_name,
				DECRYPTER(c.extension, 'sunev8clt1234567890', c.id) as extension
				FROM 
				tbltravelorderapprover AS a
				LEFT JOIN tblemployees c ON a.approver = c.id
				LEFT JOIN tblfieldpositions b ON c.position_id = b.id
				WHERE a.employee_id = ?
			";
			// $query = $this->db->query($this->sql,array($id));
			$query = $this->db->query($this->sql,array($employee_id));
			$result = $query->result();
			// mysqli_next_result($this->db->conn_id);
			$data['approvers'] = $result;
			if(sizeof($result) > 0){
				$code = "0";
				$message = "Successfully fetched travvel order approvals.";
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

	}
?>