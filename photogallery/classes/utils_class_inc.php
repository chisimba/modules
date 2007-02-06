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
            $form .= ' <input type="button" onclick="getTheName()" name="submitform" value="'.$this->objLanguage->languageText('phrase_uploadfile', 'filemanager', 'Upload File').'" />';
            
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
             
        
        return $form.$form2->show();
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
       if(is_dir($newGalleryPath))
       {
          // return FALSE;
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
           $xml = simplexml_load_file($this->_objConfig->getSiteRootPath().'usrfiles/galleries/galleries.xml');
          
           $newArr = array();
           foreach ($xml as $gal => $k)
           {
               print($k->sitename);
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
   public function createThumbnail($image,$thumbpath)
   {
       try {
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
   
}
?>