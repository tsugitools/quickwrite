<?php
namespace QW\DAO;

class QW_DAO {

    private $PDOX;
    private $p;

    public function __construct($PDOX, $p) {
        $this->PDOX = $PDOX;
        $this->p = $p;
    }


	function getSetID($context_id, $link_id) {
			$query = "SELECT SetID FROM {$this->p}qw_main WHERE context_id = :context_id AND link_id = :link_id";
			 $arr = array(':context_id' => $context_id, ':link_id' => $link_id);
			$context = $this->PDOX->rowDie($query, $arr);
			return $context["SetID"];
	}
	
	
	
	function siteExists($context_id, $link_id) {
        $query = "SELECT * FROM {$this->p}qw_main WHERE context_id = :context_id AND link_id = :link_id";
        $arr = array(':context_id' => $context_id, ':link_id' => $link_id);
        $result = $this->PDOX->rowDie($query, $arr);
        return $result !== false;
    }
	
	
    function createMain($userId, $context_id, $link_id,$CourseName) {
        $query = "INSERT INTO {$this->p}qw_main (UserID, context_id, link_id, CourseName) VALUES (:userId, :contextId,:link_id,:CourseName);";
        $arr = array(':userId' => $userId, ':contextId' => $context_id, ':link_id' => $link_id,':CourseName' => $CourseName);
        $this->PDOX->queryDie($query, $arr);
        return $this->PDOX->lastInsertId();
    }

    function updateMain($SetID, $CourseName, $active, $random) {
        $query = "UPDATE {$this->p}qw_main SET CourseName = :CourseName, Active = :active, Random = :random WHERE SetID = :SetID;";
        $arr = array(':CourseName' => $CourseName, ':active' => $active, ':random' => $random, ':SetID' => $SetID);
        $this->PDOX->queryDie($query, $arr);
    }
	
	
    function getQuestions($SetID) {
        $query = "SELECT * FROM {$this->p}qw_questions WHERE SetID=".$SetID." order by QNum;";
        return $this->PDOX->allRowsDie($query);
    }
	
	function eachQuestion($QID) {
        $query = "SELECT * FROM {$this->p}qw_questions WHERE QID=".$QID;
        return $this->PDOX->allRowsDie($query);
    }
	
	

    function createQuestion($SetID, $QNum, $Question) {
		
		$query = "INSERT INTO {$this->p}qw_questions (SetID, QNum, Question) VALUES (:SetID, :QNum, :Question);";
        $arr = array(':SetID' => $SetID, ':QNum' => $QNum, ':Question' => $Question);
        $this->PDOX->queryDie($query, $arr);
        return $this->PDOX->lastInsertId();
    }
	
	
	function Answer($UserID, $SetID, $QID, $Answer, $Date2) {
		
		$query = "INSERT INTO {$this->p}qw_activity (SetID,UserID, QID, Answer, Modified) VALUES (:SetID, :UserID, :QID, :Answer, :Modified);";
        $arr = array(':SetID' => $SetID,':UserID' => $UserID, ':QID' => $QID, ':Answer' => $Answer, ':Modified' => $Date2);
        $this->PDOX->queryDie($query, $arr);
        return $this->PDOX->lastInsertId();
    }
	
	
    function updateAnswer($ActivityID, $Answer, $Modified) {
        $query = "UPDATE {$this->p}qw_activity set Answer = :Answer, Modified = :Modified where ActivityID = :ActivityID;";
        $arr = array(':ActivityID' => $ActivityID, ':Answer' => $Answer, ':Modified' => $Modified);
        $this->PDOX->queryDie($query, $arr);
    }
	
	
	
	
	function addUserData($SetID, $QID, $userId, $Answer,$Attempt,$Date2) {
         $query = "INSERT INTO {$this->p}qw_activity (SetID, QID, UserID, Answer, Attempt, Modified) VALUES ( :SetID, :QID, :userId, :Answer, :Attempt, :Modified);";
         $arr = array(':SetID' => $SetID, ':QID' => $QID, ':userId' => $userId, ':Answer' => $Answer, ':Attempt' => $Attempt, ':Modified' => $Date2);
         $this->PDOX->queryDie($query, $arr);
       
    }
	
	
	
	
    function updateQuestion($QID, $Question) {
        $query = "UPDATE {$this->p}qw_questions set Question = :Question where QID = :QID;";
        $arr = array(':QID' => $QID, ':Question' => $Question);
        $this->PDOX->queryDie($query, $arr);
    }
	
	
	
    function getQuestionById($QID) {
        $query = "SELECT * FROM {$this->p}qw_questions WHERE QID = :QID;";
        $arr = array(':QID' => $QID);
        return $this->PDOX->rowDie($query, $arr);
    }

