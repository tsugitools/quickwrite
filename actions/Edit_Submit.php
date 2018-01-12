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


		
		$SetID=$_SESSION["SetID"];
	
	for ($x = 1; $x < ($_POST["Total"]+1); $x++) {
		$Temp = "A".$x;
		$ActivityID = "Q".$x;	   
		$Answer = str_replace("'", "&#39;", $_POST[$Temp]);
		$ActivityID2 = $_POST[$ActivityID];
		
	echo $Answer."<br>";
		$QW_DAO->updateAnswer($ActivityID2, $Answer, $Date2);
	}
	
	header( 'Location: '.addSession('../student-report.php') ) ;
	
