<?php
class IpcrValidateCollection extends Helper {


    public function __construct()
    {
        $this->load->database();
        //$this->load->model("UserAccount");
    }

    public function get_ipcr(){

        // $this->db->select('*');
        // $this->db->from('tblipcrform');
        //     $this->db->where('(validate = 0 OR validate = 1 OR validate = -1) AND fill_up = 1');
        //     $this->db->order_by('form_id', 'desc');
            
            $this->db->select('
            CONCAT(
                DECRYPTER(a.last_name,"sunev8clt1234567890",a.id),", ",
                DECRYPTER(a.first_name,"sunev8clt1234567890",a.id)," ",
                DECRYPTER(a.middle_name,"sunev8clt1234567890",a.id)) AS empl_name,
            a.id,
            b.*,
        ');
        $this->db->from('tblemployees a');
        $this->db->join('tblipcrform b','a.id = b.name', 'left');
        $this->db->where('(b.validate = 0 OR b.validate = 1 OR b.validate = -1) AND b.fill_up = 1');
        $this->db->order_by('b.form_id', 'desc');
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
            CONCAT(
                DECRYPTER(cc.last_name,"sunev8clt1234567890",cc.id),", ",
                DECRYPTER(cc.first_name,"sunev8clt1234567890",cc.id)," ",
                DECRYPTER(cc.middle_name,"sunev8clt1234567890",cc.id)) AS full_name,
            c.username AS reviewed_by_name,
            d.username AS approved_by_name,
            e.username AS discussed_with_emp_name,
            f.username AS assesed_by_supervisor_name,
            g.username AS final_rating_by_head_of_office_name,
            a.name as form_name,
            h.username as ratee_name
        ');
        $this->db->from('tblipcrform a');
        $this->db->join('tblipcranswer b','a.form_id = b.form_id');
        $this->db->join('tblemployees cc','a.name = cc.id');
        $this->db->join('tblwebusers c','a.reviewed_by = c.userid');
        $this->db->join('tblwebusers d','a.approved_by = d.userid');
        $this->db->join('tblwebusers e','a.discussed_with_emp = e.userid');
        $this->db->join('tblwebusers f','a.assesed_by_supervisor = f.userid');
        $this->db->join('tblwebusers g','a.final_rating_by_head_of_office = g.userid');
        $this->db->join('tblwebusers h','a.ratee = h.userid','left');
        $this->db->where('a.form_id',$postdata['id']);
        $query = $this->db->get();

        if(empty($query->result())){
            return false;
        }
        else{
            return $query->result();
        }
    }

    public function validate($postdata){
        $data = array(
            'validate' => $postdata['val'],
            'date_discussed' => ($postdata['val'] == 1 ? date('Y-m-d H:i:s') : "") 
        );

        $this->db->where('form_id',$postdata['data'][0]['value']);
        $result = $this->db->update('tblipcrform',$data);

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