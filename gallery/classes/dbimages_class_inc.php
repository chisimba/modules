<?php
/**
 *
 * Database access for Image gallery
 *
 * Database access for Image gallery. This is a sample database model class
 * that you will need to edit in order for it to work.
 *
 * PHP version 5
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the
 * Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 * @category  Chisimba
 * @package   gallery
 * @author    Kevin Cyster kcyster@gmail.com
 * @copyright 2007 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   0.001
 * @link      http://www.chisimba.com
 *
 */

// security check - must be included in all scripts
if (!
/**
 * The $GLOBALS is an array used to control access to certain constants.
 * Here it is used to check if the file is opening in engine, if not it
 * stops the file from running.
 *
 * @global entry point $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 *
 */
$GLOBALS['kewl_entry_point_run'])
{
        die("You cannot view this page directly");
}
// end security check

/**
*
* Database access for Image gallery
*
* Database access for Image gallery. This is a sample database model class
* that you will need to edit in order for it to work.
*
* @package   gallery
* @author    Kevin Cyster kcyster@gmail.com
*
*/
class dbimages extends dbtable
{

    /**
     * 
     * The name of the table of the block
     *
     * @access public
     * @var string
     */
    public $table;
    
    /**
    *
    * Intialiser for the gallery database connector
     * 
    * @access public
    * @return VOID
    *
    */
    public function init()
    {
        //Set the parent table to our demo table
        parent::init('tbl_gallery_images');
        $this->table = 'tbl_gallery_images';
        
        $this->objUser = $this->getObject('user', 'security');
        $this->userId = $this->objUser->userId();
        
    }

    /**
     *
     * Method to delete gallery images
     * 
     * @access public
     * @param string $galleryId The id of the gallery images to be deleted
     * @return VOID 
     */
    public function deleteGalleryImages($galleryId)
    {
        return $this->delete('gallery_id', $galleryId);
    }
    
    /**
     *
     * Mehod to get the images in an album
     * 
     * @access public
     * @param string $albumId The id of the album to get images for
     * @return array The array of images 
     */
    public function getUserGalleryAlbumImages($albumId)
    {
        return $this->fetchAll("WHERE `album_id` = '$albumId' ORDER BY display_order ASC");
    }

    /**
     *
     * Method to delete gallery images
     * 
     * @access public
     * @param string $albumId The id of the album images to be deleted
     * @return VOID 
     */
    public function deleteAlbumImages($albumId)
    {
        return $this->delete('album_id', $albumId);
    }    

    /**
     *
     * Mehod to add an image to the album
     * 
     * @access public
     * @param array $data The image data to be added to the database
     * @return VOID
     */
    public function addUserGalleryAlbumImage($data)
    {
        return $this->insert($data);
    }

    /**
     *
     * Method to update gallery images
     * 
     * @access public
     * @param string $galleryId The id of the gallery to update images of
     * @param integer $shared 1 if the image is shared 0 if not
     * @return VOID 
     */
    public function updateGalleryAlbumImages($galleryId, $shared)
    {
        return $this->update('gallery_id', $galleryId, array('is_shared' => $shared));
    }
    
    /**
     *
     * Method to get all the images in a gallery
     * 
     * @access public
     * @param string $galleryId The id of the gallery to get images for
     * @return array $iamges The array of images 
     */
    public function getUserGalleryImages($galleryId)
    {
        return $this->fetchAll("WHERE `gallery_id` = '$galleryId'");
    }
}
?>