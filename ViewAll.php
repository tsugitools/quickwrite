<?php

require_once('../config.php');
require_once('dao/QW_DAO.php');

use \Tsugi\Core\LTIX;
use \QW\DAO\QW_DAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$QW_DAO = new QW_DAO($PDOX, $p);

// Start of the output
$OUTPUT->header();

include("tool-header.html");

$OUTPUT->bodyStart();

include("menu.php");

echo ('<div class="container">');

echo('<div id="Btn02">
        <a href="actions/ExportToFile.php" id="Btn02_1">Export Results</a>
      </div>');

echo('<h2 class="tool-title">Quick Write</h2>');

$SetID = $_SESSION["SetID"];
$StudentList = $QW_DAO->Report($SetID);

if (count($StudentList)) {
    echo ('<div>View All Results.</div>');
}

foreach ( $StudentList as $row ) {
    echo('<div class="panel-body">
            <div class="col-sm-3">'.$row["FirstName"].' '.$row["LastName"]);

    $UserID = 	$row["UserID"];
    $questions = $QW_DAO->getQuestions($SetID);
    $Date1 = $QW_DAO->getUserData($SetID, $UserID);
    $dateTime1 = new DateTime($Date1["Modified"]);
    $formattedDate = $dateTime1->format("m-d-y")." at ".$dateTime1->format("h:i A");

    echo('<br />
            <span><em>'.$formattedDate.'</em></span>
        </div>
        <div class="col-sm-9 noPadding">
        ');

    echo('<table>');

    foreach ( $questions as $row1 ) {

        $A="";
        $QID = $row1["QID"];

        $Data = $QW_DAO->Review($QID, $UserID);

        foreach ( $Data as $row2 ) {
            $A= $row2["Answer"];
            $Date1 = $row2["Modified"];
        }

        echo ('<tr>
                  <td><strong>Question '.$row1["QNum"].'</strong></td>
                  <td>'.$A.'</td>
                </tr>
              ');
    }

    echo('</table>');

    echo ('</div>
        </div>');
}

echo ('</div>');

$OUTPUT->footerStart();

include("tool-footer.html");

$OUTPUT->footerEnd();
