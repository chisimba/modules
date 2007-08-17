<?php
/* ----------- data class extends dbTable for tbl_calendar------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }

/**
* class to control the utilty method for the events calendar
*
* @author Wesley Nitsckie
* @copyright (c) 2005 University of the Western Cape
* @package photogallery
* @version 1
*
*
*/
class utils extends object
{

    /**
     * Constructor
     */
    public function init()
    {
       $this->_objUser = $this->getObject('user', 'security');
       $this->objLanguage = & $this->getObject('language','language');
       $this->_objConfig = & $this->getObject('altconfig','config');
       $this->_objDBAlbum = & $this->getObject('dbalbum', 'photogallery');
       $this->_objDBImages = & $this->getObject('dbimages', 'photogallery');
       $this->galFolder = & $this->_objConfig->getcontentBasePath().'photogallery';
       $this->_objTags = & $this->getObject('dbtags', 'tagging');
       $this->_objFileMan = & $this->getObject('dbfile','filemanager');
    }

    /**
     * Method to get the navigation
     *
     */
    public function getNav()
    {
       // $this->loadClass('sidebar' , 'navigation');
        if ($this->_objUser->isLoggedIn())
        {
			
			
			$objSideBar = $this->getObject('sidebar', 'navigation');//new sidebar();
			$nodes[] = array('text' => 'View Photos', 'uri' => $this->uri(array('action' => 'front')),  'nodeid' => 'front');
			$nodes[] = array('text' => 'Overview', 'uri' => $this->uri(array('action' => 'overview')),  'nodeid' => 'overview');
			$nodes[] = array('text' => 'Comments', 'uri' => $this->uri(array('action' => 'comments')),  'nodeid' => 'comments');
			$nodes[] = array('text' => 'Upload', 'uri' => $this->uri(array('action' => 'uploadsection')),  'nodeid' => 'uploadsection');
			$nodes[] = array('text' => 'Edit', 'uri' => $this->uri(array('action' => 'editsection')),  'nodeid' => 'editsection');
			$nodes[] = array('text' => 'Flickr', 'uri' => $this->uri(array('action' => 'flickr')),  'nodeid' => 'flickr');
	
			return $objSideBar->show($nodes, $this->getParam('action'));
		} else {
			return FALSE;
		}
    }
  
    /**
     *Method to do the upload process
     *@return boolean
     *@access public
     *@param string $albumId optional     
     */
     public function doUpload($albumId = null)
     {
        
        //upload images and add entries to the database
        $results = $this->uploadImages();       
        
		//var_dump($results);
        //check the album 
        if($albumId == null)
        {
            //if new album then add to database and get the new id
            $albumId = $this->_objDBAlbum->createAlbum($this->getParam('albumtitle'));
        }
        
        $this->insertToImagesTable($albumId ,$results);
        
     }                   
  
  
  	/**
  	* Method to upload the photos
  	* @return array
  	*/
  	public function uploadImages()
  	{
		
		$objUpload =& $this->newObject('upload', 'filemanager');
		$objUpload->setUploadFolder('users/'.$this->_objUser->userId().'/photos/');
		$results = $objUpload->uploadFiles();
		
		return $results;
	}
  
