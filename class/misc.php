<?php
/*
 * misc.php
 * 
 * Miscalleneous functions
 */

function parse_valid_url($link) {
	$link = trim($link);
	$Url = parse_url($link);
	if (isset($Url['query'])) {
		if (substr($Url['query'],-1) != substr($link,-1)) {
			$Url['query'] = substr($Url['query'],0,-1);
		}
	}
	if (!isset($Url['host'])) {
		return false;
	}
	if (!isset($Url['path'])) {
		$Url['path'] = '/';
	}
	if (!isset($Url['port'])) {
		$Url['port'] = 80;
	}
	return $Url;
}

function Debug($var) {
	if (!is_array($var) && strip_tags($var) != $var) {
		echo "<pre>";var_dump(htmlentities($var));echo "</pre>";exit;
	} else {
		echo "<pre>";var_dump($var);echo "</pre>";exit;
	}
}


?>