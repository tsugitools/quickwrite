<?php
require_once "../../config.php";
require_once('../dao/QW_DAO.php');

use \Tsugi\Core\LTIX;
use \QW\DAO\QW_DAO;

$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;
$QW_DAO = new QW_DAO($PDOX, $p);

date_default_timezone_set('America/New_York');
//echo date_default_timezone_get();
  $Date2 = date("Y-m-d H:i:s");


if ( $USER->instructor ) {
	$Question = str_replace("'", "&#39;", $_POST["Question"]);
	$Flag= $_POST["Flag"];
	
	if( $Flag == 1){ 
		$SetID=$_SESSION["SetID"];
		$QNum = $_POST["QNum"];
		$QW_DAO->createQuestion($SetID, $QNum, $Question);
	}
	else{
		$QID = $_POST["QID"];
		$QW_DAO->updateQuestion($QID, $Question);
	}
	   
header( 'Location: '.addSession('../instructor-home.php?Add=1') ) ;
	
}else{
	
		
		$SetID=$_SESSION["SetID"];
	
	for ($x = 1; $x < ($_POST["Total"]+1); $x++) {
		$Temp = "A".$x;
		$QID = "Q".$x;	   
		$Answer = str_replace("'", "&#39;", $_POST[$Temp]);
		
		
		$QID2 = $_POST[$QID];
		
	echo $Answer."<br>";
		$QW_DAO->Answer($USER->id, $SetID, $QID2, $Answer, $Date2);
	}
	
	header( 'Location: '.addSession('../student-report.php') ) ;
	
	
}