<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Tien
 * Date: 5/14/13
 * Time: 9:54 PM
 * To change this template use File | Settings | File Templates.
 */
class Vts_Site_Wordpress extends Vts_Site_Abstract{

    private $_endOfDb = "vtscat.com";
    private $_prex = "samplew";
    private $_prexTable = "wp_";
    private $_domain = "vtscat.com";
    private $_prexDomainSample = "samplew";

    private $_build;
    private $_directory;

    public function __construct(){
        $this->_build = new Vts_Site_Build();
        $this->_directory = new Vts_Site_Directory();
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
     * Make website for wordpress
     * @author tien.nguyen
     */
    public function make($olddomain, $domain, $siteData = null){
        //get and setup database site sample
        $this->setupDatabase($olddomain, $domain, $siteData);
        //copy code
        $this->copyCodeSample($olddomain, $domain);
        //change file config
        $this->changeConfigFile($olddomain, $domain, TYPE_SITE);
        //generate file build
        $this->_build->setOptions(array('buildfrom' => $olddomain));
        $this->_build->generate($this->getPathRoot("site").'/'.$domain);
        return $this->checkComplete($olddomain, $domain);
    }

    /**
     * Make website for wordpress
     * @author tien.nguyen
     */
    public function duplicate($olddomain, $siteData = null){
        //make domain
        $domain = $this->_prex . $this->getSampleSiteIdRandom() . "." . $this->_domain;
        //get and setup database site sample
        $this->setupDatabase($olddomain, $domain, $siteData);
        //copy code
        $this->copyCodeSample($olddomain, $domain, $this->_basePath . '/sample/wordpress/');
        //change file config
        $this->changeConfigFile($olddomain, $domain, TYPE_SAMPLE);
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
    public function checkComplete($olddomain, $domain, $pathTo = null)    {
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
            exec("cp -r " . $this->getPathRoot("sample") . $olddomain . " " . $pathTo . $domain);
        } else {
            exec("cp -r " . $this->getPathRoot("sample") . $olddomain. " " .
                $this->_basePath . '/site/' . $domain);
        }
    }

    /**
     * Change config file
     * @author tien.nguyen
     * @param $domain
     */
    public function changeConfigFile($olddomain, $domain, $type){
        $path = $this->getPathRoot($type, FW_DEFAULT);
        $pathNewDomain = $path. $domain;
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
    }

    /**
     * Get all site sample
     * @author tien.nguyen
     */
    public function getSamples(){
        $sites = $this->_directory->read($this->getPathRoot("sample"), nul, true, array('DELETED'));
        return $sites;
    }

    /**
     * Get site
     * @return array
     */
    public function getSites(){
        $sites = $this->_directory->read($this->getPathRoot("site"), nul, true, array('DELETED'));
        return $sites;
    }

    /**
     * Override function remove.
     * @author tien.nguyen
     * @param $type
     * @param $domain
     * @return bool
     */
    public function remove($type, $domain){
        return parent::remove($type, $domain, FW_WORDPRESS);
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
}