<?php
//5ive definition
$tablename = 'tbl_illperiodical';

//Options line for comments, encoding and character set
$options = array('comment' => 'table for illperiodi','collate' =>'utf8_general_ci','character_set' =>'utf8');

$fields = array(
	'id' => array(
		'type' => 'text',
		'length' => 32
		),
      'userid'=> array(
	        'type' => 'text',
		'length' => 5
		),
                          
        'titleperiodical'=>array(
                    'type'=>'text',
                    'length'=>150
                    ),

       'volume'=>array(
                    'type'=>'text',
                    'length'=>50
                    ),

        'part'=> array(
                     'type' =>'text',
                     'length'=>10
                      ),
          
        'year'=>array(
                    'type'=>'text',
                    'length'=>30
                                     
                     ),

        'pages'=> array(
                     'type' =>'text',
                     'length'=>5 
                        ),
          
        'author'=>array(
                    'type'=>'text',
                    'length'=>150
                                   
                     ),

        'titlearticle'=> array(
                     'type' =>'text',
                     'length'=>150
                    
                         ),
          
        'prof'=>array(
                    'type'=>'text',
                    'length'=>20
                     
			),
                   
         
        'address'=> array(
                     'type' =>'text',
                     'length'=>150
                     
			),
                   
          
        'cell'=>array(
                    'type'=>'text',
                    'length'=>15
                    
			),

         
        'fax'=> array(
                     'type' =>'text',
                     'length'=>15
			),
          
        'tell'=>array(
                    'type'=>'text',
                    'length'=>20
                  
			),
           
          
        'tellw'=>array(
                    'type'=>'text',
                    'length'=>30
                    
			),

   
        'emailaddress'=>array(
                    'type'=>'text',
                    'length'=>30
                   ),
       
          
        'entitynum'=>array(
                    'type'=>'text',
                    'length'=>30,
                    'notnull'=>1
                        ),
          
        'studentno'=>array(
                    'type'=>'text',
                    'length'=>50
                     ),
               
         
        'course'=> array(
                     'type' =>'text',
                     'length'=>50
                     )
);       


?>
