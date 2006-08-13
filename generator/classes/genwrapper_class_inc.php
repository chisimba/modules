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
    /**
    * 
    * @param string $module The module in which the class is found
    * 
    */
    private $module;
    
    /**
    * 
    * @param string $classFile The filename of the class being wrapped
    * 
    */
    private $classFile;
    
    /**
    * 
    * @param string $objToWrap The name of the class being wrapped
    * 
    */
    private $objToWrap;
    
    /**
    * 
    * @param string $className The name of the class being created as the wrapper
    * 
    */
    public $className;
    
    public $strClass;
   
    /**
    * 
    * Standard init method reading the parameters from the
    * form that has been submitted
    * 
    */
    function init()
    {
        //Get the values needed to do the work
        $this->classFile = $this->getParam('filename', NULL);
        $this->module = $this->getParam('module', NULL);
        $this->params = $this->getParam('params', NULL);
        $this->className = $this->getParam('classname', NULL);
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
        $this->loadWrapClass();
        $this->objToWrap = $this->getWrapClassName();
        $this->prepareWrapper();
        //Replace template elemente for name of class
        $this->classCode = str_replace('{WRAPCLASS}', 
          $this->objToWrap, $this->classCode);
        $this->classCode = str_replace('{WRAPCLASSINSTANCE}', 
          $this->objToWrap, $this->classCode);
        $this->classCode = str_replace('{WRAPCLASSPARAMS}', 
          $this->getConstructorParams(), $this->classCode);
        $this->classCode = str_replace('{WRAPPERCLASS}', 
          $this->className, $this->classCode);
        $this->classCode = str_replace('{WRAPCLASSSFULLPATH}', 
          "modules/" . $this->module . "/lib/" . $this->classFile,
          $this->classCode);
        //Start up the class
        $objWrapee = $this->instantiateClass();
        $this->classCode = str_replace('{METHODS}', 
          $this->getMethods($this->objToWrap), $this->classCode);

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
        $f = "modules/" . $this->module 
          . "/lib/" . $this->classFile;
        //Read the file into a string
        $fp = fopen($f, "r");
        $this->strClass = fread($fp, filesize($f));
        fclose($fp);
        //Parse the string and extract the class name
        $regExpr = "/(class )(.*)(\{)/isU";
        if (preg_match($regExpr, $this->strClass, $elems)) { 
            $ret = $elems[2];
        } else {
            $ret = "{COULDNOTEXTRACTCLASSNAME}";
        }
        return $ret;
    }
    
    /**
    * 
    * Method to instantiate the class being wrapped. 
    * 
    */
    private function instantiateClass()
    {
        $params = $this->getConstructorParams();
        //Add params if any are required
        if ($params !== "") {
            @$this->objWrapped = new $this->objToWrap($params); #can this work???????
        } else {
            @$this->objWrapped = new $this->objToWrap();
        }
    }

    /**
    * 
    * Method to get all properties of the class to be wrapped
    * 
    * Note: This will not return private and protected properties
    * which is exactly as we want it. We do not want to wrap
    * private properties.
    * 
    * @return string array An array of all the properties of the
    * class being wrapped
    * 
    */
    private function getProperties()
    {
        $prp = get_object_vars($this->objWrapped);
        $ret="";
        foreach ($prp as $property) {
            $ret .= "public " . $property . "\n";
        }
        return $ret;
    }
    
    /**
    * 
    * Method to get all methods of the class to be wrapped
    * 
    * Note: This will not return private and protected methods
    * which is exactly as we want it. We do not want to wrap
    * private methods.
    * 
    * @return string array An array of all the methods of the
    * class being wrapped
    * 
    */
    private function getMethods()
    {
        $mth = get_class_methods($this->objWrapped);
        $ret="";
        foreach ($mth as $method) {
            if (trim($method) != "__construct") {
                $params = $this->extractParams($method);
                $ret .= "\n    /**\n    *\n    * Wrapper method for " 
                  . $method . " in the " . $this->objToWrap . "\n    * "
                  . "class being wrapped. "
                  . "See that class for details of the \n" 
                  . "    * ". $method . "method.\n    *\n    */\n"
                  . "    public " . $method . "(" . $params . ")\n    {\n"
                  . "        return \$this->" . $this->objToWrap . "->" 
                  . $method . "(". $params . ");\n    }\n";
            }
        }
        return $ret;
    }
    
    /**
    * 
    * Method to extract the parameters from the method
    * being processed
    * 
    * @param string $mthd The method being parsed
    * 
    */
    function extractParams($mthd)
    {
        
        //Create a regex to match the current pattern
        $regExpr = "/(" . $mthd . "*\()(.*)(\))/isU";
        //echo $regExpr . "<br /><br /><br />";
        if (preg_match($regExpr, $this->strClass, $elems)) { 
            $ret = $elems[2];
            
        } else {
            $ret = NULL;
        }
        /**if ($ret == "") {
            echo "Did not find params in $mthd <br />";
        } else {
            echo "Found $ret in $mthd <br />";
        }*/
        return $ret;
    }

    /**
    * 
    * Method to get the constructor class methods
    * for use in instantiating the class
    * 
    */
    function getConstructorParams()
    {
        return $this->extractParams('__construct');
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
        $xml = simplexml_load_file("modules/generator/resources/wrapper-items.xml");
        //Initialize the class
        $ret = $xml->xpath("//item[@name = 'buildClass']");
        $this->classCode .= $ret[0]->code;
        //Add the init method
        $ret = $xml->xpath("//item[@name = 'initializeClass']");
        $tmp .= $ret[0]->code;
        $this->classCode = str_replace('{METHODS}', $tmp . "{METHODS}", $this->classCode);
        //Return a casual true
        return TRUE;
    }
}
?>