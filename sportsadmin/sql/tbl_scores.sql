<?php
  /*
  $sqldata[]="CREATE TABLE tbl_scores(
  id varchar(32) NOT NULL default '',
  sportId varchar(32),
  playerId varchar(32) NOT NULL default '',
  fixtureId varchar(32),
  tournamentId varchar(32) default '',
  teamId varchar(32) NOT NULL default '',
  time varchar(32),
  enteredBy varchar(32) NOT NULL default '',
  creationDate date,
  updatedBy varchar(32),
  PRIMARY KEY(id),
  FOREIGN KEY (sportId)
  REFERENCES tbl_sports(id) 
  ON DELETE CASCADE
  ON UPDATE CASCADE,
  FOREIGN KEY (tournamentId)
  REFERENCES tbl_tournament(id) 
  ON DELETE CASCADE
  ON UPDATE CASCADE,
  FOREIGN KEY (fixtureId)
  REFERENCES tbl_fixture(id)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
  foreign key (enteredBy)
  REFERENCES tbl_user 
  ON DELETE CASCADE
  ON UPDATE CASCADE,
  FOREIGN KEY (teamId)
  REFERENCES tbl_team(id)
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
$tablename = 'tbl_scores';

//Options line for comments, encoding and character set
$options = array('comment' => 'Table to hold the scores', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

//
$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32,
		'notnull' => 1
		),
	'sportId' => array(
		'type' => 'text',
		'length' => 32
		),
	'playerId' => array(
		'type' => 'text',
		'length' => 32,
		'notnull'=> 1
		),
	'fixtureId' => array(
		'type' => 'text',
		'length' => 32
		),
	'tournamentId' => array(
		'type' => 'text',
		'length'=> 32
		),
	'teamId' => array(
		'type' => 'text',
		'length' => 32
		),
	'matchDate' => array(
		'type' => 'timestamp'
		),
	'time' => array(
		'type' => 'text',
		'length' => 32
		),
	'enteredBy' => array(
		'type' => 'text',
		'length' => 32
		),
	'creationDate' => array(
		'type' => 'timestamp'
		),
	'updatedBy' => array(
		'type' => 'text',
		'length' => 32
		)
	);
	
// create other indexes here...
$name = 'scores_index';

$indexes = array(
                'fields' => array(
					'sportId' => array(),
                    'tournamentId' => array(),
					'fixtureId' => array(),
                    'enteredBy' => array(),
					'teamId' => array(),
                    'playerId' => array(),
                ),
        );
?>