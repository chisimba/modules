<?php
/**
 *
 * Database access for grades
 *
 * Database access for grades. This is a sample database model class
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
 * @package   grades
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
* Database access for grades
*
* Database access for grades. This is a sample database model class
* that you will need to edit in order for it to work.
*
* @package   grades
* @author    Kevin Cyster kcyster@gmail.com
*
*/
class dbsubjectcontext extends dbtable
{

    /**
    *
    * Intialiser for the grades database connector
    * @access public
    * @return VOID
    *
    */
    public function init()
    {
        //Set the parent table to our demo table
        parent::init('tbl_grades_subjectcontext');
        $this->table = 'tbl_grades_subjectcontext';
        
        $this->objDBcontext = $this->getObject('dbcontext', 'context');
    }

    /**
     *
     * Method to return all links
     *
     * @access public
     * @return array $result An array of all the links
     *
     */
    public function getAll()
    {
        $result = $this->fetchAll();
        
        return $result;
    }

    /**
     *
     * Method to get links
     * 
     * @access public
     * @param string $id The id of the item to get links for
     * @return array 
     */
    public function getLinkedContexts($id)
    {
        $sql = "SELECT *, l.id AS id, c.id AS cid FROM $this->table AS l";
        $sql .= ' LEFT JOIN tbl_context AS c ON (l.context_id = c.id)';
        $sql .= " WHERE l.subject_id = '$id'";

        return $this->getArray($sql);   
    }
    
    /**
     *
     * Method to get links
     * 
     * @access public
     * @param string $id The id of the item to get links for
     * @return array 
     */
    public function getUnlinkedContexts($id)
    {
        $linkedContexts = $this->getLinkedContexts($id);
        if (!empty($linkedContexts))
        {
            $ids = array();
            foreach ($linkedContexts as $grade)
            {
                $ids[] = "'" . $grade['cid'] . "'";
            }
            $idString = implode(',', $ids);
        
            $sql = " SELECT * FROM tbl_context ";
            $sql .= "WHERE id NOT IN ($idString)";
            
            return $this->getArray($sql);
        }
        else
        {
            return $this->objDBcontext->fetchAll();
        }
    }
    
    /**
     * Method to add a link to the database
     * 
     * @access public
     * @param array @data The array of link data
     * @return string The id of the link added
     */
    public function insertData($data)
    {
        return $this->insert($data);
    }
    
    /**
     *
     * Method to delete a link
     * 
     * @access public
     * @param string $sid The id of the link to delete
     * return boolean 
     */
    public function deleteLink($id)
    {
        return $this->delete('id', $id);
    }

    /**
     *
     * Method to delete links
     * 
     * @access public
     * @param string $field The type of the linked component to delete
     * @param string $sid The id of the component to delete
     * return boolean 
     */
    public function deleteLinks($field, $id)
    {
        return $this->delete($field, $id);
    }
}
?>