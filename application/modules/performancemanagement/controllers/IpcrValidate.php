<?php

class IpcrValidate extends MX_Controller{
    public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('IpcrValidateCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}

    public function index() {
        $data['content'] = $this->load->view('ipcrvalidate.form.php','',TRUE);
        $data['menu'] = $this->load->view('templates/menu_template','',TRUE);
        $data['title'] = 'INDIVIDUAL PERFORMANCE COMMITMENT VALIDATE';
        $this->load->view('templates/master_template',$data);
    }

    public function get_ipcr(){

        $ret = new IpcrValidateCollection();

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

        $ret = new IpcrValidateCollection();

        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        else{
            // $result['users'] = $ret->get_users();
            $result['data'] = $ret->view_ipcr($_POST);
            $result['form'] = $this->load->view('form/ipcrvalidate.form.php',$result,TRUE);
        }
        echo json_encode($result);

    }

    public function validate(){
        if (!$this->input->is_ajax_request()) {
           show_404();
        }
        else{
            $ret =  new IpcrValidateCollection();
            if($ret->validate($_POST)) {
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