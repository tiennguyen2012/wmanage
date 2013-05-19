<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Tien
 * Date: 5/13/13
 * Time: 11:05 PM
 * To change this template use File | Settings | File Templates.
 */
class Vts_Controller_AbstractApiController extends Zend_Controller_Action {

    public function init(){
        //check username and password
        $username = $this->_getParam('user');
        $password = $this->_getParam('pass');

        if($username && $password){
            $apiConfig = Vts_Config::get("api");
            if($username != $apiConfig->username || $password != $apiConfig->password){
                echo json_encode(array('result' => false, 'message' => "Username and Password is invalid."));
                die;
            }
        }else{
            echo json_encode(array('result' => false, 'message' => "Username and Password is empty."));
            die;
        }

    }
}