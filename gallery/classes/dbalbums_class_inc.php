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
class dbalbums extends dbtable
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
        parent::init('tbl_gallery_albums');
        $this->table = 'tbl_gallery_albums';
        
        $this->objUser = $this->getObject('user', 'security');
        $this->userId = $this->objUser->userId();
        
    }

    /**
     *
     * Get the galleries for the user.
     *
     * @access public
     * @param string $galleryId The id of the gallery to get albums for
     * @return array The array of gallery albums
     */
    public function getUserGalleryAlbums($galleryId)
    {        
        return $this->fetchAll("WHERE gallery_id='$galleryId' ORDER BY display_order ASC");

    }
    
    /**
     *
     * Method to delete gallery albums
     * 
     * @access public
     * @param string $galleryId The id of the gallery albums to be deleted
     * @return VOID 
     */
    public function deleteGalleryAlbums($galleryId)
    {
        return $this->delete('gallery_id', $galleryId);
    }

    /**
     *
     * Method to save an album
     * 
     * @access public
     * @param string $galleryId The id of the gallery the album is being added to
     * @param string $name The name of the album
     * @param string $desc The description of the album
     * @param string $shared 1 if the album is shared | 0 if not
     * @return VOID 
     */
    public function addUserGalleryAlbum($galleryId, $name, $desc, $shared)
    {
        $albums = $this->getUserGalleryAlbums($galleryId);
        $next = count($albums) + 1;
        
        $data = array();
        $data['gallery_id'] = $galleryId;
        $data['user_id'] = $this->userId;
        $data['name'] = $name;
        $data['description'] = $desc;
        $data['is_shared'] = ($shared == 'on') ? 1 : 0;
        $data['display_order'] = $next;
        $data['date_created'] = date('Y-m-d H:i:s');
        $data['created_by'] = $this->userId;
        
        return $this->insert($data);
    }
    
    /**
     *
     * Method to update an album
     * 
     * @access public
     * @param string $id The id of the album being updated
     * @param string $name The name of the album
     * @param string $desc The description of the album
     * @return VOID 
     */
    public function updateAlbum($id, $name, $desc)
    {
        $data = array();
        $data['name'] = $name;
        $data['description'] = $desc;
        $data['date_updated'] = date('Y-m-d H:i:s');
        $data['updated_by'] = $this->userId;
        
        return $this->update('id', $id, $data);
    }
    
    /**
     *
     * Method to get an album
     * 
     * @access public
     * @param string $id The id of the album to get
     * @return array The array of album data
     */
    public function getAlbum($id)
    {
        return $this->getRow('id', $id);
    }
    
    /**
     *
     * Method to delete an album
     * 
     * @access public
     * @param string $id The id of the album to delete
     * @return VOID
     */
    public function deleteAlbum($id)
    {
        return $this->delete('id', $id);
    }    
    
    /**
     *
     * Method to update gallery albums
     * 
     * @access public
     * @param string $galleryId The id of the gallery to update albums of
     * @param integer $shared 1 if the album is shared 0 if not
     * @return VOID 
     */
    public function updateGalleryAlbums($galleryId, $shared)
    {
        return $this->update('gallery_id', $galleryId, array('is_shared' => $shared));
    }
}
?>