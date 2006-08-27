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
		//Read all the required info into variables
        $copyright = $this->getParam('copyright', '{UNSPECIFIED}');
        $databaseclass = $this->getParam('databaseclass', '{UNSPECIFIED}');
		//Serialize the variables to the session
	    $this->setSession('copyright', $copyright);
	    $this->setSession('databaseclass', $databaseclass); 
	    
	
		//Load the skeleton file for the class from the XML		
        $this->loadSkeleton('controller');
        //Insert the properties
        $this->insertItem('controller', 'properties');
        //Insert the properties
        $this->insertItem('controller', 'methods');
        //Insert the copyright
        $this->classCode = str_replace('{COPYRIGHT}', $copyright, $this->classCode);
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