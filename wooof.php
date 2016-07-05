<?php
/*
 * Wipe Out Object Oriented Framework
 * 
 * The handy-dandy mini framework for your site building pleasure.
 * Implements:
 *              - Mysql Database Encapsulation
 *              - Mysql Database Modification and Development
 *              - Automatic input sanitization
 *              - Complete and continuous DB logging so that the database can be recreated up to any point in time
 *              - Location Role Action Based Security (LoRaBaSe)
 *              - Various facilities that save you time and typing (again and again).
 *              - Automated Administration System.
 *              - Much more ...
 * 
 * Copyright Ioannis Loukeris 1996 - 2014.
 * 
 * Open Source Softwqare . Distributed under the GPL2.
 * 
 * PHP:     5.3.4+ required
 * MySQL:   5+ required
 */

// WOOOF Setup is already loaded. In development it would be something like
// setupXXXXXXX.inc.php in production it is setup.inc.php . 
// All setup is done in this file, edit at will.

// WOOOF Definitions - Do not edit unless you know what you are doing!

$WOOOF_VERSION='0.9.23';

class WOOOF_tablePresentationTypes
{
    const   asList                  =   1;
    const   CategorizedList         =   2;
    const   treeView                =   3;
    const   TreeCategorizdedList    =   4;
    const   treeView2               =   5;
    
    public static function getTablePresentationLiteral($type)
    {
        switch ($type) {
            case 1:
                $literal='List';
                break;
            case 2:
                $literal='Categorized List';
                break;
            case 3:
                $literal='Tree View';
                break;
            case 4:
                $literal='Tree Categorized List';
                break;
            case 5:
                $literal='Tree View 2';
                break;
        }
        return $literal;
    }
}

class WOOOF_columnPresentationTypes
{
    const   textBox     =   1;
    const   dropList    =   2;
    const   textArea    =   3;
    const   htmlText    =   4;
    const   checkBox    =   5;
    const   date        =   6;
    const   time        =   7;
    const   dateAndTime =   8;
    const   autoComplete=   9;
    const   radioHoriz  =   10;
    const   radioVert   =   11;
    const   file        =   12;
    const   picture     =   13;
    
    public static function getColumnPresentationLiteral($type)
    {
        switch ($type) {
            case 1:
                $literal='textBox';
                break;
            case 2:
                $literal='dropList';
                break;
            case 3:
                $literal='textArea';
                break;
            case 4:
                $literal='htmlText';
                break;
            case 5:
                $literal='checkBox';
                break;
            case 6:
                $literal='date';
                break;
            case 7:
                $literal='time';
                break;
            case 8:
                $literal='dateAndTime';
                break;
            case 9:
                $literal='autoComplete';
                break;
            case 10:
                $literal='radioHoriz';
                break;
            case 11:
                $literal='radioVert';
                break;
            case 12:
                $literal='file';
                break;
            case 13:
                $literal='picture';
                break;
        }
        return $literal;
    }
}

class WOOOF_dataBaseColumnTypes
{
    const int           = 1;
    const float         = 2;
    const char          = 3;
    const varchar       = 4;
    const decimal       = 5;
    const mediumtext    = 6;
    const longtext      = 7;
    
    public static function getColumnTypeLiteral($type)
    {
        switch ($type) {
            case 1:
                $literal='int';
                break;
            case 2:
                $literal='float';
                break;
            case 3:
                $literal='char';
                break;
            case 4:
                $literal='varchar';
                break;
            case 5:
                $literal='decimal';
                break;
            case 6:
                $literal='mediumtext';
                break;
            case 7:
                $literal='longtext';
                break;
        }
        return $literal;
    }
}

class WOOOF_databaseLoggingModes
{
    const   logAllQueries               =   1;
    const   doNotLogSelects             =   2;
    const   doNotLogSelectsDescrShow    =   3;
}

//Actual Classes start here

/*
 * WOOOF
 * 
 * The framework's main class
 * Automatically initializes the databases on initialization
 * and provides various helper methods for the construction and display of pages
 * It is advisable for performance reasons not to create more than one instance
 * TODO: modify this class to singleton
 * 
 */
class WOOOF
{
    public $dataBases;  // array with all the database objects defined 
    public $db;         // the default database for easy access
    private $dateTime;
    private $currentMicroTime;
    
    public function __construct($checkSessionAndActionAndActivateLogging=TRUE) 
    {
        global $defaultDBIndex;
        global $databaseName;
        global $databaseLog;
        global $fileLog;
        global $logTable;
        global $logFilePath;
        
        global $pageLocation;
        global $requestedAction;
        global $userData;
        global $storeUserPaths;
        global $antiFloodProtection;

        global $__isSiteBuilderPage;
        global $__isAdminPage;
        
        for($dbCount=0; $dbCount<count($databaseName); $dbCount++)
        {
            if ($databaseName[$dbCount] != '')
            {
                $this->dataBases[$dbCount] = new WOOOF_dataBase(microtime(true));
                if ($defaultDBIndex == $dbCount)
                {
                    $this->db = $this->dataBases[$dbCount];
                }
                if ($checkSessionAndActionAndActivateLogging)
                {
                    $this->dataBases[$dbCount]->loggingToDatabase($databaseLog[$dbCount], $logTable[$dbCount]);
                    $this->dataBases[$dbCount]->loggingToFile($fileLog[$dbCount], $logFilePath[$dbCount]);
                }else
                {
                    $this->dataBases[$dbCount]->loggingToDatabase(FALSE, $logTable[$dbCount]);
                    $this->dataBases[$dbCount]->loggingToFile(FALSE, $logFilePath[$dbCount]);
                }
                if ($__isAdminPage == true || $__isSiteBuilderPage == true)
                {
                    $this->dataBases[$dbCount]->setLoggingType(WOOOF_databaseLoggingModes::doNotLogSelectsDescrShow,WOOOF_databaseLoggingModes::doNotLogSelectsDescrShow);
                }
            }
        }
        
        $this->currentMicroTime=microtime(true);
        
        $this->dateTime = date('YmdHis');
        
        
        if ($checkSessionAndActionAndActivateLogging)
        {
            $bR = $this->db->query('select * from __bannedIPs where IP=\''. WOOOF::cleanUserInput($_SERVER['REMOTE_ADDR']) .'\' and banExpiration>\''. $this->dateTime .'\'');
            if(mysql_num_rows($bR))
            {
                die('you are banned!');
                exit;
            }
            
            if (!$this->sessionCheck())
            {
                $this->newSession('0123456789');
            }
            $security = $this->db->getSecurityPermitionsForLocationAndUser($pageLocation, $userData['id']);
            if ($storeUserPaths)
            {
                $this->db->query('insert into __userPaths set sessionId=\''. WOOOF::cleanUserInput($_COOKIE["sid"]) .'\', requestPage=\''. WOOOF::cleanUserInput($_SERVER['REQUEST_URI']) .'\', requestData=\''. WOOOF::cleanUserInput(serialize($_POST)) .'\', timeStamp=\''. $this->dateTime .'\'');
            }
        
            if ($antiFloodProtection>0)
            {
                $requestsLastSecondR = $this->db->query('SELECT count(*) FROM __userPaths where sessionId=\''. WOOOF::cleanUserInput($_COOKIE['sid']) .'\' and timeStamp>\''. date('YmdH'). (date('is') -1) .'\'');
                $requestsLastSecond = mysql_fetch_row($requestsLastSecondR);
                if ($requestsLastSecond[0] >= $antiFloodProtection-1)
                {
                    $bR = $this->db->query('select * from __bannedIPs where IP=\''. WOOOF::cleanUserInput($_SERVER['REMOTE_ADDR']) .'\'');
                    if (mysql_num_rows($bR)>5)
                    {
                        $when = strtotime("+3 days");
                    }elseif (mysql_num_rows($bR)>1)
                    {
                        $when = strtotime("+2 days");
                    }elseif (mysql_num_rows($bR))
                    {
                        $when = strtotime("+1 days");
                    }else
                    {
                        $when = strtotime("+6 hours");
                    }
                    $this->db->query('insert into __bannedIPs set IP=\''. WOOOF::cleanUserInput($_SERVER['REMOTE_ADDR']) .'\', banExpiration=\''. $when .'\'');
                    exit;
                }
            }
        }
      
        if (isset($security) && is_array($security))
        {
            if (!isset($security[$requestedAction]) || $security[$requestedAction]!==TRUE)
            {
                if ($__isAdminPage == true || $__isSiteBuilderPage == true)
                {
                    header("Location: logIn.php");
                    exit;
                }else
                {
                    die('Security failure: you don\'t have permission to perform the requested action.');
                }
            }
        }else if ($checkSessionAndActionAndActivateLogging)
        {
            if ($__isAdminPage == true || $__isSiteBuilderPage == true)
            {
                header("Location: login.php");
                exit;
            }else
            {
                die('Security failure: you don\'t have permission to perform the requested action.');
            }
        }
    }
    
    public function evaluateUserName($userName)
    {
        if (strlen(preg_replace('![0-9A-Za-z\\-\\_]+!', '', $userName)))
        {
            return 'Illegal characters detected in username!<br/>';
        }else
        {
            return TRUE;
        }
    }
    
    public function evaluatePassword($password,$passwordConfirmation)
    {
        global $minimumPasswordLength;
        global $minimumCapitalsInPassword;
        global $minimumNumbersInPassword;
        global $minimumSymbolsInPassword;

        $errorText='';
        
        if ($password!=$passwordConfirmation)
        {
            $errorText.='The password and password confirmation you provided don\'t match!<br/>';
        }

        if (strlen($password)<$minimumPasswordLength)
        {
            $errorText.='The password you provided is shorter than 8 characters !<br/>';
        }
        
        if (strlen(preg_replace('![^0-9]+!', '', $password )) < $minimumNumbersInPassword && $minimumNumbersInPassword>0)
        {
            $errorText.='The password you provided has less than '. $minimumNumbersInPassword .' digits !<br/>';
        }
        
        if (strlen(preg_replace('![^A-Z]+!', '', $password)) < $minimumCapitalsInPassword && $minimumCapitalsInPassword>0)
        {
            $errorText.='The password you provided has less than '. $minimumCapitalsInPassword .' capital letters!<br/>';
        }
        
        if (strlen(preg_replace('![^\\!\\@\\#\\$\\%\\^\\&\\*\\(\\)]+!', '', $password)) < $minimumSymbolsInPassword && $minimumSymbolsInPassword>0)
        {
            $errorText.='The password you provided has less than '. $minimumSymbolsInPassword .' of symbols "!,@,#,$,%,^,&,*,(,)"!<br/>';
        }
        
        if (strlen(preg_replace('![\\!\\@\\#\\$\\%\\^\\&\\*\\(\\)0-9A-Za-z]+!', '', $password)))
        {
            $errorText.='Illegal characters detected !<br/>';
        }
        
        if ($errorText=='')
        {
            return TRUE;
        } else 
        {
            return $errorText;
        }
    }
    
    public static function getCurrentDateTime()
    {
        return date("YmdHis");
    }
    
    public function reportError($errorInput)
    {
        require 'errorTemplate.php';
        exit;
    }

    public static function randomString($length)
    {
        $pool="aAbBcCdDeEfFgGhHiIjJkKlLmMnNoOqQrRsStTuUvVwWxXyYzZ1234567890";
        $poolLength=strlen($pool);
        $randomString="";
        for($idx=0;$idx<$length;$idx++) 
        {
            $randomString.=substr($pool,(rand()%($poolLength)),1);
        }
        return $randomString;
    }
    
    public static function translateCheckBoxValue($value)
    {
        if ($value=='1')
        {
            return ' checked';
        }else
        {
            return '';
        }
    }
    
    public static function translateSelectValue($min,$max,$value)
    {
        $arr='';
        for($i=$min; $i<=$max; $i++)
        {
            if ($i == $value)
            {
                $arr[$i] = ' selected';
            }else
            {
                $arr[$i] = '';
            }
        }
        return $arr;
    }

    public static function decodeDate($tstamp,$separator='-')
    {
            $out=substr($tstamp,6,2);
            $out.=$separator . substr($tstamp,4,2);
            $out.=$separator . substr($tstamp,0,4);
            return $out;
    }

    public static function decodeDayMonth($tstamp,$separator='-')
    {
            $out=substr($tstamp,6,2);
            $out.=$separator . substr($tstamp,4,2);
            //$out.="-" . substr($tstamp,0,4);
            return $out;
    }

    public static function decodeDateTime($tstamp,$separator='-',$showSeconds=FALSE)
    {
            $out=substr($tstamp,6,2);
            $out.=$separator . substr($tstamp,4,2);
            $out.=$separator . substr($tstamp,0,4);
            $out.=', '. substr($tstamp,8,2);
            $out.=":" . substr($tstamp,10,2);
            if ($showSeconds)
            {
                $out.=":" . substr($tstamp,12,2);
            }
            return $out;
    }

    public static function decodeTime($tstamp)
    {
            $out= substr($tstamp,8,2);
            $out.=":" . substr($tstamp,10,2);
            //$out.=":" . substr($tstamp,12,2);
            return $out;
    }

    public function sessionCleanUp()
    {
        global $sessionExpirationPeriod;
        $this->db->query("update __sessions set active='0' where lastAction<'". date("YmdHis",  strtotime("-". $sessionExpirationPeriod)) ."' ");
    }
    
    public function invalidateSession()
    {
        $this->db->query("update __sessions set active='0' where sessionId='". WOOOF::cleanUserInput($_COOKIE['sid']) ."'");
    }
    
    public function sessionCheck()
    {
        global $userData;
        $this->sessionCleanUp();
        if (!isset($_COOKIE["sid"]))
        {
            return 0;
        }
        $result = $this->db->query("select * from __sessions where sessionId='". WOOOF::cleanUserInput($_COOKIE["sid"]) ."' and active='1'");
        //echo "select * from __sessions where sessionId='". trim(mysql_real_escape_string($_COOKIE["sid"])) ."' and active='1'<br>";
        //print_r($row);
        //exit;
        if(!mysql_num_rows($result)) 
        {
            //if ($row[0]=="") echo'case one<br/>';
            //if ($row[0]=='0') echo'case two<br/>';
            //echo 'Failed!<br/>';
            return FALSE;
        }
        else
        {
            $row=mysql_fetch_assoc($result);
            $this->db->query("update __sessions set lastAction='". $this->dateTime ."' where sessionId='". WOOOF::cleanUserInput($_COOKIE["sid"]) ."' and active='1'");
            $uR = $this->db->query("select * from __users where id='". $row['userId'] ."'");
            $userData = mysql_fetch_assoc($uR);
            $userData['loginPass']='surelyNotYourBusinessAtAll';
            return TRUE;
        }
    }

    public function newSession($uid)
    {
        global $userData;
        global $sessionExpirationPeriod;


        $this->sessionCleanUp();
        
        $uR = mysql_query('select * from __users where id=\''. WOOOF::cleanUserInput($uid) .'\'');
        if (!mysql_num_rows($uR))
        {
            die('Session for unknown id was requested!');
        }
        
        $userData = mysql_fetch_assoc($uR);
        
        if ($uid != '0123456789')
        {
            //$this->db->query('update __sessions set active=\'0\' where userId=\''. WOOOF::cleanUserInput($uid) .'\'');
        }
        $go_on=0;
        do
        {
            $sid = WOOOF::randomString(40);
            $new_sid_result=mysql_query("select * from __sessions where sessionId='". $sid ."'");
            if (!mysql_num_rows($new_sid_result)) $go_on=1;
        }while (!$go_on);
        setcookie("sid",$sid,  strtotime("+".$sessionExpirationPeriod),'/');
        $_COOKIE['sid']=$sid;
        $this->db->query("insert into __sessions (userId,sessionId,loginDateTime,lastAction,loginIP,active) values ('$uid','$sid','". $this->dateTime ."','". $this->dateTime ."','". WOOOF::cleanUserInput($_SERVER["REMOTE_ADDR"]) ."','1')");
    }

    public static function breakDateTime($dateTime)
    {
        $out='';
        $out['day']=substr($dateTime,6,2);
        $out['month']=substr($dateTime,4,2);
        $out['year']=substr($dateTime,0,4);
        $out['hour']=substr($dateTime,8,2);
        $out['minute']=substr($dateTime,10,2);
        $out['second']=substr($dateTime,12,2);
        return $out;
    }

    public static function buildDateTime($dateTime)
    {
        $dateTime['day'] = str_pad($dateTime['day'], 2, '0', STR_PAD_LEFT);
        $dateTime['month'] = str_pad($dateTime['month'], 2, '0', STR_PAD_LEFT);
        $dateTime['year'] = str_pad($dateTime['year'], 4, date('Y') , STR_PAD_LEFT);
        $dateTime['hour'] = str_pad($dateTime['hour'], 2, '0', STR_PAD_LEFT);
        $dateTime['minute'] = str_pad($dateTime['minute'], 2, '0', STR_PAD_LEFT);
        $dateTime['second'] = str_pad($dateTime['second'], 2, '0', STR_PAD_LEFT);
        return $dateTime['year'] . $dateTime['month'] . $dateTime['day'] . $dateTime['hour'] . $dateTime['minute'] . $dateTime['second'];
    }

    public static function buildDateTimeFromAdminPost($columnName)
    {
        $out='';
        $out['day'] = $_POST[$columnName.'1'];
        $out['month'] = $_POST[$columnName.'2'];
        $out['year']=$_POST[$columnName.'3'];
        $out['hour']=$_POST[$columnName.'4'];
        $out['minute']=$_POST[$columnName.'5'];
        $out['second']=$_POST[$columnName.'6'];
        return WOOOF::buildDateTime($out);
    }

    public static function cleanUserInput($input)
    {
        global $__isAdminPage;
        //return mysql_real_escape_string(htmlentities(strip_tags(trim($input)),ENT_NOQUOTES | ENT_HTML5,'UTF-8'));
        if (!$__isAdminPage) 
        {
            $input = strip_tags($input);
        }
        return mysql_real_escape_string(trim($input));
    }
    
