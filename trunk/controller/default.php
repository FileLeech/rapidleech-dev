<?php
/*
 * default.php
 * 
 * The default page controller
 */

$DisplayContent->assign('path', '/');

// Dummy files
$Files = array(
	array(
		'name' => 'dummy1.txt',
		'size' => 10923,
		'time' => time()),
	array(
		'name' => 'asdj.sdk',
		'size' => 3894,
		'time' => time() - 5000));

$DisplayContent->assign('Files', $Files);
?>