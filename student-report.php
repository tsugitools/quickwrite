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

echo ('
<div style="margin-left:30px;">
<h2>Quick Write</h2>');
$SetID = $_SESSION["SetID"];
$questions = $QW_DAO->getQuestions($SetID);	
$Total = count($questions);	

?>
<style>
	h3{color:green; margin:15px; font-weight: bold;}
	#checkmark  {float:right; margin-right:calc(25% - 90px); font-size:70px;color:green;}
	#answer{width:75%; min-height:110px; border: 1px solid lightgray; padding:5px; background-color:lightgray;}
@media (max-width: 480px) {
    #checkmark  {display:none;}
	#answer{width:100%;}
}
</style>
<?php

if($Total == 0){
		
	echo ('<h4 style="margin:50px;">No question prompts have been created.</h3>');
	
} else{

	
	$Date1 = $QW_DAO->getUserData($SetID, $USER->id);	
	$dateTime1 = new DateTime($Date1["Modified"]);	
	$D1 =$dateTime1->format("m-d-y")." at ".$dateTime1->format("h:i A");
	
	echo('
	<br>
	<h3>Submitted on '.$D1.'</h3>
	
	<div class="panel-body" style="margin:15px; "> ');
	foreach ( $questions as $row ) {
	
				$A="";	
				$QID = $row["QID"];	
		
				$Data = $QW_DAO->Review($QID, $USER->id);	
				foreach ( $Data as $row2 ) {

					$A= $row2["Answer"];
					$Date1 = $row2["Modified"];
					
					
				}

	
	
	
	echo('
	             
			<b>'.$row["QNum"].'.'.$row["Question"].'</b><br><br>
	');
		
	
		if ($A != ""){ 
			
			 echo ('<i class="fa fa-check" id="checkmark"></i>');
			}
					
		
		
		echo ('
			
			<div id="answer" >'.$A.'</div>
				
			<br><br>
		   
		

        '); 
	 
 }

echo ('</div>');
	
}

echo ('</div>');
$OUTPUT->footerStart();

include("tool-footer.html");

$OUTPUT->footerEnd();


?>