<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Tien
 * Date: 5/19/13
 * Time: 10:21 AM
 * To change this template use File | Settings | File Templates.
 */
class Vts_Util {

    /**
     * Get path from type and wordpress
     *
     * @param $type
     * @param string $fw
     * @return string
     */
    public static function getPath($type, $fw = 'wordpress'){
        if($type == "sample"){
            return SAMPLE_SITE_BASE_PATH.'/sample/'.$fw.'/';
        }else{
            return SAMPLE_SITE_BASE_PATH.'/site/';
        }
    }

    /**
     * Get class of framework
     * @param $fw
     * @return Vts_Site_Wordpress
     */
    public static function getClassType($fw){
        switch($fw){
            case FW_WORDPRESS: {
                return new Vts_Site_Wordpress();
                break;
            }
        }
    }
}