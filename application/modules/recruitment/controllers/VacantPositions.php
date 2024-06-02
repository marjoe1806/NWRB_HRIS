<?php

class VacantPositions extends MX_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Helper');
		$this->load->module('session');
		$this->load->model('VacantPositionsCollection');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}

	public function index()
	{
		Helper::rolehook(ModuleRels::VACANT_POSITIONS_SUB_MENU);
		$listData = array();
		$viewData = array();

		$page = "viewVacantPositions";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/vacantpositionslist", $listData, TRUE);

		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('Vacant Positions');
			Helper::setMenu('templates/menu_template');
			Helper::setView('vacantpositions', $viewData, FALSE);
			Helper::setTemplate('templates/master_template');
		} else {
			$result['key'] = $listData['key'];
			$result['table'] = $viewData['table'];
			echo json_encode($result);
		}
		Session::checksession();
	}

	// initialize table
	function fetchRows()
	{

		$data = array();
		$ret = new VacantPositionsCollection();
		$ret = $ret->make_datatables();
		foreach ($ret as $k => $row) {
			$buttons_action = "";
			$buttons_data = "";

			$row->filled_pos = $row->filled_pos < 0 ? 0 : $row->filled_pos;

			$sub_array = array();

			foreach ($row as $k1 => $v1) {
				$buttons_data .= ' data-' . $k1 . '="' . $v1 . '" ';
			}

			if ($row->filled_pos) {
				$buttons_action .= '<a id="addJobOpeningsForm" '
					. ' class="addJobOpeningsForm" style="text-decoration: none;" '
					. ' href="' . base_url() . $this->uri->segment(1) . '/JobOpenings/addJobOpeningsForm" '
					. $buttons_data
					. ' > '
					. ' <button class="btn btn-success btn-circle waves-effect waves-circle waves-float" data-toggle="tooltip" data-placement="top" title="Add Job Opening">'
					. ' <i class="material-icons">add_to_queue</i>'
					. ' </button> '
					. ' </a>';
			}

			if ($_POST['filter1'] == "all"){
				$sub_array[] = $buttons_action;
				$sub_array[] = $row->code;
				$sub_array[] = $row->name;
				$sub_array[] = $row->department_name;
				$sub_array[] = $row->grade;
				$sub_array[] = $row->vacant;
				$sub_array[] = $row->permanent;
				$sub_array[] = $row->contractual;
				$sub_array[] = $row->cos;
				$sub_array[] = $row->temporary;
				$sub_array[] = (int)$row->filled_pos > 0 ? "<span class='text-success'><b> Vacant </b></span>" : $row->full_name;
				$data[] = $sub_array;
			}else{

				$sub_array[] = $buttons_action;
				$sub_array[] = $row->code;
				$sub_array[] = $row->name;
				$sub_array[] = $row->department_name;
				$sub_array[] = $row->grade;
				$sub_array[] = $row->vacant;
				$sub_array[] = $row->permanent;
				$sub_array[] = $row->contractual;
				$sub_array[] = $row->cos;
				$sub_array[] = $row->temporary;
				$sub_array[] = (int)$row->filled_pos == 0 ? $row->full_name : $row->filled_pos;
				$data[] = $sub_array;
			}

			
		}

		$output = array(
			"draw" => intval($_POST["draw"]),
			"recordsTotal" => $this->VacantPositionsCollection->get_all_data(),
			"recordsFiltered" => $this->VacantPositionsCollection->get_filtered_data(),
			"data" => $data
		);
		echo json_encode($output);
	}
}

?>
