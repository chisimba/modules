<?php
/*
  $sqldata[]="CREATE TABLE tbl_playerdata(
  id varchar(32) NOT NULL default '',
  playerId varchar(32) NOT NULL default '',
  event text(32) NOT NULL default '',
  enteredBy vaRchar (32) NOT NULL default '',
  updated datetime,
  dateEntered timestamp,
  updatedBy varchar(32) NOT NULL default '',
  PRIMARY KEY(id),
  FOREIGN KEY (playerId)
  REFERENCES tbl_player(id) 
  ON DELETE CASCADE
  ON UPDATE CASCADE,
  FOREIGN KEY (updatedBy) 
  REFERENCES tbl_users(userId)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
  FOREIGN KEY (enteredBy) 
  REFERENCES tbl_users(userId)
  ON DELETE CASCADE
  ON UPDATE CASCADE    
  
)TYPE=InnoDB
";
*/

//5ive definition
$tablename = 'tbl_playerdata';

//Options line for comments, encoding and character set
$options = array('comment' => 'Table to hold the players information', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

//
$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32,
		'notnull' => 1
		),
	'playerId' => array(
		'type' => 'text',
		'length' => 32,
		'notnull' => 1
		),
	'event' => array(
		'type' => 'clob'
		),
	'enteredBy' => array(
		'type' => 'text',
		'length' => 32
		),
	'dateEntered' => array(
		'type' => 'timestamp'
		),
	'updatedBy' => array(
		'type' => 'text',
		'length' => 32
		),
	'updated' => array(
		'type' => 'timestamp'
		)
	);
	
// create other indexes here...
$name = 'playerdata_index';

$indexes = array(
                'fields' => array(
					'playerId' => array(),
                    'enteredBy' => array(),
                    'updatedBy' => array(),
                ),
        );
?>