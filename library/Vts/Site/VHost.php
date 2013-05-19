<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Tien
 * Date: 5/19/13
 * Time: 4:33 PM
 * To change this template use File | Settings | File Templates.
 */
class Vts_Site_VHost {
    private $_tplPath;

    public function __construct(){
        $this->_tplPath = __DIR__.'/Template';
    }

    public function getString($serverName, $rootDocument, $fw = FW_DEFAULT){
        $string = file_get_contents($this->_tplPath.'/'.$fw.".".$this->getOS().".txt");
        if($string){
            $string = str_replace("{server_name}", $serverName, $string);
            $string = str_replace("{root_document}", $rootDocument, $string);
        }
        return $string;
    }

    public function getOS(){
        return "window";
    }
}