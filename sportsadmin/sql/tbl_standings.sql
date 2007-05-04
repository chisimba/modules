<?php
/*
  $sqldata[]="CREATE TABLE tbl_gameinfo(
  id varchar(32) NOT NULL default '',
  sportId varchar(32),
  tournamentId varchar(32) default '',
  teamAId varchar(32) NOT NULL default '',
  teamBId varchar(32) NOT NULL default '',
  teamAscores int(3),
  teamBscores int(3),
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
  FOREIGN KEY( teamAId)
  REFERENCES tbl_team(id)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
  FOREIGN KEY( teamBId)
  REFERENCES tbl_team(id)
  ON DELETE CASCADE
  ON UPDATE CASCADE
 
  
)TYPE=InnoDB
";
*/

//5ive definition
$tablename = 'tbl_standings';

//Options line for comments, encoding and character set
$options = array('comment' => 'Table to hold the standings', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

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
	'tournamentId' => array(
		'type' => 'text',
		'length' => 32
		),
	'teamAId' => array(
		'type' => 'text',
		'length' => 32,
		'notnull'=> 1
		),
	'teamBId' => array(
		'type' => 'text',
		'length' => 32,
		'notnull'=> 1
		),
	'teamAscores' => array(
		'type' => 'integer',
		'length' => 3
		),
	'teamBscores' => array(
		'type' => 'integer',
		'length' => 3
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
$name = 'standings_index';

$indexes = array(
                'fields' => array(
					'teamAId' => array(),
					'teamBId' => array(),
					'tournamentId' => array(),
                    'sportId' => array(),
                ),
        );
?>