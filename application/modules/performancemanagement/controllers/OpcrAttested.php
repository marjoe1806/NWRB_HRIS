<?php

class OpcrAttested extends MX_Controller{
    public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('OpcrAttestedCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}

    public function index() {
        $data['content'] = $this->load->view('opcrattested.form.php','',TRUE);
        $data['menu'] = $this->load->view('templates/menu_template','',TRUE);
        $data['title'] = 'OFFICE PERFORMANCE COMMITMENT REVIEW ATTESTED';
        $this->load->view('templates/master_template',$data);
    }

    public function get_opcr(){

        $ret = new OpcrAttestedCollection();

        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        else{
            $res = $ret->get_opcr();
            $result = array('data'=>$res);
        }
        echo json_encode($result);

    }

    public function view_opcr(){

        $ret = new OpcrAttestedCollection();

        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        else{
            // $result['users'] = $ret->get_users();
            $result['data'] = $ret->view_opcr($_POST);
            $result['form'] = $this->load->view('form/opcrattest.form.php',$result,TRUE);
        }
        echo json_encode($result);

    }

    public function approve(){
        if (!$this->input->is_ajax_request()) {
           show_404();
        }
        else{
            $ret =  new OpcrAttestedCollection();
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