<?php
//table name
$tablename = 'tbl_conference_regconfig';

//options array
$options = array('comment' => 'Conference register','collate' => 'utf8_general_ci', 'character_set' => 'utf8');

//$fields
$fields = array(

    	'id'=> array(
		'type'=> 'text',
		'length'=> 32,	
		'notnull'=> TRUE
		),	
	'useOrganisation'=> array(
		'type' => 'text',
		'length' => 2,
		'notnull' => TRUE,
		'default' => '1'
		), 
	'useNameBadge' => array(
		'type'=> 'text',
		'length' => 2,
		'notnull' => TRUE,
		'default' => '1'
		),
	'useFlights' => array(
		'type' => 'text',
		'length' => 2,
		'notnull'=> TRUE,	
		'default' => 1
		),
	'useTransfers' => array(
		'type' => 'text',
		'length' => 2,
		'notnull'=> TRUE,	
		'default' => 1
		),
	'useCarhire' => array(
		'type' => 'text',
		'length' => 2,
		'notnull' => TRUE,
		'default' => 1
		),
	'startReg' => array(	
		'type' => 'timestamp'
		),
	'endEarlyBird' => array(
		'type' => 'timestamp'
		),
	'endReg' => array(
		'type' => 'timestamp'
		),
	'earlyBirdFee' => array (
		'type' => 'text',
		'length' => 10,
		'notnull' => FALSE
		),
	'earlyBirdForeign' => array (
		'type' => 'text',
		'length'=> 10,
		'notnull' => FALSE
   		),
	
	'regFee' => array(
		'type' => 'text',
		'lenght' => 10,
		'notnull'=> FALSE
		),
	'regFeeForeign' => array (
		'type' => 'text',
		'length' => 10,
		'notnull' => FALSE
		),
	'currency1' => array(
		'type' => 'text',
		'length' => 15,
		'notnull' => 1,
		'default' => 'usdollar'
		),
	'currency2' => array(
		'type' => 'text',
		'length' => 15,
		'notnull' => 1,
		'default' => 'usdollar'
		),
	'accountName' => array(
		'type'=> 'text',
		'length'=> 100,
		'notnull' => FALSE
		),
	'accountNum' => array(
		'type' => 'text',
  		'length' => 20,
		'notnull'=> FALSE
		),
	'bank' => array(
		'type' => 'text',
		'length' => 100,
		'notnull' => FALSE
		),
	'branch' => array(
		'type' =>'text',
		'length' => 100,
		'notnull'=> FALSE
		),
	'branchCode' => array(
		'type' => 'text',
		'length' => 20,
		'notnull' => FALSE
		),
	'swiftcode' => array(
		'type' => 'text',
		'length' => 20,
		'notnull' => FALSE
		),
	'contextCode' => array(
		'type' => 'text',
		'length'=> 100,
		'notnull' => FALSE
		),
	'createId' => array(
		'type' => 'text',
		'length' => 32,
		'notnull' => TRUE
		),
 	'modifierId'=> array(
		'type' => 'text',
		'length' => 32,
		'notnull'=> FALSE
		),
	'dateCreated' => array(
		'type' => 'timestamp',
		'notnull'=> TRUE
		),
	'dateModified' => array(
		'type' => 'timestamp',
		'notnull' => TRUE
		),
	'updated' => array(
		'type' => 'timestamp',
		'length' => 14,
		'notnull'=> TRUE
		),
	
);

?>