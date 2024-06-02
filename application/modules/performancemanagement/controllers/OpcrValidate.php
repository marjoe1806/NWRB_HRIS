<?php

class OpcrValidate extends MX_Controller{

    // public function __construct() {
    //     parent::__construct();
    //     $this->load->model('Helper'); 
    //     $this->load->model('OpcrValidateCollection');
    //     $this->load->library('session');
    // }
    public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('OpcrValidateCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
    public function index() {
        $data['content'] = $this->load->view('opcrvalidate.form.php','',TRUE);
        $data['menu'] = $this->load->view('templates/menu_template','',TRUE);
        $data['title'] = 'OFFICE PERFORMANCE COMMITMENT REVIEW VALIDATE';
        $this->load->view('templates/master_template',$data);
    }
    public function get_opcr(){
        $ret = new OpcrValidateCollection();
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        else{
            $res = $ret->get_opcr();
            $result = array('data'=>$res);
        }
        echo json_encode($result);
    }
    public function view_ipcr(){
        $ret = new OpcrValidateCollection();
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        else{
            $result['data'] = $ret->view_ipcr($_POST);
            $result['form'] = $this->load->view('form/opcrvalidate.form.php',$result,TRUE);
        }
        echo json_encode($result);

    }

    public function validate(){
        if (!$this->input->is_ajax_request()) {
           show_404();
        }
        else{
            $ret =  new OpcrValidateCollection();
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