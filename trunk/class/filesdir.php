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
?>