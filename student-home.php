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

echo ('<h2>Quick Write</h2>');
$SetID = $_SESSION["SetID"];
$questions = $QW_DAO->getQuestions($SetID);	
$Total = count($questions);	



if($Total == 0){
		
	echo ('<h4 style="margin:50px;">No question prompts have been created.</h3>');
	
} else{

	
	
		
	
	echo(' <div class="panel-body" style="margin:20px; "> 
	<form method="post" action="actions/AddQ_Submit.php">');
foreach ( $questions as $row ) {
	
	echo('
	             
			'.$row["QNum"].'.'.$row["Question"].'<br><br>
			<textarea class="form-control" name="A'.$row["QNum"].'" rows="3" autofocus required style="width:70%;"></textarea>
			<input type="hidden" name="Q'.$row["QNum"].'" value="'.$row["QID"].'"/>
			<input type="hidden" name="Total" value="'.$Total.'"/>
			
			<br><br>
		   
		

        '); 
	 
 }
 echo('<input type="submit" class="btn btn-success" style="width:70px;" value="Submit">');
echo ('</form></div>');
	
}

$OUTPUT->footerStart();

include("tool-footer.html");

$OUTPUT->footerEnd();


?>