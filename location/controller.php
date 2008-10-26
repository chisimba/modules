<?php

/**
 * Location controller class
 * 
 * Class to control the Location module
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
 * @package   location
 * @author    Charl van Niekerk <charlvn@charlvn.za.net>
 * @copyright 2008 Charl van Niekerk
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za
 * @see       http://fireeagle.yahoo.net
 */

class location extends controller
{
    protected $objLocationOps;

    /**
     * Standard constructor to load the necessary resources
     * and populate the new object's instance variables
     * @access public
     */
    public function init()
    {
        // Load the location library
        $this->objLocationOps = $this->getObject('locationops', 'location');
    }

    /**
     * Standard dispatch method to handle the various possible actions
     * @access public
     */
    public function dispatch()
    {
        $action = $this->getParam('action');
        $method = 'action' . ucfirst($action);

        if (!method_exists($this, $method)) {
            $method = 'actionDefault';
        }

        return $this->$method();
    }

    private function actionCallback() {
        $this->objLocationOps->handleFireEagleCallback();
        $this->nextAction(null);
    }

    private function actionSynchronise() {
        $this->objLocationOps->synchroniseFireEagle();
    }

    private function actionMap()
    {
        $this->objLocationOps->setupMap();

        return 'map_tpl.php';
    }

    private function actionDefault()
    {
        if ($this->objLocationOps->isFireEagleAuthenticated()) {
            $this->objLocationOps->update();
            $location = $this->objLocationOps->getFireEagleUser();
            header('Content-Type: text/plain');
            print_r($location);
        } else {
            $this->objLocationOps->initFireEagleHandshake();
        }
    }

    /**
     * Overide the login object in the parent class
     *
     * @param  string $action The name of the action
     * @return bool
     * @access public
     */
    public function requiresLogin($action)
    {
        $publicActions = array('synchronise');

        return !in_array($action, $publicActions);
    }
}

?>
