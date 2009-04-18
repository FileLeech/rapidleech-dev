<?php
/*
 * ajaxaction.php
 * 
 * Functions of the ajax actions
 */

if (!$_GET['action']) {
	// No action?! Probably not called from ajax
	exit;
}
if (!$_GET['file']) {
	// No file specified! Exit operation
	exit;
}
// Get the original filename and path
if (!isset($_GET['path'])) {
	// No path specified, exit
	exit;
}
$FilePath = 'files';
$path = urldecode($_GET['path']);
$OrigFile = $FilePath.$path.$_GET['file'];
switch ($_GET['action']) {
	case 'delete':
		unlink($OrigFile);
		break;
}
?>