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

$SetID = $_SESSION["SetID"];

$questions = $QW_DAO->getQuestions($SetID);
$Total = count($questions);

$_SESSION["Next"] = $Total +1;

$numQuestionsMsg = $Total . " " . (($Total == 1) ? "question" : "questions") . " posted";

if (isset($_GET["QID"])) {
    $QID = $_GET["QID"];
} else {
    $QID = 0;
}

echo ('<div class="container">');

echo('<div id="Btn01">
        <a class="btn btn-default" href="ViewAll.php" >View All Results</a>
        <br />
        <span><em>'.$numQuestionsMsg.'</em></span>
        </div>');

echo('<h2 class="tool-title">Quick Write</h2>');

echo ('<p>Add questions to quickly collect feedback from your students.</p>');

foreach ( $questions as $row ) {

    $StudentNum = count($QW_DAO->ReportByQID($SetID, $row['QID']));
    ?>

    <div class="modal fade" id="<?php echo $row['QID']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <?php echo ('<h3 id="qTitle">Question '.$row["QNum"].'</h3><h4>'.$row["Question"].'</h4>');?>
                </div>
                <div class="modal-body">
                    <?php

                    $QID = $row['QID'];
                    $StudentList = $QW_DAO->ReportByQID($SetID, $QID);

                    $rNum=0;
                    foreach ( $StudentList as $row3 ) {
                        $UserID = 	$row3["UserID"];
                        $A="";
                        $Data = $QW_DAO->Review($QID, $UserID);

                        foreach ( $Data as $row2 ) {
                            $A= $row2["Answer"];
                            $Date1 = $row2["Modified"];
                            if ($A != "") {
                                $rNum++;
                            }
                        }

                        if($A !=""){
                            echo('<div class="panel-body " style="border:1px solid gray;">
                                    <div class="col-sm-2 noPadding">'.$row3["FirstName"].' '.$row3["LastName"].'</div>
                                    <div class="col-sm-10 noPadding">' . $A .'</div>
                                  </div>
                                  ');
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="Edit_<?php echo $row['QID']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Question Text</h4>
                </div>
                <div class="modal-body">
                <?php
                echo ('<form method="post" action="actions/AddQ_Submit.php">
                        <input type="hidden" name="QID" value="'.$row["QID"].'"/>
                        <input type="hidden" name="Flag" value="2"/>
                        <textarea class="form-control" name="Question" rows="3" autofocus required>'.$row["Question"].'</textarea>
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                        <input type="submit" class="btn btn-success" value="Save">
                      </form>');
                ?>
                </div>
            </div>
        </div>
    </div>

    <?php

    echo('<div class="row">           
            <div class="col-sm-1 text-center" id="Btn04">
                <h4>'.$row["QNum"].'</h4>
            </div>
            
            <div class="col-sm-8">
                <div>'.$row["Question"].'</div>
            </div>
            
            <div class="col-sm-3" id="Btn03">
                <a class="btn btn-danger pull-right" href="actions/Delete.php?QID='.$row["QID"].'" onclick="return confirmDelete();">
                    <span class="fa fa-trash"></span>
                </a>
                <a href="#Edit_'.$row['QID'].'" class="btn btn-success" data-toggle="modal" >Edit </a>
                <a href="#'.$row['QID'].'"  class="btn btn-primary"  data-toggle="modal">Report ('.$rNum.') </a>
            </div>
          </div>
          ');
}

if($_GET["Add"]){
    echo('<form method="post" action="actions/AddQ_Submit.php">
            <div>
                <div class="col-sm-1 noPadding text-center">
                    <h4>'.$_SESSION["Next"].'</h4>
                </div>
                <div class="col-sm-8 noPadding">
                    <textarea class="form-control" name="Question" id="Question" rows="3" autofocus required></textarea>
                    <input type="hidden" name="QNum" value="'.$_SESSION["Next"].'"/>
                    <input type="hidden" name="Flag" value="1"/>
                </div>
                <div class="col-sm-3 noPadding">
                    <input type="submit" class="btn btn-success" value="Save" >
                </div>
            </div>
          </form>
          ');
}

echo ('<a class="btn btn-success" href="instructor-home.php?Add=1">Add Question</a>');

echo ('</div>'); // End container

$OUTPUT->footerStart();

include("tool-footer.html");

$OUTPUT->footerEnd();
