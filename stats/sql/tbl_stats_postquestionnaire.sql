<?php
$tablename = 'tbl_stats_postquestionnaire';

//Options line for comments, encoding and character set
$options = array('comment' => 'Table to store users answers to the post course questionnaire', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
    'id' => array(
        'type' => 'text',
        'length' => 32,
        'notnull' => true
        ),
    'studentno' => array(
        'type' => 'text',
        'length' => 32,
        'notnull' => true
        ),
    'contactno' => array(
        'type' => 'text',
        'length' => 32
        ),
    'q1' => array(
        'type' => 'text',
        'length' => 1
        ),
    'q2' => array(
        'type' => 'text',
        'length' => 1
        ),
    'q3' => array(
        'type' => 'text',
        'length' => 1
        ),
    'q4' => array(
        'type' => 'text',
        'length' => 1
        ),
    'q5' => array(
        'type' => 'text',
        'length' => 1
        ),
    'q6' => array(
        'type' => 'text',
        'length' => 1
        ),
    'q7' => array(
        'type' => 'text',
        'length' => 1
        ),
    'q8' => array(
        'type' => 'text',
        'length' => 1
        ),
    'q9' => array(
        'type' => 'text',
        'length' => 1
        ),
    'q10' => array(
        'type' => 'text',
        'length' => 1
        ),
    'q11' => array(
        'type' => 'text',
        'length' => 1
        ),
    'q12' => array(
        'type' => 'text',
        'length' => 1
        ),
    'q13' => array(
        'type' => 'text',
        'length' => 1
        ),
    'q14' => array(
        'type' => 'text',
        'length' => 1
        ),
    'q15' => array(
        'type' => 'text',
        'length' => 1
        ),
    'q16' => array(
        'type' => 'text',
        'length' => 1
        ),
    'q17' => array(
        'type' => 'text',
        'length' => 1
        ),
    'q18' => array(
        'type' => 'text',
        'length' => 1
        ),
    'q19' => array(
        'type' => 'text',
        'length' => 1
        ),
    'q20' => array(
        'type' => 'clob'
        )
    );

$name = 'tbl_stats_postquestionnaire_index';

$indexes = array(
                'fields' => array(
                    'studentno' => array()
                )
        );
?>