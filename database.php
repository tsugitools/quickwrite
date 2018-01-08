<?php

// The SQL to uninstall this tool
$DATABASE_UNINSTALL = array(
    /*
     * "drop table if exists {$CFG->dbprefix}qw_link"
     * We probably want to keep these records even if the tool
     * is uninstalled.
     */
);

// The SQL to create the tables if they don't exist
$DATABASE_INSTALL = array(
    array( "{$CFG->dbprefix}qw_main",
        "create table {$CFG->dbprefix}qw_main (
    SetID       INTEGER NOT NULL AUTO_INCREMENT,
    UserID      INTEGER NULL,
    context_id  INTEGER NULL,
	link_id  INTEGER NULL,
    CourseName 		varchar(255) NULL,
    Modified    datetime NULL,
    PRIMARY KEY(SetID)
	
) ENGINE = InnoDB DEFAULT CHARSET=utf8"),    
    array( "{$CFG->dbprefix}qw_questions",
        "create table {$CFG->dbprefix}qw_questions (
    QID      INTEGER NOT NULL AUTO_INCREMENT,
    SetID       INTEGER NULL,
    QNum     INTEGER NULL,
    Question       varchar(1500) NULL,   
    Modified    datetime NULL,
    
  
    CONSTRAINT `{$CFG->dbprefix}qw_ibfk_1`
        FOREIGN KEY (`SetID`)
        REFERENCES `{$CFG->dbprefix}qw_main` (`SetID`)
        ON UPDATE CASCADE,

    PRIMARY KEY(QID)
	
) ENGINE = InnoDB DEFAULT CHARSET=utf8"),
    array( "{$CFG->dbprefix}qw_activity",
        "create table {$CFG->dbprefix}qw_activity (
    ActivityID  INTEGER NOT NULL AUTO_INCREMENT,
    UserID      INTEGER NULL,
    SetID       INTEGER NULL,
    QID      	INTEGER NULL,
	Answer      varchar(3000) NULL,
    Modified    datetime NULL,  
    PRIMARY KEY(ActivityID)
) ENGINE = InnoDB DEFAULT CHARSET=utf8"),
    array( "{$CFG->dbprefix}qw_temp",
        "create table {$CFG->dbprefix}qw_temp (
    TemSetID  INTEGER NOT NULL AUTO_INCREMENT,
    UserID      INTEGER NULL,
    SetID       INTEGER NULL,	
    PRIMARY KEY(TemSetID)
	
) ENGINE = InnoDB DEFAULT CHARSET=utf8"),
	array( "{$CFG->dbprefix}qw_students",
        "create table {$CFG->dbprefix}qw_students (
    StudentID  INTEGER NOT NULL AUTO_INCREMENT,
    UserID      INTEGER NULL,
    context_id  INTEGER NULL,
	LastName    varchar(100) NULL,
	FirstName   varchar(100) NULL,     
    PRIMARY KEY(StudentID)
	
) ENGINE = InnoDB DEFAULT CHARSET=utf8")
);