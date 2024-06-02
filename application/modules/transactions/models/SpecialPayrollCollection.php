<?php
	class SpecialPayrollCollection extends Helper { 
		public function __construct() {
			$this->load->model('HelperDao');
			ModelResponse::busy();
		}

		public function getList($division_id,$bonus_type,$year){
			$sql = "SELECT a.id, a.last_name, a.first_name, a.middle_name, a.salary,
						IFNULL((SELECT amount FROM tbltransactionsbonus WHERE employee_id = a.id AND bonus_type = '".$bonus_type."' AND year = '".$year."' AND is_active = 1),0) AS amount,
						IFNULL((SELECT created_by FROM tbltransactionsbonus WHERE employee_id = a.id AND bonus_type = '".$bonus_type."' AND year = '".$year."' AND is_active = 1),NULL) AS created_by,
						IFNULL((SELECT date_modified FROM tbltransactionsbonus WHERE employee_id = a.id AND bonus_type = '".$bonus_type."' AND year = '".$year."' AND is_active = 1),NULL) AS date_modified,
						IFNULL((SELECT username FROM tblwebusers WHERE userid = (SELECT created_by FROM tbltransactionsbonus WHERE employee_id = a.id AND bonus_type = '".$bonus_type."' AND year = '".$year."' AND is_active = 1)),NULL) AS username
					FROM tblemployees AS a WHERE a.division_id = '".$division_id."' ORDER BY DECRYPTER(a.last_name,'sunev8clt1234567890',a.id)";
			$query = $this->db->query($sql);
			return $query->result_array();
		}

		public function saveBonus($params){
			$sql = "SELECT 1 FROM tbltransactionsbonus WHERE employee_id = '".$params['employee_id']."' AND division_id = '".$params['division_id']."' AND bonus_type = '".$params['bonus_type']."' AND year = '".$params['year']."'";
			$query = $this->db->query($sql);
			if(sizeof($query->result_array()) > 0) {
				$this->updateRows($params);
			} else {
				$this->addRows($params);
			}
		}

		public function addRows($params){
			$helperDao = new HelperDao();
			$code = "1";
			$params['created_by'] = Helper::get('userid');
			$message = "Failed to add special payroll.";
			$this->db->trans_begin();
			$this->db->insert("tbltransactionsbonus", $params);

			if($this->db->trans_status() === TRUE){
				$code = "0";
				$message = "Successfully added special payroll.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				$this->db->trans_commit();
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
			$message = "Failed to updated special payroll.";
			$params['created_by'] = Helper::get('userid');
			$this->db->trans_begin();
			$this->db->where('employee_id', $params['employee_id']);
			$this->db->where('division_id', $params['division_id']);
			$this->db->where('year', $params['year']);
			$this->db->where('bonus_type', $params['bonus_type']);
			$this->db->update('tbltransactionsbonus',$params);
			
			if($this->db->trans_status() === TRUE){
				$code = "0";
				$message = "Successfully updated special payroll.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				$this->db->trans_commit();
				return true;	
			}	
			else {
				$this->db->trans_rollback();
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}


	}
?>