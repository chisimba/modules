<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	die("You cannot view this page directly");
}
// end security check

/**
* 
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
*
*/
abstract class abgenertor
{
    /**
    *
    * @var string $mdouleName String the name of the module being created
    *
    */
	 protected $mdouleName;
	 
    /**
    *
    * @var string $className String the name of the class being created
    *
    */
	 protected $className;

	/**
	 * 
	 * Method to return the initial security settings for the top of the page
	 * 
	 */
	 function getIntialSecurity()
	 {
	     $ret = "// security check - must be included in all scripts\n"
			. "if (!$GLOBALS['kewl_entry_point_run']) {\n"
			. "	die(\"You cannot view this page directly\");\n}\n"
			. "// end security check\n\n";
		 return $ret;
	 }
	 
	 /**
	  * 
	  * Method to return the initial PHP <?php code
	  * 
	  */
	 function startPhp()
	 {
	     return "<?php\n";
	 }
	 
	 /**
	  * 
	  * Method to return the closing ?> php code
	  * 
	  */
	  function endPhp()
	  {
	      return "?>";
	  }
 
	/**
    *
    * Method to set the values of protected/private properties. Note that it 
    * prevents the sloppy approach of adding poperties that are not defined.
    *
    * @param string $itemName The name of the property whose value is being set.
    * @param string $itemValue The value of the property being set
    *
    */
    public function setValue($itemName, $itemValue)
    {
		  if (property_exists($itemName, $this)) {
		      $this->$itemName = $itemValue;
		      return TRUE;
		  } else {
		      return FALSE;
		  }
    }
    
    /**
    *
    * Method to set the values of protected/private properties
    *
    * @param string $itemName The name of the property whose value is being retrieved.
    *
    */
    public function getValue($itemName)
    {
    	  if (isset($this->$itemName)) {
            return $this->$itemName;
        } else {
            return NULL;
        }
    }
    
    /**
     * 
     * Method to prepare the object code for the insertion
     * 
     */
     function prepare()
     {
         $ret = $this->startPhp() . $this->getIntialSecurity()
         . "{WORKAREA}" . $this->endPhp(); 
     }
}
?>