<?php
class IpcrApprovalCollection extends Helper {


    public function __construct()
    {
        $this->load->database();
        //$this->load->model("UserAccount");
    }

    public function get_ipcr(){

        $this->db->select('*');
        $this->db->from('ipcr_form');
        if($_SESSION['user_level_id'] != 1){
            $this->db->where('assesed_by_supervisor = "'.$_SESSION['user_id'].'" AND fill_up = 1 AND validate = 1 AND (approval = 0 OR approval = 1 OR approval = -1)');
        }
        else{
            $this->db->where('fill_up = 1 AND validate = 1 AND (approval = 0 OR approval = 1 OR approval = -1)');
        }
        $query = $this->db->get();
        $results = $query->result();
        foreach ($results as $key => $value) {
            $value->date_review = date('M-d-Y',strtotime($value->date_review));
            $value->date_approve = date('M-d-Y',strtotime($value->date_approve));
            $value->posted_date = date('M-d-Y',strtotime($value->posted_date));
        }
        
        if(empty($results)){
            return $results;
        }
        else{
            return $results;
        }

    }

    public function get_users(){

        $this->db->select('*');
        $this->db->from('users');
        $query = $this->db->get();
        
        if(empty($query->result())){
            return false;
        }
        else{
            return $query->result();
        }

    }

    public function view_ipcr($postdata){

        $this->db->select('
            *,
            c.name AS reviewed_by_name,
            d.name AS approved_by_name,
            e.name AS discussed_with_emp_name,
            f.name AS assesed_by_supervisor_name,
            g.name AS final_rating_by_head_of_office_name,
            a.name as form_name,
            h.name as ratee_name
        ');
        $this->db->from('ipcr_form a');
        $this->db->join('ipcr_answer b','a.form_id = b.form_id');
        $this->db->join('users c','a.reviewed_by = c.user_id');
        $this->db->join('users d','a.approved_by = d.user_id');
        $this->db->join('users e','a.discussed_with_emp = e.user_id');
        $this->db->join('users f','a.assesed_by_supervisor = f.user_id');
        $this->db->join('users g','a.final_rating_by_head_of_office = g.user_id');
        $this->db->join('users h','a.ratee = h.user_id','left');
        $this->db->where('a.form_id',$postdata['id']);
        $query = $this->db->get();
        // var_dump($query->result()); die();

        if(empty($query->result())){
            return false;
        }
        else{
            return $query->result();
        }
    }

    public function approve($postdata){
        // var_dump($postdata); die();
        $data = array(
            'approval' => $postdata['val'],
            'date_assesed' => date('Y-m-d H:i:s')
        );

        $this->db->where('form_id',$postdata['data'][0]['value']);
        $result = $this->db->update('ipcr_form',$data);

        if($result){
            $code = 0;
            $message = "Successfully Updated!";
            $this->ModelResponse($code, $message);
            return true;
        }
        else{
            $code = 1;
            $message = "Failed to update data!";
            $this->ModelResponse($code, $message);
            return false; 
        }
    }
}
//echo '<pre>' . var_export($_POST, true) . '</pre>'; die();
?>