<?php

/**

* Handles attachments to events.

*/

class dbTev extends dbTable{

	/**
	 *Constructor
 	*/
	
	

	function init()
	{
		parent::init('tbl_tev');
	}


  /**
   *function to insert all claimant details into a db table - tev   
   */     

 	function addclaimant($claimantdetails)
	{
      $results = $this->insert($claimantdetails);
      return results;
  }


	
  /**
   *function to get all claimant information from the db
   */
        
	function getTev()
	{
    /*$getclaimantdetails = array('claimanantname'  =>  $this->getParam('txtClaimantName'),
                                'title'           =>  $this->getParam('txtTitle'),
                                'mailaddress'     =>  $this->getParam('txtAddress'),
                                'city'            =>  $this->getParam('txtCity'),
                                'province'        =>  $this->getParam('txtprovince'),
                                'postalcode'      =>  $this->getParam('txtpostalcode'),
                                'country'         =>  $this->getParam('txtcountry'),
                                'travelpurpose'   =>  $this->getParam('travel')
                                );
                                return  $getclaimantdetails;*/
                               
  
  }
  
  /**
   *function to delete claimant information from the db
   */
   function removeClaimant()
  {}          
  
  

}



?>
