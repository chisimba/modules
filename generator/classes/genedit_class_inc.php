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
* Class to generate a Chisimba edit template
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
class genedit extends abgenertor implements ifgenerator
{
    private $dataClass;
    private $xml;
   
	/**
	 * Method to generate the class for the controller
	 */
	function generate($className)
	{
        $this->prepareTemplate();

        
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
    * Method to prepare the template for the code
    * to insert into. It uses XPATH to extract the code
    * from the XML tree
    * 
    */
    function prepareTemplate()
    {
        $xml = simplexml_load_file("modules/generator/resources/edit-template-items.xml");
        //Initialize the template
        $ret = $xml->xpath("//item[@name = 'initializeTemplate']");
        $this->classCode = $ret[0]->code;
        //Add the heading to the template
        $ret = $xml->xpath("//item[@name = 'createHeading']");
        $this->classCode .= $ret[0]->code;
        //Set up the area for the code to create the edit form
        $ret = $xml->xpath("//item[@name = 'makeeditform']");
        $this->classCode .= $ret[0]->code;
        //Set up the render output code
        $ret = $xml->xpath("//item[@name = 'renderOutput']");
        $this->classCode .= $ret[0]->code;
        //Return a casual true
        return TRUE;
    }
}
?>