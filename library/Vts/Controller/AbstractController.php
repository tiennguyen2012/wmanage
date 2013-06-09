<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Tien
 * Date: 5/19/13
 * Time: 10:54 AM
 * To change this template use File | Settings | File Templates.
 */
class Vts_Controller_AbstractController extends Zend_Controller_Action {

    public $refHref;
    /**
     * Array no check when you login
     * @author tien.nguyen
     * @var array
     */
    private $_noCheckLogin;

    public function init(){
    	parent::init();
        $this->refHref = base64_decode($this->_getParam("ref", ""));
        $this->view->ref = $this->refHref;
        
        $this->checkLogin();
    }
    
    /**
     * Get router for some controller and action no need login
     * @author tien.nguyen
     * @return array
     */
    private function _getRoutNoLogin(){
    	$res = array();
    	$res[] = array('controller' => 'user', 'action' => 'login');
    	$this->_noCheckLogin = $res;
    }
    
    /**
     * Check some controller and action
     * @param string $controller
     * @param string $action
     * @return boolean
     */
    private function _isNoCheckLogin($controller, $action = null){
    	$this->_getRoutNoLogin();
    	foreach ($this->_noCheckLogin as $item){
    		$flag = false;
    		if(!empty($item['controller']) && !empty($item['action']) ){
    			if($controller == $item['controller'] &&
    			$action == $item['action']){
    				return TRUE;
    			}
    		}elseif(!empty($item['controller'])){
    			if($item['controller'] == $controller){
    				return TRUE;
    			}
    		}
    	}
    	return FALSE;
    }
    
    /**
     * function check login. Check controller and action "no check login"
     * Get session login. If session is existed return true else return false
     * if no login redirect to login.
     * @author tien.nguyen
     */
    public function checkLogin(){
    	$controller = $this->_request->getControllerName();
    	$action = $this->_request->getActionName();
    
    	$resNoCheck = $this->_isNoCheckLogin($controller, $action);
    	if(!$resNoCheck){
    		$vtsUser = new Vts_User();
    		if(!$vtsUser->isLogined())
    			$this->_redirect($this->view->url(array('controller' => 'user', 'action' => 'login')));
    	}
    }
}