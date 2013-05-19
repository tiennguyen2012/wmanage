<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Tien
 * Date: 5/19/13
 * Time: 9:01 AM
 * To change this template use File | Settings | File Templates.
 */
class Vts_Site_Directory {

    private $_build;

    public function __construct(){
        $this->_build = new Vts_Site_Build();
    }

    public function read($path, $properties = null, $isBuild = false, $notShow = array()){
        if(is_dir($path)){
            $res = array();

            $res = array();
            if ($handle = opendir($path)) {
                while (false !== ($entry = readdir($handle))) {
                    //not add folder . .. notshow
                    if ($entry != "." && $entry != ".." && !in_array(strtoupper($entry), $notShow)) {

                        //create new item to get name
                        $item = new stdClass();
                        $item->name = $entry;

                        //add file build
                        if($isBuild){
                            $buildProperties = $this->_build->read($path.'/'.$entry);
                            if($buildProperties){
                                foreach($buildProperties as $k => $v){
                                    $item->{$k} = $v;
                                }
                            }
                        }

                        //merge properties
                        if(!empty($properties)){
                            foreach($properties as $k => $v){
                                $item->{$k} = $v;
                            }
                        }

                        //add item
                        $res[] = $item;
                    }
                }
            }
            closedir($handle);
            return $res;

        }else{
            echo "Path ".$path." not exist";
            return false;
        }
    }
}