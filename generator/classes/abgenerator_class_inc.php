<?php 
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
} 
// end security check
/**
* Abstract class defining methods and properties that must be present
* in a generator class that extends it. This is the base class that all
* generator objects must extend. It provides some standard functionality
* that cuts across all code generator ojects so that the code
* does not have to be rewritten each time
* 
* Usaeage: class mygeneratorclass extends abgenerator implements ifgenerator
* 
* @author Derek Keats 
* @category Chisimba
* @package generator
* @copyright AVOIR
* @licence GNU/GPL
*/
abstract class abgenerator extends object
{
    /**
    * 
    * @var string $classCode String to hold the content of the 
    * class being created
    * 
    */
    public $classCode;
    
    /**
    * 
    * @var string object $objUser String to hold the user object
    * 
    */
    public $objUser;
    
    /**
     * 
     * Standard init method, instantiates user object
     * 
     */
    public function init()
    {
        $this->objUser = $this->getObject('user', 'security');
    }
    
    /**
     * 
     * Method to read the object skeleton
     * 
     */
    public function loadSkeleton($classItem, $objectType='class')
    {
        //Load the XML class template
        $xml = simplexml_load_file("modules/generator/resources/" 
          . $classItem . "_" . $objectType . "_skeleton.xml"); 
        //Loop through and include the code
        foreach($xml->item as $item) {
            $this->classCode .= $item->code;
        }
        //Put in the start php <?php code
        $this->startphp();
        //Put in the end php code
        $this->endphp();
        //Put in the author
        $this->author();
        //Put in the module code
        $this->modulecode();
        //Put in the module name
        $this->modulename();
        return TRUE;
    }
    
    /**
     * 
     * Method to get the properties of the class and insert them
     * into the building up class code
     * 
     * @param string $classItem The class type being built (eg controller, db, etc)
     * @param string $itemType The item type being inserted (must be either properties or methods)
     * @access Private
     * 
     */
     public function insertItem($classItem, $objectType, $itemType)
     {
         //Load the XML class template
        $xml = simplexml_load_file("modules/generator/resources/" 
          . $classItem . "_" . $objectType  . "_" . $itemType . ".xml");
        //Initialize the string that we are reading into
        $classInsert=""; 
        //Loop through and include the code
        foreach($xml->item as $item) {
            $classInsert .= $item->code;
        }
        $pattern = "{" . strtoupper($itemType) . "}";
        //Insert the classProperties in place of the parsecode {PROPERTIES}
        $this->classCode = str_replace($pattern, $classInsert, $this->classCode);
     }
     
    /**
    * 
    * Method to return the module author as the logged in user and insert
    * it into the code of the class being built in place of the {AUTHOR} 
    * parsecode
    * 
    * @access Private
    * 
    */
    public function author()
    {
        $author = $this->objUser->fullName();
        $this->classCode = str_replace("{AUTHOR}", $author, $this->classCode);
        return TRUE;
    }
    
    /**
    * 
    * Method to return the module code and insert it into the code of the 
    * class being built in place of the {MODULECODE} parsecode
    *  
    * @access Private
    * 
    */
    public function modulecode()
    {
    	//Get the module code from parameter
        $moduleCode = $this->getParam('modulecode', NULL);
        //If there is no parameter, check the session cookies
        if ($moduleCode == NULL) {
            $moduleCode = $this->getSession('modulecode', '{MODULECODE_UNSPECIFIED}');
        } else {
            //Serialize the variable to the session since we are geting it from a param
			$this->setSession('modulecode', $moduleCode);
        }
        //Do the replace
        $this->classCode = str_replace("{MODULECODE}", $moduleCode, $this->classCode);
        return TRUE;
    }
    
    /**
    * 
    * Method to return the module name and insert it into the code of the 
    * class being built in place of the {MODULENAME} parsecode
    *  
    * @access Private
    * 
    */
    public function modulename()
    {
    	//Get the module code from parameter
        $moduleName = $this->getParam('modulename', NULL);
        //If there is no parameter, check the session cookies
        if ($moduleName == NULL) {
            $moduleName = $this->getSession('modulename', '{MODULENAME_UNSPECIFIED}');
        } else {
            //Serialize the variable to the session since we are geting it from a param
			$this->setSession('modulename', $moduleName);
        }
        //Do the replace
        $this->classCode = str_replace("{MODULENAME}", $moduleCode, $this->classCode);
        return TRUE;
    }

    /**
    * 
    * Method to return the module description and insert it into the code of the 
    * class being built in place of the {MODULENAME} parsecode
    *  
    * @access Private
    * 
    */
    public function moduledescription()
    {
    	//Get the module description from parameter
        $moduleDescription = $this->getParam('moduledescription', NULL);
        //If there is no parameter, check the session cookies
        if ($moduleDescription == NULL) {
            $moduleDescription = $this->getSession('moduledescription', '{MODULEDESCRIPTION_UNSPECIFIED}');
        } else {
            //Serialize the variable to the session since we are geting it from a param
			$this->setSession('moduledescription', $moduleDescription);
        }
        //Do the replace
        $this->classCode = str_replace("{MODULEDESCRIPTION}", $moduleDescription, $this->classCode);
        return TRUE;
    }
    
