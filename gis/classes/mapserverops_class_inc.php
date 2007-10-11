<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
 * Class to handle mapserver elements
 * This object can be used elsewhere in the system to render certain aspects of the interface
 *
 * @author Paul Scott
 * @copyright GNU/GPL, AVOIR
 * @package gis
 * @access public
 */
class mapserverops extends object
{
	public $objConfig;
        public $objMapserver;
        public $image;
        public $image_url;

    /**
     * Standard init function called by the constructor call of Object
     *
     * @param void
     * @return void
     * @access public
     */
    public function init()
    {
        try {
            $this->objLanguage = $this->getObject('language', 'language');
            $this->loadClass('href', 'htmlelements');
        }
        catch(customException $e) {
            echo customException::cleanUp();
            die();
        }
    }

    /**
     * Mapserver init function. 
     *
     * Public method to create the mapserver instance and make it available as a class property
     * @access public
     * @param string - mapfile
     * @return object - mapserver object
     */
     public function initMapserver($mapfile)
     {
         dl('php_mapscript.dll');
         $this->objMapserver = ms_newMapObj($mapfile);
     }

     public function saveMapImage()
     {

	$this->image = $this->objMapserver->draw();
        $this->image_url = $this->image->saveWebImage();
        return $this->image_url;
     }
     
     public function addLayer($name, $type, $attributes = array())
     {
        $layer = $this->objMapserver->ms_newShapeObj($type);
        foreach($attributes as $key => $attribute)
        {
           $layer->set($key[$value]);
        }

        return TRuE;
     }
     
}
?>