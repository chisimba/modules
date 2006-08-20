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
     * Standard init, calls parent init method to instantiate user
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
		//Read all the required info into variables
		$moduleCode = $this->getParam('modulecode', '{UNSPECIFIED}');
		$moduleName = $this->getParam('modulename', '{UNSPECIFIED}');
		$moduleDescription = $this->getParam('moduledescription', '{UNSPECIFIED}');
        $copyright = $this->getParam('copyright', '{UNSPECIFIED}');
        $databaseclass = $this->getParam('databaseclass', '{UNSPECIFIED}');
		$purpose = $moduleName . '\n' . $moduleDescription;
		//Serialize the variables to the session
		$this->setSession('modulecode', $moduleCode);
		$this->setSession('modulename', $moduleName);
	    $this->setSession('moduledescription', $moduleDescription);
	    $this->setSession('copyright', $copyright);
	    $this->setSession('purpose', $purpose); 
	    $this->setSession('databaseclass', $databaseclass); 
	    
	
		//Off we go to prepare the class from the XML		
        $this->prepareClass();
        //Set up the class with the name specified by module code
        $this->setupClass($moduleCode, 'controller');
        //Insert the standard properties for holding objects
        $this->classCode = str_replace('{PROPERTIES}', 
          $this->getStandardObjectDefinitions(), $this->classCode);
        //Insert the module name and description
        $this->classCode = str_replace('{PURPOSE}', $purpose, $this->classCode);
        //Insert the author
        $this->classCode = str_replace('{AUTHOR}', $this->getAuthor(), $this->classCode);
        //Insert the package name
        $this->classCode = str_replace('{PACKAGE}', $moduleCode, $this->classCode);
        //Insert the copyright
        $this->classCode = str_replace('{COPYRIGHT}', $copyright, $this->classCode);
        //Insert the default controller methods
        $this->classCode = str_replace('{METHODS}', $this->getDefaultMethods() 
          . "\n{SPECIALMETHODS}\n", $this->classCode);
         //Insert the standard methods to init()
        $this->classCode = str_replace('{OBJECTS}', $this->initObjects, $this->classCode);
        //Insert the database classname
        $this->classCode = str_replace('{DATACLASS}', $databaseclass, $this->classCode);
        
        //Add code for logging
        $this->initLogger();
        //Clean up unused template tags
        $this->cleanUp();
        $this->prepareForDump();
	    return $this->classCode;
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
        $xml = simplexml_load_file("modules/generator/resources/controller-methods.xml"); 
        //Loop through and include the code
        foreach($xml->method as $method) {
            $ret .= $method->code;
        }
        return $ret;
    }
    
    /**
    * 
    * Method to read the standard objects to build in init() from
    * the file standard-objects.xml. It builds the property definitions
    * as well as the code to do the instantiation, which is placed
    * int $this->initObjects for later use.
    * 
    * @return string $ret The property definitions
    * 
    */
    function getStandardObjectDefinitions()
    {
        $ret="";
        
        $xml = simplexml_load_file("modules/generator/resources/standard-objects.xml"); 
        //Loop through and include the code
        foreach($xml->object as $object) {
            //Set up the defining of the properties
            $ret .= "\n    /**\n    *\n    *"
              . "@var string object \$" . $object->name
              . "String to hold the instance of the object " 
              . $object->name . "\n    * which is " 
              . $object->comment . ".\n    *\n    */\n" 
              . "    public \$" . $object->name . ";";
            //Set up an array to be used for instantiating the objects in init()
            $this->initObjects .= "        //" . $object->comment
              . "\n        \$this->" . $object->name
              . " = \$this->getObject(\"" .  $object->obclass 
              . "\", \"" . $object->module . "\");\n";
            
        }
        return $ret;
    }

}
?>