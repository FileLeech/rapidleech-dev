<?php
/*
 * progress.php
 * 
 * Gets the download progress
 */

require_once('class/filesdir.php');

// Get the download id
$id = $_GET['id'];
$instance = $DownloadInstance->retrieveInstance($id);
$percent = @round ( ($instance['Received']) / ($instance['Size']) * 100, 2 );
$percent = ceil($percent);
$instance['Percentage'] = $percent;
$instance['Received'] = byteConvert($instance['Received']);
$instance['Size'] = byteConvert($instance['Size']);

echo json_encode($instance);

?>