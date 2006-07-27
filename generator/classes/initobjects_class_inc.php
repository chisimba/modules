<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	die("You cannot view this page directly");
}
// end security check

/**
* 
* Class to return the init strings for common objects that can be instantiated
* in the init method of a class being generated
* 
* Usaeage: $iO = this->getObject('initobjects');
*          $iO->objectList=array('$objUser', '$objConfig');
*          echo $this->iO->show();
*
* @author Derek Keats
* @category Chisimba
* @package generator
* @copyright AVOIR
* @licence GNU/GPL
*
*/
class initobjects extends getset
{
	/**
	 * Method to generate the class for the controller
	 */
	function generate()
	{
	    //
	}
	
	/**
	* 
	* Method to build the dispatch method of the controller class
	* 
	*/
    function makeDispatch()
    {
        //
    }
    
    function makeInit()
    {
        //
    }
    
    function buildDynamicMethods()
    {
        //
    }
}
?>