<?php
/*
 * createdir.php
 * 
 * The ajax controller file which controls the creation of directory
 */

if (!$_GET['folder']) {
	echo "You didn't enter a comment name!";
	exit;
}
$folder_name = sanitize_filename($_GET['folder']);
$FilePath = 'files';
$path = urldecode($_GET['path']);
$TargetFile = $FilePath.$path.$_GET['folder'];
if (file_exists($TargetFile)) {
	echo "File already exists!";
	exit;
}
$result = mkdir($TargetFile,0777);
if ($result) {
	echo "success";
} else {
	echo "Failed to create directory ".$_GET['folder'];
}
?>