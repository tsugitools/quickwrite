<?php
require_once "../../config.php";
require_once('../dao/QW_DAO.php');

use \Tsugi\Core\LTIX;
use \QW\DAO\QW_DAO;

$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$QW_DAO = new QW_DAO($PDOX, $p);

if ($USER->instructor) {

    if (isset($_POST["toolTitle"])) {
        $currentTime = new DateTime('now', new DateTimeZone($CFG->timezone));
        $currentTime = $currentTime->format("Y-m-d H:i:s");

        $QW_DAO->updateMainTitle($_SESSION["qw_id"], $_POST["toolTitle"], $currentTime);
    }

    if (!isset($_POST["nonav"])) {
        $_SESSION['success'] = "Title saved.";

        header( 'Location: '.addSession('../instructor-home.php') ) ;
    }
}
