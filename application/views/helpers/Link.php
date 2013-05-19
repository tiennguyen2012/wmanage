<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Tien
 * Date: 5/19/13
 * Time: 12:17 PM
 * To change this template use File | Settings | File Templates.
 */
class Zend_View_Helper_Link extends Zend_View_Helper_Url {

    public function link(array $urlOptions = array(), $name = null, $reset = false, $encode = true){
        $urlOptions['ref'] = base64_encode($_SERVER['REQUEST_URI']);
        return parent::url($urlOptions, $name, $reset, $encode);
    }
}