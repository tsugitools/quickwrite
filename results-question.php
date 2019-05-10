<?php

require_once('../config.php');
require_once('dao/QW_DAO.php');

use QW\DAO\QW_DAO;
use Tsugi\Core\LTIX;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$QW_DAO = new QW_DAO($PDOX, $p);

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
        <h1>Results <small>by Question</small></h1>
        <section id="questionResponses">
            <div class="list-group">
                <?php
                $questionNumber = 1;
                foreach ($questions as $question) {
                    $responses = $QW_DAO->getAllAnswersToQuestion($question["question_id"]);
                    $numberResponses = count($responses);
                    ?>
                    <div class="list-group-item response-list-group-item">
                        <div class="row">
                            <div class="col-sm-3 header-col">
                                <a href="#responses<?=$question["question_id"]?>" class="h4 response-collapse-link" data-toggle="collapse">
                                    Question <?=$questionNumber?>
                                    <span class="fa fa-chevron-down rotate" aria-hidden="true"></span>
                                </a>
                            </div>
                            <div class="col-sm-offset-1 col-sm-8 header-col">
                                <div class="flx-cntnr flx-row flx-nowrap flx-start">
                                    <span class="flx-grow-all"><?=$question["question_txt"]?></span>
                                    <span class="badge response-badge"><?=$numberResponses?></span>
                                </div>
                            </div>
                            <div id="responses<?=$question["question_id"]?>" class="col-xs-12 results-collapse collapse">
                                <?php
                                foreach ($responses as $response) {
                                    if (!$QW_DAO->isUserInstructor($CONTEXT->id, $response["user_id"])) {
                                        $responseDate = new DateTime($response["modified"]);
                                        $formattedResponseDate = $responseDate->format("m/d/y")." | ".$responseDate->format("h:i A");
                                        ?>
                                        <div class="row response-row">
                                            <div class="col-sm-3">
                                                <h5><?=$QW_DAO->findDisplayName($response["user_id"])?></h5>
                                                <p><?=$formattedResponseDate?></p>
                                            </div>
                                            <div class="col-sm-offset-1 col-sm-8">
                                                <p class="response-text"><?=$response["answer_txt"]?></p>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php
                    $questionNumber++;
                }
                ?>
            </div>
        </section>
    </div>

<?php

$OUTPUT->footerStart();

include("tool-footer.html");

$OUTPUT->footerEnd();
