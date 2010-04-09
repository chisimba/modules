<?php

/**
 * Triplestore data access class
 * 
 * Class to facilitate interaction with the framework triplestore.
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
 * @copyright 2010 Charl van Niekerk
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za/
 */

// security check - must be included in all scripts
if (!
/**
 * Description for $GLOBALS
 * @global unknown $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
 * Triplestore data access class
 * 
 * Class to facilitate interaction with the framework triplestore.
 * 
 * @category  chisimba
 * @package   triplestore
 * @author    Charl van Niekerk <charlvn@charlvn.com>
 * @copyright 2010 Charl van Niekerk
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za/
 */

class dbtriplestore extends dbTable
{
    /**
     * Instance of the user object of the security class.
     *
     * @access private
     * @var    object
     */
    private $objUser;

    /**
     * Initialises the instance of the triplestore data access class.
     */
    public function init()
    {
        // Set the associated database table name.
        parent::init('tbl_triplestore');

        // Instance of the user object of the security class.
        $this->objUser = $this->getObject('user', 'security');
    }

    /**
     * Returns an easy to use object to access and modify triples associated with a particular subject.
     *
     * @access public
     * @param  string $subject The id of the subject.
     * @return object Instance of the triplesubject class representing the subject requested.
     */
    public function __get($subject)
    {
        // Retrieve the triples associated with the subject.
        $triples = $this->getAll("WHERE subject = '$subject'");

        // Initialise and populate a new instance of the triplesubject class.
        $objSubject = $this->newObject('triplesubject', 'triplestore');
        $objSubject->populate($subject, $triples);

        // Return the newly created triplesubject.
        return $objSubject;
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

    /**
     * Returns a nested associative array of the triples in the triplestore according to the filters provided.
     *
     * @access public
     * @param  array $filters Associative array of the filters to use. Empty array to return everything.
     * @return array The nested array of triples.
     */
    public function getNestedTriples($filters=array())
    {
        // Retrieve all the triples out of the triplestore.
        $triples = $this->getTriples($filters);

        // Initialise the data array to be returned.
        $subjects = array();

        // Add each triple to the data array.
        foreach ($triples as $triple) {
            // Ensure the subject array exists.
            if (!array_key_exists($triple['subject'], $subjects)) {
                $subject[$triple['subject']] = array();
            }

            // Ensure the predicate array exists on the subject.
            if (!array_key_exists($triple['predicate'], $subject[$triple['subject']])) {
                $subject[$triple['subject']][$triple['predicate']] = array();
            }

            // Add the object to the predicate array on the subject.
            $subjects[$triple['subject']][$triple['predicate']][$triple['id']] = $triple['object'];
        }

        // Return the nested array of data.
        return $subjects;
    }

    /**
     * Fetches an array of triples from the triplestore according to the filters provided.
     *
     * @access public
     * @param  array $filters Associative array of the filters to use. Empty array to return everything.
     * @return array The array of triples.
     */
    public function getTriples($filters=array())
    {
        // Build the where clause from the filters array.
        $where = array();
        foreach ($filters as $filterType => $filter) {
            $where[] = "$filterType = '$filter'";
        }
        $where = empty($where) ? NULL : ('WHERE ' . implode(' AND ', $where));

        // Retrieve all the triples out of the triplestore.
        $triples = $this->getAll($where);

        return $triples;
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
    public function insert($subject, $predicate, $object)
    {
        // Compile the associative array representing the new triple.
        $triple              = array();
        $triple['userid']    = $this->objUser->userId();
        $triple['date']      = date('Y-m-d H:i:s');
        $triple['subject']   = $subject;
        $triple['predicate'] = $predicate;
        $triple['object']    = $object;

        // Insert the triple into the triplestore.
        $id = parent::insert($triple);

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
}

?>
