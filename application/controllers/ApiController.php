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
        $result->result = false;

        if($siteSampleId && $domain){
            $vtsWordpress = new Vts_Site_Wordpress();
            $result->result = $vtsWordpress->make($siteSampleId, $domain);
        }

        echo json_encode($result);
    }

    /**
     * Duplicate web site for sample.
     */
    public function duplicateWordpressAction(){
        $siteSampleId = $this->_getParam("sample-id");

        $result = new stdClass();
        $result->result = false;

        if($siteSampleId){
            $vtsWordpress = new Vts_Site_Wordpress();
            $result->result = $vtsWordpress->duplicate($siteSampleId);
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