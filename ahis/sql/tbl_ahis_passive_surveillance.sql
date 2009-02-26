<?php

$tablename = 'tbl_ahis_passive_surveillance';

$options = array('comment'=> 'table to store passive surveillance data','collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32,
        'notnull' => TRUE
		),
	'reporterid' => array(
		'type' => 'text',
		'length' => 32,
        'notnull' => TRUE
		),
	'territoryid' => array(
		'type' => 'text',
		'length' => 32,
        'notnull' => TRUE
		),
	'statusid' => array(
		'type' => 'text',
		'length' => 32,
        'notnull' => TRUE
		),
	'qualityid' => array(
		'type' => 'text',
		'length' => 32,
        'notnull' => TRUE
		),
	'speciesid' => array(
		'type' => 'text',
		'length' => 32,
        'notnull' => TRUE
		),
	'ageid' => array(
		'type' => 'text',
		'length' => 32,
        'notnull' => TRUE
		),
	'sexid' => array(
		'type' => 'text',
		'length' => 32,
        'notnull' => TRUE
		),
	'productionid' => array(
		'type' => 'text',
		'length' => 32,
        'notnull' => TRUE
		),
	'controlmeasureid' => array(
		'type' => 'text',
		'length' => 32,
        'notnull' => TRUE
		),
	'basisofdiagnosisid' => array(
		'type' => 'text',
		'length' => 32,
        'notnull' => TRUE
		),
    'refno' => array(
		'type' => 'integer',
		'length' => 4,
        'notnull' => TRUE,
        'default' => 0
		),
	'susceptible' => array(
		'type' => 'integer',
		'length' => 4,
        'notnull' => TRUE,
        'default' => 0
		),
	'cases' => array(
		'type' => 'integer',
		'length' => 4,
        'notnull' => TRUE,
        'default' => 0
		),
	'deaths' => array(
		'type' => 'integer',
		'length' => 4,
        'notnull' => TRUE,
        'default' => 0
		),
	'vaccinated' => array(
		'type' => 'integer',
		'length' => 4,
        'notnull' => TRUE,
        'default' => 0
		),
	'slaughtered' => array(
		'type' => 'integer',
		'length' => 4,
        'notnull' => TRUE,
        'default' => 0
		),
	'destroyed' => array(
		'type' => 'integer',
		'length' => 4,
        'notnull' => TRUE,
        'default' => 0
		),
	'production' => array(
		'type' => 'integer',
		'length' => 4,
        'notnull' => TRUE,
        'default' => 0
		),
	'newcases' => array(
		'type' => 'integer',
		'length' => 4,
        'notnull' => TRUE,
        'default' => 0
		),
	'recovered' => array(
		'type' => 'integer',
		'length' => 4,
        'notnull' => TRUE,
        'default' => 0
		),
	'prophylactic' => array(
		'type' => 'integer',
		'length' => 4,
        'notnull' => TRUE,
        'default' => 0
		),
	'location' => array(
		'type' => 'text',
		'length' => 64,
        'notnull' => TRUE
		),
	'latitude' => array(
		'type' => 'text',
		'length' => 64,
        'notnull' => TRUE
		),
	'longitude' => array(
		'type' => 'text',
		'length' => 64,
        'notnull' => TRUE
		),
	'disease' => array(
		'type' => 'text',
		'length' => 64,
        'notnull' => TRUE
		),
	'causitive' => array(
		'type' => 'text',
		'length' => 64,
        'notnull' => TRUE
		),
	'vaccinesource' => array(
		'type' => 'text',
		'length' => 64,
        'notnull' => TRUE
		),
	'vaccinebatch' => array(
		'type' => 'text',
		'length' => 64,
        'notnull' => TRUE
		),
	'reportdate' => array(
		'type' => 'date',
        'notnull' => TRUE
		),
	'prepareddate' => array(
		'type' => 'date',
        'notnull' => TRUE
		),
	'ibardate' => array(
		'type' => 'date',
        'notnull' => TRUE
		),
	'dvsdate' => array(
		'type' => 'date',
        'notnull' => TRUE
		),
	'reporteddate' => array(
		'type' => 'date',
        'notnull' => TRUE
		),
	'vetdate' => array(
		'type' => 'date',
        'notnull' => TRUE
		),
	'occurencedate' => array(
		'type' => 'date',
        'notnull' => TRUE
		),
	'diagnosisdate' => array(
		'type' => 'date',
        'notnull' => TRUE
		),
	'investigationdate' => array(
		'type' => 'date',
        'notnull' => TRUE
		),
	'vaccinemanufacturedate' => array(
		'type' => 'date',
        'notnull' => TRUE
		),
	'vaccineexpirydate' => array(
		'type' => 'date',
        'notnull' => TRUE
		),
	'vaccinetested' => array(
		'type' => 'boolean',
        'notnull' => TRUE
		),
	'remarks' => array(
		'type' => 'clob'
		)
	
    );
//create other indexes here...

$name = 'index_tbl_ahis_passive_surveillance';

?>