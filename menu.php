<?php
if ($USER->instructor) {
?>
<nav class="navbar navbar-fixed-top navbar-default">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#instructor-nav" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">Quick Write</a>
        </div>

        <div class="collapse navbar-collapse" id="instructor-nav">
            <ul class="nav navbar-nav navbar-right">
                <?php
                if ('student-home.php' != basename($_SERVER['PHP_SELF'])) {
                    ?>
                    <li <?php if('instructor-home.php' == basename($_SERVER['PHP_SELF'])){echo ' class="active"';} ?>><a href="instructor-home.php"><span class="fa fa-cog" aria-hidden="true"></span> Build</a></li>
                    <li <?php if('results-student.php' == basename($_SERVER['PHP_SELF']) || 'results-question.php' == basename($_SERVER['PHP_SELF']) || 'results-download.php' == basename($_SERVER['PHP_SELF'])){echo ' class="active dropdown"';}else{ echo 'class="dropdown"';} ?>>
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="fa fa-list-ul" aria-hidden="true"></span> Results <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="results-student.php">By Student</a></li>
                            <li><a href="results-question.php">By Question</a></li>
                            <li><a href="results-download.php">Download Results</a></li>
                        </ul>
                    </li>
                    <li><a href="student-home.php"><span class="fa fa-graduation-cap" aria-hidden="true"></span> Student View</a></li>
                    <?php
                } else {
                    ?>
                    <li><a href="instructor-home.php"><span class="fa fa-arrow-left" aria-hidden="true"></span> Exit Student View</a></li>
                    <?php
                }
                ?>
            </ul>
        </div>
    </div>
</nav>
<?php
} else {
?>
    <nav class="navbar navbar-fixed-top navbar-default">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="index.php">Quick Write</a>
            </div>
        </div>
    </nav>
<?php
}