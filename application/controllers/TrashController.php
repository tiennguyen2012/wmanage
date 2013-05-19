<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Tien
 * Date: 5/19/13
 * Time: 8:56 AM
 * To change this template use File | Settings | File Templates.
 */
class TrashController extends Zend_Controller_Action {

    /**
     * @var Application_Model_Trash
     */
    private $_trash;

    public function init(){
        parent::init();
        $this->_trash = new Application_Model_Trash();
    }

    public function sampleAction(){
        $fw = $this->_getParam("fw", FW_DEFAULT);
        $samples = $this->_trash->getSampleTrash($fw);

        $this->view->samples = $samples;
    }

    public function siteAction(){
        $fw = $this->_getParam("fw", FW_DEFAULT);
        $sites = $this->_trash->getSiteTrash($fw);

        $this->view->sites = $sites;
    }

    public function deleteAction(){
        $domain = $this->_getParam("domain");
        $fw = $this->_getParam("fw", FW_DEFAULT);
        $type = $this->_getParam("type", TYPE_SAMPLE);

        if($domain && $fw && $type){
            $objSite = Vts_Util::getClassType($fw);
            $res = $objSite->delete($domain, $type);
            if($res){
                $this->_redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }
}