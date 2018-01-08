<?php
require_once "../../config.php";
require_once "../dao/QW_DAO.php";
require_once "../util/Utils.php";

use \Tsugi\Core\LTIX;
use \QW\DAO\QW_DAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$QW_DAO = new QW_DAO($PDOX, $p);

$SetID=$_SESSION["SetID"];
$QID=$_GET["QID"];

if ( $USER->instructor ) {

    $QW_DAO->deleteQuestion($QID);

    $remainingQ = $QW_DAO->getQuestions($SetID);
	$QNum = 0;
    foreach ( $remainingQ as $question ) {
        $QNum++;
        $QW_DAO->updateQNumber($question["QID"], $QNum);
    }

	
    header( 'Location: '.addSession('../instructor-home.php?Add=0') ) ;
} 
