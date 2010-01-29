<?php

/**
 * Triplestore data access class
 * 
 * Class to interact with the database for the triplestore module.
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
 * @category  chisimba
 * @package   triplestore
 * @author    Charl van Niekerk <charlvn@charlvn.com>
 * @copyright 2010 Charl van Niekerk / AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za
 */

class dbtriplestore extends dbTable 
{
    /**
     * Constructor
     */
    public function init()
    {
        parent::init('tbl_triplestore');
    }

    /**
     * Adds a new triple into the triplestore.
     *
     * @access public
     * @param  string $subject   The subject of the triple.
     * @param  string $predicate The predicate of the triple.
     * @param  string $object    The object of the triple.
     * @return string The autogenerated id of the new triple.
     */
    public function add($subject, $predicate, $object)
    {
        // Compile the associative array of the new database row.
        $row              = array();
        $row['subject']   = $subject;
        $row['predicate'] = $predicate;
        $row['object']    = $object;

        // Insert the row into the database and get the autogenerated id.
        $id = $this->insert($row);

        // Return the new row's generated id.
        return $id;
    }

    /**
     * Modify an existing triple in the triplestore.
     *
     * @access public
     * @param  string  $id        The autogenerated id of the triple to edit.
     * @param  mixed   $subject   The new subject as a string or FALSE to leave as-is.
     * @param  mixed   $predicate The new predicate as a string or FALSE to leave as-is.
     * @param  mixed   $object    The new object as a string or FALSE to leave as-is.
     * @return boolean TRUE on success, FALSE on failure.
     */
    public function edit($id, $subject=FALSE, $predicate=FALSE, $object=FALSE)
    {
        // Initialise the row to be used as an associative array.
        $row = array();

        // If a new subject has been specified, add to the row array.
        if ($subject !== FALSE) {
            $row['subject'] = $subject;
        }

        // If a new predicate has been specified, add to the row array.
        if ($predicate !== FALSE) {
            $row['predicate'] = $predicate;
        }

        // If a new object has been specified, add to the row array.
        if ($object !== FALSE) {
            $row['object'] = $object;
        }

        // Attempt to apply the changes to the triplestore.
        $success = $this->update('id', $id, $row);

        // Return the result of the update.
        return $success;
    }
}

?>
