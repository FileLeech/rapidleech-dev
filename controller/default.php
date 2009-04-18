<?php
/*
 * default.php
 * 
 * The default page controller
 */

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

?>