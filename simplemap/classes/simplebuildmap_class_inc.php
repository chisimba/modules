<?
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	die("You cannot view this page directly");
}
// end security check

/**
*
* This is a simple class to build the basic Google Maps API
* as required to do a simple map.
*
* @author Derek Keats
* @category Chisimba
* @package simplemap
* @copyright AVOIR & UEC
* @licence GNU/GPL
*
*/
class simplebuildmap extends object 
{
    /**
    * 
    * @var $objConfig String object property for holding the 
    * configuration object
    * @access public
    * 
    */
    public $objConfig;
    public $width;
	public $height;
    
    
    /**
    *
    * Standard init method
    *
    */
    function init()
    {
        //Create the configuration object
        $this->objConfig = $this->getObject('dbsysconfig', 'sysconfig');
        $this->width = $this->getParam('width', '800');
        $this->height = $this->getParam('height', '600');
    }

    /**
    *
    * Method to return the google maps API key for the current site.
    * The API key is specific to site and directory, so if you change the 
    * directory your Chisimba installation is working from, then you
    * need to obtain a new key.
    * 
    * @access public
    * @return String The google API key 
    *
    */
    function getApiKey()
    {
		return $this->objConfig->getValue('mod_simplemap_apikey', 'simplemap');
    }
    
    function insertMapLayer()
    {
        return "<div id=\"map\" style=\"width: " . $this->width 
          . "px; height: " . $this->height . "px\"></div>";
    }
    
}
?>
