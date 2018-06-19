<?php
namespace QW\DAO;

class QW_DAO {

    private $PDOX;
    private $p;

    public function __construct($PDOX, $p) {
        $this->PDOX = $PDOX;
        $this->p = $p;
    }

    function skipSplash($user_id) {
        $query = "SELECT skip_splash FROM {$this->p}qw_splash WHERE user_id = :userId";
        $arr = array(':userId' => $user_id);
        $context = $this->PDOX->rowDie($query, $arr);
        return $context["skip_splash"];
    }

    function toggleSkipSplash($user_id) {
        $skip = $this->skipSplash($user_id) ? 0 : 1;
        $query = "INSERT INTO {$this->p}qw_splash (user_id, skip_splash) VALUES (:userId, ".$skip.") ON DUPLICATE KEY UPDATE skip_splash = ".$skip;
        $arr = array(':userId' => $user_id);
        $this->PDOX->queryDie($query, $arr);
    }

    function getOrCreateMain($user_id, $context_id, $link_id) {
        $main_id = $this->getMainID($context_id, $link_id);
        if (!$main_id) {
            return $this->createMain($user_id, $context_id, $link_id);
        } else {
            return $main_id;
        }
    }

    function getMainID($context_id, $link_id) {
        $query = "SELECT qw_id FROM {$this->p}qw_main WHERE context_id = :context_id AND link_id = :link_id";
        $arr = array(':context_id' => $context_id, ':link_id' => $link_id);
        $context = $this->PDOX->rowDie($query, $arr);
        return $context["qw_id"];
    }

    function createMain($user_id, $context_id, $link_id) {
        $query = "INSERT INTO {$this->p}qw_main (user_id, context_id, link_id, modified) VALUES (:userId, :contextId, :linkId, now());";
        $arr = array(':userId' => $user_id, ':contextId' => $context_id, ':linkId' => $link_id);
        $this->PDOX->queryDie($query, $arr);
        return $this->PDOX->lastInsertId();
    }

    function deleteMain($qw_id, $user_id) {
        $query = "DELETE FROM {$this->p}qw_main WHERE qw_id = :mainId AND user_id = :userId";
        $arr = array(':mainId' => $qw_id, ':userId' => $user_id);
        $this->PDOX->queryDie($query, $arr);
    }

    function getQuestions($qw_id) {
        $query = "SELECT * FROM {$this->p}qw_question WHERE qw_id = :qwId order by question_num;";
        $arr = array(':qwId' => $qw_id);
        return $this->PDOX->allRowsDie($query, $arr);
    }

    function getQuestionById($question_id) {
        $query = "SELECT * FROM {$this->p}qw_question WHERE question_id = :questionId;";
        $arr = array(':questionId' => $question_id);
        return $this->PDOX->rowDie($query, $arr);
    }

    function createQuestion($qw_id, $question_text) {
        $nextNumber = $this->getNextQuestionNumber($qw_id);
        $query = "INSERT INTO {$this->p}qw_question (qw_id, question_num, question_txt, modified) VALUES (:qwId, :questionNum, :questionText, now());";
        $arr = array(':qwId' => $qw_id, ':questionNum' => $nextNumber, ':questionText' => $question_text);
        $this->PDOX->queryDie($query, $arr);
        return $this->PDOX->lastInsertId();
    }

    function updateQuestion($question_id, $question_text) {
        $query = "UPDATE {$this->p}qw_question set question_txt = :questionText, modified = now() WHERE question_id = :questionId;";
        $arr = array(':questionId' => $question_id, ':questionText' => $question_text);
        $this->PDOX->queryDie($query, $arr);
    }

    function getNextQuestionNumber($qw_id) {
        $query = "SELECT MAX(question_num) as lastNum FROM {$this->p}qw_question WHERE qw_id = :qwId";
        $arr = array(':qwId' => $qw_id);
        $lastNum = $this->PDOX->rowDie($query, $arr)["lastNum"];
        return $lastNum + 1;
    }

    function countAnswersForQuestion($question_id) {
        $query = "SELECT COUNT(*) as total FROM {$this->p}qw_answer WHERE question_id = :questionId;";
        $arr = array(':questionId' => $question_id);
        return $this->PDOX->rowDie($query, $arr)["total"];
    }

    function deleteQuestion($question_id) {
        $query = "DELETE FROM {$this->p}qw_question WHERE question_id = :questionId;";
        $arr = array(':questionId' => $question_id);
        $this->PDOX->queryDie($query, $arr);
    }

    function getUsersWithAnswers($qw_id) {
        $query = "SELECT DISTINCT user_id FROM {$this->p}qw_answer a join {$this->p}qw_question q on a.question_id = q.question_id WHERE q.qw_id = :qwId;";
        $arr = array(':qwId' => $qw_id);
        return $this->PDOX->allRowsDie($query, $arr);
    }

