<?php
$__isAdminPage = true;

require_once 'setup.inc.php';

$requestedAction = 'viewUncontroled';
$pageLocation = '3_webService';

$wo = new WOOOF();

$f = file_get_contents($_REQUEST['file']);

$lines = explode("\t".'&&&&&', $f);
echo '<pre>';
foreach($lines as $line)
{
    $columns = explode("\t", $line);
    echo 'insert into mvp_dataAssociated (id, associatedData, data_id, associatedDType) values (\''. $wo->cleanUserInput($columns[0]) .'\',\''. $wo->cleanUserInput($columns[2]) .'\',\''. $wo->cleanUserInput($columns[1]) .'\',\''. $wo->cleanUserInput($columns[3]) .'\')<br>';
    $wo->db->query('insert into mvp_dataAssociated (id, associatedData, data_id, associatedDType) values (\''. $wo->cleanUserInput($columns[0]) .'\',\''. $wo->cleanUserInput($columns[2]) .'\',\''. $wo->cleanUserInput($columns[1]) .'\',\''. $wo->cleanUserInput($columns[3]) .'\')');
}
