a<?php
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
class gendbtable extends abgenerator implements ifgenerator
{
    private $dataClass;
    public $arrayOfFields=array();
    
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
        $this->prepareClass();
        $this->setupClass($className, 'model', $classImplements=NULL);
        //Insert the default controller methods
        $this->classCode = str_replace('{METHODS}', $this->getDefaultMethods(), $this->classCode);
        //Insert the save methods (must have the array set first)
        $this->classCode = str_replace('{SAVECODE}', $this->getSaveMethods(), $this->classCode);
        //Insert the purpose in the comments
        $this->classCode = str_replace('{PURPOSE}', $this->getPurpose(), $this->classCode);
        //Insert the author
        $this->classCode = str_replace('{AUTHOR}', $this->getAuthor(), $this->classCode);
        //Insert the database table
        $table = $this->getParam('tablename', '{TABLENAME}');
        $this->classCode = str_replace('{DATABASETABLE}', $table, $this->classCode);
        //Insert the package name
        $package = $this->getParam('modulename', '{UNSPECIFIED}');
        $this->classCode = str_replace('{PACKAGE}', $package, $this->classCode);
        //Insert the copyright
        $copy = $this->getParam('copyright', '{UNSPECIFIED}');
        $this->classCode = str_replace('{COPYRIGHT}', $copy, $this->classCode);
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
        $xml = simplexml_load_file("modules/generator/resources/dbtable-methods.xml"); 
        //Loop through and include the code
        foreach($xml->method as $method) {
            $ret .= $method->code;
        }
        return $ret;
    }
    
    /**
    * 
    * Method to return the save methods for the data
    * 
    * @return String $ret The formatted code
    * 
    */
    function getSaveMethods()
    {
        //Initialize the return string
        $ret="";
        //Loop and make the getparam statements
        foreach ($this->arrayOfFields as $fieldName) {
            $ret .= $this->makeGetParam($fieldName);
        }
        //Insert the save when coming from edit
        $ret .= $this->makeSave();
        return $ret;
    }
    
    /**
    * 
    * Method to make the getParam methods
    * 
    * @param string $fieldName THe name of the field to get
    * @return string $ret The formatted getparam statement
    * 
    */
    function makeGetParam($fieldName)
    {
        switch ($fieldName) {
            case "id":
                $ret = "        //Retrieve the value of the primary keyfield \$" . $fieldName . "\n"
                  . "        \$" . $fieldName . " = \$this->getParam('" 
                  . $fieldName . "', NULL);\n";
                break;
                
            case "updated":
            case "dateModified":
            case "datemodified":
                 $ret = "        //Set the value of \$" . $fieldName . " to the current datetime.\n"
                  . "        \$" . $fieldName . " = \$this->now();\n";
                break;
            //Things that are formatted depending on add or edit
            case "creatorId":
            case "creatorid":
            case "modifierId":
            case "modifierid":
            case "dateCreated":
            case "datecreated":
                $ret = NULL;
                break;
            //The default method to return the getting of the value
            default:
                $ret = "        //Retrieve the value of \$" . $fieldName . "\n"
                  . "        \$" . $fieldName . " = \$this->getParam('" 
                  . $fieldName . "', NULL);\n";
                break;
        } #switch
        return $ret;
    }
    
    /**
    * 
    * Method to make the save part of the code
    * 
    * @return string $ret The save part of the code
    * 
    */
    function makeSave()
    {
        //Set up the top of the edit/save area
        $ret = "\n        //If coming from edit use the update code\n"
          . "        if (\$mode==\"edit\") {\n"
          . "            \$ar = (\n";
        //How many fields are there
        $fldCount = count($this->arrayOfFields);
        //Set the index pointer to 0
        $iIndex = 0;
        //Loop over the items
        foreach ($this->arrayOfFields as $fieldName) {
            $ret .= $this->makeSaveFromEditItem($fieldName, $fldCount, $iIndex);
            $iIndex++;
        }
        $ret .= "            \$this->update('id', \$id, \$ar);\n"
          . "        } else {\n"
          . "            \$ar = (\n";
        //Set the index pointer to 0
        $iIndex = 0;
        //Loop over the items
        foreach ($this->arrayOfFields as $fieldName) {
            $ret .= $this->makeSaveFromAddItem($fieldName, $fldCount, $iIndex);
            $iIndex++;
        }
        $ret .= "            \$this->insert(\$ar);\n        }\n";
        return $ret;
    }
    
    /**
    * 
    * Method to make the individual components of the save from edit. It 
    * uses the fldCount and iIndex to determine whether to continue
    * with a comma or end up with a bracket.
    * 
    * @param string $fieldName The name of the field
    * @param string $fldCount The number of fields
    * @param string $iIndex The position in the array of fields being processed
    * 
    */
    function makeSaveFromEditItem($fieldName, $fldCount, $iIndex)
    {
        //Index and field count should be real numbers not array index
        $iIndex = $iIndex + 1; 
        //Close up the array if we are at the end, else add , and new line
        if ($iIndex < $fldCount) {
            $comma = ",\n";
        } else {
            $comma = "\n            );\n";
        }
        switch ($fieldName) {
            //The id field is generated
            case "id":
            //These do not change on an edit
            case "creatorId":
            case "creatorid":
            case "dateCreated":
            case "datecreated":
            case "creationdate":
            case "creationDate":
                $ret = NULL;
                break;
            case "dateModified":
            case "datemodified":
                $ret = "              '" . $fieldName 
                  . "' => \$this->now();";
                break;
            case "modifierId":
            case "modifierid":
            case "modifiedBy":
            case "modifiedby":
                $ret = "              '" . $fieldName 
                  . "' => \$this->objUser->fullName();\n";
                break;
            //The default method to return the getting of the value
            default:
                $ret = "              '" . $fieldName 
                  . "' => \$" . $fieldName . $comma;
                break;
        }
        return $ret;
    }

    /**
    * 
    * Method to make the individual components of the save frmo add. It 
    * uses the fldCount and iIndex to determine whether to continue
    * with a comma or end up with a bracket.
    * 
    * @param string $fieldName The name of the field
    * @param string $fldCount The number of fields
    * @param string $iIndex The position in the array of fields being processed
    * 
    */
    function makeSaveFromAddItem($fieldName, $fldCount, $iIndex)
    {
        //Index and field count should be real numbers not array index
        $iIndex = $iIndex + 1; 
        //Close up the array if we are at the end, else add , and new line
        if ($iIndex < $fldCount) {
            $comma = ",\n";
        } else {
            $comma = "\n            );\n";
        }
        switch ($fieldName) {
            //Id is generated
            case "id":
                $ret = NULL;
                break;
            case "creatorId":
            case "creatorid":
                $ret = "              '" . $fieldName 
                  . "' => \$this->objUser->fullName();\n";
                break;
            //The default method to return the getting of the value
            default:
                $ret = "              '" . $fieldName 
                  . "' => \$" . $fieldName . $comma;
                break;
        }
        return $ret;
    }
    
    /**
    * 
    * Simple method to insert text in place of {PURPOSE} in the generated
    * code. 
    * 
    * @return string The text
    * 
    */
    function getPurpose()
    {
        return "Data access class";
    }
}
?>