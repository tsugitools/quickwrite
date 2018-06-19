<?php
require_once "../../config.php";
require_once('../dao/QW_DAO.php');

use \Tsugi\Core\LTIX;
use \QW\DAO\QW_DAO;

$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$QW_DAO = new QW_DAO($PDOX, $p);

if ($USER->instructor) {

    $questionId = $_POST["questionId"];
    $questionText = $_POST["questionText"];

	if ($questionId > -1) {
	    // Existing question
	    $QW_DAO->updateQuestion($questionId, $questionText);
    } else {
	    // New question
        $QW_DAO->createQuestion($_SESSION["qw_id"], $questionText);
    }

    header( 'Location: '.addSession('../instructor-home.php') ) ;
}
