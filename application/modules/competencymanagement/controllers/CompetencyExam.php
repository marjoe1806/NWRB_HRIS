<?php

class CompetencyExam extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->model('Helper');
		$this->load->model('CompetencyExamCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
		
	}
	public function index($params) {
		$validation = $this->validateInformation($params);
		$helper = new Helper();
		$viewData['validation'] = $validation;
		$viewData['quiz_data'] = [];

		if($validation['Code'] == "0"){
			$viewData['quiz_data'] = $this->CompetencyExamCollection->get_data($validation);
		}
		$viewData['multiple_choice_data'] = $this->CompetencyExamCollection->get_competency_choices($validation);
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Competency Exam');
			Helper::setMenu('templates/menu_template');
			Helper::setView('competencyexam',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		}
		else{
			$result['key'] = $listData['key'];
			$result['table'] = $viewData['table'];
			echo json_encode($result);
		}
		Session::checksession();
	}

	public function add_essay() {
		return 'testing';
	}
	public function add_result() {
		// Load the model
		$this->load->model('data_model');
		// {
		// 	competency_id: 0,
		// 	enumeration_res: 2,
		// 	fill_res: 2,
		// 	multiplication_res: 0,
		// 	access_id:
		// }
		// Get the data from the AJAX request
		$data = array(
			'competency_id' => $_POST['competency_id'],
			'enumeration_res' => $_POST['enumeration_res'],
			'fill_res' => $_POST['fill_res'],
			'multiplication_res' => $_POST['multiplication_res'],
			'access_id' => $_POST['access_id']
		  );
		// $data = array(
		// 	'competency_id' => 1,
		// 	'enumeration_res' => 23,
		// 	'essay_res' => 23,
		// 	'fill_res' => 23,
		// 	'multiplication_res' => 23,
		// 	'access_id' => 6
		//   );
		// Call the model function to insert the data
		$this->data_model->insert_data_to_result($data);
	
		// Return a response (you can customize the response if needed)
		echo "Data inserted successfully!";
	}

	public function add_answer() {
		// Load the model
		$this->load->model('data_model');


		// Call the model function to insert the data
		$this->data_model->insert_data_to_answer($data);
	
		// Return a response (you can customize the response if needed)
		echo "Data inserted successfully!";
	}

	private function validateInformation($params){
		$helper = new Helper();
		$decrypted = $helper->decrypt($params,'0000CoMPeTeNcY0000ExAm0000');
		$sliced = explode("-", $decrypted);
		
		$ret =  new CompetencyExamCollection();

		if(isset($sliced[0]) && isset($sliced[1])){

			$data = array(
				"emailaddress" => $sliced[0],
				"access_id" => $sliced[1],
			);

			$validate = $ret->validateAccess($data);
			if($validate){
				$info = array(
					"id" => $validate[0]->id,
					"access_id" => $validate[0]->access_id,
					"status" => $validate[0]->status,
					"emailaddress" => $validate[0]->emailaddress,
					"reference" => $validate[0]->reference,
					"date" => $validate[0]->date,
					"time_start" => $validate[0]->time_start,
					"time_end" => $validate[0]->time_end,
					"exam_status" => $validate[0]->exam_status,
					"exam_duration" => $validate[0]->exam_duration,
					"type_id" => $validate[0]->type_id
				);

				$date_today = date("Y-m-d");
				$time_today = date("H:m");
				
				$date_format = date('F d, Y', strtotime($info['date']));
				$time_start_format = date('h:i A', strtotime($info['time_start']));
				$time_end_format = date('h:i A', strtotime($info['time_end']));

				if($info['exam_status'] == 1){
					$res = new ModelResponse("1", "You have already completed the exam.!");
				}else if(strtotime($date_today) < strtotime($info['date'])){
					$res = new ModelResponse("1", "Please come back on ".$date_format."!");
				}else if(strtotime($date_today) > strtotime($info['date'])){
					$res = new ModelResponse("1", "Sorry your examination schedule was on ".$date_format."!");
				}else{
					if(strtotime($time_today) < strtotime($info['time_start'])){
						$res = new ModelResponse("1", "Please standby. Exam will start at ".$time_start_format."!");
					}else if(strtotime($time_today) > strtotime($info['time_end'])){
						$res = new ModelResponse("1", "Sorry your examination schedule was on ".$time_end_format."!");
					}else{
						$res = new ModelResponse("0", "Valid", $info);
					}
				}
				return json_decode($res,true);
			}else{
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
		}else{
			$res = new ModelResponse("1", "Invalid Link Address");
			return json_decode($res,true);
		}
	}
}
?>