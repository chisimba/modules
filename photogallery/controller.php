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
require_once('classes/phpFlickr.php');

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
        $this->_objDBAlbum = & $this->getObject('dbalbum', 'photogallery');
        $this->_objDBImage = & $this->getObject('dbimages', 'photogallery');
        $this->_objFileMan = & $this->getObject('dbfile','filemanager');
        $this->_objDBComments = & $this->getObject('dbcomments','photogallery');
        $this->_objConfig = $this->getObject('altconfig', 'config');
        /*
        $this->_objFlickr = new phpFlickr("710e95b3b34ad8669fe36534a8343773");
        $this->_objFlickr->enableCache("db","mysql://root:@localhost/chisimba_framework");
        $this->_objDBFlickrUsernames = $this->getObject('dbflickrusernames' , 'photogallery');
/*

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
        */
    }


    /**
     *The standard dispatch method
     */
    public function dispatch()
    {
        $this->setVar('pageSuppressXML',true);
        $action = $this->getParam("action");
        $this->setLayoutTemplate('layout_tpl.php');
		if($this->requiresLogin())
		{
		 $css = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('admin.css','photogallery').'" />';
		
		} else {
			$css = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('style/default.css','photogallery').'" />';
		}
		$this->appendArrayVar('headerParams',$css);
        switch ($action)
        {

          //document.form1.galleryname.value = document.gallerySelect.option[document.gallerySelect.selectedIndex];
          
          	//front view
          	default:
			case null:
				if($this->_objUser->isLoggedIn())
				{
					return $this->nextAction('overview');
				} else {
					return $this->nextAction('front');
				}
          	
            case 'front':
            	if($this->_objUser->isLoggedIn())
				{
					$this->setVar('albums',$this->_objDBAlbum->getUserAlbums());
            	}
            	$this->setVar('sharedalbums',$this->_objDBAlbum->getSharedAlbums());
            	$this->setVar('flickralbums', $this->_objDBFlickrUsernames->getFlickrSharedAlbums());
            	$this->setVar('pageTitle', 'Photo Gallery');
            	return 'front_tpl.php';
            case 'viewalbum':
            	if($this->getParam('mode') == 'flickr')
            	{
            	 	
            	 	$this->setVar('images', $this->_objFlickr->photosets_getPhotos($this->getParam('albumid')));
					return 'viewalbum_flickr_tpl.php';
				}
            	$this->_objDBAlbum->incrementHitCount($this->getParam('albumid'));
            	$this->setVar('albums',$this->_objDBAlbum->getUserAlbums());
            	$this->setVar('images', $this->_objDBImage->getAlbumImages($this->getParam('albumid')));
            	return 'viewalbum_tpl.php';
            case 'viewimage':
            
            	if($this->getParam('mode') == 'flickr')
            	{
            	 	$this->setVar('albums', $this->_objFlickr->photosets_getInfo($this->getParam('albumid')));
            	 	$this->setVar('image', $this->_objFlickr->photos_getInfo($this->getParam('imageid') /*photosets_getPhotos($this->getParam('albumid')*/));
            	 	$this->setVar('comments',$this->_objFlickr->photos_comments_getList($this->getParam('imageid')));
					return 'viewimage_flickr_tpl.php';
				} else {
	            	$this->_objDBImage->incrementHitCount($this->getParam('imageid'));
	            	$this->setVar('albums',$this->_objDBAlbum->getUserAlbums());
	            	$this->setVar('images', $this->_objDBImage->getAlbumImages($this->getParam('albumid')));
	            	$this->setVar('comments',$this->_objDBComments->getImageComments($this->getParam('imageid')));
	            	$this->setVar('image',$this->_objDBImage->getRow('id',$this->getParam('imageid')));
	            	return  'viewimage_tpl.php';
	            }
			
			
			//comments
			case 'addcomment':
				$this->_objDBComments->addComment();
				return $this->nextAction('viewimage', array('albumid' => $this->getParam('albumid'), 'imageid' => $this->getParam('imageid')));
			
			case 'comments':
				$this->setVar('comments', $this->_objDBComments->getUserComments());
				return 'comments_tpl.php';
			case 'editcomment':
				$this->setVar('comment', $this->_objDBComments->getRow('id',$this->getParam('commentid')));
				return 'editcomment_tpl.php';
			case 'saveedit':
				$this->_objDBComments->saveEdit();
				return $this->nextAction('comments');
			case 'deletecomment':
				$this->_objDBComments->delete('id', $this->getParam('commentid'));
				return $this->nextAction('comments');
			case 'addflickrcomment':
			print $this->getParam('comment');
				var_dump($this->_objFlickr->photos_comments_addComment($this->getParam('imageid'), $this->getParam('comment')));die;
				return $this->nextAction('viewimage', array('albumid' => $this->getParam('albumid'), 'imageid' => $this->getParam('imageid')));
			
			//overview
			
			case 'null':
            	return $this->nextAction('uploadsection');
                //$this->appendArrayVar('bodyOnLoad', 'ind = document.forms[\'grid\'].gallerySelect.selectedIndex;  alert(document.forms[\'grid\'].gallerySelect.options[ind].value); ');
                // $this->setVar('admin', $this->);
                //$this->setVar('resourcePath', $this->getResourceUri('','photogallery'));
                $this->setVar('resourcePath', 'usrfiles/photogallery/');
                return 'main_tpl.php';
            case 'galleries':
                $this->setVar('galleries', $this->_objUtils->readGalleries());
                return 'galleries_tpl.php';
            case 'createfolder':

                $this->_objUtils->createGallery($this->getParam('newgallery'));
                return $this->nextAction(null);
           /*
            case 'admin';
                $this->setVar('imageArr', $this->_objUtils->getImagesAdminList());
                return 'admin_tpl.php';
            case 'admin2';
                //$this->setVar('imageArr', $this->_objUtils->getImagesAdminList());
                return 'admin2_tpl.php';

            case 'deleteimage':
                $this->_objUtils->deleteImage($this->getParam('fileid'));
                return $this->nextAction('admin');
            case 'sync':
                $this->_objUtils->syncImageList();
                return $this->nextAction(null);

*/
			//upload section
            case 'uploadsection':
            	if($this->getParam('errmsg') != '')
            	{
					$this->setVar('errmsg',$this->getParam('errmsg'));	
				}
                $this->setVar('albumbsArr',$this->_objDBAlbum->getUserAlbums());
                return 'upload_tpl.php';
            case 'upload':
                //$this->_objUtils->UploadImage($this->getParam('galleryname'));
                if($this->getParam('albumselected') == '' && $this->getParam('albumtitle') == '')
                {
					$errmsg = 'Please supply a name for your new ablum';
					return $this->nextAction('uploadsection', array('errmsg' => $errmsg));
				}
			//	print '<pre>';
			//	print_r($_FILES);
				if(count($_FILES)<1)
                {
					$errmsg= 'Please select at least one file to upload';
					return $this->nextAction('uploadsection', array('errmsg' => $errmsg));
				}
                
                $this->_objUtils->doUpload($this->getParam('albumselect'));
                
				return $this->nextAction('uploadsection');
                
                
            case 'overview':
            	$this->setVar('tencomments', $this->_objDBComments->getTenRecentComments());
                return 'overview_tpl.php';
            
			//edit section
			case 'editsection':
            	$this->setVar('arrAlbum',$this->_objDBAlbum->getUserAlbums());
            	$this->setVar('flickrusernames',$this->_objDBFlickrUsernames->getUsernames());
                return 'edit_tpl.php';
               
        	case 'editalbum':
        		$this->setVar('album', $this->_objDBAlbum->getRow('id',$this->getParam('albumid')));
        		$this->setVar('thumbnails', $this->_objDBImage->getAlbumImages($this->getParam('albumid')));
        		return 'editalbum_tpl.php';
        	case 'savealbumedit':
        		$this->_objUtils->saveAlbumEdit();
//        		return $this->nextAction('editalbum',array('albumid' => $this->getParam('albumid')));
        	case 'savealbumorder':
        		$this->_objDBAlbum->reOrderAlbums();
        		return $this->nextAction('editsection');
        	case 'deletealbum':
        		$this->_objUtils->deleteAlbum($this->getParam('albumid'));
        		return $this->nextAction('editsection');
        	case 'deleteimage':
        		$this->_objUtils->deleteImage($this->getParam('imageid'));
        		return $this->nextAction('editalbum',array('albumid' => $this->getParam('albumid')));
        	case 'sortalbumimages':
        		$this->setVar('album', $this->_objDBAlbum->getRow('id',$this->getParam('albumid')));
        		$this->setVar('thumbnails', $this->_objDBImage->getAlbumImages($this->getParam('albumid')));
        		return 'orderimages_tpl.php';
        	case 'saveimageorder':
        		$this->_objDBImage->reOrderImages($this->getParam('albumid'));
        		return $this->nextAction('sortalbumimages',array('albumid' => $this->getParam('albumid')));
        		
        	case 'flickr':
        		$this->setVar('usernames', $this->_objDBFlickrUsernames->getUsernames());
        		return 'flickr_tpl.php';
        	case 'validateflickusername':
        		if($this->_objFlickr->people_findByUsername($this->getParam('username')) == FALSE)
        		{  
				 	 $msg = 'The username you added was invalid';
				} else {
	     			 $this->_objDBFlickrUsernames->addUsername();					
				}
				return $this->nextAction('flickr');
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
     //var_dump( $this->getParam('action'));
     //die;
     	switch ($this->getParam('action'))
        {	
         	default:
         		return  FALSE;
         	case null:
         		return  FALSE;
         	case 'front':
         		return  FALSE;
        	case 'viewalbum':
        		return  FALSE;
        	case 'viewimage':
				return FALSE;
			case 'addcomment':
				return FALSE;	
			case 'addflickrcomment':
				return FALSE;				 
			default:
				return TRUE; 		
        	
        }
        
    }
}

?>
