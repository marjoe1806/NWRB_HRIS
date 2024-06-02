<?php

class ApplicantReports extends MX_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('ApplicantReportsCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}

    public function index() {
        Helper::rolehook(ModuleRels::APPLICANT_REPORTS_SUB_MENU);
        $listData = array();
        $viewData = array();
        $page = "viewApplicantReports";
        $listData['key'] = $page;
        $viewData['table'] = $this->load->view("helpers/applicantreportslist", $listData, TRUE);
        if (!$this->input->is_ajax_request()) {
            Helper::setTitle('Applicant Report');
            Helper::setMenu('templates/menu_template');
            Helper::setView('applicantreports', $viewData, FALSE);
            Helper::setTemplate('templates/master_template');
        }else{
            $result['key'] = $listData;
            $result['table'] = $viewData['table'];
            echo json_encode($result);
        }
        Session::checksession();
    }

    public function viewApplicantReportsSummary() {
        $formData = array();
        $result = array();
        $result['key'] = 'viewApplicantReportsSummary';
        if (!$this->input->is_ajax_request()) {
            show_404();
        }else{
            $ret = new ApplicantReportsCollection;

            // set $formData
            $formData['applicants'] = $ret->fetchApplicantReports();

            if(sizeof($formData['applicants']) > 0) {
                $result['form'] = $this->load->view('helpers/applicantreportslist.php', $formData, TRUE);
            }else{
                $result['form'] = "<h2 class='text-danger'>No data available for generation of reports.</h2>";
            }
        }
        echo json_encode($result);
    }
}
