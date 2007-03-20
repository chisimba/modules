<?php

	//table definition
$tablename = 'tbl_realtime_conversation';
 
	//encoding and character set
$options = array('collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
		'id' => array(
			'type' => 'text',
			'length' => 32,
			'notnull' => 1
			),
		'userid' => array(
			'type' => 'text',
			'length' => 32,
			'notnull' => 1
			),	
		'contextcode' => array(
			'type' => 'text',
			'length' => 25,
			'notnull' => 1
			),
		'starttime' => array(
			'type' => 'timestamp'
			),
		'endtime' => array(
			'type' => 'timestamp'
			)
		);
$name = 'tbl_realtime_conversation_idx';

$indexes = array(
                'fields' => array(
                    'contextcode' => array()
                )
        );				
?>
