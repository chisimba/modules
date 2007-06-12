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
class dbimages extends dbTable
{

    /**
     * Constructor
     */
    public function init()
    {
        parent::init('tbl_photogallery_images');
        $this->_objUser = $this->getObject('user', 'security');
        
    }
    
   /**
   * Method to insert images into the database
   * @return boolean|string
   * @access public
   */
   public function insertImageData($fields)
   {
		return $this->insert($fields);
	
	
	}
	
	/**
	* Method to the thumbnail
	*/
	public function getThumbNailFromFileId($fileId)
	{
		$objFile = $this->getObject('dbfile', 'filemanager');
		$file = $objFile->getFile($fileId);
		$objThumbnails = $this->getObject('thumbnails', 'filemanager');
		return $objThumbnails->getThumbnail($fileId,$file['filename']);
	}
	
	/**
	* Method to get the list of images for a user
	* @param string userId
	* @access public
	*/
	public function getAlbumImages($albumId)
	{		
		return $this->getAll("WHERE album_id='".$albumId."' ORDER BY position ");
		
	}
	
	/**
	* Method to update the images
	* @param string $id
	* @param array $fields
	* @access public
	*/
	public function updateImage($id, $fields)
	{
		return $this->update('id', $id, $fields);
	}
     
     
     /**
	* Increase the hit count for an album
	* @param string $albumId
	* @access public
	*/
	public function incrementHitCount($imageId)
	{
	 	$image = $this->getRow('id', $imageId);
	 	$views = array('no_views' => intval($image['no_views']) + 1 );
		$this->update('id', $imageId,$views);
	}
	
	/**
	* Method to reorder the ablums
	* 
	*/
	public function reOrderImages($albumId)
	{
		$order = str_replace('images[]=','',$this->getParam('imageOrder'));
		$newOrder = split('&',$order);
	
		$images = $this->getAlbumImages($albumId);
	   
		$cnt = 0;
		foreach($newOrder  as $arr)
		{
		 	$cnt++;		
			$this->update('id', $images[$arr-1]['id'], array('position' => $cnt));
		}
	}
	
	/**
	* Method to get the count of images
	* for an album
	*@param string $albumId
	*/
	public function getImageCount($albumId)
	{
		$images = $this->getAll("WHERE albumId = '.$albumId.'");
		return count($images);
	}
}
?>
