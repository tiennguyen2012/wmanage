<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Tien
 * Date: 5/14/13
 * Time: 10:06 PM
 * To change this template use File | Settings | File Templates.
 */
class Vts_Config {

    public static function get($name){
        $config = new Zend_Config_Ini(APPLICATION_PATH.'/configs/application.ini', APPLICATION_ENV);
        if(isset($config->{$name})){
            return $config->{$name};
        }
        return null;
    }
}