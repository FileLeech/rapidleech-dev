<?php
/*
 * ajaxrename.php
 * 
 * The Ajax Renaming Controller
 */

// Check if there is a parameter received
if (!isset($_GET['fieldname'])) {
	// No field name specified, exit the operation
	exit;
}
// Get the original filename and path
if (!isset($_GET['path'])) {
	// No path specified, exit
	exit;
}
$path = urldecode($_GET['path']);
if (substr($path,-1) != '/') $path .= '/';
// Original file complete path
$FilePath = 'files';
$OrigFile = $FilePath.$path.$_GET['fieldname'];
// Sanitize filename first
$_GET['content'] = sanitize_filename($_GET['content']);
$RenameFile = $FilePath.$path.$_GET['content'];
rename($OrigFile,$RenameFile);
// Print the changes
echo $_GET['content'];
?>