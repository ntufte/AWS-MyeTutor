<?php 
$__isAdminPage = true;

require_once 'setup.inc.php';

$requestedAction = 'viewUncontroled';
$pageLocation = '3_webService';

$wo = new WOOOF();

$dR = $wo->db->query('select * from mvp_data');
while ($d= mysql_fetch_assoc($dR)) 
{
	$d['ord'] = str_replace('D', '', $d['id']);
	echo $d['id'] .' '. $d['ord'] .'<br>';
	$wo->db->query('update mvp_data set ord=\''. $d['ord'] .'\' where id=\''. $d['id'] .'\'');
}