  	/**
  	* Method to insert the details into the
  	* database
  	* @param array $results The results of the upload
	* @return boolean
	* @access public
	*/
	public function insertToImagesTable($albumId, $results = null)
	{
		if($results == null)
		{
			return FALSE;
		} 
		
		//$albumId = $this->getParam('albumselect');
		foreach($results as $result)
		{
			if($result['fileid'] != '')
			{
				$fields = array();
				$fields['file_id'] = $result['fileid'];
				$fields['album_id'] = $albumId;
			
				$this->_objDBImages->insertImageData($fields);
				
				if(!$this->_objDBAlbum->hasThumb($albumId))
				{
					$this->_objDBAlbum->update('id', $albumId, array('thumbnail' =>$result['fileid'] ));
				}	
				
				//	var_dump($fields);
			}
			
		}
		
	}
  
  
	/**
	* Method to save the edit for albums
	*/
	public function saveAlbumEdit()
	{
	
		
		$objDBAlbum = & $this->getObject('dbalbum', 'photogallery');
		$objDBImage = & $this->getObject('dbimages', 'photogallery');
		
		
		$isshared = ($this->getParam('isshared') == 'on')? 1 : 0;
		
		$fields = array('title' => $this->getParam('albumtitle'),                        
                        'no_pics' => $this->getParam('totalimages'),                       
                        'is_shared' => $isshared,
                        'description' => $this->getParam('description'),
                        'thumbnail' =>  $this->getParam('thumbselect'));
                        
                        //var_dump($fields);die;
		$objDBAlbum->updateAlbum($this->getParam('albumid'), $fields);
		
		$tagsArr = spliti(',',$this->getParam('tags'));
		$uri = $this->uri(array('action' => 'viewalbum', 'albumid' => $this->getParam('albumid')));
		
		$this->_objTags->insertTags($tagsArr, $this->_objUser->userId(), $this->getParam('albumid'), 'photogallery', $uri);
		
		$imgCnt = $this->getParam('totalimages');
		for($i = 0; $i < $imgCnt; $i++)
		{
		 	$id = $this->getParam($i.'-id');
			$fields = array('title' => $this->getParam($i.'-title'),
							'description' => $this->getParam($i.'-desc'));
			$objDBImage->updateImage($id, $fields);
		}
	}
  
  
	  /**
	  * Method to delete an album
	  * this will delete all images and comments on 
	  * album
	  * @param string $albumId
	  * @access public
	  */
  	public function deleteAlbum($albumId)
  	{
		$objDBAlbum = & $this->getObject('dbalbum', 'photogallery');
		$objDBImage = & $this->getObject('dbimages', 'photogallery');
		$objDBComments = & $this->getObject('dbcomments', 'photogallery');
		
		//get all in images
		$images = $objDBImage->getAll("WHERE album_id='".$albumId."'");
		foreach($images as $image)
		{
			
			$objDBComments->delete('file_id',$image['id']);
		}
		
		$objDBImage->delete('album_id', $albumId);
		$objDBAlbum->delete('id', $albumId);
	}
  
   /**
	  * Method to delete an image
	  * this will delete all  comments on 
	  * image
	  * @param string $imageId
	  * @access public
	  */
  	public function deleteImage($imageId)
  	{
  		$objDBImage = & $this->getObject('dbimages', 'photogallery');
		$objDBComments = & $this->getObject('dbcomments', 'photogallery');
		
		$objDBComments->delete('file_id', $imageId);
		$objDBImage->delete('id', $imageId);
  
  	}
  
  
  	/**
  	* Method to generate the navigation for the image
  	* @param string $imageId
  	* @access public
  	*/
  	public function getImageNav($imageId)
  	{
  	 	$link = $this->getObject('link' , 'htmlelements');
  	 	
  	 	$image = $this->_objDBImages->getRow('id', $imageId);
  	 	if($image['position'] > 1)
  	 	{
  	 	 	$sql = "WHERE album_id='".$image['album_id']."' AND position = ".(intval($image['position'])-1);
  	 	 	$prevImage = $this->_objDBImages->getAll($sql);	
			$prevStr = '<div class="imgprevious">';
			
			$link->href = $this->uri(array('action' => 'viewimage', 'albumid' => $image['album_id'] , 'imageid' => $prevImage[0]['id']));
			$link->link = '&laquo; prev';
			$link->extra = 'title="Previous Image"';
			
			$prevStr .= $link->show().'</div>';

		}
		
		$imageCount = count($this->_objDBImages->getAll("WHERE album_id= '".$image['album_id']."'"));
		
		if($imageCount != ($image['position']))
		{
			
			$sql = "WHERE album_id='".$image['album_id']."' AND position = ".(intval($image['position'])+1);
  	 	 	$nextImage = $this->_objDBImages->getAll($sql);	
			$nextStr = '<div class="imgnext">';
		
			$link->href = $this->uri(array('action' => 'viewimage', 'albumid' => $image['album_id'] , 'imageid' => $nextImage[0]['id']));
			$link->link = 'next &raquo;';
			$link->extra = 'title="Next Image"';
			
			$nextStr .= $link->show().'</div>';
		}
	
  	 	$nav = 
		'<div class="imgnav">'.
			$prevStr.
			$nextStr.
			'</div>';
			
		return $nav;
		
	}
  	
