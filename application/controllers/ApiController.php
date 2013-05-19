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
     * Create site for sample
     * @author tien.nguyen
     */
    public function createAction(){
        $oldDomain = $this->_getParam("olddomain");
        $domain = $this->_getParam("domain");
        $fw = $this->_getParam("fw", FW_DEFAULT);

        $result = new stdClass();
        $result->result = false;

        if($oldDomain && $domain){
            $objSite = Vts_Util::getClassType($fw);
            $result->result = $objSite->make($oldDomain, $domain);
        }
        echo json_encode($result);
    }

    /**
     * Duplicate web site for sample.
     * @author tien.nguyen
     */
    public function duplicateAction(){
        $oldDomain = $this->_getParam("olddomain");
        $fw = $this->_getParam("fw", FW_DEFAULT);

        $result = new stdClass();
        $result->result = false;

        if($oldDomain){
            $objSite = Vts_Util::getClassType($fw);
            $result->result = $objSite->duplicate($oldDomain);
        }

        echo json_encode($result);
    }

    /**
     * Delete site
     */
    public function removeAction(){
        $domain = $this->_getParam("domain");
        $type = $this->_getParam("type", TYPE_SAMPLE);
        $fw = $this->_getParam('fw', FW_DEFAULT);

        $result = new stdClass();
        $result->result = false;

        if($domain && $type && $fw){
            $objSite = Vts_Util::getClassType($fw);
            $result->result = $objSite->remove($type, $domain);
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