    function getStudentAnswerForQuestion($question_id, $user_id) {
        $query = "SELECT * FROM {$this->p}qw_answer WHERE question_id = :questionId AND user_id = :userId; ";
        $arr = array(':questionId' => $question_id, ':userId' => $user_id);
        return $this->PDOX->rowDie($query, $arr);
    }

    function getMostRecentAnswerDate($user_id, $qw_id) {
        $query = "SELECT max(a.modified) as modified FROM {$this->p}qw_answer a join {$this->p}qw_question q on a.question_id = q.question_id WHERE a.user_id = :userId AND q.qw_id = :qwId;";
        $arr = array(':userId' => $user_id, ':qwId' => $qw_id);
        $context = $this->PDOX->rowDie($query, $arr);
        return $context['modified'];
    }

    function createAnswer($user_id, $question_id, $answer_txt, $date) {
        $query = "INSERT INTO {$this->p}qw_answer (user_id, question_id, answer_txt, modified) VALUES (:userId, :questionId, :answerTxt, :modified);";
        $arr = array(':userId' => $user_id,':questionId' => $question_id, ':answerTxt' => $answer_txt, ':modified' => $date);
        $this->PDOX->queryDie($query, $arr);
        return $this->PDOX->lastInsertId();
    }

    function updateAnswer($answer_id, $answer_txt, $modified) {
        $query = "UPDATE {$this->p}qw_answer set answer_txt = :answerTxt, modified = :modified where answer_id = :answerId;";
        $arr = array(':answerId' => $answer_id, ':answerTxt' => $answer_txt, ':modified' => $modified);
        $this->PDOX->queryDie($query, $arr);
    }

    function getAllAnswersToQuestion($question_id) {
        $query = "SELECT * FROM {$this->p}qw_answer WHERE question_id = :questionId;";
        $arr = array(':questionId' => $question_id);
        return $this->PDOX->allRowsDie($query, $arr);
    }

    function findEmail($user_id) {
        $query = "SELECT email FROM {$this->p}lti_user WHERE user_id = :user_id;";
        $arr = array(':user_id' => $user_id);
        $context = $this->PDOX->rowDie($query, $arr);
        return $context["email"];
    }

    function findDisplayName($user_id) {
        $query = "SELECT displayname FROM {$this->p}lti_user WHERE user_id = :user_id;";
        $arr = array(':user_id' => $user_id);
        $context = $this->PDOX->rowDie($query, $arr);
        return $context["displayname"];
    }

    ////





    function getQuestionBySetAndNumber($SetID, $QNum) {
        $query = "SELECT * FROM {$this->p}qw_questions WHERE QNum = :QNum AND SetID = :SetID;";
        $arr = array(':QNum' => $QNum, ':SetID' => $SetID);
        return $this->PDOX->rowDie($query, $arr);
    }



    function userDataExists($SetID, $userId) {
        $query = "SELECT * FROM {$this->p}qw_answer WHERE SetID = :SetID AND UserID = :userId";
        $arr = array(':SetID' => $SetID, ':userId' => $userId);
        $result = $this->PDOX->rowDie($query, $arr);
        return $result !== false;
    }

    function getAllAnswersForUser($SetID, $UserID) {
        $query = "SELECT * FROM {$this->p}qw_answer WHERE SetID = :SetID AND UserID = :UserID";
        $arr = array(':SetID' => $SetID, ':UserID' => $UserID);
        return $this->PDOX->allRowsDie($query, $arr);
    }


    function getAnswerById($AnswerID) {
        $query = "SELECT * FROM {$this->p}qw_answer WHERE AnswerID = :AnswerID;";
        $arr = array(':AnswerID' => $AnswerID);
        return $this->PDOX->rowDie($query, $arr);
    }



    function getUsersWithAnswersToQuestion($SetID, $QID) {
        $query = "SELECT DISTINCT UserID FROM {$this->p}qw_answer WHERE SetID = :SetID AND QID = :QID and Answer != '';";
        $arr = array(':SetID' => $SetID, ':QID' => $QID);
        return $this->PDOX->allRowsDie($query, $arr);
    }

    function deleteAnswersToQuestion($QID) {
        $query = "DELETE FROM {$this->p}qw_answer WHERE QID = :QID;";
        $arr = array(':QID' => $QID);
        $this->PDOX->queryDie($query, $arr);
    }

    function updateQNumber($QID, $QNum) {
        $query = "UPDATE {$this->p}qw_questions set QNum = :QNum where QID = :QID;";
        $arr = array(':QNum' =>$QNum, ':QID' => $QID);
        $this->PDOX->queryDie($query, $arr);
    }

    function findUserID($user_key) {
        $query = "SELECT user_id FROM {$this->p}lti_user WHERE user_key = :user_key;";
        $arr = array(':user_key' => $user_key);
        $context = $this->PDOX->rowDie($query, $arr);
        return $context["user_id"];
    }




}
