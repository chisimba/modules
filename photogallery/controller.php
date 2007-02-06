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
        
       
        $this->_objDBContext = & $this->getObject('dbcontext', 'context');
        $this->_objUser = & $this->getObject('user', 'security');
        $this->_objUtils = & $this->getObject('utils');
        $this->objLanguage = & $this->getObject('language','language');
        $this->_objConfig = & $this->getObject('altconfig','config');
        $this->_objContextModules = & $this->getObject('dbcontextmodules', 'context');
        
    
        
        $this->appendArrayVar('headerParams', $this->getJavascriptFile('SpryData.js','photogallery'));
        
        $this->appendArrayVar('headerParams', $this->getJavascriptFile('SpryEffects.js','photogallery'));
        $this->appendArrayVar('headerParams', $this->getJavascriptFile('SpryXML.js','photogallery'));
        $this->appendArrayVar('headerParams', $this->getJavascriptFile('xpath.js','photogallery'));
        $str = '<link href="'.$this->getResourceUri('screen.css','photogallery').'" rel="stylesheet" type="text/css" />
                <script type="text/javascript">
                var dsGalleries = new Spry.Data.XMLDataSet("'.$this->_objConfig->getSiteRoot().'usrfiles/galleries/galleries.xml", "galleries/gallery", { useCache:  false });
                var dsGallery = new Spry.Data.XMLDataSet("'.$this->_objConfig->getSiteRoot().'usrfiles/galleries/{dsGalleries::@base}{dsGalleries::@file}", "gallery",{ useCache:  false });
                var dsPhotos = new Spry.Data.XMLDataSet("'.$this->_objConfig->getSiteRoot().'usrfiles/galleries/{dsGalleries::@base}{dsGalleries::@file}", "gallery/photos/photo",{ useCache:  false });
                
                </script>';
        $this->appendArrayVar('headerParams', $this->getJavascriptFile('gallery.js','photogallery'));
        $this->appendArrayVar('headerParams', $str);
        $this->setVar('bodyParams', ' id="gallery" ');
        $css = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('drop_shadow.css','photogallery').'" />';
        
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
        	
          //document.form1.galleryname.value = document.gallerySelect.option[document.gallerySelect.selectedIndex];
            case null:
                //$this->appendArrayVar('bodyOnLoad', 'ind = document.forms[\'grid\'].gallerySelect.selectedIndex;  alert(document.forms[\'grid\'].gallerySelect.options[ind].value); ');
                $this->setVar('admin', $this->_objUtils->getAdminSection());
                return 'main_tpl.php';
            case 'galleries':
                $this->setVar('galleries', $this->_objUtils->readGalleries());               
                return 'galleries_tpl.php';
            case 'createfolder':
           
                $this->_objUtils->createGallery($this->getParam('newgallery'));
                return $this->nextAction(null);
            case 'upload':
                $this->_objUtils->UploadImage($this->getParam('galleryname'));
                return $this->nextAction(null);
                
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