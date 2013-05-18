<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Tien
 * Date: 5/14/13
 * Time: 9:54 PM
 * To change this template use File | Settings | File Templates.
 */
class Vts_Site_Wordpress
{

    private $_basePath = SAMPLE_SITE_BASE_PATH;
    private $_endOfDb = "vtscat.com";
    private $_prex = "samplew";
    private $_prexTable = "wp_";
    private $_domain = "vtscat.com";
    private $_prexDomainSample = "samplew";
    private $_tempFolder = SAMPLE_SITE_TEMP_PATH;


    private $_build;

    public function __construct(){
        $this->_build = new Vts_Site_Build();
    }

    /**
     * Set base path
     * @author tien.nguyen
     * @param $basePath
     */
    public function setBasePath($basePath)
    {
        $this->_basePath = $basePath;
    }

    /**
     * get database name sample id
     * @author tien.nguyen
     * @param $siteSamepleId
     */
    public function getDatabaseNameSample($siteSamepleId)
    {
        return $this->_prex . $siteSamepleId . "." . $this->_endOfDb;
    }

    /**
     * Make website for wordpress
     * @author tien.nguyen
     */
    public function make($olddomain, $domain, $siteData = null)
    {
        //get and setup database site sample
        $this->setupDatabase($olddomain, $domain, $siteData);

        //copy code
        $this->copyCodeSample($olddomain, $domain);

        //change file config
        $this->changeConfigFile($olddomain, $domain);

        //generate file build
        $this->_build->setOptions(array('buildfrom' => $olddomain));
        $this->_build->generate($this->getPathRoot("site").'/'.$domain);

        return $this->checkComplete($olddomain, $domain);
    }

    /**
     * Make website for wordpress
     * @author tien.nguyen
     */
    public function duplicate($olddomain, $siteData = null)
    {
        //make domain
        $domain = $this->_prex . $this->getSampleSiteIdRandom() . "." . $this->_domain;

        //get and setup database site sample
        $this->setupDatabase($olddomain, $domain, $siteData);

        //copy code
        $this->copyCodeSample($olddomain, $domain, $this->_basePath . '/sample/wordpress/');

        //change file config
        $this->changeConfigFile($olddomain, $domain);

        //generate file build
        $this->_build->setOptions(array('buildfrom' => $olddomain));
        $this->_build->generate($this->getPathRoot("sample").'/'.$domain);

        return $this->checkComplete($olddomain, $domain, $this->_basePath . '/sample/wordpress/');
    }


    /**
     * check is complete
     * @param $siteSampleId
     * @param $domain
     * @return bool
     */
    public function checkComplete($olddomain, $domain, $pathTo = null)
    {
        $isComplete = true;

        //check exist database
        $configResource = Vts_Config::get("resources");
        $configResource = $configResource->toArray();
        $configResource['db']['params']['dbname'] = $domain;
        try {
            mysql_connect($configResource['db']['params']['host'], $configResource['db']['params']['username'],
                $configResource['db']['params']['password']);
            $isComplete = mysql_select_db($domain);
        } catch (Exception $e) {
            $isComplete = false;
        }

        //check folder code
        if (empty($pathTo)) {
            $pathTo = $this->_basePath . '/site/' . $domain;
        }
        if (!is_dir($pathTo)) {
            $isComplete = false;
        }

        return $isComplete;
    }

    /**
     * Setup database for site
     * @author tien.nguyen
     * @param $siteSampleId
     * @param $domain - string
     * @param $siteData - is object
     *  - blogname : name of site
     *  - blogdescrition: description
     *  - admin_email:  email of customer
     */
    public function setupDatabase($olddomain, $domain, $siteData)
    {
        $configResource = Vts_Config::get("resources");
        $configResource = $configResource->toArray();
        $configResource['db']['params']['dbname'] = $domain;
        $db = Zend_Db::factory($configResource['db']['adapter'], $configResource['db']['params']);
        Zend_Db_Table::setDefaultAdapter($db);

        //execute mysql copy database to create new database
        $command = "mysqldump -h " . $configResource['db']['params']['host'] .
            " -u " . $configResource['db']['params']['username'] .
            " -p" . $configResource['db']['params']['password'] . " " . $olddomain .
            " > " . $this->_tempFolder . '/' . $olddomain . ".sql";

        $commandCreate = "mysqladmin  -h " . $configResource['db']['params']['host'] .
            " -u " . $configResource['db']['params']['username'] .
            " -p" . $configResource['db']['params']['password'] . " " . "create " . $domain;

        $command2 = "mysql -h " . $configResource['db']['params']['host'] .
            " -u " . $configResource['db']['params']['username'] .
            " -p" . $configResource['db']['params']['password'] . " " . $domain . " < " . $this->_tempFolder . '/' .
            $olddomain . ".sql";

//        echo $command."<br/>";
//        echo $commandCreate."<br/>";
//        echo $command2."<br/>";
//        die;

        $res = exec($command);
        $res = exec($commandCreate);
        $res = exec($command2);

        /**
         * update some config for new domain.
         */
        //update url config, site name.
        mysql_connect($configResource['db']['params']['host'], $configResource['db']['params']['username'],
            $configResource['db']['params']['password']);
        mysql_select_db($domain);

        $options = $db->fetchAll($db->select()->from($this->_prexTable . "options"));
        foreach ($options as $row) {
            //update URL
            if (!empty($row['option_value']) && strpos($this->_sampleUrl, $row['option_value']) !== null) {
                $value = str_replace("http://" . $olddomain,
                    "http://" . $domain, $row['option_value']);

                mysql_query("UPDATE " . $this->_prexTable . "options SET option_value='" . $value .
                    "' WHERE option_name = '" . $row['option_name'] . "'");
            }
        }
        //echo "End set database...";
    }

