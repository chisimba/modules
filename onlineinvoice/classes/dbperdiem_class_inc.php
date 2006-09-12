<?php
/**
  *create a class that contains functions to manipulate data in db table tbl_pierdiem
  *  
  */
   
   class dbperdiem extends dbTable{
      
      var $bval = 0.25;
      var $lval = 0.30;
      var $dval = 0.45;
      var $total  = 0;
	      
      /**
        * Constructor of the dbInvoice class
      */   
      function init()
      {
  	       parent::init('tbl_pierdiem');
      }
    
    /**
      *function to add invoice dates to the db
      */         
  	
       function addperdiem($perdiemdetails)
       {
            $results = $this->insert($perdiemdetails);
            return $results;
       }
       
       /**
        *fucntion used to calculate the breakfast, lunch, dinner rate of the traveler
        *multiplies each rate by a %value 
        *receives 3parameters ...rates user enters
        *returns the total for the expenses         
        */                               
       function calucrate($brate,$lrate,$drate)
       {
            return  $total  = (($brate * $bval) + ($lrate * $lval) + ($drate * $dval));
       }   // $total;
  }
?>
