<?php
/**
 *
 * Assignments
 *
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
 * @copyright 2007 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
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
     * Method to search assignments and return the results.
     * @param string $field The table field in which to search.
     * @param string $value The value to search for.
     * @param string $context The current context.
     * @return array $data The results of the search.
     */
        public function search($field, $value, $context)
        {
            $sql = "SELECT * FROM tbl_assignment";
            $sql .= " WHERE $field LIKE '$value%'";
            $sql .= " AND context='$context'";
            $sql .= ' ORDER BY closing_date';

            $data = $this->getArray($sql);

            if($data){
                return $data;
            }
            return FALSE;
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

        /**
         *  Add new
         * @param <type> $name
         * @param <type> $context
         * @param <type> $description
         * @param <type> $resubmit
         * @param <type> $format
         * @param <type> $mark
         * @param <type> $percentage
         * @param <type> $opening_date
         * @param <type> $closing_date
         * @return <type>
         */
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
        /**
         *
         * @param <type> $id
         * @param <type> $name
         * @param <type> $description
         * @param <type> $resubmit
         * @param <type> $mark
         * @param <type> $percentage
         * @param <type> $opening_date
         * @param <type> $closing_date
         * @return <type>
         */
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
    /**
     * delete an sssignment
     * @param <type> $id
     * @return <type>
     */
        public function deleteAssignment($id)
        {
            $result = $this->delete('id', $id);

            return $result;
        }

    }
?>
