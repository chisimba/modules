<?php

/**
 * Triplestore predicate data access class
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
 * Triplestore predicate data access class
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

class triplepredicate extends object implements Iterator
{
    /**
     * The objects associated with this predicate.
     *
     * @var array
     */
    protected $objects;

    /**
     * Instance of the dbtriplestore class of the triplestore module.
     *
     * @access protected
     * @var    object
     */
    protected $objTriplestore;

    /**
     * Initialise the instance of the triplepredicate class.
     *
     * @access public
     */
    public function init()
    {
        $this->objects = array();
        $this->objTriplestore = $this->getObject('dbtriplestore', 'triplestore');
    }

    /**
     * Converts this instance to a string by impoding the object array.
     *
     * @access public
     * @return string The imploded object array.
     */
    public function __toString()
    {
        return implode(' ', $this->objects);
    }

    /**
     * Adds an object to this predicate.
     *
     * @access public
     * @param  string $object The new object to add.
     */
    public function addObject($object)
    {
        $this->objects[] = $object;
    }

    /**
     * Part of the Iterator interface.
     *
     * @access public
     */
    public function current()
    {
        return current($this->objects);
    }

    /**
     * Gets an object at a particular index.
     *
     * @access public
     * @param  integer $index The index of the object to return.
     * @return string  The object at the index specified.
     */
    public function getObject($index)
    {
        return $this->objects[$index];
    }

    /**
     * Part of the Iterator interface.
     *
     * @access public
     */
    public function key()
    {
        return key($this->objects);
    }

    /**
     * Part of the Iterator interface.
     *
     * @access public
     */
    public function next()
    {
        return next($this->objects);
    }

    /**
     * Populates this predicate with the objects retrieved from the triplestore.
     *
     * @access public
     * @param  array $objects The objects retrieved from the triplestore.
     */
    public function populate($objects)
    {
        $this->objects = $objects;
    }

    /**
     * Part of the Iterator interface.
     *
     * @access public
     */
    public function rewind()
    {
        reset($this->objects);
    }

    /**
     * Sets the objects on this predicate.
     *
     * @access public
     * @param  array $objects The object array.
     */
    public function setObjects($objects)
    {
        $this->objects = $objects;
        $this->objTriplestore->setObjects($this->subject, $this->predicate, $objects);
    }

    /**
     * Returns an array of objects on this predicate.
     *
     * @access public
     * @return array The array of objects.
     */
    public function toArray()
    {
        return $this->objects;
    }

    /**
     * Part of the Iterator interface.
     *
     * @access public
     */
    public function valid()
    {
        return $this->current() !== false;
    }
}
