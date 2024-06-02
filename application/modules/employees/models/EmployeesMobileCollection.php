<?php
	class EmployeesMobileCollection extends Helper {
      	var $select_column = null; 
		public function __construct() {
			$this->load->model('HelperDao');
			ModelResponse::busy();
			$columns = $this->getColumns();
			foreach ($columns as $key => $value) {
				$this->select_column[] = $this->table.'.'.$value['COLUMN_NAME'];
			}
			//var_dump($this->select_column);die();

		}
		//Fetch
		var $table = "tblemployeesmobilelogs";   
      	var $order_column = array(
      				"tblfieldmobiledevices.description",
      				"tblemployeesmobilelogs.log",
      				"tblwebusers.username",
      				"tblemployeesmobilelogs.date_created"
      	);
      	public function getColumns(){
      		
      		$sql = " SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='".$this->table."' AND TABLE_SCHEMA='".$this->db->database."' ";
			$query = $this->db->query($sql);
			$rows = $query->result_array();
			return $rows; 
      	}
		function make_query()  
		{  
			$this->select_column[] = 'tblwebusers.username';
			$this->select_column[] = 'tblfieldmobiledevices.description';
		    $this->db->select(
		    	$this->table.'.*,
		    	tblwebusers.username AS username,
		    	tblfieldmobiledevices.description'
		    );  
		    $this->db->from($this->table);  
		    $this->db->join("tblwebusers",$this->table.".created_by = tblwebusers.userid","left");
		    $this->db->join("tblfieldmobiledevices",$this->table.".mobile_id = tblfieldmobiledevices.imei","left");
		    if(isset($_POST["search"]["value"]))  
		    {  
		    	$this->db->group_start();

	     		//var_dump($this->select_column);die();
		     	foreach ($this->select_column as $key => $value) {
		     		
	     			$this->db->or_like($value, $_POST["search"]["value"]);  
		     		
		     	}
		
		        $this->db->group_end(); 
		    }
		 
		    if(isset($_POST["order"]))  
		    {  	
		    	$this->db->order_by($this->order_column[$_POST['order']['0']['column']]." ". $_POST['order']['0']['dir']);
		    }  
		    else  
		    {  
		          $this->db->order_by($this->table.'.date_created', 'DESC');  
		    }  
		}  
		function make_datatables(){  
		    $this->make_query();  
		    if($_POST["length"] != -1)  
		    {  
		        $this->db->limit($_POST['length'], $_POST['start']);  
		    }  

		    $query = $this->db->get();  
		    //echo $this->db->last_query();die();

		    //var_dump($this->db->last_query());die();
		    return $query->result();  

		}  
		function get_filtered_data(){  
		     $this->make_query(); 
		     //var_dump($this->make_query());die();

		     $query = $this->db->get();  
		     return $query->num_rows();  
		}       
		function get_all_data()  
		{  
		    $this->db->select($this->table."*");  
		    $this->db->from($this->table);
		    return $this->db->count_all_results();  
		}  
		//End Fetch
		public function downloadMobileEmployees($mobile_id){
			$helperDao = new HelperDao();
			$data = array();
			$code = "1";
			$message = "Failed to download mobile employees. No data available";
			//Get data from employee service
			$ret = $this->getMobileEmployeesCloud($mobile_id);
			// var_dump($ret);die();

			if(isset($ret['Data']['mobile_employee']) && sizeof($ret['Data']['mobile_employee']) > 0){
				//Write Template to Text
				$text['empModels'] = array();
				foreach ($ret['Data']['mobile_employee'] as $key => $value) {
					$text['empModels'][$key]['coordinates']="0,0";
					$text['empModels'][$key]['empId'] = $value['temp_id'];
					$text['empModels'][$key]['encodedImage'] = "";
					$text['empModels'][$key]['fingerprintTemplates'] = array(
						"empNo"=>0,
						"id"=>0,
						"templates"=>array($value['template1'],$value['template2'],$value['template3'],$value['template4'])
					);
					$text['empModels'][$key]['firstName'] = $value['first_name'];
					$text['empModels'][$key]['id'] = $value['id'];
					$text['empModels'][$key]['lastName'] = $value['last_name'];
					$text['empModels'][$key]['status'] = 1;
				}
				$securityKey = "TheBestSecretKey";
				$this->load->model("Security");
				$security = new Security();
				$text = json_encode($text,true);
				$encrypted_params = $security->encrypt($text,$securityKey);
				// var_dump($encrypted_params); die();
				$current_date = date('m-d-Y');
				$filename = $ret['Data']['mobile_employee'][0]['imei'].$current_date;
				/*$myfile = fopen($filename.".txt", "w") or die("Unable to open file!");
				$txt = $encrypted_params;
				fwrite($myfile, $txt);
				fclose($myfile);*/
				$data["content"] = $encrypted_params; 
				$code = "0";
				$message = "Successfully downloaded mobile employees.";
				$sqldata = array(
					'mobile_id'=>$mobile_id,
					'log'=>$message,
					'created_by'=>Helper::get('userid')
				);
				$this->db->insert('tblemployeesmobilelogs',$sqldata);
				$this->ModelResponse($code, $message, $data);	
				$helperDao->auditTrails(Helper::get('userid'),$message);
				return true;		
			}	
			else {
				$this->ModelResponse($code, $message);
				$helperDao->auditTrails(Helper::get('userid'),$message);
			}
			return false;
		}
		public function getMobileEmployeesCloud($mobile_id){
			$data = array('imei'=>$mobile_id);
			// var_dump(json_encode($data));die();
			$url = $GLOBALS['service_url'] . "getEmployeeMobileByIMEI";
			// var_dump($url);die();
			$postdata = json_encode($data);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
			$result = curl_exec($ch);
			// var_dump($result);die();
			if(!curl_error($ch)) $ret = json_decode($result,true);
			else $ret = array();
			curl_close($ch);
			return $ret;
		}

	}
?>