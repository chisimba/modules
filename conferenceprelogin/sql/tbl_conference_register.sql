<?php
//table name
$tablename = 'tbl_conference_register';

//options array
$options = array('comment' => 'Conference register','collate' => 'utf8_general_ci', 'character_set' => 'utf8');

//$fields
$fields = array(
	
	'id'=> array(
		'type'=> 'text',
		'length'=> 32,
		'notnull'=> TRUE
		),
	'initials'=> array (
		'type' => 'text',
		'length' => 5,
		'notnull'=> FALSE
		),
	'organisation' => array(
		'type'=>'text',
		'length'=> 120,
		'notnull' => FALSE
		),
	'nameBadge'=> array(
		'type' => 'text',
		'length' => 150,
		'notnull'=> FALSE
		),
	'tel' => array(
		'type' => 'text',
		'length' => 20,
		'notnull' => FALSE
		),
	'fax' => array(
		'type' => 'text',
		'length' => 20,
		'notnull' => FALSE
		),
	'regType' => array(
		'type' => 'text',
		'length' => 20,
		'notnull' => TRUE,
		'default' => 'registration'
		),
	'flights' => array(
		'type' => 'text',
		'length' => 4,
		'notnull'=> TRUE,
		'default' => 'no'
		),
	'transfers' => array(
		'type' => 'text',
		'length' => 4,
		'notnull' => TRUE,
		'default' => 'no'
		),
	'carhire' => array(
		'type' => 'text',
		'length' => 4,
		'notnull' => TRUE,
		'default'=> 'no'
		),
	'paid' => array(
		'type' => 'text',
		'length' => 4,
		'notnull' => TRUE,
		'default' => 'no'
		),
	'invoiced' => array(
		'type' => 'text',
		'length' => 4,
		'notnull' => TRUE,
		'default' => 'no'
		),
	'status' => array(
		'type' => 'text',
		'length' => 20,
		'notnull'=> TRUE,
		'default'=> 'register'
		),
	'reason' => array(
		'type' => 'text',
		'length' => 255,
		'notnull' => FALSE
		),
	'contextCode' => array(
		'type' => 'text',
		'lenght' => 50,
		'notnull' => FALSE
		),
	'userId' => array(
		'type' => 'text',
		'length' => 32,
		'notnull'=> FALSE
		),
	'modifier' => array(
		'type' => 'text',
		'length' => 32,
		'notnull' => FALSE
		),
	'dateCreated'=> array(
		'type'=> 'timestamp',
		'notnulll' => TRUE
		),
	'dateModified'=> array(
		'type' => 'timestamp',
		'notnull' => FALSE
		),
	'updated'=> array(
		'type' => 'timestamp',
		'length'=> 14,
		'notnull' => TRUE
		),
	);
    
    
    
 /*   PRIMARY KEY(id),
    CONSTRAINT conferenceUserId FOREIGN KEY (userId) REFERENCES tbl_users (userId)
    ON DELETE CASCADE ON UPDATE CASCADE
)TYPE=INNODB comment='Table extending tbl_users for additional conference delegate information;'";*/
?>
