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
        $olddomain = $this->_getParam("olddomain");
        $domain = $this->_getParam("domain");

        $result = new stdClass();
        $result->result = false;

        if($olddomain && $domain){
            $vtsWordpress = new Vts_Site_Wordpress();
            $result->result = $vtsWordpress->make($olddomain, $domain);
        }

        echo json_encode($result);
    }

    /**
     * Duplicate web site for sample.
     */
    public function duplicateWordpressAction(){
        $olddomain = $this->_getParam("olddomain");

        $result = new stdClass();
        $result->result = false;

        if($olddomain){
            $vtsWordpress = new Vts_Site_Wordpress();
            $result->result = $vtsWordpress->duplicate($olddomain);
        }

        echo json_encode($result);
    }

    /**
     * Delete site
     */
    public function deleteWordpressAction(){
        $domain = $this->_getParam("domain");
        $type = $this->_getParam("type");
//        Zend_Debug::dump($this->_getAllParams()); die;
        $result = new stdClass();
        $result->result = false;

        if($domain && $type){
            $vtsWordpress = new Vts_Site_Wordpress();
            $result->result = $vtsWordpress->delete($domain, $type);
        }

        echo json_encode($result);
    }

    /**
     * Download website with zip and database
     */
    public function downloadAction(){
        $domain = $this->_getParam('domain');
        $typeSite = $this->_getParam('type');

        //check exist domain and type of site
        if($domain && $typeSite){
            $vtsWordpress = new Vts_Site_Wordpress();
            $fileName = $vtsWordpress->download($domain, $typeSite);
            if($fileName){
                $file = file_get_contents(SAMPLE_SITE_TEMP_PATH.'/'.$fileName);
                echo $file;
            }
        }
    }
}