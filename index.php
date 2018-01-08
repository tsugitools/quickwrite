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




$_SESSION["UserName"] = $USER->email;
$_SESSION["FullName"] = $USER->displayname;
$_SESSION["UserID"]= $USER->id;
$LastName = $USER->lastname;
$FirstName = $USER->firstname;
//echo "Site ID: ".$CONTEXT->id;
$_SESSION["SetID"]=0;
$_SESSION["CourseName"] = $CONTEXT->title;

$Main = $QW_DAO->siteExists($CONTEXT->id, $LINK->id);

if ($Main != 1) {  	
	$QW_DAO->createMain($USER->id, $CONTEXT->id, $LINK->id, $CONTEXT->title);
}


$_SESSION["SetID"] = $QW_DAO->getSetID($CONTEXT->id, $LINK->id);
$SetID = $_SESSION["SetID"];

if ( $USER->instructor ) {

header( 'Location: '.addSession('instructor-home.php?Add=0') ) ;
	
	//include("roster.php");
	
	

}else{ // student

	
	$a = $QW_DAO->checkStudent($CONTEXT->id, $USER->id);
	if($a["UserID"] == ""){	$b = $QW_DAO->addStudent($USER->id, $CONTEXT->id, $LastName, $FirstName);}
	
	echo $SetID." ".$USER->id."<br>";
	
		
	$Exist = $QW_DAO->userDataExists($SetID, $USER->id);
	
	//echo $Exist;
	
	if($Exist==1){header( 'Location: '.addSession('student-report.php') ) ;}
	else{header( 'Location: '.addSession('student-home.php') ) ;}
	
	
	
   	
	
}

$OUTPUT->footerStart();

include("tool-footer.html");

$OUTPUT->footerEnd();

