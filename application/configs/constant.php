<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Tien
 * Date: 5/18/13
 * Time: 2:19 PM
 * To change this template use File | Settings | File Templates.
 */
/**
 * This file will insert index.php after APPLICATION_ENV
 * and will change when upload server
 */
if(APPLICATION_ENV == 'production'){
    define('SAMPLE_SITE_BASE_PATH', '/home/web/sites/vtscat.com');
    define('SAMPLE_SITE_TEMP_PATH', '/home/web/sites/vtscat.com/data/temp');
}else{
    define('SAMPLE_SITE_BASE_PATH', 'E:/xampp/htdocs/vtssoft/vtscat.com');
    define('SAMPLE_SITE_TEMP_PATH', 'E:/xampp/htdocs/vtssoft/vtscat.com/data/temp');
}