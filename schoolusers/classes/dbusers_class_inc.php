<?php
/**
 *
 * Database access for schoolusers
 *
 * Database access for schoolusers. This is a sample database model class
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
 * @package   schoolusers
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
* Database access for schoolusers
*
* Database access for schoolusers. This is a sample database model class
* that you will need to edit in order for it to work.
*
* @package   schoolusers
* @author    Kevin Cyster kcyster@gmail.com
*
*/
class dbusers extends dbtable
{

    /**
    *
    * Intialiser for the schoolusers database connector
    * @access public
    * @return VOID
    *
    */
    public function init()
    {
        //Set the parent table to our demo table
        parent::init('tbl_users');
        $this->table = 'tbl_users';
    }

    /**
     *
     * Get the text of the init_overview that we have in the sample database.
     *
     * @return string The text of the init_overview
     * @access public
     *
     */
    public function getAllUsers()
    {
        return $this->fetchAll("WHERE isactive = '1'");
    }
    
    /**
     *
     * Get the text of the init_overview that we have in the sample database.
     *
     * @return string The text of the init_overview
     * @access public
     *
     */
    public function getUsers($start, $records)
    {
        return $this->fetchAll("WHERE isactive = '1' LIMIT $start, $records");
    }
    
    /**
     *
     * Method to get a user and the associated extra school data
     * 
     * @access public
     * @param string $id The id of the user to retieve
     * @return array The user data array 
     */
    public function getUser($id)
    {
        $sql = "SELECT *, u.id as id, d.id as data_id FROM $this->table AS u ";
        $sql .= "LEFT JOIN tbl_schoolusers_data as d ON (u.id = d.user_id) ";
        $sql .= "WHERE u.id = '$id'";
        
        $data = $this->getArray($sql);
        
        return $data[0];
    }
    
    /**
     *
     * Method to set a user as inactive (delete)
     * 
     * @access public
     * @param string $id The id of the user
     * @param array $data The user data to update 
     */
    public function updateUser($id, $data)
    {
        return $this->update('id', $id, $data);
    }
    
    /**
     *
     * Method to get the number of records
     * 
     * @access public
     * @return integer The number of records in the table 
     */
    public function getCount()
    {
        return $this->getRecordCount();
    }
}
?>