   /**
   * Method to get a list of tags for 
   * an image
   *  @param string $imageId
   * @return string
   */
   public function getTagLIst($imageId)
   {
		$link = $this->getObject('link', 'htmlelements');
		$tagsArr = $this->_objTags->getPostTags($imageId, 'photogallery');
		if(count($tagsArr) > 0 )
		{
			$cnt = 0; 	
			$max = count($tagsArr);
			foreach($tagsArr as $t)
			{
			
		 		$cnt++;
		 		$link->href = $this->uri(array('action'=>'deletetag' , 'tagid' => $t['id'], 'albumid' => $this->getParam('imageId')));
		 		$link->link = 'x';
		 		$tagsStr .= $t['meta_value'].'['.$link->show().']';
				$tagsStr .= ($cnt < $max) ? ', ' : '';
			//	$tagsStr .= ', ';
			
			}
		} else {
			$tagsStr = '';
		}
		
		return $tagsStr;
	}
  
  	
  /**
  * Method to generate a tag cloud
  * 
  * @return string
  */
  public function getPopular()
  {
		$objTagCloud = $this->getObject('tagcloud', 'utilities');
		//	return $objTagCloud->exampletags();
		
		$tags = $this->_objTags->getTagsByModule('photogallery');
		
		if (count($tags) > 0 )
		{
			foreach ($tags as $tag)
			{
			 	$weight = $this->_objTags->getRecordCount("WHERE meta_value='".$tag['meta_value']."'");
			// print $weight.' - '.$tag['meta_value'].'<br>';
			 	$uri = $this->uri(array('action' => 'popular', 'meta_value' => $tag['meta_value']));
			 	
				$objTagCloud->addElement($tag['meta_value'],$uri  , $weight, time());
			}
			
			return $objTagCloud->biuldAll();
		} else {
			return  $this->objLanguage->languageText("mod_photogallery_nopoptags", "photogallery");
		}
		
	
  }
  
  
  /**
  * Method to generate a list of images for a given tag
  * @param string $tag
  * @return string
  */
  public function getTaggedImages($tag)
  {
   		$objTag = $this->getObject('dbtags', 'tagging');
   		$taggedList = $objTag->getAll("WHERE meta_value='".$tag."' AND module='photogallery'");
   	//	print '<pre>';
   		
   	//	var_dump()
		//$images = $this->_objDBImages->getAll("WHERE is_shared=0 AND ORDER BY no_views DESC LIMIT 8");
		$objThumbnail = & $this->getObject('thumbnails','filemanager');
		$link = $this->getObject('link','htmlelements');
		
		$arrCheck = array();
		
		foreach($taggedList as $tagged)
		{
		 	if(!in_array($tagged['item_id'],$arrCheck))
		 	{
				
			
		 	$image = $this->_objDBImages->getRow('id', $tagged['item_id']);
		 	
			$str.='<div class="image">
					<div class="imagethumb">';
			$filename = $this->_objFileMan->getFileName($image['file_id']); 
	 		$path = $objThumbnail->getThumbnail($image['file_id'],$filename);
	 		$bigPath = $this->_objFileMan->getFilePath($image['file_id']);
		 	$link->href = $this->uri(array('action' => 'viewimage', 'albumid' => $image['album_id'],'imageid' => $image['id']));
		 	$link->link = '<img title="'.$image['title'].' - '.$image['no_views'].' Hits" src="'.$path.'" alt="'.$image['title'].'"  />';
		 	$link->extra = ' rel="lightbox" ';
			$str.=$link->show().'</div></div>';
			
			$arrCheck[] = $tagged['item_id'];
			}
		}
		
			return '<div style="padding: 10px; margin-top:10px; margin-left:5px; border: solid 2px #eee; width:550px; background: #f5f5f5;">
				<h2>Photos tagged as \''.$tag.'\'</h2>
				<div style = "display:table-row-group; margin-bottom:2em; top:300px; position:static; padding: 10px; margin-top:10px; margin-left:5px; border: solid 2px #eee; width:550px; background: #f5f5f5;">
			'.$str.'</div></div>';
	
	
  }
  
