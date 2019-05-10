<?php
require_once "../../config.php";
require_once('../dao/QW_DAO.php');

use \Tsugi\Core\LTIX;
use \QW\DAO\QW_DAO;

$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$QW_DAO = new QW_DAO($PDOX, $p);

$currentTime = new DateTime('now', new DateTimeZone($CFG->timezone));
$currentTime = $currentTime->format("Y-m-d H:i:s");

$questionId = $_POST["questionId"];
$answerText = $_POST["answerText"];

if (!isset($answerText) || $answerText == "") {
    $_SESSION['error'] = "Your answer cannot be blank.";
} else {
    $QW_DAO->createAnswer($USER->id, $questionId, $answerText, $currentTime);
    $_SESSION['success'] = "Answer saved.";
}

header( 'Location: '.addSession('../student-home.php') ) ;

