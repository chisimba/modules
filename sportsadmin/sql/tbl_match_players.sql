<?php
/*
  $sqldata[]="CREATE TABLE tbl_match_players (
  id varchar(32) NOT NULL default '',
  teamId varchar(32) NOT NULL default '',
  playerId varchar(32) NOT NULL default '',
  tournamentId varchar(32), 
  fixtureId varchar (32) NOT NULL default '',
  goals int(32),
  sportId varchar(32), 
  PRIMARY KEY(id),
  position varchar(32),
  FOREIGN KEY (sportId)
  REFERENCES tbl_sports(id) 
  ON DELETE CASCADE
  ON UPDATE CASCADE,
  FOREIGN KEY (playerId)
  REFERENCES tbl_player(id) 
  ON DELETE CASCADE
  ON UPDATE CASCADE,
  FOREIGN KEY(fixtureId)
  REFERENCES tbl_fixtures(id)
  ON DELETE CASCADE
  ON UPDATE CASCADE  
  
)TYPE=InnoDB";
*/

//5ive definition
$tablename = 'tbl_match_players';

//Options line for comments, encoding and character set
$options = array('comment' => 'Table to hold the match players information', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

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
	'fixtureId' => array(
		'type' => 'text',
		'length' => 32
		),
	'goals' => array(
		'type' => 'integer',
		'length' => 2
		),
	'sportId' => array(
		'type' => 'text',
		'length' => 32
		)
	);
	
// create other indexes here...
$name = 'match_players_index';

$indexes = array(
                'fields' => array(
                    'id' => array(),
					'playerId' => array(),
                    'fixtureId' => array(),
                    'sportId' => array(),
                ),
        );
?>