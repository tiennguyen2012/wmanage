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
        $siteSampleId = $this->_getParam("sample-id");
        $domain = $this->_getParam("domain");

        $result = new stdClass();

        if($siteSampleId && $domain){
            $vtsWordpress = new Vts_Site_Wordpress();
            $vtsWordpress->make($siteSampleId, $domain);
        }

        echo json_encode($result);
    }
}