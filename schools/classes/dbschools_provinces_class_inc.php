<?php
/**
 *
 * Database access for schools provinces
 *
 * Database access for schools. This is a sample database model class
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
 * @package   schools
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
* Database access for schools
*
* Database access for schools. This is a sample database model class
* that you will need to edit in order for it to work.
*
* @package   schools
* @author    Kevin Cyster kcyster@gmail.com
*
*/
class dbschools_provinces extends dbtable
{

    /**
    *
    * Intialiser for the schools database connector
    * @access public
    * @return VOID
    *
    */
    public function init()
    {
        //Set the parent table to our demo table
        parent::init('tbl_schools_provinces');
        $this->table = 'tbl_schools_provinces';
    }

    /**
     *
     * Get all of the provinces fo the schools module.
     *
     * @access public
     * @return array The array of provinces
     */
    public function getAllProvinces()
    {
        return $this->fetchAll();
    }

    /**
     * Method to return the province
     * 
     * @access public
     * @param integer $id The id of the province to get
     * @return array The array of province data
     */
    public function getProvince($id)
    {
        return $this->getRow('id', $id);
    }

    /**
     *
     * Method to delete a provincd
     * 
     * @access public
     * @param string $id The id of the province to delete
     * return boolean 
     */
    public function deleteProvince($id)
    {
        return $this->delete('id', $id);
    }
    
    /**
     * Method to add a province to the database
     * 
     * @access public
     * @param array @data The array of province data
     * @return string $id The id of the province  added
     */
    public function insertProvince($data)
    {
        return $this->insert($data);
    }    

    /**
     * Method to edit a province on the database
     * 
     * @access public
     * @param array @data The array of province data
     * @return string $id The id of the province edited
     */
    public function updateProvince($id, $data)
    {
        return $this->update('id', $id, $data);
    }
}
?>