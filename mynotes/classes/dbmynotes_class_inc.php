<?php
/**
 *
 * Database access for My notes
 *
 * Database access for My notes. This is a sample database model class
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
 * @package   mynotes
 * @author    Nguni Phakela nguni52@gmail.com
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
* Database access for My notes
*
* Database access for My notes. This is a sample database model class
* that you will need to edit in order for it to work.
*
* @package   mynotes
* @author    Nguni Phakela nguni52@gmail.com
*
*/
class dbmynotes extends dbtable
{

    /**
    *
    * Intialiser for the mynotes database connector
    * @access public
    * @return VOID
    *
    */
    public function init()
    {
        //Set the parent table to our demo table
        parent::init('tbl_mynotes_text');
    }

    /**
     *
     * Get the text of the init_overview that we have in the sample database.
     *
     * @return string The text of the init_overview
     * @access public
     *
     */
    public function getOverview()
    {
        return $this->getAll(' WHERE id=\'init_overview\' ');
    }
    
    /*
     * Method to save a note to the database
     * 
     */
    public function insertNote($data) {
        $this->insert($data);        
    }
    
    /*
     * Method to edit a note in the database
     * 
     */
    public function updateNote($data, $id) {
        $this->update('id', $id, $data);        
    }
    
    /**
     * Method to delete a note.
     *
     * @access public
     * @param string $id The id of the note.
     * @return
     */
    public function deleteNote($id) {
        $this->delete('id', $id);
    }
    
    /**
     * Method to return notes for a user
     * 
     * @access public
     * @param string $id The id of the user's notes
     * @return array The note array 
     */
    public function getNotes($uid, $limit = NULL)
    {
        return $this->fetchAll(" WHERE `userid` = '$uid' ORDER BY datemodified DESC ".$limit );
    }
    
    /*
     * Method to return a note
     * 
     * @access public
     * @param string $id The id of the note
     * @return array The note array
     */
    public function getNote($id) {
        return $this->getRow('id', $id);
    }
    
    /*
     * Method to search the notes based on a tag
     * 
     */
    public function getNotesWitTags($searchKey) {
        return $this->fetchAll(" WHERE `tags` LIKE '%".$searchKey."%'");
    }

}
?>