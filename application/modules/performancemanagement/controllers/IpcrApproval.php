<?php

class IpcrApproval extends MX_Controller{
    public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('IpcrApprovalCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}

    public function index() {
        $data['content'] = $this->load->view('ipcrapproval.form.php','',TRUE);
        $data['menu'] = $this->load->view('templates/menu_template','',TRUE);
        $data['title'] = 'INDIVIDUAL PERFORMANCE COMMITMENT APPROVAL';
        $this->load->view('templates/master_template',$data);
    }

    public function get_ipcr(){

        $ret = new IpcrApprovalCollection();

        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        else{
            $res = $ret->get_ipcr();
            $result = array('data'=>$res);
        }
        echo json_encode($result);

    }

    public function view_ipcr(){

        $ret = new IpcrApprovalCollection();

        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        else{
            $result['users'] = $ret->get_users();
            $result['data'] = $ret->view_ipcr($_POST);
            $result['form'] = $this->load->view('form/ipcrapproval.form.php',$result,TRUE);
        }
        echo json_encode($result);

    }

    public function approve(){
        if (!$this->input->is_ajax_request()) {
           show_404();
        }
        else{
            $ret =  new IpcrApprovalCollection();
            if($ret->approve($_POST)) {
                $res = new ModelResponse($ret->getCode(), $ret->getMessage());
            } else {
                $res = new ModelResponse($ret->getCode(), $ret->getMessage());
            }
            $result = json_decode($res,true);
        }
        echo json_encode($result);
    }
}
?>