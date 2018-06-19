<?php

require_once('../config.php');
require_once('dao/QW_DAO.php');

use QW\DAO\QW_DAO;
use Tsugi\Core\LTIX;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$QW_DAO = new QW_DAO($PDOX, $p);

if (!isset($_GET["question_id"])) {
    header( 'Location: '.addSession('instructor-home.php') ) ;
}

$question_id = $_GET["question_id"];

// Start of the output
$OUTPUT->header();

include("tool-header.html");

$OUTPUT->bodyStart();

$question = $QW_DAO->getQuestionById($question_id);

$answers = $QW_DAO->getAllAnswersToQuestion($question_id);

$totalAnswers = count($answers);

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-10 col-sm-offset-1">
            <div class="list-group fadeInFast" id="qwContentContainer">
                <div class="list-group-item">
                    <h4>Answers (<?php echo($totalAnswers); ?>)</h4>
                </div>
                <?php
                foreach ($answers as $answer) {
                    ?>
                    <div class="list-group-item">
                        <div class="row">
                            <div class="col-xs-2">
                                <h5>
                                    <?php echo($QW_DAO->findDisplayName($answer["user_id"])); ?>
                                </h5>
                            </div>
                            <div class="col-xs-10">
                                <p>
                                    <?php echo($answer["answer_txt"]); ?>
                                </p>
                            </div>
                        </div>
                        </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php
$OUTPUT->footerStart();

include("tool-footer.html");

$OUTPUT->footerEnd();
