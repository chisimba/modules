/*<?
$sqldata[]="CREATE TABLE tbl_coursemetadata (
  id VARCHAR(32) NOT NULL ,
  provider varchar(255),
  url varchar(255),
  enterdate datetime,
  oai_identifier varchar(255),
  oai_set varchar(255),
  datestamp datetime,
  deleted enum('false', 'true') NOT NULL,
  dc_title TEXT NULL ,
  dc_subject TEXT NULL ,
  dc_description TEXT NULL,
  dc_type VARCHAR(255) NULL,
  dc_source VARCHAR(255) NULL,
  dc_sourceurl VARCHAR(255) NULL,
  dc_relationship VARCHAR(255) NULL,
  dc_coverage VARCHAR(255) NULL,
  dc_creator VARCHAR(255) NULL,
  dc_publisher VARCHAR(255) NULL,
  dc_contributor VARCHAR(255) NULL,
  dc_rights VARCHAR(255) NULL,
  dc_date VARCHAR(255) NULL,
  dc_format VARCHAR(255) NULL,
  dc_identifier VARCHAR(255) NULL,
  dc_language VARCHAR(255) NULL,
  dc_audience VARCHAR(255) NULL,
  updated TIMESTAMP(14) NOT NULL,
  PRIMARY KEY(id)))TYPE=INNODB;";  
?>*/
<?php
$tablename = 'tbl_coursemetadata';

$options = array('collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32
		),
	'provider' => array(
		'type' => 'text',
		'length' => 225
		),
	'url' => array(
		'type' => 'text',
		'length' => 225
		),
	'enterdate' => array(
		'type' => 'date'
		),
    'oai_identifier' => array(
		'type' => 'text',
		'length' => 255
		),
    'oai_set' => array(
		'type' => 'text',
		'length' => 225
		),
	'datestamp' => array(
		'type' => 'date'
		),
	'deleted'=> array(
		'type' => 'smallint'
		), 
		

 	 'dc_title' => array(
		'type' => 'text',
		),
	'dc_subject' => array(
		'type' => 'text',
		),
 	'dc_description' => array(
		'type' => 'text',
		),
 	 'dc_type' => array(
		'type' => 'text',
		'length' => 255
		),

  	' dc_source' => array(
		'type' => 'text',
		'length' => 255
		),
  	'dc_sourceurl' => array(
		'type' => 'text',
		'length' => 255
		),
  	'dc_relationship' => array(
		'type' => 'text',
		'length' => 255
		),
   	'dc_coverage' => array(
		'type' => 'text',
		'length' => 255
		),
	'dc_creator' => array(
		'type' => 'text',
		'length' => 255
		),
	'dc_publisher' => array(
		'type' => 'text',
		'length' => 255
		),
	'dc_contributor' => array(
		'type' => 'text',
		'length' => 255
		),
	'dc_rights' => array(
		'type' => 'text',
		'length' => 255
		),
	'dc_date' => array(
		'type' => 'text',
		'length' => 255
		),
	'dc_format' => array(
		'type' => 'text',
		'length' => 255
		),
	'dc_identifier' => array(
		'type' => 'text',
		'length' => 255
		),
	'dc_language' => array(
		'type' => 'text',
		'length' => 255
		),
	'dc_audience' => array(
		'type' => 'text',
		'length' => 255
		),
	
	'updated' => array(
		'type' => 'timestamp'
		'lenght'=> 14
		)
	);
?>
