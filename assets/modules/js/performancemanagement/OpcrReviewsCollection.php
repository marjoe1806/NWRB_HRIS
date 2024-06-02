<?php
class OpcrReviewsCollection extends Helper {


    public function __construct()
    {
        $this->load->database();
        $this->load->model('Helper');
        $this->load->model('HelperDao');
        //$this->load->model("UserAccount");
        
    }

  

    public function get_opcr($postdata){
        // var_dump($postdata); die();

        $this->db->select('
            CONCAT(
                DECRYPTER(a.first_name,"sunev8clt1234567890",a.id)," ",
                DECRYPTER(a.middle_name,"sunev8clt1234567890",a.id)," ",
                DECRYPTER(a.last_name,"sunev8clt1234567890",a.id)) AS empl_name,
            a.id,
            b.*,
        ');
        $this->db->from('tblemployees a');
        $this->db->join('tblopcrform b','a.id = b.name');
        if (@$postdata['employee_id'] != "") $this->db->where('a.id', $postdata['employee_id']);
        if (@$postdata['division_id'] != "") $this->db->where('a.division_id', $postdata['division_id']);
        $query = $this->db->get();
        $results = $query->result();



        // $query = $this->db->get();
        // $results = $query->result();
        foreach ($results as $key => $value) {
            $value->submitted_date = date('M-d-Y',strtotime($value->submitted_date));
            $value->officer_assessed_date = date('M-d-Y',strtotime($value->officer_assessed_date));
            $value->pmt_assessed_date = date('M-d-Y',strtotime($value->pmt_assessed_date));
            $value->validate_date = date('M-d-Y',strtotime($value->validate_date));
            $value->attested_date = date('M-d-Y',strtotime($value->attested_date));
            $value->final_rating_date = date('M-d-Y',strtotime($value->final_rating_date));
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
        $this->db->from('tblwebusers');
        $query = $this->db->get();
        
        if(empty($query->result())){
            return false;
        }
        else{
            return $query->result();
        }

    }

    public function view_opcr($postdata){
        // var_dump($postdata['id']); die();
        $this->db->select('
            a.*,
            b.*,
            CONCAT(
                DECRYPTER(aa.first_name,"sunev8clt1234567890",aa.id)," ",
                DECRYPTER(aa.middle_name,"sunev8clt1234567890",aa.id)," ",
                DECRYPTER(aa.last_name,"sunev8clt1234567890",aa.id)) AS full_name,
            a.name as submitted_name,
            CONCAT(
                DECRYPTER(c.first_name,"sunev8clt1234567890",c.id)," ",
                DECRYPTER(c.middle_name,"sunev8clt1234567890",c.id)," ",
                DECRYPTER(c.last_name,"sunev8clt1234567890",c.id)) as assessed_by_planning_officer,
            CONCAT(
                DECRYPTER(d.first_name,"sunev8clt1234567890",d.id)," ",
                DECRYPTER(d.middle_name,"sunev8clt1234567890",d.id)," ",
                DECRYPTER(d.last_name,"sunev8clt1234567890",d.id)) as validated_name,
            CONCAT(
                DECRYPTER(e.first_name,"sunev8clt1234567890",e.id)," ",
                DECRYPTER(e.middle_name,"sunev8clt1234567890",e.id)," ",
                DECRYPTER(e.last_name,"sunev8clt1234567890",e.id)) as attested_name,
            CONCAT(
                DECRYPTER(f.first_name,"sunev8clt1234567890",f.id)," ",
                DECRYPTER(f.middle_name,"sunev8clt1234567890",f.id)," ",
                DECRYPTER(f.last_name,"sunev8clt1234567890",f.id)) as final_rating_name,
            CONCAT(
                DECRYPTER(g.first_name,"sunev8clt1234567890",g.id)," ",
                DECRYPTER(g.middle_name,"sunev8clt1234567890",g.id)," ",
                DECRYPTER(g.last_name,"sunev8clt1234567890",g.id)) as assessed_by_pmt,
            DECRYPTER(h.first_name,"sunev8clt1234567890",h.id) as ratee_name,
            CONCAT(
                DECRYPTER(i.first_name,"sunev8clt1234567890",i.id)," ",
                DECRYPTER(i.middle_name,"sunev8clt1234567890",i.id)," ",
                DECRYPTER(i.last_name,"sunev8clt1234567890",i.id)) as approver_name,
           
        ');
        $this->db->from('tblopcrform a');
        $this->db->join('tblopcranswer b','a.form_id = b.form_id');
        $this->db->join('tblemployees aa','a.name = aa.id');
        $this->db->join('tblemployees c','a.assessed_by_planning_officer = c.id','left');
        $this->db->join('tblemployees d','a.validated_by = d.id','left');
        $this->db->join('tblemployees e','a.attested_by = e.id','left');
        $this->db->join('tblemployees f','a.final_rating_by = f.id','left');
        $this->db->join('tblemployees g','a.assessed_by_pmt = g.id','left');
        $this->db->join('tblemployees h','a.ratee = h.id', 'left');
        $this->db->join('tblemployees i','a.approved_by = h.id', 'left');
        $this->db->where('a.form_id',$postdata['id']);
        $query = $this->db->get();

        //  var_dump($query->result()); die();

        if(empty($query->result())){
            return false;
        }
        else{
            return $query->result();
        }
    }

    public function update($postdata){
        // var_dump($postdata); die();
        $data = array(
            // 'final_rating_date' => date('Y-m-d '),
            'assessed_reviewed' => 1,
            'strat_mfo' => $postdata['strat_mfo'],
            'core_mfo' => $postdata['core_mfo'],
            'support_mfo' => $postdata['support_mfo'],
            'overall_rating_mfo' => $postdata['overall_rating_mfo'],
            'initial_avg_rating' => 0,
            'avg_rating_mfo' => $postdata['avg_rating_mfo'],
            'adj_rating_mfo' => $postdata['adj_rating_mfo'],
            'strat_rating' => $postdata['strat_rating'],
            'core_rating' => $postdata['core_rating'],
            'support_rating' => $postdata['support_rating'],
            'overall_rating' => $postdata['overall_rating'],
            'avg_rating' => $postdata['avg_rating'],
            'adj_rating' => $postdata['adj_rating'],
            // 'final_rating_by' => $_SESSION['employee_id'],
            'final_rating' => 1
        );
        // var_dump($data); die();
        $this->db->where('form_id', $postdata['form_id']);
        $this->db->update('tblopcrform', $data);
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
            if(isset($postdata['id_answer'])){
                foreach ($postdata['id_answer'] as $key => $value) {
                    $form[$key]['id'] = $value;
                    $form[$key]['q1'] = isset($postdata['q1'][$key]) ? $postdata['q1'][$key] : '';
                    $form[$key]['e2'] = isset($postdata['e2'][$key]) ? $postdata['e2'][$key] : '';
                    $form[$key]['t3'] = isset($postdata['t3'][$key]) ? $postdata['t3'][$key] : '';
                    $form[$key]['a4'] = isset($postdata['a4'][$key]) ? $postdata['a4'][$key] : '';
                    $form[$key]['remarks'] = isset($postdata['remarks'][$key]) ? $postdata['remarks'][$key] : '';
                    }
             }
            
        foreach ($form as $key => $value) {
            $this->db->where('id', $value['id']);
            $this->db->update('tblopcranswer', $value);
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

?>