  /**
  * Method to get the top ten photos
  * 
  * @access public
  */
  public function getPopularPhotos()
  {
		$images = $this->_objDBImages->getAll("WHERE is_shared=0 ORDER BY no_views DESC LIMIT 8");
		$objThumbnail = & $this->getObject('thumbnails','filemanager');
		$link = $this->getObject('link','htmlelements');
		$str = '';
		if(count($images) > 0)
		{
			
			foreach($images as $image)
			{
				$str.='<div class="image"><div class="imagethumb">';
				$filename = $this->_objFileMan->getFileName($image['file_id']); 
		 		$path = $objThumbnail->getThumbnail($image['file_id'],$filename);
		 		$bigPath = $this->_objFileMan->getFilePath($image['file_id']);
			 	$link->href = $this->uri(array('action' => 'viewimage', 'albumid' => $image['album_id'],'imageid' => $image['id']));
			 	$link->link = '<img title="'.$image['title'].' - '.$image['no_views'].' Hits" src="'.$path.'" alt="'.$image['title'].'"  />';
			 	$link->extra = ' rel="lightbox" ';
				$str.=$link->show().'</div></div>';
			}
			
			return '<div style="padding: 10px; margin-top:10px; margin-left:5px; border: solid 2px #eee; width:550px; background: #f5f5f5;">
						<h3> Popular Images</h3>
						<div style = "display:table-row-group;  padding: 10px; margin-top:10px; margin-left:5px; border: solid 2px #eee; width:550px; background: #f5f5f5;">
						'.$str.'</div></div>';
		}
  }
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
   /**
    * Method to read the xml gallery file
    *
    * @return array
    */
   public function readGalleries()
   {
       try{
           $filestring = 'usrfiles/galleries/galleries.xml';
           if (file_exists($filestring)) {
               $xml = simplexml_load_file($filestring);

               //var_dump($xml);
            } else {
               exit('Failed to open test.xml.');
            }

            $gals = array();
            foreach ($xml as $gallery)
            {
               $gals[] = $gallery->sitename;
            }

            return $gals;
       }
        catch (customException $e)
        {
        	echo customException::cleanUp($e);
        	die();
        }
   }

   /**
    * Method to get the thumbnails for gallery
    * @param string $gallery The name of the gallery
    * @return array
    * @access public
    */
   public function getGalleryThumbs($gallery)
   {
       try{
           $filestring = 'usrfiles/galleries/'.$gallery.'/photos.xml';
           if (file_exists($filestring)) {
               $xml = simplexml_load_file($filestring);
            // print_r($xml->photos);
               //var_dump($xml);
            } else {
               exit('Failed to open test.xml.');
            }
           // var_dump($xml->photos);
            $gals = array();
            foreach ($xml->photos->photo as $gallery)
            {//var_dump($gallery);// $gallery->thumbpath;
               $gals[] = array('name' => $gallery['thumbpath'], 'width' => $gallery['thumbwidth'], 'height' => $gallery['thunkheight']) ;
            }

            return $gals;

       }
       catch (customException $e)
        {
        	echo customException::cleanUp($e);
        	die();
        }

   }

   /**
    * Method to generate the admin section
    * @return string
    * @access public
    *
    */
   public function getAdminSection()
   {
       $folder = '';
       if($this->_objUser->isAdmin())
       {
            $str = 'ADMIN SECTION';
            $form = '<form name="form1" id="form1" enctype="multipart/form-data" method="post" action="'.$this->uri(array('action' => 'upload', 'folder' => $folder)).'">';
            $form .='<input type="file" name="uploadedfile" size="40" />';
            $form .='<input type="hidden" name="galleryname" id="galleryname" size="40" />';
            $form .= ' <input type="submit" onclick="getTheName()" name="submitform" value="'.$this->objLanguage->languageText('phrase_uploadfile', 'filemanager', 'Upload File').'" />';

             $form .= '</form>';

             $form2 = $this->newObject('form' , 'htmlelements');
             $form2->action = $this->uri(array('action' => 'createfolder'));

             $inp = $this->newObject('textinput', 'htmlelements');
             $inp->name = 'newgallery';
             $inp->value = '';

             $but = $this->newObject('button','htmlelements');
             $but->value = 'Create Gallery';
             $but->setToSubmit();

             $form2->addToForm($inp);
             $form2->addToForm('&nbsp'.$but->show());


        return $form/*.$form2->show()*/;
            return $str;
       } else {

           return FALSE;
       }


   }

