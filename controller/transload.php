<?php
/*
 * transload.php
 * 
 * Start the download!
 */

require_once('class/http.class.php');
require_once('class/filesdir.php');

$url = urldecode($_GET['link']);
if (!$url) {
	// Exit as error
	die ("Invalid link!");
}
$parsedUrl = parse_url($url);
$filename = basename($parsedUrl['path']);
if (!$_GET['path']) $_GET['path'] = '%2F';
$path = urldecode($_GET['path']);
$saveToFile = 'files'.$path.$filename;

// Set script to execute for ever!
set_time_limit(0);

$http = new http_class();

// Set the user agent
$http->user_agent="Mozilla/5.0 (Windows; U; BeOS; en-US; rv:1.9.0.7) Gecko/2009021910";

// Follow redirects
$http->follow_redirect=1;

// But not infinite redirects, limit redirect times to 5
$http->redirection_limit=5;

$arguments = array();
$error=$http->GetRequestArguments($url,$arguments);
$arguments["Headers"]["Pragma"]="nocache";

// Open connection to the url
$timeStart = getmicrotime();
$error=$http->Open($arguments);
if ($error == '') {
	// Send request
	$error=$http->SendRequest($arguments);
	if ($error == '') {
		$headers=array();
		// Reply header
		$error=$http->ReadReplyHeaders($headers);
		$filesize = $headers['content-length'];
		$id = $_GET['id'];
		$DownloadInstance->setInstance($id,$filename,$url,"Starting...",$filesize);
		if ($error == '') {
			// Create the download file
			$fp = fopen($saveToFile,'w');
			// Read contents
			// Loop infintely
			$received = 0;
			$body = "";
			$lastChunkTime = 0;
			$chunkSize = GetChunkSize($filesize);
			for (;;) {
				$error=$http->ReadReplyBody($body,8*1024);
				if($error!="" || strlen($body)==0) {
					break;
				}
				$saved = fwrite($fp,$body);
				$received += $saved;
				$time = getmicrotime () - $timeStart;
				$chunkTime = $time - $lastChunkTime;
				$chunkTime = $chunkTime ? $chunkTime : 1;
				$lastChunkTime = $time;
				$speed = @round ( $chunkSize / $chunkTime, 2 );
				$speed = byteConvert($speed/10)."/s";
				$DownloadInstance->editInstance($id,'Downloading...',$speed,$received);
			}
			// Download is finished
			$DownloadInstance->editInstance($id,'Finished',$speed,$received);
		}
	}
}

if ($error != '') {
	var_dump($error);exit;
}
?>