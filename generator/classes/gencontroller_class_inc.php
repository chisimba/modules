<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	die("You cannot view this page directly");
}
// end security check
require_once('modules/generator/classes/abgenerator_class_inc.php');
require_once('modules/generator/classes/ifgenerator_class_inc.php');

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
    * 
    * @var string $dataClass The name of the dataclass being used in this module
    * 
    */
    private $dataClass;
    
    /**
     * 
     * Standard init, calls parent init method to instantiate user and other objects
     * 
     */
    function init()
    {
        parent::init();
    }
    
	/**
	 * Method to generate the class for the controller
	 */
	function generate($className)
	{
		//Load the skeleton file for the class from the XML		
        $this->loadSkeleton('controller', 'class');
        //Insert the properties
        $this->insertItem('controller', 'class', 'properties');
        //Insert the properties
        $this->insertItem('controller', 'class', 'methods');
        //Insert the module description
        $this->moduledescription();
        //Insert the copyright
        $this->copyright();
        //Insert the database class
        $this->databaseclass();
        //Add code for logging
        $this->initLogger();
        //Clean up unused template tags
        $this->cleanUp();
        $this->prepareForDump();
	    return $this->classCode;
	}
}
?>