<?php
//Chisimba table definition
$tablename = 'tbl_apachelog';

//Options line for comments, encoding and character set
$options = array('comment' => 'Apache log file analysis', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32
		),
    'fullrecord' => array(
		'type' => 'clob'
		),
    'ip_addr' => array(
		'type' => 'text',
		'length' => 255
		),
	'log_date' => array(
		'type' => 'text',
		),
	'request' => array(
		'type' => 'clob',
		),
	'servercode' => array(
		'type' => 'text',
		),
	'requrl' => array(
		'type' => 'text',
		),
	'useragent' => array(
		'type' => 'text',
		),
	'userid' => array(
		'type' => 'text',
        'length' => '25',
        'notnull' => true
		),
    'datecreated' => array(
		'type' => 'timestamp'
		),
    'modifierid' => array(
		'type' => 'text',
        'length' => '25',
        'notnull' => true
		),
    'datelastupdated' => array(
		'type' => 'timestamp'
		),
);
$name = 'ip_addr';

$indexes = array(
                'fields' => array(
                	'ip_addr' => array(),
                )
        );
?>