    public function handleLoginFromPost()
    {
        $userRow='';
        
        $rowForTest = $this->db->getRowByColumn('__users', 'loginName', WOOOF::cleanUserInput($_POST['username']));
        
        if (PHP_VERSION_ID<50307)
        {
            $salt='$2a$08$'. $rowForTest['id'] . strrev($rowForTest['id']) . $rowForTest['id'];
        }else
        {
            $salt='$2y$08$'. $rowForTest['id'] . strrev($rowForTest['id']) . $rowForTest['id'];
        }

        // TODO: Randomize salt production here ^

        $cryptResult=crypt(WOOOF::cleanUserInput($_POST['password']), $salt);
        $thePassword = substr($cryptResult, 28);
        $result = $this->db->query('select * from __users where binary loginName=\''. WOOOF::cleanUserInput($rowForTest['loginName']) .'\' and binary loginPass=\''. $thePassword .'\'');
        if (mysql_num_rows($result))
        {
            $userRow = mysql_fetch_assoc($result);
            $userRow['loginPass']='not your business, really !';
            return $userRow;
            
        }else
        {
            return FALSE;
        }
    }
    
    public static function isValidEmail($email)
    {
        $isValid = true;
        $atIndex = strrpos($email, "@");
        if (is_bool($atIndex) && !$atIndex)
        {
            $isValid = false;
        }
        else
        {
            $domain = substr($email, $atIndex+1);
            $local = substr($email, 0, $atIndex);
            $localLen = strlen($local);
            $domainLen = strlen($domain);
            if ($localLen < 1 || $localLen > 64)
            {
                // local part length exceeded
                $isValid = false;
            }
            else if ($domainLen < 1 || $domainLen > 255)
            {
                // domain part length exceeded
                $isValid = false;
            }
            else if ($local[0] == '.' || $local[$localLen-1] == '.')
            {
                // local part starts or ends with '.'
                $isValid = false;
            }
            else if (preg_match('/\\.\\./', $local))
            {
                // local part has two consecutive dots
                $isValid = false;
            }
            else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
            {
                // character not valid in domain part
                $isValid = false;
            }
            else if (preg_match('/\\.\\./', $domain))
            {
                // domain part has two consecutive dots
                $isValid = false;
            }
            else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local)))
            {
                // character not valid in local part unless 
                // local part is quoted
                if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\","",$local)))
                {
                    $isValid = false;
                }
            }
            if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
            {
                // domain not found in DNS
                $isValid = false;
            }
        }
        return $isValid;
    }
    
    public static function sendMail($from,$emailAddress,$subject,$message,$replyTo='')
    {
        $to      = $emailAddress;
        $subject = '=?UTF-8?B?'. base64_encode($subject) .'?=';
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
        $headers .= 'From: '. $from ."\r\n";
        if ($replyTo!='')
        {
            $headers .= 'Reply-To: '. $replyTo ."\r\n";
        }
        mail($to, $subject, $message, $headers);
    }
    
    public static function resizePicture($inputPicture,$outputPicture,$targetWidthMax,$targetHeightMax,$quality=90)
    {
        // Get dimensions
        list($widthOrig, $heightOrig, $imageType) = getimagesize($inputPicture);
        //calculate aspect ratio
        $ratioOrig = $widthOrig/$heightOrig;
        //change target dimasnions so as to retain aspect ratio
        if ( $ratioOrig > 1 ) 
        {
            $targetHeightMax = $targetWidthMax/$ratioOrig;
        }else
        {
            $targetWidthMax = $targetHeightMax*$ratioOrig;
        }

        // create new image in memory
        $image_p = imagecreatetruecolor($targetWidthMax, $targetHeightMax);
        //load image depending on image type
        switch ($imageType)
        {
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($inputPicture);
                break;
            case IMAGETYPE_GIF:
                $image = imagecreatefromgif($inputPicture);
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($inputPicture);
                break;
            default:
                die('could not locate image data!');
                break;
        }
        //copy with resample/resize
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $targetWidthMax, $targetHeightMax, $widthOrig, $heightOrig);
        //export jpg image at requested quality
        //        $result = imagejpeg($image_p, $outputPicture, $quality);
        
        $ext = strtolower(substr($outputPicture, -3));
        switch ($ext)
        {
            case 'jpg':
                $result = imagejpeg($image_p, $outputPicture, $quality);
                break;
            case 'gif':
                $result = imagegif($image_p, $outputPicture);
                break;
            case 'png':

                    $quality =  9;
                
                $result = imagepng($image_p, $outputPicture,$quality);
                break;
        }

        return $result;
    }

    public static function resizePictureStrict($inputPicture,$outputPicture,$targetWidth,$targetHeight,$quality=70)
    {
        list($widthOrig, $heightOrig, $imageType) = getimagesize($inputPicture);
        $image_p = imagecreatetruecolor($targetWidth, $targetHeight);
        //load image depending on image type
        switch ($imageType)
        {
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($inputPicture);
                break;
            case IMAGETYPE_GIF:
                $image = imagecreatefromgif($inputPicture);
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($inputPicture);
                break;
        }
        //copy with resample/resize
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $targetWidth, $targetHeight, $widthOrig, $heightOrig);
        //export  image at requested quality
        
        $ext = strtolower(substr($outputPicture, -3));
        switch ($ext)
        {
            case 'jpg':
                $result = imagejpeg($image_p, $outputPicture, $quality);
                break;
            case 'gif':
                $result = imagegif($image_p, $outputPicture);
                break;
            case 'png':
                $result = imagepng($image_p, $outputPicture,$quality);
                break;
        }
        $result = imagejpeg($image_p, $outputPicture, $quality);
        return $result;
    }

    public static function resizePictureStrictWithOverlay($inputPicture,$outputPicture,$targetWidth,$targetHeight,$overlay,$quality=70)
    {
        list($widthOrig, $heightOrig, $imageType) = getimagesize($inputPicture);
        $image_p = imagecreatetruecolor($targetWidth, $targetHeight);
        //load image depending on image type
        switch ($imageType)
        {
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($inputPicture);
                break;
            case IMAGETYPE_GIF:
                $image = imagecreatefromgif($inputPicture);
                break;
            case IMAGETYPE_BMP:
                $image = imagecreatefrombmp($inputPicture);
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($inputPicture);
                $quality = 9;
                break;
        }
        //copy with resample/resize
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $targetWidth, $targetHeight, $widthOrig, $heightOrig);

        list($widthOrig, $heightOrig, $imageType) = getimagesize($inputPicture);
        switch ($imageType)
        {
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($inputPicture);
                break;
            case IMAGETYPE_GIF:
                $image = imagecreatefromgif($inputPicture);
                break;
            case IMAGETYPE_BMP:
                $image = imagecreatefrombmp($inputPicture);
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($inputPicture);
                break;
        }

        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $targetWidth, $targetHeight, $widthOrig, $heightOrig);

        //export jpg image at requested quality
        $result = imagejpeg($image_p, $outputPicture, $quality);
        return $result;
    }
    
    public static function cropPictureAndResize($inputPicture, $outputPicture, $targetWidth, $targetHeight, $overlay='', $quality=90)
    {
        list($widthOrig, $heightOrig, $imageType) = getimagesize($inputPicture);
        $XRatio = $widthOrig / $targetWidth;
        $YRatio = $heightOrig / $targetHeight;
        switch ($imageType)
        {
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($inputPicture);
                break;
            case IMAGETYPE_GIF:
                $image = imagecreatefromgif($inputPicture);
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($inputPicture);
                $quality = 9;
                break;
        }
        
        if ($XRatio < $YRatio)
        {
            $ratioDiff = round($XRatio * $targetHeight);
            $pixelDiff = $heightOrig - $ratioDiff;
            $image_t = imagecreatetruecolor($widthOrig, $ratioDiff);
            imagecopy($image_t, $image, 0, 0, 0, round($pixelDiff/2), $widthOrig, $heightOrig - round($pixelDiff/2));
        }else
        {
            $ratioDiff = round($YRatio * $targetWidth);
            $pixelDiff = $widthOrig - $ratioDiff;
            $image_t = imagecreatetruecolor($ratioDiff,$heightOrig);
            imagecopy($image_t, $image, 0, 0, round($pixelDiff/2), 0, $widthOrig - round($pixelDiff/2), $heightOrig);
        }
        
        $result = imagejpeg($image_t, $outputPicture.'interim.jpg',100);
        if ($result === true )
        {
            if ($overlay!='')
            {
                $result = WOOOF::resizePictureStrictWithOverlay($outputPicture.'interim.jpg', $outputPicture, $targetWidth, $targetHeight, $overlay, $quality);
            }else
            {
                $result = WOOOF::resizePicture($outputPicture.'interim.jpg', $outputPicture, $targetWidth, $targetHeight, $quality);
            }
            unlink($outputPicture.'interim.jpg');
            return $result;
        }else
        {
            return false;
        }
    }

    public static function cropCenterOfPicture($input, $output, $width, $height)
    {
        list($widthOrig, $heightOrig, $imageType) = getimagesize($input);
        switch ($imageType)
        {
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($input);
                break;
            case IMAGETYPE_GIF:
                $image = imagecreatefromgif($input);
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($input);
                break;
        }
        if ($width<$widthOrig && $height<$heightOrig)
        {
            $image_d = imagecreatetruecolor($width, $height);
            imagecopy($image_d, $image, 0, 0, floor(($widthOrig - $width)/2), floor(($heightOrig - $height)/2), $width, $height);
            $ext = strtolower(substr($output, -3));
            switch ($ext)
            {
                case 'jpg':
                    $result = imagejpeg($image_d, $output, 100);
                    break;
                case 'gif':
                    $result = imagegif($image_d, $output);
                    break;
                case 'png':
                    $result = imagepng($image_d, $output,0);
                    break;
            }
            return $result;
        }else
        {
            return false;
        }
    }

    public static function customOrderTranslation($input)
    {
    $ln=strlen($input);
    $tmp="";
    for($z=0;$z<$ln;$z++)
    {
        switch($input[$z])
        {
            case "α":
            case "ά":
            case "Α":
            case "Ά":
            case "a":
            case "A":
                $tmp.='a1';
            break;
            case "β":
            case "Β":
            case "b":
            case "B":
            case "v":
            case "V":
                $tmp.='b1';
            break;
            case "γ":
            case "Γ":
            case "w":
            case "W":
            case "g":
            case "G":
                $tmp.='c1';
            break;
            case "δ":
            case "Δ":
            case "d":
            case "D":
                $tmp.='d1';
            break;
            case "ε":
            case "έ":
            case "Ε":
            case "Έ":
            case "e":
            case "E":
                $tmp.='e1';
            break;
            case "ζ":
            case "Ζ":
            case "z":
            case "Z":
            case "j":
            case "J":
                $tmp.='f1';
            break;
            case "η":
            case "ή":
            case "Η":
            case "Ή":
                $tmp.='h1';
            break;
            case "θ":
            case "Θ":
                $tmp.='i1';
            break;
            case "ι":
            case "ί":
            case "Ι":
            case "Ί":
            case "I":
            case "i":
                $tmp.='i2';
            break;
            case "κ":
            case "Κ":
            case "k":
            case "K":
            case "c":
            case "C":
            case "q":
            case "Q":
                $tmp.='k1';
            break;
            case "λ":
            case "Λ":
            case "l":
            case "L":
                $tmp.='l1';
            break;
            case "μ":
            case "Μ":
            case "M":
            case "m":
                $tmp.='m1';
            break;
            case "ν":
            case "Ν":
            case "n":
            case "N":
                $tmp.='n1';
            break;
            case "ξ":
            case "Ξ":
            case "x":
            case "X":
                $tmp.='n2';
            break;
            case "ο":
            case "Ο":
            case "ό":
            case "Ό":
            case "o":
            case "O":
                $tmp.='o1';
            break;
            case "π":
            case "Π":
            case "p":
            case "P":
                $tmp.='p1';
            break;
            case "Ρ":
            case "ρ":
            case "r":
            case "R":
                $tmp.='p2';
            break;
            case "σ":
            case "ς":
            case "Σ":
            case "s":
            case "S":
                $tmp.='s1';
            break;
            case "τ":
            case "Τ":
            case "T":
            case "t":
                $tmp.='t1';
            break;
            case "υ":
            case "ύ":
            case "Υ":
            case "Ύ":
            case "y":
            case "Y":
            case "u":
            case "U":
                $tmp.='u1';
            break;
            case "φ":
            case "Φ":
            case "f":
            case "F":
                $tmp.='v1';
            break;
            case "χ":
            case "Χ":
            case "h":
            case "H":
                $tmp.='x1';
            break;
            case "ψ":
            case "Ψ":
                $tmp.='y1';
            break;
            case "ω":
            case "ώ":
            case "Ω":
            case "Ώ":
                $tmp.='z1';
            break;
            case "΄":
            case "'":
            case "<":
            case ">":
                    $tmp.="";
            break;
            default:
                $tmp.=$input[$z];
            }
        }
        return $tmp;
    }
    
 /**
 *  storeExternalFiles
 * 
 *  scans the $_FILE global variable and stores the files present there into the 
 *  specified folder (in $absoluteFilesRepositoryPath). Creates a unique random name for each file
 *  (default is 40 characters long) and returns an array with the names.
 *  If no file was uploaded the function returns false
 * 
 * 	input filename length (default is 40 chars)
 */

    public function storeExternalFiles()
    {
    	global $absoluteFilesRepositoryPath;
    	
    	$filesFound=0;
    	while(list($key,$val)=each($_FILES))
    	{
                if ($val['error']==UPLOAD_ERR_OK)
                {
                    $filesFound++;
                    do 
                    {
                        $newName=$this->randomString(40);
                    }while(file_exists($absoluteFilesRepositoryPath.$newName));
                    
                    if (!move_uploaded_file($val['tmp_name'],$absoluteFilesRepositoryPath.$newName))
                    {
                        echo "file upload failed !!!<BR>";
                        exit;
                    }
                    chmod($absoluteFilesRepositoryPath.$newName,0440);
                    $fileNames[]=$newName;
                    $fileNames[$key]=$newName;
                    $this->db->query('insert into __externalFiles set id=\''. $this->db->getNewId('__externalFiles') .'\', entryDate=\''. $this->dateTime .'\', fileName=\''. $newName .'\', originalFileName=\''. WOOOF::cleanUserInput($val['name']) .'\'');
                    if (mysql_error()!='')
                    {
                        echo mysql_error();
                        exit;
                    }
                }
    	}
	
    	if ($filesFound==0)
    	{
    		return FALSE;
    	}else 
    	{
    		return $fileNames;
    	}
    }

    public function getPresentationListFromQuery($query, $headers, $presentation, $table, $displayActivation = false, $displayPreview = false, $displayUpDown = false)
    {
        $output='<div class="headerObjectRow">
';
        $z=0;
        foreach($headers as $header)
        {

            $output.='<div class="'. $presentation[$z] .'">'. $header .'</div>
';
            $z++;
        }
        if ($displayActivation)
        {
            $output.='<div class="objectPropertyCellSmall">Status</div>
';
        }
        $output.='<div class="objectControls">
        &nbsp;
    </div>
';
        $output.='</div>
';
        
        $table->getResultByQuery($query,true,false);
        $output.= $table->getAdminListRows($headers,$presentation,$displayActivation,$displayPreview,$displayUpDown);

        return $output;
    }

    public function doTableList($table, $where='', $parentId='', $isFoldable=FALSE)
    {
        $query = 'SELECT id';
        foreach ($table->columns as $key => $value)
        {
            $column = $value->getColumnMetaData();
            if ($column['appearsInLists'] && !($column['name']=='active' && $table->getHasActivationFlag()) && !isset($headers[$column['ordering']]))
            {
                $headers[$column['ordering']]=$column['description'];
                $query .=', '. $column['name'];
                $presentation[$column['ordering']]=$column['adminCSS'];
            }
        }
        if ($table->getHasActivationFlag())
        {
            $query.=', active';
            $displayActivation = true;
        }else
        {
            $displayActivation = false;
        }
        if (trim($table->getAdminListMarkingCondition())!='')
        {
            $displayPreview = $table->getAdminListMarkingCondition();
        }else
        {
            $displayPreview = false;
        }
        if (trim($table->getOrderingColumnForListings())!='')
        {
            $displayUpDown = true;
        }else
        {
            $displayUpDown = false;
        }
        $query .= ' from '. $table->getTableName();
        if ($where!='') 
        {
            $query .= ' '. $where;
        }
        if (trim($table->getOrderingColumnForListings())!='')
        {
            $query .= ' order by '. trim($table->getOrderingColumnForListings());
        }
        if ($isFoldable)
        {

        }else
        {
            if ($parentId!='')
            {
                $content='<div class="addTitle"><a href="administration.php?action=edit&address=1_'. $table->getTableId() .'_&wooofParent='. $parentId .'">Προσθήκη '. $table->getSubTableDescription() .' &nbsp;<img src="images/add.png" alt="Create new item" border="0" align="top"></a></div>
    ';
            }else
            {
                $content='<div class="listTitle"><a href="administration.php?action=edit&address=1_'. $table->getTableId() .'_">Προσθήκη &nbsp;<img src="images/add.png" alt="Create new item" border="0" align="top"></a></div>
    ';
            }
        }
        $headers = array_values($headers);
        $presentation = array_values($presentation);
        $content.=$this->getPresentationListFromQuery($query,$headers,$presentation, $table, $displayActivation, $displayPreview, $displayUpDown); //TODO: get preview up and running here
        return $content;
    }

    public function amIA($role)
    {
        global $userData;
        $roleR = $this->db->query('select * from __roles where role=\''. mysql_real_escape_string($role) .'\'');
        if (!mysql_num_rows($roleR))
        {
            return FALSE;
        }
        $role = mysql_fetch_assoc($roleR);

        $result = $this->db->query('select * from __userRoleRelation where userId=\''. $userData['id'] .'\' and roleId=\''. $role['id'] .'\'');
        if (mysql_num_rows($result))
        {
            return TRUE;
        }else
        {
            return FALSE;
        }
    }
}

