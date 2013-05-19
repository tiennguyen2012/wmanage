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

    public function init(){
        $this->refHref = base64_decode($this->_getParam("ref", ""));
        $this->view->ref = $this->refHref;
    }
}