<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Tien
 * Date: 5/13/13
 * Time: 11:03 PM
 * To change this template use File | Settings | File Templates.
 */
class ApiController extends Vts_Controller_AbstractApiController {

    /**
     * Create init
     */
    public function init(){
        parent::init();
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
    }

    /**
     * Create wordpress api
     */
    public function createWordpressAction(){
        $result = new stdClass();


        echo json_encode($result);
    }
}