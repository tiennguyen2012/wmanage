<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Tien
 * Date: 5/19/13
 * Time: 10:58 AM
 * To change this template use File | Settings | File Templates.
 */
class Application_Model_Site {

    private $_directory;

    public function __construct(){
        $this->_directory = new Vts_Site_Directory();
    }

    /**
     * @param string $fw
     * @return array|bool
     */
    public function getSamples($fw = FW_DEFAULT){
        return $this->_directory->read(Vts_Util::getPath(TYPE_SAMPLE, $fw), null, true, array(FOLDER_DELETED));
    }

    public function getSites($fw = FW_DEFAULT){
        return $this->_directory->read(Vts_Util::getPath(TYPE_SITE, $fw), null, true, array(FOLDER_DELETED));
    }

    public function make($oldDomain, $domain, $fw = FW_DEFAULT ){
        $objSite = Vts_Util::getClassType($fw);
        $res = $objSite->make($oldDomain, $domain);
        return $res;
    }

    public function remove($type, $domain, $fw = FW_DEFAULT){
        $objSite = Vts_Util::getClassType($fw);
        $res = $objSite->remove($type, $domain, $fw);
        return $res;
    }

    public function delete($type, $domain, $fw = FW_DEFAULT){
        $objSite = Vts_Util::getClassType($fw);
        $res = $objSite->delete($type, $domain, $fw);
        return $res;
    }
}