/*
 * WOOOF_dataBase
 * 
 * represents a data base connection to a specific database.
 * It is the only sanctioned gateway to the MySQL backend when using WOOOF.
 * Logs queries into a db table and/or a file if requested.
 * 
 */

class WOOOF_dataBase
{
    private $connection;
    private $logToFile;
    private $logFileName;
    private $logFileHandle;
    private $logToDatabase;
    private $logTable;
    private $dataBaseName;
    private $fileLoggingMode;
    private $dataBaseLoggingMode;
    private $myConnectionSerialNumber;
    private $logConnection;
    private $lastQueryInfo;
    private $logForFlushingToFile;
    private $currentMicroTime;
    private $options;
    
    public static $howManyConnections=0;
    
    public $resultActive;
    public $resultRows;
    public $lastDbResult;
    
    public function __construct($currentMicroTime, $host='localhost', $userName='', $password='', $dbName='') 
    {
        global $databaseName;
        global $databaseUser;
        global $databasePass;
        global $databaseHost;
        
        $this->resultActive = FALSE;
        
        $this->currentMicroTime = $currentMicroTime;
        
        if ($dbName=='')
        {
            $this->myConnectionSerialNumber = WOOOF_dataBase::$howManyConnections;
            WOOOF_dataBase::$howManyConnections ++;
            $host = $databaseHost[$this->myConnectionSerialNumber];
            $userName = $databaseUser[$this->myConnectionSerialNumber];
            $password = $databasePass[$this->myConnectionSerialNumber];
            $dbName = $databaseName[$this->myConnectionSerialNumber];
        }
        $this->logFileHandle='';
        $this->connection = mysql_connect($host, $userName, $password, TRUE);
        // we are always requesting a new connection so as to avoid two objects having the same actual connection,
        // as this would probably mess query logging, error/exception handling etc
        
        if ($this->connection === FALSE)
        {
            die('Database connection failed with MySQL error :<br/>'.  mysql_error());
        }
        $this->logConnection=  $this->connection;
        $this->selectDataBase($dbName);
        $this->query('set names utf8');
        $optionsCheck = $this->query('SELECT * FROM information_schema.tables WHERE table_schema = \''. $dbName .'\' AND table_name = \'__options\' LIMIT 1');
        if (mysql_num_rows($optionsCheck))
        {
            $optionsR =  $this->query('select * From __options');
            while($o = mysql_fetch_assoc($optionsR))
            {
                $this->options[$o['id']] = $o;
            }
        }
    }

    public function getOptions()
    {   
        return $this->options;
    }

    public function loggingToFile($activate,$filePath='')
    {
        global $logFilePath;
        global $__isSiteBuilderPage;

        if ($activate)
        {
            if ($filePath == '')
            {
                $this->logFileName = $logFilePath[$this->myConnectionSerialNumber];
            }else
            {
                $this->logFileName = $filePath;
            }

            if ($__isSiteBuilderPage)
            {
                $this->logFileName .= 'dbManager_';
            }
            
            $this->logFileName .= $this->dataBaseName .'_log.sql';
            
            if ($this->logFileHandle!='')
            {
                fclose($this->logFileHandle);
            }
            $this->logFileHandle = fopen($this->logFileName, 'a');
            if ($this->logFileHandle === FALSE)
            {
                die('Database Log File Opening Failed! Requested file was '. $this->logFileName);
            }
            //register_shutdown_function(array($this, 'flushFileLog'));
            $this->logToFile = TRUE;
        }else
        {
            $this->logToFile = FALSE;
        }
    }
    
    function __destruct() 
    {
        if ($this->logToFile)
        {
            //echo 'inDestructor!<br/>';
            fputs($this->logFileHandle, $this->logForFlushingToFile, strlen($this->logForFlushingToFile));
            $this->logForFlushingToFile='';
        }
    }
    
    public function loggingToDatabase($activate,$table='')
    {
        global $logTable;
        if ($activate)
        {
            if ($table == '')
            {
                $this->logTable = $logTable[$this->myConnectionSerialNumber];
            }else
            {
                $this->logTable = $table;
            }
            $this->logToDatabase = TRUE;
        }else
        {
            $this->logToDatabase = FALSE;
        }
    }
    
    public function selectDataBase($newDataBaseName)
    {
        if ($newDataBaseName!=$this->dataBaseName)
        {
            if (mysql_select_db($newDataBaseName,$this->connection) === FALSE)
            {
                die('Could not select requested database ('. $newDataBaseName .'). Mysql returned : <br/>' . mysql_error($this->connection));
            }
            $this->dataBaseName = $newDataBaseName;
           
            // reactivating file and database logging if already activated should 
            // be called here so as to make sure logging facilities are present and available
            // if not an exception will be thrown and no queries will be executed without logging 
            if ($this->logToFile)
            {
                $this->loggingToFile(TRUE);
            }
            
            if ($this->logToDatabase)
            {
                $this->loggingToDatabase(TRUE);
            }
        }
    }

    public function query($sanitizedQueryText)
    {
        $result = mysql_query($sanitizedQueryText, $this->connection);
        if (mysql_error($this->connection) != '')
        {
            $debugInfo = debug_backtrace();
            die(nl2br("<br/><font color=\"red\">Query Failed ! at ". $debugInfo[0]['file'] ." on line ". $debugInfo[0]['line'] ."</font> Query text: \r$sanitizedQueryText\r\rMysql Error:".  mysql_error($this->connection)));
        }
        $this->lastQueryInfo = mysql_info($this->connection);
        if (!$this->lastQueryInfo)
        {
            if (@mysql_num_rows($result))
            {
                $this->lastQueryInfo = "Returned Rows: ". mysql_affected_rows($this->connection);
            }elseif (mysql_affected_rows($this->connection))
            {
                $this->lastQueryInfo = "Affected Rows: ". mysql_affected_rows($this->connection);
            }
        }
        
        //always call logging, better safe than sorry
        $this->logQuery($sanitizedQueryText);
        
        return $result;
    }
    
    private function logQuery($sanitizedQueryText)
    {
        global $userData;
        
        $doLog = true;

        if ($this->logToFile)
        {
            if ($this->fileLoggingMode == WOOOF_databaseLoggingModes::doNotLogSelects)
            {
                if (substr(strtolower(trim($sanitizedQueryText)),0,6) == 'select')
                {
                    $doLog=FALSE;
                }
            }else if ($this->fileLoggingMode == WOOOF_databaseLoggingModes::doNotLogSelectsDescrShow)
            {
                if (substr(strtolower(trim($sanitizedQueryText)),0,6) == 'select' || substr(strtolower(trim($sanitizedQueryText)),0,5) == 'desc' || substr(strtolower(trim($sanitizedQueryText)),0,4) == 'show')
                {
                    $doLog=FALSE;
                }
            }

            if ($doLog)
            {
                if (isset($_COOKIE['sid']))
                {
                    $cookie = escapeshellcmd(WOOOF::cleanUserInput($_COOKIE['sid'])); 
                }else
                {
                    $cookie=' No Session! ';
                }
                $this->logForFlushingToFile .= '#'. $cookie .'#'. $userData['id'] .'#'. $this->currentMicroTime .'#'. basename($_SERVER['PHP_SELF']) ."\r\n";
                $this->logForFlushingToFile .= $sanitizedQueryText .";\r\n";
            }
        }
        
        if ($this->logToDatabase)
        {
            $doLog=TRUE;
            if ($this->dataBaseLoggingMode == WOOOF_databaseLoggingModes::doNotLogSelects)
            {
                if (substr(strtolower(trim($sanitizedQueryText)),0,6)=='select')
                {
                    $doLog = FALSE;
                }
            }else if ($this->dataBaseLoggingMode == WOOOF_databaseLoggingModes::doNotLogSelectsDescrShow)
            {
                if (substr(strtolower(trim($sanitizedQueryText)),0,6) == 'select' || substr(strtolower(trim($sanitizedQueryText)),0,4) == 'desc' || substr(strtolower(trim($sanitizedQueryText)),0,4) == 'show')
                {
                    $doLog=FALSE;
                }
            }
            if ($doLog)
            {
                $query = 'insert into '. $this->logTable .' (executionTime,queryText) values ('. $this->currentMicroTime .',\''. mysql_real_escape_string($sanitizedQueryText) .'\')';
                mysql_query($query,  $this->logConnection);
                if (mysql_error()!='')
                {
                    die(nl2br("<br/><font color=\"red\">Log Query Failed !</font> Query text: \r$query\r\rMysql Error:".  mysql_error($this->connection)));
                }
            }
        }
    }
    
    public function setLoggingType($newLogToDBType,$newLogToFileType)
    {
        if ($newLogToDBType == WOOOF_databaseLoggingModes::doNotLogSelects)
        {
            $this->dataBaseLoggingMode = WOOOF_databaseLoggingModes::doNotLogSelects;
        }else if ($newLogToDBType == WOOOF_databaseLoggingModes::doNotLogSelectsDescrShow)
        {
            $this->dataBaseLoggingMode = WOOOF_databaseLoggingModes::doNotLogSelectsDescrShow;
        }else
        {
            $this->dataBaseLoggingMode = WOOOF_databaseLoggingModes::logAllQueries;
        }
        
        if ($newLogToFileType == WOOOF_databaseLoggingModes::doNotLogSelects)
        {
            $this->fileLoggingMode = WOOOF_databaseLoggingModes::doNotLogSelects;
        }else if ($newLogToFileType == WOOOF_databaseLoggingModes::doNotLogSelectsDescrShow)
        {
            $this->fileLoggingMode = WOOOF_databaseLoggingModes::doNotLogSelectsDescrShow;
        }else
        {
            $this->fileLoggingMode = WOOOF_databaseLoggingModes::logAllQueries;
        }
    }
    
    public function setLogConnection($connectionHandle,$logTable='')
    {
        $this->logConnection=$connectionHandle;
        if ($logTable!='')
        {
            $this->logTable=$logTable;
        }
        $this->loggingToDatabase(TRUE, $this->logTable);
    }
    
    public function getSecurityPermitionsForLocationAndUser($location, $userId)
    {
        global $aggressiveSecurity;
        
        $relationR = $this->query('select * from __userRoleRelation where userId=\''. WOOOF::cleanUserInput($userId) .'\'');
        $returnArray = array();
        while ($relation = mysql_fetch_assoc($relationR))
        {
            //echo 'userId -> '. $userId .' role -> "'. $relation['roleId'] .'" location -> '. $location .' <br/>';
            $resultArray = $this->getSecurityPermitionsForLocationAndRole($location, $relation['roleId']);
            if (is_array($resultArray))
            {
                while(list($key,$val) = each($resultArray))
                {
                    if (isset($returnArray[$key]))
                    {
                        if ($val != $returnArray[$key])
                        {
                            if ($aggressiveSecurity == TRUE)
                            {
                                if ($returnArray[$key] == TRUE)
                                {
                                    if ($val != TRUE)
                                    {
                                        $returnArray[$key] = FALSE;
                                    }
                                }
                            }else
                            {
                                if ($returnArray[$key] != TRUE)
                                {
                                    if ($val == TRUE)
                                    {
                                        $returnArray[$key] = TRUE;
                                    }
                                }
                            }
                        }
                    }else   
                    {
                        $returnArray[$key] = $val;
                    }
                }
            }
        }
        return $returnArray;
    }
    
    public function getSecurityPermitionsForLocationAndRole($location, $roleId)
    {
        $locationPieces = explode('_', $location);
        $numberOfPieces = count($locationPieces);
        
        $currentLocation='';
        $permitions='';
        
        for($c = 0; $c < $numberOfPieces ; $c++)
        {
            if ($currentLocation!='')
            {
                $currentLocation.='_';
            }
            $currentLocation.=$locationPieces[$c];
            $result = $this->query('SELECT * FROM __lrbs where location=\''. mysql_real_escape_string($currentLocation) .'\' and role=\''. mysql_real_escape_string($roleId) .'\'');
            while($p = mysql_fetch_assoc($result))
            {
                if ($p['allowed'] == '1')
                {
                    $permitions[$p['action']]=TRUE;
                    //echo 'role-> '. $roleId .' location -> '. $currentLocation .' action -> '. $p['action'] .' TRUE <br/>' ;
                }else
                {
                    $permitions[$p['action']]=FALSE;
                    //echo 'role-> '. $roleId .' location -> '. $currentLocation .' action -> '. $p['action'] .' FALSE <br/>' ;
                }
            }
        }
        
        return $permitions;
    }
    
    public function getDataBaseName()
    {
        return $this->dataBaseName;
    }
    
    public function getLastQueryInfo()
    {
        return $this->lastQueryInfo;
    }
    
    public function getNewId($targetTable)
    {
        $goOn = FALSE;
        do
        {
            $id = WOOOF::randomString(10);
            $testR=mysql_query('select id from '. mysql_real_escape_string($targetTable) .' where id=\''. $id .'\'');
            if (!mysql_num_rows($testR)) $goOn=1;
        }while (!$goOn);
        return $id;
    }
    
    public function getEmptyTable()
    {
        return new WOOOF_dataBaseTable($this, '');
    }
    
    /*
    getAliasArray. Returns a Look up array out of a DB table.
    $tableName has the name of the table
    $whereClause has the where portion of the query (default is nothing)
    $valueColumn has the name of the column to be used as value (default is 'id')
    $descriptionColumn has the name of the column to be used as <option>'s descriptive text (default is 'name')
    */

    public function getAliasArray($tableName, $whereClause='', $valueColumn='id', $descriptionColumn='name')
    {
        $array='';
        $result=$this->query('select '. $valueColumn .', '. $descriptionColumn .' from '. $tableName .' '. $whereClause);
        while ($row=mysql_fetch_row($result))
        {
                $array[$row[0]]= $row[1];
        }
        return $array;
    }

    /*
    getDropList. Returns an html droplist out of a DB table.
    $tableName has the name of the table
    $selectName has the name to be given to the select tag
    $whereClause has the where portion of the query (default is nothing)
    $tagClass has the name of the css to apply to the tag
    $valueColumn has the name of the column to be used as value (default is 'id')
    $descriptionColumn has the name of the column to be used as <option>'s descriptive text (default is 'name')
    */
    public function getDropList($tableName, $selectName, $whereClause='', $tagClass='normal_text', $valueColumn='id', $descriptionColumn='name', $orderBy='')
    {
        global $cssForFormItem;
        
        if ($tagClass='')
        {
            $tagClass = $cssForFormItem['dropList'];
        }
        $tag='<select name="'. $selectName  .'" class="'. $tagClass .'">
';
        if ($orderBy!='')
        {
            $orderBy = ' ORDER BY '. $orderBy;
        }
        $query = 'select '. $valueColumn .', '. $descriptionColumn .' from '. $tableName .' '. $whereClause . $orderBy;

        $result=$this->query($query);
        $descriptionColumns = explode(',', $descriptionColumn);
        while ($row=mysql_fetch_row($result))
        {
                $tag.='<option value="'. $row[0] .'">';
                for($dCounter = 1; $dCounter<=count($descriptionColumns); $dCounter++)
                {
                    $tag.= $row[$dCounter] .' ';
                }
                $tag.= '</option>
';
        }
        $tag.='</select>
';
        return $tag;
    }
    
    public function getRadio($tableName, $radioName, $isHorizontal = FALSE ,$whereClause='', $tagClass='normalTextBlack', $valueColumn='id', $descriptionColumn='name', $selectColumn='', $selectValue='')
    {
        $tag='';
        $result=$this->query('select '. $valueColumn .', '. $descriptionColumn .' from '. $tableName .' '. $whereClause);
        while ($row=mysql_fetch_assoc($result))
        {
            if ($selectColumn!='')
            {
                if (isset($row[$selectColumn]) && $row[$selectColumn]== $selectValue)
                {
                    $selectedOption=' checked';
                }else   
                {
                    $selectedOption='';
                }
            }else
            {
                $selectedOption='';
            }
            
            $tag.='<span class="'. $tagClass .'"><input type="radio" name="'. $radioName .'" value="'. $row[$valueColumn] .'"'. $selectedOption .'>'. $row[$descriptionColumn] .'</span>&nbsp; ';
            if (!$isHorizontal)
            {
                $tag.='<br/>';
            }
        }
        return $tag;
    }

    /*
    getDropListSelected. Returns an html droplist out of a DB table.
    $tableName has the name of the table
    $selectName has the name to be given to the select tag
    $whereClause has the where portion of the query (default is nothing)
    $tagClass has the name of the css to apply to the tag
    $valueColumn has the name of the column to be used as value (default is 'id')
    $descriptionColumn has the name of the column to be used as <option>'s descriptive text (default is 'name')
    */
    public function getDropListSelected($tableName, $selectName, $whereClause='', $tagClass='normal_text', $valueColumn='id', $descriptionColumn='name', $columnToSearch='', $valueToSearch='')
    {
        $tag='<select name="'. $selectName  .'" class="'. $tagClass .'">
';

        $result=$this->query('select '. $valueColumn .', '. $descriptionColumn .', '. $columnToSearch .' from '. $tableName .' '. $whereClause);
        while ($row=mysql_fetch_row($result))
        {
            //echo $columnToSearch .' - '. $row[2] .' - '. $valueToSearch .'<br>';
                if ($row[2]==$valueToSearch)
                {
                        $selected=' selected';
                }else
                {
                        $selected='';
                }
                $tag.='<option value="'. $row[0] .'"'. $selected .'>'. $row[1] .'</option>
';
        }
        $tag.='</select>
';
        return $tag;
    }

    public function getRow($table, $rowId)
    {
        $r = $this->query('SELECT * FROM '. $table .' WHERE id=\''. $rowId .'\'');
        return mysql_fetch_assoc($r);
    }

    public function getRowByColumn($table, $columnName, $value, $order='')
    {
        if ($order!='')
        {
            $order = ' ORDER BY '. $order;
        }
        $r = $this->query('SELECT * FROM '. $table .' WHERE '. $columnName .'=\''. $value .'\''. $order);
        return mysql_fetch_assoc($r);
    }

