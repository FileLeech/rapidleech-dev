<?php
/*
 * refreshftable.php
 * 
 * Returns the data of the files to be displayed on the table
 */

$Nav = 'files/';
if (isset($_GET['nav'])) $Nav.= urldecode($_GET['nav']);
if (substr($Nav,-1) != '/') $Nav .= '/';
// Do not allow ../
$Nav = str_replace('../','/',$Nav);
$Nav = str_replace('//','/',$Nav);
$Files = getDir($Nav);
$return = array();
$TotalSize = 0;
foreach ($Files as $File) {
	if ($File['type'] == 'file') {
		$return[] = array(
			'<input type="checkbox" name="files[]" value="'.$File['name'].'" />',
			'<span id="'.htmlentities($File['name']).'" class="editText">'.htmlentities($File['name']).'</span>',
			byteConvert($File['size']),
			date('d-m-y H:i:s',$File['time']),
			'[<a href="javascript:action(\'delete\',\''.$File['name'].'\');" title="Delete File">X</a>]'
		);
		$TotalSize += $File['size'];
	} else {
		$return[] = array(
			'&nbsp;',
			'<a href="index.php?nav='.urlencode(substr($File['realpath'],6)).'">'.$File['name'].'</a>',
			byteConvert($File['size']),
			date('d-m-y H:i:s',$File['time']),
			'&nbsp;'
		);
	}
}
$return['files'] = $return;
$return['info']['size'] = byteConvert($TotalSize);
$return['info']['item'] = count($Files);

echo json_encode($return);
?>