<?php

class CompetencyExamination extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->model('Helper');
		$this->load->model('CompetencyExaminationCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
		
	}
	public function index($params) {
		$validation = $this->validateInformation($params);

		Helper::rolehook(ModuleRels::DIVISIONS_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "saveExaminationCompetency";
		$listData['key'] = $page;
		$listData['validation'] = $validation;
		$listData['exam'] = [];

		if($validation['Code'] == "0"){
			$listData['exam'] = $this->CompetencyExaminationCollection->getExamination($validation);
		}

		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Competency Exam');
			Helper::setMenu('templates/menu_template');
			Helper::setView('competencyexamination',$listData,FALSE);
			Helper::setTemplate('templates/master_template');
		}
		else{
			$result['key'] = $listData['key'];
			echo json_encode($result);
		}
		Session::checksession();
	}

	private function validateInformation($params){
		$helper = new Helper();
		$decrypted = $helper->decrypt($params,'0000CoMPeTeNcY0000ExAm0000');
		$sliced = explode("-", $decrypted);
		
		$ret =  new CompetencyExaminationCollection();

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
					"type_id" => $validate[0]->type_id,
					"accessemail_id" => $validate[0]->accessemail_id
				);

				$date_today = date("Y-m-d");
				$time_today = date("H:m");
				
				$date_format = date('F d, Y', strtotime($info['date']));
				$time_start_format = date('h:i A', strtotime($info['time_start']));
				$time_end_format = date('h:i A', strtotime($info['time_end']));

				if($info['exam_status'] == 1){
					$res = new ModelResponse("1", "You have already completed the exam.");
				}else if(strtotime($date_today) < strtotime($info['date'])){
					$res = new ModelResponse("1", "Please come back on ".$date_format.".");
				}else if(strtotime($date_today) > strtotime($info['date'])){
					$res = new ModelResponse("1", "Sorry your examination schedule was on ".$date_format.".");
				}else{
					if(strtotime($time_today) < strtotime($info['time_start'])){
						$res = new ModelResponse("1", "Please standby. Exam will start at ".$time_start_format.".");
					}else if(strtotime($time_today) > strtotime($info['time_end'])){
						$res = new ModelResponse("1", "Sorry your examination time has already lapsed.");
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

	public function saveExaminationCompetency(){
		$result = array();
		$page = 'saveExaminationCompetency';

		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			if($this->input->post() && $this->input->post() != null)
			{
				$post_data = array();
				$ret =  new CompetencyExaminationCollection();

				$summary = array(
					"enumeration_res" => 0,
					"multiplication_res" => 0,
					"fill_res" => 0,
					"essay_res" => 0,
					"accessemail_id" => $this->input->post('accessemail_id', true),
					"competency_id" => $this->input->post('competency_id', true),
				);

				$data = array();

				foreach ($this->input->post() as $key => $value) {
					$ques = $ret->checkAnswer($key, $value);

					if($ques && ($key != 'access_id' || $key != 'competency_id')){
						$info = array(
							"question_id" => $ques[0]->id,
							"competency_id" => $ques[0]->competency_id,
							"exam_type" => $ques[0]->exam_type,
							"answer" => $ques[0]->answer,
							"exam_type" => $ques[0]->exam_type,
							"points" => $ques[0]->points,
						);

						if(strtolower($info['answer']) == strtolower($value)){
							if($info['exam_type'] == 'enumeration'){
								$summary['enumeration_res'] += intval($info['points']);
							}else if($info['exam_type'] == 'fill'){
								$summary['fill_res'] += intval($info['points']);
							}else if($info['exam_type'] == 'multiple'){
								$summary['multiplication_res'] += intval($info['points']);
							}
							
							$answer_specific = array(
								"access_email_id" => $this->input->post('access_id', true),
								"question_id" => $ques[0]->id,
								"competency_id" => $ques[0]->competency_id,
								"answer" => $value,
								"score" => $ques[0]->points,
							);
							array_push($data, $answer_specific);
						}else{
							$answer_specific = array(
								"access_email_id" => $this->input->post('access_id', true),
								"question_id" => $ques[0]->id,
								"competency_id" => $ques[0]->competency_id,
								"answer" => $value,
								"score" => 0,
							);
							array_push($data, $answer_specific);
						}

					}
				}

				if($ret->saveAnswers($data)) {
					$ret->saveResult($summary);
					$ret->updateAccessEmailStatus($summary['accessemail_id']);
					$res = new ModelResponse($ret->getCode(), $ret->getMessage(), $summary);
					$result = json_decode($res,true);
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
					$result = json_decode($res,true);
				}
			}else
			{
				$res = new ModelResponse();
				$result = json_decode($res,true);
			}
			$result['key'] = $page;
		}
		echo json_encode($result);
	}
}
?>