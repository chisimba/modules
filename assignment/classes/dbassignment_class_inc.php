<?php
/**
 *
 * Assignments
 *
 * Assignments enable students to view a list of booked assignments. The status is displayed indicating whether it is open, closed or if the student has submitted. The mark is shown once it has been marked.A new assignment can be opened for answering. Students can complete the assignment if its online and submit it. An uploadable or offline assignment can be completed and then loaded into the database. A marked assignment can be opened and the lecturer's comment can be viewed.
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
 * @package   assignment2
 * @author    Tohir Solomons _EMAIL
 * @copyright 2007 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   CVS: $Id$
 * @link      http://avoir.uwc.ac.za
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
* Database accesss class for Chisimba for the module assignment
*
* @author Tohir Solomons
* @package assignment
*
*/
class dbassignment extends dbtable
{

    /**
    *
    * Intialiser for the assignment2 controller
    * @access public
    *
    */
    public function init()
    {
        //Set the parent table here
        parent::init('tbl_assignment');
        $this->objUser = $this->getObject('user','security');
    }
    
    public function getAssignment($id)
    {
        return $this->getRow('id', $id);
    }
    
    /**
    * Method to get an assignment from the database.
    * @param string $context The current context.
    * @param string $filter
    * @return array $data List of assignments
    */
    public function getAssignments($context, $filter=NULL)
    {
        $sql = " WHERE context='".$context."'";

        if($filter){
            $sql .= ' AND '.$filter;
        }
        $sql .= ' ORDER BY closing_date DESC';
        
        return $this->getAll($sql);
    }
    
    public function addAssignment($name, $context, $description, $resubmit, $format, $mark, $percentage, $opening_date, $closing_date)
    {
        return $this->insert(array(
                'name' => $name,
                'context' => $context,
                'description' => $description,
                'resubmit' => $resubmit,
                'format' => $format,
                'mark' => $mark,
                'percentage' => $percentage,
                'opening_date' => $opening_date,
                'closing_date' => $closing_date,
                'userid' => $this->objUser->userId(),
                'last_modified' => date('Y-m-d H:i:s',time()),
                'updated' => date('Y-m-d H:i:s',time())
            ));
    }
    
    public function updateAssignment($id, $name, $description, $resubmit, $mark, $percentage, $opening_date, $closing_date)
    {
        return $this->update('id', $id, array(
                'name' => $name,
                'description' => $description,
                'resubmit' => $resubmit,
                'mark' => $mark,
                'percentage' => $percentage,
                'opening_date' => $opening_date,
                'closing_date' => $closing_date,
                'userid' => $this->objUser->userId(),
                'last_modified' => date('Y-m-d H:i:s',time()),
                'updated' => date('Y-m-d H:i:s',time())
            ));
    }
    
    public function deleteAssignment($id)
    {
        $result = $this->delete('id', $id);
        
        return $result;
    }

}
?>