   /**
    * Method to create a new section
    * @param string $name
    * @return boolean
    * @access public
    */
   public function createGallery($name)
   {
       //check if the folder dont already exist
       $newGalleryPath = $this->_objConfig->getSiteRootPath().'usrfiles/galleries/'.$name;
       if(!is_dir($this->_objConfig->getSiteRootPath().'usrfiles/galleries'))
       {
          mkdir($this->_objConfig->getSiteRootPath().'usrfiles/galleries');
          $file = fopen($this->_objConfig->getSiteRootPath().'usrfiles/galleries/galleries.xml','wr');
           fwrite($file, '<galleries></galleries>');
           fclose($file);

       }

       if(is_dir($newGalleryPath))
       {
           return FALSE;
       } else {
           //create the folder
           mkdir($newGalleryPath);

           //create thumbnails folder
           mkdir($newGalleryPath.'/thumbnails');

           //create large folder
           mkdir($newGalleryPath.'/images');

           //create xml file
           $file = fopen($newGalleryPath.'/photos.xml','wr');
           fwrite($file, $this->getPhotoXMLContent($name));
           fclose($file);

         //read the xml file
           chmod($this->_objConfig->getSiteRootPath().'usrfiles/galleries/galleries.xml',0777);
           $xml = simplexml_load_file($this->_objConfig->getSiteRootPath().'usrfiles/galleries/galleries.xml');

           $newArr = array();
           foreach ($xml as $gal => $k)
           {

               $newArr[] = $k->sitename;
           }

           $newArr[] = $name;
           $this->writeGalleriesXML($newArr);




       }




       //add to xml

   }

   /**
    * Method to generate the photos.xml content
    * @return string
    * @param string $name
    */
   private function getPhotoXMLContent($name, $imagesArr = null)
   {

       $str = '<gallery
                base = ""
                background = "#FFFFFF"
                banner = "#F0F0F0"
                text = "#000000"
                link = "#0000FF"
                alink = "#FF0000"
                vlink = "#800080"
                date = "4/11/2006">
                	<sitename>'.ucwords($name).' Gallery</sitename>
                	<photographer></photographer>
                	<contactinfo></contactinfo>
                	<email></email>
                	<security><![CDATA[]]> </security>

                <banner font = "Arial" fontsize = "3" color =  "#F0F0F0"> </banner>
                <thumbnail base ="thumbnails/" font = "Arial" fontsize = "4" color = "#F0F0F0" border = "0" rows = "3" col = "5"> </thumbnail>
                <large base ="images/" font = "Arial" fontsize = "3" color = "#F0F0F0" border = "0"> </large>
                <photos id = "images">';

       if(is_array($imagesArr))
       {
           foreach ($imagesArr as $image)
           {
               $str .= '
               <photo
                path = "'.$image['path'].'"
                width = "'.$image['width'].'"
                height = "'.$image['height'].'"
                thumbpath = "'.$image['path'].'"
                thumbwidth = "'.$image['thumbwidth'].'"
                thumbheight = "'.$image['thumbheight'].'">
                </photo>

                ';
           }
       }

       $str .='

                </photos>
                </gallery>
                ';


       return $str;
   }

   /**
    * Write Galleries.xml file
    *
    * @param array $newArr
    */
   public function writeGalleriesXML($newArr)
   {
       $str = '<galleries>';
       foreach ($newArr as $name)
       {
           $str .='
              <gallery base="'.strtolower($name).'/" file="photos.xml">
                <sitename>'.ucwords($name).'</sitename>
              </gallery>';
       }
       $str .='</galleries>';

       chmod($this->_objConfig->getSiteRootPath().'usrfiles/galleries/galleries.xml', 0777);
       $file = fopen($this->_objConfig->getSiteRootPath().'usrfiles/galleries/galleries.xml', 'wr');
       fwrite($file,$str);
       fclose($file);
   }
   /**
    * Method to upload the file
    *
    */
   public function uploadImageFile()
   {
       try{


           //check if the gallery folder exist
           if($this->checkFolderExist() == FALSE)
           {
               $err = 'Cannot create folder!';
               return $err;
           }


          // var_dump(is_uploaded_file($_FILES['uploadedfile']['tmp_name']));
           //die;
           if (is_uploaded_file($_FILES['uploadedfile']['tmp_name']))
           {
                $newImage = strtolower($this->galFolder.'/images/'.$_FILES['uploadedfile']['name']);

                if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'],$newImage))
                {

                    //add the file to the database
                    $objDBFile = & $this->getObject('dbfile', 'filemanager');

                    //mimetype
                    $objMime = & $this->getObject('mimetypes','files');
                    $mimetype = $objMime->getMimeType($newImage);

                    //get the file size
                    $filesize = filesize($newImage);

                    //get the category
                    $category = 'images';

                    $version = 1;

                    $userId = null;

                    $description = $this->getParam('description');

                    $license = null;

                    $path =  strtolower('/photogallery/images/'.$_FILES['uploadedfile']['name']);

                    $line = $objDBFile->getRow('path',$path);
                    if(is_array($line))
                    {
                        $fileId = $line['id'];
                    } else {
                        $fileId = $objDBFile->addFile(
                                     strtolower($_FILES['uploadedfile']['name']),
                                     $path, $filesize, $mimetype, $category, $version, $userId, $description, $license);
                    }
                    //create the thumbnail
                    $ret = $this->createThumbnail($newImage, $fileId);

                    //generate new photo list text file
                    $this->syncImageList();
                }
           }


       //check if there is an entry in the database


       }
       catch (customException $e)
       {
        	echo customException::cleanUp($e);
        	die();
       }

   }

