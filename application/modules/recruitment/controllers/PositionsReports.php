<?php

class PositionsReports extends MX_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Helper');
		$this->load->module('session');
		$this->load->model('PositionsReportsCollection');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}

	public function index()
	{
		Helper::rolehook(ModuleRels::VACANT_POSITIONS_SUB_MENU);
		$listData = array();
		$viewData = array();

		$page = "viewpositionsreports";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/positionsreportslist", $listData, TRUE);

		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Positions Reports');
			Helper::setMenu('templates/menu_template');
			Helper::setView('positionsreports', $viewData, FALSE);
			Helper::setTemplate('templates/master_template');
		} else {
			$result['key'] = $listData['key'];
			$result['table'] = $viewData['table'];
			echo json_encode($result);
		}
		Session::checksession();
	}
	function fetchRows()
	{
		$data = array();
		$ret = new PositionsReportsCollection();
		$ret = $ret->make_datatables();
		foreach ($ret as $k => $row) {
			$buttons_action = "";
			$buttons_data = "";

			$row->filled_pos = $row->filled_pos < 0 ? 0 : $row->filled_pos;

			$sub_array = array();

			foreach ($row as $k1 => $v1) {
				$buttons_data .= ' data-' . $k1 . '="' . $v1 . '" ';
			}

				$buttons_action .= '<a id="viewPositionsReportsForm" '
					. ' class="viewPositionsReportsForm" style="text-decoration: none;" '
					. ' href="' . base_url() . $this->uri->segment(1) . '/PositionsReports/viewPositionsReportsForm" '
					. $buttons_data
					. ' > '
					. ' <button class="btn btn-primary btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="View details" id="remove_symbol">'
					. ' <i class="material-icons">remove_red_eye</i>'
					. ' </button> '
					. ' </a>';

			$sub_array[] = $buttons_action;
			$sub_array[] = $row->name;
			$sub_array[] = $row->grade;
			$sub_array[] = $row->vacant;
			$sub_array[] = $row->permanent;
			$sub_array[] = $row->contractual;
			$sub_array[] = $row->cos;
			$sub_array[] = $row->temporary;
			if($row->filled_pos){
			$sub_array[] = $row->filled_pos;
			}else{
			$sub_array[] = "Filled";
			}
			$data[] = $sub_array;

		}

		$output = array(
			"draw" => intval($_POST["draw"]),
			"recordsTotal" => $this->PositionsReportsCollection->get_all_data(),
			"recordsFiltered" => $this->PositionsReportsCollection->get_filtered_data(),
			"data" => $data
		);
		echo json_encode($output);
	}
	
	public function viewPositionsReportsForm(){
		$formData = array();
		$result = array();
		$result['key'] = 'viewPositionsReports';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			$formData['key'] = $result['key'];
			$result['form'] = $this->load->view('forms/positionsreportsform.php', $formData, TRUE);
		}
		echo json_encode($result);
	}

}

?>
