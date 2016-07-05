<?php
require_once 'setup.inc.php';
header('Content-Type: text/html; charset=utf-8');
$requestedAction='viewUncontroled';
$pageLocation='3_lessons';

$wo = new WOOOF();

$result = $wo->db->query('select * from compTestResults where id=\''. $wo->cleanUserInput($_GET['target']) .'\'');
if (mysql_num_rows($result))
{
    $restultData = mysql_fetch_assoc($result);
    $pieces = explode('@@@@', $restultData['testData']);
    $resultArray = unserialize($pieces[0]);
    $success=0;
    $fail=0;
    foreach ($resultArray as $key => $value) 
    {
        $answer = $wo->db->getRow('competenceTestData',$key);
        if ($answer['correct']==$value)
        {
            $success++;
        }else
        {
            $fail++;
        }
    }
    echo 'You answered '. $success .' correctly and '. $fail .' wrongly, in a total of 30 questions.';
}else
{
    header("Location: getCompetenceTest.php");
    exit;
}