    function getQuestionBySetAndNumber($SetID, $QNum) {
        $query = "SELECT * FROM {$this->p}qw_questions WHERE QNum = :QNum AND SetID = :SetID;";
        $arr = array(':QNum' => $QNum, ':SetID' => $SetID);
        return $this->PDOX->rowDie($query, $arr);
    }

    function deleteQuestion($QID) {
        $query = "DELETE FROM {$this->p}qw_questions WHERE QID = :QID;";
        $arr = array(':QID' => $QID);
        $this->PDOX->queryDie($query, $arr);
    }

	
	
	function userDataExists($SetID, $userId) {
        $query = "SELECT * FROM {$this->p}qw_activity WHERE SetID = :SetID AND UserID = :userId";
        $arr = array(':SetID' => $SetID, ':userId' => $userId);
        $result = $this->PDOX->rowDie($query, $arr);
        return $result !== false;
    }
	
	function getUserData($SetID, $UserID) {
        $query = "SELECT * FROM {$this->p}qw_activity WHERE SetID = :SetID AND UserID = :UserID";
        $arr = array(':SetID' => $SetID, ':UserID' => $UserID);
        return $this->PDOX->rowDie($query, $arr);
    }
	
	function Review($QID, $userId) {
        $query = "SELECT * FROM {$this->p}qw_activity WHERE QID = :QID AND UserID = :userId  order by Modified DESC; ";
        $arr = array(':QID' => $QID, ':userId' => $userId);
        return $this->PDOX->allRowsDie($query, $arr);
    }
	

	
 function Report($SetID) {
        $query = "SELECT DISTINCT {$this->p}qw_activity.UserID, {$this->p}qw_students.LastName, {$this->p}qw_students.FirstName FROM {$this->p}qw_activity  INNER JOIN {$this->p}qw_students ON {$this->p}qw_activity.UserID = {$this->p}qw_students.UserID WHERE SetID = :SetID ORDER By {$this->p}qw_students.LastName;";
        $arr = array(':SetID' => $SetID);
        return $this->PDOX->allRowsDie($query, $arr);
    }

	
		
 function ReportByQID($SetID, $QID) {
        $query = "SELECT DISTINCT {$this->p}qw_activity.UserID, {$this->p}qw_students.LastName, {$this->p}qw_students.FirstName FROM {$this->p}qw_activity  INNER JOIN {$this->p}qw_students ON {$this->p}qw_activity.UserID = {$this->p}qw_students.UserID WHERE SetID = :SetID AND QID = :QID ORDER By {$this->p}qw_students.LastName;";
        $arr = array(':SetID' => $SetID, ':QID' => $QID);
        return $this->PDOX->allRowsDie($query, $arr);
    }

	
function checkStudent($context_id, $UserID){
		$query = "SELECT * FROM {$this->p}qw_students WHERE context_id = :context_id AND UserID = :UserID";
        $arr = array(':context_id' => $context_id, ':UserID' => $UserID);        
		return $result = $this->PDOX->rowDie($query, $arr);	
}


function addStudent($userId, $context_id, $LastName, $FirstName) {
        $query = "INSERT INTO {$this->p}qw_students (UserID, context_id, LastName, FirstName) VALUES (:userId, :context_id, :LastName,:FirstName);";
        $arr = array(':userId' => $userId, ':context_id' => $context_id, ':LastName' => $LastName,':FirstName' => $FirstName);
        $this->PDOX->queryDie($query, $arr);
        return $this->PDOX->lastInsertId();
}

function getStudentName($UserID) {
        $query = "SELECT * FROM {$this->p}qw_students WHERE UserID = :UserID;";
        $arr = array(':UserID' => $UserID);        
        return $this->PDOX->allRowsDie($query, $arr);
}

	
function getStudentList($context_id) {
        $query = "SELECT * FROM {$this->p}qw_students WHERE context_id = :context_id order by LastName;";
        $arr = array(':context_id' => $context_id);        
        return $this->PDOX->allRowsDie($query, $arr);
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
	
		
function findEmail($user_id) {
        $query = "SELECT email FROM {$this->p}lti_user WHERE user_id = :user_id;";
        $arr = array(':user_id' => $user_id);        
        $context = $this->PDOX->rowDie($query, $arr);
        return $context["email"];
}
	
			
function findDate($UserID, $SetID) {
        $query = "SELECT Modified FROM {$this->p}qw_activity WHERE UserID = :UserID AND SetID = :SetID;";
        $arr = array(':UserID' => $UserID, ':SetID' => $SetID);        
        $context = $this->PDOX->rowDie($query, $arr);
        return $context["Modified"];
}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}