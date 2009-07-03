<?php
	$tablename="tbl_contactdetails";
	$options = array('comment' => 'test', 'collate' => 'utf8_general_ci', 'character_set' => 'utf8');


	//field
	$fields=array(
			 'id' => array(
        				'type' => 'text',
        				'length' => '32',
        			      ),

			"academicname"=>array(
					    "type"=>"text",
					    "length"=>"40" 
					  ),
			"schoolname"=>array(
					    "type"=>"text",
					    "length"=>"40"
					 ),
			"headsign"=>array(
                                            "type"=>"text",
                                            "length"=>"40"
                                         ),
			"telnum"=>array(
                                            "type"=>"text",
                                            "length"=>"40"
                                         ),
			"emailadd"=>array(
                                            "type"=>"text",
                                            "length"=>"40"
                                         ),
			"courseId"=>array(
                                            "type"=>"text",
                                            "length"=>"40"
                                         )
		      );
	
?>
