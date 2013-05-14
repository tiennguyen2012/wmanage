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

    private $_basePath = "E:/xampp/htdocs/vtssoft";
    private $_endOfDb = "vtscat.com";
    private $_prex = "wsample";
    private $_prexTable = "wp_";
    private $_domain = "vtscat.com";
    private $_prexDomainSample = "wsample";

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
        return $this->_prex . $siteSamepleId .".". $this->_endOfDb;
    }

    /**
     * Make website for wordpress
     * @author tien.nguyen
     */
    public function make($siteSampleId, $domain, $siteData = null)
    {
        //get and setup database site sample
        $this->setupDatabase($siteSampleId, $domain, $siteData);

        //copy code
        $this->copyCodeSample($siteSampleId, $domain);
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
    public function setupDatabase($siteSampleId, $domain, $siteData)
    {
        $configResource = Vts_Config::get("resources");
        $configResource = $configResource->toArray();
        $configResource['db']['params']['dbname'] = $domain;
        $db = Zend_Db::factory($configResource['db']['adapter'], $configResource['db']['params']);
        Zend_Db_Table::setDefaultAdapter($db);

        //execute mysql copy database to create new database
        $command = "mysqldump -h " . $configResource['db']['params']['host'] .
            " -u " . $configResource['db']['params']['username'] .
            " -p" . $configResource['db']['params']['password'] . " " . $this->getDatabaseNameSample($siteSampleId) .
            " | mysql -h " . $configResource['db']['params']['host'] .
            " -u " . $configResource['db']['params']['username'] .
            " -p" . $configResource['db']['params']['password'] . " " . $domain;
        $res = exec($command);

        /**
         * update some config for new domain.
         */
        //update url config, site name.
        $options = $db->query("select * from " . $this->_prexTable . "options");
        while ($row = mysql_fetch_object($options)) {
            //update URL
            if (strpos($this->_sampleUrl, $row->option_value) !== null) {
                $value = str_replace("http://".$this->_prexDomainSample.$siteSampleId, "http://" . $domain, $row->option_value);
                $db->query("UPDATE " . $this->_prexTable . "options SET option_value='" . $value .
                    "' WHERE option_name = '" . $row->option_name . "'");
            }
        }
    }

    /**
     * Copy code to new site.
     * @author tien.nguyen
     */
    public function copyCodeSample($siteSampleSite, $domain){
        exec("cp -r ".$this->_basePath."/sample/".$this->_prexDomainSample.".".$siteSampleSite." ".
            $this->_basePath.'/site/'.$domain);
    }
}