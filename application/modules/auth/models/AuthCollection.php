<?php
	class AuthCollection extends Helper {
		
		public function __construct() {
			ModelResponse::busy();
		}
		
		public function query($linkid) {
			$params['LinkId'] 				= $linkid;
			$params['RequestType'] 	=  "TESTLINK";
			$data = json_encode($params);
			$ret = parent::serviceCall("AUTH",$data);
			
			if($ret != null) {
				if($ret->Code == 0) {
					$this->ModelResponse($ret->Code, $ret->Message);
					return true;
				} else {
					$this->ModelResponse($ret->Code, $ret->Message);
				}			
			}
			return false;
		}

		public function dlink($linkid,$passcode) {
			$params['LinkId'] 				=	$linkid;
			$params['Passcode']		=	$passcode;
			$params['RequestType'] 	=	"DLINK";
			$data = json_encode($params);
			$ret = parent::serviceCall("AUTH",$data);
		
			if($ret != null) {
				if($ret->Code == 0) {
					$this->ModelResponse($ret->Code, $ret->Message);
					return true;
				} else {
					$this->ModelResponse($ret->Code, $ret->Message);
				}			
			}
			return false;
		}		
		
		public function queryGlobalNoSess($sql, $params) {
			$data = json_encode(array("Sql"=>$sql, "Params"=>$params));
			$ret = parent::serviceCall("QNOSESS",$data);
			
			if($ret != null) {
				if($ret->Code == 0) {
					if(isset($ret->Data)) {
						$this->ModelResponse($ret->Code, $ret->Message, $ret->Data);
					} else {
						$this->ModelResponse($ret->Code, $ret->Message);
					}
					return true;
				} else {
					$this->ModelResponse($ret->Code, $ret->Message);
				}
			}
			return false;
		}
		
	}
?>