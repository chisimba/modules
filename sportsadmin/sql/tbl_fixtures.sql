<?php
/*
  $sqldata[]="CREATE TABLE tbl_fixtures (
  id varchar(32) NOT NULL default '',
  team_A varchar(32) NOT NULL default '',
  team_B varchar(32) NOT NULL default '',
  tournamentId varchar(32) NOT NULL default '',
  place varchar(32) NOT NULL default '',
  matchDate datetime,
  sportId varchar(32),
   updated TIMESTAMP ( 14 ) NOT NULL,
  PRIMARY KEY(id),
  FOREIGN KEY (tournamentId)
  REFERENCES tbl_tournament(id) 
  ON DELETE CASCADE
  ON UPDATE CASCADE,
  FOREIGN KEY(team_A)
  REFERENCES tbl_team(id) 
  ON DELETE CASCADE 
  ON UPDATE CASCADE,
  FOREIGN KEY(team_B)
  REFERENCES tbl_team(id) 
  ON DELETE CASCADE 
  ON UPDATE CASCADE,
  FOREIGN KEY(sportId)
  REFERENCES tbl_sport(id)
  ON DELETE CASCADE
  ON UPDATE CASCADE  
  
)TYPE=InnoDB
";
*/

//5ive definition
$tablename = 'tbl_fixtures';

//Options line for comments, encoding and character set
$options = array('comment' => 'Table to hold the fixtures', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

//
$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32,
		'notnull' => 1
		),
	'team_A' => array(
		'type' => 'text',
		'length' => 32,
		'notnull'=> 1
		),
	'team_B' => array(
		'type' => 'text',
		'length' => 32,
		'notnull' => 1
		),
	'tournamentId' => array(
		'type' => 'text',
		'length' => 32,
		'notnull' => 1
		),
	'place' => array(
		'type' => 'text',
		'length' => 32
		),
	'matchDate' => array(
		'type' => 'timestamp'
		),
	'sportId' => array(
		'type' => 'text',
		'length' => 32
		),
	'updated' => array(
		'type' => 'timestamp'
		)
	);
	
// create other indexes here...
$name = 'fixtures_index';

$indexes = array(
                'fields' => array(
					'tournamentId' => array(),
                    'team_A' => array(),
					'team_B' => array(),
                    'sportId' => array(),
                ),
        );
?>