<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check
/**
 * The photo gallery manages the galleries for different sections of the site including personal, context and site galleries
 * @author Wesley Nitsckie
 * @copyright 2004, University of the Western Cape & AVOIR Project
 * @license GNU GPL
 * @package photogallery
 **/

class photogallery extends controller
{
    
    /**
     * Constructor
     */
    public function init()
    {
        
       
        $this->_objDBContext = & $this->newObject('dbcontext', 'context');
        $this->_objUser = & $this->newObject('user', 'security');
        $this->_objUtils = & $this->newObject('utils');
        $this->objLanguage = & $this->newObject('language','language');
        $this->_objConfig = & $this->newObject('altconfig','config');
        $this->_objContextModules = & $this->newObject('dbcontextmodules', 'context');
        
        $this->appendArrayVar('headerParams', $this->getJavascriptFile('SpryData.js','photogallery'));
        
        $this->appendArrayVar('headerParams', $this->getJavascriptFile('SpryEffects.js','photogallery'));
        $this->appendArrayVar('headerParams', $this->getJavascriptFile('SpryXML.js','photogallery'));
        $this->appendArrayVar('headerParams', $this->getJavascriptFile('xpath.js','photogallery'));
        $str = '<link href="modules/photogallery/resources/css/screen.css" rel="stylesheet" type="text/css" />
                <script type="text/javascript">
                var dsGalleries = new Spry.Data.XMLDataSet("'.$this->_objConfig->getSiteRoot().'usrfiles/galleries/galleries.xml", "galleries/gallery");
                var dsGallery = new Spry.Data.XMLDataSet("'.$this->_objConfig->getSiteRoot().'usrfiles/galleries/{dsGalleries::@base}{dsGalleries::@file}", "gallery");
                var dsPhotos = new Spry.Data.XMLDataSet("'.$this->_objConfig->getSiteRoot().'usrfiles/galleries/{dsGalleries::@base}{dsGalleries::@file}", "gallery/photos/photo");
                </script>';
        $this->appendArrayVar('headerParams', $this->getJavascriptFile('gallery.js','photogallery'));
        $this->appendArrayVar('headerParams', $str);
        $this->setVar('bodyParams', ' id="gallery" ');
        $css = '<link rel="stylesheet" type="text/css" href="modules/photogallery/resources/drop_shadow.css" />';
        
        $this->appendArrayVar('headerParams',$css);
    }
    
    
    /**
     *The standard dispatch method
     */
    public function dispatch()
    {
        $this->setVar('pageSuppressXML',true);
        $action = $this->getParam("action");
        $this->setLayoutTemplate('layout_tpl.php');
       
        
        switch ($action)
        {
        	
          
            case null:
                return 'main_tpl.php';
            case 'galleries':
                $this->setVar('galleries', $this->_objUtils->readGalleries());
                return 'galleries_tpl.php';
           
            
                
        }
    }
    
    /**
     * Method to get the menu
     * @return string 
     */
    public function getMenu()
    {
        return $this->_objUtils->getNav();
    }
    
    /**
     * 
     */
    public function requiresLogin()
    {
        return FALSE;
    }
}

?>