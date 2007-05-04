<?php
/*
  $sqldata[]="CREATE TABLE tbl_match_info (
  id varchar(32) NOT NULL default '',
  teamId varchar(32) NOT NULL default '',
  playerId varchar(32) NOT NULL default '',
  tournamentId varchar(32), 
  goals int(32),
  sportId varchar(32),
  yellowCards varchar(32),
  redCards varchar(32),
  injury varchar(32),
  PRIMARY KEY(id),
  position varchar(32),
  FOREIGN KEY (sportId)
  REFERENCES tbl_sports(id) 
  ON DELETE CASCADE
  ON UPDATE CASCADE,
  FOREIGN KEY (playerId)
  REFERENCES tbl_player(id) 
  ON DELETE CASCADE
  ON UPDATE CASCADE
 
  
)TYPE=InnoDB
";
*/

//5ive definition
$tablename = 'tbl_match_info';

//Options line for comments, encoding and character set
$options = array('comment' => 'Table to hold the match information', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

//
$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32,
		'notnull' => 1
		),
	'teamId' => array(
		'type' => 'text',
		'length' => 32,
		'notnull'=> 1
		),
	'playerId' => array(
		'type' => 'text',
		'length' => 32,
		'notnull' => 1
		),
	'tournamentId' => array(
		'type' => 'text',
		'length' => 32,
		'notnull' => 1
		),
	'goals' => array(
		'type' => 'integer',
		'length' => 2
		),
	'sportId' => array(
		'type' => 'text',
		'length' => 32
		),
	'yellowCards' => array(
		'type' => 'text',
		'length' => 32
		),
	'redCards' => array(
		'type' => 'text',
		'length' => 32
		),
	'injury' => array(
		'type' => 'text',
		'length' => 32
		),
	'position' => array(
		'type' => 'text',
		'length' => 32
		)
	);
	
// create other indexes here...
$name = 'match_info_index';

$indexes = array(
                'fields' => array(
					'playerId' => array(),
                    'sportId' => array(),
                ),
        );
?>