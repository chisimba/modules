<?php

/*

Todo: Investigate possibility of tree categories

*/


// Table Name
$tablename = 'tbl_news_categories';

//Options line for comments, encoding and character set
$options = array('comment' => 'The table contains the categories under which news stories can be placed', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

// Fields
$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32,
		'notnull' => 1
    ),
	'categoryname' => array (
		'type' => 'text',
		'length' =>50,
		'notnull' => 1
	),
	'categoryorder' => array (
		'type' => 'integer',
		'length' => 10
	)
);
//create other indexes here...
//create other indexes here...
$name = 'tbl_news_categories_idx';

$indexes = array(
                'fields' => array(
                	'categoryname' => array(),
                	'categoryorder' => array(),
                )
        );
		



?>