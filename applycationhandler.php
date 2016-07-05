<?php
require_once 'setup.inc.php';

$requestedAction='signIn';
$pageLocation='3_signIn';

$wo = new WOOOF();

function clean_string($string) {

  $bad = array("content-type","bcc:","to:","cc:","href");

  return str_replace($bad,"",$string);

}

if (!$wo->isValidEmail($_POST['email']))
{
    die('Not A Valid Email.');
}

$_POST['password'] = $wo->randomString(10);
$userName = $wo->cleanUserInput($_POST['email']);
$newUserID = $wo->db->getNewId('__users');

$salt='$2y$08$'. $newUserID . strrev($newUserID) . $newUserID;
$cryptResult=crypt(WOOOF::cleanUserInput($_POST['password']), $salt);
$thePassword = substr($cryptResult, 28);

$_POST['user'] = $newUserID;

$columnToFill[] = 'firstname';
$columnToFill[] = 'surname';
$columnToFill[] = 'email';
$columnToFill[] = 'parentsemail';
$columnToFill[] = 'sex';
$columnToFill[] = 'age';

$columnToFill[] = 'stdevice1';
$columnToFill[] = 'stdevice2';
$columnToFill[] = 'stdevice3';
$columnToFill[] = 'stdevice4';

if($_REQUEST['userType']=='1a')
{
    $columnToFill[] = 'school_level';
    $columnToFill[] = 'parentfirstname';
    $columnToFill[] = 'parentsurname';
    $columnToFill[] = 'parent';
    $columnToFill[] = 'numchild';
    $columnToFill[] = 'Preferred_payment_method';
    $columnToFill[] = 'Preferred_payment_tip';
    $columnToFill[] = 'paydevice1';
    $columnToFill[] = 'paydevice2';
    $columnToFill[] = 'paydevice3';
    $columnToFill[] = 'paydevice4';
    $columnToFill[] = 'subscriptionType';
    $columnToFill[] = 'serviceLevel'; 
}else
{
    $columnToFill[] = 'tut_subject'; // required  
    $columnToFill[] = 'tut_level'; // required  
    $columnToFill[] = 'tut_edu_level'; // required 
}


$columnToFill[] = 'user';
$columnToFill[] = 'userType';

$alreadyIn = $wo->db->getRowByColumn('userData', 'email', $userName);
if (isset($alreadyIn['id']))
{
    echo 'Failed! user already registered!';
}

if ($_REQUEST['subscriptionType']!='a1' && $_REQUEST['subscriptionType']!='a2' && $_REQUEST['subscriptionType']!='a3' && $_REQUEST['subscriptionType']!='HmQBY1XSd1')
{
    echo 'Failed! wrong subscription!';
    exit;
}

if ($_REQUEST['serviceLevel']!='a1' && $_REQUEST['serviceLevel']!='a2' && $_REQUEST['serviceLevel']!='a3' && $_REQUEST['serviceLevel']!='a4' && $_REQUEST['subscriptionType']!='HmQBY1XSd1')
{
    echo 'Failed! wrong  service level !';
    exit;
}


$wo->db->query('insert into __users set loginName=\''.$userName .'\', loginPass=\''. $thePassword .'\', id=\''. $newUserID .'\'');
$t = new WOOOF_dataBaseTable($wo->db, 'userData');

$t->handleInsertFromPost($columnToFill);

$wo->db->query('insert into __userRoleRelation set userId=\''. $newUserID .'\', roleId=\'qweasdzxcl\'');
$wo->db->query('insert into __userRoleRelation set userId=\''. $newUserID .'\', roleId=\'9999999999\'');

$email_to = "info@anastasispavlakis.com";
 
$email_subject = "Thank you for registration to Myetutor.org";
    
$email_subject_to_us = "New Registration to Myetutor.org";


    // create email headers
     
    $headers = 'From: '.$email_to."\r\n".
     
    'Reply-To: '.$email_to."\r\n" .
     
    'X-Mailer: PHP/' . phpversion();
     
    $headers_to_us = 'From: '. clean_string($_POST['email']) ."\r\n".
     
    'Reply-To: '. clean_string($_POST['email']) ."\r\n" .
     
    'X-Mailer: PHP/' . phpversion();


    $email_message = "Dear ".clean_string($_POST['first_name'])."\n\n";

    $email_message .= "Thank you for completing your registration with MyeTutor.org. You will soon be contacted to participate in our beta launch of our eLearning platform. \n\n";

    $email_message .= "Your account details \n\n";

    $email_message .= "First Name : ".clean_string($_POST['first_name'])."\n";
 
    $email_message .= "Last Name : ".clean_string($_POST['last_name'])."\n";
 
    $email_message .= "Email : ". clean_string($_POST['email'])."\nYour password is: ". $_REQUEST['password'] ;


    mail(clean_string($_POST['email']), $email_subject, $email_message, $headers);


    $email_message_to_us = "Account details \n\n";

    $email_message_to_us .= "First Name: ".clean_string($_POST['firstname'])."\n";
 
    $email_message_to_us .= "Last Name: ".clean_string($_POST['lastname'])."\n";
 
    $email_message_to_us .= "Email: ".clean_string($_POST['email'])."\n";

    $email_message_to_us .= "Parents's email: ".clean_string($_POST['parentsemail'])."\n";

    $email_message_to_us .= "Parent's name: ".clean_string($_POST['parentfirstname'])."\n";

    $email_message_to_us .= "Parents's surname: ".clean_string($_POST['parentsurname'])."\n";


    mail($email_to, $email_subject_to_us, $email_message_to_us, $headers_to_us); 


