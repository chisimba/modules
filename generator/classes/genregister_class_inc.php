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
* Class to generate a Chisimba register.conf (register.xml) file
* 
* Useage: class genregister extends abgenerator implements ifgenerator
*
* @author Derek Keats
* @category Chisimba
* @package generator
* @copyright AVOIR
* @licence GNU/GPL
*
*/
class genregister extends abgenerator implements ifgenerator
{
	public $registerCode;
    
    function init()
    {
        $this->objUser = $this->getObject('user', 'security');
    }
    
	/**
	 * Method to generate the class for the controller
	 */
	function generate($className)
	{
		//Initialize the register code
		$this->registerCode="";
		//Read all the required info into variables
		$this->moduleCode = $this->getParam('modulecode', '{UNSPECIFIED}');
		$this->moduleName = $this->getParam('modulename', '{UNSPECIFIED}');
		$this->moduleDescription = $this->getParam('moduledescription', '{UNSPECIFIED}');
        $this->copyright = $this->getParam('copyright', '{UNSPECIFIED}');
		$this->menuCategory = $this->getParam('menucategory', NULL);
		$this->sideMenuCategory = $this->getParam('sidemenucategory', NULL);
        $this->contextAware = $this->getParam('contextaware', NULL);
        $this->dependsContext = $this->getParam('dependscontext', NULL);
		
		//Read the XML template
		$this->registerCode = $this->readRegisterTemplate();
		//Replace the template tags with actual values
		$this->registerCode = str_replace('{MODULE_NAME}', $this->moduleName, $this->registerCode);
		$this->registerCode = str_replace('{MODULE_ID}', $this->moduleCode, $this->registerCode);
		$this->registerCode = str_replace('{MODULE_DESCRIPTION}', $this->moduleDescription, $this->registerCode);
        $this->registerCode = str_replace('{MODULE_AUTHORS}', $this->objUser->fullName(), $this->registerCode);
        $this->registerCode = str_replace('{MODULE_RELEASEDATE}', date("F j, Y, g:i a"), $this->registerCode);
        $this->registerCode = str_replace('{MENU_CATEGORY}', $this->menuCategory, $this->registerCode);
		$this->registerCode = str_replace('{SIDEMENU_CATEGORY}', $this->sideMenuCategory, $this->registerCode);
		$this->registerCode = str_replace('{MODULE_DESCRIPTION}', $this->moduleDescription, $this->registerCode);
        $this->registerCode = str_replace('{CONTEXT_AWARE}', $this->contextAware, $this->registerCode);
        $this->registerCode = str_replace('{DEPENDS_CONTEXT}', $this->dependsContext, $this->registerCode);
	


        /*
         *  //Clean up unused template tags
        $this->cleanUp();
        $this->prepareForDump();*/
	    return $this->registerCode;
	}
	
    /**
    * 
    * Method to read the register.conf XML template and format the 
    * basic REGISTER.CONF file format, without parsing any template
    * codes.
    * 
    * @return string The raw REGISTER.CONF text with the template
    * codes still in place.
    * 
    */
	function readRegisterTemplate()
	{
        $ret="";
        $xml = simplexml_load_file("modules/generator/resources/register_conf_txt.xml"); 
        //Loop through and include the code
        $addIt=TRUE;
        foreach($xml->entry as $entry) {
        	if ($entry->param == "MENU_CATEGORY") {
        	    if ($this->menuCategory !== NULL) {
					$ret .= $entry->param . ": " . $entry->value . "\n";
        	    }
        	} elseif ($entry->param == "SIDEMENU_CATEGORY") {
        	    if ($this->sideMenuCategory !== NULL) {
					$ret .= $entry->param . ": " . $entry->value . "\n";
        	    }
        	} else {
        	    $ret .= $entry->param . ": " . $entry->value . "\n";
        	}
        }
	    return $ret;
	}

}
?>