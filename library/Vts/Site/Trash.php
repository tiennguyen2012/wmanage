<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Tien
 * Date: 5/19/13
 * Time: 8:59 AM
 * To change this template use File | Settings | File Templates.
 */
class Vts_Site_Trash {

    /**
     * @var Vts_Site_Directory
     */
    private $_directory;

    public function __construct(){
        $this->_directory = new Vts_Site_Directory();
    }

    public function getAllDeleteByPath($path){
        $result = array();
        if(is_dir($path.'DELETED')){
            $result = $this->_directory->read($path.'DELETED', null, true);
        }
        return $result;
    }
}