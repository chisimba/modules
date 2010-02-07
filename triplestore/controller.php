<?php

/**
 * Triplestore controller class
 * 
 * Class to control the Triplestore module
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
 * @version   $Id: controller.php 16050 2009-12-26 01:34:10Z charlvn $
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
 * Triplestore controller class
 *
 * Class to control the Triplestore module
 *
 * @category  Chisimba
 * @package   triplestore
 * @author    Charl van Niekerk <charlvn@charlvn.com>
 * @copyright 2010 Charl van Niekerk
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   Release: @package_version@
 * @link      http://avoir.uwc.ac.za/
 */
class triplestore extends controller
{
    /**
     * Instance of the dbtriplestore class of the triplestore module.
     *
     * @access protected
     * @var    object
     */
    protected $objTriplestore;

    /**
     * Standard constructor to load the necessary resources
     * and populate the new object's instance variables.
     *
     * @access public
     */
    public function init()
    {
        $this->objTriplestore = $this->getObject('dbtriplestore', 'triplestore');
    }

    /**
     * Standard dispatch method to handle the various possible actions.
     *
     * @access public
     */
    public function dispatch()
    {
        $filters     = array();
        $filterTypes = array('subject', 'predicate', 'object');

        foreach ($filterTypes as $filterType) {
            $filter = $this->getParam($filterType);
            if ($filter) {
                $filters[$filterType] = $filter;
            }
        }

        $nestedTriples = $this->objTriplestore->getNestedTriples($filters);

        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($nestedTriples);
    }

    /**
     * Overide the login object in the parent class.
     *
     * @access public
     * @param  string $action The name of the action
     * @return bool
     */
    public function requiresLogin($action)
    {
        return FALSE;
    }
}

?>
