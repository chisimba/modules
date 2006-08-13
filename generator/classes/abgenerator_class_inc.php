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
    * Standard init method
    * 
    */
    function init()
    {
        $this->objUser = $this->getObject('user', 'security');
    }


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
    * Method to prepare the class by loading it from XML and replacing
    * some template items with their actual values as the class is built up.
    * 
    */
    function prepareClass()
    {
        //Load the XML class template
        $xml = simplexml_load_file("modules/generator/resources/class-setup.xml"); 
        //Loop through and include the code
        foreach($xml->section as $section) {
            $this->classCode .= $section->code;
        }
        //Put in the start php <?php code
        $this->classCode = str_replace('{STARTPHP}', '<?php', $this->classCode);
        //Put in the end php code
        $this->classCode = str_replace('{ENDPHP}', '?>', $this->classCode);
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
    function getAuthor()
    {
        return $this->objUser->fullName();
    }
    
} 

?>