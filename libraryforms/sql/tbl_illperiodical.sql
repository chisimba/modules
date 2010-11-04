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
                          
        'ptitleperiodical'=>array(
                    'type'=>'text',
                    'length'=>150
                    ),

       'pvolume'=>array(
                    'type'=>'text',
                    'length'=>50
                    ),

        'ppart'=> array(
                     'type' =>'text',
                     'length'=>10
                      ),
          
        'pyear'=>array(
                    'type'=>'text',
                    'length'=>30
                                     
                     ),

        'ppages'=> array(
                     'type' =>'text',
                     'length'=>5 
                        ),
          
        'pauthor'=>array(
                    'type'=>'text',
                    'length'=>150
                                   
                     ),

        'ptitlearticle'=> array(
                     'type' =>'text',
                     'length'=>150
                    
                         ),
          
        'pprof'=>array(
                    'type'=>'text',
                    'length'=>20
                     
			),
                   
         
        'paddress'=> array(
                     'type' =>'text',
                     'length'=>150
                     
			),
                   
          
        'pcell'=>array(
                    'type'=>'text',
                    'length'=>15
                    
			),

               
        'ptell'=>array(
                    'type'=>'text',
                    'length'=>20
                  
			),
           
          
        'ptellw'=>array(
                    'type'=>'text',
                    'length'=>30
                    
			),

   
        'pemailaddress'=>array(
                    'type'=>'text',
                    'length'=>30
                   ),
       
          
        'pentitynum'=>array(
                    'type'=>'text',
                    'length'=>30,
                    'notnull'=>1
                        ),
          
        'pstudentno'=>array(
                    'type'=>'text',
                    'length'=>50
                     ),
               
         
        'pcourse'=> array(
                     'type' =>'text',
                     'length'=>50
                     )
);       


?>
