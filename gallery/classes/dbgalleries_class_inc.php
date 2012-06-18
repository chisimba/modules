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
class dbgalleries extends dbtable
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
        parent::init('tbl_gallery_galleries');
        $this->table = 'tbl_gallery_galleries';
        
        $this->objUser = $this->getObject('user', 'security');
        $this->userId = $this->objUser->userId();
        
    }

    /**
     *
     * Get the galleries for the user.
     *
     * @access public
     * @return array The array of user galleries
     */
    public function getUserGalleries()
    {        
        return $this->fetchAll("WHERE `user_id` = '$this->userId' ORDER BY display_order ASC");

    }
    
    /**
     *
     * Method to save a gallery
     * 
     * @access public
     * @param string $name The name of the gallery
     * @param string $desc The description of the gallery
     * @param string $shared 1 if the gallery is shared | 0 if not
     * @return VOID 
     */
    public function addUserGallery($name, $desc, $shared)
    {
        $galleries = $this->getUserGalleries();
        $next = count($galleries) + 1;
        
        $data = array();
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
     * Method to update a gallery
     * 
     * @access public
     * @param string $id The id of the gallery
     * @param string $name The name of the gallery
     * @param string $desc The description of the gallery
     * @param string $shared 1 if the gallery is shared | 0 if not
     * @return VOID 
     */
    public function updateGallery($id, $name, $desc, $shared)
    {
        $data = array();
        $data['name'] = $name;
        $data['description'] = $desc;
        $data['is_shared'] = $shared;
        $data['date_updated'] = date('Y-m-d H:i:s');
        $data['updated_by'] = $this->userId;
        
        return $this->update('id', $id, $data);
    }
    
    /**
     *
     * Method to get a gallery
     * 
     * @access public
     * @param string $id The id of the gallery to get
     * @return array The galley data 
     */
    public function getGallery($id)
    {
        return $this->getRow('id', $id);
    }

    /**
     *
     * Method to delete a gallery
     * 
     * @access public
     * @param string $id The id of the gallery to be deleted
     * @return VOID 
     */
    public function deleteGallery($id)
    {
        return $this->delete('id', $id);
    }
}
?>