    public function getResultByQuery($query,$serialRows = TRUE, $associativeRows = TRUE)
    {
        $this->lastDbResult = $this->query($query);
        $this->resultActive = TRUE;
        while($row = mysql_fetch_assoc($this->lastDbResult))
        {
            if ($associativeRows && isset($row['id']))
            {
                $this->resultRows[$row['id']] = $row;
            }
            if ($serialRows)
            {
                $this->resultRows[] = $row;
            }
        }
    }
    
    public function getLastInsertId()
    {
        return mysql_insert_id($this->connection);
    }
    
    public function getSecretSauce()
    {
        global $userData;
        
        $sSR = $this->query('select * from __secretSauce where userId=\''. $userData['id'] .'\'');
        
    }
    
    public function invalidateSecretSauce($sauce)
    {
        global $userData;
        
        $this->cleanSecretSauces();
        $sSR = $this->query('select * from __secretSauce where userId=\''. $userData['id'] .'\' and sauce=\''. WOOOF::cleanUserInput($sauce) .'\'');
        if (mysql_num_rows($sSR))
        {
            $this->query('delete from __secretSauce where userId=\''. $userData['id'] .'\' and sauce=\''. WOOOF::cleanUserInput($sauce) .'\'');
            return true;
        }else
        {
            return false;
        }
    }
    
    private function cleanSecretSauces()
    {
        global $userData;
        $dR=  $this->query('delete from secretSaucewhere userId=\''. $userData['id'] .'\' and entryDate<\''. date('YmdHis', strtotime('-1 week')) .'\'');
    }
}


/*
 * WOOOF_dataBaseTable
 * 
 * Stores all the meta data for each table.
 * Retrieves Rows based requested properties.
 * Can also commit updates to the database
 * if the user has enough privileges (per table and per column).
 */

class WOOOF_dataBaseTable
{
    private $dataBase;
    private $tableName;
    private $description;
    private $subtableDescription;
    private $tableId;
    private $orderingColumnForListings;
    private $appearsInAdminMenu;
    private $adminPresentation;
    private $adminItemsPerPage;
    private $adminListMarkingCondition;
    private $adminListMarkedStyle;
    private $groupedByTable;
    private $remoteGroupColumn;
    private $localGroupColumn;
    private $tablesGroupedByThis;
    private $hasActivationFlag;
    private $availableForSearching;
    private $hasGhostTable;
    private $hasDeletedColumn;
    private $currentUserCanEdit;
    private $currentUserCanRead;
    private $currentUserCanChangeProperties;
    private $hasEmbededPictures;
    private $columnForMultipleTemplates;

    public $columns;
    public $resultActive;
    public $resultRows;
    public $lastDbResult;
    
    public function __construct($dataBaseObject,$tableName,$tableId='')
    {
        $this->dataBase = $dataBaseObject;
        
        $this->resultActive = FALSE;
        
        if ($tableName=='' && $tableId=='')
        {
            $this->tableName='';
            $this->tableId='';
            $this->currentUserCanChangeProperties = TRUE;
            $this->currentUserCanRead = FALSE;
            $this->currentUserCanEdit = FALSE;
        }else
        {
            $result=null;
            if ($tableName!='')
            {
                $result = $this->dataBase->query('select * from __tableMetaData where tableName=\''. mysql_real_escape_string($tableName) .'\'');
            }else
            {
                $result = $this->dataBase->query('select * from __tableMetaData where id=\''. mysql_real_escape_string($tableId) .'\'');
            }
            if (!mysql_num_rows($result))
            {
                if (mysql_error()=='')
                {
                    die(nl2br('Error!!! requested table doesn\'t have metadata stored!'));
                }else
                {
                    die(nl2br('Mysql error ! '. mysql_error() .'
when trying to instanciate a database table. 

The requested table was '. $tableName .'
'));
                }
            }
            $metaData = mysql_fetch_assoc($result);

            $columnsR = $this->dataBase->query('select * from __columnMetaData where tableId=\''. $metaData['id'] .'\' order by ordering');
            while($column = mysql_fetch_assoc($columnsR))
            {
                $this->columns[$column['name']] = WOOOF_dataBaseColumn::fromMetaRow($this->dataBase,$column);
                $this->columns[] = WOOOF_dataBaseColumn::fromMetaRow($this->dataBase,$column);
            }
            $this->tableName = $metaData['tableName'];
            $this->description = $metaData['description'];
            $this->subtableDescription = $metaData['subtableDescription'];
            $this->tableId = $metaData['id'];
            $this->orderingColumnForListings = $metaData['orderingColumnForListings'];
            $this->appearsInAdminMenu = $metaData['appearsInAdminMenu'];
            $this->adminPresentation = $metaData['adminPresentation'];
            $this->adminItemsPerPage = $metaData['adminItemsPerPage'];
            $this->adminListMarkingCondition = $metaData['adminListMarkingCondition'];
            $this->adminListMarkedStyle = $metaData['adminListMarkedStyle'];
            $this->groupedByTable = $metaData['groupedByTable'];
            $this->remoteGroupColumn = $metaData['remoteGroupColumn'];
            $this->localGroupColumn = $metaData['localGroupColumn'];
            $this->tablesGroupedByThis = $metaData['tablesGroupedByThis'];
            $this->hasActivationFlag = $metaData['hasActivationFlag'];
            $this->availableForSearching = $metaData['availableForSearching'];
            $this->hasGhostTable = $metaData['hasGhostTable'];
            $this->hasDeletedColumn = $metaData['hasDeletedColumn'];
            $this->hasEmbededPictures = $metaData['hasEmbededPictures'];
            $this->columnForMultipleTemplates = $metaData['columnForMultipleTemplates'];
            
            $this->currentUserCanChangeProperties = TRUE;
            $this->currentUserCanRead = FALSE;
            $this->currentUserCanEdit = FALSE;
        }
    }
    // TODO: recheck why mysql_real_escape string and not WOOOF::cleanUserInput ??
    // TODO: check security implications for removed mysql_real escape string in key
    public function getResult($whereClauses, $orderBy='', $limitStart='', $limitHowMany='')
    {
        if ($orderBy!='')
        {
            $orderBy=' ORDER BY '. $orderBy;
        }

        if (is_array($whereClauses) && count($whereClauses)>0)
        {
            $where = ' WHERE ';
            while(list($key,$val)=each($whereClauses))
            {
                if ($where != ' WHERE ')
                {
                    $where .= ' AND ';
                }
                $where .= $key .'= \''. mysql_real_escape_string($val) .'\'';
            }
        } else
        {
            $where = '';
        }
        
        if ($limitStart != '' || $limitHowMany != '')
        {
            $limit = ' LIMIT ';
            if ($limitStart != '')
            {
                $limit .= mysql_real_escape_string($limitStart).',';
            }
            
            if ($limitHowMany != '')
            {
                $limit .= mysql_real_escape_string($limitHowMany);
            }
        }else
        {
            $limit='';
        }
        $this->resultRows = array();
        $this->lastDbResult = $this->dataBase->query('SELECT * FROM '. $this->tableName .' '. $where .' '. mysql_real_escape_string($orderBy) .' '. $limit);
        
        //echo 'SELECT * FROM '. $this->tableName .' '. $where .' '. mysql_real_escape_string($orderBy) .' '. $limit .'<br/>';

        $this->resultActive = TRUE;
        $index=0;
        while($row = mysql_fetch_assoc($this->lastDbResult))
        {
            $this->resultRows[$row['id']] = $row;
            $this->resultRows[$index] = $row;
            $index++;
        }
        
    }
    
    public function getResultJoin($tables, $whereClause, $orderBy='', $limitStart='', $limitHowMany='')
    {
        if ($orderBy!='')
        {
            $orderBy=' ORDER BY '. $orderBy;
        }
        if ($whereClause!='')
        {
            $where = ' WHERE '. $whereClause;
        } else
        {
            $where = '';
        }
        
        if ($limitStart != '' || $limitHowMany != '')
        {
            $limit = ' LIMIT ';
            if ($limitStart != '')
            {
                $limit .= mysql_real_escape_string($limitStart).',';
            }
            
            if ($limitHowMany != '')
            {
                $limit .= mysql_real_escape_string($limitHowMany);
            }
        }else
        {
            $limit='';
        }
        $this->lastDbResult = $this->dataBase->query('SELECT DISTINCT '. $this->tableName .'.* FROM '. $this->tableName .', '. $tables .' '. $where .' '. mysql_real_escape_string($orderBy) .' '. $limit);
        $this->resultActive = TRUE;
        while($row = mysql_fetch_assoc($this->lastDbResult))
        {
            $this->resultRows[$row['id']] = $row;
            $this->resultRows[] = $row;
        }
        
    }
    
    public function getResultByQuery($query,$serialRows = TRUE, $associativeRows = TRUE)
    {
        $result = $this->dataBase->query($query);
        while($row = mysql_fetch_assoc($result))
        {
            if ($associativeRows && isset($row['id']))
            {
                $this->resultRows[$row['id']] = $row;
            }
            if ($serialRows)
            {
                $this->resultRows[] = $row;
            }
        }
    }
    
    public function getTableId()
    {
        return $this->tableId;
    }
    
    public function getAdminPresentation()
    {
            return $this->adminPresentation;
    }
    
    public function getTableName()
    {
            return $this->tableName;
    }
    
    public function getTableDescription()
    {
            return $this->description;
    }

    public function getSubTableDescription()
    {
            return $this->subtableDescription;
    }
    
    public function getOrderingColumnForListings()
    {
            return $this->orderingColumnForListings;
    }
    
    public function getAppearsInAdminMenu()
    {
            return $this->appearsInAdminMenu;
    }
    
    public function getAdminItemsPerPage()
    {
            return $this->adminItemsPerPage;
    }
    
    public function getAdminListMarkingCondition()
    {
            return $this->adminListMarkingCondition;
    }
    
    public function getAdminListMarkedStyle()
    {
            return $this->adminListMarkedStyle;
    }
    
    public function getGroupedByTable()
    {
            return $this->groupedByTable;
    }
    
    public function getRemoteGroupColumn()
    {
            return $this->remoteGroupColumn;
    }
    
    public function getLocalGroupColumn()
    {
            return $this->localGroupColumn;
    }
    
    public function getTablesGroupedByThis()
    {
            return $this->tablesGroupedByThis;
    }
    
    public function getHasActivationFlag()
    {
            return $this->hasActivationFlag;
    }
    
    public function getAvailableForSearching()
    {
            return $this->availableForSearching;
    }
    
    public function getHasGhostTable()
    {
            return $this->hasGhostTable;
    }
    
    public function getHasDeletedColumn()
    {
            return $this->hasDeletedColumn;
    }

    public function getHasEmbededPictures()
    {
            return $this->hasEmbededPictures;
    }

    public function getColumnForMultipleTemplates()
    {
            return $this->columnForMultipleTemplates;
    }

    public function updateMetaDataFromPost()
    {
        if ($this->currentUserCanChangeProperties)
        {
            if ($this->tableId=='')
            {
                $newTableId = $this->dataBase->getNewId('__tableMetaData');
                $query='insert into __tableMetaData set id=\''. $newTableId .'\',';
                if (trim($_POST['orderingColumnForListings'])!='')
                {
                    $pieces = explode(' ', trim($_POST['orderingColumnForListings']));
                    $actualColumn = $pieces[0]; // remember this is used for filtering out desc
                    $extraColumns.=' '. $actualColumn .' int unsigned not null default \'0\', ';
                }
                if (trim($_POST['hasActivationFlag'])=='1')
                {
                    $extraColumns.=' active char(1) not null default \'0\',';
                }
                $this->dataBase->query('CREATE TABLE '. mysql_real_escape_string(trim($_POST['tableName'])) .' (id CHAR(10) not NULL, isDeleted CHAR(1) NOT NULL DEFAULT \'0\', '. $extraColumns .' PRIMARY KEY(id), KEY(isDeleted)) charset=utf8');
            }else
            {
                $query='update __tableMetaData set';
            }
            $query.='
tableName=\''. mysql_real_escape_string(trim($_POST['tableName'])) .'\',
description=\''. mysql_real_escape_string(trim($_POST['description'])) .'\',
subtableDescription=\''. mysql_real_escape_string(trim($_POST['subtableDescription'])) .'\',
orderingColumnForListings=\''. mysql_real_escape_string(trim($_POST['orderingColumnForListings'])) .'\',
appearsInAdminMenu=\''. mysql_real_escape_string(trim($_POST['appearsInAdminMenu'])) .'\',
adminPresentation=\''. mysql_real_escape_string(trim($_POST['adminPresentation'])) .'\',
adminItemsPerPage=\''. mysql_real_escape_string(trim($_POST['adminItemsPerPage'])) .'\',
adminListMarkingCondition=\''. mysql_real_escape_string(trim($_POST['adminListMarkingCondition'])) .'\',
adminListMarkedStyle=\''. mysql_real_escape_string(trim($_POST['adminListMarkedStyle'])) .'\',
groupedByTable=\''. mysql_real_escape_string(trim($_POST['groupedByTable'])) .'\',
remoteGroupColumn=\''. mysql_real_escape_string(trim($_POST['remoteGroupColumn'])) .'\',
localGroupColumn=\''. mysql_real_escape_string(trim($_POST['localGroupColumn'])) .'\',
tablesGroupedByThis=\''. mysql_real_escape_string(trim($_POST['tablesGroupedByThis'])) .'\',
hasActivationFlag=\''. mysql_real_escape_string(trim($_POST['hasActivationFlag'])) .'\',
availableForSearching=\''. mysql_real_escape_string(trim($_POST['availableForSearching'])) .'\',
hasGhostTable=\''. mysql_real_escape_string(trim($_POST['hasGhostTable'])) .'\',
hasEmbededPictures = \''. mysql_real_escape_string(trim($_POST['hasEmbededPictures'])) .'\',
hasDeletedColumn=\''. mysql_real_escape_string(trim($_POST['hasDeletedColumn'])) .'\',
columnForMultipleTemplates=\''. mysql_real_escape_string(trim($_POST['columnForMultipleTemplates'])) .'\'';

            if ($this->tableId!='')
            {
                $query.=' where id=\''. $this->tableId .'\'';
                if ($this->tableName != mysql_real_escape_string(trim($_POST['tableName'])))
                {
                    $this->dataBase->query('RENAME TABLE '. $this->tableName .' TO '. mysql_real_escape_string(trim($_POST['tableName'])));
                }
            }
            $this->dataBase->query($query);

            if (trim($_POST['orderingColumnForListings'])!='' && $this->tableId=='')
                {
                    $this->dataBase->query('insert into __columnMetaData set 
                    id=\''. $this->dataBase->getNewId('__columnMetaData') .'\',
                    tableId=\''. $newTableId .'\',
                    name=\''. $actualColumn .'\',
                    description=\'Σειρά Εμφάνισης\',
                    type=\'1\',
                    length=\'\',
                    presentationType=\'1\',
                    isReadOnly=\'0\',
                    notNull=\'0\',
                    isInvisible=\'0\',
                    appearsInLists=\'0\',
                    isASearchableProperty=\'0\',
                    isReadOnlyAfterFirstUpdate=\'0\',
                    isForeignKey=\'0\',
                    presentationParameters=\'\',
                    valuesTable=\'\',
                    columnToShow=\'\',
                    columnToStore=\'\',
                    defaultValue=\'0\',
                    orderingMirror=\'\',
                    searchingMirror=\'\',
                    resizeWidth=\'\',
                    resizeHeight=\'\',
                    thumbnailWidth=\'\',
                    thumbnailHeight=\'\',
                    midSizeWidth=\'\',
                    midSizeHeight=\'\',
                    thumbnailColumn=\'\',
                    midSizeColumn=\'\',
                    ordering=\'9998\'');
                }
                if (trim($_POST['hasActivationFlag'])=='1' && $this->tableId=='')
                {
                    $this->dataBase->query('insert into __columnMetaData set
                    id=\''. $this->dataBase->getNewId('__columnMetaData') .'\',
                    tableId=\''. $newTableId .'\',
                    name=\'active\',
                    description=\'Ενεργό\',
                    type=\'1\',
                    length=\'\',
                    presentationType=\'5\',
                    isReadOnly=\'0\',
                    notNull=\'0\',
                    isInvisible=\'0\',
                    appearsInLists=\'0\',
                    isASearchableProperty=\'0\',
                    isReadOnlyAfterFirstUpdate=\'0\',
                    isForeignKey=\'0\',
                    presentationParameters=\'\',
                    valuesTable=\'\',
                    columnToShow=\'\',
                    columnToStore=\'\',
                    defaultValue=\'0\',
                    orderingMirror=\'\',
                    searchingMirror=\'\',
                    resizeWidth=\'\',
                    resizeHeight=\'\',
                    thumbnailWidth=\'\',
                    thumbnailHeight=\'\',
                    midSizeWidth=\'\',
                    midSizeHeight=\'\',
                    thumbnailColumn=\'\',
                    midSizeColumn=\'\',
                    ordering=\'9999\'');
                }
        }
    }
    
    public function handleFileUpload($postName='fileName')
    {
        global $absoluteFilesRepositoryPath;
        if (isset($_FILES[$postName]) && is_uploaded_file($_FILES[$postName]['tmp_name']))
        {
            $tempName = WOOOF::randomString(40);
            move_uploaded_file($_FILES[$postName]['tmp_name'], $absoluteFilesRepositoryPath . $tempName );
            chmod($absoluteFilesRepositoryPath . $tempName, 400);
            $externalFileId = $this->dataBase->getNewId('__externalFiles');
            $this->dataBase->query('insert into __externalFiles set id=\''. $externalFileId .'\', entryDate=\''. WOOOF::getCurrentDateTime() .'\', fileName=\''. $tempName .'\', originalFileName=\''. WOOOF::cleanUserInput(basename($_FILES[$postName]['name'])) .'\'');
            return $externalFileId;
        }
        return FALSE;
    }


    public function updateRowFromPost($rowId,$columnsToFill)
    {
        global $siteBasePath;
        global $__isAdminPage;
        
        if (!is_array($columnsToFill))
        {
            return FALSE;
        }
        
        $query='update '. $this->tableName .' set';
        
        $error='';
        $columnsToFill = array_values($columnsToFill);
        for($q = 0; $q < count($columnsToFill); $q++)
        {
            if ($columnsToFill[$q]!='id' && isset($this->columns[$columnsToFill[$q]]))
            {
                $metaData = $this->columns[$columnsToFill[$q]]->getColumnMetaData();
                $trimmedOrderingColumn = trim(str_replace(' desc', '', $this->getOrderingColumnForListings()));
                if ( $trimmedOrderingColumn== $columnsToFill[$q] && (isset($_POST[$columnsToFill[$q]]) && (trim($_POST[$columnsToFill[$q]]) == '0' || trim($_POST[$columnsToFill[$q]])=='')) && $metaData['type']== WOOOF_dataBaseColumnTypes::int)
                {
                    $oR = $this->dataBase->query('select max('. $trimmedOrderingColumn .') as maxOrd from '. $this->tableName);
                    $o = mysql_fetch_assoc($oR);
                    $_POST[$columnsToFill[$q]] = $o['maxOrd'] + 10;
                }
                if ($metaData['presentationType'] == WOOOF_columnPresentationTypes::file)
                {
                    $externalFileId=$this->handleFileUpload($columnsToFill[$q]);
                    if ($externalFileId===FALSE)
                    {
                        die('File Upload Failure!');
                    }else
                    {
                        $query.=' '. $columnsToFill[$q] .'=\''. $externalFileId .'\'';
                    }
                }elseif ($metaData['presentationType'] == WOOOF_columnPresentationTypes::picture && isset($_FILES[$columnsToFill[$q]]))
                {
                    if (trim($metaData['presentationParameters']) != '')
                    {
                        $outputPath = $siteBasePath . $metaData['presentationParameters'];
                    }else
                    {
                        $outputPath = $siteBasePath .'images/';
                    }

                    $fromFile = $outputPath. WOOOF::randomString(10) .'_'. $_FILES[$columnsToFill[$q]]['name'];
                    
                    $mvResult = move_uploaded_file($_FILES[$columnsToFill[$q]]['tmp_name'], $fromFile);
                    //echo $fromFile;
                    if ($mvResult)
                    {
                        if ($metaData['resizeWidth']!='')
                        {
                            $choppedFile='';
                            $filePieces = explode('.', $_FILES[$columnsToFill[$q]]['name']);
                            for($b=0; $b<(count($filePieces)-1); $b++)
                            {
                                $choppedFile.=$filePieces[$b].'.';
                            }

                            $choppedFile.='jpg';
                            $targetFilename = $this->tableId .'_'. $metaData['columnId'] .'_'. $rowId .'_'. $choppedFile;
                            
                            WOOOF::resizePicture($fromFile, $outputPath . $targetFilename, $metaData['resizeWidth'], $metaData['resizeHeight']);
                            $query.=' '. $columnsToFill[$q] .'=\''. WOOOF::cleanUserInput($targetFilename) .'\'';
                            if ($metaData['thumbnailWidth']!='')
                            {
                                WOOOF::resizePicture($fromFile, $outputPath . 'thumb_' .$targetFilename, $metaData['thumbnailWidth'], $metaData['thumbnailHeight']);
                                if ($metaData['thumbnailColumn']!='')
                                {
                                    $this->dataBase->query('update '. $this->tableName .' set '. $metaData['thumbnailColumn'] .'=\''. 'thumb_' .$targetFilename .'\' where id=\''. $rowId .'\'');
                                }
                            }
                            if ($metaData['midSizeWidth']!='')
                            {
                                WOOOF::resizePicture($fromFile, $outputPath . 'mid_' .$targetFilename, $metaData['midSizeWidth'], $metaData['midSizeHeight']);
                                if ($metaData['thumbnailColumn']!='')
                                {
                                    $this->dataBase->query('update '. $this->tableName .' set '. $metaData['midSizeColumn'] .'=\''. 'mid_' .$targetFilename .'\' where id=\''. $rowId .'\'');
                                }
                            }
                            unlink($fromFile);
                        }else
                        {
                            //echo basename(WOOOF::cleanUserInput($fromFile));
                            $query.=' '. $columnsToFill[$q] .'=\''. basename(WOOOF::cleanUserInput($fromFile)) .'\'';
                            //exit;
                        }
                    }else
                    {
                        $query.=' '. $columnsToFill[$q] .'='. $columnsToFill[$q];
                    }
                }elseif ($metaData['presentationType'] == WOOOF_columnPresentationTypes::htmlText)
                {
                    if (!$__isAdminPage)
                    {
                        require_once 'HTMLPurifier.standalone.php';
                        $config = HTMLPurifier_Config::createDefault();
                        $purifier = new HTMLPurifier($config);
                        $query.=' '. $columnsToFill[$q] .'=\''. mysql_real_escape_string($purifier->purify($_POST[$columnsToFill[$q]])) .'\'';
                    }else
                    {
                        $query.=' '. $columnsToFill[$q] .'=\''. mysql_real_escape_string($_POST[$columnsToFill[$q]]) .'\'';
                    }
                    
                }elseif ($metaData['presentationType'] == WOOOF_columnPresentationTypes::date || $metaData['presentationType'] == WOOOF_columnPresentationTypes::time || $metaData['presentationType'] == WOOOF_columnPresentationTypes::dateAndTime )
                {
                    if ($metaData['isReadOnly'] || (trim($_POST[$columnsToFill[$q].'1']) == '' && $_POST[$columnsToFill[$q].'4'] == ''))
                    {
                        $tempDate = WOOOF::getCurrentDateTime();
                    }
                    else
                    {
                        $tempDate = WOOOF::buildDateTimeFromAdminPost($columnsToFill[$q]);
                    }
                     if ($this->columns[$columnsToFill[$q]]->checkValue($tempDate) === TRUE)
                    {
                        $query.=' '. $columnsToFill[$q] .'=\''. WOOOF::cleanUserInput($tempDate) .'\'';
                    }
                }else
                {
                    if ($this->columns[$columnsToFill[$q]]->checkValue($_POST[$columnsToFill[$q]]) === TRUE)
                    {
                        $query.=' '. $columnsToFill[$q] .'=\''. WOOOF::cleanUserInput($_POST[$columnsToFill[$q]]) .'\'';
                    }else
                    {
                        $error.=$this->columns[$columnsToFill[$q]]->checkValue($_POST[$columnsToFill[$q]]);
                    }
                }
                if ($q<count($columnsToFill)-1)
                {
                    $query.=',';
                }
            }
        }
        if ($error!='')
        {
            return $error;
        }
        $query.=' where id=\''. WOOOF::cleanUserInput($rowId) .'\'';
        //echo $query;

        $this->dataBase->query($query);
        //exit;
        return TRUE;
    }
    
    public function presentResultsWithSecurityRole($htmlFragment, $locationBase, $role, $requestedAction)
    {
        $rowsHtml='';
        for($k = 0; $k<count($this->resultRows)/2; $k++)
        {
            $permitions = $this->dataBase->getSecurityPermitionsForLocationAndRole($locationBase . $this->resultRows[$k]['id'], $role);
            if (isset($permitions[$requestedAction]) && $permitions[$requestedAction]===TRUE)
            {
                $rowsHtml .= $this->presentRowReadOnly($this->resultRows[$k]['id'],$htmlFragment);
            }
        }
        return $rowsHtml;
    }
    public function presentResultsWithSecurityUser($htmlFragment, $locationBase, $user, $requestedAction)
    {
        $rowsHtml='';
        for($k = 0; $k<count($this->resultRows)/2; $k++)
        {
            $permitions = $this->dataBase->getSecurityPermitionsForLocationAndUser($locationBase . $this->resultRows[$k]['id'], $user);
            //echo $locationBase . $this->resultRows[$k]['id'] .'<br/>';
            
            if (isset($permitions[$requestedAction]) && $permitions[$requestedAction]===TRUE)
            {
                $rowsHtml .= $this->presentRowReadOnly($this->resultRows[$k]['id'],$htmlFragment);
            }
        }
        return $rowsHtml;
    }
    
    public function presentResults($htmlFragment)
    {
        $rowsHtml='';
        for($k = 0; $k<count($this->resultRows)/2; $k++)
        {
            $rowsHtml .= $this->presentRowReadOnly($this->resultRows[$k]['id'],$htmlFragment);
        }
        return $rowsHtml;
    }
    
    public function presentResultsWithRowHighlight($htmlFragment, $rowId, $extraClass)
    {
        $rowsHtml='';
        for($k = 0; $k<count($this->resultRows)/2; $k++)
        {
            if ($this->resultRows[$k]['id'] == $rowId)
            {
                $modifiedFragment=str_replace('@@@extraClass@@@', ' '. $extraClass, $htmlFragment);
            }else
            {
                $modifiedFragment=str_replace('@@@extraClass@@@', ' ', $htmlFragment);
            }
            $rowsHtml .= $this->presentRowReadOnly($this->resultRows[$k]['id'],$modifiedFragment);
        }
        return $rowsHtml;
    }
    
    public function presentRowReadOnly($rowId,$htmlFragment='')
    {
        return $this->presentRow($rowId, 'read', $htmlFragment);
    }
    
    public function presentRowForInsert($htmlFragment='')
    {
        return $this->presentRow('', 'insert', $htmlFragment);
    }
    
    public function presentRowForUpdate($rowId,$htmlFragment='')
    {
        return $this->presentRow($rowId, 'update', $htmlFragment);
    }
    
    private function presentRow($rowId, $type, $htmlFragment='')
    {

        $buildFromScratch = FALSE;
        
        if ($rowId != '')
        {
            if (isset($this->resultRows[$rowId]))
            {
                $row = $this->resultRows[$rowId];
            }else
            {
                $result = $this->dataBase->query('select * from '. $this->tableName .' where id=\''. $rowId .'\'');
                $row = mysql_fetch_assoc($result);
            }
        }else if ($rowId == '' && $type=='update')
        {
            die('Requested update with empty row ID !!! @ presentRow');
        }else if ($rowId == '' && $type=='read')
        {
            die('Requested read with empty row ID !!! @ presentRow'. $htmlFragment);
        }
        
        if ($htmlFragment == '')
        {
            $htmlFragment='<table cellpadding="2" cellspacing="1" bgcolor="#000000">
';
            $buildFromScratch = TRUE;
        }

        for($counter=0; $counter<count($this->columns)/2; $counter++)
        {
            $metaData = $this->columns[$counter]->getColumnMetaData();
            
            if ($metaData['isInvisible']=='1')
            {
                continue;
            }
            //print_r($metaData);
            //echo '<br>';
            $value='';
            if ($type == 'read')
            {
                $value = $this->columns[$counter]->renderReadOnly($row[$metaData['name']], $row['id']);
            }else if ($type == 'insert')
            {
                $value = $this->columns[$counter]->renderForInsert();
            }else if ($type == 'update')
            {
                $value = $this->columns[$counter]->renderForEdit($row[$metaData['name']],$rowId);
                //echo 'value -> '. $row[$metaData['name']] .' '. $value .'<br>';
            }
            if ($buildFromScratch)
            {
                $htmlFragment.='<tr>
    <td bgcolor="#FFFFFF">'. $metaData['description'] .'</td><td bgcolor="#FFFFFF">'. $value .'</td>
</tr>';
            }else
            {
                $htmlFragment = str_replace('@@@'. $metaData['name'] .'@@@', $value, $htmlFragment);
            }                
        }
        if ($buildFromScratch)
        {
            $htmlFragment.='</table>';
        }
        
        if (isset($row['id']))
        {
            $htmlFragment = str_replace('@@@id@@@', $row['id'], $htmlFragment);
        }
        
        return $htmlFragment;
    }

    public function constructAdministrationFragment()
    {
        global $siteBaseURL;
        global $cssFileNameForTinyMCE;

        $output='';
        $richColumns='';
        foreach ($this->columns as $column) 
        {
            $columnDescr = $column->getColumnMetaData();
            if ($columnDescr['isInvisible']!='1' && !isset($output[$columnDescr['ordering']]))
            {
                if ($columnDescr['presentationType']=='13')
                {
                    $headClass = 'editorDescription';
                    $itemClass = 'thumbLink';
                    $formFieldsClass = 'formFields';
                }else if ($columnDescr['presentationType']!='3' && $columnDescr['presentationType']!='4')
                {
                    $headClass = 'itemDescription';
                    $itemClass = 'itemValue';
                    $formFieldsClass = 'formFields';
                }else
                {
                    $headClass = 'editorDescription';
                    $itemClass = 'editor';
                    if ( $columnDescr['presentationType']=='4')
                    {
                        if ($richColumns!='') $richColumns.=', ';
                        $richColumns .= $columnDescr['name'];
                    }
                    $formFieldsClass = 'editorformFields';
                }
                $output[$columnDescr['ordering']] = '<section class="'. $formFieldsClass .'">
      <div class="'. $headClass .'">'. $columnDescr['description'] .'</div>
      <div class="'. $itemClass .'">@@@'. $columnDescr['name'] .'@@@</div>
    </section>';
            }
        }
        ksort($output);
        $returnFragment[0] = implode("\n", $output);
        if ($richColumns!='')
        {
            $editorScripts='tinyMCE.init({
        // General options
        mode : "exact",
        elements : "@@@columns@@@",
        theme : "advanced",
        entity_encoding : "raw",
        browser_spellcheck : true,
        gecko_spellcheck: true,
        plugins : "autolink,lists,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

        // Theme options
        theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
        theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,spellchecker,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,|,forecolor,backcolor",
        theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
        theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true,

        // Skin options
        skin : "o2k7",
        skin_variant : "silver",

        // Example content CSS (should be your site CSS)
        content_css : "'. $cssFileNameForTinyMCE .'",

        // Drop lists for link/image/media/template dialogs
        template_external_list_url : "js/template_list.js",
        external_link_list_url : "js/link_list.js",
        external_image_list_url : "js/image_list.js",
        media_external_list_url : "js/media_list.js",

        // Replace values for the template plugin
        template_replace_values : {
                username : "Some User",
                staffid : "991234"
        }
});
';
            $returnFragment[1] = str_replace('@@@columns@@@', $richColumns, $editorScripts);
        }

        return $returnFragment;
    }

