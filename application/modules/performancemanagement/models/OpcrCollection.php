<?php
class OpcrCollection extends Helper {


    public function __construct()
    {
        $this->load->database();
    }

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

    public function add_form($postdata){
        $this->db->trans_start();
            $data = array(
                'name' => $postdata['employee_id'],
                'position' => $postdata['position'],
                'period_of' => $postdata['period_of'],
                'approved_by' => $postdata['approved_by'],
                'date_approved' => $postdata['date_approved'],
                'assessed_by_planning_officer' => $postdata['assessed_by_planning_officer'],
                'officer_assessed_date' => $postdata['officer_assessed_date'],
                'final_rating_by' => $postdata['final_rating_by'],
                'final_rating_date' => $postdata['final_rating_date'],
                'pmt_assessed_date' => $postdata['pmt_assessed_date'],
                'assessed_by_pmt'=> $postdata['assessed_by_pmt'],
                'ratee' =>  $_SESSION['employee_id'],
                'submitted_date' =>  date('Y-m-d'),
            );
            
            $this->db->insert('tblopcrform', $data);
            $insert_id = $this->db->insert_id();

            if($this->db->trans_status() === false){
                $this->db->trans_rollback();
            }
            else{
                $this->db->trans_commit();
            }
        $this->db->trans_complete();

        $this->db->trans_start();

            $form = array();

            if(isset($postdata['strat_major'])){
                foreach ($postdata['strat_major'] as $key => $value) {
                    $form['strat'][$key]['form_id'] = $insert_id;
                    $form['strat'][$key]['weight_of_output'] = 'Strategic';
                    $form['strat'][$key]['mfo_pap'] = $value;
                }
            }

            if(isset($postdata['strat_success'])){
                foreach ($postdata['strat_success'] as $key => $value) {
                    $form['strat'][$key]['success_target'] = $value;
                }
            }

            if(isset($postdata['strat_alloted'])){
                foreach ($postdata['strat_alloted'] as $key => $value) {
                    $form['strat'][$key]['allotted_budget'] = $value;
                }
            }

            if(isset($postdata['strat_office'])){
                foreach ($postdata['strat_office'] as $key => $value) {
                    $form['strat'][$key]['office_individual'] = $value;
                }
            }

            if(isset($postdata['strat_actual'])){
                foreach ($postdata['strat_actual'] as $key => $value) {
                    $form['strat'][$key]['actual_accomplishments'] = $value;
                }
            }

            if(isset($postdata['core_major'])){
                foreach ($postdata['core_major'] as $key => $value) {
                    $form['core'][$key]['form_id'] = $insert_id;
                    $form['core'][$key]['weight_of_output'] = 'Core';
                    $form['core'][$key]['mfo_pap'] = $value;
                }
            }

            if(isset($postdata['core_success'])){
                foreach ($postdata['core_success'] as $key => $value) {
                    $form['core'][$key]['success_target'] = $value;
                }
            }

            if(isset($postdata['core_alloted'])){
                foreach ($postdata['core_alloted'] as $key => $value) {
                    $form['core'][$key]['allotted_budget'] = $value;
                }
            }

            if(isset($postdata['core_office'])){
                foreach ($postdata['core_office'] as $key => $value) {
                    $form['core'][$key]['office_individual'] = $value;
                }
            }

            if(isset($postdata['core_actual'])){
                foreach ($postdata['core_actual'] as $key => $value) {
                    $form['core'][$key]['actual_accomplishments'] = $value;
                }
            }

            if(isset($postdata['support_major'])){
                foreach ($postdata['support_major'] as $key => $value) {
                    $form['support'][$key]['form_id'] = $insert_id;
                    $form['support'][$key]['weight_of_output'] = 'Support';
                    $form['support'][$key]['mfo_pap'] = $value;
                }
            }

            if(isset($postdata['support_success'])){
                foreach ($postdata['support_success'] as $key => $value) {
                    $form['support'][$key]['success_target'] = $value;
                }
            }

            if(isset($postdata['support_alloted'])){
                foreach ($postdata['support_alloted'] as $key => $value) {
                    $form['support'][$key]['allotted_budget'] = $value;
                }
            }

            if(isset($postdata['support_office'])){
                foreach ($postdata['support_office'] as $key => $value) {
                    $form['support'][$key]['office_individual'] = $value;
                }
            }

            if(isset($postdata['support_actual'])){
                foreach ($postdata['support_actual'] as $key => $value) {
                    $form['support'][$key]['actual_accomplishments'] = $value;
                }
            }

            $form['Final-rating'][] = array(
                            'weight_of_output' => "Final-Rating",
                            'form_id' => $insert_id,
                        );
            
            foreach ($form as $key => $value) {
               foreach ($value as $key2 => $value2) {
                   $this->db->insert('tblopcranswer', $value2);
                   if($this->db->trans_status() === false)
                        $this->db->trans_rollback();
                   else
                        $this->db->trans_commit();
               }
            }
            // $test = array_merge($form['strat'],$form['core'],$form['support']);
            // $this->db->insert_batch('tblopcranswer', $test);
            // if($this->db->trans_status() === false){
            //      $this->db->trans_rollback();
            // }else{
            //      $this->db->trans_commit();
            // }

            if($this->db->affected_rows() != 1){
                $code = 1;
                $message = "Failed to insert data!";
                $this->ModelResponse($code, $message);
                return false; 
            }
            else{
                $code = 0;
                $message = "Successfully Inserted!";
                $this->ModelResponse($code, $message);
                return true;
            }

        $this->db->trans_complete();

    }

}
?>