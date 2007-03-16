<?php

	//table definition
$tablename = 'tbl_realtime_voicequeue';
 
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
		'conversationid' => array(
			'type' => 'text',
			'length' => 32,
			'notnull' => 1
			),
		'hastoken' => array(
			'type' => 'text',
			'length' => 32
			)
		);
$name = 'tbl_podcast_voicequeue_idx';

$indexes = array(
                'fields' => array(
                	'conversationid' => array(),
                    'userid' => array()
                )
        );				
?>
