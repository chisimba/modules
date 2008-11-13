<?php
$tablename = 'tbl_stats_questionnaire';

//Options line for comments, encoding and character set
$options = array('comment' => 'Table to store users answers to the questionnaires', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
    'id' => array(
        'type' => 'text',
        'length' => 32,
        'notnull' => true;
        ),
    'studentno' => array(
        'type' => 'text',
        'length' => 32,
        'notnull' => true;
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
        'type' => 'text',
        'length' => 1
        ),
    'q21' => array(
        'type' => 'text',
        'length' => 1
        ),
    'q22' => array(
        'type' => 'text',
        'length' => 1
        ),
    'q23' => array(
        'type' => 'text',
        'length' => 1
        ),
    'q24' => array(
        'type' => 'text',
        'length' => 1
        ),
    'q25' => array(
        'type' => 'text',
        'length' => 1
        ),
    'q26' => array(
        'type' => 'text',
        'length' => 1
        ),
    'q27' => array(
        'type' => 'text',
        'length' => 1
        ),
    'q28' => array(
        'type' => 'text',
        'length' => 1
        ),
    'q29' => array(
        'type' => 'text',
        'length' => 1
        ),
    'q30' => array(
        'type' => 'text',
        'length' => 1
        ),
    'q31' => array(
        'type' => 'text',
        'length' => 1
        ),
    'q32' => array(
        'type' => 'text',
        'length' => 1
        ),
    'q33' => array(
        'type' => 'text',
        'length' => 1
        ),
    'q34' => array(
        'type' => 'text',
        'length' => 1
        ),
    'q35' => array(
        'type' => 'text',
        'length' => 1
        ),
    'q36' => array(
        'type' => 'text',
        'length' => 1
        ),
    'q37' => array(
        'type' => 'text',
        'length' => 1
        ),
    'q38' => array(
        'type' => 'text',
        'length' => 1
        ),
    'q39' => array(
        'type' => 'text',
        'length' => 1
        ),
    'q40' => array(
        'type' => 'text',
        'length' => 1
        ),
    'q41' => array(
        'type' => 'text',
        'length' => 1
        ),
    'q42' => array(
        'type' => 'text',
        'length' => 1
        ),
    'q43' => array(
        'type' => 'text',
        'length' => 1
        ),
    'q44' => array(
        'type' => 'text',
        'length' => 1
        ),
    'q45' => array(
        'type' => 'text',
        'length' => 1
        ),
    'q46' => array(
        'type' => 'text',
        'length' => 1
        ),
    'q47' => array(
        'type' => 'text',
        'length' => 1
        ),
    'q48' => array(
        'type' => 'clob'
        )
    );

$name = 'tbl_stats_questionnaire_index';

$indexes = array(
                'fields' => array(
                    'studentno' => array()
                )
        );
?>