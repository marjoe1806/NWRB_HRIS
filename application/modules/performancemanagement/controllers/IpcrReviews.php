<?php

class IpcrReviews extends MX_Controller{
    public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('IpcrReviewsCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}

    public function index() {
        $data['content'] = $this->load->view('ipcrreviews.form.php','',TRUE);
        $data['menu'] = $this->load->view('templates/menu_template','',TRUE);
        $data['title'] = 'INDIVIDUAL PERFORMANCE COMMITMENT REVIEW LIST';
        $this->load->view('templates/master_template',$data);
    }

    public function get_ipcr(){
        //    var_dump("hit"); die();
        $ret = new IpcrReviewsCollection();

        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        else{
            $res = $ret->get_ipcr($_POST);
            $result = array('data'=>$res);
        }
        // var_dump($result); die();
        echo json_encode($result);

    }

    public function view_ipcr(){

        $ret = new IpcrReviewsCollection();

        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        else{
            $result['data'] = $ret->view_ipcr($_POST);
            //Reviewed BY
            $isHave1 = $this->db->select("*")->from('tblemployees')->where("id", $result['data'][0]->reviewed_by)->limit(1)->get()->row_array();
            if($isHave1){
               $reviewedBy = $this->db->select("*")->from('tblfieldpositions')->where("id", $isHave1['position_id'])->limit(1)->get()->row_array();
            }
            //Approved BY
            $isHave = $this->db->select("*")->from('tblemployees')->where("id", $result['data'][0]->approved_by)->limit(1)->get()->row_array();
            if($isHave){
               $approveBy = $this->db->select("*")->from('tblfieldpositions')->where("id", $isHave['position_id'])->limit(1)->get()->row_array();
            }

            //discussed_with_emp
            $isHave = $this->db->select("*")->from('tblemployees')->where("id", $result['data'][0]->discussed_with_emp)->limit(1)->get()->row_array();
            if($isHave){
                $discussed_with_emp = $this->db->select("*")->from('tblfieldpositions')->where("id", $isHave['position_id'])->limit(1)->get()->row_array();
            }

            //assesed_by_supervisor
            $isHave = $this->db->select("*")->from('tblemployees')->where("id", $result['data'][0]->assesed_by_supervisor)->limit(1)->get()->row_array();
            if($isHave){
                $assesed_by_supervisor = $this->db->select("*")->from('tblfieldpositions')->where("id", $isHave['position_id'])->limit(1)->get()->row_array();
            }

            //final_rating_by_head_of_office
            $isHave = $this->db->select("*")->from('tblemployees')->where("id", $result['data'][0]->final_rating_by_head_of_office)->limit(1)->get()->row_array();
            if($isHave){
                $final_rating_by_head_of_office = $this->db->select("*")->from('tblfieldpositions')->where("id", $isHave['position_id'])->limit(1)->get()->row_array();
            }

            
            $result['OfficeHead'] =  $final_rating_by_head_of_office;
            $result['supervisor'] =  $assesed_by_supervisor;
            $result['employee'] =  $discussed_with_emp;
            $result['reviewedBy'] =  $reviewedBy;
            $result['approvedBy'] =  $approveBy;
            $result['form'] = $this->load->view('form/ipcrview.form.php',$result,TRUE);
        }
        echo json_encode($result);
    }

    public function print_ipcr(){

        $ret = new IpcrReviewsCollection();

        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        else{
            $result['data'] = $ret->view_ipcr($_POST);

            $isHave1 = $this->db->select("*")->from('tblemployees')->where("id", $result['data'][0]->reviewed_by)->limit(1)->get()->row_array();
            if($isHave1){
               $reviewedBy = $this->db->select("*")->from('tblfieldpositions')->where("id", $isHave1['position_id'])->limit(1)->get()->row_array();
            }

            $isHave = $this->db->select("*")->from('tblemployees')->where("id", $result['data'][0]->approved_by)->limit(1)->get()->row_array();
            if($isHave){
               $approveBy = $this->db->select("*")->from('tblfieldpositions')->where("id", $isHave['position_id'])->limit(1)->get()->row_array();
            }

            $isHave = $this->db->select("*")->from('tblemployees')->where("id", $result['data'][0]->discussed_with_emp)->limit(1)->get()->row_array();
            if($isHave){
                $discussed_with_emp = $this->db->select("*")->from('tblfieldpositions')->where("id", $isHave['position_id'])->limit(1)->get()->row_array();
            }

            $isHave = $this->db->select("*")->from('tblemployees')->where("id", $result['data'][0]->assesed_by_supervisor)->limit(1)->get()->row_array();
            if($isHave){
                $assesed_by_supervisor = $this->db->select("*")->from('tblfieldpositions')->where("id", $isHave['position_id'])->limit(1)->get()->row_array();
            }

            $isHave = $this->db->select("*")->from('tblemployees')->where("id", $result['data'][0]->final_rating_by_head_of_office)->limit(1)->get()->row_array();
            if($isHave){
                $final_rating_by_head_of_office = $this->db->select("*")->from('tblfieldpositions')->where("id", $isHave['position_id'])->limit(1)->get()->row_array();
            }

            
            $result['OfficeHead'] =  $final_rating_by_head_of_office;
            $result['supervisor'] =  $assesed_by_supervisor;
            $result['employee'] =  $discussed_with_emp;
            $result['reviewedBy'] =  $reviewedBy;
            $result['approvedBy'] =  $approveBy;
            $result['form'] = $this->load->view('form/ipcrprint.form.php',$result,TRUE);
        }
        echo json_encode($result);
    }

    public function update(){
        if (!$this->input->is_ajax_request()) {
           show_404();
        }
        else{
            $ret =  new IpcrReviewsCollection();
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
        $ret = new IpcrReviewsCollection();

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