<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Tien
 * Date: 5/19/13
 * Time: 8:58 AM
 * To change this template use File | Settings | File Templates.
 */
class Application_Model_Trash {

    /**
     * @var Vts_Site_Trash
     */
    private $_trash;

    public function __construct(){
        $this->_trash = new Vts_Site_Trash();
    }

    public function getSampleTrash($fw = FW_DEFAULT){
        return $this->_trash->getAllDeleteByPath(Vts_Util::getPath(TYPE_SAMPLE));
    }

    public function getSiteTrash($fw = FW_DEFAULT){
        return $this->_trash->getAllDeleteByPath(Vts_Util::getPath(TYPE_SITE));
    }
}