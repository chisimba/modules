<?php
/*
  $sqldata[]="CREATE TABLE tbl_sportsnews (
  id varchar(32) NOT NULL default '',
  news text,
  creator varchar(50) NOT NULL default '',
  teamId varchar(32) not NULL default '',
  modifiedBy varchar(30) NOT NULL default '',
  sportId varchar(50) not null,
  dateCreated timestamp,  
  updated timestamp,
  PRIMARY KEY(id),
  FOREIGN KEY (teamId)
  REFERENCES tbl_team(id)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
  FOREIGN KEY (creator)
  REFERENCES tbl_users(userId) 
  ON DELETE CASCADE
  ON UPDATE CASCADE,
  FOREIGN KEY (sportId)
  REFERENCES tbl_sports(id)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
  FOREIGN KEY (modifiedBy)
  REFERENCES tbl_users (userId)
  ON DELETE CASCADE
  ON UPDATE CASCADE
  
  
)TYPE=InnoDB
";
*/

//5ive definition
$tablename = 'tbl_sportsnews';

//Options line for comments, encoding and character set
$options = array('comment' => 'Table to hold the news for each sport', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

//
$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32,
		'notnull' => 1
		),
	'news' => array(
		'type' => 'clob'
		),
	'creator' => array(
		'type' => 'text',
		'length' => 32
		),
	'teamId' => array(
		'type' => 'text',
		'length' => 32
		),
	'sportId' => array(
		'type' => 'text',
		'length' => 32
		),
	'modifiedBy' => array(
		'type' => 'text',
		'length' => 32
		),
	'dateCreated' => array(
		'type' => 'timestamp'
		),
	'updated' => array(
		'type' => 'timestamp'
		)
	);
	
// create other indexes here...
$name = 'sportsnews_index';

$indexes = array(
                'fields' => array(
					'teamId' => array(),
                    'modifiedBy' => array(),
					'creator' => array(),
                    'sportId' => array(),
                ),
        );
?>