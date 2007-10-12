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
   
   public function drawMapMsCross($size=array(), $extent=array(), $layers=array())
   {
	//dl('php_mapscript.dll');
	//header("Content-type: image/png");
	//$map = ms_newMapObj('zambezia2.map');
	// We create the map object based on the mapfile received as parameter
	$size = explode(" ",$size);
	$this->objMapserver->setSize($size[0], $size[1]);
	// and set the image size (resolution) based on mapsize parameter
	// Update: The map size must be setted before the extent, otherwise the extent 
	// will be adjusted to the aspect ratio of the map defined on SIZE parameter
	// of MAP object in your mapfile

	$extent = explode(" ",$extent);
	$this->objMapserver->setExtent($extent[0], $extent[1], $extent[2], $extent[3]);
	// We get the mapext parameter... split it on its 4 parts using 
	// the space character as splitter
	$layerslist=$layers;
	for ($layer = 0; $layer < $this->objMapserver->numlayers; $layer++) {
		$lay = $this->objMapserver->getLayer($layer);
		if ((strpos($layerslist,($this->objMapserver->getLayer($layer)->name)) !== false) || (($map->getLayer($layer)->group != "") && (strpos($layerslist,($map->getLayer($layer)->group)) !== false)))
		{
			// if the name property of actual $lay object is in $layerslist
			// or the group property is in $layerslist then the layer was requested
			//so we set the status ON... otherwise we set the stat to OFF
			@$lay->set(status,MS_ON);
		} 
		else {
			@$lay->set(status,MS_OFF);
		}
	}

	// The next lines are the same as previous mapscript
	$image = $this->objMapserver->draw();
	$imagename = $image->saveWebImage();
	$retimage = ImageCreateFromPng('/ms4w/tmp'.$imagename);
	//return $imagename; 
	}   
}
?>