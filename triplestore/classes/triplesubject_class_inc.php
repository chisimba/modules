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
 * @version   $Id: dbtriplestore_class_inc.php 16584 2010-01-29 21:29:56Z charlvn $
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
 * @version   $Id: dbtriplestore_class_inc.php 16584 2010-01-29 21:29:56Z charlvn $
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

    public function __get($name)
    {
        if (!array_key_exists($name, $this->predicates)) {
            $objects = array();
            foreach ($this->triples as $triple) {
                if ($triple['predicate'] === $name) {
                    $objects[] = $triple['object'];
                }
            }
            $this->predicates[$name] = $this->newObject('triplepredicate', 'triplestore');
            $this->predicates[$name]->setObjects($objects);
        }

        return $this->predicates[$name];
    }

    public function __isset($name)
    {
        $set = false;

        foreach ($this->triples as $triple) {
            if ($triple['predicate'] === $name) {
                $set = true;
                break;
            }
        }

        return $set;
    }

    /**
     * Loads a new subject and its associated triples.
     *
     * @access public
     * @param  string $subject The id of the subject to load.
     */
    public function setSubject($subject)
    {
        $this->data    = $this->objTriplestore->getSubject($subject);
        $this->objects = array();
        $this->subject = $subject;
    }
}

?>
