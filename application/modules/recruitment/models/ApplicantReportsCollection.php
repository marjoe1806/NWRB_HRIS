<?php
    class ApplicantReportsCollection extends Helper {
        
        public function __construct() {
            $this->load->model('HelperDao');
            ModelResponse::busy();
        }

        var $table = 'tblrecruitmentapplicants';

        function fetchApplicantReports() {
            $this->db->select('*, tblfieldpositions.name as position');
            $this->db->from($this->table);
			$this->db->join('tblrecruitmentvacancies', $this->table.'.vacancy_id = tblrecruitmentvacancies.id', 'left');
			$this->db->join('tblfieldpositions', 'tblrecruitmentvacancies.position_id = tblfieldpositions.id', 'left');
            $this->db->where($this->table.'.application_status', $_POST['status']);

            if(isset($_POST['vacancy_id']) && $_POST['vacancy_id']) {
				$this->db->where($this->table.'.vacancy_id', $_POST['vacancy_id']);
			}

            $result = $this->db->get();
            return $result->result_array();
        }
    }
