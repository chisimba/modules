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
        $this->gLat = $this->getParam('gLat', '-33.799669');
        $this->gLong = $this->getParam('gLong', '18.364472');
        $this->magnify = $this->getParam('magnify', '6');
        //Get an instance of the language object
        $this->objLanguage = $this->getObject('language', 'language');
    }
    
    function show()
    {
    	$str = $this->insertMapLayer();
    	$str .= $this->setupScript();
    	$myMap = $this->getDemoFile();
    	
		return str_replace ("[[SMAP_INSERT_HERE]]", $myMap, $str);
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
    
    function getDemoFile()
    {
    	// Create the configuration object
        $objRsConfig = $this->getObject('altconfig', 'config');
        //Set the file type and get the file into a string
		$this->fileType = "smap";        
        $filename =  "http://localhost/" . $objRsConfig->getItem('MODULE_URI') . "simplemap/resources/jsmaps/madiba.smap";
        $handle = fopen($filename, "r");
		$contents = stream_get_contents($handle);
		fclose($handle);
        return $contents;
    }
    
    function getNoScript()
    {
    	return "<noscript>" . $this->objLanguage->languageText("mod_simplemap_noscript", "simplemap") .  "</noscript>\n\n";
    
    }
    
    function setupScript()
    {		
    	
    	$ret = $this->getNoScript();
    	$lat = $this->gLat;
    	        
    	$long = $this->gLong;
    	$mag = $this->magnify;
    	$ret .="
            <script type=\"text/javascript\">
    		//<![CDATA[
		    if (GBrowserIsCompatible()) { 
		      // A function to create the marker and set up the event window
		      // Dont try to modify this function. It has to be here for the map to display
		      // Each instance of the function preserves the contents of a different instance
		      // of the \"marker\" and \"html\" variables which will be needed later when the event triggers.    
		      function createMarker(point,html) {
		        var marker = new GMarker(point);
		        GEvent.addListener(marker, \"click\", function() {
		          marker.openInfoWindowHtml(html);
		        });
		        return marker;
		      }
		      // Display the map, with some controls and set the initial location 
		      var map = new GMap2(document.getElementById(\"map\"));
		      map.addControl(new GLargeMapControl());
		      map.addControl(new GMapTypeControl());
		      map.setCenter(new GLatLng($lat,$long),$mag);
      
			  [[SMAP_INSERT_HERE]]
   
            }
    
		    // display a warning if the browser was not compatible
		    else {
		      alert(\"Sorry, the Google Maps API is not compatible with this browser\");
		    }
		    //]]>
		    </script>";
        return $ret;
    }
}
?>
