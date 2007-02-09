<?
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	die("You cannot view this page directly");
}
// end security check

/**
*
* This is a parser for including SMAPs into content
*
* @author Derek Keats
* @category Chisimba
* @package timeline
* @copyright AVOIR and UWC
* @licence GNU/GPL
*
*/
class smapparser extends object
{
  
    /**
    * 
    * @var string $demoMap Holds the value of the demo timeline
    * 
    */
    public $demoMap;
    
    /**
    * 
    * @var string $uri Holds the value of the timeline to display
    * 
    */
    public $uri;

	/*
	* @var string $timeLineModuleLink Holds the link for the timeline module
	*/
    public $sMapModuleLink;
    
    /**
    *
    * Standard init method
    *
    * @access Public
    *
    */
    public function init()
    {
        $this->sMapModuleLink = $this->Uri(array(), "simplemap");
    }
    
    /*
     * 
     * Method to set the uri parameter for the timeline to be 
     * parsed.
     * 
     * @access public
     * @return string The URI for the timeline to be parsed
     * 
     */
	public function setMapUri($uri)
	{
	    $this->uri = $uri;
	    return TRUE;
	}
	
	/*
	 * 
	 * Method to get the timeline Uri as stored
	 * 
	 * @access public
	 * @return string The URI or Null if not set
	 * 
	 */
	public function getMapUri()
	{
	    if (isset($this->uri)) {
	        return $this->uri;
	    } else {
	        return NULL;
	    }
	}
	

    public function show()
    {
    	$objIframe = $this->getObject('iframe', 'htmlelements');
    	$objIframe->width = "100%";
    	$objIframe->height="600";
        $ret = $this->sMapModuleLink;
        $ret .= "&amp;mode=plain&amp;smap=" . urlencode($this->uri);
		$objIframe->src=$ret;
        //$ret = "<iframe height=\"600\" width=\"100%\" src=\"$ret\"></iframe>";
        return $objIframe->show();
    }
	}
?>