<?php

class OpcrReviews extends MX_Controller{

    public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('OpcrReviewsCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}

    public function index() {
        $data['content'] = $this->load->view('opcrreviews.form.php','',TRUE);
        $data['menu'] = $this->load->view('templates/menu_template','',TRUE);
        $data['title'] = 'OFFICE PERFORMANCE COMMITMENT REVIEW LIST';
        $this->load->view('templates/master_template',$data);
    }

    public function get_opcr(){

        $ret = new OpcrReviewsCollection();

        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        else{
            $res = $ret->get_opcr($_POST);
            $result = array('data'=>$res);
        }
        echo json_encode($result);

    }

    public function view_opcr(){

        $ret = new OpcrReviewsCollection();

        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        else{
            $result['data'] = $ret->view_opcr($_POST);
            $result['form'] = $this->load->view('form/opcrview.form.php',$result,TRUE);
        }
        echo json_encode($result);

    }

    public function print_opcr(){

        $ret = new OpcrReviewsCollection();

        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        else{
            $result['data'] = $ret->view_opcr($_POST);
            $result['form'] = $this->load->view('form/opcrprint.form.php',$result,TRUE);
        }
        echo json_encode($result);

    }

    public function update(){
        if (!$this->input->is_ajax_request()) {
           show_404();
        }
        else{
            $ret =  new OpcrReviewsCollection();
            if($ret->update($_POST)) {
                $res = new ModelResponse($ret->getCode(), $ret->getMessage());
            } else {
                $res = new ModelResponse($ret->getCode(), $ret->getMessage());
            }
            $result = json_decode($res,true);
        }
        echo json_encode($result);
    }

    public function print_preview(){
        $ret = new OpcrReviewsCollection();

        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        else{
            // $result['users'] = $ret->get_users();
            $result['print'] = $this->load->view('form/ipcr_print.form.php','',TRUE);
        }
        echo json_encode($result);
    }
}

?>