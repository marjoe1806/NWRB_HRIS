<?php
    class JobOpeningsCollection extends Helper {
        var $select_column = null;
		public function __construct() {
			$this->load->model('HelperDao');
			ModelResponse::busy();
			$columns = $this->getColumns();
			foreach ($columns as $key => $value) {
				$this->select_column[] = $this->table.'.'.$value;
			}
		}


        // set orderable columns in job opening list
        var $table = "tblrecruitmentvacancies";
        
		var $order_column = array(null,'id','name','code','grade','salary','place','expiration_date','is_approve','is_active');

        // set searchable parameters in tblrecruitmentvacancies table
		public function getColumns() {
			$rows = array('id','name','code','grade','salary','place','expiration_date','is_approve','is_active');
			return $rows;
		}

        // set limit in datatable
		function make_datatables() {
			$this->make_query();
			if($_POST["length"] != -1) {
				$this->db->limit($_POST['length'], $_POST['start']);
			}

			$query = $this->db->get();
			return $query->result();
		}

        // fetch list of job opening
		function make_query() {
			$order_column =  array(null,'id','name','code','grade','salary','place','expiration_date','is_approve','is_active');
			// change effectivity to salary based on tblfieldsalarygradesteps
			$this->db->select(
				$this->table.'.*,
				tblfieldpositions.name AS name,
				tblfieldpositions.code AS code,
				tblfieldpositions.salary_grade_id AS grade,
				tblfieldsalarygradesteps.salary AS salary,
				tblrecruitmentvacanciesqualifications.name AS qualification_name
				');
			$this->db->from($this->table);

			$this->db->join('tblfieldpositions', $this->table.'.position_id = tblfieldpositions.id', 'left');
			$this->db->join('tblrecruitmentvacanciesqualifications', $this->table.'.id = tblrecruitmentvacanciesqualifications.vacancy_id', 'left');
			$this->db->join('tblfieldsalarygrades', 'tblfieldpositions.salary_grade_id = tblfieldsalarygrades.id', 'left');
			$this->db->join('tblfieldsalarygradesteps', 'tblfieldpositions.salary_grade_id = tblfieldsalarygradesteps.grade_id AND tblfieldpositions.salary_grade_step_id = tblfieldsalarygradesteps.step', 'left');
			$this->db->group_by($this->table.'.id');

			$select_column = array(
				'tblrecruitmentvacancies.id',
				'tblfieldpositions.name',
				'code',
				'grade',
				'salary',
				'place',
				'expiration_date',
				'is_approve',
				'tblrecruitmentvacancies.is_active',
				);
				
		
			if(isset($_POST["search"]["value"])) {
				$this->db->group_start();
				foreach($select_column as $key => $value) {
					if($value == 'is_approve') {
						if(strpos(strtolower($_POST["search"]["value"]), 'approve') !== false)
							$this->db->or_like($value, 1);
						if(strpos(strtolower($_POST["search"]["value"]), 'pending') !== false)
							$this->db->or_like($value, 0);
					}

					if($value == 'tblrecruitmentvacancies.is_active') {
						if(strpos(strtolower($_POST["search"]["value"]), 'active') !== false)
							$this->db->or_like($value, 1);
						else
							$this->db->or_like($value, 0);
					}

					$this->db->or_like($value, $_POST["search"]["value"]);
				}
				$this->db->group_end();
			}
			if(isset($_POST["order"])){
				$this->db->order_by($order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
				}else{
					$this->db->order_by($this->table.'.id');
		}

			if(isset($_GET['SalaryGradeId']) && $_GET['SalaryGradeId'] != null)
				$this->db->where('tblfieldsalarygrades.grade = "'.$_GET['SalaryGradeId'].'"');

			if(isset($_GET['Name']) && $_GET['Name'] != null)
				$this->db->like('tblfieldpositions.name', $_GET['Name']);

            if(isset($_GET['Place']) && $_GET['Place'] != null)
				$this->db->like('tblrecruitmentvacancies.place', $_GET['Place']);

            if(isset($_GET['IsApprove']) && $_GET['IsApprove'] != null)
				$this->db->where('tblrecruitmentvacancies.is_approve', intval($_GET['IsApprove']));

            if(isset($_GET['IsActive']) && $_GET['IsActive'] != null)
				$this->db->like('tblrecruitmentvacancies.is_active', $_GET['IsActive']);
		}

		public function getJobOpeningsRows($id) {
			$ret = array();
			$vacancyqualifications = $this->db->select("*")->from($this->table.'qualifications')->where('vacancy_id', $id)->get()->result_array();
			$this->db->select("*")->from($this->table)->where($this->table.'.id', $id);
			$this->db->join('tblfieldpositions', $this->table.'.position_id = tblfieldpositions.id', 'left');
			$job_opening = $this->db->get()->result_array();
			$ret = array("Code"=>0,"Message"=>"Success!",
				"Data"=>array(
					"job_opening" => $job_opening,
					"vacancyqualifications" => $vacancyqualifications,
				)
			);
			return $ret;
		}

		public function deleteQualifications($id,$name,$category) {
			$helperDao = new HelperDao();
			$code = "1";

			$this->db->delete('tblrecruitmentvacanciesqualifications', array('id' => $id));

			if ($this->db->trans_status() === TRUE) {
				$code = "0";
				$this->db->trans_commit();
				$message = "Successfully deleted qualification.";
				$helperDao->auditTrails(Helper::get('userid'), $message);
				$this->ModelResponse($code, $message);
				return true;
			} else {
				$message = "Failed to deleted qualification.";
				$helperDao->auditTrails(Helper::get('userid'), $message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		

        // get count of job opening
		function get_all_data() {
			$this->db->select($this->table."*");
			$this->db->from($this->table);
			return $this->db->count_all_results();
		}

        // get count of filtered job opening
		function get_filtered_data(){
			$this->make_query();
			$query = $this->db->get();
			return $query->num_rows();
		}

		// add case
		public function addRows($params) {
			$helperDao = new HelperDao();
			$code = "1";
			$params['modified_by'] = Helper::get('userid');
			$message = "Failed to add job opening";

			// set value for tblrecruitmentvacancies table
			$job_opening['position_id'] = $params['position_id'];
			$job_opening['place'] = $params['place'];
			$job_opening['expiration_date'] = $params['expiration_date'];
			$job_opening['publication_date'] = $params['publication_date'];
			// $job_opening['expiration_date'] = date('Y-m-d', $job_opening['expiration_date']);
			$job_opening['modified_by'] = $params['modified_by'];

			$this->db->insert('tblrecruitmentvacancies', $job_opening);
			$id = $this->db->insert_id();

			if(isset($params['qualification_name'])) {
				$qualifications_name = $params['qualification_name'];
				$qualifications_category = $params['qualification_category'];

				foreach ($qualifications_name as $key => $value) {
					$data['vacancy_id'] = $id;
					$data['name'] = $value;
					$data['category'] = $qualifications_category[$key];
					$data['modified_by'] = $job_opening['modified_by'];

					$this->db->insert('tblrecruitmentvacanciesqualifications', $data);

				}
			}

			if($this->db->affected_rows() > 0) {
				$code = "0";
				$message = "Successfully inserted job opening";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;
			} else {
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		// update job expiration date
		public function updateExpirationDate($params)
		{
			// print_r($params); die();
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Job opening expiration date failed to update.";
			$vacancyqualifications = $this->db->select("*")->from($this->table.'qualifications')->where('vacancy_id', $params['id'])->get()->result_array();
			
			$data['id'] = isset($params['id']) ? $params['id'] : '';
			$data['expiration_date'] = $params['expiration_date'];
			$data['publication_date'] = $params['publication_date'];
			$data['position_id'] = $params['position_id'];
			$data['place'] = $params['place'];
			$data['modified_by'] = Helper::get('userid');

			$sql = "UPDATE tblrecruitmentvacancies SET position_id = ?, place = ?, expiration_date = ?, modified_by = ? WHERE id = ?";
			$sql_params = array($data['position_id'], $data['place'], $data['expiration_date'], $data['modified_by'], $data['id']);

			foreach ($params['qualification_name'] as $key => $value) {
				if (isset($vacancyqualifications[$key]['id'])){
					$sql1 = "UPDATE tblrecruitmentvacanciesqualifications SET name = ?, category = ?, modified_by = ? WHERE id = ?";
					$sql_params1 = array($value,$params['qualification_category'][$key], $data['modified_by'], $vacancyqualifications[$key]['id']);
					$this->db->query($sql1, $sql_params1);
				}else{
					$data = array(
						"vacancy_id" => $data['id'],
						"name" => $value,
						"category" => $params['qualification_category'][$key],
						"modified_by" => Helper::get('userid')
					);
					$this->db->insert('tblrecruitmentvacanciesqualifications', $data);
				}	
			}
			
			$this->db->query($sql, $sql_params);
			if ($this->db->trans_status() === TRUE) {
				$code = "0";
				$this->db->trans_commit();
				$message = "Successfully updated job opening.";
				$helperDao->auditTrails(Helper::get('userid'), $message);
				$this->ModelResponse($code, $message);
				return true;
			} else {
				$helperDao->auditTrails(Helper::get('userid'), $message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		// approve job opening
		public function approveJobOpening($params) {
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Failed to approve job opening";

			$status = $this->getJobOpeningStatus($params['id']);

			$data['id'] = isset($params['id'])?$params['id']:'';
			$data['status'] = $status ? $status : 1;

			$sql = "UPDATE tblrecruitmentvacancies SET is_approve = ? WHERE id = ?";
			$sql_params = array($data['status'], $data['id']);

			$this->db->query($sql, $sql_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully approved job opening.";

				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;
			} else {
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		// disapprove job opening
		public function disapprovedJobOpening($params) {
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Failed to disapproved job opening.";

			$data['id'] 	= isset($params['id'])?$params['id']:'';
			$data['status']	= 999;
			$sql = "	UPDATE tblrecruitmentvacancies SET is_approve = ? WHERE id = ? ";
			$sql_params = array($data['status'],$data['id']);

			$this->db->query($sql,$sql_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully disapproved job opening.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;
			} else {
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		// get job opening status
		public function getJobOpeningStatus($id) {
			$this->db->select('*');
			$this->db->from('tblrecruitmentvacancies');
			$this->db->where('id', $id);

			$query = $this->db->get();

			if($query->num_rows() > 0) {
				$ret = $query->row();
				return $ret->is_approve;
			}
			return false;
		}

		public function hasRowsActive(){
			$data = array();
			$code = "1";
			$message = "No data available.";
			// $sql = 'SELECT v.*, p.name, p.code FROM 
			// 		tblrecruitmentvacancies v, tblfieldpositions p WHERE 
			// 		v.position_id = p.id AND v.is_active = "1" AND v.is_approve = "1" AND p.is_active = "1"';
			$sql = 'SELECT a.*, b.name, b.code FROM tblrecruitmentvacancies a 
			LEFT JOIN tblfieldpositions b ON a.position_id = b.id WHERE a.is_active = "1" AND a.is_approve = "1" AND b.is_active ="1" GROUP BY b.code';
			$query = $this->db->query($sql);
			$vacancy_rows = $query->result_array();
			$data['details'] = $vacancy_rows;
			if(sizeof($vacancy_rows) > 0){
				$code = "0";
				$message = "Successfully fetched job openings.";
				$this->ModelResponse($code, $message, $data);
				return true;
			}
			else {
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		// activate job opening
		public function activateJobOpening($params) {
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Failed to activate job opening.";

			$data['id'] 	= isset($params['id'])?$params['id']:'';
			$data['status']	= 1;
			$data['modified_by'] = Helper::get('userid');

			$sql = "UPDATE tblrecruitmentvacancies SET is_active = ?, modified_by = ? WHERE id = ?";
			$sql_params = array($data['status'],$data['modified_by'],$data['id']);

			$this->db->query($sql,$sql_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully activate job opening.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;
			} else {
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		// deactivate job opening
		public function deactivateJobOpening($params) {
			$helperDao = new HelperDao();
			$code = "1";
			$message = "Failed to deactivate job opening.";

			$data['id'] 	= isset($params['id'])?$params['id']:'';
			$data['status']	= 0;
			$data['modified_by'] = Helper::get('userid');

			$sql = "UPDATE tblrecruitmentvacancies SET is_active = ?, modified_by = ? WHERE id = ?";
			$sql_params = array($data['status'],$data['modified_by'],$data['id']);

			$this->db->query($sql,$sql_params);
			if($this->db->affected_rows() > 0){
				$code = "0";
				$message = "Successfully deactivate job opening.";
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
				return true;
			} else {
				$helperDao->auditTrails(Helper::get('userid'),$message);
				$this->ModelResponse($code, $message);
			}
			return false;
		}

		function getSignatories(){
			$sign = $this->db->query('SELECT
			a.*,
			CONCAT(
				DECRYPTER(b.first_name,"sunev8clt1234567890",b.id)," ",
				LEFT(DECRYPTER(b.middle_name,"sunev8clt1234567890",b.id),1),". ",
				DECRYPTER(b.last_name,"sunev8clt1234567890",b.id)," ",			
				DECRYPTER(b.extension,"sunev8clt1234567890",b.id)) as employee_name, b.position_designation, b.division_designation, c.name as position_title, d.department_name as department
			FROM tblfieldsignatories a LEFT JOIN tblemployees b ON a.employee_id = b.id
			LEFT JOIN tblfieldpositions c ON b.position_id = c.id LEFT JOIN tblfielddepartments d ON b.division_id = d.id WHERE a.id IN (26) ORDER BY a.id')->result_array();
			return $sign;
		}

		// approved and active job openings list
		public function authorizedJobOpenings() {
			$data = array();
			$code = "1";
			$message = "No data available.";
			$sql = 'SELECT * FROM tblrecruitmentvacancies WHERE is_approve = 1 AND is_active = 1';
			$query = $this->db->query($sql);
			$job_openings = $query->result_array();

			if(sizeof($job_openings) > 0){
				foreach($job_openings as $key => $value) {
					$position_id = $value['position_id'];
					$vacancy_id = $key;

					$sql = 'SELECT * FROM tblfieldpositions WHERE id = ?';
					$sql_params = array($position_id);
					$query = $this->db->query($sql, $sql_params);
					$res = $query->result_array();
					$job_openings[$key]['position'] = $res;

					$salary_grade_id = $res[0]['salary_grade_id'];
					$salary_grade_step_id = $res[0]['salary_grade_step_id'];

					$sql = 'SELECT * FROM tblfieldsalarygradesteps WHERE grade_id = ? AND step = ?';
					$sql_params = array($salary_grade_id, $salary_grade_step_id);
					$query = $this->db->query($sql, $sql_params);
					$res = $query->result_array();
					$job_openings[$key]['salary'] = $res;

					unset($sql_params);
					$sql = 'SELECT * FROM tblrecruitmentvacanciesqualifications WHERE vacancy_id = ?';
					$sql_params = array($vacancy_id);
					$query = $this->db->query($sql, $sql_params);
					$res = $query->result_array();
					$job_openings[$key]['qualifications'] = $res;
				}

				$data['details'] = $job_openings;

				$code = "0";
				$message = "Successfully fetched job openings.";
				$this->ModelResponse($code, $message, $data);
				return true;
			}
			else {
				$this->ModelResponse($code, $message);
			}
			return false;
		}
	}
?>
