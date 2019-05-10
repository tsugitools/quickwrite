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

$toolTitle = $QW_DAO->getMainTitle($_SESSION["qw_id"]);

if (!$toolTitle) {
    $toolTitle = "Quick Write";
}

$questions = $QW_DAO->getQuestions($_SESSION["qw_id"]);

// Clear any preview responses if there are questions
if ($questions) {
    $instructors = $QW_DAO->findInstructors($CONTEXT->id);
    foreach($instructors as $instructor) {
        $QW_DAO->deleteAnswers($questions, $instructor["user_id"]);
    }
}

include("menu.php");

$OUTPUT->flashMessages();

?>
    <div class="container">
        <h1 id="toolTitle" class="flx-cntnr flx-row flx-nowrap flx-start">
            <span class="title-text-span" onclick="editTitleText();"><?=$toolTitle?></span>
            <a id="toolTitleEditLink" class="toolTitleAction" href="javascript:void(0);" onclick="editTitleText();">
                <span class="fa fa-fw fa-pencil" aria-hidden="true"></span>
                <span class="sr-only">Edit Title Text</span>
            </a>
        </h1>
        <form id="toolTitleForm" action="actions/UpdateMainTitle.php" method="post" style="display:none;">
                <label for="toolTitleInput" class="sr-only">Title Text</label>
                <div class="h1 flx-cntnr flx-row flx-nowrap flx-start">
                    <textarea class="title-edit-input flx-grow-all" id="toolTitleInput" name="toolTitle" rows="2"><?=$toolTitle?></textarea>
                    <a id="toolTitleSaveLink" class="toolTitleAction" href="javascript:void(0);">
                        <span class="fa fa-fw fa-save" aria-hidden="true"></span>
                        <span class="sr-only">Save Title Text</span>
                    </a>
                    <a id="toolTitleCancelLink" class="toolTitleAction" href="javascript:void(0);">
                        <span class="fa fa-fw fa-times" aria-hidden="true"></span>
                        <span class="sr-only">Cancel Title Text</span>
                    </a>
            </div>
        </form>
        <p class="lead">Add questions to quickly collect feedback from your students.</p>
        <section id="theQuestions">
            <?php
            $questionNum = 1;
            foreach ($questions as $question) {
                ?>
                <div class="h3 inline flx-cntnr flx-row flx-nowrap flx-start question-row">
                    <div><?=$questionNum?>.</div>
                    <div class="flx-grow-all question-text">
                        <span class="question-text-span" onclick="editQuestionText(<?=$question["question_id"]?>)" id="questionText<?=$question["question_id"]?>"><?= $question["question_txt"] ?></span>
                        <form id="questionTextForm<?=$question["question_id"]?>" onsubmit="return confirmDeleteQuestionBlank(<?=$question["question_id"]?>)" action="actions/AddOrEditQuestion.php" method="post" style="display:none;">
                            <input type="hidden" name="questionId" value="<?=$question["question_id"]?>">
                            <label for="questionTextInput<?=$question["question_id"]?>" class="sr-only">Question Text</label>
                            <textarea class="form-control" id="questionTextInput<?=$question["question_id"]?>" name="questionText" rows="2" required><?=$question["question_txt"]?></textarea>
                        </form>
                    </div>
                    <a class="questionEditAction<?=$question["question_id"]?>" href="javascript:void(0);" onclick="editQuestionText(<?=$question["question_id"]?>)">
                        <span class="fa fa-fw fa-pencil" aria-hidden="true"></span>
                        <span class="sr-only">Edit Question Text</span>
                    </a>
                    <a class="questionDeleteAction<?=$question["question_id"]?>" onclick="return confirmDeleteQuestion();" href="actions/DeleteQuestion.php?question_id=<?=$question["question_id"]?>">
                        <span aria-hidden="true" class="fa fa-fw fa-trash"></span>
                        <span class="sr-only">Delete Question</span>
                    </a>
                    <a class="questionSaveAction<?=$question["question_id"]?>" href="javascript:void(0);" style="display:none;">
                        <span aria-hidden="true" class="fa fa-fw fa-save"></span>
                        <span class="sr-only">Save Question</span>
                    </a>
                    <a class="questionCancelAction<?=$question["question_id"]?>" href="javascript:void(0);" style="display: none;">
                        <span aria-hidden="true" class="fa fa-fw fa-times"></span>
                        <span class="sr-only">Cancel Question</span>
                    </a>
                </div>
                <?php
                $questionNum++;
            }
            ?>
            <div id="newQuestionRow" class="h3 inline flx-cntnr flx-row flx-nowrap flx-start question-row" style="display:none;">
                <div><?=$questionNum?>.</div>
                <div class="flx-grow-all question-text">
                    <form id="questionTextForm-1" action="actions/AddOrEditQuestion.php" method="post">
                        <input type="hidden" name="questionId" value="-1">
                        <label for="questionTextInput-1" class="sr-only">Question Text</label>
                        <textarea class="form-control" id="questionTextInput-1" name="questionText" rows="2" required></textarea>
                    </form>
                </div>
                <a class="questionSaveAction-1" href="javascript:void(0);">
                    <span aria-hidden="true" class="fa fa-fw fa-save"></span>
                    <span class="sr-only">Save Question</span>
                </a>
                <a class="questionCancelAction-1" href="javascript:void(0);">
                    <span aria-hidden="true" class="fa fa-fw fa-times"></span>
                    <span class="sr-only">Cancel Question</span>
                </a>
            </div>
        </section>
        <section id="addQuestions">
            <h3>
                <a href="javascript:void(0);" id="addQuestionLink" onclick="showNewQuestionRow();" class="btn btn-success"><span class="fa fa-plus" aria-hidden="true"></span> Add Question</a>
            </h3>
        </section>
    </div>

    <input type="hidden" id="sess" value="<?php echo($_GET["PHPSESSID"]) ?>">
<?php

$OUTPUT->footerStart();

include("tool-footer.html");

$OUTPUT->footerEnd();
