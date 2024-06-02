<?php
/**
*
* WARNING: This is only intended for developers.
* Changing the values will result to many errors.
* Author: Telcom Live Content Inc.	
*
*/
class UserAccount extends CI_Model {	
	
	public function put($data) {
		//var_dump($data);die();
		foreach($data as $key => $val) {
			if(is_array($val)) {				
				$_SESSION['moduleKey'] = $key;
			} else {
				$_SESSION[$key] = $val;
			}			
		}
	}
	
	public function modules($moduleValue) {
		$_SESSION['sessionModules'] = $moduleValue;
	}
	
	public function getModules() {
		return $_SESSION['sessionModules'];
	}
	
	public function setSessionId($sessionId) {
		$_SESSION['sessionKey'] = $sessionId;
	}
	
	public static function getSessionId() {
		return $_SESSION['sessionKey'];
	}
}
?>