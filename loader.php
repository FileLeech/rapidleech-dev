<?php
/*
 * loader.php
 * 
 * Initializes everything
 */

session_start ();

// If development mode, display all errors
if (defined ( DEV_MODE )) {
	error_reporting ( E_ALL );
} else {
	//error_reporting ( 0 );
}

require_once ('configs/configs.php');
require_once ('class/filesdir.php');
require_once ('class/HSTemplate.php');

// Initialize the template engine
$template_path = dirname ( __FILE__ ) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . $TemplateChoice;
$options = array (
				'template_path' => $template_path, 
				'cache_path' => dirname ( __FILE__ ) . DIRECTORY_SEPARATOR . 'cache', 
				'debug' => false 
);

$TemplateClass = new HSTemplate($options);

?>