<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Tien
 * Date: 5/15/13
 * Time: 10:02 PM
 * To change this template use File | Settings | File Templates.
 */
class WordpressController extends Zend_Controller_Action {

    /**
     * @var Vts_Site_Wordpress
     */
    private $_wordpress;

    public function init(){
        parent::init();
        $this->_wordpress =  new Vts_Site_Wordpress();
    }


    public function sampleAction(){
        $samples = $this->_wordpress->getSamples();

        $this->view->samples = $samples;
    }

    public function siteAction(){
        $sites = $this->_wordpress->getSites();

        $this->view->sites = $sites;
    }

    public function downloadAction(){

    }
}