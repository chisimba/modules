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
* Class to generate a Chisimba dbtable class
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
class gendbtable extends abgenertor implements ifgenerator
{
    private $dataClass;
	/**
	 * Method to generate the class for the controller
	 */
	function generate($className)
	{
        $this->prepareClass();
        $this->setupClass('dummy4test', 'dbtable', $classImplements=NULL);
        //Insert the default controller methods
        $this->classCode = str_replace('{METHODS}', $this->getDefaultMethods() . "\n{SPECIALMETHODS}\n", $this->classCode);
        //Clean up unused template tags
        $this->cleanUp();
        $this->prepareForDump();
	    return $this->classCode;
	}
	
    /**
    * 
    * Format the code for display as HTML
    * 
    */
	private function prepareForDump()
	{
		$this->classCode = htmlentities($this->classCode);
	    $this->classCode = str_replace(' ', '&nbsp;', $this->classCode);
	    $this->classCode = nl2br($this->classCode);
        return TRUE;
	}
	
    /**
    * 
    * Method to load an XML definition file to extract the method 
    * code. The XML file is named 
    * and is structured as: controller-methods.xml located in the
    * resources folder of this module. It uses simplexml_load_file
    * to do the work.
    * <method>
    *  <name>
    *  </name>
    *  <code>
    *  </code>
    * </method>
    * 
    */
    function getDefaultMethods()
    {
        $ret="";
        $xml = simplexml_load_file("modules/generator/resources/dbtable-methods.xml"); 
        //Loop through and include the code
        foreach($xml->method as $method) {
            $ret .= $method->code;
        }
        return $ret;
    }
}
?>