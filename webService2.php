<?php
$__isAdminPage = true;

require_once 'setup.inc.php';

$obj = new stdClass();
$obj->status = 'OK';
$obj->errorNumber = '1000';
$obj->errorDescription = 'Success.';

if (isset($_REQUEST['pleaseDump']))
{
    var_dump($_REQUEST);
}

function showErrorAndTerminate($errorNumber, $errorDescription)
{
	global $obj;
	
	$obj->status = 'Error';
	$obj->errorNumber = $errorNumber;
	$obj->errorDescription = $errorDescription;
        header('Content-Type: application/json');
	echo json_encode($obj);
	exit;
}

function validateDate($date, $format = 'YmdHis')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

function generateUserAfinity($tutorData)
{
    global $userData;
    global $wo;

    $userDataX = $wo->db->getRowByColumn('userData', 'user', $userData['id']);

    //$diff[] = 'matchingLRN';
    //$diff[] = 'analogiesLRN';
    //$diff[] = 'subsetsLRN';
    //$diff[] = 'intersectionsLRN';
    $diff[] = 'kinaestheticLSTYL';
    //$diff[] = 'constructionLRN';
    //$diff[] = 'reconstructionLRN';
    //$diff[] = 'rulesLRN';
    $diff[] = 'visualLSTYL';
    $diff[] = 'intrapersonalLSTYL';
    //$diff[] = 'rightHEMIS';
    $diff[] = 'auditoryLSTYL';
    $diff[] = 'linguisticLSTYL';
    //$diff[] = 'leftHEMIS';
    $diff[] = 'interpersonalLSTYL';

    $max=0;
    $secondMax=0;
    $maxName='';
    $secondMaxName='';

    foreach ($diff as $value) 
    {
        if ($userDataX[$value]>=$max)
        {
            $max = $userDataX[$value];
            $maxName = $value;
        }elseif ($userDataX[$value]>=$secondMax)
        {
            $secondMax = $userDataX[$value];
            $secondMaxName = $value;
        }
    }
    
    $affinity = $userDataX[$maxName] - $tutorData[$maxName];

    if ($affinity < $userDataX[$secondMaxName] - $tutorData[$secondMaxName])
    {
        $affinity = $userDataX[$secondMaxName] - $tutorData[$secondMaxName];
    }
    $affinity = 100 - $affinity;
    return $affinity;
}

function cmp_by_affinity($a, $b) {
  return $a['affinity'] - $b['affinity'];
}

function getDominantMIsForUser($userDataX)
{
    $tmp['kinaestheticLSTYL'] = $userDataX['kinaestheticLSTYL'];
    $tmp['visualLSTYL'] = $userDataX['visualLSTYL'];
    $tmp['intrapersonalLSTYL'] = $userDataX['intrapersonalLSTYL'];
    $tmp['auditoryLSTYL'] = $userDataX['auditoryLSTYL'];
    $tmp['linguisticLSTYL'] = $userDataX['linguisticLSTYL'];
    $tmp['interpersonalLSTYL'] = $userDataX['interpersonalLSTYL'];
            
    if (!arsort($tmp))
    {
        showErrorAndTerminate('88888', 'Internal failure. Unable to sort stored MIs');
    }
    
    $howMany=0;
    foreach($tmp as $key => $value)
    {
        if ($howMany<3)
        {
            $mis[] = array($key,$value);
            $howMany++;
        }else
        {
            break;
        }
    }
    
    return $mis;
}

function showLesson($lesson)
{
    global $obj;
    global $wo;
    global $userData;

    //// TODO: remove this ugly hack! hardcoded to demo the new lesson form
    if ($userData['id']=='CSAGOL7ATL')
    {
        $obj->lessonContent='<div style="width: 740px; height:980px"><iframe src="/student2/"></iframe><div>';
        $obj->lessonType = 'DT4';
        return;
    }
    
    if ($lesson['dataType'] == 'DT1')
    {
        $optionsR = $wo->db->query('select * from mvp_dataAssociated where data_id=\''. $lesson['id'] .'\' and associatedDType=\'ADT1\'');
        $obj->lessonContent = '<form data-form> <input type="hidden" name="fetchNext" value="1">'. nl2br($lesson['actualData']) .'<br>
';

        while($op = mysql_fetch_assoc($optionsR))
        {
            $obj->lessonContent .= '<input type="radio" name="answer" value="'. $op['id'] .'">'. $op['associatedData'] .'<br>
';
        }
        
        $obj->lessonContent .= '</form>';
        $obj->lessonType = $lesson['dataType'];
    }else
    {
        $obj->lessonContent = nl2br($lesson['actualData']);
        $obj->lessonType = $lesson['dataType'];
    }
}

function scoreQuestion($lesson,$answerGiven)
{
    global $wo;
    
    $adR = $wo->db->query('select * from mvp_dataAssociated where data_id=\''. $lesson['id'] .'\' and associatedDType=\'ADT3\'');
    $answ = mysql_fetch_assoc($adR);
    $answer = explode('.', $answ['associatedData']);
    $choice = trim($answer[0]);
    if ($choice=='Α' || $choice=='A')
    {
        $numericAnswer=0;
    }elseif ($choice=='Β' || $choice=='B')
    {
        $numericAnswer=1;
    }elseif ($choice=='Γ' || $choice=='C')
    {
        $numericAnswer=2;
    }elseif ($choice=='Δ' || $choice=='D')
    {
        $numericAnswer=3;
    }else
    {
        showErrorAndTerminate('SQ001', 'Internal data Error.');
    }

    $adR = $wo->db->query('select * from mvp_dataAssociated where data_id=\''. $lesson['id'] .'\' and associatedDType=\'ADT1\' order by id');
    $clock = 0;
    while($opt = mysql_fetch_assoc($adR))
    {
        if ($opt['id']==$answerGiven)
        {
            $selected = $clock;
        }
        $clock++;
    }

    if ($numericAnswer == $selected)
    {
        return true;
    }else
    {
        return false;
    }
}

function getNextTheory($userId, $hasHadTheory)
{
    global $wo;
    
    $userDataX = $wo->db->getRowByColumn('userData', 'user', $userId);
    $dominantMI = getDominantMIsForUser($userDataX);
    
    if ($dominantMI[0][0]=='kinaestheticLSTYL' || $dominantMI[0][0]=='visualLSTYL' || $dominantMI[1][0]=='kinaestheticLSTYL' || $dominantMI[1][0]=='visualLSTYL')
    {
        $extraSauce = ' and (kinestheticRating=\'2\' OR visualRating=\'2\')';
    }else
    {
        $extraSauce = ' and (linguisticRating=\'2\' OR mathematicalRating=\'2\')';
    }
    
    if ($hasHadTheory!='')
    {
        $extraSauce2 =' and ord>\''. $wo->cleanUserInput($hasHadTheory) .'\'';
    }else
    {
        $extraSauce2 ='';
    }
        
    
    $testR=$wo->db->query('select * from mvp_data where mvp_data.dataType = \'DT4\''. $extraSauce . $extraSauce2 .' order by ord');
    $done = false;
    $tId = '';
    while(!$done && $t = mysql_fetch_assoc($testR))
    {
        $tId = $t['id'];
        $examplesR = $wo->db->query('select * from mvp_data where dataType=\'DT2\' and relevantTheory=\''. $tId .'\'');
        if (mysql_num_rows($examplesR))
        {
            $questionsR = $wo->db->query('select * from mvp_data where dataType=\'DT1\' and relevantTheory=\''. $tId .'\'');
            if (mysql_num_rows($questionsR))
            {
                $done = true;
            }
        }
    }

    if ($done!=true && $hasHadTheory)
    {
        $testR=$wo->db->query('select * from mvp_data where mvp_data.dataType = \'DT4\''. $extraSauce .' order by ord');
        $done = false;
        $tId = '';
        while(!$done && $t = mysql_fetch_assoc($testR))
        {
            $tId = $t['id'];
            $examplesR = $wo->db->query('select * from mvp_data where dataType=\'DT2\' and relevantTheory=\''. $tId .'\'');
            if (mysql_num_rows($examplesR))
            {
                $questionsR = $wo->db->query('select * from mvp_data where dataType=\'DT1\' and relevantTheory=\''. $tId .'\'');
                if (mysql_num_rows($questionsR))
                {
                    $done = true;
                }
            }
        }
    }
    
    if ($done!=true)
    {
            showErrorAndTerminate('GNT001', 'Insufficient Data !');
    }

    return $wo->db->getRow('mvp_data', $tId);
}

