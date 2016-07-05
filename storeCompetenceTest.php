<?php
require_once 'setup.inc.php';
header('Content-Type: text/html; charset=utf-8');
$requestedAction='viewUncontroled';
$pageLocation='3_lessons';

$wo = new WOOOF();

if (isset($_POST['submit']))
{
    foreach ($_POST as $key => $value) 
    {
        if (substr($key, 0,9) == 'question_')
        {
            $id = substr($key, 9);
            $packet[$id]=$value;
        }
    }
    $newId = $wo->db->getNewId('compTestResults');
    $wo->db->query('insert into compTestResults set id=\''. $newId .'\', entryDate=\''. $wo->getCurrentDateTime() .'\', testData=\''. $wo->cleanUserInput(serialize($packet)) .'@@@@'. json_encode($packet) .'\', userId=\''. $userData['id'] .'\'');
    echo 'Your answers have successfuly been stored. Thank you for your participation. You can see your score <a href="scoreCompetenceTest.php?target='. $newId .'">here</a>';
    exit;
}else
{
    header("Location: getCompetenceTest.php");
    exit;
}