    public function getInsertableColumns()
    {
        $output='';
        foreach ($this->columns as $column) 
        {
            $columnDescr = $column->getColumnMetaData();
            if ($columnDescr['isInvisible']!='1' || ($this->getOrderingColumnForListings() == $columnDescr['name']))
            {
                $output[$columnDescr['ordering']-1] = $columnDescr['name'];
            }
        }
        return $output;
    }

    public function handleMultiInsertFromPost($columnsToFill,$mandatory,$howManyRepetitions)
    {
        global $imagesRelativePath;
        global $__isAdminPage;
        
        for($z=0; $z<$howManyRepetitions; $z++)
        {
            $insertId=FALSE;

            $query='insert into '. $this->tableName .' set';

            if (is_array($columnsToFill))
            {
                for($q = 0; $q < count($columnsToFill); $q++)
                {
                    $dropInsert=0;
                    if ($columnsToFill[$q]!='id')
                    {
                        $metaData = $this->columns[$columnsToFill[$q]]->getColumnMetaData();
                        if ($metaData['presentationType'] == WOOOF_columnPresentationTypes::file)
                        {
                            if(is_uploaded_file($_FILES[$columnsToFill[$q]]['tmp_name'][$q]))
                            {
                                $externalFileId=$this->handleFileUpload($columnsToFill[$q]);
                                if ($externalFileId===FALSE)
                                {
                                    die('File Upload Failure!');
                                }else
                                {
                                    $query.=' '. $columnsToFill[$q] .'=\''. $externalFileId .'\',';
                                }
                            }
                        }else if ($metaData['presentationType'] == WOOOF_columnPresentationTypes::picture)
                        {
                            if(is_uploaded_file($_FILES[$columnsToFill[$q]]['tmp_name'][$q]))
                            {
                                if (trim($metaData['presentationParameters']) != '')
                                {
                                    $outputPath = $siteBasePath . $metaData['presentationParameters'];
                                }else
                                {
                                    $outputPath = $siteBasePath . $imagesRelativePath;
                                }

                                if ($metaData['resizeWidth']=='')
                                {
                                    $imageInfo = getimagesize($_FILES[$columnsToFill[$q]]['tmp_name'][$q]);
                                    $metaData['resizeWidth']=$imageInfo[0];
                                    $metaData['resizeHeight']=$imageInfo[1];
                                }

                                $choppedFile='';
                                $filePieces = explode('.', $_FILES[$columnsToFill[$q]]['name'][$q]);
                                for($b=0; $b<count($filePieces)-1; $b++)
                                {
                                    $choppedFile.=$filePieces[$b].'.';
                                }

                                $choppedFile.='jpg';
                                $targetFilename = $this->tableId .'_'. $metaData['columnId'] .'_'. $rowId .'_'. $choppedFile;

                                WOOOF::resizePicture($_FILES[$columnsToFill[$q]]['tmp_name'][$q], $outputPath . $targetFilename, $metaData['resizeWidth'], $metaData['resizeHeight']);
                                $query.=' '. $columnsToFill[$q] .'=\''. WOOOF::cleanUserInput($targetFilename) .'\',';
                                if ($metaData['thumbnailWidth']!='')
                                {
                                    WOOOF::resizePicture($_FILES[$columnsToFill[$q]]['tmp_name'][$q], $outputPath . 'thumb_' .$targetFilename, $metaData['thumbnailWidth'], $metaData['thumbnailHeight']);
                                    if ($metaData['thumbnailColumn']!='')
                                    {
                                        $this->dataBase->query('update '. $this->tableName .' set '. $metaData['thumbnailColumn'] .'=\''. 'thumb_' .$targetFilename .'\' where id=\''. $rowId .'\'');
                                    }
                                }
                                if ($metaData['midSizeWidth']!='')
                                {
                                    WOOOF::resizePicture($_FILES[$columnsToFill[$q]]['tmp_name'][$q], $outputPath . 'mid_' .$targetFilename, $metaData['midSizeWidth'], $metaData['midSizeHeight']);
                                    if ($metaData['midSizeColumn']!='')
                                    {
                                        $this->dataBase->query('update '. $this->tableName .' set '. $metaData['midSizeColumn'] .'=\''. 'mid_' .$targetFilename .'\' where id=\''. $rowId .'\'');
                                    }
                                }
                            }
                        }else if ($metaData['presentationType'] == WOOOF_columnPresentationTypes::htmlText)
                        {
                            if (!$__isAdminPage)
                            {
                                require_once 'HTMLPurifier.standalone.php';
                                $config = HTMLPurifier_Config::createDefault();
                                $purifier = new HTMLPurifier($config);
                                $query.=' '. $columnsToFill[$q] .'=\''. mysql_real_escape_string($purifier->purify($_POST[$columnsToFill[$q]][$z])) .'\',';
                            }else
                            {
                                $query.=' '. $columnsToFill[$q] .'=\''. mysql_real_escape_string($_POST[$columnsToFill[$q]][$z]) .'\',';
                            }

                            if (trim($_POST[$columnsToFill[$q]][$z])=='' && $mandatory[$q]=='1')
                            {
                                $dropInsert=1;
                            }
                        }else
                        {
                            $query.=' '. $columnsToFill[$q] .'=\''. WOOOF::cleanUserInput($_POST[$columnsToFill[$q]][$z]) .'\',';
                            if (trim($_POST[$columnsToFill[$q]][$z])=='' && $mandatory[$q]=='1')
                            {
                                $dropInsert=1;
                            }
                        }
                    }
                }
            }

            $insertId=$this->dataBase->getNewId($this->tableName);

            $query.=' id=\''. $insertId .'\'';
            if (!$dropInsert){
                $this->dataBase->query($query);
                $ids[] = $insertId;
            }
            
        }
        return $ids;
    }
    
