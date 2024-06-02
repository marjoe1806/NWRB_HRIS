<?php
class DpcrReviewsCollection extends Helper {


    public function __construct()
    {
        $this->load->database();
        $this->load->model('HelperDao');
    }

    public function get_dpcr($postdata){
        $this->db->select('
            CONCAT(
                DECRYPTER(a.first_name,"sunev8clt1234567890",a.id)," ",
                DECRYPTER(a.middle_name,"sunev8clt1234567890",a.id)," ",
                DECRYPTER(a.last_name,"sunev8clt1234567890",a.id)) AS empl_name,
            a.id,
            b.*,
        ');
        $this->db->from('tblemployees a');
        $this->db->join('tbldpcrform b','a.id = b.name');
        if (@$postdata['employee_id'] != "") $this->db->where('a.id', $postdata['employee_id']);
        if (@$postdata['division_id'] != "") $this->db->where('a.division_id', $postdata['division_id']);
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
    public function get_dpcr_applicants(){
        $this->db->select('name');
        $this->db->from('tbldpcrform');
        $this->db->group_by('name');
        $query = $this->db->get();

        if(empty($query->result())){
            return false;
        }
        else{
            return $query->result();
        }
    }
    public function hasRowsActive(){
        $helperDao = new HelperDao();
        $data = array();
        $code = "1";
        $message = "No data available.";
        $sql = " SELECT * FROM tblfielddepartments where is_active = '1' ORDER BY department_name ASC";
        $query = $this->db->query($sql);
        $userlevel_rows = $query->result_array();
        $data['details'] = $userlevel_rows;
        if(sizeof($userlevel_rows) > 0){
            $code = "0";
            $message = "Successfully fetched departments.";
            $this->ModelResponse($code, $message, $data);	
            return true;		
        }	
        else {
            $this->ModelResponse($code, $message);
        }
        return false;
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

    public function view_dpcr($postdata){
        // var_dump($postdata); die();

        $this->db->select('
            a.*,
            b.*,
            CONCAT(
                DECRYPTER(cc.first_name,"sunev8clt1234567890",cc.id)," ",
                DECRYPTER(cc.middle_name,"sunev8clt1234567890",cc.id)," ",
                DECRYPTER(cc.last_name,"sunev8clt1234567890",cc.id)) AS full_name,
            CONCAT(
                DECRYPTER(c.first_name,"sunev8clt1234567890",c.id)," ",
                DECRYPTER(c.middle_name,"sunev8clt1234567890",c.id)," ",
                DECRYPTER(c.last_name,"sunev8clt1234567890",c.id)) AS reviewed_by_name,
            CONCAT(
                DECRYPTER(d.first_name,"sunev8clt1234567890",d.id)," ",
                DECRYPTER(d.middle_name,"sunev8clt1234567890",d.id)," ",
                DECRYPTER(d.last_name,"sunev8clt1234567890",d.id)) AS approved_by_name,
            CONCAT(
                DECRYPTER(e.first_name,"sunev8clt1234567890",e.id)," ",
                DECRYPTER(e.middle_name,"sunev8clt1234567890",e.id)," ",
                DECRYPTER(e.last_name,"sunev8clt1234567890",e.id)) AS discussed_with_emp_name,
            CONCAT(
                DECRYPTER(f.first_name,"sunev8clt1234567890",f.id)," ",
                DECRYPTER(f.middle_name,"sunev8clt1234567890",f.id)," ",
                DECRYPTER(f.last_name,"sunev8clt1234567890",f.id)) AS assesed_by_supervisor_name, 
            IF(g.id, CONCAT(
                DECRYPTER(g.first_name,"sunev8clt1234567890",g.id)," ",
                DECRYPTER(g.middle_name,"sunev8clt1234567890",g.id)," ",
                DECRYPTER(g.last_name,"sunev8clt1234567890",g.id)), 
                "PENDING") AS final_rating_by_head_of_office_name,
            a.name as form_name,
            DECRYPTER(h.first_name,"sunev8clt1234567890",h.id) as ratee_name
        ');
        $this->db->from('tbldpcrform a');
        $this->db->join('tbldpcranswer b','a.form_id = b.form_id');
        $this->db->join('tblemployees cc','a.name = cc.id');
        $this->db->join('tblemployees c','a.reviewed_by = c.id');
        $this->db->join('tblemployees d','a.approved_by = d.id');
        $this->db->join('tblemployees e','a.discussed_with_emp = e.id');
        $this->db->join('tblemployees f','a.assesed_by_supervisor = f.id');
        $this->db->join('tblemployees g','a.final_rating_by_head_of_office = g.id', 'left');
        $this->db->join('tblemployees h','a.ratee = h.id', 'left');
        $this->db->where('a.form_id',$postdata['id']);
        $query = $this->db->get();
        $result = $query->result();
        if(empty($result)){
            // var_dump("error"); die();
            return false;
        }
        else{
            // var_dump("success"); die();
            return $result;
        }
    }

    public function update($postdata){
        $this->db->trans_start();
        $data = array(
            'date_review' => date('Y-m-d '),
            'comments' => isset($postdata['comments']) ? $postdata['comments'] : '',
            'final_average_rating' => isset($postdata['final_average_rating']) ? $postdata['final_average_rating'] : 0,
            // 'final_rating_by_head_of_office' => $_SESSION['employee_id'],
            // 'date_final_rating' => date('Y-m-d H:i:s'),
            'answered' => 1,
            'higher_approval' => 1

        );
        // var_dump($data); die();
        $this->db->where('form_id', $postdata['form_id']);
        $this->db->update('tbldpcrform', $data);
        if($this->db->trans_status() === false){
            $this->db->trans_rollback();
            $code = 1;
            $message = "Failed to insert data!";
            $this->ModelResponse($code, $message);
            return false; 
        }
        else{
            $this->db->trans_commit();
            $this->db->trans_start();
            $form = array();
            if(isset($postdata['id'])){
                foreach ($postdata['id'] as $key => $value) {
                    $form[$key]['id'] = $value;
                    $form[$key]['q1'] = isset($postdata['q1'][$key]) ? $postdata['q1'][$key] : '';
                    $form[$key]['e2'] = isset($postdata['e2'][$key]) ? $postdata['e2'][$key] : '';
                    $form[$key]['t3'] = isset($postdata['t3'][$key]) ? $postdata['t3'][$key] : '';
                    $form[$key]['a4'] = isset($postdata['a4'][$key]) ? $postdata['a4'][$key] : '';
                    $form[$key]['remarks'] = isset($postdata['remarks'][$key]) ? $postdata['remarks'][$key] : '';

                    // foreach ($postdata['rating'][$key] as $key2 => $value2) {
                    //     $form[$key][$value2] = 1;
                    }
                }
      
            foreach ($form as $key => $value) {
               $this->db->where('id', $value['id']);
               $this->db->update('tbldpcranswer', $value);
               if($this->db->trans_status() === false)
                    $this->db->trans_rollback();
               else
                    $this->db->trans_commit();
            }
            $this->db->trans_complete();
            $code = 0;
            $message = "Successfully Updated!";
            $this->ModelResponse($code, $message);
            return true;
        }
        $this->db->trans_complete();
    }
}
//echo '<pre>' . var_export($_POST, true) . '</pre>'; die();
?>