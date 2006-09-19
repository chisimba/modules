<?php

class dbLodging extends dbTable{
  

  var $ratetotal = 0;

	/**
	* Constructor
	*/

	function init()
	{
		parent::init('tbl_lodging');
	}


	function addlodge($data)
	{
   $results = $this->insert($data); 
   return $results;
  }

	function calculodgerate()
	{
	
	   $lodgerate = $this->getSession('lodgedetails');
	   $initial =  $lodgerate[0]['cost'];
	   
      $count = count($lodgerate);
      $num = $count - 1;
      $last = $lodgerate[$num]['cost'];

  
      for($i = $initial; $i <= $last; $i = $i++){
        $ratetotal = $i + $i[$initial + 1];
      //  var_dump($ratetotal);
      }
     return $ratetotal; 

  
  }

}



?>
