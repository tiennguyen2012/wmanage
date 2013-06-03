<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');
set_time_limit(20000);
//require_once "E:/xampp/htdocs/vtssoft/vtscat.com/system/mana.vtscat.com/library/Vts/Site/Database.php";
//$ac = new Vts_Site_Database("localhost", "root", "123456");
//echo $ac->isExistDatabase("vtscat.com");

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    'D:\ZendFramework-1.11.7\library',
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

require_once APPLICATION_PATH.'/configs/constant.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()
            ->run();