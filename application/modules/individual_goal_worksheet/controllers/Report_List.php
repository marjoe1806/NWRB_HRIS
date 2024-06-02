<?php

class Report_List extends MX_Controller{

	public function __construct() {
		parent::__construct();
		$this->load->model('Helper'); //--> Loads Helper Class (Contains various helper functions)
		$this->load->model('ModuleRels');
		$this->load->model('Report_Collection');
		$this->load->module('session');
		Helper::sessionEndedHook('session');
	}

	public function index() {
		// var_dump("test");die();
        //$this->Helper->sessionEndedHook('Session');
        

		Helper::setTitle('Performance Evaluation'); //--> Set title to the Template
		Helper::setView('individual_goal.form','',FALSE);
		Helper::setMenu('templates/menu_template');
		Helper::setTemplate('templates/master_template');
		Session::checksession();
	}

	public function insert(){
		$ret = $this->Report_Collection->insert($_POST);
		echo json_encode(array("data"=>$ret));
	}

	public function get_emp_name(){
		$ret = $this->Report_Collection->get_emp_name();
		echo json_encode(array("data"=>$ret));
	}

	public function load_tbl_reports(){
		$ret = $this->Report_Collection->load_tbl_reports();
		echo json_encode(array("data"=>$ret));
	}

	public function getAllData(){
		$ret = $this->Report_Collection->getAllData($_POST);
		//echo '<pre>' . var_export($ret, true) . '</pre>'; die();
		echo json_encode(array("data"=>$ret));
	}

	public function update(){
		$ret = $this->Report_Collection->update($_POST);
		echo json_encode(array("data"=>$ret));
	}

	public function delete(){
		$ret = $this->Report_Collection->delete($_POST);
		echo json_encode(array("data"=>$ret));
	}

	public function delete_this_report(){
		$ret = $this->Report_Collection->delete_this_report($_POST);
		echo json_encode(array("data"=>$ret));
	}

	public function exportExcel(){
  		ob_start();
		$out = $this->TRExportExcel();
		ob_end_clean();
		header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
		header("Content-Disposition: attachment; filename=Individual Goal Worksheet". date("Y-m-d H-i-s") . ".xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		echo $out;
		die();
		exit;
  	}

  	public function TRExportExcel(){
 		
 		//var_dump($_POST['form-input']); die();

  		$tableTitle = 
			'<table cellpadding="0" cellspacing="0" width="100%" style="border-color:black;">
				<tr style="text-align:center; font-size: 20px; font-weight: bold;">
					<td colspan = "28">
						AGRICUTURAL CREDIT POLICY COUNCIL<br>
						<small>
							28th Floor OSMA Bldg., One San Miguel Ave. cor Shaw Blvd. Ortigas Pasig City
						</small>
					</td>
				</tr>
			</table>'
		;



		$table = 
			'<table cellpadding="0" cellspacing="0" border = "1" width="100%" style="border-color:black;"> 
				<thead>
	              	<th colspan = "5">
	              		INDIVIDUAL GOALS<br>
	              		<small>
	              			(Must be specified with target dates as appropriate)
	              		</small>
	              	</th>
	              	<th colspan = "5">
                        <center>
                            3rd QUARTER RESULTS<br>
                            <small>Date:_____ Initials:_____</small>
                        </center>
                    </th>
                     <th colspan = "5">
                        <center>
                            4th QUARTER RESULTS<br>
                            <small>Date:_____ Initials:_____</small>
                        </center>
                    </th>
                    <th colspan = "5">
                        <center>
                            RESULT AND REMARKS
                        </center>
                    </th>
                    <th colspan = "5">
                        <center>
                            PERCENTAGE SCORE<br>
                            <small>RESULTS _____ * 100%</small>
                        </center>
                    </th>
	            </thead>
	            <tbody>
	            	<tr>
	        '
		;

		$data = '';
		$tr = '<tr>';
		$closetr = '</tr>';
		$b = 5;

		

		/*foreach ($_POST['form-input'] as $key => $value) {
			$val[$key+1] = $value; 
		}

		for ($a=1; $a <= count($val); $a++) { 
			$data = $data
				.'<td colspan = "5" style = "font-size: 13pt;"><center>'. $val[$a] .'</center></td>'
			;
			if($a == $b){
				$data = $data
					.'</tr><tr>'
				;
				$b = $b + 5;
			}
		}*/

		/*foreach ($_POST['form-input'] as $key => $value) {
			$data = $data
				.'<tr>'.
					'<td>'. $value .'</td>'.
					'<td>'. $value .'</td>'.
					'<td>'. $value .'</td>'.
					'<td>'. $value .'</td>'.
					'<td>'. $value .'</td>'
			;
		}*/


		
		return $tableTitle. $table. $data;
  	}
}

?>

