<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	die("You cannot view this page directly");
}
// end security check

/**
* 
* Abstract class defining basic get and set methods
*
* @author Derek Keats
* @category Chisimba
* @package generator
* @copyright AVOIR
* @licence GNU/GPL
*
*/
abstract class abgetset
{
	/**
	 * Standard init method
	 */
    function init()
    {
        
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
    
}
?>