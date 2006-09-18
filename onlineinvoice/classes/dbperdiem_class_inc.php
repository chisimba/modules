<?php
/**
  *create a class that contains functions to manipulate data in db table tbl_pierdiem
  *  
  */
   
   class dbperdiem extends dbTable{
      
     // var $bval = 0.25;
     // var $lval = 0.30;
     // var $dval = 0.45;
     // var $total  = 0;
	      
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
        * Fucntion used to calculate the breakfast, lunch, dinner rate of the traveler
        * 
        * @return int $total Total expenditure for the day        
        */                               
       function calculate()
       {
         //   $total = '0';
            switch($this->getParam('rates_radio')){
                case 'foreign':
                
                    $breakfastTotal = ($this->getParam('txtbreakfastRate') * 0.25) + $this->getParam('txtbreakfastRate') ;
                   // var_dump($breakfastTotal);
                    $lunchTotal = ($this->getParam('txtlunchRate') * 0.30) +  $this->getParam('txtlunchRate');
  
                    $dinnerTotal = ($this->getParam('txtdinnerRate') * 0.45) + $this->getParam('txtdinnerRate') ;
                    
                    $total = $breakfastTotal + $lunchTotal + $dinnerTotal;
                    break;
                    
                case 'domestic':
                    $total = $this->getParam('txtbreakfastRate') + $this->getParam('txtlunchRate') + $this->getParam('txtdinnerRate');
                    break;     
            }
            return  $total;
       }   // $total;
  }
?>