   /**
    * Method to check if the photogallery folder exist
    *
    *
    */
   public function checkFolderExist()
   {
       try {

           if(file_exists($this->galFolder))
           {
               $ret = TRUE;

               //check the images folder;
               $ret = (file_exists($this->galFolder.'/images') == TRUE ) ? TRUE : mkdir($this->galFolder.'/images',0777);

               //check the thumbs folder;
               $ret = (file_exists($this->galFolder.'/thumbs') == TRUE ) ? TRUE : mkdir($this->galFolder.'/thumbs',0777);

           } else {
               //create the folder

                $ret = mkdir($this->galFolder);

                //create images folder
                mkdir($this->galFolder.'/images',0777);

                //create thumbs folder
                mkdir($this->galFolder.'/thumbs',0777);

               //return FALSE;
           }

           if(!file_exists($this->galFolder.'/one.swf'))
           {
               //copy the flash file
               copy($this->getResourcePath('one.swf','photogallery'), $this->galFolder.'/one.swf');
           }

           return $ret;
       }
       catch (customException $e)
       {
        	echo customException::cleanUp($e);
        	die();
       }

   }



   /**
    * Upload a image to a gallery
    * @param string $gallery
    * @access public
    * @return boolean
    */
   public function UploadImage($gallery)
   {
       try{
           $gallery = strtolower($gallery);

           //move uploaded file to /$gallery/images
           if (is_uploaded_file($_FILES['uploadedfile']['tmp_name']) && $this->isImage($_FILES['uploadedfile']['type']))
           {

              $newImage = $this->_objConfig->getSiteRootPath().'usrfiles/galleries/'.$gallery.'/images/'.$_FILES['uploadedfile']['name'];

              if( move_uploaded_file($_FILES['uploadedfile']['tmp_name'],$newImage))
              {
                   $filename = $_FILES['uploadedfile']['name'];
                   chmod($newImage, 0777);
                   //create a thumbnail of the image
                   $thumbImage = $this->_objConfig->getSiteRootPath().'usrfiles/galleries/'.$gallery.'/thumbnails/'.$_FILES['uploadedfile']['name'];
                   $imgDetails = $this->createThumbnail($newImage,$thumbImage);
                   //add to the xml file
                   $arr = $this->readPhotoXML($gallery);
                   $thumbInfo =  getimagesize($thumbImage);
                   $imgDetails = getimagesize($newImage);
                   //add the uploaded file to the xml entries
                   $arr[] = array('path' => $filename, 'width' =>$imgDetails[0] , 'height' => $imgDetails[1], 'thumbheight' => $thumbInfo[0] , 'thumbwidth' => $thumbInfo[0]);
                   //write the xml file


                   $newGalleryPath = $this->_objConfig->getSiteRootPath().'usrfiles/galleries/'.$gallery;
                   chmod($newGalleryPath.'/photos.xml',0777);
                   $file = fopen($newGalleryPath.'/photos.xml','wr');
                   fwrite($file, $this->getPhotoXMLContent($gallery, $arr));
                   fclose($file);

              }
           }



            return TRUE;

       }
       catch (customException $e)
       {
        	echo customException::cleanUp($e);
        	die();
       }
   }

