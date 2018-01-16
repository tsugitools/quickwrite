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

echo ('<div class="container">
        <h2>Quick Write</h2>');

$SetID = $_SESSION["SetID"];
$questions = $QW_DAO->getQuestions($SetID);
$Total = count($questions);

$Edit2=0;

if ($Total == 0) {
    echo ('<h4 style="margin:50px;">No question prompts have been created.</h3>');
} else {
    $Date1 = $QW_DAO->getUserData($SetID, $USER->id);
    $dateTime1 = new DateTime($Date1["Modified"]);
    $D1 =$dateTime1->format("m-d-y")." at ".$dateTime1->format("h:i A");

    echo('<h3>Submitted on '.$D1.'</h3>
            <form method="post" action="actions/Edit_Submit.php">
            <div class="panel-body">');

    foreach ( $questions as $row ) {
        $Edit = 0;
        $A = "";
        $QID = $row["QID"];

        $Data = $QW_DAO->Review($QID, $USER->id);

        foreach ( $Data as $row2 ) {
            $ActivityID  =  $row2["ActivityID"];

            $A= $row2["Answer"];
            if ($A == "") {
                $Edit=1;
                $Edit2=1;
            }
            $Date1 = $row2["Modified"];
        }

        echo($row["QNum"].'. '.$row["Question"].'
                <input type="hidden" name="Q'.$row["QNum"].'" value="'.$ActivityID.'"/>
                ');

        if ($A != "") {
            echo ('<span class="fa fa-check" id="checkmark"></span>');
        }

        echo ('<div>');

        if ($Edit) {
            echo('<textarea class="form-control" name="A'.$row["QNum"].'" rows="3" autofocus id="answer" style="resize:none;background-color:white;"></textarea>');
        } else {
            echo('<div id="answer">'.$A.'</div>
                    <input type="hidden" name="A'.$row["QNum"].'" value="'.$A.'"/>');
        }

        echo ('</div>');
    }
    echo ('<input type="hidden" name="Total"  value="'.$Total.'"/> </div>');
}

if($Edit2){
    echo('<input type="submit" class="btn btn-success" value="Submit">');
}

echo ('</form></div>');

$OUTPUT->footerStart();

include("tool-footer.html");

$OUTPUT->footerEnd();
