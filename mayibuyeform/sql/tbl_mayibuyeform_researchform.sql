<?php
//5ive definition
$tablename = 'tbl_mayibuyeform_researchform';

//Options line for comments, encoding and character set
$options = array('comment' => 'table for researchform','collate' =>'utf8_general_ci','character_set' =>'utf8');

$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32
		),
      'userid'=> array(
	        'type' => 'text',
		'length' => 5
		),
                          
        'date'=>array(
                    'type'=>'text',
                    'length'=>150
                    ),

       'name'=>array(
                    'type'=>'text',
                    'length'=>50
                    ),

        'telno'=> array(
                     'type' =>'text',
                     'length'=>10
                      ),
          
        'faxno'=>array(
                    'type'=>'text',
                    'length'=>30
                                     
                     ),
                    
        'emailaddress'=>array(
                    'type'=>'text',
                    'length'=>150
                    ),

       'nameofsignotory'=>array(
                    'type'=>'text',
                    'length'=>50
                    ),

        'jobtitle'=> array(
                     'type' =>'text',
                     'length'=>10
                      ),
          
        'nameoforganization'=>array(
                    'type'=>'text',
                    'length'=>30
			),


         'postaladdress'=>array(
			'type'=>'text',
			'length'=>30
		),

	'physicaladdress'=>array(
			'type'=>'text',
			'length'=>30
		),

	'vatnum'=>array(
			'type'=>'text',
			'length'=>30

		),

	'jobno'=>array(
			'type'=>'text',
			'length'=>30

		),

	'telephone'=>array(
			'type'=>'text',
			'length'=>30
		),
			
			
	'faxnumber'=>arry(
			'type'=>'text',
			'length'=>30
		),

	'email'=array(
			'type'=>'text',
			'length'=>30
		),

	'nameofresgn'=>array(
			'type'=>'text',
			'length'=>30
		),

	'jobtitle2'=>arrya(
			'type'=>'text',
			 'length'=>30
		),
		
          'organizationname'=>array(
			'type'=>'text',
			'length'=>30
		),

          'postalddress2'=>array(
			'type'=>'text',
			'length'=>30
		),

	'tell'=>array(
			'type'=>'text',
			'length'=>30
		),


	'fax'=>array(
			'type'=>'text'
			'length'=>30
		),

	'studentno'=>array(
			'type'=>'text'
			'length'=>30
		),
		
	'staffno'=>array(
			'type'=>'text'
			'length'=>30
		),

	'collection'=>array(
			'type'=>'text'
			'length'=>30
		),	

	'imageaudio'=>array(
			'type'=>'text'  
			'length'=>30
		),         
                     

	'projectname'=>array(
			'type'=>'text'
			'length'=>30
		), 

           'timeperido'=>array(
			'type'=>'text'
			'length'=>30
			
          
                     ));
?>
