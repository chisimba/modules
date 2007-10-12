<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check
class gis extends controller
{
    public $objLog;
    public $objLanguage;
    public $objGisOps;
    public $objPostGis;
    public $objGisUtils;
    public $objMapserverOps;

    /**
     * Constructor method to instantiate objects and get variables
     */
    public function init()
    {
        try {
            $this->objLanguage = $this->getObject('language', 'language');
            $this->objGisOps = $this->getObject('gisops');
            $this->objGisUtils = $this->getObject('gisutils');
            $this->objMapserverOps = $this->getObject('mapserverops');
            //Get the activity logger class
            $this->objLog = $this->newObject('logactivity', 'logger');
            //Log this module call
            $this->objLog->log();
        }
        catch(customException $e) {
            echo customException::cleanUp();
            die();
        }
    }

    /**
     * Method to process actions to be taken
     *
     * @param string $action String indicating action to be taken
     */
    public function dispatch($action = Null)
    {
        switch ($action) {
            default:
            	//return the upload form
                  return 'upload_tpl.php';
            	break;
				
			case 'showmap':
			     //$this->objMapserverOps->initMapserver('zambezia2.map');
                 //$themap = $this->objMapserverOps->saveMapImage();
				 $themap = $this->objMapserverOps->drawMapMsCross();
                 $this->setVarByRef('themap', $themap);
				 return 'showmap_tpl.php';
				 break;
				 
            case 'addgeom':
            	$this->objPostGis = $this->getObject('dbpostgis');
            	$this->objGisOps = $this->getObject('gisops');
            	$this->objPostGis->addGeomToGeonames(27700);
            	//$this->objPostGis->createGeomFromPoints();
            	break;
               
            case 'uploaddatafile':
            	$this->objPostGis = $this->getObject('dbpostgis', 'gis');
            	$test = $this->objPostGis->isPostGIS();
            	if($test[0]['present'] == 't')
            	{
            		$filename = $this->getParam('shpzip');
            		$objFile = $this->getObject('dbfile', 'filemanager');
					$fpath = $objFile->getFullFilePath($this->getParam('shpzip'));
					$fname = $objFile->getFileName($this->getParam('shpzip'));
					$fpath = str_replace($fname, '', $fpath);
					$destpath = $fpath.'/shapes/';
					if(!file_exists($fpath))
					{
						mkdir($destpath, 0777);
					}
					$this->objGisUtils->unPackFilesFromZip($fpath.$fname, $destpath);    		
					$this->objPostGis->shp2pgsql($destpath);
            		chdir($destpath);
            		foreach(glob("*.{shp,dbf,shx}", GLOB_BRACE) as $delfiles)
            		{
            			unlink($delfiles);
            			//echo $delfiles;
            		}
            	}
            	else {
            		throw new customException($this->objLanguage->languageText("mod_gis_nopostgis", "gis"));
            	}
            	
            	$this->nextAction('');
            	break;
        }
    }
}
?>