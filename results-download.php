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

$OUTPUT->flashMessages();

?>
    <div class="container">
        <h1>Download Results</h1>
        <p class="lead">Click on the link below to download the student results.</p>
        <h4>
            <a href="actions/ExportToFile.php">
                <span class="fa fa-download" aria-hidden="true"></span> QuickWrite-<?=$CONTEXT->title?>-Results.xls
            </a>
        </h4>
    </div>
<?php

$OUTPUT->footerStart();

include("tool-footer.html");

$OUTPUT->footerEnd();