    /**
     * Copy code to new site.
     * @author tien.nguyen
     */
    public function copyCodeSample($olddomain, $domain, $pathTo = null)
    {
        if ($pathTo) {
            exec("cp -r " . $this->_basePath . "/sample/wordpress/" . $olddomain . " " . $pathTo . $domain);
        } else {
            exec("cp -r " . $this->_basePath . "/sample/wordpress/" . $olddomain. " " .
                $this->_basePath . '/site/' . $domain);
        }
//        echo "copy code...";
    }

    /**
     * Change config file
     * @author tien.nguyen
     * @param $domain
     */
    public function changeConfigFile($olddomain, $domain)
    {
        $pathNewDomain = $this->_basePath . '/site/' . $domain;

        $configFile = $pathNewDomain . "/wp-config.php";
        if (file_exists($configFile)) {
            $string = file_get_contents($configFile);

            //replace database
            $oldDatabase = $olddomain;

            $string = str_replace("define('DB_NAME', '" . $oldDatabase . "');", "define('DB_NAME', '" . $domain . "');", $string);

            //rewrite file
            $handle = fopen($configFile, "w+");
            fwrite($handle, $string);
            fclose($handle);
        }
//        echo "copy config...";
    }

    /**
     * Get all site sample
     * @author tien.nguyen
     */
    public function getSamleSite()
    {
        $res = array();
        if ($handle = opendir($this->_basePath . '/sample/wordpress')) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != ".." && $entry != "DELETED") {

                    $siteSample = $this->_build->read($this->_basePath . '/sample/wordpress/'.$entry);
                    if(empty($siteSample)){
                        $siteSample = new stdClass();
                    }
                    $siteSample->name = $entry;
                    $res[] = $siteSample;
                }
            }
        }
        closedir($handle);
        return $res;
    }

    /**
     * Get site
     * @return array
     */
    public function getSites(){
        $res = array();
        if ($handle = opendir($this->_basePath . '/site')) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != ".." && $entry != "DELETED") {
                    $domainSite = new stdClass();
                    $domainSite->name = $entry;
                    if(file_exists($this->_basePath . '/site/'.$entry.'/build.txt')){
                        $string = file_get_contents($this->_basePath . '/site/'.$entry.'/build.txt');
                        $tmp = explode("\n", $string);
                        foreach($tmp as $item){
                            $tmp2 = explode("=", $item);
                            if(count($tmp2) == 2){
                                $domainSite->{$tmp2[0]} = $tmp2[1];
                            }
                        }
                    }

                    $res[] = $domainSite;

                }
            }
        }
        closedir($handle);
        return $res;
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

    /**
     * Download website
     * @param $domain
     * @param $type
     * @return bool|string
     */
    public function download($domain, $type){
        try {
            $newfile = $domain . "." . time();

            //copy file to data temp
            exec("cp -r " . $this->getPathRoot($type) . $domain . " " . $this->_tempFolder . "/" . $newfile);

            //make sql in data temp
            $configResource = Vts_Config::get("resources");
            $configResource = $configResource->toArray();
            $configResource['db']['params']['dbname'] = $domain;
            $sql = "mysqldump -h " . $configResource['db']['params']['host'] .
                " -u " . $configResource['db']['params']['username'] .
                " -p" . $configResource['db']['params']['password'] . " " . $domain .
                " > " . $this->_tempFolder . '/' . $domain . "/".$domain.".sql";

            //zip file and send to header
            exec("");
            return $newfile;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Get path from root
     * @param $type
     * @return string
     */
    public function getPathRoot($type)
    {
        $path = "";
        switch ($type) {
            case "sample":
                $path = $this->_basePath . '/sample/wordpress/';
                break;
            case "site":
                $path = $this->_basePath . '/site/';
                break;
        }
        return $path;
    }

    /**
     * Delete site
     * @param $path
     * @param $domain
     */
    public function delete($domain, $type){
        //rename folder
        $path = $this->getPathRoot($type);
        if(is_dir($path.'/'.$domain)){
            //make folder
            if(!is_dir($path.'/DELETED')){
                mkdir($path.'/DELETED', 0777);
            }
            exec("cp -r ".$path.'/'.$domain." ". $path.'/DELETED/'.$domain.'_'.date('Y-m-d'));
            exec("rm -rf ".$path.$domain);
        }

        //remove domain
        //to do in there

        return true;
    }
}