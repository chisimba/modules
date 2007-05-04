<?php
/*
  $sqldata[]="CREATE TABLE tbl_player (
  id varchar(32) NOT NULL default '',
  name varchar(32) NOT NULL default '',
  dateOfBirth date,
  team varchar(50) not null default '',
  country varchar(32) NOT NULL default '',
  sportId varchar(50) NOT NULL default '', 
  playerimage varchar(50) NOT NULL default '',
  position varchar(32) NOT NULL default '',
  updated TIMESTAMP (14) NOT NULL,
  PRIMARY KEY(id),
  FOREIGN KEY (sportId)
  REFERENCES tbl_sports(id) 
  ON DELETE CASCADE
  ON UPDATE CASCADE  
  
)TYPE=InnoDB
";
*/


//5ive definition
$tablename = 'tbl_player';

//Options line for comments, encoding and character set
$options = array('comment' => 'Table to hold the players information', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

//
$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32,
		'notnull' => 1
		),
	'name' => array(
		'type' => 'text',
		'length' => 32
		),
	'dateOfBirth' => array(
		'type' => 'timestamp'
		),
	'team' => array(
		'type' => 'text',
		'length' => 32
		),
	'country' => array(
		'type' => 'text',
		'length' => 32
		),
	'sportId' => array(
		'type' => 'text',
		'length' => 32
		),
	'playerimage' => array(
		'type' => 'text',
		'length' => 32
		),
	'position' => array(
		'type' => 'text',
		'length' => 32
		),
	'updated' => array(
		'type' => 'timestamp'
		)
	);
	
// create other indexes here...
$name = 'player_index';

$indexes = array(
                'fields' => array(
                    'sportId' => array()
                )
        );
?>