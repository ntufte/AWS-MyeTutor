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
    $line = trim($line);
    $columns = explode("\t", $line);
    if ($columns[0]!='D151')
    {
        continue;
    }
    $columns[3] = $wo->getCurrentDateTime();
    
    if (stripos($columns[43], '@@@v@@@'))
    {
        echo 'inside the video loop
';
        $tmpPieces = explode('@@@v@@@', $columns[43]);
        print_r($tmpPieces); echo '
';
        $columns[43] = $tmpPieces[0];
        for($z=1;$z<count($tmpPieces);$z++)
        {
            echo 'inside analysis
';
            $tmpPieces2 = explode('@@@', $tmpPieces[$z]);
            print_r($tmpPieces2);
            $columns[43] .= ' <video width="640" height="480" controls><source src="videos/'. $tmpPieces2[0] .'" type="video/mp4">Your browser does not support the video tag.</video>';
        $columns[43].=$tmpPieces2[1];
        }
    }
    echo $columns[0].'
';
    //print_r($columns);
    //$wo->db->query('insert into mvp_data
    echo ' insert into mvp_data       (id, 
            creatorId, developerId, creationDate, bookTitle, bookEdition, bookAuthor, bookPublisher, bookChapter, bookSection, bookPage, bookLanguage, bookSubsection, nameDataBook, immediatelyConnected, geoRelatedBook, geoData, geoSchoolGrade, microDescription, linguisticRating, visualRating, mathematicalRating, intrapersonalRating, interpersonalRating, kinestheticRating, spiritualRating, naturalisticRating, difficultyLevel, categoryLevel, priorityLevel, content, review, theory, report, test, liveBoard, discussionBoards, answerType, subquestionsNr, level, initial, priority, identifier, actualData, dataType, ord, relevantTheory, relevantExample 
            ) values (
            \''. $wo->cleanUserInput($columns[0]) .'\',
            \''. $wo->cleanUserInput($columns[1]) .'\',
            \''. $wo->cleanUserInput($columns[2]) .'\',
            \''. $wo->cleanUserInput($columns[3]) .'\',
            \''. $wo->cleanUserInput($columns[4]) .'\',
            \''. $wo->cleanUserInput($columns[5]) .'\',
            \''. $wo->cleanUserInput($columns[6]) .'\',
            \''. $wo->cleanUserInput($columns[7]) .'\',
            \''. $wo->cleanUserInput($columns[8]) .'\',
            \''. $wo->cleanUserInput($columns[9]) .'\',
            \''. $wo->cleanUserInput($columns[10]) .'\',
            \''. $wo->cleanUserInput($columns[11]) .'\',
            \''. $wo->cleanUserInput($columns[12]) .'\',
            \''. $wo->cleanUserInput($columns[13]) .'\',
            \''. $wo->cleanUserInput($columns[14]) .'\',
            \''. $wo->cleanUserInput($columns[15]) .'\',
            \''. $wo->cleanUserInput($columns[16]) .'\',
            \''. $wo->cleanUserInput($columns[17]) .'\',
            \''. $wo->cleanUserInput($columns[18]) .'\',
            \''. $wo->cleanUserInput($columns[19]) .'\',
            \''. $wo->cleanUserInput($columns[20]) .'\',
            \''. $wo->cleanUserInput($columns[21]) .'\',
            \''. $wo->cleanUserInput($columns[22]) .'\',
            \''. $wo->cleanUserInput($columns[23]) .'\',
            \''. $wo->cleanUserInput($columns[24]) .'\',
            \''. $wo->cleanUserInput($columns[25]) .'\',
            \''. $wo->cleanUserInput($columns[26]) .'\',
            \''. $wo->cleanUserInput($columns[27]) .'\',
            \''. $wo->cleanUserInput($columns[28]) .'\',
            \''. $wo->cleanUserInput($columns[29]) .'\',
            \''. $wo->cleanUserInput($columns[30]) .'\',
            \''. $wo->cleanUserInput($columns[31]) .'\',
            \''. $wo->cleanUserInput($columns[32]) .'\',
            \''. $wo->cleanUserInput($columns[33]) .'\',
            \''. $wo->cleanUserInput($columns[34]) .'\',
            \''. $wo->cleanUserInput($columns[35]) .'\',
            \''. $wo->cleanUserInput($columns[36]) .'\',
            \''. $wo->cleanUserInput($columns[37]) .'\',
            \''. $wo->cleanUserInput($columns[38]) .'\',
            \''. $wo->cleanUserInput($columns[39]) .'\',
            \''. $wo->cleanUserInput($columns[40]) .'\',
            \''. $wo->cleanUserInput($columns[41]) .'\',
            \''. $wo->cleanUserInput($columns[42]) .'\',
            \''. mysql_real_escape_string($columns[43]) .'\',
            \''. $wo->cleanUserInput($columns[44]) .'\',
            \''. $wo->cleanUserInput(str_replace('D', '', $columns[0])) .'\',
            \''. $wo->cleanUserInput($columns[45]) .'\',
            \''. $wo->cleanUserInput($columns[46]) .'\'
            )';
    
}
