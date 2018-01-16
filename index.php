<?php
require_once('../config.php');
require_once('dao/QW_DAO.php');

use \Tsugi\Core\LTIX;
use \QW\DAO\QW_DAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$QW_DAO = new QW_DAO($PDOX, $p);

$_SESSION["UserName"] = $USER->email;
$_SESSION["FullName"] = $USER->displayname;
$_SESSION["UserID"]= $USER->id;

$LastName = $USER->lastname;
$FirstName = $USER->firstname;

$_SESSION["SetID"]=0;
$_SESSION["CourseName"] = $CONTEXT->title;

$Main = $QW_DAO->siteExists($CONTEXT->id, $LINK->id);

if (!$Main) {
	$QW_DAO->createMain($USER->id, $CONTEXT->id, $LINK->id, $CONTEXT->title);
}

$_SESSION["SetID"] = $QW_DAO->getSetID($CONTEXT->id, $LINK->id);
$SetID = $_SESSION["SetID"];

if ( $USER->instructor ) {

    header( 'Location: '.addSession('instructor-home.php?Add=0') ) ;

} else { // student

	$student = $QW_DAO->checkStudent($CONTEXT->id, $USER->id);

	if ($student["UserID"] == ""){
	    $student = $QW_DAO->addStudent($USER->id, $CONTEXT->id, $LastName, $FirstName);
	}
	
	$Exist = $QW_DAO->userDataExists($SetID, $USER->id);
	
	if ($Exist) {
	    header( 'Location: '.addSession('student-report.php') ) ;
	} else {
	    header( 'Location: '.addSession('student-home.php') ) ;
	}
}
