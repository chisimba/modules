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
      	$this->_objTags = $this->getObject('dbtags', 'tagging');
      	
      	
      	
    }


    /**
     *The standard dispatch method
     */
    public function dispatch()
    {
        $this->setVar('pageSuppressXML',true);
        $action = $this->getParam("action");
        $this->setLayoutTemplate('layout_tpl.php');
		//if($this->requiresLogin())
		//{
		 $css = '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('admin.css','photogallery').'" />';
		
		//} else {
			$css .= '<link rel="stylesheet" type="text/css" href="'.$this->getResourceUri('style/default.css','photogallery').'" />';
		//}
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
            	$this->initFlickr();
            	$this->setVar('sharedalbums',$this->_objDBAlbum->getSharedAlbums());
            	$this->setVar('flickralbums', $this->_objDBFlickrUsernames->getFlickrSharedAlbums());
            	$this->setVar('pageTitle', 'Photo Gallery');
            	return 'front_tpl.php';
            case 'viewalbum':
            	if($this->getParam('mode') == 'flickr')
            	{
            	 	$this->initFlickr();
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
            	 	$this->initFlickr();
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
				$this->initFlickr();
				var_dump($this->_objFlickr->photos_comments_addComment($this->getParam('imageid'), $this->getParam('comment')));die;
				return $this->nextAction('viewimage', array('albumid' => $this->getParam('albumid'), 'imageid' => $this->getParam('imageid')));
			
		
         
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
				$this->initFlickr();
            	$this->setVar('arrAlbum',$this->_objDBAlbum->getUserAlbums());
            	$this->setVar('flickrusernames',$this->_objDBFlickrUsernames->getUsernames());
                return 'edit_tpl.php';
               
        	case 'editalbum':
        		$this->setVar('album', $this->_objDBAlbum->getRow('id',$this->getParam('albumid')));
        		$this->setVar('thumbnails', $this->_objDBImage->getAlbumImages($this->getParam('albumid')));
        		$this->setVar('tagsStr', $this->_objUtils->getTagLIst($this->getParam('albumid')));
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
        	case 'savealbumdescription':
        		$this->setPageTemplate('');
				$this->setLayoutTemplate('');
        		$this->_objDBAlbum->saveDescription($this->getParam('albumid'), $this->getParam('description'));
        		echo $this->getParam('description');
        		break;
			//flickr	
        	case 'flickr':
        		$this->initFlickr();
        		if($this->getParam('msg') != '')
        		{
					$this->setVar('msg',$this->getParam('msg'));	
				}
        		
        		$this->setVar('usernames', $this->_objDBFlickrUsernames->getUsernames());
        		return 'flickr_tpl.php';
        	case 'validateflickusername':
        		$this->initFlickr();
        		if($this->_objFlickr->people_findByUsername($this->getParam('username')) == FALSE)
        		{  
				 	 $msg = 'The username you added was invalid';
				 	 
				} else {
	     			 $this->_objDBFlickrUsernames->addUsername();					
				}
				return $this->nextAction('flickr',array('msg' => $msg));
				
			case 'addtags':
				$uri = $this->uri(array('action' => 'viewalbum', 'albumid' => $this->getParam('albumid')));					
				
				$this->setPageTemplate('');
				$this->setLayoutTemplate('');
				$this->_objTags->insertTags(array($this->getParam('myinput'.$this->getParam('imageid'))), $this->_objUser->userId(), $this->getParam('imageid'), 'photogallery', $uri);	
				echo $this->_objUtils->getTagLIst($this->getParam('imageid'));
				break; 
				
			case 'deletetag':
			
				$this->_objTags->delete('id', $this->getParam('tagid'), 'tbl_tags');
				return $this->nextAction('editalbum',array('albumid' => $this->getParam('albumid')));
				
			case 'popular':
				$this->setVar('cloud',$this->_objUtils->getPopular());
				$this->setVar('imagelist', $this->_objUtils->getPopularPhotos());
				if($this->getParam('meta_value'))
				{
					$this->setVar('taggedImages', $this->_objUtils->getTaggedImages($this->getParam('meta_value')));
				}
				return 'popular_tpl.php';
			case 'deleteflickrusername':
         		$this->_objDBFlickrUsernames->deleteUsername($this->getParam('flickrusername'));
				return $this->nextAction('flickr');
				
				
        }
    }
    
    
    /**
    * Method to initialize flickr api
    */
    public function  initFlickr()
    {
      	$this->_objFlickr = new phpFlickr("710e95b3b34ad8669fe36534a8343773");
      
		//setup the proxy to get the flickr images
      	$objProxy = $this->getObject('proxy','utilities');
      	$arrProxy = $objProxy->getProxy();
      	$this->_objFlickr->setProxy($arrProxy['proxyserver'], $arrProxy['proxyport']);
      	
       
        //$this->_objFlickr->enableCache("db",KEWL_DB_DSN);
        $this->_objDBFlickrUsernames = $this->getObject('dbflickrusernames' , 'photogallery');
        

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
			case 'popular':
				return FALSE;
			case 'viewtag':
				return FALSE;			 
			default:
				return TRUE; 		
        	
        }
        
    }
}

?>
