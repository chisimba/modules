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
class genwrapper extends abgenerator implements ifgenerator
{
    private $module;
    private $classFile;
    privte $objToWrap;
    private $xml;
   
    /**
    * 
    * Standard init method
    * 
    */
    function init()
    {
    
    }
   
	/**
    * 
	* Method to generate the class for the controller
    * 
    * @param string $className The name of the wrapper class 
    * to generate
    * 
	*/
	public function generate($className)
	{
        $this->prepareWrapper();

        
        //Clean up unused template tags
        $this->cleanUp();
        $this->prepareForDump();
	    return $this->classCode;
	}
    
    /**
    * 
    * Method to load the class being wrapped
    * 
    */
    private function loadWrapClass()
    {
        //load the class
        require_once("modules/" . $this->module . "/lib/" . $this->classFile);
    }
    
    /**
    * 
    * Method to get the name of the class being wrapped. The class is 
    * fopened, and a regex is used to extract the name.
    * 
    * @return string The name of the class being wrapped
    * 
    */
    private function getWrapClassName()
    {
        //Read the file into a string
        $fp = fopen($this->file, "r");
        $strClass = fread($fp, filesize($this->file));
        fclose($fp);
        //Parse the string and extract the class name
        $regExpr = "/(class )(.*)(\{)/isU";
        
        
        $ret="";
        $return $ret;
    }
    
    /**
    * 
    * Method to instantiate the class being wrapped. 
    * 
    */
    private function instantiateClass()
    {
        //Add params if any are required
        if (isSet($this->params) {
            $this->objToWrap = new $this->className($this->params); #can this work???????
        } else {
            $this->objToWrap = new $this->className();
        }
    }

    /**
    * 
    * Method to get all properties of the class to be wrapped
    * 
    * @return string array An array of all the properties of the
    * class being wrapped
    * 
    */
    private function getProperties()
    {
        return get_object_vars($this->objToWrap);
    }
    
    /**
    * 
    * Method to get all methods of the class to be wrapped
    * 
    * @return string array An array of all the methods of the
    * class being wrapped
    * 
    */
    private function getMethods()
    {
        return get_class_methods($this->objToWrap);
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
    private function prepareWrapper()
    {
    
    
    
    
    ///--------------------------------------old code working here-----------------------
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