    public function handleInsertFromPost($columnsToFill)
    {
        global $imagesRelativePath;
        global $siteBasePath;
        global $__isAdminPage;
        
        $insertId=FALSE;
        
        $query='insert into '. $this->tableName .' set';
        
        if (is_array($columnsToFill))
        {
            foreach($columnsToFill as $column)
            {
                if ($column!='id')
                {
                    $metaData = $this->columns[$column]->getColumnMetaData();
                    $trimmedOrderingColumn = trim(str_replace(' desc', '', $this->getOrderingColumnForListings()));
                    if ( $trimmedOrderingColumn== $column && (!isset($_POST[$column]) || (trim($_POST[$column]) == '0' || trim($_POST[$column])=='')) && $metaData['type']== WOOOF_dataBaseColumnTypes::int)
                    {
                        $oR = $this->dataBase->query('select max('. $trimmedOrderingColumn .') as maxOrd from '. $this->tableName);
                        $o = mysql_fetch_assoc($oR);
                        $_POST[$column] = $o['maxOrd'] + 10;
                    }
                    if ($metaData['presentationType'] == WOOOF_columnPresentationTypes::file)
                    {
                        if(is_uploaded_file($_FILES[$column]['tmp_name']))
                        {
                            $externalFileId=$this->handleFileUpload($column);
                            if ($externalFileId===FALSE)
                            {
                                die('File Upload Failure!');
                            }else
                            {
                                $query.=' '. $column .'=\''. $externalFileId .'\',';
                            }
                        }
                    }else if ($metaData['presentationType'] == WOOOF_columnPresentationTypes::picture)
                    {
                        if (trim($metaData['presentationParameters']) != '')
                        {
                            $outputPath = $siteBasePath . $metaData['presentationParameters'];
                        }else
                        {
                            $outputPath = $siteBasePath .'images/';
                        }

                        $fromFile = $outputPath. WOOOF::randomString(10) .'_'. $_FILES[$column]['name'];
                        
                        $mvResult = move_uploaded_file($_FILES[$column]['tmp_name'], $fromFile);
                        //echo $fromFile;
                        if ($mvResult)
                        {
                            if ($metaData['resizeWidth']!='')
                            {
                                $choppedFile='';
                                $filePieces = explode('.', $_FILES[$column]['name']);
                                for($b=0; $b<(count($filePieces)-1); $b++)
                                {
                                    $choppedFile.=$filePieces[$b].'.';
                                }

                                $choppedFile.='jpg';
                                $targetFilename = $this->tableId .'_'. $metaData['columnId'] .'_'. $rowId .'_'. $choppedFile;
                                
                                WOOOF::resizePicture($fromFile, $outputPath . $targetFilename, $metaData['resizeWidth'], $metaData['resizeHeight']);
                                $query.=' '. $column .'=\''. WOOOF::cleanUserInput($targetFilename) .'\', ';
                                if ($metaData['thumbnailWidth']!='')
                                {
                                    WOOOF::resizePicture($fromFile, $outputPath . 'thumb_' .$targetFilename, $metaData['thumbnailWidth'], $metaData['thumbnailHeight']);
                                    if ($metaData['thumbnailColumn']!='')
                                    {
                                        $this->dataBase->query('update '. $this->tableName .' set '. $metaData['thumbnailColumn'] .'=\''. 'thumb_' .$targetFilename .'\' where id=\''. $rowId .'\'');
                                    }
                                }
                                if ($metaData['midSizeWidth']!='')
                                {
                                    WOOOF::resizePicture($fromFile, $outputPath . 'mid_' .$targetFilename, $metaData['midSizeWidth'], $metaData['midSizeHeight']);
                                    if ($metaData['thumbnailColumn']!='')
                                    {
                                        $this->dataBase->query('update '. $this->tableName .' set '. $metaData['midSizeColumn'] .'=\''. 'mid_' .$targetFilename .'\' where id=\''. $rowId .'\'');
                                    }
                                }
                                unlink($fromFile);
                            }else
                            {
                                //echo basename(WOOOF::cleanUserInput($fromFile));
                                $query.=' '. $column .'=\''. basename(WOOOF::cleanUserInput($fromFile)) .'\',';
                                //exit;
                            }
                        }else
                        {
                            $query.=' '. $column .'='. $column .', ';
                        }
                    }else if ($metaData['presentationType'] == WOOOF_columnPresentationTypes::htmlText)
                    {
                        if (!$__isAdminPage)
                        {
                            require_once 'HTMLPurifier.standalone.php';
                            $config = HTMLPurifier_Config::createDefault();
                            $purifier = new HTMLPurifier($config);
                            $query.=' '. $column .'=\''. mysql_real_escape_string($purifier->purify($_POST[$column])) .'\',';
                        }else
                        {
                            $query.=' '. $column .'=\''. mysql_real_escape_string($_POST[$column]) .'\',';
                        }
                    }else if ($metaData['presentationType'] == WOOOF_columnPresentationTypes::date || $metaData['presentationType'] == WOOOF_columnPresentationTypes::time || $metaData['presentationType'] == WOOOF_columnPresentationTypes::dateAndTime && isset($_POST[$column.'1']))
                    {
                        if ($metaData['isReadOnly'] || (trim($_POST[$column.'1']) == '' && $_POST[$column.'4'] == ''))
                        {
                            $tempDate = WOOOF::getCurrentDateTime();
                        }else
                        {
                            $tempDate = WOOOF::buildDateTimeFromAdminPost($column);
                        }
                         if ($this->columns[$column]->checkValue($tempDate) === TRUE)
                        {
                            $query.=' '. $column .'=\''. WOOOF::cleanUserInput($tempDate) .'\',';
                        }
                    }else
                    {
                        $query.=' '. $column .'=\''. WOOOF::cleanUserInput($_POST[$column]) .'\',';
                    }
                }
            }
        }
        
        $insertId=$this->dataBase->getNewId($this->tableName);
        
        $query.=' id=\''. $insertId .'\'';
        
        $this->dataBase->query($query);

        return $insertId;
    }
    
    public function renderSubtableAsCheckBoxes($row, $subTableName, $optionsTableName, $itemsPerRow, $className='')
    {
        global $cssForFormItem;
        
        $output = '<table cellpadding="2" cellspacing="0" border="0" style="background-color:inherit; font-size: 14px;">
';
        //echo '1<br/>';
        $subTable = new WOOOF_dataBaseTable($this->dataBase, $subTableName);
        //echo '2<br/>';
        $optionsTable = new WOOOF_dataBaseTable($this->dataBase, $optionsTableName);
        //echo '3<br/>';
        $optionsTable->getResult('',$optionsTable->getOrderingColumnForListings());
        //echo $subTable->getOrderingColumnForListings() .'<br/>';
        $whereClauses[$subTable->getLocalGroupColumn()] = $row[$subTable->getRemoteGroupColumn()];
        
        $subTable->getResult($whereClauses);
        
        $howManyItems = count($optionsTable->resultRows)/2;
        $howManyRows = ceil($howManyItems / $itemsPerRow);
        $itemsOut = 0;
        $howManyItemsSub = count($subTable->resultRows)/2;
        $checkBoxName = $subTable->getTableId();        
        
        for($n = 0; $n < count($subTable->columns)/2; $n++)
        {
            $columnMetaData = $subTable->columns[$n]->getColumnMetaData();
            if ($columnMetaData['valuesTable']==$optionsTable->getTableName())
            {
                $presentationColumn = $columnMetaData['name'];
                $presentationValueColumn = $columnMetaData['columnToStore']; 
                $presentationShowColumn = $columnMetaData['columnToShow'];
            }
        }
        
        if ($className=='')
        {
            $className = $cssForFormItem['checkBox'];
        }
        
        for($q = 0; $q < $howManyRows; $q++)
        {
            $output .= '<tr style="border-bottom:0px;">';
            for($z = 0; $z < $itemsPerRow; $z++)
            {
                if ($itemsOut<$howManyItems)
                {
                    $output .= '<td style="margin-top:5px;">';
                    $isChecked = FALSE;
                    for($n = 0; $n < $howManyItemsSub; $n++)
                    {
                        if ($subTable->resultRows[$n][$presentationColumn]==$optionsTable->resultRows[$itemsOut][$presentationValueColumn])
                        {
                            $isChecked = TRUE;
                        }
                    }
                    if ($isChecked)
                    {
                        $checked=' checked';
                    }else
                    {
                        $checked='';
                    }
                    $output .= '<input type="checkbox" name="'. $checkBoxName .'[]" value="'. $optionsTable->resultRows[$itemsOut][$presentationValueColumn] .'"'. $checked .' class="'. $className .'" style="margin-top: 0px;"> '. $optionsTable->resultRows[$itemsOut][$presentationShowColumn];
                    $output .= '</td>';
                }else
                {
                    $output .= '<td>&nbsp;</td>';
                }
                $itemsOut++;
            }
            $output .= '</tr>';
        }
        $output.='</table>';
        return $output;
    }
    
public function renderSubtableReadOnly($row, $subTableName, $optionsTableName, $className='', $separator=' | ')
    {
        global $cssForFormItem;
        
        
        //TODO : items per row fix up please 
        
        $itemsPerRow = 99999;
        if ($separator!='') $output = '<span class="'. $className .'">| </span>';
        $subTable = new WOOOF_dataBaseTable($this->dataBase, $subTableName);
        $optionsTable = new WOOOF_dataBaseTable($this->dataBase, $optionsTableName);
        $optionsTable->getResult('',$optionsTable->getOrderingColumnForListings());
        $whereClauses[$subTable->getLocalGroupColumn()] = $row[$subTable->getRemoteGroupColumn()];
        
        $subTable->getResult($whereClauses);
        
        $howManyItems = count($optionsTable->resultRows)/2;
        $howManyRows = ceil($howManyItems / $itemsPerRow);
        $itemsOut = 0;
        $howManyItemsSub = count($subTable->resultRows)/2;
        $checkBoxName = $subTable->getTableId();        
        
        for($n = 0; $n < count($subTable->columns)/2; $n++)
        {
            $columnMetaData = $subTable->columns[$n]->getColumnMetaData();
            if ($columnMetaData['valuesTable']==$optionsTable->getTableName())
            {
                $presentationColumn = $columnMetaData['name'];
                $presentationValueColumn = $columnMetaData['columnToStore']; 
                $presentationShowColumn = $columnMetaData['columnToShow'];
            }
        }
        
        if ($className=='')
        {
            $className = $cssForFormItem['checkBox'];
        }
        
        for($q = 0; $q < $howManyRows; $q++)
        {
            for($z = 0; $z < $itemsPerRow; $z++)
            {
                if ($itemsOut<$howManyItems)
                {
                    $isChecked = FALSE;
                    for($n = 0; $n < $howManyItemsSub; $n++)
                    {
                        if ($subTable->resultRows[$n][$presentationColumn]==$optionsTable->resultRows[$itemsOut][$presentationValueColumn])
                        {
                            $isChecked = TRUE;
                        }
                    }
                    if ($isChecked)
                    {
                        $output.= '<span class="'. $className .'">'. $optionsTable->resultRows[$itemsOut][$presentationShowColumn] . $separator .'</span>';
                    }
                }
                $itemsOut++;
            }

        }
        return $output;
    }
    
    public function updateSubtableFromPostCheckBoxes($row,$subtableName, $optionsTable)
    {
        $sT = new WOOOF_dataBaseTable($this->dataBase, $subtableName);
        
        $sTId = $sT->getTableId();
        
        $this->dataBase->query('delete from '. $sT->getTableName() .' where '. $sT->getLocalGroupColumn() .'=\''. $row[$sT->getRemoteGroupColumn()] .'\'');

        if (isset($_POST[$sTId]))
        {
            $oT = new WOOOF_dataBaseTable($this->dataBase, $optionsTable);
            $oT->getResult('');
            
            for($n = 0; $n < count($sT->columns)/2; $n++)
            {
                $columnMetaData = $sT->columns[$n]->getColumnMetaData();
                if ($columnMetaData['valuesTable']==$oT->getTableName())
                {
                    $presentationColumn = $columnMetaData['name'];
                }
            }
            
            while(list($key,$val) = each($_POST[$sTId]))
            {
                $this->dataBase->query('insert into '. WOOOF::cleanUserInput($sT->getTableName()) .' (id,'. WOOOF::cleanUserInput($sT->getLocalGroupColumn()) .','. WOOOF::cleanUserInput($presentationColumn) .') values (\''. $this->dataBase->getNewId($sT->getTableName()) .'\',\''. WOOOF::cleanUserInput($row[$sT->getRemoteGroupColumn()]) .'\',\''. WOOOF::cleanUserInput($val) .'\')');
            }
        }
    }

    public function presentTree($columnsToShow,$htmlFragment,$rowId=null,$onClass='on',$offClass='off')
    {
        $whereClauses[$this->getLocalGroupColumn()]='-1';
        $this->getResult($whereClauses,$this->getOrderingColumnForListings());
        $output='<ul class="treeLevel1">
';

        for ($i=0; $i < count($this->resultRows)/2; $i++) 
        { 
            if ($this->hasActivationFlag)
            {
                if ($this->resultRows[$i]['active']=='1')
                {
                    $activation = '<a href="administration.php?action=deactivate&address=1_'. $this->tableId .'_'. $this->resultRows[$i]['id'] .'" class="'. $onClass .'">Active</a>';
                }else
                {
                    $activation = '<a href="administration.php?action=activate&address=1_'. $this->tableId .'_'. $this->resultRows[$i]['id'] .'" class="'. $offClass .'">Inactive</a>';
                }
            }else
            {
                $activation = '';
            }
            if (trim($this->getOrderingColumnForListings()!=''))
            {
                $upDown = ' <a href="administration.php?action=moveUp&address=1_'. $this->tableId .'_'. $this->resultRows[$i]['id'] .'"><img src="images/arrowUp.png" border="0" alt="Up this item in order"></a><a href="administration.php?action=moveDown&address=1_'. $this->tableId .'_'. $this->resultRows[$i]['id'] .'"><img src="images/arrowDown.png" border="0" alt="Down this item in order"></a>';
            }
            $tmp = str_replace('@@@'. $columnsToShow .'@@@', $this->resultRows[$i][$columnsToShow], $htmlFragment);
            $tmp = str_replace('@@@id@@@', $this->resultRows[$i]['id'], $tmp);
            $tmp = str_replace('@@@tableId@@@', $this->tableId, $tmp);
            if ($rowId==$this->resultRows[$i]['id'])
            {
                $tmp = str_replace('@@@level@@@', 1 .' articleMenuSelected', $tmp);
            }else
            {
                $tmp = str_replace('@@@level@@@', 1, $tmp);
            }
            $tmp = str_replace('@@@activation@@@', $activation, $tmp);
            $tmp = str_replace('@@@upDown@@@', $upDown, $tmp);
            $tmp = str_replace('@@@subItems@@@', $this->presentTreeNode($this->resultRows[$i]['id'],$columnsToShow,2,$htmlFragment,$rowId,$onClass,$offClass), $tmp);

            $output .= $tmp;
            /*
            $output.='<li class="treeItemLevel1">'. $this->resultRows[$i][$columnsToShow] .' &nbsp; '. $activation .' <a href="administration.php?address=1_'. $this->tableId .'_'. $this->resultRows[$i]['id'] .'&action=edit"><img border="0" align="top" alt="edit" src="images/edit.png"></a> &nbsp; <a href="javascript:confirmDelete(\'administration.php?address=1_'. $this->tableId .'_'. $this->resultRows[$i]['id'] .'&action=delete\');"><img border="0" align="top" alt="Delete" src="images/delete.png"></a>
          '. $this->presentTreeNode($this->resultRows[$i]['id'],$columnsToShow,2,$htmlFragment) .'</li>
';*/
        }
        $output.='</ul>
';
        return $output;
    }

