<?php

class Dpcr extends MX_Controller{

    // public function __construct() {
    //     parent::__construct();
    //     $this->load->model('Helper'); 
    //     $this->load->model('DpcrCollection');
    //     $this->load->library('session');
    //     Helper::checksession();
    //     Helper::checkmodule(100);
    // }

    public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('DpcrCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
    public function index() {
        $ret =  new DpcrCollection();
        // $result['immediate_supervisor'] = $ret->get_immediate_supervisor();
        // $result['get_head_of_office'] = $ret->get_head_of_office();
        // $result['get_supervisor'] = $ret->get_supervisor();
        // $result['get_all_users'] = $ret->get_all_users();
        $result['get_employee'] = $ret->get_employee();
        $data['content'] = $this->load->view('dpcr.form.php',$result,TRUE);
        $data['menu'] = $this->load->view('templates/menu_template','',TRUE);
        $data['title'] = 'INDIVIDUAL PERFORMANCE COMMITMENT REVIEW';
        $this->load->view('templates/master_template',$data);
        Session::checksession();
    }

    public function add_form(){
        if (!$this->input->is_ajax_request()) {
           show_404();
        }
        else{
            $ret =  new DpcrCollection();
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