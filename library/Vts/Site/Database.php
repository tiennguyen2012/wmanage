<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Tien
 * Date: 5/19/13
 * Time: 2:52 PM
 * To change this template use File | Settings | File Templates.
 */
class Vts_Site_Database {

    private $_hostname;
    private $_username;
    private $_password;

    private $_handle;

    public function __construct(){
        $config = Vts_Config::get("resources");

        $this->_hostname = $config->db->params->host;
        $this->_username = $config->db->params->username;
        $this->_password = $config->db->params->password;
    }

    public function connect(){
        $this->_handle = mysql_connect($this->_hostname, $this->_username, $this->_password);
    }

    public function close(){
        mysql_close($this->_handle);
    }

    public function drop($database){
        $this->connect();
        mysql_query("DROP DATABASE `".$database."`");
        $this->close();
        return !$this->isExistDatabase($database);
    }

    public function isExistDatabase($database){
        $rs = false;
        $this->connect();
        $databaseResult = mysql_query("SHOW DATABASES;");
        while($row = mysql_fetch_array($databaseResult)){
            if(strtoupper($row['Database']) == strtoupper($database)){
                $rs = true;
                break;
            }
        }
        $this->close();
        return $rs;
    }
}