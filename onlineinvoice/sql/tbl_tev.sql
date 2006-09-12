<?php
// Table Name
$tablename = 'tbl_tev';

//Options line for comments, encoding and character set
$options = array('comment' => 'The table tev - travel expense voucher is managed by the onlineinvoice module', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');

// Fields
$fields = array(
	'id' => array(
    'type' => 'text',
    'length' => 32,
    'notnull'=> 1
    ),
  'createdby'  => array(
     'type'  =>  'text',
     'length'=>  32,
    ),
  'datecreated' =>  array(
      'type'  =>  'date',
    ),
  'modifiedby'  =>  array(
      'type'    =>  'text',
      'length'  =>  32
    ),
   'datemodified' =>  array(
    'type'        =>  'date'
    ),
    'updated'     =>  array(
    'type'        =>  'date'
    ),
   'name' =>  array(
      'type'        =>  'text',
      'length'      =>  32,
      'notnull'     => 1
    ),
    'title'         =>  array(
      'type'        =>  'text',
      'length'      =>  32,
      'notnull'     => 1
    ),
    'address'   =>  array(
      'type'        =>  'text',
      'length'      =>  255,
      'notnull'     => 1
    ),
    'city'          =>  array(
      'type'        =>  'text',
      'length'      =>  32,
      'notnull'     => 1
    ),
    'province'      =>  array(
     'type'         =>  'text',
     'length'       =>  32,
     'notnull'     => 1 
    ),
    'postalcode'    =>  array(
      'type'        =>  'text',
      'length'      =>  32,
      'notnull'     => 1
      
    ),
    'country'       =>  array(
      'type'        =>  'text',
      'length'      =>  32,
      'notnull'     => 1
    ),
   'travelpurpose' => array(
   'type'   =>  'text',
   'length' =>  32,
   'notnull'     => 1
   ),         
    );





?>
//create other indexes here...

/*$name = 'ind_groups_FK';

$indexes = array(
                'fields' => array(
                	'parent_id' => array()
                )
        );

*/
