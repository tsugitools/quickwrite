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

$SetID = $_SESSION["qw_id"];

$toolTitle = $QW_DAO->getMainTitle($_SESSION["qw_id"]);

if (!$toolTitle) {
    $toolTitle = "Quick Write";
}

$questions = $QW_DAO->getQuestions($SetID);
$totalQuestions = count($questions);

$moreToSubmit = false;

include("menu.php");

$OUTPUT->flashMessages();

?>
    <div class="container">
        <h1><?=$toolTitle?></h1>
        <?php
        $questionNum = 1;
        foreach ($questions as $question) {
            $answer = $QW_DAO->getStudentAnswerForQuestion($question["question_id"], $USER->id);
            ?>
            <h2>Question <?=$questionNum?></h2>
            <?php
            if (!$answer) {
                ?>
                <form action="actions/AnswerQuestion.php" method="post">
                    <input type="hidden" name="questionId" value="<?=$question["question_id"]?>">
                    <div class="form-group">
                        <label class="h3" for="answerText<?=$question["question_id"]?>"><?= $question["question_txt"] ?></label>
                        <textarea class="form-control" id="answerText<?=$question["question_id"]?>" name="answerText" rows="5"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Submit</button>
                </form>
                <?php
            } else {
                $dateTime = new DateTime($answer['modified']);
                $formattedDate = $dateTime->format("m-d-y")." | ".$dateTime->format("h:i A");
                ?>
                <h3><?= $question["question_txt"] ?></h3>
                <p><?=$formattedDate?></p>
                <p><?=$answer["answer_txt"]?></p>
                <?php
            }
            $questionNum++;
        }
        ?>
    </div>
<?php
$OUTPUT->footerStart();

include("tool-footer.html");

$OUTPUT->footerEnd();