function getEffectiveUserId()
{
    global $userData;
    global $wo;

    $userDataX = $wo->db->getRowByColumn('userData', 'user', $userData['id']);
    return $userDataX['isParentToId'];
}

if (!isset($_REQUEST['action']))
{
    showErrorAndTerminate('2001', 'No action requested.  request: '. print_r($_REQUEST, TRUE) .'<br> GET:'. print_r($_GET, TRUE) .'<br> POST:'. print_r($_POST, TRUE));
}elseif ((!isset($_REQUEST['wsSessionIdentifier']) || $_REQUEST['wsSessionIdentifier']=='') && $_REQUEST['action']!='wsLogin')
{
    showErrorAndTerminate('2003a', 'Not valid session supplied.');
}

if (isset($_REQUEST['wsSessionIdentifier']))
{
    $_COOKIE['sid'] = $_REQUEST['wsSessionIdentifier'];
}

$requestedAction = 'viewUncontroled';
$pageLocation = '3_webService';

$wo = new WOOOF();

if (isset($_REQUEST['wsSessionIdentifier']) || ($userData['id']='0123456789' && $_REQUEST['action']!='wsLogin'))
{
    $_COOKIE['sid'] = $_REQUEST['wsSessionIdentifier'];
}

if($wo->sessionCheck()!=TRUE)
{
    showErrorAndTerminate('4000', 'Session is invalid. User is not logged in.');
}

if (isset($_REQUEST['test']) && $_REQUEST['test']=='test')
{
    $uR = $wo->db->query('select * From userData');
    while($u = mysql_fetch_assoc($uR))
    {
        $data = getDominantMIsForUser($u);
        echo $u['email'] .' '. print_r($data,TRUE) .'<br>';
    }
    exit;
}

