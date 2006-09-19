<?php
      /**
       *create a class for the itinerary       
       */    
       
       /**

* Handles attachments to events.

*/

class dbitinerary extends dbTable{

	/**
    * Constructor
    */

	function init()
	{
		parent::init('tbl_itinerary');
	}


  /**
   *create a funtion to add itinerary information
   */     
	function additinerary($itinerarydetails)
	{
	      
	      $results = $this->insert($itinerarydetails);
        return $results;
  }

	function getitinerary()
	{
      /**
       *get all information from the itinerary form 
      */
  }
  

}





         

?>
