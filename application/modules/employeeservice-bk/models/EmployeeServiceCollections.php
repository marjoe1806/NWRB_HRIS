<?php
class EmployeeServiceCollections extends Helper {

	public function __construct() {
		ModelResponse::busy();
		$this->load->database();
	}

	public function isExistingEmployees($employee_id_number){
		$sql = " SELECT id FROM tblemployees WHERE DECRYPTER(employee_id_number,'sunev8clt1234567890',id) = ?";
		$query = $this->db->query($sql,array($employee_id_number));
		$data = $query->result_array();
		if(sizeof($data) > 0)
			return true;
		return false;
	}
	public function getEmployeeId($employee_id_number){
		$sql = " SELECT id FROM tblemployees WHERE DECRYPTER(employee_id_number,'sunev8clt1234567890',id) = ? ORDER BY date_created DESC ";
		$query = $this->db->query($sql,array($employee_id_number));
		$data = $query->result_array();
		return $data;
	}
	public function insertEmployeeDTR($data){
		$code = "1";
		$message = "Failed to insert.";
		$this->db->trans_begin();
		foreach ($data['EmpModelList'] as $k => $v) {

			$params = array();
			$id = uniqid();
			$params['id'] = $id;

			//getEmployee
			$employee = $this->getEmployeeId($v['empId']);
			if(sizeof($employee) > 0){
				$id = $employee[0]['id'];
			}

			$params['employee_id_number'] = Helper::encrypt($v['empId'],$id);
			$replaced_id_number = str_replace("-","",$v['empId']);
			$params['employee_number'] = Helper::encrypt($replaced_id_number,$id);
			$params['first_name'] = Helper::encrypt($v['firstName'],$id);
			$params['last_name'] = Helper::encrypt($v['lastName'],$id);
			$params['employment_status'] = "Active";
			$employee_id_photo = 'data:image/jpeg;base64,'.$v['encodedImage'];
			//var_dump($this->isExistingEmployees($v['empId']));die();
			if(!$this->isExistingEmployees($v['empId'])){
				//Insert Employee
				$sql = "INSERT INTO tblemployees(id,employee_id_number,employee_number,first_name,last_name,employment_status)VALUES(?,?,?,?,?,?)";
				$this->db->query($sql,$params);

				//Insert photo
				$params2['employee_id'] = $id;
				$params2['employee_id_photo'] = $employee_id_photo;
				$sql2 = "INSERT INTO tblemployeesphotos(employee_id,employee_id_photo)VALUES(?,?)";
				$this->db->query($sql2,$params2);
			}
			else{
				//die('hit');

				//var_dump($employee);die();
				//var_dump($employee);die();
				if(sizeof($employee) > 0){
					$sql3 = " UPDATE tblemployees SET employee_id_number = ?, employee_number = ?,first_name = ?,last_name = ? WHERE id = ?";
					$params3 = array(	$params['employee_id_number'],$params['employee_number'],
										$params['first_name'],$params['last_name'],
										$employee[0]['id']);
					$this->db->query($sql3,$params3);
					$sql4 = " UPDATE tblemployeesphotos SET employee_id_photo = ? WHERE employee_id = ?";
					$params4 = array($employee_id_photo,$employee[0]['id']);
					$this->db->query($sql4,$params4);
				}
				//Update Employee

			}

		}
		if(isset($data['DailyTimeRecordList']) && sizeof($data['DailyTimeRecordList'] > 0)){
			foreach ($data['DailyTimeRecordList'] as $k => $v) {
				$params = array();
				$replaced_emp_number = str_replace("-","",$v['empIdStr']);
				$params['employee_number'] 	= $replaced_emp_number;
				$params['transaction_date'] = date('Y-m-d', strtotime($v['timestamp']));
				$params['transaction_time'] = date('h:i:s', strtotime($v['timestamp']));
				$params['transaction_type'] = $v['transType'];
				$params['source_location'] 	= $v['transCoordinates'];
				$params['source_device'] 	= @$data['imei'];

				$sql = "INSERT INTO tbltimekeepingdailytimerecord(employee_number,transaction_date,transaction_time,transaction_type,source_location,source_device)VALUES(?,?,?,?,?,?)";
					$this->db->query($sql,$params);
			}
		}
		if(isset($data['AuditTrailList']) && sizeof($data['AuditTrailList'] > 0)){
			foreach($data['AuditTrailList'] as $k1 => $v1) {
				$log_data = array(
					Helper::get('userid'),
					$v1['action'],
					@$data['ip'],
					@$data['imei']
				);
				$sql = " INSERT INTO tblaudittrail(userid,log,ip,source_device)VALUES(?,?,?,?)";
				$query = $this->db->query($sql,$log_data);
			}
		}
		if($this->db->trans_status() === TRUE){
			$code = "0";
			$this->db->trans_commit();
			$message = "Successfully inserted Employee DTR.";
			$this->ModelResponse($code, $message);
			return true;
		}
		else {
			$this->db->trans_rollback();
			$this->ModelResponse($code, $message);
		}
		return false;
	}
	public function insertEmployees(){
		$this->db->trans_begin();
		// die('hit')
		// $pay_basis = "Permanent-Casual";
		$offset = 5000;
		$limit = 5000;
		$inserted = 0;
		$sql = "SELECT id,scan_no,first_name,last_name,pay_basis,department,area FROM tblemployeestemp2 ORDER BY last_name ASC LIMIT $offset,$limit";
		$query = $this->db->query($sql);
		$data = $query->result_array();
		$sqldata = array();
		$names = array();
		// var_dump($data);die();
		foreach($data as $k=>$v){
			$id = uniqid();
			
			
			
			$db = get_instance()->db->conn_id;
			$temp_id = $v['id'];
			// $name = explode(',',$v['name']);
			$fname = strtok($v['first_name'], " ");
			// var_dump($fname);die();
			$scan_no = Helper::encrypt(@$v['scan_no'],$id);
			$first_name =  Helper::encrypt(trim($v['first_name']),$id);
			$middle_name =  Helper::encrypt(" ",$id);
			$last_name =  Helper::encrypt(trim($v['last_name']),$id);
			$pay_basis = "Permanent-Casual";
			// die('hit');
			// var_dump($pay_basis);die();
			// $sql = "SELECT department,area FROM tblemployeestemp WHERE scan_no = '".$v['scan_no']."' AND name = '".mysqli_real_escape_string($db,$v['name'])."' LIMIT 1";
			// $query = $this->db->query($sql);
			// $data2 = $query->result_array();
			// var_dump($data2);die();
			$department = $v['department'];
			$area = $v['area'];
			$name_concat = $fname." ".$v['last_name'];
			if(!in_array($name_concat, $names)){
			// var_dump("(SELECT id FROM tblfielddepartments WHERE code = '".mysqli_real_escape_string($db,$department)."')");die();
				$sql = "INSERT INTO tblemployees(id,employment_status,pay_basis,employee_number,employee_id_number,last_name,middle_name,first_name,division_id,location_id)
				VALUES('$id','Active','$pay_basis','$scan_no','$scan_no','$last_name','$middle_name','$first_name',
				(SELECT id FROM tblfielddepartments WHERE code = '".mysqli_real_escape_string($db,$department)."'),
				(SELECT id FROM tblfieldlocations WHERE name = '".mysqli_real_escape_string($db,$area)."') )";
				// $sql = "INSERT INTO tblemployeestemp2 (id,first_name,last_name,scan_no,department,area)VALUES($id,'".mysqli_real_escape_string($db,$name[1])."','".mysqli_real_escape_string($db,$name[0])."','".$v['scan_no']."','".mysqli_real_escape_string($db,$department)."','".mysqli_real_escape_string($db,$area)."')";
				$query = $this->db->query($sql);
				$inserted += 1;
			}
			else{
				$inserted -= 1;
			}
			$names[] = $name_concat;
		}
		// var_dump($data);die();
		if($this->db->trans_status() === TRUE){
			$this->db->trans_commit();
			echo "SUCCESSfully inserted $inserted in offset: $offset";
		}
		else {
			$this->db->trans_rollback();
			echo "Failed to insert";
		}
	}


}
?>