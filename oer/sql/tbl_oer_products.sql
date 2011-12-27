<?php

//define table
$tablename = 'tbl_oer_products';
$options = array('comment'=>'Table to store Products','collate'=>'utf8_general_ci','character_set'=>'utf8');

//define fields
$fields = array(
		'id' => array('type' => 'text','length' => 32,'not null'),
                'parent_id' => array('type' => 'text','length' => 32),
                'title'=>array('type'=>'text','length'=>255,'not null'),
                'alternative_title'=>array('type'=>'text','length'=>255,'not null'),
                'resource_type' =>array('type' =>'text','length'=>32),
                'date' => array('type' => 'timestamp'),
                'language'=>array('type' => 'text','length' => 32),
                'translation_of'=>array('type' => 'text','length' => 32),
                'description'=>array('type'=>'text'),
                'abstract'=>array('type'=>'text'),
                'table_of_contents'=>array('type'=>'text'),
                'creator' => array('type' => 'text','length' => 32),
                'contacts'=>array('type'=>'text'),  //unesco contacts
                'publisher' =>array('type' =>'text','length'=>255),
                'other_contributors' => array ('type' => 'text'),
                'format'=> array('type' => 'text','length' => 32),
                'coverage' => array('type' => 'text', 'length'=>255),
                'rights' => array('type' => 'text', 'length'=>512),
                'rights_holder' => array('type' => 'text', 'length'=>255),
                'provenance' => array('type' => 'text', 'length'=>512),
                'relation' =>array('type' =>'text','length'=>32),
                'relation_type' =>array('type' =>'text','length'=>32),
                'status' => array('type' => 'text', 'length'=>255),
                'thumbnail'=>array('type'=>'text','length'=>512),
                'deleted' => array('type' => 'integer','length' => 1,'default' => '0'),
                'is_accredited' => array('type'=>'text','length'=>4),
                'accreditation_body' => array('type'=>'text','length'=>255),
                'accreditation_date' => array('type'=>'text','length'=>255)
 		);
?>