   /**
    * Method to read the photo xml file
    *
    * @param string $gallery
    * @access public
    */
   public function readPhotoXML($gallery)
   {
        //read the xml file
           $xml = simplexml_load_file($this->_objConfig->getSiteRootPath().'usrfiles/galleries/'.$gallery.'/photos.xml');
          //var_dump($xml->photos->photo[1]);

           $newArr = array();
           foreach ($xml->photos->photo as $gal => $k)
           {
              // print($k['path']);
              // $path = $k['path'];
              $newArr[] =array('path' => $k['path'], 'width' => $k['width'] , 'height' => $k['height'], 'thumbheight' => $k['thumbheight'] , 'thumbwidth' => $k['thumbnailwidth']);
           }

           return $newArr;
          // print_r($newArr);
         //  die;
   }
   /**
    * Method to create a thumbnail
    * @param string $image
    * @return boolean
    *
    */
   public function createThumbnail($image,$fileId)
   {
       try {
           $objResize = & $this->getObject('imageresize', 'files');
           $objResize->setImg($image);
           $objResize->resize(100, 100, TRUE);
          if ($objResize->canCreateFromSouce) {
              //$fileId = '1';
            $img = $this->galFolder.'/thumbs/'.$fileId.'.jpg';
        } else {
            $img = $this->galFolder.'/thumbs/'.$objResize->filetype.'.jpg';
        }

        // Save File
        return $objResize->store($img);


           /*
           $arrDetails = array();
           $newheight = 75;
           $newwidth = 75;
           list($width, $height) = getimagesize($image);
            $thumb = imagecreatetruecolor($newwidth, $newheight);
            $imgInfo = getimagesize($image);
            $mime = image_type_to_mime_type($imgInfo[2]);
            //die($mime);
            switch ($mime)
            {
                case 'image/jpeg':
                     $source = imagecreatefromjpeg($image);
                     break;
                case 'image/png':
                     $source = imagecreatefrompng($image);
                     break;
                case 'image/gif':
                     $source = imagecreatefromgif($image);
                     break;
                case 'image/bmp':
                     $source = imagecreatefrombmp($image);
                     break;
            }



            // Resize
            imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

            imagejpeg($thumb,$thumbpath);

            */
        }
       catch (customException $e)
       {
        	echo customException::cleanUp($e);
        	die();
       }
   }

   /**
    * Method to check if the uploaded file is an image
    * @param string $image
    * @return boolean
    */
   public function isImage($image)
   {
       return true;

       if(strchr($image, 'image'))
       {
           return TRUE;
       } else {
           FALSE;
       }
   }


   /**
    * Method to get the images list for admin
    *
    *
    */
   public function getImagesAdminList()
   {

       $objDBFile = & $this->getObject('dbfile', 'filemanager');

       $arr = $objDBFile->getAll("WHERE moduleuploaded='photogallery'");
      // var_dump($arr);die;
      return $arr;
   }

   /**
    * Merhod to delete the image
    * @param string fileId The file id
    */
   public function deleteImage2($id)
   {
       $objDBFile = & $this->getObject('dbfile', 'filemanager');
       $file = $objDBFile->getRow('id',$id);


       //remove image from system
       if(file_exists($this->galFolder.'/images/'.$file['filename']))
       {
           unlink($this->galFolder.'/images/'.$file['filename']);
       }

       //remove thumb from file system

       if(file_exists($this->galFolder.'/thumbs/'.$file['id'].'.'.$file['datatype']))
       {
           unlink($this->galFolder.'/thumbs/'.$file['id'].'.'.$file['datatype']);
       }

       //remove from database
       $objDBFile->delete('id',$id);

       $this->syncImageList();
   }

    /**
     * Method to sync the image list
     *
     */
    public function syncImageList()
    {
        $objDBFile = & $this->getObject('dbfile', 'filemanager');
        $list = $objDBFile->getAll("WHERE moduleuploaded='photogallery'");

        $str = 'img_path=usrfiles/photogallery/images/&tmb_path=usrfiles/photogallery/thumbs/&arr_imgs=';
        foreach ($list as $image)
        {
            $str .= $image['id'].'.'.$image['datatype'].','.$image['filename'].','.$image['description'].';'; //'tmb_01.jpg,image_01.jpg,Surfing;
        }

        $handle = fopen('usrfiles/photogallery/vfpg_config.txt','w+');
        fwrite($handle,strtolower($str));
        fclose($handle);


    }

    /**
     * Method to get the flash gallery
     *
     *
     */
    public function getFlashGallery()
    {
                $str = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="700" height="450">
          <param name="movie" value="'.$resourcePath.'">
          <param name="quality" value="high">
          <param name="wmode" value="transparent">
          <embed src="usrfiles/photogallery/one.swf?videopath=usrfiles/photogallery/" width="700" height="450" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" wmode="transparent"></embed>
        </object>';


            return $str;
    }
}
?>