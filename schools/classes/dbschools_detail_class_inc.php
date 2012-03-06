<?php
/**
 *
 * Database access for schools detail
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
class dbschools_detail extends dbtable
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
        parent::init('tbl_schools_detail');
        $this->table = 'tbl_schools_detail';
    }

    /**
     * This module gets the record count in the scholls detail table
     *
     * @return string The text of the init_overview
     * @access public
     *
     */
    public function getCount()
    {
        return $this->getRecordCount();
    }

    /**
     * Method to a school details to the database
     * 
     * @access public
     * @param array @data The array of school detail data
     * @return string $id The id of the school detail added
     */
    public function addSchool($data)
    {
        return $this->insert($data);
    }
    
    /**
     * Method to get the schools detail data
     * $join, $tblJoin
     * @access public
     * @return array $data The array of details for schools
     */
    public function getDetails()
    {
        $sql = 'SELECT *, d.id AS id, u.id AS uid, dis.id AS disid, prov.id AS provid FROM tbl_schools_detail AS d';
        $sql .= ' LEFT JOIN tbl_users AS u ON (d.principal_id = u.id)';
        $sql .= ' LEFT JOIN tbl_schools_districts AS dis ON (d.district_id = dis.id)';
        $sql .= ' LEFT JOIN tbl_schools_provinces AS prov ON (dis.province_id = prov.id)';

        return $this->getArray($sql);       
    }
    
    /**
     *
     * Method to delete school details
     * 
     * @access public
     * @param string $id The id of the school to delete
     * return boolean 
     */
    public function deleteDetail($id)
    {
        return $this->delete('id', $id);
    }

    /**
     * Method to return the districts for a province
     * 
     * @access public
     * @param integer $id The id of the province to get districts for
     * @return array The array of provinces for a district 
     */
    public function getDetail($id)
    {
        return $this->getRow('id', $id);
    }

    /**
     * Method to a school details to the database
     * 
     * @access public
     * @param array @data The array of school detail data
     * @return string $id The id of the school detail added
     */
    public function updateSchool($id, $data)
    {
        return $this->update('id', $id, $data);
    }
    
}
?>