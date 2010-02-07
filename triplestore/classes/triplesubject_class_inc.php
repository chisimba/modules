<?php

/**
 * Triplestore subject data access class
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
 * Triplestore subject data access class
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

class triplesubject extends object
{
    /**
     * Instance of the dbtriplestore class of the triplestore module.
     *
     * @access protected
     * @var    object
     */
    protected $objTriplestore;

    /**
     * Associative array of instances of the triplepredicate class of the triplestore module.
     *
     * @access protected
     * @var    array
     */
    protected $predicates;

    /**
     * The subject id.
     *
     * @access protected
     * @var    string
     */
    protected $subject;

    /**
     * Array of the triples associated with this subject.
     *
     * @access protected
     * @var    array
     */
    protected $triples;

    /**
     * Initialise the instance of the triplesubject class.
     *
     * @access public
     */
    public function init()
    {
        $this->objTriplestore = $this->getObject('dbtriplestore', 'triplestore');
    }

    /**
     * Gets an object representing a predicate associated with this subject.
     *
     * @access public
     * @param  string $predicate The predicate to retrieve.
     * @return object An instance of the triplepredicate class.
     */
    public function __get($predicate)
    {
        if (!array_key_exists($predicate, $this->predicates)) {
            $objects = array();
            foreach ($this->triples as $triple) {
                if ($triple['predicate'] === $predicate) {
                    $objects[$triple['id']] = $triple['object'];
                }
            }
            $this->predicates[$predicate] = $this->newObject('triplepredicate', 'triplestore');
            $this->predicates[$predicate]->populate($this->subject, $predicate, $objects);
        }

        return $this->predicates[$predicate];
    }

    /**
     * Checks if at least one triple with the given predicate exists on this subject.
     *
     * @access public
     * @param  string  $predicate The predicate to check.
     * @return boolean TRUE if the predicate is found on this subject; FALSE otherwise.
     */
    public function __isset($predicate)
    {
        // First check the array of predicate objects (for efficiency).
        if (array_key_exists($predicate, $this->predicates)) {
            $found = TRUE;
        } else {
            // Assume the predicate could not be found.
            $found = FALSE;

            // Search the triple cache for the given predicate.
            foreach ($this->triples as $triple) {
                if ($triple['predicate'] == $predicate) {
                    $found = TRUE;
                    break;
                }
            }
        }

        // Return the result.
        return $found;
    }

    public function __set($predicate, $objects)
    {
        $this->__get($predicate)->set($objects);
    }

    /**
     * Populates the properties of the object.
     *
     * @access public
     * @param  string $subject The name of the subject.
     * @param  array  $triples The triples associated with the subject.
     */
    public function populate($subject, $triples)
    {
        $this->predicates = array();
        $this->subject    = $subject;
        $this->triples    = $triples;
    }
}

?>
