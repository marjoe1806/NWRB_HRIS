<?php
class EmployeeServiceCollections extends Helper {

	public function __construct() {
		$this->load->model('HelperDao');
		$this->load->model('MessageRels');
		$this->load->model('SystemMessages');
		ModelResponse::busy();
		$this->load->database();
	}

	//function to initiate login
	public function checkUser($params) {		
		$data = array();
		$code = "1";
		$message = "Username does not exist or is inactive.";

		$user = $this->getUser($params['username']);
		$params['username'] = strtoupper($params['username']);
		if(isset($user[0]['employee_id'])){
			$message = "Username and password does not match.";
			$password = $this->Helper->encrypt($params['password'],$params['username']);
			if($this->getUser($params['username'], $password)){
				$code = "0";
				$message = "Successfully fetched data.";
				$data = $this->getSessionVar($user[0]['employee_id']);
				$this->ModelResponse($code, $message, $data);
				return true;
			} else {
				$this->ModelResponse($code, $message);
			}
		} else {
			$this->ModelResponse($code, $message);
		}
		return false;
	}

	//function to update password
	public function changePass($params) {		
		$helperDao = new HelperDao();
		$sysmessages = new SystemMessages();
		$code = "1";
		$message = "Failed to update password.";

		$user = $this->getUser($params['username']);
		$params['username'] = strtoupper($params['username']);
		if(isset($user[0]['employee_id'])){
			$message = "Username and old password does not match.";
			$password = $this->Helper->encrypt($params['password'],$params['username']);
			$oldpassword = $this->Helper->encrypt($params['oldpassword'],$params['username']);
			if($this->getUser($params['username'], $oldpassword)){
				$data = array(
					"password" => $password,
					"isfirstlogin" => "0"
				);

				$this->db->where('username', $params['username']);
				if ($this->db->update('tblmobileusers',$data) === FALSE){
					$this->ModelResponse($code, $message);
				} else {
					// update web users
					$this->db->where('employee_id', $user[0]['employee_id']);
					if ($this->db->update('tblwebusers',$data) === FALSE){
						$helperDao->auditTrails(Helper::get('userid'),$message);
						$this->ModelResponse($code, $message);
					}
					// send email
					$sysmessages->init(MessageRels::EMAIL_USER_UPDATE);
					$message = $sysmessages->getMessage();
					$message = str_replace("<fname>","Sir/Ma'am",$message);
					$changelogs = "\n USERNAME: ". $params['username'];
								// . "\n PASSWORD: (Mobile Change Password)";
					$message = str_replace("<changelogs>",$changelogs,$message);
					$helperDao->sendMail(
						$sysmessages->getSubject(),
						$params['username'],
						$message
					);

					$code = "0";
					$message = "Password successfully updated.";
					$helperDao->auditTrails(Helper::get('userid'),$message);
					$this->ModelResponse($code, $message);
					return true;
				}
			} else {
				$this->ModelResponse($code, $message);
			}	
		} else {
			$this->ModelResponse($code, $message);
		}		
		return false;
	}

	//function to insert employee timelog
	public function addEmployeeDTR($params) {
		$code = "1";
		$message = "Failed to insert timelog.";

		if(isset($params['scanning_no'])) {
			$this->db->select('*');
			$this->db->from('tblemployees');
			$this->db->where('DECRYPTER(employee_id_number,"sunev8clt1234567890",id)', $params['scanning_no']);
			$res =  $this->db->get()->result_array();

			if(sizeof($res) > 0) {
				// $data['regular_shift'] = $res[0]['regular_shift'];
				// $data['shift_id'] = $res[0]['shift_id'];
				// $data['flex_shift_id'] = $res[0]['flex_shift_id'];
				// $data['shift_date_effectivity'] = $res[0]['shift_date_effectivity'];
				// $data['pay_basis'] = $res[0]['pay_basis'];
				// $data['division_id'] = $res[0]['division_id'];
				// $data['position_id'] = $res[0]['position_id'];
				// $data['payroll_grouping_id'] = $res[0]['payroll_grouping_id'];
				// $data['billing_type'] = $res[0]['billing_type'];
				// $data['billing_rate'] = $res[0]['billing_rate'];
				// $data['cola'] = $res[0]['cola'];
			}
		}
			

		$data['employee_number'] = $params['scanning_no'];
		$data['transaction_date'] = $params['transaction_date'];
		$data['transaction_time'] = $params['transaction_time'];
		$data['col4'] = "0";
		$data['transaction_type'] = $params['transaction_type'];
		$data['col6'] = "0";
		$data['col7'] = "0";
		$data['source_location'] = $params['source_location'];
		$data['source_device'] = $params['source_device'];
		$data['latitude'] = "";
		$data['longitude'] = "";
		$data['remarks'] = "";
		$data['modified_by'] = "";
		$data['log_id'] = "";

		$this->db->insert('tbltimekeepingdailytimerecord', $data);
		if($this->db->affected_rows() > 0) {
			$code = "0";
			$message = "Successfully recorded employee timelog.";
			$this->ModelResponse($code, $message);
			return true;		
		} else {
			$this->ModelResponse($code, $message);
		}
		return false;
	}

