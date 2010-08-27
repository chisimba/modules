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
class dbtriplestore extends dbTable {

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
    public function init() {
        // Set the associated database table name.
        parent::init('tbl_triplestore');

        // Instance of the user object of the security class.
        $this->objUser = $this->getObject('user', 'security');
        //Instance of the xml serial class
        $this->objXMLSerial = $this->getObject('xmlserial', 'utilities');
    }

    /**
     * Returns an easy to use object to access and modify triples associated with a particular subject.
     *
     * @access public
     * @param  string $subject The id of the subject.
     * @return object Instance of the triplesubject class representing the subject requested.
     */
    public function __get($subject) {
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
    public function delete($id) {
        // Attempt to delete the triple from the triplestore.
        $result = parent::delete('id', $id);

        // Return the result of the delete.
        return $result;
    }

    /**
     * Exports triples in CSV format.
     *
     * @access public
     * @param  string $file            The name and path to the CSV file.
     * @param  string $subject         The name of the column containig the subject names.
     * @param  array  $filters         Associative array of the filters to use. Empty array to return everything.
     * @param  string $delimiter       The CSV delimiter to use.
     * @param  string $objectDelimiter The delimiter to use for multiple object values.
     */
    public function exportCSV($file, $subject, $filters=array(), $delimiter=',', $objectDelimiter='|') {
        $triples = $this->getNestedTriples($filters);
        $allPredicates = array();
        foreach ($triples as $objects) {
            $predicates = array_keys($objects);
            foreach ($predicates as $predicate) {
                if (!in_array($predicate, $allPredicates)) {
                    $allPredicates[] = $predicate;
                }
            }
        }
        sort($allPredicates);
        $handle = fopen($file, 'w');
        $fields = array_merge(array($subject), $allPredicates);
        fputcsv($handle, $fields, $delimiter);
        foreach ($triples as $subject => $objects) {
            $fields = array($subject);
            foreach ($allPredicates as $predicate) {
                if (array_key_exists($predicate, $objects)) {
                    $fields[] = implode($objectDelimiter, $objects[$predicate]);
                } else {
                    $fields[] = '';
                }
            }
            fputcsv($handle, $fields, $delimiter);
        }
        fclose($handle);
    }

    /**
     * Returns the subject of the current user.
     *
     * @access public
     * @return object The subject.
     */
    public function getCurrentUser() {
        $userId = $this->objUser->userId();
        $subject = $this->__get($userId);

        return $subject;
    }

    /**
     * Returns a nested associative array of the triples in the triplestore according to the filters provided.
     *
     * @access public
     * @param  array $filters Associative array of the filters to use. Empty array to return everything.
     * @return array The nested array of triples.
     */
    public function getNestedTriples($filters=array()) {
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
     * Returns a list of subjects according to the filters provided.
     *
     * @access public
     * @param  array $filters Associative array of the filters to use. Empty array to return all available subjects.
     * @return array The array of subjects.
     */
    public function getSubjects($filters=array()) {
        // Initialise the subjects array.
        $subjects = array();

        // Retrieve the subjects from the triplestore according to the filters provided.
        $triples = $this->getTriples($filters, array('subject'), 'subject');

        // Loop through the triples and populate the subjects array.
        foreach ($triples as $triple) {
            $subjects[] = $this->__get($triple['subject']);
        }

        return $subjects;
    }

    /**
     * Fetches an array of triples from the triplestore according to the filters provided.
     *
     * @access public
     * @param  array  $filters Associative array of the filters to use. Empty array to return everything.
     * @param  array  $columns The columns to retrieve. Empty array to return all available columns.
     * @param  string $groupBy The column to group by. NULL to not group by anything.
     * @return array  The array of triples.
     */
    public function getTriples($filters=array(), $columns=array(), $groupBy=NULL) {
        // Build the where clause from the filters array.
        $where = array();
        foreach ($filters as $filterType => $filter) {
            $where[] = "$filterType = '$filter'";
        }
        $where = empty($where) ? NULL : ('WHERE ' . implode(' AND ', $where));

        // Build the select clause.
        if (empty($columns)) {
            $select = '*';
        } else {
            $select = implode(', ', $columns);
        }

        // Build the SQL statement.
        $stmt = "SELECT $select FROM {$this->_tableName} $where";

        // If a group by has been set, add that to the statement.
        if ($groupBy !== NULL) {
            $stmt .= " GROUP BY $groupBy";
        }
        // Retrieve all the triples out of the triplestore.
        $triples = $this->getArray($stmt);

        return $triples;
    }

    /**
     * Imports a CSV file into the triplestore.
     *
     * @access public
     * @param  string $file      The name and path to the CSV file.
     * @param  string $subject   The name of the column containing the subjects.
     * @param  string $delimiter The CSV delimiter.
     */
    public function importCSV($file, $subject, $delimiter=',') {
        if (($handle = fopen($file, 'r')) !== FALSE) {
            $predicates = null;
            while (($row = fgetcsv($handle, 0, $delimiter)) !== FALSE) {
                if (is_array($predicates)) {
                    foreach ($predicates as $count => $predicate) {
                        if ($row[$count] !== '') {
                            $this->insert($row[$subject], $predicate, $row[$count]);
                        }
                    }
                } else {
                    $subject = array_search($subject, $row);
                    $predicates = $row;
                    unset($predicates[$subject]);
                }
            }
            fclose($handle);
        }
    }

    /**
     * Imports a XML file into the triplestore.
     *
     * @access public
     * @param  string $file      The name and path to the XML file.
     * @param  string $subject   The name of the column containing the subjects.
     * @return string $id The id of the last triple inserted
     */
    public function importXML($file, $subject) {

        //Convert xml to a php array
        $triplesArray = $this->objXMLSerial->readXML($file);
        //Loop through array and insert triples
        if(is_array($triplesArray) && count($triplesArray)>0){
            foreach($triplesArray as $triple){
                if(is_array($triple) && count($triple)>0){
                    foreach($triple as $trip){
                        $id = $this->insert($trip['subject'], $trip['predicate'], $trip['object']);
                    }
                }
               
            }
        }
        return $id;
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
    public function insert($subject, $predicate, $object) {
        // Compile the associative array representing the new triple.
        $triple = array();
        $triple['userid'] = $this->objUser->userId();
        $triple['date'] = date('Y-m-d H:i:s');
        $triple['subject'] = $subject;
        $triple['predicate'] = $predicate;
        $triple['object'] = $object;

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
    public function update($id, $subject=FALSE, $predicate=FALSE, $object=FALSE) {
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

    public function getTriplesPaginated($subject, $page, $pageSize) {
        $order = $this->getParam('order', 'predicate');
        // Stop it at the first page
        if ($page < 1)
            $page = 1;
        // It starts at 0
        $page = $page - 1;
        $start = $page * $pagesize + 1;
        $limit = $pageSize;
        $filter = "WHERE subject = '$subject'";
        $records = $this->getRecordCount('$filter');
        // Make sure we don't go beyond total records
        if ($page > $records) {
            $page = $records;
        }
        // Retrieve the triples associated with the subject.
        $numOfPages = ceil($records / $limit);
        $triples = $this->getAll("WHERE subject = '$subject' ORDER BY $order LIMIT $start, $limit");
        return $triples;
        //example $contexts = $this->objDBContext->getAll("ORDER BY updated DESC limit ".$start.", ".$limit);
    }

}

?>