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
     * Adds a new triple to the triplestore.
     *
     * @access public
     * @param  string $subject   The subject of the triple.
     * @param  string $predicate The predicate of the triple.
     * @param  string $object    The object of the triple.
     * @return string The autogenerated id of the new triple.
     */
    public function add($subject, $predicate, $object)
    {
        // Compile the associative array representing the new triple.
        $triple              = array();
        $triple['subject']   = $subject;
        $triple['predicate'] = $predicate;
        $triple['object']    = $object;

        // Insert the triple into the triplestore.
        $id = $this->insert($triple);

        // Return the new triple's autogenerated id.
        return $id;
    }

    /**
     * Modifies an existing triple in the triplestore.
     *
     * @access public
     * @param  string  $id        The autogenerated id of the triple to edit.
     * @param  mixed   $subject   The new subject as a string or FALSE to leave as-is.
     * @param  mixed   $predicate The new predicate as a string or FALSE to leave as-is.
     * @param  mixed   $object    The new object as a string or FALSE to leave as-is.
     * @return boolean TRUE on success, FALSE on failure.
     */
    public function update($id, $subject=FALSE, $predicate=FALSE, $object=FALSE)
    {
        // Initialise the associative array representing the update to the triple.
        $update = array();

        // If a new subject has been specified, add to the update array.
        if ($subject !== FALSE) {
            $update['subject'] = $subject;
        }

        // If a new predicate has been specified, add to the update array.
        if ($predicate !== FALSE) {
            $update['predicate'] = $predicate;
        }

        // If a new object has been specified, add to the update array.
        if ($object !== FALSE) {
            $update['object'] = $object;
        }

        // Attempt to apply the changes to the triplestore.
        $result = parent::update('id', $id, $update);

        // Return the result of the update.
        return $result;
    }

    /**
     * Deletes a triple from the triplestore.
     *
     * @access public
     * @param  string  $id The autogenerated id of the triple to delete.
     * @return boolean TRUE on success, FALSE on failure.
     */
    public function delete($id)
    {
        // Attempt to delete the triple from the triplestore.
        $result = parent::delete('id', $id);

        // Return the result of the delete.
        return $result;
    }
}

?>
