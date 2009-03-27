<?php
/*
 * default.php
 * 
 * The default page controller
 */

// Dummy files
/*$Files = array(
	array(
		'name' => 'dummy1.txt',
		'size' => 10923,
		'time' => time()),
	array(
		'name' => 'asdj.sdk',
		'size' => 3894,
		'time' => time() - 5000));*/
// You must add a trailing slash
$Nav = 'files/';
if (isset($_GET['nav'])) $Nav.= urldecode($_GET['nav']);
if (substr($Nav,-1) != '/') $Nav .= '/';
// Do not allow ../
$Nav = str_replace('../','/',$Nav);
$Nav = str_replace('//','/',$Nav);
$DisplayContent->assign('path', substr($Nav,5));
$Files = getDir($Nav);

$DisplayContent->assign('Files', $Files);

function getDir($dir,$return = array()) {
	$d = dir($dir);
	while (false !== ($entry = $d->read())) {
		if ($entry != '.' && $entry != '..' && is_dir($dir.$entry)) {
			$return[] = array(
				'name' => $entry,
				'time' => filemtime_r($dir.$entry),
				'size' => GetFolderSize($dir.$entry),
				'realpath' => $dir.$entry,
				'type' => 'dir');
		} elseif (is_file($dir.$entry)) {
			$return[] = array(
				'name' => $entry,
				'size' => filesize($dir.$entry),
				'time' => filemtime($dir.$entry),
				'type' => 'file');
		}
	}
	return $return;
}

function filemtime_r($path) {
   
    if (!file_exists($path))
        return 0;
   
    if (is_file($path))
        return filemtime($path);
    $ret = 0;
   
     foreach (glob($path."/*") as $fn)
     {
        if (filemtime_r($fn) > $ret)
            $ret = filemtime_r($fn);   
            // This will return a timestamp, you will have to use date().
     }
    return $ret;   
}

function GetFolderSize($dirname) {
	
	// open the directory, if the script cannot open the directory then return folderSize = 0
	$dir_handle = opendir($dirname);
	if (!$dir_handle) return 0;
	
	// traversal for every entry in the directory
	while ($file = readdir($dir_handle)){
	
	    // ignore '.' and '..' directory
		if  ($file  !=  "."  &&  $file  !=  "..")  {
		
		    // if entry is directory then go recursive !
			if  (is_dir($dirname."/".$file)){
				$folderSize += GetFolderSize($dirname.'/'.$file);
			
			// if file then accumulate the size
			} else {
				$folderSize += filesize($dirname."/".$file);
			}
		}
	}
	// chose the directory
	closedir($dir_handle);
	
	// return $dirname folder size
	return $folderSize ;
}

?>