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
    * @var string $methodXML Property to hold the methodXML when read using getMethod()
    */
	private $methodXml;
	
    /**
    * 
    * @var string $moduleName String the name of the module being created
    */
    public $moduleName;

    /**
    * 
    * @var string $className String the name of the class being created
    * 
    */
    public $className;
    
    /**
    * 
    * @var string $classCode String to hold the content of the 
    * class being created
    * 
    */
    public $classCode;

    /**
    * 
    * @var string $initObjects String containing the code for the objects to be 
    * provided in the init class
    * 
    */
    public $initObjects;
    
    /** 
    * 
    * @var string $author String  The author of the module, usually the logged in user.
    * 
    */
    public $author;
    
    /**
    * 
    * @var string $copyRight String The module copyright owner
    * 
    */
    public $copyRight;
    
    /**
    * 
    * @var string $package String The package that the class belongs to,
    * usually the same as the module code
    * 
    */
    public $package;
    
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
     * Method to read the class skeleton
     * 
     */
    public function loadSkeleton($classItem)
    {
        //Load the XML class template
        $xml = simplexml_load_file("modules/generator/resources/" . $classItem . "class_skeleton.xml"); 
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
     private function insertItem($classItem, $itemType)
     {
         //Load the XML class template
        $xml = simplexml_load_file("modules/generator/resources/" 
          . $classItem . "class_" . $itemType . ".xml");
        //Initialize the string that we are reading into
        $classInsert=""; 
        //Loop through and include the code
        foreach($xml->item as $item) {
            $classInsert .= $item->code;
        }
        $pattern = "{" . str2upper($itemType) . "}";
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
    private function author()
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
    private function modulecode()
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
    private function modulename()
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
    private function moduledescription()
    {
    	//Get the module code from parameter
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
	protected function prepareForDump()
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
	function getMethod($classItem, $methodName)
	{
	    if ($this->methodXml == NULL || $this->methodXML="") {
	    	$this->methodXml = simplexml_load_file("modules/generator/resources/" 
              . $classItem . "class_methods.xml");
	    }
	    $xPathParam = "//item[@name = '" . $methodName . "'";
	    $ret = $xml->xpath($xPathParam);
	}
    
    ///------------old methods to be deprecated
    
    
    /**
    * 
    * Method to setup the class definition line of a templated class
    * by replacing {CLASS} {EXTENDS} {IMPLEMENTS} in the template
    * 
    * @param string $classname the Name of the class we are starting
    * @param string $classType the type of class to create with valid class
    *    types being controller, view, helper and model.
    * @return string $ret the class code
    */
    function setupClass($className, $classType = NULL, $classImplements=NULL)
    {
        //Insert the class name
        $this->classCode = str_replace('{CLASS}', $className, $this->classCode);
        //Switchboard based on class type
        switch ($classType) {
            case NULL:
            case "view":
            case "helper":
                $this->classCode = str_replace('{EXTENDS}', '', $this->classCode);
                break;
            case "controller":
                $this->classCode = str_replace('{EXTENDS}', 'extends controller', $this->classCode);
                break;
            case "model":
                $this->classCode = str_replace('{EXTENDS}', 'extends dbTable', $this->classCode);
                break;
            default:
                $this->classCode = str_replace('{EXTENDS}', '', $this->classCode);
                break;
        } #switch
        if ($classImplements=NULL) {
            $this->classCode = str_replace('{IMPLEMENTS}', '', $this->classCode);
        } else {
            $this->classCode = str_replace('{IMPLEMENTS}', $classImplements, $this->classCode);
        }
        return TRUE;
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
    * Method to set the values of protected/private properties. Note that it 
    * prevents the sloppy approach of adding poperties that are not defined.
    * 
    * @param string $itemName The name of the property whose value is being set.
    * @param string $itemValue The value of the property being set
    */
    public function setValue($itemName, $itemValue)
    {
        if (property_exists($itemName, $this)) {
            $this->$itemName = $itemValue;
            return true;
        } else {
            return false;
        } 
    } 

    /**
    * Method to set the values of protected/private properties
    * 
    * @param string $itemName The name of the property whose value is being retrieved.
    */
    public function getValue($itemName)
    {
        if (isset($this->$itemName)) {
            return $this->$itemName;
        } else {
            return null;
        } 
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