if ($_REQUEST['action']=='wsLogin')
{
    $loginResult = FALSE;
    $rowForTest = $wo->db->getRowByColumn('__users', 'loginName', $wo->cleanUserInput($_REQUEST['username']));

    if (isset($rowForTest['id']))
    {
        if (PHP_VERSION_ID<50307)
        {
            $salt='$2a$08$'. $rowForTest['id'] . strrev($rowForTest['id']) . $rowForTest['id'];
        }else
        {
            $salt='$2y$08$'. $rowForTest['id'] . strrev($rowForTest['id']) . $rowForTest['id'];
        }

        // TODO: Randomize salt production here ^
        $cryptResult=crypt(WOOOF::cleanUserInput($_REQUEST['password']), $salt);
        $hash = substr($cryptResult, 28);

            $result = $wo->db->query('select * from __users where binary loginName=\''. $wo->cleanUserInput($rowForTest['loginName']) .'\' and binary loginPass=\''. $hash .'\'');
            if (mysql_num_rows($result))
            {
                    $userRow = mysql_fetch_assoc($result);
                    $userRow['loginPass']='not your business, really !';

                    $goOn = FALSE;

                    do
                    {
                            $sid = 'ws'. WOOOF::randomString(38);
                            $new_sid_result=$wo->db->query("select * from __sessions where sessionId='". $sid ."'");
                            if (!mysql_num_rows($new_sid_result)) $goOn = TRUE;
                    }while (!$goOn);

                    $result = $wo->db->query("insert into __sessions (userId,sessionId,loginDateTime,lastAction,loginIP,active) values ('${userRow['id']}','$sid','". $wo->getCurrentDateTime() ."','". $wo->getCurrentDateTime() ."','". $wo->cleanUserInput($_SERVER["REMOTE_ADDR"]) ."','1')");

                    if ($result===FALSE)
                    {
                            showErrorAndTerminate('2005', 'Failed to insert new session in the data base for user `'. $userData['loginName'] .'`. Potential security breach!');
                    }
                    setcookie("sid",$sid,  strtotime("+".$sessionExpirationPeriod),'/');
                    $obj->wsSessionIdentifier = $sid;
                    $loginResult = TRUE;	
            }
    }

    if ($loginResult===FALSE)
    {
            showErrorAndTerminate('2004', 'Wrong credentials supplied.Login failure');
    }	
}elseif ($_REQUEST['action']=='wsGetLatestNews') //1000
{
    if ($wo->amIA('tutor'))
    {
        $newsColumn = 'seeTutor';
    }elseif ($wo->amIA('student'))
    {
        $newsColumn = 'seeStudent';
    }else
    {
        $newsColumn = 'seeParent';
    }

    $news = new WOOOF_dataBaseTable($wo->db, 'mvp_news');

    $whereClauses['active'] = '1';
    $whereClauses[$newsColumn] = '1';

    $news->getResult($whereClauses, 'entryDate desc');

    if (count($news->resultRows)>0)
    {
        for($z=0; $z<count($news->resultRows)/2; $z++)
        {
            $news->resultRows[$z]['entryDate'] = $wo->decodeDateTime($news->resultRows[$z]['entryDate']);
            $obj->results[] = $news->resultRows[$z];
        }
    }else
    {
            $obj->results = array(array('id' => 0));
    }
}elseif ($_REQUEST['action']=='wsGetNewsItem')  //2000
{
    if ($wo->amIA('tutor'))
    {
        $newsColumn = 'seeTutor';
    }elseif ($wo->amIA('student'))
    {
        $newsColumn = 'seeStudent';
    }else
    {
        $newsColumn = 'seeParent';
    }

    $news = new WOOOF_dataBaseTable($wo->db, 'mvp_news');

    $whereClauses['active'] = '1';
    $whereClauses[$newsColumn] = '1';
    $whereClauses['id'] = $_REQUEST['newsItemId'];

    $news->getResult($whereClauses, 'entryDate desc');

    if (count($news->resultRows)>0)
    {
            $news->resultRows[0]['entryDate'] = $wo->decodeDateTime($news->resultRows[0]['entryDate']);
            $obj->results = $news->resultRows;
    }else
    {
            $obj->results = array(array('id' => 0));
    }
}elseif ($_REQUEST['action']=='wsGetCompetenceTest')   //3000
{
    $_REQUEST['outputType']='JSON';
    
    if (!$wo->amIA('student'))
    {
        showErrorAndTerminate('3001', 'Only Students can get competence tests from this service.');
    }else
    {
        $_REQUEST['audience']='Student';
    }

    $_REQUEST['testType']='Competence';
    $audience='1a';

    $testType='1a'; // competence only

    $result = $wo->db->query('select id,question,answ1,answ2,answ3,answ4 from competenceTestData where audience=\''. $audience .'\' and testType=\''. $testType .'\' order by RAND() limit 5');
    while($datum = mysql_fetch_assoc($result))
    {
            $obj->questions[] = $datum;
    }
}elseif ($_REQUEST['action']=='wsScoreCompetenceTest')   //3000
{
    if (!$wo->amIA('student'))
    {
        showErrorAndTerminate('3501', 'Only Students can get competence tests from this service.');
    }

    $newId = $wo->db->getNewId('compTestResults');

    if (!isset($_REQUEST['answers']))
    {
        showErrorAndTerminate('3502', 'No answers provided!');
    }
    
    $_REQUEST['answers'] = json_decode($_REQUEST['answers']);

    $wo->db->query('insert into compTestResults set id=\''. $newId .'\', entryDate=\''. $wo->getCurrentDateTime() .'\', testData=\''. $wo->cleanUserInput(serialize($_REQUEST['answers'])) .'@@@@'. json_encode($_REQUEST['answers']) .'\', userId=\''. $userData['id'] .'\'');

    $obj->correctAnswers=0;
    
    mb_internal_encoding('UTF-8');
    
    foreach($_REQUEST['answers'] as $key => $value)
    {
        $keyID = explode('_', $key);
        $question = $wo->db->getRow('competenceTestData', $keyID[1]);
        $answer = mb_substr(trim($value), 0,1);
        $comment=0;
        $question['comment0']='';
        $correctAnswerNumber = -1;
        switch($answer)
        {
            case 'A':
            case 'Α':
                $correctAnswerNumber = 1;
                $comment=1;
                break;
            case 'Β':
            case 'B':
                $correctAnswerNumber = 2;
                $comment=2;
                break;
            case 'Γ':
            case 'C':
                $correctAnswerNumber = 3;
                $comment=3;
                break;
            case 'Δ':
            case 'D':
                $correctAnswerNumber = 4;
                $comment=4;
                break;
        }
        if ($question['correct'] == $correctAnswerNumber)
        {
            $obj->correctAnswers++;
        }else
        {
            $obj->failures[] = array('question' => $question['question'], 'answerGiven' => $value, 'correctAnswer' => $question['correct'], 'correctAnswerNumeric' => $correctAnswerNumber, 'failureReason' => $question['comment'.$comment]);
        }

    }



}elseif ($_REQUEST['action']=='wsLogOut')   //4000
{
    $wo->invalidateSession();
    $obj->result='Session invalidated. User logged out.';
}elseif ($_REQUEST['action']=='wsCheckSessionAndRetrieveUserData') //5000
{  
    if($wo->sessionCheck()==TRUE)
    {
        $obj->sessionStatus = 'Session Valid';
        $obj->userLogin = $userData;
        $obj->userData = $wo->db->getRowByColumn('userData', 'user', $userData['id']);
        $userType = $wo->db->getRow('userTypes', $obj->userData['userType']);
        $obj->userData['userTypeLiteral']=$userType['name'];
        $serviceLevel = $wo->db->getRow('mvp_serviceLevels', $obj->userData['serviceLevel']);
        $obj->userData['serviceLevelLiteral']=$serviceLevel['name'];
        $result = $wo->db->query('select count(*) from compTestResults where userId=\''. $userData['id'] .'\'');
        $compTestR = mysql_fetch_row($result);
        if ($compTestR[0]>0)
        {
            $obj->userData['hasCompletedCompetence'] = TRUE;
        }else
        {
            $obj->userData['hasCompletedCompetence'] = FALSE;
        }
        
        if ($wo->amIA('tutor'))
        {
            $obj->userData['hasCompletedCompetence'] = TRUE;
        }
 /*
        if ($userData['matchingLRN'] > 0 ||
            $userData['analogiesLRN'] > 0 ||
            $userData['subsetsLRN'] > 0 ||
            $userData['intersectionsLRN'] > 0 ||
            $userData['constructionLRN'] > 0 ||
            $userData['reconstructionLRN'] > 0 ||
            $userData['kinaestheticLSTYL'] > 0 ||
            $userData['visualLSTYL'] > 0 ||
            $userData['intrapersonalLSTYL'] > 0 ||
            $userData['auditoryLSTYL'] > 0 ||
            $userData['linguisticLSTYL'] > 0 ||
            $userData['interpersonalLSTYL'] > 0 ||
            $userData['rightHEMIS'] > 0 ||
            $userData['leftHEMIS'] > 0 ||
            $userData['rulesLRN'] > 0 ) {
            $obj->userData['hasCompletedPsychometric'] = TRUE;
        }else
        {
             $obj->userData['hasCompletedPsychometric'] = FALSE;
        }

        unset($userData['matchingLRN']);
        unset($userData['analogiesLRN']);
        unset($userData['subsetsLRN']);
        unset($userData['intersectionsLRN']);
        unset($userData['constructionLRN']);
        unset($userData['reconstructionLRN']);
        unset($userData['kinaestheticLSTYL']);
        unset($userData['visualLSTYL']);
        unset($userData['intrapersonalLSTYL']);
        unset($userData['auditoryLSTYL']);
        unset($userData['linguisticLSTYL']);
        unset($userData['interpersonalLSTYL']);
        unset($userData['rightHEMIS']);
        unset($userData['leftHEMIS']);
        unset($userData['rulesLRN']); */
    }else
    {
        showErrorAndTerminate('4000', 'Session is invalid. User is not logged in.');
    }
}elseif ($_REQUEST['action']=='wsAvailableClasses') //7000
{
    $scheduler = new WOOOF_dataBaseTable($wo->db, 'mvp_scheduler');
    $whereClauses2 = array();
    $whereClauses2['active']='1';
    if (!isset($_REQUEST['targetDate']))
    {
        showErrorAndTerminate('7000', 'This service requires a target date to be sent.');
    }
    $datePieces =$wo->breakDateTime($_REQUEST['targetDate']);
    if (!checkdate($datePieces['month'], $datePieces['day'], $datePieces['year']))
    {
        showErrorAndTerminate('7001', 'The target date specified is not valid.');
    }
    $whereClauses2['scheduledDateTime>'] = $datePieces['year'] . $datePieces['month']. $datePieces['day']. '000000';
    $whereClauses2['scheduledDateTime<'] = $datePieces['year'] . $datePieces['month']. $datePieces['day']. '999999';
    
    $scheduler->getResult($whereClauses2, 'scheduledDateTime');
    
    $timesAllocated = array();
    
    for($z=0; $z<count($scheduler->resultRows)/2; $z++)
    {
        //// WARNING !!! the following ugly hack kills the awareness of the front-end whether the user has a registered class at the specific hour. 
        if (isset($timesAllocated[$scheduler->resultRows[$z]['scheduledDateTime']]))
        {
            continue;
        }
        $timesAllocated[$scheduler->resultRows[$z]['scheduledDateTime']] = TRUE;
        $tutor = $wo->db->getRowByColumn('userData','user',$scheduler->resultRows[$z]['tutorId']);
        $scheduleItem = new stdClass();
        $scheduleItem->id = $scheduler->resultRows[$z]['id'];
        $scheduleItem->title = 'Tutor: '. $tutor['firstname'] .' '. $tutor['surname'];
        $scheduleItem->type = $scheduler->resultRows[$z]['type'];
        $datePieces = $wo->breakDateTime($scheduler->resultRows[$z]['scheduledDateTime']);
        $scheduleItem->start = $datePieces['year'].'-'.$datePieces['month'].'-'.$datePieces['day'].'T'.$datePieces['hour'].':'.$datePieces['minute'].':00';

        //$scheduleItem->start = $scheduler->resultRows[$z]['scheduledDateTime'];

        $dateObject = date_create($datePieces['year'].':'.$datePieces['month'].':'.$datePieces['day'].' '.$datePieces['hour'].':'.$datePieces['minute'].':00');
        $dateObject2 = $dateObject->modify('+1 hour');
        $scheduleItem->end = $dateObject2->format('Y-m-d\TH:i:s');
        if ($scheduler->resultRows[$z]['type']=='a2')
        {
            $noSeats=5;
        }else
        {
            $noSeats=1;
        }
        $scheduleItem->seatsAvailable = ($noSeats - (int)$scheduler->resultRows[$z]['participantsCount']);

        $obj->scheduled[] = $scheduleItem;
    }    
    
}elseif ($_REQUEST['action']=='wsCreateClass')   //11000
{
    if ($wo->amIA('tutor'))
    {
        $scheduler = new WOOOF_dataBaseTable($wo->db, 'mvp_scheduler');
        $columnsToFill = array();
        $columnsToFill[]='active';
        $columnsToFill[]='tutorId';
        $columnsToFill[]='scheduledDateTime';
        $columnsToFill[]='type';
        $columnsToFill[]='participantsCount';
        
        $_POST['active']='1';
        $_POST['tutorId']=$userData['id'];
        $_POST['participantsCount']=0;

        if (!validateDate($_REQUEST['scheduledDateTime']) || (substr($_REQUEST['scheduledDateTime'],-4)!='3000' && substr($_REQUEST['scheduledDateTime'],-4)!='0000'))
        {
            showErrorAndTerminate('11002', 'Invalid Date or Time specified!');
        }

        if ($_POST['type']!='a1' && $_POST['type']!='a2')
        {
            showErrorAndTerminate('11001', 'Wrong Class Type specified!');
        }

        $scheduler->handleInsertFromPost($columnsToFill); 
    }else
    {
        showErrorAndTerminate('11000', 'Only tutors can create new Classes.');
    }
}elseif ($_REQUEST['action']=='wsGetClassesOwned') //6000
{
    if ($wo->amIA('tutor'))
    {
        
        $result = $wo->db->query('select * from mvp_scheduler where tutorId = \''. $userData['id'] .'\' AND scheduledDateTime>=\''. date('Ymd').'000000' .'\' order by scheduledDateTime');
        while($row = mysql_fetch_assoc($result))
        {
            $datePieces = $wo->breakDateTime($row['scheduledDateTime']);
            $row['start'] = $datePieces['year'].'-'.$datePieces['month'].'-'.$datePieces['day'].'T'.$datePieces['hour'].':'.$datePieces['minute'].':00';
            $dateObject = date_create($datePieces['year'].':'.$datePieces['month'].':'.$datePieces['day'].' '.$datePieces['hour'].':'.$datePieces['minute'].':00');
            $dateObject2 = $dateObject->modify('+1 hour');
            $row['end'] = $dateObject2->format('Y-m-d\TH:i:s');
            if ($row['type']=='a2')
            {
                $noSeats=5;
            }else
            {
                $noSeats=1;
            }
            $row['seatsAvailable'] = $noSeats - $row['participantsCount'];
            $obj->ownedClasses[] = $row;
        }
    }else
    {
        showErrorAndTerminate('6000', 'Students cannot use this service. Only tutors have own classes.');
    }
}elseif ($_REQUEST['action']=='wsGetRegisteredClasses')  //8000
{
    if ($wo->amIA('student') || $wo->amIA('parent'))
    {
        if ($wo->amIA('parent'))
        {
            $userData['id'] = getEffectiveUserId();
        }
        $result = $wo->db->query('select mvp_scheduler.* from mvp_scheduler, mvp_schedulerParticipants where mvp_scheduler.id = mvp_schedulerParticipants.schedulerId AND mvp_schedulerParticipants.studentId = \''. $userData['id'] .'\' order by scheduledDateTime');
        while($row = mysql_fetch_assoc($result))
        {
            $tutor = $wo->db->getRowByColumn('userData','user',$row['tutorId']);
            $row['title'] = $tutor['firstname'] .' '. $tutor['surname'];
            $datePieces = $wo->breakDateTime($row['scheduledDateTime']);
            $row['start'] = $datePieces['year'].'-'.$datePieces['month'].'-'.$datePieces['day'].'T'.$datePieces['hour'].':'.$datePieces['minute'].':00';
            $dateObject = date_create($datePieces['year'].':'.$datePieces['month'].':'.$datePieces['day'].' '.$datePieces['hour'].':'.$datePieces['minute'].':00');
            $dateObject2 = $dateObject->modify('+1 hour');
            $row['end'] = $dateObject2->format('Y-m-d\TH:i:s');
            if ($row['type']=='a2')
            {
                $noSeats=5;
            }else
            {
                $noSeats=1;
            }
            $row['seatsAvailable'] = $noSeats - $row['participantsCount'];
            $obj->registeredClasses[] = $row;
        }
    }else
    {
        showErrorAndTerminate('8000', 'Tutors cannot use this service. Only students have registered classes.');
    }
}elseif ($_REQUEST['action']=='wsUpdatePersonalInfo')  //9000
{ 
    //avatar userText
    $userDataT = new WOOOF_dataBaseTable($wo->db, 'userData');
    
    $whereClauses = array();
    $whereClauses['user'] = $userData['id'];
    
    $userDataT->getResult($whereClauses);
    
    if (isset($_FILES['avatar']))
    {
        $columnsToFill[] = 'avatar';
    }
    if ($wo->amIA('student'))
    {
        $columnsToFill[] = 'userText';
    }
    
    $userDataT->updateRowFromPost($userDataT->resultRows[0]['id'], $columnsToFill);
    $userDataT->getResult($whereClauses);
    
    $obj->newImageName = $userDataT->resultRows[0]['avatar'];

}elseif ($_REQUEST['action']=='wsRegisterToClass')   //10000
{
    if ($wo->amIA('student'))
    {
        $wo->db->query('BEGIN');
        
        if (!isset($_REQUEST['classId']))
        {
            showErrorAndTerminate('10001', 'Invalid class id provided.');
        }

        $schedulerR = $wo->db->query('select * from mvp_scheduler where id=\''. $wo->cleanUserInput($_REQUEST['classId']) .'\' FOR UPDATE');
        $scheduler = mysql_fetch_assoc($schedulerR);
        
        if (!isset($scheduler['id']))
        {
            showErrorAndTerminate('10001', 'Invalid class id provided.');
        }

        if (!validateDate($scheduler['scheduledDateTime']) || (substr($scheduler['scheduledDateTime'],-4)!='3000' && substr($scheduler['scheduledDateTime'],-4)!='0000'))
        {
            showErrorAndTerminate('10006', 'Invalid Date or Time specified!');
        }

        if (($scheduler['participantsCount']>0 && $scheduler['type']=='a1') || $scheduler['participantsCount']>4)
        {
            showErrorAndTerminate('10002', 'Class is already fully booked');
        }
        
        $partR = $wo->db->query('select * from mvp_schedulerParticipants where schedulerId=\''. $scheduler['id'] .'\' and studentId=\''. $userData['id'] .'\'');
        
        if (mysql_num_rows($partR))
        {
            showErrorAndTerminate('10003', 'You are already registered for that class.');
        }
        
        if ($scheduler['scheduledDateTime'] < $wo->getCurrentDateTime())
        {
            showErrorAndTerminate('10004', 'Cannot register for classes that have already begun ...');
        }
        
        $bookedSameTimeR = $wo->db->query('select * from mvp_scheduler, mvp_schedulerParticipants where mvp_schedulerParticipants.schedulerId = mvp_scheduler.id and mvp_schedulerParticipants.studentId=\''. $userData['id'] .'\' and mvp_scheduler.id != \''. $scheduler['id'] .'\' and mvp_scheduler.scheduledDateTime=\''. $scheduler['scheduledDateTime'] .'\'');
        if (mysql_num_rows($bookedSameTimeR)>0)
        {
            showErrorAndTerminate('10005', 'Cannot register at that time. You have already registered in another class.');
        }

        $wo->db->query('insert into mvp_schedulerParticipants (id, schedulerId, studentId, ord) values '
                . '(\''. $wo->db->getNewId('mvp_schedulerParticipants') .'\', \''. $scheduler['id'] .'\', \''. $userData['id'] 
                .'\', \''. ($scheduler['participantsCount']+1) .'\')');
        $wo->db->query('update mvp_scheduler set participantsCount=participantsCount+1 where id=\''. $scheduler['id'] .'\'');
        $wo->db->query('COMMIT');
    }else
    {
        showErrorAndTerminate('10000', 'Tutors cannot use this service. Only students can register to classes.');
    }
}elseif ($_REQUEST['action']=='wsGetHourlyTutors') //12000
{
    if ($wo->amIA('student') || $wo->amIA('parent'))
    {
        if ($wo->amIA('parent'))
        {
            $userData['id'] = getEffectiveUserId();
        }
        $bookedSameTimeR = $wo->db->query('select * from mvp_scheduler, mvp_schedulerParticipants where mvp_schedulerParticipants.schedulerId = mvp_scheduler.id and mvp_schedulerParticipants.studentId=\''. $userData['id'] .'\' and mvp_scheduler.scheduledDateTime=\''. substr($wo->cleanUserInput($_REQUEST['targetDateTime']),0,12) .'00' .'\'');
        if (mysql_num_rows($bookedSameTimeR)>0)
        {
            $bookedScheduler = mysql_fetch_assoc($bookedSameTimeR);
            showErrorAndTerminate('12001', 'Cannot register at that time. You have already registered in another class: '. $bookedScheduler['id']);
        }

        if (!validateDate($_REQUEST['targetDateTime']) || (substr($_REQUEST['targetDateTime'],-4)!='3000' && substr($_REQUEST['targetDateTime'],-4)!='0000')  || $_REQUEST['targetDateTime']<$wo->getCurrentDateTime())
        {
            showErrorAndTerminate('12002', 'Invalid Date or Time specified!');
        }

        $result = $wo->db->query('select distinct userData.*, mvp_scheduler.id as classId, mvp_scheduler.type from userData, mvp_scheduler where mvp_scheduler.tutorId = userData.user and mvp_scheduler.scheduledDateTime = \''. $wo->cleanUserInput($_REQUEST['targetDateTime']) .'\'');
        
        $obj->private = array();
        $obj->group = array();

        while ($row = mysql_fetch_assoc($result)) 
        {
            $row['affinity'] = generateUserAfinity($row);
            if ($row['type']=='a1')
            {
                $obj->private[] = $row;
            }else
            {
                $obj->group[] = $row;
            }
        }

        usort($obj->private, 'cmp_by_affinity');
        usort($obj->group, 'cmp_by_affinity');

    }else
    {
        showErrorAndTerminate('12000', 'Tutors cannot use this service. Only students can get hourly classes for registration.');
    }
}elseif ($_REQUEST['action']=='wsGetClassParticipants') //13000
{
    
    if (!isset($_REQUEST['classId']))
    {
        showErrorAndTerminate('13000', 'No class id provided.');
    }

    $scheduler = $wo->db->getRow('mvp_scheduler', $_REQUEST['classId']);

    if (!isset($scheduler['id']))
    {
        showErrorAndTerminate('13001', 'Invalid class id provided.');
    }

    $partR = $wo->db->query('select * from mvp_schedulerParticipants where schedulerId=\''. $scheduler['id'] .'\' and studentId=\''. $userData['id'] .'\'');
    if ($userData['id'] == $scheduler['tutorId'] || mysql_num_rows($partR))
    {
        $participants = new WOOOF_dataBaseTable($wo->db,'mvp_schedulerParticipants');
        $whereClauses['schedulerId']=$scheduler['id'];
        $participants->getResult($whereClauses, 'ord');
        $tutor = $wo->db->getRowByColumn('userData','user',$scheduler['tutorId']);

        $filteredTutor['firstname'] = $tutor['firstname'];
        $filteredTutor['surname'] = $tutor['surname'];
        $filteredTutor['avatar'] = $tutor['avatar'];
        $filteredTutor['id'] = $tutor['user'];

        $obj->tutor = $filteredTutor;
        for($p=0; $p<count($participants->resultRows)/2; $p++)
        {
            $filteredParticipant = array();
            $participant = $wo->db->getRowByColumn('userData', 'user', $participants->resultRows[$p]['studentId']);
            $filteredParticipant['id'] = $participant['user'];
            $filteredParticipant['firstname'] = $participant['firstname'];
            $filteredParticipant['surname'] = $participant['surname'];
            $filteredParticipant['avatar'] = $participant['avatar'];
            $filteredParticipant['order'] = $participants->resultRows[$p]['ord'];
            $filteredParticipant['dominantMIs'] = getDominantMIsForUser($participant); 
            $obj->participants[]= $filteredParticipant;
        }
        
    }else
    {
        showErrorAndTerminate('13002', 'You are not a participant in this class.');
    }
}elseif ($_REQUEST['action']=='wsGetClassStatus') //14000
{
    if (!isset($_REQUEST['classId']))
    {
        showErrorAndTerminate('14000', 'No class id provided.');
    }

    $scheduler = $wo->db->getRow('mvp_scheduler', $_REQUEST['classId']);

    if (!isset($scheduler['id']))
    {
        showErrorAndTerminate('14001', 'Invalid class id provided.');
    }

    if (!isset($_REQUEST['timePoint']))
    {
        showErrorAndTerminate('14002', 'No timePoint provided.');
    }

    if (!validateDate($_REQUEST['timePoint']))
    {
        showErrorAndTerminate('14003', 'Invalid timePoint provided.');
    }
    
    $partR = $wo->db->query('select * from mvp_schedulerParticipants where schedulerId=\''. $scheduler['id'] .'\' and studentId=\''. $userData['id'] .'\'');
    
    if ($userData['id'] == $scheduler['tutorId'] || mysql_num_rows($partR))
    {
        $statusR = $wo->db->query('select mvp_schedulerActions.*, mvp_schedulerActionTypes.name as actionTypeName From mvp_schedulerActions, mvp_schedulerActionTypes where schedulerId=\''. $scheduler['id'] .'\' and (toUser=\''. $userData['id'] .'\' || toAllUsers=\'1\' || fromUser=\''. $userData['id'] .'\') and timePoint>\''. $wo->cleanUserInput($_REQUEST['timePoint']) .'\' and mvp_schedulerActionTypes.id = mvp_schedulerActions.actionType order by timePoint');
        $obj->statusItems = array();
        while($row = mysql_fetch_assoc($statusR))
        {
            $obj->statusItems[] = array('fromUser' => $row['fromUser'], 'toAllUsers' => $row['toAllUsers'], 'toUser' => $row['toUser'], 'actionType' => $row['actionTypeName'], 'timePoint' => $row['timePoint'], 'txtContent' => $row['txtContent']);
            $obj->latestTimePoint = $row['timePoint'];
        }

        $participantsR = $wo->db->query('select * from mvp_schedulerParticipants where schedulerId=\''. $scheduler['id'] .'\'');
        while ($participant = mysql_fetch_assoc($participantsR)) 
        {
            if ($participant['insideClass']==1)
            {
                $obj->participantsInClass[]=$participant['studentId'];
                $obj->participantsLessonStage[$participant['studentId']] = 'D12';
            }
            $obj->buttons[] = array ('studentId' => $participant['studentId'], 'btnRaise' => $participant['btnRaise'], 'btnNo' => $participant['btnNo'], 'btnYes' => $participant['btnYes'], 'btnThumb' => $participant['btnThumb'], ); 
        }
        $obj->tutorInsideClassroom = $scheduler['tutorInsideClass'];    
        if (!isset($obj->latestTimePoint))
        {
            $statusR = $wo->db->query('select mvp_schedulerActions.*, mvp_schedulerActionTypes.name as actionTypeName From mvp_schedulerActions, mvp_schedulerActionTypes where schedulerId=\''. $scheduler['id'] .'\' and (toUser=\''. $userData['id'] .'\' || toAllUsers=\'1\' || fromUser=\''. $userData['id'] .'\') and mvp_schedulerActionTypes.id = mvp_schedulerActions.actionType order by timePoint desc limit 1');
            if (!mysql_num_rows($statusR))
            {
                $obj->latestTimePoint = $scheduler['scheduledDateTime'];
            }else
            {
                $lastAction = mysql_fetch_assoc($statusR);
                $obj->latestTimePoint = $lastAction['timePoint'];
            }
        }
    }else
    {
        showErrorAndTerminate('14004', 'You are not a participant in this class.');
    }
}elseif ($_REQUEST['action']=='wsEnteredClassroom') //15000
{
    if (!isset($_REQUEST['classId']))
    {
        showErrorAndTerminate('15000', 'No class id provided.');
    }

    $scheduler = $wo->db->getRow('mvp_scheduler', $wo->cleanUserInput($_REQUEST['classId']));

    if (!isset($scheduler['id']))
    {
        showErrorAndTerminate('15001', 'Invalid class id provided.');
    }
    
    $partR = $wo->db->query('select * from mvp_schedulerParticipants where schedulerId=\''. $scheduler['id'] .'\' and studentId=\''. $userData['id'] .'\'');
    
    $userDataX = $wo->db->getRowByColumn('userData', 'user', $userData['id']);

    if ($userData['id'] == $scheduler['tutorId'] || mysql_num_rows($partR))
    {
        $wo->db->query('insert into mvp_schedulerActions set
        id = \''. $wo->db->getNewId('mvp_schedulerActions') .'\',
        fromUser = \''. $userData['id'] .'\',
        toUser = \'\',
        actionType = \'a2\',
        toAllUsers = \'1\',
        timePoint = \''. $wo->getCurrentDateTime() .'\',
        txtContent = \'User '. $userDataX['firstname'] .' '. $userDataX['surname'] .' entered the class room.\',
        schedulerId=\''. $scheduler['id'] .'\'');
        if ($userData['id'] != $scheduler['tutorId'])
        {
            $wo->db->query('update mvp_schedulerParticipants set insideClass=\'1\' where studentId=\''. $userData['id'] .'\' and schedulerId=\''. $scheduler['id'] .'\'');
        }else
        {
            $wo->db->query('update mvp_scheduler set tutorInsideClass=\'1\' where id=\''. $scheduler['id'] .'\'');
        }
    }else
    {
        showErrorAndTerminate('15002', 'You are not a participant in this class.');
    }
}elseif ($_REQUEST['action']=='wsLeftClassroom') //17000
{
    if (!isset($_REQUEST['classId']))
    {
        showErrorAndTerminate('17000', 'No class id provided.');
    }

    $scheduler = $wo->db->getRow('mvp_scheduler', $wo->cleanUserInput($_REQUEST['classId']));

    if (!isset($scheduler['id']))
    {
        showErrorAndTerminate('17001', 'Invalid class id provided.');
    }
    
    $partR = $wo->db->query('select * from mvp_schedulerParticipants where schedulerId=\''. $scheduler['id'] .'\' and studentId=\''. $userData['id'] .'\'');
    
    $userDataX = $wo->db->getRowByColumn('userData', 'user', $userData['id']);

    if ($userData['id'] == $scheduler['tutorId'] || mysql_num_rows($partR))
    {
        $wo->db->query('insert into mvp_schedulerActions set
        id = \''. $wo->db->getNewId('mvp_schedulerActions') .'\',
        fromUser = \''. $userData['id'] .'\',
        toUser = \'\',
        actionType = \'a3\',
        toAllUsers = \'1\',
        timePoint = \''. $wo->getCurrentDateTime() .'\',
        txtContent = \'User '. $userDataX['firstname'] .' '. $userDataX['surname'] .' entered the class room.\',
        schedulerId=\''. $scheduler['id'] .'\'');
        if ($userData['id'] != $scheduler['tutorId'])
        {
            $wo->db->query('update mvp_schedulerParticipants set insideClass=\'0\' where userData=\''. $userData['id'] .'\' and schedulerId=\''. $scheduler['id'] .'\'');
        }else
        {
            $wo->db->query('update mvp_scheduler set tutorInsideClass=\'0\' where id=\''. $scheduler['id'] .'\'');
        }
    }else
    {
        showErrorAndTerminate('17002', 'You are not a participant in this class.');
    }
}elseif ($_REQUEST['action']=='wsChatMessage') //16000
{
    if (!isset($_REQUEST['classId']))
    {
        showErrorAndTerminate('16000', 'No class id provided.');
    }

    $scheduler = $wo->db->getRow('mvp_scheduler', $wo->cleanUserInput($_REQUEST['classId']));

    if (!isset($scheduler['id']))
    {
        showErrorAndTerminate('16001', 'Invalid class id provided.');
    }
    
    $partR = $wo->db->query('select * from mvp_schedulerParticipants where schedulerId=\''. $scheduler['id'] .'\' and studentId=\''. $userData['id'] .'\'');
    
    if ($userData['id'] == $scheduler['tutorId'] || mysql_num_rows($partR))
    {
        if ($wo->cleanUserInput($_REQUEST['toUser'])=='' && $_REQUEST['toAllUsers']!='1')
        {
            showErrorAndTerminate('16003', 'Recipient (toUser) cannot be empty when public message flag (toAllUsers) is empty.');
        }else
        {
            $isUserR = $wo->db->query('select id from userData where user=\''. $wo->cleanUserInput($_REQUEST['toUser']) .'\'');
            if ($_REQUEST['toUser']!='' && !mysql_num_rows($isUserR))
            {
                showErrorAndTerminate('16004', 'There is no such user as the supplied recipient.');
            }else
            {
                $isParticipantR = $wo->db->query('select id from mvp_schedulerParticipants where studentId=\''. $wo->cleanUserInput($_REQUEST['toUser']) .'\' and schedulerId=\''. $scheduler['id'] .'\'');
                if ($_REQUEST['toUser']!='' && !mysql_num_rows($isParticipantR) && $scheduler['tutorId']!=$_REQUEST['toUser'])
                {
                    showErrorAndTerminate('16005', 'There is no such user as the supplied recipient in this class.');
                }else
                {
                    $wo->db->query('insert into mvp_schedulerActions set
                    id = \''. $wo->db->getNewId('mvp_schedulerActions') .'\',
                    fromUser = \''. $userData['id'] .'\',
                    toUser = \''. $wo->cleanUserInput($_REQUEST['toUser']) .'\',
                    actionType = \'a1\',
                    toAllUsers = \''. $wo->cleanUserInput($_REQUEST['toAllUsers']) .'\',
                    timePoint = \''. $wo->getCurrentDateTime() .'\',
                    txtContent = \''. $wo->cleanUserInput($_REQUEST['txtContent']) .'\',
                    schedulerId=\''. $scheduler['id'] .'\'');
                }
            }
        }
    }else
    {
        showErrorAndTerminate('16002', 'You are not in this class.');
    }
}elseif ($_REQUEST['action']=='wsButtonAction') //18000
{
    if (!isset($_REQUEST['classId']))
    {
        showErrorAndTerminate('18000', 'No class id provided.');
    }

    $scheduler = $wo->db->getRow('mvp_scheduler', $wo->cleanUserInput($_REQUEST['classId']));

    if (!isset($scheduler['id']))
    {
        showErrorAndTerminate('18001', 'Invalid class id provided.');
    }

    $partR = $wo->db->query('select * from mvp_schedulerParticipants where schedulerId=\''. $scheduler['id'] .'\' and studentId=\''. $userData['id'] .'\'');

    $userDataX = $wo->db->getRowByColumn('userData', 'user', $userData['id']);

    if ($userData['id'] == $scheduler['tutorId'] && !mysql_num_rows($partR))
    {
        showErrorAndTerminate('18004', 'Tutors don\'t click buttons in classes.');
    }

    if (mysql_num_rows($partR))
    {
        if ($_REQUEST['pressed']=='1' || $_REQUEST['pressed']=='0')
        {
            if ($_REQUEST['whichButton']!='btnThumb' && $_REQUEST['whichButton']!='btnYes' && $_REQUEST['whichButton']!='btnNo' && $_REQUEST['whichButton']!='btnRaise')
            {
                showErrorAndTerminate('18005', 'Wrong button requested. Only btnThumb, btnYes, btnNo, btnRaise are supported');
            }else
            {
                $wo->db->query('update mvp_schedulerParticipants set '. $wo->cleanUserInput($_REQUEST['whichButton']) .'=\''. $wo->cleanUserInput($_REQUEST['pressed']) .'\' where studentId=\''. $userData['id'] .'\' and schedulerId=\''. $scheduler['id'] .'\'');
            }
        }else
        {
             showErrorAndTerminate('18003', 'Pressed parameter should be either one or zero.');
        }
    }else
    {
        showErrorAndTerminate('18002', 'You are not a participant in this class.');
    }
}elseif ($_REQUEST['action']=='wsGetNextLessonStage') //19000
{
    /*$lesson = $wo->db->getRow('mvp_data', 'D12');
    $obj->lessonContent = nl2br($lesson['actualData']);
    $obj->lessonType = $lesson['dataType'];
    exit;*/
    
    if (!isset($_REQUEST['classId']))
    {
        showErrorAndTerminate('19000', 'No class id provided.');
    }

    $scheduler = $wo->db->getRow('mvp_scheduler', $wo->cleanUserInput($_REQUEST['classId']));

    if (!isset($scheduler['id']))
    {
        showErrorAndTerminate('19001', 'Invalid class id provided.');
    }
    
    $partR = $wo->db->query('select * from mvp_schedulerParticipants where schedulerId=\''. $scheduler['id'] .'\' and studentId=\''. $userData['id'] .'\'');
    
    if ($userData['id'] == $scheduler['tutorId'] || mysql_num_rows($partR))
    {
        if ($wo->amIA('student'))
        {
            if ($_REQUEST['userId']!=$userData['id'])
            {
                showErrorAndTerminate('19003', 'A student cannot request class data for other students.');
            }
        }else
        {
            if ($scheduler['tutorId']!=$userData['id'])
            {
                showErrorAndTerminate('19004', 'A tutor cannot request data from other classes.');
            }else
            {
                $partR = $wo->db->query('select * from mvp_schedulerParticipants where schedulerId=\''. $scheduler['id'] .'\' and studentId=\''. $wo->db->cleanUserInput($_REQUEST['userId']) .'\'');
                if (!mysql_num_rows($partR))
                {
                    showErrorAndTerminate('19005', 'The requested user is not a participant in this class.');
                }
                if ($_REQUEST['fetchNext']=='1')
                {
                    showErrorAndTerminate('19006', 'The Tutor cannot advance the student\'s lesson.');
                }
            }
        }
        // checks done
        //fetch current part

        $posR = $wo->db->query('select * from mvp_userLessonPosition where userId=\''. $wo->cleanUserInput($_REQUEST['userId']) .'\'');
        if (!mysql_num_rows($posR))
        {
            //never taken lesson before.

            $lesson = getNextTheory($_REQUEST['userId'], FALSE);
            showLesson($lesson);

            $wo->db->query('insert into mvp_userLessonPosition set userId=\''. $wo->cleanUserInput($_REQUEST['userId']) .'\', dataId=\''. $lesson['id'] .'\', id=\''. $wo->db->getNewId('mvp_userLessonPosition') .'\', state_TMP=\'1\'');
        }else
        {
            $pos = mysql_fetch_assoc($posR);
            $lesson = $wo->db->getRow('mvp_data',$pos['dataId']);
            if (isset($_REQUEST['fetchNext']) && $_REQUEST['fetchNext']=='1')
            {
                //has lesson and needs next piece
                if ($pos['state_TMP']=='1')
                {
                    $exampleR = $wo->db->query('select * from mvp_data where dataType=\'DT2\' and relevantTheory=\''. $pos['dataId'] .'\' order by RAND() limit 1');
                    if(mysql_num_rows($exampleR))
                    {
                        $example = mysql_fetch_assoc($exampleR);
                        $wo->db->query('update mvp_userLessonPosition set state_TMP=\'2\', dataId=\''. $example['id'] .'\' where id=\''. $pos['id'] .'\'');
                        showLesson($example);
                    }   
                }elseif ($pos['state_TMP']=='2')
                {
                    $questionR = $wo->db->query('select * from mvp_data where dataType=\'DT1\' and relevantTheory=\''. $lesson['relevantTheory'] .'\' order by RAND() limit 1');
                    if(mysql_num_rows($questionR))
                    {
                        $question = mysql_fetch_assoc($questionR);
                        $wo->db->query('update mvp_userLessonPosition set state_TMP=\'3\', dataId=\''. $question['id'] .'\' where id=\''. $pos['id'] .'\'');
                        showLesson($question);
                    }else
                    {
                        showErrorAndTerminate('19010', 'Internal Error. Inconsistent state. Question not found in filtered theory.');
                    }    
                }elseif ($pos['state_TMP']=='3')
                {
                    if (!isset($_REQUEST['answer']))
                    {
                        showErrorAndTerminate('19008', 'You cannot get next part without answering.');
                    }
                    
                    if (scoreQuestion($lesson, $_REQUEST['answer']))
                    {
                        $relevantTheory = $wo->db->getRow('mvp_data', $lesson['relevantTheory']);
                        $lesson = getNextTheory($userData['id'], $relevantTheory['ord']); // correct answer, fetch next theory
                        showLesson($lesson);
                        $wo->db->query('update mvp_userLessonPosition set state_TMP=\'1\', dataId=\''. $lesson['id'] .'\' where id=\''. $pos['id'] .'\'');
                    }else
                    {
                        // wrong answer, fetch other example 
                        $exampleR = $wo->db->query('select * from mvp_data where dataType=\'DT2\' and relevantTheory=\''. $pos['dataId'] .'\' order by RAND() limit 1');
                        $example = mysql_fetch_assoc($exampleR);
                        $wo->db->query('update mvp_userLessonPosition set state_TMP=\'4\', dataId=\''. $example['id'] .'\' where id=\''. $pos['id'] .'\'');
                        showLesson($example);
                    }
                }elseif ($pos['state_TMP']=='4')
                {
                    $exampleR = $wo->db->query('select * from mvp_data where dataType=\'DT2\' and relevantTheory=\''. $lesson['relevantTheory'] .'\' and id != \''. $lesson['id'] .'\' order by RAND() limit 1');
                    $example = mysql_fetch_assoc($exampleR);
                    $wo->db->query('update mvp_userLessonPosition set state_TMP=\'5\', dataId=\''. $example['id'] .'\' where id=\''. $pos['id'] .'\'');
                    showLesson($example);
                }elseif ($pos['state_TMP']=='5')
                {
                    $questionR = $wo->db->query('select * from mvp_data where dataType=\'DT1\' and relevantTheory=\''. $lesson['relevantTheory'] .'\' order by RAND() limit 1');
                    if(mysql_num_rows($questionR))
                    {
                        $question = mysql_fetch_assoc($questionR);
                        $wo->db->query('update mvp_userLessonPosition set state_TMP=\'6\', dataId=\''. $question['id'] .'\' where id=\''. $pos['id'] .'\'');
                        showLesson($question);
                    }else
                    {
                        $obj->query = 'select * from mvp_data where dataType=\'DT1\' and relevantTheory=\''. $lesson['relevantTheory'] .'\' order by RAND() limit 1';
                        showErrorAndTerminate('19010b', 'Internal Error. Inconsistent state. Question not found in filtered theory.');
                    } 
                }elseif ($pos['state_TMP']=='6')
                {
                    if (!isset($_REQUEST['answer']))
                    {
                        showErrorAndTerminate('19009', 'You cannot get next part without answering at PART2');
                    }
                    
                    if (scoreQuestion($lesson, $_REQUEST['answer']))
                    {
                        $relevantTheory = $wo->db->getRow('mvp_data', $lesson['relevantTheory']);
                        $lesson = getNextTheory($userData['id'], $relevantTheory['ord']); // correct answer, fetch next theory
                        showLesson($lesson);
                        $wo->db->query('update mvp_userLessonPosition set state_TMP=\'1\', dataId=\''. $lesson['id'] .'\' where id=\''. $pos['id'] .'\'');
                    }else
                    {
                        // wrong answer, restart and set panic button
                        $exampleR = $wo->db->query('select * from mvp_data where id=\''. $lesson['relevantTheory'] .'\'');
                        $example = mysql_fetch_assoc($exampleR);
                        
                        $partR = $wo->db->query('select * From mvp_schedulerParticipants where schedulerId=\''. $example['id'] .'\' and studentId=\''. $userData['id'] .'\'');
                        $participation = mysql_fetch_assoc($partR);
                        $wo->db->query('update mvp_schedulerParticipants set panicActive=\'1\' where id=\''. $participation['id'] .'\'');
                        
                        $wo->db->query('update mvp_userLessonPosition set state_TMP=\'1\', dataId=\''. $example['id'] .'\' where id=\''. $pos['id'] .'\'');
                        showLesson($example);
                    }
                }else
                {
                    showErrorAndTerminate('19011', 'Inconsistent state! You have discovered an internal state we never thought possible !');
                }
            }else
            {
                //show current location
                showLesson($lesson);
            }
        }
    }else
    {
        showErrorAndTerminate('19002', 'You are not a participant in this class.');
    }
}elseif ($_REQUEST['action']=='wsStudentProfile') //20000
{
    $userDataX = $wo->db->getRowByColumn('userData', 'user', $wo->cleanUserInput($_REQUEST['studentId']));
    if (!isset($userDataX['id']))
    {
        showErrorAndTerminate('20000', 'Invalid student ID.');
    }else
    {
        if ($userDataX['userType']!='1a')
        {
            showErrorAndTerminate('20001', 'Requested id doesn\'t belong to a student.');
        }else
        {
            $obj->name = $userDataX['firstname'] .' '. $userDataX['surname'];
            $obj->avatar = $userDataX['avatar'];
            $obj->userText = $userDataX['userText'];      
            $obj->dominantMIs = getDominantMIsForUser($userDataX);
            
            $tR = $wo->db->query('select distinct(mvp_scheduler.tutorId) from mvp_scheduler, mvp_schedulerParticipants where mvp_scheduler.id = mvp_schedulerParticipants.schedulerId and studentId=\''. $userDataX['user'] .'\' order by mvp_scheduler.scheduledDateTime desc limit 2');
            while($tut = mysql_fetch_assoc($tR))
            {
                $tutX = $wo->db->getRowByColumn('userData', 'user', $tut['tutorId']);
                $cR = $wo->db->query('select count(mvp_scheduler.tutorId) from mvp_scheduler, mvp_schedulerParticipants where mvp_scheduler.id = mvp_schedulerParticipants.schedulerId and studentId=\''. $userDataX['user'] .'\' and tutorId=\''. $tutX['user'] .'\'');
                $countLessons = mysql_fetch_row($cR);
                $obj->latestTutors[] = array($tutX['firstname'], $tutX['surname'], $tutX['avatar'], $countLessons[0]);
            }
        }
    }
    
}elseif ($_REQUEST['action']=='wsTutorProfile') //21000
{
    $userDataX = $wo->db->getRowByColumn('userData', 'user', $wo->cleanUserInput($_REQUEST['tutorId']));
    if (!isset($userDataX['id']))
    {
        showErrorAndTerminate('21000', 'Invalid tutor ID.');
    }else
    {
        if ($userDataX['userType']!='2a')
        {
            showErrorAndTerminate('21001', 'Requested id doesn\'t belong to a tutor.');
        }else
        {
            $obj->name = $userDataX['firstname'] .' '. $userDataX['surname'];
            $obj->avatar = $userDataX['avatar'];
            $obj->userText = $userDataX['userText'];      
            $obj->dominantMIs = getDominantMIsForUser($userDataX);
        }
    }
}elseif ($_REQUEST['action']=='wsGetPreTest') //22000
{
    if ($wo->amIA('student'))
    {
        $attachedData = new WOOOF_dataBaseTable($wo->db, 'mvp_dataAssociated');
        $preR = $wo->db->query('select distinct mvp_data.* from mvp_data, mvp_dataAssociated where mvp_data.id = data_id and dataType=\'DT1\' order by rand() limit 5');
        while ($pT = mysql_fetch_assoc($preR))
        {
            $atDR = $wo->db->query('select id, isDeleted, associatedData, data_id, associatedDType from mvp_dataAssociated where data_id=\''. $pT['id'] .'\' and associatedDType=\'ADT1\'');
            $tmp=array();
            while($at = mysql_fetch_row($atDR))
            {
                $tmp[] = $at;
            }
            $questionIds[] = $pT['id'];
            
            $pT['answers']=$tmp;
            $obj->question[] = $pT;
        }
        $newId = $wo->db->getNewId('mvp_preTests');
        $wo->db->query('insert into mvp_preTests set id=\''. $newId .'\', userId=\''. $userData['id'] .'\', questionIds=\''. $wo->cleanUserInput(serialize($questionIds)) .'\'');
        $obj->preTestId = $newId;
    }else
    {
        showErrorAndTerminate('22001', 'Only students can do pre-tests.');
    }
}elseif ($_REQUEST['action']=='wsAnswerPreTest') //23000
{
    if ($wo->amIA('student'))
    {
        $theTest = $wo->db->getRow('mvp_preTests', $wo->cleanUserInput($_REQUEST['preTestId']));
        if (!isset($theTest['id']))
        {
            showErrorAndTerminate('23002', 'Invalid preTestId.');
        }else
        {
            if ($theTest['userId']==$userData['id'])
            {
                $wo->db->query('update mvp_preTests set answers=\''. $wo->cleanUserInput(serialize($_REQUEST['answers'])) .'\'');
            }else 
            {
                showErrorAndTerminate('23003', 'Invalid preTestId or session identifier.');
            }
        }
    }else
    {
        showErrorAndTerminate('23001', 'Only students can do pre-tests.');
    }
}else
{
     showErrorAndTerminate('999999', 'Requested action is not implemented. Check the manual :-)');
}

header('Content-Type: text/html; charset=utf-8');
header('Content-Type: application/json');
echo json_encode($obj);
exit;
