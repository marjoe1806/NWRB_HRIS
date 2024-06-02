<?php

class Opcr extends MX_Controller{

    public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('OpcrCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}

    public function index() {
        $ret =  new OpcrCollection();
        $result['get_employee'] = $ret->get_employee();
        $data['content'] = $this->load->view('opcr.form.php', $result, TRUE);
        $data['menu'] = $this->load->view('templates/menu_template','',TRUE);
        $data['title'] = 'OFFICE PERFORMANCE COMMITMENT REVIEW (OPCR)';
        $this->load->view('templates/master_template',$data);
        Session::checksession();
    }

    public function add_form(){
        if (!$this->input->is_ajax_request()) {
           show_404();
        }
        else{
            $ret =  new OpcrCollection();
            if($ret->add_form($_POST)) {
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