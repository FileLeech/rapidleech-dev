<?php
/*
 * index.php
 * 
 * The main file
 */

define('DEV_MODE',1);

// Initialize the download instance here
require_once('class/DownloadInstance.php');
$DownloadInstance = DownloadInstance::getInstance();
if (isset($_GET['mod']) && $_GET['mod'] == 'getProgress') {
	require_once('controller/progress.php');
	exit;
}

// Loader
require_once('loader.php');

// Get mod from _GET
$mod = "";
if (isset($_GET['mod'])) $mod = $_GET['mod'];
if ($mod == 'transload') require_once('controller/transload.php');

// Content page
$DisplayContent =  $TemplateClass->getDisplay('content', true);
$DisplayContent->assign('display', 'DISPLAY');

switch ($mod) {
	case 'action':
		
		break;
	case 'transload':
		
		break;
	default:
		require_once('controller/default.php');
		$DisplayContent->addTemplate('default', 'default.phtml');
		break;
}

// Print the index page
$DisplayIndex =  $TemplateClass->getDisplay('index'); 

// Assign objects
$DisplayIndex->assign('DisplayContent',     $DisplayContent);

// Add templates
$DisplayIndex->addTemplate('header', 'header.phtml');
$DisplayIndex->addTemplate('index' , 'index.phtml' );
$DisplayIndex->addTemplate('footer', 'footer.phtml'); 

// assign global variables
$TemplateClass->assignGlobal('mod',     $mod);
$TemplateClass->assignGlobal('global',  'GLOBAL');

// display all non separated 'display'
$TemplateClass->display(); 
?>