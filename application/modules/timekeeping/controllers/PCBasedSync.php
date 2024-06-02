<?php

class PCBasedSync extends MX_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Helper');
		$this->load->model('PCBasedSyncCollection');
		$this->load->module('session');
		$this->load->model('ModuleRels');
		Helper::sessionEndedHook('session');
	}
	public function index() {
		Helper::rolehook(ModuleRels::AUDIT_TRAILS_SUB_MENU);
		$listData = array();
		$viewData = array();
		$page = "viewPCBasedSync";
		$listData['key'] = $page;
		$viewData['table'] = $this->load->view("helpers/pcbasedsynclist",$listData,TRUE); 
		if (!$this->input->is_ajax_request()) {
			Helper::setTitle('PC Based Syncing');
			Helper::setMenu('templates/menu_template');
			Helper::setView('pcbasedsync',$viewData,FALSE);
			Helper::setTemplate('templates/master_template');
		}
		else{
			$result['key'] = $listData['key'];
			$result['table'] = $viewData['table'];
			echo json_encode($result);
		}
		Session::checksession();
	}
	function fetchRows(){ 
        $fetch_data = $this->PCBasedSyncCollection->make_datatables();  
        $data = array();  
        foreach($fetch_data as $k => $row)  
        {  
        	$buttons = "";
        	$buttons_data = "";

            $sub_array = array();    
            $sub_array[] = $row->source_device;
            $sub_array[] = $row->username; 
            $sub_array[] = date('m/d/Y h:i:s',strtotime($row->date_created));
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"                  =>     intval($_POST["draw"]),  
            "recordsTotal"          =>      $this->PCBasedSyncCollection->get_all_data(),  
            "recordsFiltered"     	=>     $this->PCBasedSyncCollection->get_filtered_data(),  
            "data"                  =>     $data  
        );  
        echo json_encode($output);  
    }

    public function pcSyncing(){
		//var_dump($_POST);die();
		$result = array();
		$page = 'pcSyncing';
		if (!$this->input->is_ajax_request()) {
		   show_404();
		}
		else
		{
			if($this->input->post() && $this->input->post() != null)
			{
				$post_data = array();
				foreach ($this->input->post() as $k => $v) {
					$post_data[$k] = $this->input->post($k,true);
				}
				$ret =  new PCBasedSyncCollection();
				if($ret->addPcSyncing($post_data)) {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				} else {
					$res = new ModelResponse($ret->getCode(), $ret->getMessage());
				}
				$result = json_decode($res,true);
			}
			else
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