	//function to get user details using username/password
	public function getUser($username,$password=null){
		$this->db->select('*');
		$this->db->from('tblmobileusers');
		$this->db->where("status", "ACTIVE");
		$this->db->where("username", $username);
		if($password != null)
			$this->db->where("password",$password);
		return $this->db->get()->result_array();
	}

	//get session details 
	public function getSessionVar($employee_id){
		$this->db->select('tblmobileusers.username');
		$this->db->select('tblmobileusers.isfirstlogin');
		$this->db->select('CONCAT(DECRYPTER(tblemployees.last_name,"sunev8clt1234567890",tblemployees.id),", ",DECRYPTER(tblemployees.first_name,"sunev8clt1234567890",tblemployees.id)," ",DECRYPTER(tblemployees.middle_name,"sunev8clt1234567890",tblemployees.id)) AS "full_name"');
		$this->db->select('DECRYPTER(tblemployees.employee_number,"sunev8clt1234567890",tblemployees.id) AS "scanning_no"');
		$this->db->select('tblfieldlocations.name AS "location"');
		$this->db->select('tblfieldlocations.latitude');
		$this->db->select('tblfieldlocations.longitude');
		$this->db->select('tblfieldlocations.radius');
		$this->db->select('tblfieldlocations.lat1');
		$this->db->select('tblfieldlocations.lat2');
		$this->db->select('tblfieldlocations.long1');
		$this->db->select('tblfieldlocations.long2');
		$this->db->from('tblmobileusers');
		$this->db->join('tblemployees', 'tblmobileusers.employee_id = tblemployees.id');
		$this->db->join('tblfieldlocations', 'tblemployees.location_id = tblfieldlocations.id');
		$this->db->where("tblmobileusers.employee_id", $employee_id);
		return $this->db->get()->result_array();
	}

	public function getActualDTR($date,$scanning_no){
		$sql = "SELECT * FROM tbltimekeepingdailytimerecord WHERE DATE(transaction_date) = DATE('".$date."') AND CAST(employee_number AS INT) = CAST('".$scanning_no."' AS INT)";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function getDTR($date,$scanning_no){
		$sql = "SELECT * FROM tbldtr WHERE DATE(transaction_date) = DATE('".$date."') AND CAST(scanning_no AS INT) = CAST('".$scanning_no."' AS INT)";
		$query = $this->db->query($sql);
		return $query->row_array();
	}

	public function import_employees_dtr($params, $scanning_no, $date){
		$helperDao = new HelperDao();
		$code = "1";
		$message = "Failed to insert timelog.";

		$this->db->trans_begin();
		if($scanning_no != null && $date != null){
			$sql = "DELETE FROM tbldtr WHERE DATE(transaction_date) = DATE('".$date."') AND CAST(scanning_no AS INT) = CAST('".$scanning_no."' AS INT)";
			$query = $this->db->query($sql);
		}
		
		$sql = "INSERT INTO tbldtr (scanning_no, transaction_date, check_in, break_out, break_in, check_out, ot_in, ot_out, offset, approve_offset_hrs, approve_offset_mins, ot_hrs, ot_mins, monetized, tardiness_hrs, tardiness_mins, ut_hrs, ut_mins, remarks, source) VALUES " . $params;
		$query = $this->db->query($sql);
		if($this->db->trans_status() === TRUE) {
			$this->db->trans_commit();
			$code = "0";
			$message = "Successfully recorded employee timelog.";
			$helperDao->auditTrails(Helper::get('userid'),$message);
			$this->ModelResponse($code, $message);
			return true;
		} else {
			$this->db->trans_rollback();
			$code = "1";
			$message = "Failed to insert timelog.";
			$helperDao->auditTrails(Helper::get('userid'),$message);
			$this->ModelResponse($code, $message);
		}
		return false;
	}
}
?>