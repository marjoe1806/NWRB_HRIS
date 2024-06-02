<?php

class DpcrReviews extends MX_Controller{
    public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('DpcrReviewsCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}

    public function index() {
        $data['content'] = $this->load->view('dpcrreviews.form.php','',TRUE);
        $data['menu'] = $this->load->view('templates/menu_template','',TRUE);
        $data['title'] = 'INDIVIDUAL PERFORMANCE COMMITMENT REVIEW LIST';
        $this->load->view('templates/master_template',$data);
    }

    public function get_dpcr(){
        //    var_dump("hit"); die();
        $ret = new DpcrReviewsCollection();

        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        else{
            $res = $ret->get_dpcr($_POST);
            $result = array('data'=>$res);
        }
        // var_dump($result); die();
        echo json_encode($result);

    }

    public function view_dpcr(){

        $ret = new DpcrReviewsCollection();

        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        else{
            $result['data'] = $ret->view_dpcr($_POST);
            $result['form'] = $this->load->view('form/dpcrview.form.php',$result,TRUE);
        }
        echo json_encode($result);

    }

    public function print_dpcr(){

        $ret = new DpcrReviewsCollection();

        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        else{
            $result['data'] = $ret->view_dpcr($_POST);
            $result['form'] = $this->load->view('form/dpcrprint.form.php',$result,TRUE);
        }
        echo json_encode($result);

    }

    public function update(){
        if (!$this->input->is_ajax_request()) {
           show_404();
        }
        else{
            $ret =  new DpcrReviewsCollection();
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
        $ret = new DpcrReviewsCollection();

        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        else{
            // $result['users'] = $ret->get_users();
            $result['print'] = $this->load->view('form/dpcr_print.form.php','',TRUE);
        }
        echo json_encode($result);
    }
}
?>