<?php

/**

* Handles attachments to events.

*/

class dbincident extends dbTable{

	/**
	 *Constructor
 	*/
    	function init()
	    {
		    parent::init('tbl_incident');
	    }


  /**
   *function to insert all claimant details into a db table - tev   
   */     

 	    function addincident($incidentdetails)
	    {
        $results = $this->insert($incidentdetails);
        return $results;
      }


 
  

}



?>
