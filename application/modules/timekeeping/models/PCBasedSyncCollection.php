<?php
	class PCBasedSyncCollection extends Helper {
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
		var $table = "tbltimekeepinglogs";
      	var $order_column = array('source_device','username','tbltimekeepinglogs.date_created');
      	public function getColumns(){

      		$sql = " SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='".$this->table."' AND TABLE_SCHEMA='".$this->db->database."' ";
			$query = $this->db->query($sql);
			$rows = $query->result_array();
			return $rows;
      	}
		function make_query()
		{
			//var_dump($this->select_column);die();
			$this->select_column[] = 'tblwebusers.username';
		
		    $this->db->select(
		    	$this->table.'.*,
		    	tblwebusers.*'
		    );

		    $this->db->from($this->table);
		    $this->db->join("tblwebusers",$this->table.".created_by = tblwebusers.userid","left");
		    $this->db->where($this->table.".source_device = 'PC Based'");
		   
    	    if(isset($_POST["search"]["value"]))
		    {
		    	$this->db->group_start();

		    		//var_dump($this->select_column);die();
		     	foreach ($this->select_column as $key => $value) {
		     		//$this->db->like($value, $_POST["search"]["value"]);
		     		//var_dump($value == "$this->table.first_name");
		     		$this->db->or_like($value, $_POST["search"]["value"]);

		     	}
		        $this->db->group_end();
		    }
		    if(isset($_POST["order"]))
		    {
		          $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		    }
		    else
		    {
		          $this->db->order_by('id', 'DESC');
		    }
		}
		function make_datatables(){
		    $this->make_query();
		    if($_POST["length"] != -1)
		    {
		        $this->db->limit($_POST['length'], $_POST['start']);
		    }

		    $query = $this->db->get();

		    // var_dump($this->db->last_query());die();
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
		    $this->db->where($this->table.".source_device = 'PC Based'");
		   
		    return $this->db->count_all_results();
		}

		public function addPcSyncing($params) {
			$code = "1";
			$message = "Failed to sync pc based.";
			// File path
			$target_path = "C:/xampp/htdocs/MMDA/PAYROLL/assets/mdfilessynced/";
			$helperDao = new HelperDao();
			//Execute the command
			// var_dump(file_exists($target_path . "masterfiles.mdb")	);die();
			// Check if all the 3 mdb files exists
			if (file_exists($target_path . "masterfiles.mdb") == true && file_exists($target_path . "timekeeper.mdb") == true && file_exists($target_path . "translog.mdb") == true) {
				// die('check');
			    // Open files then read databases
			    $timekeeper_connect = new PDO('odbc:DRIVER={Microsoft Access Driver (*.mdb)}; DBQ='. realpath($target_path . "timekeeper.mdb"),'Admin','@CCESSP@ssw0rd');
			    $masterfile_connect = new PDO('odbc:DRIVER={Microsoft Access Driver (*.mdb)}; DBQ='. realpath($target_path . "masterfiles.mdb"),'Admin','@CCESSP@ssw0rd');
			    $translog_connect 	= new PDO('odbc:DRIVER={Microsoft Access Driver (*.mdb)}; DBQ='. realpath($target_path . "translog.mdb"),'Admin','@CCESSP@ssw0rd');

			    // Fetch time records
			    // var_dump(Date('Ymd') . " ".Date('His',strtotime('-1 hour')));die();
			    // $date = Date('Ymd',strtotime('-4 days')) . Date('His');
			    $date1 =  date('Ymd',strtotime(@$params['date_from'])).'000000';
			    $date2 =  date('Ymd',strtotime(@$params['date_to'])).'235959';
			     //$date2 = '20190610235959';
				// $date = Date('Ymd') . Date('His'); 
			    //var_dump($date);die();
			    $timekeeper_sql  	= " SELECT * FROM timecard ";
			    $timekeeper_sql   .= ' WHERE trandate + trantime >= '.$date1;
			    $timekeeper_sql   .= ' AND trandate + trantime <= '.$date2;
				//$timekeeper_sql 	.= " WHERE trandate between '20190506' and '20190506'";
			    $timekeeper_sql 	.= ' ORDER BY trandate DESC ';
				// var_dump($timekeeper_sql); die();
			    $timekeeper_result 	= $timekeeper_connect->query($timekeeper_sql);
			    //var_dump($timekeeper_result);die();

			    
				$timekeeper_data = $timekeeper_result->fetchAll();
				//var_dump($timekeeper_data[0]);die();
				$log_id = -50;//Log id for pc base auto syncing
			    // Check if there is attendance data
			    if (isset($timekeeper_data) && sizeof($timekeeper_data) > 0) {
			    	/*highlight_string("<?php\n\$data =\n" . var_export($timekeeper_data, true) . ";\n?>");die();*/
			    

			    	// Loop through each timekeeping records
			    	$data = array();
			    	$sqldata = array();
			    	$iddata = array();
			        // Create insert array
			    	foreach ($timekeeper_data as $k => $v) {
			    		
			    			switch ($v['trantype']) {
			    				case 'A':
			    				$type = 0;
			    				break;
			    				case 'B':
			    				$type = 2;
			    				break;
			    				case 'Z':
			    				$type = 1;
			    				break;
			    				default:
			    				$type = null;
			    				break;
			    			}
			    			$masterfile_sql  		= "SELECT idno FROM employee WHERE employeeid = " . $v['employeeid'];
			    			$masterfile_result 	= $masterfile_connect->query($masterfile_sql);

			    			$employee_id 			= $masterfile_result->fetch();
			    			$data[] = array(
			    				"log_id"						=> $log_id,
			    				"employee_number" 	=> $employee_id['idno'],
			    				"transaction_date" 	=> date("Y-m-d", strtotime($v["trandate"])),
			    				"transaction_time" 	=> date("H:i:s", strtotime($v["trantime"])),
			    				"transaction_type" 	=> $type
			    			);
			    			$iddata[] =  '("'.$v["timecardid"].'")';
			    			$sqldata[] = '("'.$log_id.'", "'.$employee_id['idno'].'", "'.date("Y-m-d", strtotime($v["trandate"])).'", "'.date("H:i:s", strtotime($v["trantime"])).'", "'.$type.'" )';
			    	}
					//Validate if existing id
			        // Insert batch
			        // $this->db->insert_batch('tbltimekeepingdailytimerecord', $result);
			        // var_dump(sizeof($sqldata));die();
			        
			        if(sizeof($sqldata) > 0){
			        	$this->db->trans_begin();
			        	$keys = array_keys($data[0]);
			        	$total_divide = sizeof($sqldata) / 2000;

			        	$chunked_data = array_chunk($sqldata,ceil($total_divide),true);

			        	foreach ($chunked_data as $k3 => $v3) {
			        		
			        		$sql = "INSERT INTO tbltimekeepingdailytimerecord(".implode(',',$keys).") VALUES ".implode(',', $v3);
			        		$this->db->query($sql);

			        	}
			        	$sql2 = "INSERT INTO tbltimekeepinglogs(source_device,created_by)VALUES('PC Based','".Helper::get('userid')."')";
		        		$this->db->query($sql2);
						if($this->db->trans_status() === FALSE){
							$this->db->trans_rollback();
							$helperDao->auditTrails(Helper::get('userid'),$message);
							$this->ModelResponse($code, $message);	
							// Close PDO connections
					        $masterfile_connect = null;
					        $timekeeper_connect = null;
					        $translog_connect = null;	
						}	
						else {
							$code = "0";
							$this->db->trans_commit();
							$message = "Successfully imported pc based time logs.";
							$helperDao->auditTrails(Helper::get('userid'),$message);
							$this->ModelResponse($code, $message);
							// Close PDO connections
							$masterfile_connect = null;
							$timekeeper_connect = null;
							$translog_connect = null;
							return true;
						}
					}
					else{
						$this->db->trans_rollback();
						$helperDao->auditTrails(Helper::get('userid'),$message);
						$this->ModelResponse($code, $message);	
						// Close PDO connections
				        $masterfile_connect = null;
				        $timekeeper_connect = null;
				        $translog_connect = null;
					}
					return false; 
			    }
			}
			else{
				$this->ModelResponse($code, $message);
			}
		}
	}
?>