    private function presentTreeNode($nodeId, $columnsToShow,$level,$htmlFragment,$rowId=null,$onClass='on',$offClass='off')
    {
        if (trim($this->getOrderingColumnForListings())!='')
        {
            $order=' order by '. $this->getOrderingColumnForListings();
        }else
        {
            $order='';
        }
        $result = $this->dataBase->query('select * from '. $this->getTableName() .' where '. $this->getLocalGroupColumn() .' = \''. $nodeId .'\''. $order);
        if (!mysql_num_rows($result))
        {
            return '';
        }
        $output='<ul class="treeLevel'. $level .'">
';
        while ($row = mysql_fetch_assoc($result)) 
        {
            if ($this->hasActivationFlag)
            {
                if ($row['active']=='1')
                {
                    $activation = '<a href="administration.php?action=deactivate&address=1_'. $this->tableId .'_'. $row['id'] .'" class="'. $onClass .'">Active</a>';
                }else
                {
                    $activation = '<a href="administration.php?action=activate&address=1_'. $this->tableId .'_'. $row['id'] .'" class="'. $offClass .'">Inactive</a>';
                }
            }else
            {
                $activation = '';
            }
            if (trim($this->getOrderingColumnForListings()!=''))
            {
                $upDown = ' <a href="administration.php?action=moveUp&address=1_'. $this->tableId .'_'. $row['id'] .'"><img src="images/arrowUp.png" border="0" alt="Up this item in order"></a><a href="administration.php?action=moveDown&address=1_'. $this->tableId .'_'. $row['id'] .'"><img src="images/arrowDown.png" border="0" alt="Down this item in order"></a>';
            }
            $tmp = str_replace('@@@'. $columnsToShow .'@@@', $row[$columnsToShow], $htmlFragment);
            $tmp = str_replace('@@@id@@@', $row['id'], $tmp);
            $tmp = str_replace('@@@tableId@@@', $this->tableId, $tmp);
            if ($rowId==$row['id'])
            {
                $tmp = str_replace('@@@level@@@',  $level .' articleSubMenuSelected', $tmp);
            }else
            {
                $tmp = str_replace('@@@level@@@',  $level, $tmp);
            }
            $tmp = str_replace('@@@activation@@@', $activation, $tmp);
            $tmp = str_replace('@@@upDown@@@', $upDown, $tmp);
            $tmp = str_replace('@@@subItems@@@', $this->presentTreeNode($row['id'],$columnsToShow,($level+1),$htmlFragment), $tmp);
            $output .= $tmp;
/*
            $output .= '<li class="treeItemLevel'. $level .'">'. $row[$columnsToShow] .' &nbsp; '. $activation .' <a href="administration.php?address=1_'. $this->tableId .'_'. $row['id'] .'&action=edit"><img border="0" align="top" alt="edit" src="images/edit.png"></a> &nbsp; <a href="javascript:confirmDelete(\'administration.php?address=1_'. $this->tableId .'_'. $row['id'] .'&action=delete\');"><img border="0" align="top" alt="Delete" src="images/delete.png"></a>
          '. $this->presentTreeNode($row['id'],$columnsToShow,($level+1)) .'</li>
';*/
        }
        $output.='</ul>
';
        return $output;
    }

    public function deleteRow($rowId)
    {
        if($this->hasDeletedColumn)
        {
            $this->dataBase->query('update '. $this->tableName .' set isDeleted=\'1\' where id=\''. WOOOF::cleanUserInput($rowId) .'\'');
        }else
        {
            if ($this->hasGhostTable)
            {
                //TODO: ghost table stuf goes here
            }
            $this->dataBase->query('delete from '. $this->tableName .' where id=\''. WOOOF::cleanUserInput($rowId) .'\'');
        }

    }

    public function getAdminListRows($headers,$presentation,$displayActivation,$displayPreview,$displayUpDown)
    {
        global $siteBaseURL;

        $max = count($headers);
        if ($displayActivation)
        {
            $max++;
        }
        $rowClass='objectRowDark';
        $output='';
        $extraURLBit='';
        if (!count($this->resultRows)) return '';
        foreach( $this->resultRows as $row ) 
        {
            $template='<div class="'. $rowClass .'">
';
            $z=0;
            foreach($row as $item => $value) 
            {
                if ($item!='id' && $item!='active')
                {
                    $template.='<div class="'. $presentation[$z] .'">@@@'. $item .'@@@</div>
';                  $z++;
                }
                
            }                

            if ($_GET['action']=='edit')
            {
                $extraRequestBit='&from=edit';
            }else
            {
                $extraRequestBit='&from=read';
            }

            if ($displayActivation)
            {

                if ($row['active']=='1')
                {
                    $template.='<div class="objectPropertyCellSmall"><a href="administration.php?action=deactivate&address=1_'. $this->tableId .'_'. $row['id'] . $extraRequestBit .'" class="on">Active</a></div>
';
                }else
                {
                    $template.='<div class="objectPropertyCellSmall"><a href="administration.php?action=activate&address=1_'. $this->tableId .'_'. $row['id'] . $extraRequestBit  .'" class="off">Inctive</a></div>
';
                }
            }
            $template.='<div class="objectControls">
    <a href="administration.php?&address=1_'. $this->tableId .'_'. $row['id'] .'&action=edit'. $extraURLBit .'"><img src="images/edit.png" border="0" alt="Edit this item."></a>
';
            if ($displayUpDown!==false)
            {
                $template.='<a href="administration.php?action=moveUp&address=1_'. $this->tableId .'_'. $row['id'] . $extraRequestBit  .'"><img src="images/arrowUp.png" border="0" alt="Up this item in order"></a><a href="administration.php?action=moveDown&address=1_'. $this->tableId .'_'. $row['id'] . $extraRequestBit  .'"><img src="images/arrowDown.png" border="0" alt="Down this item in order"></a>
';
            }
            if ($displayPreview!==false)
            {

                $template.='    <a href="'. $siteBaseURL . $displayPreview .'"><img src="images/preview.png" border="0" alt="Preview Item In Site" target="_blank"></a>
';
            }
            $template.='    <a href="javascript:confirmDelete(\'administration.php?address=1_'. $this->tableId .'_'. $row['id'] .'&action=delete'. $extraRequestBit .'\');"><img src="images/delete.png" border="0" alt="Delete this item"></a>
    </div>
';
            $template.='</div>
';

            $output .= $this->presentRowReadOnly($row['id'], $template);
            if ($rowClass=='objectRowDark')
            {
                $rowClass='objectRow';
            }else
            {
                $rowClass='objectRowDark';
            }
        }

        return $output;
    }
}

/*
 * WOOOF_dataBaseColumn
 * 
 * Stores all the meta data for a column
 * Can also commit updates to the database
 * if the user has enough privileges. 
 */

class WOOOF_dataBaseColumn
{
    private $dataBase;
    private $tableId;
    private $columnId;
    private $name;
    private $description;
    private $type;
    private $presentationType;
    private $length;
    private $isReadOnly;
    private $isInvisible;
    private $appearsInLists;
    private $isASearchableProperty;
    private $isReadOnlyAfterFirstUpdate;
    private $isForeignKey;
    private $presentationParameters;
    private $valuesTable;
    private $columnToShow;
    private $columnToStore;
    private $defaultValue;
    private $orderingMirror;
    private $searchingMirror;
    private $currentUserCanEdit;
    private $currentUserCanRead;
    private $currentUserCanChangeProperties;
    private $thumbnailWidth;
    private $thumbnailHeight;
    private $resizeWidth;
    private $resizeHeight;
    private $thumbnailColumn;
    private $midSizeWidth;
    private $midSizeHeight;
    private $midSizeColumn;
    private $ordering;
    private $adminCSS;
    
    public function __construct($dataBaseObject) 
    {
        $this->dataBase = $dataBaseObject;
        $this->currentUserCanEdit=FALSE;
        $this->currentUserCanRead=FALSE;
        $this->currentUserCanChangeProperties=FALSE;
    }
    
    public static function fromMetaRow($dataBaseObject,$theMetaRow)
    {
        $newDbColumn = new WOOOF_dataBaseColumn($dataBaseObject);
        $newDbColumn->tableId = $theMetaRow['tableId'];
        $newDbColumn->columnId = $theMetaRow['id'];
        $newDbColumn->name = $theMetaRow['name'];
        $newDbColumn->description = $theMetaRow['description'];
        $newDbColumn->type = $theMetaRow['type'];
        $newDbColumn->presentationType = $theMetaRow['presentationType'];
        $newDbColumn->length = $theMetaRow['length'];
        $newDbColumn->isReadOnly = $theMetaRow['isReadOnly'];
        $newDbColumn->isInvisible = $theMetaRow['isInvisible'];
        $newDbColumn->appearsInLists = $theMetaRow['appearsInLists'];
        $newDbColumn->isASearchableProperty = $theMetaRow['isASearchableProperty'];
        $newDbColumn->isReadOnlyAfterFirstUpdate = $theMetaRow['isReadOnlyAfterFirstUpdate'];
        $newDbColumn->isForeignKey = $theMetaRow['isForeignKey'];
        $newDbColumn->presentationParameters = $theMetaRow['presentationParameters'];
        $newDbColumn->valuesTable = $theMetaRow['valuesTable'];
        $newDbColumn->columnToShow = $theMetaRow['columnToShow'];
        $newDbColumn->columnToStore = $theMetaRow['columnToStore'];
        $newDbColumn->defaultValue = $theMetaRow['defaultValue'];
        $newDbColumn->orderingMirror = $theMetaRow['orderingMirror'];
        $newDbColumn->thumbnailWidth = $theMetaRow['thumbnailWidth'];
        $newDbColumn->thumbnailHeight = $theMetaRow['thumbnailHeight'];
        $newDbColumn->resizeWidth = $theMetaRow['resizeWidth'];
        $newDbColumn->resizeHeight = $theMetaRow['resizeHeight'];
        $newDbColumn->searchingMirror = $theMetaRow['searchingMirror'];
        $newDbColumn->thumbnailColumn = $theMetaRow['thumbnailColumn'];
        $newDbColumn->midSizeWidth = $theMetaRow['midSizeWidth'];
        $newDbColumn->midSizeHeight = $theMetaRow['midSizeHeight'];
        $newDbColumn->midSizeColumn = $theMetaRow['midSizeColumn'];
        $newDbColumn->ordering = $theMetaRow['ordering'];
        $newDbColumn->adminCSS = $theMetaRow['adminCSS'];
        
        $newDbColumn->updateSecurity();
        return $newDbColumn;
    }
    
    public function updateSecurity()
    {
        global $userData;
        
        $permitions = $this->dataBase->getSecurityPermitionsForLocationAndUser('1_'. $this->tableId .'_'. $this->columnId, $userData['id']);
        
        if (isset($permitions['edit']) && $permitions['edit'] === TRUE)
        {
            $this->currentUserCanEdit = TRUE;
        }else
        {
            $this->currentUserCanEdit = FALSE;
        }
        
        if (isset($permitions['read']) && $permitions['read'] === TRUE)
        {
            $this->currentUserCanRead = TRUE;
        }else
        {
            $this->currentUserCanRead = FALSE;
        }
        
        if (isset($permitions['modifyProperties']) && $permitions['modifyProperties'] === TRUE)
        {
            $this->currentUserCanChangeProperties = TRUE;
        }else
        {
            $this->currentUserCanChangeProperties = FALSE;
        }
    }
    
    public function getColumnMetaData()
    {
        $columnMetaData='';
        $columnMetaData['type'] = $this->type;
        $columnMetaData['length'] = $this->length;
        $columnMetaData['isReadOnly'] = $this->isReadOnly;
        $columnMetaData['isInvisible'] = $this->isInvisible;
        $columnMetaData['appearsInLists'] = $this->appearsInLists;
        $columnMetaData['isASearchableProperty'] = $this->isASearchableProperty;
        $columnMetaData['isReadOnlyAfterFirstUpdate'] = $this->isReadOnlyAfterFirstUpdate;
        $columnMetaData['isForeignKey'] = $this->isForeignKey;
        $columnMetaData['presentationParameters'] = $this->presentationParameters;
        $columnMetaData['valuesTable'] = $this->valuesTable;
        $columnMetaData['columnToShow'] = $this->columnToShow;
        $columnMetaData['columnToStore'] = $this->columnToStore;
        $columnMetaData['defaultValue'] = $this->defaultValue;
        $columnMetaData['orderingMirror'] = $this->orderingMirror;
        $columnMetaData['searchingMirror'] = $this->searchingMirror;
        $columnMetaData['tableId'] = $this->tableId;
        $columnMetaData['columnId'] = $this->columnId;
        $columnMetaData['name'] = $this->name;
        $columnMetaData['description'] = $this->description;
        $columnMetaData['presentationType'] = $this->presentationType;
        $columnMetaData['resizeWidth'] = $this->resizeWidth;
        $columnMetaData['resizeHeight'] = $this->resizeHeight;
        $columnMetaData['thumbnailWidth'] = $this->thumbnailWidth;
        $columnMetaData['thumbnailHeight'] = $this->thumbnailHeight;
        $columnMetaData['thumbnailColumn'] = $this->thumbnailColumn;
        $columnMetaData['midSizeWidth'] = $this->midSizeWidth;
        $columnMetaData['midSizeHeight'] = $this->midSizeHeight;
        $columnMetaData['midSizeColumn'] = $this->midSizeColumn;
        $columnMetaData['ordering'] = $this->ordering;
        $columnMetaData['adminCSS'] = $this->adminCSS;

        return $columnMetaData;
    }

