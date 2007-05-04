<?php
/*
  $sqldata[]="CREATE TABLE tbl_team (
  id varchar(32) NOT NULL default '',
  name varchar(32) NOT NULL default '',
  homeGround varchar(32) NOT NULL default '',
  coach varchar(32) NOT NULL default '',
  motto varchar(32),
  logofile varchar(32) NOT NULL default '' ,
  sportId varchar(32) NOT NULL default '',
  PRIMARY KEY(id),
  FOREIGN KEY (sportId)
  REFERENCES tbl_sports(id) 
  ON DELETE CASCADE
  ON UPDATE CASCADE  
  
)TYPE=InnoDB
";
*/

//5ive definition
$tablename = 'tbl_team';

//Options line for comments, encoding and character set
$options = array('comment' => 'Table to hold information about teams', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

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
	'homeGround' => array(
		'type' => 'text',
		'length' => 32
		),
	
	'motto' => array(
		'type' => 'text',
		'length'=> 32
		),
	'logofile' => array(
		'type' => 'text',
		'length' => 32
		),
	'sportId' => array(
		'type' => 'text',
		'length' => 32
		)
	);
	
// create other indexes here...
$name = 'team_index';

$indexes = array(
                'fields' => array(
					'sportId' => array(),
                ),
        );
?>