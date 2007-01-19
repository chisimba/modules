<?php
/*
$sqldata[]="CREATE TABLE tbl_test_questions (
    id VARCHAR(32) PRIMARY KEY NOT NULL,
    testId VARCHAR(32) NOT NULL,
    question TEXT,
    imageId VARCHAR(100) NOT NULL default '',
    imageName VARCHAR(120) NOT NULL default '',
    hint VARCHAR(120),
    mark INT,
    questionOrder INT,
    `updated` TIMESTAMP(14) NOT NULL,
    KEY `testId` (`testId`),
    CONSTRAINT `testQuestionTests` FOREIGN KEY (`testId`) REFERENCES `tbl_tests` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE) type=InnoDB
    COMMENT='This table stores a list of questions for a test'";
*/

//5ive definition
$tablename = 'tbl_test_questions';

//Options line for comments, encoding and character set
$options = array('comment' => 'This table stores a list of questions for a test', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
    'id' => array(
        'type' => 'text',
        'length' => 32,
        ),
    'testid' => array(
        'type' => 'text',
        'length' => 32,
        ),
    'question' => array(
        'type' => 'clob',
        ),
    'hint' => array(
        'type' => 'text',
        'length' => 120,
        ),
    'mark' => array(
        'type' => 'integer',
        'length' => 5,
        ),
    'questionorder' => array(
        'type' => 'integer',
        'length' => 2,
        ),
    'updated' => array(
        'type' => 'timestamp',
        ),
    );

// create other indexes here...
$name = 'test_questions_index';

$indexes = array(
                'fields' => array(
                    'testid' => array(),
                    'imageid' => array(),
                ),
        );
?>