    public function getAppearsInLists()
    {
        return $this->appearsInLists;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function updateMetaDataFromPost()
    {
        if ($this->currentUserCanChangeProperties)
        {
            if (!isset($_POST['notNull']) || $_POST['notNull'] !='1')
            {
                $_POST['notNull']='0';
            }
            if (!isset($_POST['isReadOnly']) || $_POST['isReadOnly'] !='1')
            {
                $_POST['isReadOnly']='0';
            }
            if (!isset($_POST['isInvisible']) || $_POST['isInvisible'] !='1')
            {
                $_POST['isInvisible']='0';
            }
            if (!isset($_POST['isASearchableProperty']) || $_POST['isASearchableProperty'] !='1')
            {
                $_POST['isASearchableProperty']='0';
            }
            if (!isset($_POST['isReadOnlyAfterFirstUpdate']) || $_POST['isReadOnlyAfterFirstUpdate'] !='1')
            {
                $_POST['isReadOnlyAfterFirstUpdate']='0';
            }
            if (!isset($_POST['isForeignKey']) || $_POST['isForeignKey'] !='1')
            {
                $_POST['isForeignKey']='0';
            }
            if (!isset($_POST['appearsInLists']) || $_POST['appearsInLists'] !='1')
            {
                $_POST['appearsInLists']='0';
            }
            $query='update __columnMetaData set
name=\''. mysql_real_escape_string(trim($_POST['name'])) .'\',
description=\''. mysql_real_escape_string(trim($_POST['description'])) .'\',
type=\''. mysql_real_escape_string(trim($_POST['type'])) .'\',
length=\''. mysql_real_escape_string(trim($_POST['length'])) .'\',
presentationType=\''. mysql_real_escape_string(trim($_POST['presentationType'])) .'\',
isReadOnly=\''. mysql_real_escape_string(trim($_POST['isReadOnly'])) .'\',
notNull=\''. mysql_real_escape_string(trim($_POST['notNull'])) .'\',
isInvisible=\''. mysql_real_escape_string(trim($_POST['isInvisible'])) .'\',
appearsInLists=\''. mysql_real_escape_string(trim($_POST['appearsInLists'])) .'\',
isASearchableProperty=\''. mysql_real_escape_string(trim($_POST['isASearchableProperty'])) .'\',
isReadOnlyAfterFirstUpdate=\''. mysql_real_escape_string(trim($_POST['isReadOnlyAfterFirstUpdate'])) .'\',
isForeignKey=\''. mysql_real_escape_string(trim($_POST['isForeignKey'])) .'\',
presentationParameters=\''. mysql_real_escape_string(trim($_POST['presentationParameters'])) .'\',
valuesTable=\''. mysql_real_escape_string(trim($_POST['valuesTable'])) .'\',
columnToShow=\''. mysql_real_escape_string(trim($_POST['columnToShow'])) .'\',
columnToStore=\''. mysql_real_escape_string(trim($_POST['columnToStore'])) .'\',
defaultValue=\''. mysql_real_escape_string(trim($_POST['defaultValue'])) .'\',
orderingMirror=\''. mysql_real_escape_string(trim($_POST['orderingMirror'])) .'\',
searchingMirror=\''. mysql_real_escape_string(trim($_POST['searchingMirror'])) .'\',
resizeWidth=\''. mysql_real_escape_string(trim($_POST['resizeWidth'])) .'\',
resizeHeight=\''. mysql_real_escape_string(trim($_POST['resizeHeight'])) .'\',
thumbnailWidth=\''. mysql_real_escape_string(trim($_POST['thumbnailWidth'])) .'\',
thumbnailHeight=\''. mysql_real_escape_string(trim($_POST['thumbnailHeight'])) .'\',
midSizeColumn=\''. mysql_real_escape_string(trim($_POST['midSizeColumn'.$c])) .'\',
midSizeWidth=\''. mysql_real_escape_string(trim($_POST['midSizeWidth'.$c])) .'\',
midSizeHeight=\''. mysql_real_escape_string(trim($_POST['midSizeHeight'.$c])) .'\',
thumbnailColumn=\''. mysql_real_escape_string(trim($_POST['thumbnailColumn'])) .'\',
ordering=\''. mysql_real_escape_string(trim($_POST['ordering'])) .'\',
adminCSS=\''. mysql_real_escape_string(trim($_POST['adminCSS'])) .'\'
where id=\''. $this->columnId .'\'';
            
            $this->dataBase->query($query);
            
            $result = $this->dataBase->query('select tableName from __tableMetaData where id=\''. $this->tableId .'\'');
            $temp = mysql_fetch_row($result);
            $tableName = $temp[0];
            
            if ($_POST['isForeignKey'] == '1')
            {
                $foreignKeyExists = FALSE;
                $result = $this->dataBase->query('SHOW INDEX FROM '. $tableName);
                while($row = mysql_fetch_assoc($result))
                {
                    if ($row['Key_name'] == 'FK_'. $tableName .'_'. $this->name)
                    {
                        $foreignKeyExists = TRUE;
                    }
                }
                if ($foreignKeyExists)
                {
                    $this->dataBase->query('DROP FOREIGN KEY FK_'. $tableName .'_'. $this->name);
                }
                $this->dataBase->query('ALTER TABLE '. $tableName .' ADD FOREIGN KEY FK_'. $tableName .'_'. mysql_real_escape_string(trim($_POST['name'])).
                ' REFERENCES '. mysql_real_escape_string(trim($_POST['valuesTable'])) .' ('. mysql_real_escape_string(trim($_POST['columnToStore'])) .')
    ON DELETE RESTRICT
    ON UPDATE CASCADE');
            }
            $query='ALTER TABLE '. $tableName .' CHANGE COLUMN '. $this->name .' '. mysql_real_escape_string(trim($_POST['name'])) .' '. WOOOF_dataBaseColumnTypes::getColumnTypeLiteral(mysql_real_escape_string(trim($_POST['type'])));
            if (mysql_real_escape_string(trim($_POST['length']))!='')
            {
                $query.='('.mysql_real_escape_string(trim($_POST['length'])).')';
            }
            if (mysql_real_escape_string(trim($_POST['notNull'])) == '1')
            {
                $query .= ' NOT NULL ';
            }
            if (mysql_real_escape_string(trim($_POST['defaultValue'])))
            {
                $query .= ' DEFAULT \''. mysql_real_escape_string(trim($_POST['defaultValue'])) .'\'';
            }
            $this->dataBase->query($query);
        }else
        {
            echo 'FAILED !!! You don\'t have the required rights!';
            exit;
        }
    }

    public function renderReadOnly($value, $rowId)
    {
        global $imagesRelativePath;
        global $siteBaseURL;
        global $__isAdminPage;
        
        if ( $this->isInvisible)
        {
            return '';
        }
        switch ($this->presentationType) {
            case WOOOF_columnPresentationTypes::checkBox :
                if ($value=='1')
                {
                    $product = '<span class="normalTextGreen">YES</span>';
                }else
                {
                    $product = '<span class="normalTextRed">NO</span>';
                }
                break;
            case WOOOF_columnPresentationTypes::date :
                $product = WOOOF::decodeDate($value);
                break;
            case WOOOF_columnPresentationTypes::time :
                $product = WOOOF::decodeTime($value);
                break;
            case WOOOF_columnPresentationTypes::dateAndTime :
                $product = WOOOF::decodeDateTime($value,'/',TRUE);
                break;
            case WOOOF_columnPresentationTypes::autoComplete :
            case WOOOF_columnPresentationTypes::dropList :    
            case WOOOF_columnPresentationTypes::radioHoriz :    
            case WOOOF_columnPresentationTypes::radioVert :    
                $result=$this->dataBase->query('select * from '. $this->valuesTable .' where '. $this->columnToStore .'=\''. $value .'\'');
                $row = mysql_fetch_assoc($result);
                $product = $row[$this->columnToShow];
                break;
            case WOOOF_columnPresentationTypes::textBox :
                $product = $value;
                break;
            case WOOOF_columnPresentationTypes::htmlText :
                $product = $value;
                if ($__isAdminPage)
                {
                    $product = strip_tags($product);
                    if ($product=='') $product = ' &nbsp; ';
                }
                break;
            case WOOOF_columnPresentationTypes::textArea :
            default:
                $value = nl2br($value);
                if (strlen($value)>6000)
                {
                    $product = substr($value, 0, 60) .'...';
                }else
                {
                    $product = $value;
                }
                break;
            case WOOOF_columnPresentationTypes::file :
                $fR = $this->dataBase->query('select * from __externalFiles where id=\''. $value .'\'');
                $f=mysql_fetch_assoc($fR);
                if (isset($f['id']))
                {
                    $product = '<a href="getFile.php?location='. $this->tableId .'_'. $this->columnId .'_'. $rowId .'" target="_blank">'. $f['originalFileName'] .'</a>';
                }else
                {
                    if ($this->presentationParameters=='')
                    {
                        $this->presentationParameters = 'images/';
                    }
                    $product= '<a href="'. $this->presentationParameters . $value .'" target="_blank">'. $value .'</a>';
                }
                break;
            case WOOOF_columnPresentationTypes::picture :

                if (trim($this->presentationParameters)=='')
                {
                    $prefix=$imagesRelativePath;
                }else
                {
                    $prefix=trim($this->presentationParameters);
                }
                if (!$__isAdminPage) $product = $prefix . $value ;
                else
                {
                    if ($value!='')
                    {
                        $product = '<a href="'. $siteBaseURL . $prefix . $value .'">'. $prefix . $value .'</a>';
                    }else 
                    {
                        $product = '<a href="javascript:void(null);">Δεν υπάρχει εικόνα.</a>';
                    }
                }
                break;
        }
        if ($product=='' && $__isAdminPage)
        {
            $product = '<img src="../images/spacer.gif">';
        }
        return $product;
    }
    
    public function renderForInsert($className='')
    {
        global $cssForFormItem;
        global $__isAdminPage;

        if ($this->isInvisible || $this->isReadOnly)
        {
            return '';
        }
        if ($className=='' && $this->presentationType!=4)
        {
            $className = $cssForFormItem[WOOOF_columnPresentationTypes::getColumnPresentationLiteral($this->presentationType)];
        }
        
        switch ($this->presentationType) 
        {
            case WOOOF_columnPresentationTypes::checkBox :
                if ($this->defaultValue=='1')
                {
                    $product = '<input type="checkbox" name="'. $this->name .'" value="1" checked class="'. $className .'">';
                }else
                {
                    $product = '<input type="checkbox" name="'. $this->name .'" value="1" class="'. $className .'">';
                }
                break;
            case WOOOF_columnPresentationTypes::date :
                $date = $this->prepareDefaultDateTime();
                $product = '<input type="text" size="4" name="'. $this->name .'1" value="'. $date['day'] .'" class="'. $className .'"> / <input type="text" size="4" name="'. $this->name .'2" value="'. $date['month'] .'" class="'. $className .'"> / <input type="text" size="8" name="'. $this->name .'3" value="'. $date['year'] .'" class="'. $className .'">';
                break;
            case WOOOF_columnPresentationTypes::time :
                $date = $this->prepareDefaultDateTime();
                $product = '<input type="text" size="4" name="'. $this->name .'1" value="'. $date['hour'] .'" class="'. $className .'">:<input type="text" size="4" name="'. $this->name .'2" value="'. $date['minute'] .'" class="'. $className .'">:<input type="text" size="4" name="'. $this->name .'3" value="'. $date['second'] .'" class="'. $className .'">';
                break;
            case WOOOF_columnPresentationTypes::dateAndTime :
                $date = $this->prepareDefaultDateTime();
                $product = '<input type="text" size="4" name="'. $this->name .'1" value="'. $date['day'] .'" class="'. $className .'">/<input type="text" size="4" name="'. $this->name .'2" value="'. $date['month'] .'" class="'. $className .'">/<input type="text" size="8" name="'. $this->name .'3" value="'. $date['year'] .'" class="'. $className .'"> <input type="text" size="4" name="'. $this->name .'4" value="'. $date['hour'] .'" class="'. $className .'">:<input type="text" size="4" name="'. $this->name .'5" value="'. $date['minute'] .'" class="'. $className .'">:<input type="text" size="4" name="'. $this->name .'6" value="'. $date['second'] .'" class="'. $className .'">';
                break;
            case WOOOF_columnPresentationTypes::autoComplete :
                $product = '<input type="hidden" name="'. $this->name .'_hidden" id="'. $this->name .'_hidden" value=""><input type="text" name="'. $this->name .'" id="'. $this->name .'" value="" '. $this->presentationParameters .' autocomplete="off" onKeyUp="ajaxShowOptions(this,\'g@e@t^'. $this->tableId .'^'. $this->name .'\',event)">';
                break;
            case WOOOF_columnPresentationTypes::dropList :    
                if ($__isAdminPage && isset($_GET['wooofParent']) && $_GET['wooofParent']!='')
                {
                    $value = WOOOF::cleanUserInput($_GET['wooofParent']);
                }else
                {
                    $value='';
                }
                $product=$this->dataBase->getDropListSelected($this->valuesTable, $this->name, '' , $className, $this->columnToStore, $this->columnToShow, $this->columnToStore, $value);
                break;
            case WOOOF_columnPresentationTypes::radioHoriz : 
                $product=$this->dataBase->getRadio($this->valuesTable, $this->name, TRUE, '', $className, $this->columnToStore, $this->columnToShow);
                break;
            case WOOOF_columnPresentationTypes::radioVert:    
                $product=$this->dataBase->getRadio($this->valuesTable, $this->name, FALSE,'', $className, $this->columnToStore, $this->columnToShow);
                break;
            case WOOOF_columnPresentationTypes::textBox :
                $product = '<input type="text" name="'. $this->name .'" value="'. $this->defaultValue .'" '. $this->presentationParameters .' class="'. $className .'">';
                break;
            case WOOOF_columnPresentationTypes::htmlText :
                $product='^@^ html tags Place Holder ^@^';
            case WOOOF_columnPresentationTypes::textArea :
                if ($this->presentationParameters=='')
                {
                    $parameters='cols="50" rows="10"';
                }else
                {
                    $parameters=$this->presentationParameters;
                }
                $product = '<textarea name="'. $this->name .'" id="'. $this->name .'" '. $parameters .' class="'. $className .'">'. $this->defaultValue .'</textarea>';
                break;
            case WOOOF_columnPresentationTypes::file :
            case WOOOF_columnPresentationTypes::picture :
                $product = '<input type="file" name="'. $this->name .'" id="'. $this->name .'" class="'. $className .'">';
                break;
        }
        return $product;
    }
    
    private function prepareDefaultDateTime()
    {
        if ($this->defaultValue=='@@@empty@@@')
        {
            $date['year']='';
            $date['month']='';
            $date['day']='';
            $date['hour']='';
            $date['minute']='';
            $date['second']=''; 
        }else if ($this->defaultValue=='0' || $this->defaultValue=='00000000000000')
        {
            $date = WOOOF::breakDateTime($this->defaultValue);
        }else
        {
            $this->defaultValue = WOOOF::getCurrentDateTime();
            $date = WOOOF::breakDateTime($this->defaultValue);                   
        }
        return $date;       
    }
    
    public function renderForEdit($value,$rowId,$className='')
    {
        global $cssForFormItem;
        global $imagesRelativePath;
        global $siteBaseURL;
        global $__isAdminPage;
        global $wooofParent;
        
        /* FOR AUTOCOMPLETE, droplist etc maybe ...
         *      // this theoretically is wrong as the comma could be in the first
                // (0) position. In that case the result would be a valid 0 (NOT
                // null, zero). But even in this case the first part of an explode()
                // would be an empty string, not really usefull in columns' SELECTion,
                // therefore valid zeroes should be discarded as well.
                
                $multiColumnFetch = FALSE;
                
                if (stripos($this->columnToShow, ',')) 
                {
                    $multiColumnFetch = TRUE;
                    $columnsToShow = explode(',', $this->columnToShow);
                }
         */        

        if ($className=='')
        {
            $className = $cssForFormItem[WOOOF_columnPresentationTypes::getColumnPresentationLiteral($this->presentationType)];
        }

        if ($this->isInvisible)
        {
            return '';
        }

        if ($this->isReadOnly)
        {
            return $this->renderReadOnly($value,$rowId) . ' <input type="hidden" name="'. $this->name .'" value="'. $value .'">';
        }

        switch ($this->presentationType) {
            case WOOOF_columnPresentationTypes::checkBox :
                if ($value=='1')
                {
                    $product = '<input type="checkbox" name="'. $this->name .'" value="1" checked class="'. $className .'">';
                }else
                {
                    $product = '<input type="checkbox" name="'. $this->name .'" value="1" class="'. $className .'">';
                }
                break;
            case WOOOF_columnPresentationTypes::date :
                $date = WOOOF::breakDateTime($value);
                $product = '<input type="text" size="4" name="'. $this->name .'1" value="'. $date['day'] .'" class="'. $className .'"> / <input type="text" size="4" name="'. $this->name .'2" value="'. $date['month'] .'" class="'. $className .'"> / <input type="text" size="8" name="'. $this->name .'3" value="'. $date['year'] .'" class="'. $className .'">';
                break;
            case WOOOF_columnPresentationTypes::time :
                $date = WOOOF::breakDateTime($value);
                $product = '<input type="text" size="4" name="'. $this->name .'1" value="'. $date['hour'] .'" class="'. $className .'">:<input type="text" size="4" name="'. $this->name .'2" value="'. $date['minute'] .'" class="'. $className .'">:<input type="text" size="4" name="'. $this->name .'3" value="'. $date['second'] .'" class="'. $className .'">';
                break;
            case WOOOF_columnPresentationTypes::dateAndTime :
                $date = WOOOF::breakDateTime($value);
                $product = '<input type="text" size="4" name="'. $this->name .'1" value="'. $date['day'] .'" class="'. $className .'">/<input type="text" size="4" name="'. $this->name .'2" value="'. $date['month'] .'" class="'. $className .'">/<input type="text" size="8" name="'. $this->name .'3" value="'. $date['year'] .'" class="'. $className .'"> <input type="text" size="4" name="'. $this->name .'4" value="'. $date['hour'] .'" class="'. $className .'">:<input type="text" size="4" name="'. $this->name .'5" value="'. $date['minute'] .'" class="'. $className .'">:<input type="text" size="4" name="'. $this->name .'6" value="'. $date['second'] .'" class="'. $className .'">';
                break;
            case WOOOF_columnPresentationTypes::autoComplete :
                $result=$this->dataBase->query('select * from '. $this->valuesTable .' where '. $this->columnToShow .'=\''. WOOOF::cleanUserInput($value) .'\'');
                $row = mysql_fetch_assoc($result);
                $aliasValue = $row[$this->columnToShow];
                $product = '<input type="hidden" name="'. $this->name .'_hidden" id="'. $this->name .'_hidden" value="'. $value .'"><input type="text" name="'. $this->name .'" id="'. $this->name .'" value="'. $aliasValue .'" '. $this->presentationParameters .' autocomplete="off" onKeyUp="ajaxShowOptions(this,\'g@e@t^'. $this->tableId .'^'. $this->name .'\',event)" class="'. $className .'">';
                break;
            case WOOOF_columnPresentationTypes::dropList :
                $tableMetaData = $this->dataBase->getRowByColumn('__tableMetaData', 'tableName', $this->valuesTable);
                if (trim($tableMetaData['orderingColumnForListings'])!='')
                {
                    $orderBy=' order by '.$tableMetaData['orderingColumnForListings'];
                }else 
                {
                    $orderBy='';
                }
                $product=$this->dataBase->getDropListSelected($this->valuesTable, $this->name, $orderBy , $className, $this->columnToStore, $this->columnToShow, $this->columnToStore, $value);
                break;
            case WOOOF_columnPresentationTypes::radioHoriz : 
                $product=$this->dataBase->getRadio($this->valuesTable, $this->name, TRUE, '', $className, $this->columnToStore, $this->columnToShow, $this->columnToStore, $value);
                break;
            case WOOOF_columnPresentationTypes::radioVert:    
                $product=$this->dataBase->getRadio($this->valuesTable, $this->name, FALSE,'', $className, $this->columnToStore, $this->columnToShow, $this->columnToStore, $value);
                break;
            case WOOOF_columnPresentationTypes::textBox :
                $product = '<input type="text" name="'. $this->name .'" value="'. $value .'" '. $this->presentationParameters .' class="'. $className .'">';
                break;
            case WOOOF_columnPresentationTypes::htmlText :
                $product='<div name="'. $this->name .'" id="'. $this->name .'"  class="'. $className .'">'. $value .'</div>';
                break;
            case WOOOF_columnPresentationTypes::textArea :
                if ($this->presentationParameters=='')
                {
                    $parameters='cols="50" rows="15"';
                }else
                {
                    $parameters=$this->presentationParameters;
                }
                $product = '<textarea name="'. $this->name .'" id="'. $this->name .'" '. $parameters .' class="'. $className .'">'. $value .'</textarea>';
                break;
            case WOOOF_columnPresentationTypes::file :
                $fR = $this->dataBase->query('select * from __externalFiles where id=\''. $value .'\'');
                $f=mysql_fetch_assoc($fR);
                if (isset($f['id']))
                {
                    $product = '<a href="getFile.php?location='. $this->tableId .'_'. $this->columnId .'_'. $rowId .'" class="'. $className .'" target="_blank">'. $f['originalFileName'] .'</a> <a href="deleteFile.php?location='. $this->tableId .'_'. $this->columnId .'_'. $rowId .'">Delete file.</a><br/><input type="file" name="'. $this->name .'" id="'. $this->name .'" class="'. $className .'">';
                }
                break;
            case WOOOF_columnPresentationTypes::picture :
            //echo 'INSIDE!<br/>';
                $product = '<a href="'. $siteBaseURL . $imagesRelativePath . $value .'" class="'. $className .'">'. $value .'</a><br/>
<span class="fileupload-new" style="font-size:14px;">Select new file</span><input type="file" name="'. $this->name .'" id="'. $this->name .'" class="'. $className .'"/>
';
            //<a href="administration.php?action=emptyPicture&" style="font-size:14px;">Remove</a> echo $product;
                break;
        }

        return $product;

    }
    
    public function drop()
    {
        if ($this->currentUserCanEdit)
        {
            $table = $this->dataBase->getRow('__tableMetaData',  mysql_real_escape_string(trim($this->tableId)));
            
            $this->dataBase->query('alter table '. $table['tableName'] .' drop column '. $this->name);
            $this->dataBase->query('delete from __columnMetaData where id=\''. $this->columnId .'\'');
        }
    }
    
    public function checkValue()
    {
        // TODO: Actual checks should happen here. For the moment anything goes ...
        return TRUE;
    }
}

?>
