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
class genregister extends abgenertor implements ifgenerator
{
	public $registerCode;
    
	/**
	 * Method to generate the class for the controller
	 */
	function generate()
	{
		//Initialize the register code
		$this->registerCode="";
		//Read all the required info into variables
		$this->moduleCode = $this->getParam('modulecode', '{UNSPECIFIED}');
		$this->moduleName = $this->getParam('modulename', '{UNSPECIFIED}');
		$this->moduleDescription = $this->getParam('moduledescription', '{UNSPECIFIED}');
		$this->menuCategory = $this->getParam('menucategory', NULL);
		$this->sideMenuCategory = $this->getParam('sidemenucategory', NULL);
		
		//Read the XML template
		$this->registerCode = $this->readRegisterTemplate();
		//Replace the template tags with actual values
		$this->registerCode = str_replace('{MODULE_NAME}', $this->moduleName, $this->registerCode);
		$this->registerCode = str_replace('{MODULE_ID}', $this->moduleCode, $this->registerCode);
		$this->registerCode = str_replace('{MODULE_DESCRIPTION}', $this->moduleDescription, $this->registerCode);
		$this->registerCode = str_replace('{MENU_CATEGORY}', $this->menuCategory, $this->registerCode);
		$this->registerCode = str_replace('{MODULE_DESCRIPTION}', $this->moduleDescription, $this->registerCode);
	
//CONTEXT_AWARE: {CONTEXT_AWARE}
//DEPENDS_CONTEXT: {DEPENDS_CONTEXT}


        /*
         *  //Clean up unused template tags
        $this->cleanUp();
        $this->prepareForDump();*/
	    return $this->registerCode;
	}
	
	private function prepareForDump()
	{
		$this->classCode = htmlentities($this->classCode);
	    $this->classCode = str_replace(' ', '&nbsp;', $this->classCode);
	    $this->classCode = nl2br($this->classCode);
	}
	
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