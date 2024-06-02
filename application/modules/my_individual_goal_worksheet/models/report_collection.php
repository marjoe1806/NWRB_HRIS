<?php
class report_collection extends Helper {
 
    public function __construct()
    {
        $this->load->database();
        $this->load->model("UserAccount");
    }

   public function insert($postdata){

        $data_report_id = array(
            "emp_id" => $postdata['emp_id'],
            "employee_name" => $postdata['employee_name'],
            "employee_position" => $postdata['employee_position'],
            "employee_division" => $postdata['employee_division'],
            "supervisor_name" => $postdata['supervisor_name'],
            "supervisor_id" => $postdata['supervisor_id'],
            "id_rater" => $postdata['rater_id'],
            "rater_name" => $postdata['rater_name'],
            "id_director" => $postdata['first_approver_id'],
            "director_name" => $postdata['first_approver_name'],
            "director_position" => $postdata['first_approver_position'],
            "id_third" => $postdata['second_approver_id'],
            "third_name" => $postdata['second_approver_name'],
            "third_position" => $postdata['second_approver_position']
        );

        $this->db->insert('tbl_reports', $data_report_id);

        $insert_id = $this->db->insert_id();


        foreach ($postdata['indv_goals'] as $key => $value) {
            $data = array( 
                "report_id" => $insert_id,
                "individual_goals" => $postdata['indv_goals'][$key],
                "third_quarter_results" => $postdata['third_res'][$key],
                "fourth_quarter_results" => $postdata['fourth_res'][$key],
                "result_and_remarks" => $postdata['res_remarks'][$key],
                "percentage" => $postdata['percentage'][$key],
            );
            $this->db->insert('tbl_pes',$data);
        }

        $data2 = array(
            'id_form_b' =>  $insert_id,
            'quality_of_job' => $postdata['quality_of_job'],
            'public_and_emp_rel' => $postdata['public_and_emp_rel'],
            'punc_and_attend' => $postdata['punc_and_attend'],
            'industry' => $postdata['industry'],
            'total_score' => $postdata['total_score'],
            'average_score' => $postdata['average_score'],
            'rating' => $postdata['rating'],
            'final_rating_part' => $postdata['final_rating_part'],
            'final_rating_part2' => $postdata['final_rating_part2'],
            'numerical_rate' => $postdata['numerical_rate'],
            'adjectival_rate' => $postdata['adjectival_rate']
        );

        $this->db->insert('tbl_form_b',$data2);

        if($this->db->affected_rows() > 0){
            return "success"; 
        }
        else{
            return false;
        }
   }

   public function get_emp_name(){
        $this->db->select('*'); 
        $this->db->from('tbl_emp_name');
        $query = $this->db->get();
        
        return $query->result();
   }

   /*public function load_tbl_reports(){
        $this->db->select('*');    
        $this->db->from('tbl_pes a');
        $this->db->join('tbl_reports b','a.report_id = b.report_id');
        $this->db->join('tbl_emp_name c','c.id = b.emp_id');
        $query = $this->db->get();

        return $query->result();
   }*/

    public function load_tbl_reports(){
        $this->db->select('*');    
        $this->db->from('tbl_reports');
        $this->db->where('emp_id', Helper::get('employee_id'));
        $query = $this->db->get();
        return $query->result();
    }

    public function getAllData($postdata){
        $this->db->select('*');    
        $this->db->from('tbl_pes a');
        $this->db->join('tbl_reports b', 'a.report_id = b.id_report');
        $this->db->join('tbl_form_b c', 'a.report_id = c.id_form_b');
        $this->db->where('a.report_id', $postdata['report_id']);
        $query = $this->db->get();

        return $query->result();
    }

    public function update($postdata){
        //$isSuccess;
       //var_dump($postdata); die();
        $this->db->trans_start();
        $dataq = array(
            "id_report" => $postdata['update_report_id'][0],
            "employee_name" => $postdata['employee_name'],
            "emp_id" => $postdata['employee_id'],
            "supervisor_id" => $postdata['supervisor_id'],
            "supervisor_name" => $postdata['supervisor_name'],
            "id_rater" => $postdata['rater_name_update_id'],
            "rater_name" => $postdata['rater_name_update'],
            "id_director" => $postdata['first_approver_update_id'],
            "director_name" => $postdata['first_approver_update'],
            "director_position" => $postdata['first_approver_position'],
            "id_third" => $postdata['second_approver_id_update'],
            "third_name" => $postdata['second_approver_update'],
            "third_position" => $postdata['second_approver_position_update']
        );

        // /var_dump($dataq); die();

        $this->db->where('id_report', $postdata['update_report_id'][0]);
        $this->db->update('tbl_reports', $dataq);
        foreach ($postdata['update_indv_goals'] as $key => $value) {
            $data = array(
                "id" => $postdata['update_id'][$key],
                "report_id" => $postdata['update_report_id'][$key],
                "individual_goals" => $postdata['update_indv_goals'][$key],
                "third_quarter_results" => $postdata['update_third_res'][$key],
                "fourth_quarter_results" => $postdata['update_fourth_res'][$key],
                "result_and_remarks" => $postdata['update_res_remarks'][$key],
                "percentage" => $postdata['update_percentage'][$key]
            );
            
            $this->db->where('id', $data['id']);
            $query = $this->db->get('tbl_pes');

            if($query->num_rows() > 0){
                $this->db->where('id', $data['id']);
                $this->db->update('tbl_pes', $data);
            }
            else{
                $this->db->insert('tbl_pes',$data);                
            }
        } 
        
        $data2 = array(
            "id" => $postdata['hidden_id'],
            "id_form_b" => $postdata['hidden_id_form'],
            "quality_of_job" => $postdata['update_quality_of_job'],
            "punc_and_attend" => $postdata['update_punc_and_attend'],
            "industry" => $postdata['update_industry'],
            "total_score" => $postdata['update_total_score'],
            "average_score" => $postdata['update_average_score'],
            "rating" => $postdata['update_rating'],
            "final_rating_part" => $postdata['update_final_rating_part'],
            "final_rating_part2" => $postdata['update_final_rating_part2'],
            "numerical_rate" => $postdata['update_numerical_rate'],
            "adjectival_rate" => $postdata['update_adjectival_rate'],
        );   

        $this->db->where('id', $data2['id']);
        $this->db->update('tbl_form_b', $data2);
        return $this->db->trans_complete();


    }

    public function delete($postdata){
        $this->db->where('id', $postdata['delete_id']);
        $this->db->delete('tbl_pes');
        return "deleted"; 
    }

    public function delete_this_report($postdata){
        $this->db->where('report_id', $postdata['report_id']);
        $this->db->delete('tbl_pes');
        $this->db->where('id_report', $postdata['report_id']);
        $this->db->delete('tbl_reports');
        $this->db->where('id_form_b', $postdata['report_id']);
        $this->db->delete('tbl_form_b');
        return "deleted"; 
    }

}
//echo '<pre>' . var_export($_POST, true) . '</pre>'; die();
?>