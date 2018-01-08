<?php
require_once "../../config.php";
require_once "../dao/QW_DAO.php";

use \Tsugi\Core\LTIX;
use \QW\DAO\QW_DAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$QW_DAO = new QW_DAO($PDOX, $p);

$SetID=$_SESSION["SetID"];
$QID=$_POST["QID"];
$QType = $_POST["QType"];

$Question = str_replace("'", "&#39;", $_POST["Question"]);
$Answer = str_replace("'", "&#39;", $_POST["Answer"]);

$Point = $_POST["Point"];
$FR = $_POST["FR"];
$FW = $_POST["FW"];



$FR = str_replace("'", "&#39;", $_POST["FR"]);
$FW = str_replace("'", "&#39;", $_POST["FW"]);
if(isset($_POST["RA"])){$RA = 1;}else{$RA = 0;}



if ( $USER->instructor ) {

	
	if ($QType == "Multiple"){
		
		$A=$_POST["A"];$A = str_replace("'", "&#39;", $_POST["A"]);
		$B=$_POST["B"];$B = str_replace("'", "&#39;", $_POST["B"]);
		$C=$_POST["C"];$C = str_replace("'", "&#39;", $_POST["C"]);
		$D=$_POST["D"];$D = str_replace("'", "&#39;", $_POST["D"]);
		$QW_DAO->updateQuestion($QID, $Question, $Answer, $QType, $A, $B, $C, $D, $Point, $FR, $FW, $RA);}
	
	else if ($QType == "True/False"){$QW_DAO->updateQuestion2($QID, $Question, $Answer, $QType, $Point, $FR, $FW);}

	

header( 'Location: '.addSession('../Qlist.php?SetID='.$SetID) ) ;

} else {
    // student so send back to index
    header( 'Location: '.addSession('../index.php') ) ;
}