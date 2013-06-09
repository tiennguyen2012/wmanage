<?php
class Vts_User {
	
	/**
	 * Is login system. by check session.
	 * @author tien.nguyen
	 * @return boolean
	 */
	public function isLogined(){
		$default = new Zend_Session_Namespace('default');
		if($default->loginMana)
			return TRUE;
		else
			return FALSE;
	}
	
	/**
	 * Login system by user name and password set by CONSTANT
	 * @author tien.nguyen
	 * @param string $username
	 * @param string $password
	 * @return boolean
	 */
	public function login($username, $password){
		if($username == USERNAME && $password == PASSWORD){
			$default = new Zend_Session_Namespace('default');
			$user = new stdClass();
			$user->Username = USERNAME;
			$user->Password = PASSWORD;
			$default->loginMana = $user;
			
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	/**
	 * Logout system. set session user login is null
	 * @return boolean
	 */
	public function logout(){
		$default = new Zend_Session_Namespace('default');
		$default->loginMana = null;
		return TRUE;
	}
}