/*

     
    $first_name = $_POST['firstname']; // required
 
    $last_name = $_POST['surname']; // required
 
    $email_from = $_POST['email']; // required
 
    $parentsemail = $_POST['parentsemail']; // required

    $sex = $_POST['sex']; // required  

    $childsage = $_POST['age']; // required  

    $school_level = $_POST['school_level']; // required  

    $child_device1  = $_POST['stdevice1'];

    $child_device2  = $_POST['stdevice2'];

    $child_device3  = $_POST['stdevice3'];

    $child_device4  = $_POST['stdevice4'];

    $parentfirstname = $_POST['parentfirstname']; // required

    $parentsurname = $_POST['parentsurname']; // required

    $parent = $_POST['parent']; // required

    //$parentsemail = $_POST['parentsemail']; // required

    $numchild = $_POST['numchild']; // required

    $payment_method = $_POST['Preferred_payment_method']; // required

    $payment_tip = $_POST['Preferred_payment_tip']; // required

    $paydevice1 = $_POST['paydevice1']; // required

    $paydevice2 = $_POST['paydevice2']; // required

    $paydevice3 = $_POST['paydevice3']; // required

    $paydevice4 = $_POST['paydevice4']; // required

    $type = $_POST['type']; // required

    /*$tutor_cat = "";

    if (isset($_POST['tutor_cat'])){
      $tutor_cat = $_POST['tutor_cat'];
    } 
    */
    //$password = $_POST['password']; // not required 

    //$repassword = $_POST['repassword']; // required

    // $birthdate = $_POST['birthdate']; // required 
/*
    function clean_string($string) {
 
      $bad = array("content-type","bcc:","to:","cc:","href");
 
      return str_replace($bad,"",$string);
 
    }
 
     


    //$email_message .= "Sex : ".clean_string($sex)."\n";
 
    //$email_message .= "Type: ".clean_string($type)."\n";
 



    $email_message_to_us = "Account details \n\n";

    $email_message_to_us .= "First Name: ".clean_string($first_name)."\n";
 
    $email_message_to_us .= "Last Name: ".clean_string($last_name)."\n";
 
    $email_message_to_us .= "Email: ".clean_string($email_from)."\n";

    $email_message_to_us .= "Parents's email: ".clean_string($parentsemail)."\n";

    $email_message_to_us .= "Sex : ".clean_string($sex)."\n";
 
    $email_message_to_us .= "Type: ".clean_string($type)."\n";

    $email_message_to_us .= "Child's age: ".clean_string($childsage)."\n";

    $email_message_to_us .= "School level: ".clean_string($school_level)."\n";

    $email_message_to_us .= "Child's devices: ".clean_string($child_device1)."-".clean_string($child_device2)."-".clean_string($child_device3)."-".clean_string($child_device4)."\n";

    $email_message_to_us .= "Parent's name: ".clean_string($parentfirstname)."\n";

    $email_message_to_us .= "Parents's surname: ".clean_string($parentsurname)."\n";

    $email_message_to_us .= "Parent: ".clean_string($parent)."\n";

    $email_message_to_us .= "# of children: ".clean_string($numchild)."\n";
 
    $email_message_to_us .= "Payment method : ".clean_string($payment_method)."\n";

    $email_message_to_us .= "Payment tip : ".clean_string($payment_tip)."\n";

    $email_message_to_us .= "Payment Devices : ".clean_string($paydevice1)."-".clean_string($paydevice2)."-".clean_string($paydevice3)."-".clean_string($paydevice4)."\n";

    //$email_message_to_us .= "Tutor Category : ".clean_string($tutor_cat)."\n";

    
     
 
    // create email headers
     
    $headers = 'From: '.$email_to."\r\n".
     
    'Reply-To: '.$email_to."\r\n" .
     
    'X-Mailer: PHP/' . phpversion();
     
    $headers_to_us = 'From: '.$email_from."\r\n".
     
    'Reply-To: '.$email_from."\r\n" .
     
    'X-Mailer: PHP/' . phpversion();


    @mail($email_from, $email_subject, $email_message, $headers);

    @mail($email_to, $email_subject_to_us, $email_message_to_us, $headers_to_us); 
    

 
 
 
<!--  html  -->*/
?><html>
    <head>
        <style type="text/css">

            #reg_thanks {
                text-align: center;
            }

            img { width:600px;}

            p {width:800px; margin:20px auto;}
            
        </style>
    </head>
<body>
    <div id="reg_thanks">
  <img src="img/home-family.png">
  <div class="seperator"></div>
  <p>Thank you for completing your registration with <b>MyeTutor.org</b>. You will soon be contacted to participate in our <b>beta launch</b> of our eLearning platform.</p>
  <p><?php //echo $email_message_to_us; ?></p>
</div>

</body>
</html>