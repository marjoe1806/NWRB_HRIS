<?php
	class CompetencyAccessCollection extends Helper {
		var $select_column = null; 
		public function __construct() {
			$this->load->model('Helper');
			$this->load->model('HelperDao');
			ModelResponse::busy();
		}

		var $table = "tblcompetencyaccess";
		var $table2 = "tblcompetencyaccessemail";
      	var $order_column = array('tblcompetencyaccess.reference',
		  'tblcompetencyaccess.date',
		  'tblcompetencyaccess.time_start',
		  'tblcompetencyaccess.time_end',
		  'tblcompetency.type',
		  'tblcompetencyaccess.status',
		  'tblcompetencyaccess.exam_durations'
		);
		function make_query($status){
			$this->select_column[] = 'tblcompetencyaccess.reference';
			$this->select_column[] = 'tblcompetencyaccess.date';
			$this->select_column[] = 'tblcompetencyaccess.time_start';
			$this->select_column[] = 'tblcompetencyaccess.time_end';
			$this->select_column[] = 'tblcompetency.type';
			$this->select_column[] = 'tblcompetencyaccess.status';
			$this->select_column[] = 'tblcompetencyaccess.exam_duration';

		    $this->db->select(
		    	$this->table.'.*,
		    	tblcompetency.type'
		    );
		    $this->db->from($this->table);
		    $this->db->join("tblcompetency",$this->table.".type_id = tblcompetency.id","left");
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
			if($status != "ALL") $this->db->where($this->table.".status",$status);
		    return $this->db->count_all_results();
		}
		public function addRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$params['created_by'] = Helper::get('userid');
			$message = "Access failed to insert.";
			$this->db->insert($this->table, $params);
			$insert_id = $this->db->insert_id();
			if($this->db->affected_rows() > 0)
			{
				$code = "0";
				$message = "Successfully added access.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
                $data = array(
                    'access_id' => $insert_id, 
                );
				$this->ModelResponse($code, $message, $data);
				return true;		
			}	
			else {
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}
		public function addEmails($params, $key = 'add', $resend = 0){
			$helperDao = new HelperDao();

			if($key == 'update' && (sizeof($params) > 0)){
				$this->db->where('access_id', $params[0]['access_id']);
				$this->db->where('status', '0');
				$this->db->delete($this->table2);
			}
			$code = "1";
			$message = "Emails failed to insert.";
			$this->db->insert_batch($this->table2, $params);
			if($this->db->affected_rows() > 0)
			{	
				$code = "0";
				$message = "Successfully added access.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				if($resend == 1){
					$this->sendEmailAccess($params);
				}
				return true;		
			}	
			else {
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}
		public function validateEmailExam($emailaddress, $access_id){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Validate email failed fetch.";
			
			$this->db->from($this->table2);
			$this->db->where('emailaddress', $emailaddress); 
			$this->db->where('access_id', $access_id); 
			$this->db->where('status', '1'); 
			$query = $this->db->get();

			if ($query === FALSE){
				$helperDao->auditTrails(Helper::get('userid'),$message);
			}	
			else {
				$rowcount = $query->num_rows();

				$code = "0";
				$message = "Successfully fetch last id.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				return $rowcount;
			}
			return false;
		}
		public function sendEmailAccess($params) {
			//$data = json_encode(array("Email"=>$email, "BaseUrl"=>$baseurl));
			$this->load->model('HelperDao');
			$this->load->model('SystemMessages');
			$this->load->model('MessageRels');
			$helperDao = new HelperDao();
			$code = "1";
			$message = "User password failed to reset.";
			$message2 = "Email does not exist.";
			$sysmessages = new SystemMessages();
			
			foreach ($params as $el) {
				$data = $el['emailaddress'].'-'.$el['access_id'];
				$endlink = Helper::encrypt($data,'0000CoMPeTeNcY0000ExAm0000');

				$email = $el['emailaddress'];
				$link = base_url().'competencymanagement/CompetencyExam/'.$endlink;
				$css = 'background-color: #4CAF50; border: none; color: white; padding: 15px 32px; text-align: center; text-decoration: none; font-size: 16px; margin: 4px 2px; cursor: pointer;';
				$button_link = '<a href="'.$link.'"><button  style="'.$css.'" type="button">Take Examination</button></a>';

				$message = '<html><style>p {font-size:12px;} </style><body>';
				$message .= '<p>Good day ' . $email . ',</p>';
				$message .= '<p>To take your competency test, click the link below.</p><br>';
				$message .= '<p>Link:</p>';
				$message .= $button_link ;
				$message .= '<p>Thank you, </p>';
				$message .= '<p style="font-weight: bold;">Competency Test Examiner</p><br>';
				$message .= '<p>**NOTE: THIS IS A SYSTEM GENERATED EMAIL, DO NOT REPLY.**</p>';
				$message .= '</body></html>';

				$mes = $helperDao->sendMail2(
					'Competency Test Examination',
					$email,
					$message
				);
			}
		}
		public function getLastRow($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Last count failed fetch.";
			
			$this->db->from('tblcompetencyaccess');
			$this->db->like('date_created', $params); 
			$query = $this->db->get();

			if ($query === FALSE){
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}	
			else {
				$rowcount = $query->num_rows();

				$code = "0";
				$message = "Successfully fetch last id.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return $rowcount;	
			}
			return 0;
		}
		public function getType($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Type is no longger exist or been deactivated.";
			if($params != 'All'){
				$this->db->from('tblcompetency');
				$this->db->where('id', $params);
				// $this->db->where('is_active', '1');
				$query = $this->db->get();
			}else{
				$this->db->from('tblcompetency');
				$this->db->where('is_active', '1');
				$query = $this->db->get();
			}
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
		public function getEmployee($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Employee is no longger exist or been deactivated.";
			if($params != 'All'){
				$this->db->select(
					'tblemployees.id,tblemployees.email'
				);
				$this->db->from('tblemployees');
				$this->db->where('id', $params);
				$this->db->order_by('email', 'Asc');
				$query = $this->db->get();
			}else{
				$this->db->select(
					'tblemployees.id,tblemployees.email'
				);
				$this->db->from('tblemployees');
				$this->db->where('is_active', '1');
				// $this->db->where('email', 'eugene.padernal@mobilemoney.ph');
				$this->db->order_by('email', 'Asc');
				$query = $this->db->get();
			}
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
		public function getEmailList($id){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Email list failed fetch.";
			
			$this->db->from($this->table2);
			$this->db->where('access_id', $id);
			$this->db->order_by('status', 'Desc');
			$query = $this->db->get();

			if ($query === FALSE){
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}	
			else {
				$code = "0";
				$message = "Successfully fetch email list.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return $query->result();
			}
			return false;
		}
		public function updateRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Competency access failed to update.";
			$params['modified_by'] = Helper::get('userid');
			$this->db->where('id', $params['id']);
			if ($this->db->update('tblcompetencyaccess',$params) === FALSE){
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}	
			else {
				$code = "0";
				$message = "Successfully updated competency.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;	
			}
			return false;
		}

	}
?>