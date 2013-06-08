<?php
class Vts_Log {
	
	/**
	 * Const to set file save log error.
	 * @var string
	 */
	const FILE_NAME = "log.txt";
	
	/**
	 * This is function to get file name log.
	 * Get from Vts_Log.
	 * @author tien.nguyen
	 */
	public static function getFileName(){
		$vtsLog = new Vts_Log();
		return $vtsLog::FILE_NAME;
	}
	
	/**
	 * Function static to write string log to file.
	 * - file name of log get from const in Vts_Log
	 * @author tien.nguyen
	 * @param string $string this is content that you want to write.
	 */
	public static function log($string){
		if(defined("LOG_ENABLED") && LOG_ENABLED){
			
			//make folder tmp to save log.txt
			$pathTmp = APPLICATION_PATH.'/../tmp'; 
			if(!is_dir($pathTmp)){
				mkdir($pathTmp, 0777);
			}
			
			//open file log.txt and save it
			$handle = fopen($pathTmp.'/'.Vts_Log::getFileName(), 'a++');
			fwrite($handle, Vts_Log::getString($string));
			fclose($handle);
		}
	}
	
	/**
	 * Generate format string of log that it will be written in log file
	 * @author tien.nguyen
	 * @param string $string
	 * @return string
	 */
	public static function getString($string){
		$date = Zend_Date::now()->toString('yyyy-MM-dd hh:mm:ss');
		$string = $date.": ".$string."\n";
		
		$arrBackstract = debug_backtrace();
		if($arrBackstract){
			foreach ($arrBackstract as $item){
				$string .= "\n ----- file: ".$item['file']." line: ".$item['line']." function: ".
						$item['function'];
			}
		}
		
		return $string;
	}
}