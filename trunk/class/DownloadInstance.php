<?php
/*
 * DownloadInstance.php
 * 
 * The class that manages download instances.
 */

class DownloadInstance {
	
	private static $_instance;
	private $DownloadInstance;
	
	private function __construct() {
		// Initialize the instance file
		$this->InitInstance();
		// Purge those which are useless
		$this->PurgeInstance();
	}
	
	public static function getInstance() {
		// Constructs this class
		if (!(self::$_instance instanceof self)) {
				self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	private function InitInstance() {
		// The file will be a variable in the future
		$DownloadInstance = file_get_contents('configs/instance.lst');
		// Unserialize the string to make it array
		$DownloadInstance = unserialize($DownloadInstance);
		$this->DownloadInstance = $DownloadInstance;
	}
	
	public function retrieveInstance($id) {
		// If instance exists
		if (isset($this->DownloadInstance[$id])) {
			return $this->DownloadInstance[$id];
		} else {
			return false;
		}
	}
	
	public function setInstance($id,$filename,$link,$status = "Starting...",$size = null,$speed = null,$received = null) {
		// Set the download instance
		// Check if instance exists
		if (isset($this->DownloadInstance[$id])) {
			// Don't overwrite instances!
			return $this->DownloadInstance[$id];
		} else {
			$this->DownloadInstance[$id]['Created'] = time();
			$this->DownloadInstance[$id]['Name'] = $filename;
			$this->DownloadInstance[$id]['Link'] = $link;
			$this->DownloadInstance[$id]['Status'] = $status;
			$this->DownloadInstance[$id]['Size'] = $size;
			$this->DownloadInstance[$id]['Speed'] = $speed;
			$this->DownloadInstance[$id]['Received'] = $received;
			$this->SaveInstance();
			return $this->DownloadInstance[$id];
		}
	}
	
	public function editInstance($id,$status,$speed,$received,$size = null) {
		// Edit the download instance
		if (!isset($this->DownloadInstance[$id])) {
			return false;
		} else {
			$Instance = $this->DownloadInstance[$id];
			$Instance['Status'] = $status;
			if ($size != null) {
				$Instance[$id]['Size'] = $size;
			}
			$Instance['Speed'] = $speed;
			$Instance['Received'] = $received;
			$this->DownloadInstance[$id] = $Instance;
			$this->SaveInstance();
			return $this->DownloadInstance[$id];
		}
	}
	
	private function SaveInstance() {
		// The file will be a variable in the future
		$DownloadInstance = serialize($this->DownloadInstance);
		$file = fopen('configs/instance.lst','w');
		fwrite($file,$DownloadInstance);
		fclose($file);
	}
	
	private function PurgeInstance() {
		foreach ($this->DownloadInstance as $id => $Instance) {
			if (time() - $Instance['Created'] > 3600 ) {
				unset($this->DownloadInstance[$id]);
			}
		}
		$this->SaveInstance();
	}
	
	public function __deconstruct() {
		$this->SaveInstance();
	}
}
?>