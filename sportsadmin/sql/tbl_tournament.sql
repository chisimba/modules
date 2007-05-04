<?php
  /*
  $sqldata[]="CREATE TABLE tbl_tournament(
  id varchar(32) NOT NULL,
  name varchar(32) NOT NULL,
  sponsorName varchar(32),
  creator varchar(25) NOT NULL, 
  startDate date,
  endDate date,
  sportId varchar(32),
  updated TIMESTAMP (14) NOT NULL,
  PRIMARY KEY(id),
  CONSTRAINT `FK_sports_userId` FOREIGN KEY (`creator`) REFERENCES `tbl_users` (`userId`)
  ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_sports_sportId` FOREIGN KEY (`sportId`) REFERENCES `tbl_sports` (`id`)
  ON DELETE CASCADE ON UPDATE CASCADE
  
  
)TYPE=InnoDB
";
*/


//5ive definition
$tablename = 'tbl_tournament';

//Options line for comments, encoding and character set
$options = array('comment' => 'Table to hold the fixtures', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

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
	'sponsorName' => array(
		'type' => 'text',
		'length' => 32
		),
	'creator' => array(
		'type' => 'text',
		'length' => 32,
		),
	'startDate' => array(
		'type' => 'timestamp'
		),
	'endDate' => array(
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
$name = 'tournament_index';

$indexes = array(
                'fields' => array(
					'creator' => array(),
                    'sportId' => array(),
                ),
        );
?>