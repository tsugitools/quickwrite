<?php

require_once('../config.php');
require_once('dao/QW_DAO.php');

use \Tsugi\Core\LTIX;
use \QW\DAO\QW_DAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$QW_DAO = new QW_DAO($PDOX, $p);

$students = $QW_DAO->getUsersWithAnswers($_SESSION["qw_id"]);
$questions = $QW_DAO->getQuestions($_SESSION["qw_id"]);
$totalQuestions = count($questions);

// Start of the output
$OUTPUT->header();

include("tool-header.html");

$OUTPUT->bodyStart();

include("menu.php");

$OUTPUT->flashMessages();

?>
    <div class="container">
        <h1>Results
            <small>by Student</small>
        </h1>
        <section id="studentResponses">
            <div class="panel panel-info">
                <div class="panel-heading response-panel-header">
                    <div class="row">
                        <div class="col-xs-6">
                            <h4>Student Name</h4>
                        </div>
                        <div class="col-xs-3 text-center">
                            <h4>Last Updated</h4>
                        </div>
                        <div class="col-xs-3 text-center">
                            <h4>Completed</h4>
                        </div>
                    </div>
                </div>
                <div class="list-group">
                    <?php
                    foreach ($students as $student) {
                        if (!$QW_DAO->isUserInstructor($CONTEXT->id, $student["user_id"])) {
                            $mostRecentDate = new DateTime($QW_DAO->getMostRecentAnswerDate($student["user_id"], $_SESSION["qw_id"]));
                            $formattedMostRecentDate = $mostRecentDate->format("m/d/y") . " | " . $mostRecentDate->format("h:i A");
                            $numberAnswered = $QW_DAO->getNumberQuestionsAnswered($student["user_id"], $_SESSION["qw_id"]);
                            ?>
                            <div class="list-group-item response-list-group-item">
                                <div class="row">
                                    <div class="col-xs-6 header-col">
                                        <a href="#responses<?= $student["user_id"] ?>" class="h4 response-collapse-link" data-toggle="collapse">
                                            <?= $QW_DAO->findDisplayName($student["user_id"]) ?>
                                            <span class="fa fa-chevron-down rotate" aria-hidden="true"></span>
                                        </a>
                                    </div>
                                    <div class="col-xs-3 text-center header-col">
                                        <span class="h5 inline"><?= $formattedMostRecentDate ?></span>
                                    </div>
                                    <div class="col-xs-3 text-center header-col">
                                        <span class="h5 inline"><?= $numberAnswered . '/' . $totalQuestions ?></span>
                                    </div>
                                    <div id="responses<?= $student["user_id"] ?>" class="col-xs-12 results-collapse collapse">
                                        <?php
                                        $questionNumber = 1;
                                        foreach ($questions as $question) {
                                            $response = $QW_DAO->getStudentAnswerForQuestion($question["question_id"], $student["user_id"]);
                                            ?>
                                            <div class="row response-row">
                                                <div class="col-sm-3">
                                                    <h4 class="small-hdr hdr-notop-mrgn">
                                                        <small>Question <?= $questionNumber ?></small>
                                                    </h4>
                                                    <h5 class="sub-hdr"><?= $question["question_txt"] ?></h5>
                                                </div>
                                                <div class="col-sm-offset-1 col-sm-8">
                                                    <p><?= $response["answer_txt"] ?></p>
                                                </div>
                                            </div>
                                            <?php
                                            $questionNumber++;
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </section>
    </div>
<?php

$OUTPUT->footerStart();

include("tool-footer.html");

$OUTPUT->footerEnd();
