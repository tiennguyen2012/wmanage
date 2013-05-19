<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Tien
 * Date: 5/19/13
 * Time: 12:11 AM
 * To change this template use File | Settings | File Templates.
 */
class Vts_Site_Build {
    private $_options;

    public function __construct(){
        $this->_options = array();
        $this->_options['buildfrom'] = "";
        $this->_options['buildon'] = date('Y-m-d h:i:s');
        $this->_options['buildby'] = $_SERVER['HTTP_HOST'];
        $this->_options['buildbyip'] = $_SERVER['REMOTE_ADDR'];
        $this->_options['buildfw'] = FW_DEFAULT;
    }

    public function setOptions($options){
        $this->_options = array_merge($this->_options, $options);
    }

    /**
     * Generate file build with path. If it is not exist, system will create it.
     * @author tien.nguyen
     * @param $path
     * @return bool
     */
    public function generate($path){
        try{
            $handle = fopen($path.'/build.txt', 'w+');
            fwrite($handle, $this->getString());
            fclose($handle);
            return true;
        }catch (exception $e){
            echo $e->getMessage();
            return false;
        }
    }

    public function read($path){
        try{
            if(file_exists($path.'/build.txt')){
                $result = new stdClass();
                $string = file_get_contents($path.'/build.txt');
                if($string){
                    $tmp = explode("\n", $string);
                    foreach($tmp as $item){
                        $tmp2 = explode("=", $item);
                        if(count($tmp2) == 2){
                            $result->{$tmp2[0]} = $tmp2[1];
                        }
                    }
                }
                return $result;
            }
            return null;
        }catch (exception $e){
            echo $e->getMessage();
            return null;
        }

    }

    /**
     * get string that you will write to file
     * @return string
     */
    public function getString(){
        $string = "";
        foreach($this->_options as $k => $v){
            $string .= $k."=".$v."\n";
        }
        return $string;
    }
}