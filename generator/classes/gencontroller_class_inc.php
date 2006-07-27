<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	die("You cannot view this page directly");
}
// end security check

/**
* 
* Class to generate a Chisimba controller
* 
* Usaeage: class gencontroller extends abgenerator implements ifgenerator
*
* @author Derek Keats
* @category Chisimba
* @package generator
* @copyright AVOIR
* @licence GNU/GPL
*
*/
class gencontroller extends abgenerator implements ifgenerator
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