<?php
// Setup : all setup and user made changes should be made here!

$siteName                   =   'MyeTutor MVP';         // the Site's Name
$defaultDBIndex             =   0;                  // the default database's index in the arrays
$databaseName[]             =   'myetutorAlphaDB';       // These eight variables are arrays.
$databaseUser[]             =   'myETutorDbUser';       // For each set a new database connection
$databasePass[]             =   'zjdy4@8sj2iFFla';  // is created when WOOOF is initialized
$databaseHost[]             =   'localhost';        // unless database name is ''. Duplicate as you see fit.
$databaseLog[]              =   false;               // Multiple db connections are a drag but could be used 
$fileLog[]                  =   false;              // for better security (ie logs and metaData in a different database)
$logTable[]                 =   '__dbLog';          // name of the Logging table 
$logFilePath[]              =   '/var/www/logs/'; // absolute path for file logging (filename is preset)

$sessionExpirationPeriod    =   '6 months';         // well i think you understand that one :-)

$aggressiveSecurity         =   TRUE;               // if aggressive security is TRUE any conflicting action security for a location within different roles results in action denial
                                                    // if FALSE the result is action allowance

$antiFloodProtection        =   7;               // if > 0 the number entered here is the number of requests per second that triger IP Address banning and silent ignoring of subsequent requests
                                                 // this obviously affects only pages that initialize WOOOF and doesn't count anything else.

$storeUserPaths             =   TRUE;             // If TRUE all page visits are stored to the DB along with the session ID, the timestamp, the requested action and the request parameters.

$siteBaseURL                    =   '/'; // the site's base URL
$siteBasePath                   =   '/var/www/html/'; // the site's absolute parth
$absoluteFilesRepositoryPath    =   '/var/www/uploads/'; // the path to store file uploads
$imagesRelativePath             =   'images/';
$administrationMainFileName     =   'administration.php';
$administrationDirectory        =   'administration/';

$minimumPasswordLength          =   8;
$minimumCapitalsInPassword      =   1;
$minimumNumbersInPassword       =   1;
$minimumSymbolsInPassword       =   0;

$cssForFormItem['textBox']      = ''; // enter default CSS styles here
$cssForFormItem['checkBox']     = '';
$cssForFormItem['dropList']     = '';
$cssForFormItem['textArea']     = '';
$cssForFormItem['htmlText']     = '';
$cssForFormItem['radioButton']  = '';
$cssForFormItem['file']         = '';
$cssForFormItem['picture']      = '';
$cssForFormItem['date']         = '';
$cssForFormItem['dateAndTime']  = '';
$cssForFormItem['radioHoriz']   = '';
$cssForFormItem['radioVert']    = '';

$cssFileNameForTinyMCE          = '../css/standard.css';

$__isAdminPage = false;
$__isSiteBuilderPage = false;
$extraScripts = '';
// This is used to prevent clickJacking.

if (basename($_SERVER["PHP_SELF"])!='handlePictureUpload.php' && basename($_SERVER["PHP_SELF"])!='doTheRouting.php' && basename($_SERVER["PHP_SELF"])!='fetchLessonPiece.php')
{
	header('X-Frame-Options: DENY');
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Headers: content-type');
}

// Site specific constants, functions and ultility Declarations

require('wooof.php');

if (isset($_COOKIE['lang']) && $_COOKIE['lang']=='en')
{
	$lang='_en';
}else
{
	$lang='';
}

?>