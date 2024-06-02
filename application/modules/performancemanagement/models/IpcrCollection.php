<?php
class IpcrCollection extends Helper {


    public function __construct()
    {
        $this->load->database();
    }

    public function add_form($postdata){
        $this->db->trans_start();
        $data = array(
            'name' => $postdata['employee_id'],
            'position' => strtoupper($postdata['position']),
            'period_of' => strtoupper($postdata['period_of']),
            'posted_date' => date('Y-m-d H:i:s'),
            'fill_up' => 1,
            'reviewed_by' => $postdata['reviewed_by'],
            'date_review' =>  date('Y-m-d H:i:s',strtotime($postdata['date_review'])),
            'approved_by' => $postdata['approved_by'],
            'date_approve' =>   date('Y-m-d H:i:s',strtotime($postdata['date_approve'])),
            'discussed_with_emp' => $postdata['discussed_with_emp'],
            'date_discussed' => date('Y-m-d H:i:s',strtotime($postdata['date_discussed'])),
            'assesed_by_supervisor' => $postdata['assesed_by_supervisor'],
            'date_assesed' => date('Y-m-d H:i:s',strtotime($postdata['date_assesed'])),
            'final_rating_by_head_of_office' => $postdata['final_rating_by_head_of_office'],
            'date_final_rating' => $postdata['date_final_rating'],
            'ratee' => $_SESSION['employee_id']
        );


        $this->db->insert('tblipcrform', $data);
        $insert_id = $this->db->insert_id();

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
            // if(isset($postdata['output'])){
            //     foreach ($postdata['output'] as $key => $value) {
            //         $form[$key]['output'] = $value;
            //         $form[$key]['form_id'] = $insert_id;
            //     }
            // }
            // if(isset($postdata['success_ind'])){
            //     foreach ($postdata['success_ind'] as $key => $value) {
            //         $form[$key]['success_target'] = $value;
            //     }
            // }
            // if(isset($postdata['actual_accom'])){
            //     foreach ($postdata['actual_accom'] as $key => $value) {
            //         $form[$key]['actual_accomplishments'] = $value;
            //     }
            // }
            // foreach ($form as $key => $value) {
            //     $this->db->insert('tblipcranswer', $value);
            //     if($this->db->trans_status() === false)
            //         $this->db->trans_rollback();
            //     else
            //         $this->db->trans_commit();
            // }
            $form = array();
            if(isset($postdata['strat_output'])){
                foreach ($postdata['strat_output'] as $key => $value) {
                    $form['strat'][$key]['weight_of_output'] = 'Strategic';
                    $form['strat'][$key]['output'] = $value;
                    $form['strat'][$key]['form_id'] = $insert_id;
                    $form['strat'][$key]['success_target'] = isset($postdata['strat_success_ind'][$key])? $postdata['strat_success_ind'][$key] : '';
                    $form['strat'][$key]['actual_accomplishments'] = isset($postdata['strat_actual_accom'][$key]) ? $postdata['strat_actual_accom'][$key]:  '';

                }
            }
            if(isset($postdata['core_output'])){
                foreach ($postdata['core_output'] as $key => $value) {
                    $form['core'][$key]['weight_of_output'] = 'Core';
                    $form['core'][$key]['output'] = $value;
                    $form['core'][$key]['form_id'] = $insert_id;
                    $form['core'][$key]['success_target'] = isset($postdata['core_success_ind'][$key]) ? $postdata['core_success_ind'][$key] : '';
                    $form['core'][$key]['actual_accomplishments'] = isset($postdata['core_actual_accom'][$key]) ? $postdata['core_success_ind'][$key] : '';

                }
            }
            if(isset($postdata['support_output'])){
                foreach ($postdata['support_output'] as $key => $value) {
                    $form['support'][$key]['weight_of_output'] = 'Support';
                    $form['support'][$key]['output'] = $value;
                    $form['support'][$key]['form_id'] = $insert_id;
                    $form['support'][$key]['success_target'] = isset($postdata['support_success_ind'][$key]) ? $postdata['support_success_ind'][$key] : '';
                    $form['support'][$key]['actual_accomplishments'] = isset($postdata['support_actual_accom'][$key]) ? $postdata['support_success_ind'][$key] : '';

                }
            }

            $form['Final-rating'][] = array(
                            'weight_of_output' => "Final-Rating",
                            'output' => "",
                            'form_id' => $insert_id,
                            'success_target' => "",
                            'actual_accomplishments' => "",
                            );
            foreach ($form as $key => $value) {
                foreach ($value as $key2 => $value2) {
                    $this->db->insert('tblipcranswer', $value2);
                    if($this->db->trans_status() === false)
                         $this->db->trans_rollback();
                    else
                         $this->db->trans_commit();
                }
             }
            $this->db->trans_complete();
            $code = 0;
            $message = "Successfully Inserted!";
            $this->ModelResponse($code, $message);
            return true;
        }

        $this->db->trans_complete();
    }

    // public function get_immediate_supervisor(){
    //     $this->db->select('*');
    //     $this->db->from('tblwebusers');
    //     // $this->db->where('userlevelid',25);
    //     $query = $this->db->get();

    //     if(empty($query->result())){
    //         return false;
    //     }
    //     else{
    //         return $query->result();
    //     }
    // }

    // public function get_head_of_office(){
    //     $this->db->select('*');
    //     $this->db->from('tblwebusers');
    //     // $this->db->where('userlevelid',30);
    //     $query = $this->db->get();

    //     if(empty($query->result())){
    //         return false;
    //     }
    //     else{
    //         return $query->result();
    //     }
    // }

    public function get_employee(){
        $this->db->select('
            DECRYPTER(a.first_name,"sunev8clt1234567890",a.id) as f_name,
            DECRYPTER(a.middle_name,"sunev8clt1234567890",a.id) as m_name,
            DECRYPTER(a.last_name,"sunev8clt1234567890",a.id) as l_name,
            DECRYPTER(a.employee_id_number,"sunev8clt1234567890",a.id) as emp_id,
            a.*,
        ');
        $this->db->from('tblemployees a');
        $this->db->where('division_id = 7');
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

    // public function get_supervisor(){
    //     $this->db->select('*');
    //     $this->db->from('tblwebusers');
    //     // $this->db->where('userlevelid',1);
    //     $query = $this->db->get();

    //     if(empty($query->result())){
    //         return false;
    //     }
    //     else{
    //         return $query->result();
    //     }
    // }

    // public function get_all_users(){
    //     $this->db->select('*');
    //     $this->db->from('tblwebusers');
    //     $query = $this->db->get();

    //     if(empty($query->result())){
    //         return false;
    //     }
    //     else{
    //         return $query->result();
    //     }
    // }

}
//echo '<pre>' . var_export($_POST, true) . '</pre>'; die();
?>