<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Tien
 * Date: 5/19/13
 * Time: 10:54 AM
 * To change this template use File | Settings | File Templates.
 */
class SiteController extends Vts_Controller_AbstractController {

    /**
     * @var Application_Model_Site
     */
    private $_site;

    public function init(){
        parent::init();
        $this->_site = new Application_Model_Site();
    }

    public function siteAction(){
        $fw = $this->_getParam('fw', FW_DEFAULT);
        $sites = $this->_site->getSites($fw);

        $this->view->sites = $sites;
    }

    public function sampleAction(){
        $fw = $this->_getParam('fw', FW_DEFAULT);
        $samples = $this->_site->getSamples($fw);

        $this->view->samples = $samples;
    }

    public function removeAction(){
        $fw = $this->_getParam('fw', FW_DEFAULT);
        $domain = $this->_getParam("domain");
        $type = $this->_getParam('type');

        if($fw && $domain && $type){
            $objSite = Vts_Util::getClassType($fw);
            $res = $objSite->remove($type, $domain, $fw);
            if($res){
                $this->_redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }

    public function createAction(){
        $fw = $this->_getParam('fw', FW_DEFAULT);
        $oldDomain = $this->_getParam("olddomain");
        $domain = $this->_getParam("domain");

        if($fw && $oldDomain && $domain){
            $objSite = Vts_Util::getClassType($fw);
            $res = $objSite->make($oldDomain, $domain);
            if($res){
                $this->_redirect('/site/success/ref/'.$this->_getParam('ref'));
            }
        }
    }

    public function duplicateAction(){
        $oldDomain = $this->_getParam("olddomain");
        $fw = $this->_getParam('fw', FW_DEFAULT);

        if($oldDomain){
            $objSite = Vts_Util::getClassType($fw);
            $result = $objSite->duplicate($oldDomain);
            if($result)
                $this->_redirect("/site/sample");
        }
    }

    public function successAction(){
        $domain = $this->_getParam("domain");

        $this->view->domain = $domain;
    }

    public function deleteAction(){
        $domain = $this->_getParam('domain');
        $fw = $this->_getParam('fw', FW_DEFAULT);
        $type = $this->_getParam('type');

        if($domain && $fw && $type){
            $res = $this->_site->delete($type, $domain, $fw);
            if($res){
                $this->_redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }
    
    /**
     * Action download file
     * @author tien.nguyen
     */
    public function downloadAction(){
    	$domain = $this->_getParam('domain');
    	$fw = $this->_getParam('fw', FW_DEFAULT);
    	$type = $this->_getParam('type');
    	if($domain && $fw && $type){
    		// download file and get path
    		$res = $this->_site->download($type, $domain, $fw);
    		
    		// set header zip file
    		
    		// print content of file
    		echo file_get_contents($res);
    	}
    	
    }
}