<?php
/**
*
* WARNING: This is only intended for developers.
* Changing the values will result to many errors.
* Author: Telcom Live Content Inc.	
*
*/

session_name($GLOBALS['project']);
session_start();
date_default_timezone_set('UTC');
date_default_timezone_set('Asia/Manila');
class Helper extends ModelResponse {
	
	private static $title = null;
	private static $view = null;
	private static $viewjs = null;
	private static $template = null;
	private static $menu = null;
	private static $content = null;
	
	const SESSION_STARTED = TRUE;
	const SESSION_ENDED = FALSE;
	
	public static function instance() {
		return $CI =& get_instance();
	}
	
	public static function setTitle($title) {
		Helper::$title = $title;
	}
	
	public static function getTitle() {
		return Helper::$title;
	}
	
	public static function setView($view, $data = "", $bool = TRUE) {
		ob_start();
		Helper::instance()->load->view($view.'.php', $data);
		Helper::$view = ob_get_contents();
		ob_end_clean();
		
		if($bool) {
			ob_start();
			$viewjs = $view.'.js.php';
			Helper::instance()->load->view($viewjs);
			$this->viewjs = ob_get_contents();
			ob_end_clean();
		}		
	}
	
	public static function getVIew() {
		return Helper::$view . Helper::$viewjs;
	}
	
	public static function setMenu($menu) {
		$m = Helper::instance()->load->view($menu,'',TRUE);
		Helper::$menu = $m;
	}
	
	public static function getMenu() {
		return Helper::$menu;
	}
	
	public static function setTemplate($template) {
		if(self::getView() != null) {
			$content['title'] = self::getTitle();
			$content['menu'] = self::getMenu();
			$content['content'] = self::getVIew();
			Helper::$content = $content;
			Helper::instance()->load->view($template, $content);
		} else {
			show_error("Please set the content first before setting the template");
		}		
	}	
	
	public static function setSessionInstance($bool) {
		if($bool == TRUE) {
			$_SESSION["sessionState"] = self::SESSION_STARTED;
		} else {
			$_SESSION["sessionState"] = self::SESSION_ENDED;
		}
	}	
	
	private static function getSessionInstance() {
		return isset($_SESSION["sessionState"]) &&
							$_SESSION["sessionState"] == TRUE ?
								self::SESSION_STARTED : 
								self::SESSION_ENDED;
	}
	
	public static function sessionStart() {
		self::setSessionInstance(TRUE);
		redirect();
	}
	
	public static function sessionTerminate() {
		self::setSessionInstance(FALSE);
		session_destroy();
		redirect();
	}
	
	public static function sessionStartedHook($indexpage) {
		if(self::getSessionInstance()) redirect($indexpage);
	}
	
	public static function sessionEndedHook($loginpage) {
		if(!self::getSessionInstance()) {
			redirect($loginpage);
		}
	}
	
	private function setSessionLock($bool) {
		if($bool == TRUE) {
			$_SESSION["sessionLock"] = TRUE;
		} else {
			$_SESSION["sessionLock"] = FALSE;
		}
	}
	
	private function getSessionLock() {
		return isset($_SESSION["sessionLock"]) &&
							$_SESSION["sessionLock"] == TRUE ?
								TRUE : FALSE;
	}
	
	public static function sessionLockHook($lockpage) {
		if(self::getSessionLock()) redirect($lockpage);
	}
	
	public static function sessionUnlockHook($homepage) {
		if(!self::getSessionLock()) redirect($homepage);
	}
	
	public static function sessionLock() {
		self::setSessionLock(TRUE);
		redirect();
	}
	
	public static function sessionUnlock() {
		self::setSessionLock(FALSE);
	}
	
	public static function put($key, $val) {
		$_SESSION[$key] = $val;
	}
	
	public static function get($key = "") {
		if(isset($_SESSION[$key])) {
			return $_SESSION[$key];
		} else {
			return "";
		}
	}
	
	public static function Model() {
		return Helper::instance()->Model;
	}
	
	protected function getToken() {
		$salt = $GLOBALS['project'];
		$tokenstr = $salt .":". date('mdY') .":". $_SERVER['HTTP_HOST'];
		$token = md5($tokenstr);
		return $token;
	}
	
	public static function role($userrole) {
		if(in_array($userrole,Helper::Model()->UserAccount()->getModules())) return true;
		return false;
	}
	
	public static function rolehook($userrole) {
		if(!self::role($userrole)) {
			redirect();
			return true;
		}
		return false;
	}
	
	public static function serviceCall($method, $data = "") {
		$ch = curl_init();
		$data = array("method"=>$method,"data"=>$data);
		curl_setopt($ch, CURLOPT_URL, $GLOBALS['service_url']);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
		curl_setopt($ch, CURLOPT_POST, count($data));		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 3000);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		$ret = json_decode(curl_exec($ch));
		if(!curl_error($ch)) return $ret;
		curl_close($ch);
	}
	public function decrypt($value,$salt){
		$CI =$this->instance();
		$CI->load->database();
		if($value != NULL){
			$sql = "SELECT DECRYPTER(?,?,?) AS decrypted";
			$query = $query = $this->db->query($sql, array((string)$value,'sunev8clt1234567890',(string)$salt));
			$result = $query->row_array();
			return $result['decrypted'];
		}else{
			return "";
		}
	}
	public function encrypt($value,$salt){
		$CI =$this->instance();
		$CI->load->database();
		$sql = "SELECT ENCRYPTER(?,?,?) AS encrypted";
		$query = $query = $this->db->query($sql, array($value,'sunev8clt1234567890',$salt));
		$result = $query->row_array();
		return $result['encrypted'];
	}
}

class ModelResponse extends CI_Model {
	
	private $code = null;
	private $message = null;
	private $DATA = array();
	private $ret = null;
	
	public function __construct($code = "", $message = "", $data = array()) {
		parent::__construct();
		$this->load->helper('url');
		$this->load->model('Model');
		
		$this->code = $code;
		$this->message = $message;
		$this->DATA = $data;
		if(sizeof($data) > 0) {
			$this->ret = json_encode(array("Code"=>$this->code, "Message"=>$this->message, "Data"=>$this->DATA));
		} else {
			$this->ret = json_encode(array("Code"=>$this->code, "Message"=>$this->message));
		}
		return $this->ret;
	}
	
	public function busy() {
		$this->code = 99;
		$this->message = "The system is currently experiencing some errors. Please try again later.";
		$this->ret = json_encode(array("Code"=>$this->code,"Message"=>$this->message));
	}
	
	public function ModelResponse($code = "", $message = "", $data = array()) {
		$this->code = $code;
		$this->message = $message;
		$this->DATA = $data;
		if($data) {
			$this->ret = json_encode(array("Code"=>$this->code, "Message"=>$this->message, "Data"=>$this->DATA));
		} else {
			$this->ret = json_encode(array("Code"=>$this->code, "Message"=>$this->message));
		}
	}
	
	public function setCode($val) {
		$this->code = $val;
	}
	
	public function getCode() {
		return $this->code;
	}
	
	public function setMessage($val) {
		$this->message = $val;
	}
	
	public function getMessage() {
		return $this->message;
	}
	
	public function setData($val) {
		$this->DATA = $val;
	}
	
	public function getData() {
		return $this->DATA;
	}
	
	public function __toString() {
		return $this->ret;
	}
	
}
?>