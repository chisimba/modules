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
}
?>