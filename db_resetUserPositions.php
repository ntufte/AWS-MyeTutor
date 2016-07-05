<?php
$__isAdminPage = true;

require_once 'setup.inc.php';

$requestedAction = 'viewUncontroled';
$pageLocation = '3_webService';

$wo = new WOOOF();

$wo->db->query('truncate mvp_userLessonPosition');