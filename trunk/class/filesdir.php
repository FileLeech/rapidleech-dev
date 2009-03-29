<?php
/*
 * filesdir.php
 * 
 * Stores functions and classes related to files and directories
 */


/**
* Byte converting with formated number
*
* @param int $bytes        bytes
* @return string
*/
function byteConvert($bytes){
	$b = (int)$bytes;
	$s = array('B', 'KB', 'MB', 'GB', 'TB');
	if($b < 0){
	    return "0 ".$s[0];
	}
	$con = 1024;
	$e = (int)(log($b,$con));
	return number_format($b/pow($con,$e),2).' '.$s[$e];
}

/**
 * Get microtime
 *
 * @return float
 */
function getmicrotime(){ 
	list($usec, $sec) = explode(" ",microtime()); 
	return ((float)$usec + (float)$sec); 
}

function GetChunkSize($fsize) {
	if ($fsize <= 1024 * 1024) {
		return 4096;
	}
	if ($fsize <= 1024 * 1024 * 10) {
		return 4096 * 10;
	}
	if ($fsize <= 1024 * 1024 * 40) {
		return 4096 * 30;
	}
	if ($fsize <= 1024 * 1024 * 80) {
		return 4096 * 47;
	}
	if ($fsize <= 1024 * 1024 * 120) {
		return 4096 * 65;
	}
	if ($fsize <= 1024 * 1024 * 150) {
		return 4096 * 70;
	}
	if ($fsize <= 1024 * 1024 * 200) {
		return 4096 * 85;
	}
	if ($fsize <= 1024 * 1024 * 250) {
		return 4096 * 100;
	}
	if ($fsize <= 1024 * 1024 * 300) {
		return 4096 * 115;
	}
	if ($fsize <= 1024 * 1024 * 400) {
		return 4096 * 135;
	}
	if ($fsize <= 1024 * 1024 * 500) {
		return 4096 * 170;
	}
	if ($fsize <= 1024 * 1024 * 1000) {
		return 4096 * 200;
	}
	return 4096 * 210;
}

function htmlpath($relative_path) {
	$realpath=str_replace("\\", "/", realpath($relative_path));
	$htmlpathURL=str_replace(APP_PATH,'',$realpath);
	return $htmlpathURL;
}

/**
 * Sanitize filename
 * 
 * @param string $filename		Filename to santize
 * @return string
 */
function sanitize_filename($filename) {
	$sanitized_name = preg_replace('/[^0-9a-z\.\_\-\040]/i','',$filename);
	return $sanitized_name;
}
?>