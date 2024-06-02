<?php
class OpcrValidateCollection extends Helper {
    public function __construct()
    {
        $this->load->database();
        //$this->load->model("UserAccount");
    }
    public function get_opcr(){

        // $this->db->select('*');
        // $this->db->from('tblopcrform');
        // $this->db->where('(validation = 0 OR validation = 1 OR validation = -1) AND assessed_reviewed = 1');
        // $this->db->order_by('form_id', 'desc');
        // $query = $this->db->get();
        // $results = $query->result();

        $this->db->select('
            CONCAT(
                DECRYPTER(a.last_name,"sunev8clt1234567890",a.id),", ",
                DECRYPTER(a.first_name,"sunev8clt1234567890",a.id)," ",
                DECRYPTER(a.middle_name,"sunev8clt1234567890",a.id)) AS empl_name,
            a.id,
            b.*,
        ');
        $this->db->from('tblemployees a');
        $this->db->join('tblopcrform b','a.id = b.name', 'left');
        $this->db->where('(b.validation = 0 OR b.validation = 1 OR b.validation = -1) AND b.assessed_reviewed = 1');
        $this->db->order_by('form_id', 'desc');
        $query = $this->db->get();
        $results = $query->result();
        foreach ($results as $key => $value) {
            $value->validate_date = date('M-d-Y',strtotime($value->validate_date));
            $value->submitted_date = date('M-d-Y',strtotime($value->submitted_date));
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
            a.*,
            b.*,
            CONCAT(
            DECRYPTER(cc.last_name,"sunev8clt1234567890",cc.id),", ",
            DECRYPTER(cc.first_name,"sunev8clt1234567890",cc.id)," ",
            DECRYPTER(cc.middle_name,"sunev8clt1234567890",cc.id)) AS full_name,
            a.name as submitted_name,
            c.username as assessed_reviewed_name,
            d.username as validated_name,
            e.username as attested_name,
            f.username as final_rating_name
        ');
        $this->db->from('tblopcrform a');
        $this->db->join('tblopcranswer b','a.form_id = b.form_id');
        $this->db->join('tblemployees cc','a.name = cc.id');
        $this->db->join('tblwebusers c','a.assessed_reviewed_by = c.userid','left');
        $this->db->join('tblwebusers d','a.validated_by = d.userid','left');
        $this->db->join('tblwebusers e','a.attested_by = e.userid','left');
        $this->db->join('tblwebusers f','a.final_rating_by = f.userid','left');
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
            'validation' => $postdata['val'],
            'validate_date' => ($postdata['val'] == 1 ? date('Y-m-d H:i:s') : null),
            'validated_by' => $_SESSION['employee_id']
        );

        $this->db->where('form_id',$postdata['data'][0]['value']);
        $result = $this->db->update('tblopcrform',$data);

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
?>