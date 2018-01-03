<?php

require_once "config.php";

use \Tsugi\Core\LTIX;

// Retrieve the launch data if present
$LTI = LTIX::requireData();
$p = $CFG->dbprefix;
$displayname = $USER->displayname;

// Start of the output
$OUTPUT->header();
?>
    <!-- Our main css file that overrides default Tsugi styling -->
    <link rel="stylesheet" type="text/css" href="styles/main.css">
<?php
$OUTPUT->bodyStart();

$OUTPUT->flashMessages();

$OUTPUT->welcomeUserCourse();

echo("<pre>Global Tsugi Objects:\n\n");
var_dump($USER);
var_dump($CONTEXT);
var_dump($LINK);

echo("\n<hr/>\n");
echo("Session data (low level):\n");
echo($OUTPUT->safe_var_dump($_SESSION));

$OUTPUT->footerStart();
?>
    <!-- Our main javascript file for tool functions -->
    <script src="scripts/main.js" type="text/javascript"></script>
<?php
$OUTPUT->footerEnd();