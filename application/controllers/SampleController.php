<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Tien
 * Date: 5/15/13
 * Time: 10:02 PM
 * To change this template use File | Settings | File Templates.
 */
class SampleController extends Zend_Controller_Action {

    public function wordpressAction(){
        $vtsWordpress = new Vts_Site_Wordpress();
        $sampleSites = $vtsWordpress->getSamleSite();

        $this->view->sampleSites = $sampleSites;
    }

    public function siteWordpressAction(){
        $vtsWordpress = new Vts_Site_Wordpress();
        $sites = $vtsWordpress->getSites();

        $this->view->sites = $sites;
    }

    public function downloadAction(){

    }
}