    /**
    * 
    * Method to return the copyright and insert it into the code of the 
    * class being built in place of the {COPYRIGHT} parsecode
    *  
    * @access Private
    * 
    */
    public function copyright()
    {
        //Get the module sopyright from parameter
        $copyRight = $this->getParam('copyright', NULL);
        //If there is no parameter, check the session cookies
        if ($copyRight == NULL) {
            $copyRight = $this->getSession('copyright', '{COPYRIGHT_UNSPECIFIED}');
        } else {
            //Serialize the variable to the session since we are geting it from a param
			$this->setSession('copyright', $copyRight);
        }
        //Insert the copyright
        $this->classCode = str_replace('{COPYRIGHT}', $copyRight, $this->classCode);
    }
            
    /**
    * 
    * Method to return the databasetable and insert it into the code of the 
    * class being built in place of the {DATABASETABLE} parsecode
    *  
    * @access Private
    * 
    */
    public function databasetable()
    {
        //Get the module sopyright from parameter
        $databaseTable = $this->getParam('databasetable', NULL);
        //If there is no parameter, check the session cookies
        if ($databaseTable == NULL) {
            $databaseTable = $this->getSession('databasetable', '{DATABASETABLE_UNSPECIFIED}');
        } else {
            //Serialize the variable to the session since we are geting it from a param
			$this->setSession('databasetable', $databaseTable);
        }
        //Insert the copyright
        $this->classCode = str_replace('{COPYRIGHT}', $databaseTable, $this->classCode);
    }
    
    /**
    * 
    * Method to return the databaseclass and insert it into the code of the 
    * class being built in place of the {DATABASECLASE} parsecode
    *  
    * @access Private
    * 
    */
	function databaseclass()
	{
	    //Read the database class
        $databaseclass = $this->getParam('databaseclass', NULL);
        //If there is no parameter, check the session cookies
        if ($databaseclass == NULL) {
            $databaseclass = $this->getSession('databaseclass', '{DATABASECLASS_UNSPECIFIED}');
        } else {
            //Serialize the variable to the session since we are geting it from a param
			$this->setSession('databaseclass', $databaseclass);
        }
        //Insert the database classname
        $this->classCode = str_replace('{DATACLASS}', $databaseclass, $this->classCode);
	}
    

    /**
     * 
     * Method to insert the start of the PHP code. Note that this
     * method must be here in order to comply with the parsecodes
     * in XXX_class_parsecodes.xml.
     * 
     * @access Private
     * 
     */
     private function startphp()
     {
        //Put in the start php <?php code
        $this->classCode = str_replace('{STARTPHP}', '<?php', $this->classCode);
        return TRUE;
     }
     
    /**
     * 
     * Method to insert the start of the PHP code. Note that this
     * method must be here in order to comply with the parsecodes
     * in XXX_class_parsecodes.xml.
     * 
     * @access Private
     * 
     */
     private function endphp()
     {
        //Put in the start php <?php code
        $this->classCode = str_replace('{ENDPHP}', '?>', $this->classCode);
        return TRUE;
     }
     
    /**
    * 
    * Format the code for display as HTML
    * 
    */
	public function prepareForDump()
	{
		$this->classCode = htmlentities($this->classCode);
	    $this->classCode = str_replace(' ', '&nbsp;', $this->classCode);
	    $this->classCode = nl2br($this->classCode);
        return TRUE;
	}
	
	/**
	 * 
	 * A method to get a method from a particular XML methods template
	 * 
	 * @param string $classItem The name of the class to get (e.g. controller, dbtable, etc)
	 * @param string $methodName The name of the method to extract from the class
	 * 
	 */
	public function getMethod($classItem, $methodName)
	{
	    if ($this->methodXml == NULL || $this->methodXML="") {
	    	$this->methodXml = simplexml_load_file("modules/generator/resources/" 
              . $classItem . "class_methods.xml");
	    }
	    $xPathParam = "//item[@name = '" . $methodName . "'";
	    $ret = $xml->xpath($xPathParam);
	}
	
   
    /**
     * 
     * Method to cleanup unused {TAGS} in from the XML template.
     * It reads an XML file containing the tags to be cleaned up. By
     * using an XML file, we can keep the tags out of the code and
     * keep this code quite simple.
     * 
     */
    public function cleanUp()
    {
    	$chk = $this->getParam('bypasscleanup', FALSE);
    	if ($chk !== 'TRUE') {
	        //Load the XML template tagnames for scrubbing
	        $xml = simplexml_load_file("modules/generator/resources/template-tagnames.xml");
	        //Loop through and clean up any unused tags in the code
	        foreach($xml->tag as $tag) {
	            $this->classCode = str_replace($tag->tagtext, NULL, $this->classCode);
	        }
    	}	        
    }
    
    /**
     * 
     * Method to insert the logger code into the init statement
     * by replacing the {LOGGER} template code.
     * 
     * @TODO bring this in line with the new approach ===================================================
     * 
     */
    public function initLogger()
    {
        $str = "        //Get the activity logger class\n"
          . "        \$this->objLog=\$this->newObject('logactivity', 'logger');\n"
          . "        //Log this module call\n"
          ."        \$this->objLog->log();\n";
        $this->classCode = str_replace("{LOGGER}", $str, $this->classCode);
        return TRUE;
    }
    
    /**
    * 
    * Method to return the module author as the logged in user
    * 
    * @return string The full name of the author
    * 
    */
    protected function getAuthor()
    {
        return $this->objUser->fullName();
    }
} 
?>