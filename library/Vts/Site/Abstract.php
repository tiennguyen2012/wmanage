<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Tien
 * Date: 5/19/13
 * Time: 9:54 AM
 * To change this template use File | Settings | File Templates.
 */
class Vts_Site_Abstract {

    protected $_basePath = SAMPLE_SITE_BASE_PATH;
    protected $_tempFolder = SAMPLE_SITE_TEMP_PATH;

    public function delete($type, $domain, $fw = FW_DEFAULT){
        //delete code folder
        $path = $this->getPathRoot($type, $fw);
        exec("rm -rf ".$path.'/'.FOLDER_DELETED.'/'. $domain);

        //drop database
        $database = new Vts_Site_Database();
        $dbName = substr($domain, 0, strlen($domain) - 11); //remove date in last of name
        return $database->drop($dbName);
    }

    public function remove($type, $domain, $fw = FW_DEFAULT){
        //rename folder
        $path = $this->getPathRoot($type, $fw);
        if(is_dir($path.'/'.$domain)){
            //make folder
            if(!is_dir($path.'/'.FOLDER_DELETED)){
                mkdir($path.'/'.FOLDER_DELETED, 0777);
            }
            //copy to folder remove.
            exec("cp -r ".$path.'/'.$domain." ". $path.'/'.FOLDER_DELETED.'/'.$domain.'_'.date('Y-m-d'));
            exec("rm -rf ".$path.$domain);
        }
        return true;
    }

    /**
     * Get path from root
     *
     * @param $type
     * @return string
     */
    public function getPathRoot($type, $framework = FW_DEFAULT){
        $path = "";
        switch ($type) {
            case TYPE_SAMPLE:
                $path = $this->_basePath . '/'.TYPE_SAMPLE.'/'.$framework.'/';
                break;
            case TYPE_SITE:
                $path = $this->_basePath . '/'.TYPE_SITE.'/';
                break;
        }
        return $path;
    }

    /**
     * Get site number by random
     * @return int
     */
    public function getSampleSiteIdRandom()
    {
        $int = rand(1, 1000000);
        while (strlen($int) < 7) {
            $int = "0" . $int;
        }
        return $int;
    }
}