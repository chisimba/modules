<?php
/**
    * creates database table to store most recent conversions for future reference
    *
    * @author Nonhlanhla Gangeni <2539399@uwc.ac.za>
    * @package convertions
    * @copyright UWC 2007
    * @filesource
    */
//Chisimba definition
$tablename = 'tbl_conversions';

//Options line for comments, encoding and character set
$options = array('comment' => 'Used to store recently converted values ', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

$fields = array(
    'id' => array(
		'type' => 'integer',
		'length' => 11
    ),
	'inputvalue' => array(
		'type' => 'integer',
		'length' => 11
	),
	'convertfrom' => array(
		'type' => 'text',
		'length' => 255
	),
	'convertto' => array(
		'type' => 'text',
		'length' => 255
	),
	'resultantvalue' => array(
		'type' => 'integer',
		'length' => 11	
	),	
	'updated' => array(
	'type' => 'timestamp'
	),
	'created_by' => array(
		'type' => 'text',
      'length' => 32,
	),
    'created_by_alias' => array(
		'type' => 'text',
   	'length' => 100
	),
    'modified' => array(
		'type' => 'timestamp',
	),
	 'modified_by' => array(
		'type' => 'integer',
      'length' => 11,
      'unsigned' => TRUE,
   ),   
);
?>
