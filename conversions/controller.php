<?php

/**
 * Controller class
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
 * @package   conversions
 * @author    Administrative User <admin@localhost.local.za>
 * @copyright 2007 Administrative User
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   CVS: $Id$
 * @link      http://avoir.uwc.ac.za
 * @see       References to other sections (if any)...
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
 * Main class with method for integrating all files 
 * @category  Chisimba
 * @package   conversions
 * @author    Administrative User <admin@localhost.local.za>
 * @copyright 2007 Administrative User
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   CVS: $Id$
 * @link      http://avoir.uwc.ac.za
 * @see       References to other sections (if any)...
 */
class conversions extends controller
{

    /**
     * Description for public
     * @var    $objLanguage
     * @access public 
     */
    public $objLanguage;

    /**
     * Description for public
     * @var    $objConfig
     * @access public 
     */
    public $objConfig;

    /**
     * Description for public
     * @var    $objDist
     * @access public 
     */
    public $objDist;

    /**
     * Description for public
     * @var    $objTemp
     * @access public 
     */
    public $objTemp;

    /**
     * Description for public
     * @var    $objVol
     * @access public 
     */
    public $objVol;

    /**
     * Description for public
     * @var    $objWeight
     * @access public 
     */
    public $objWeight;

    /**
     * Description for public
     * @var    $objUser
     * @access public 
     */
    public $objUser;
    
    /**
     * Constructor method to instantiate objects and get variables
     * 
     * @return void  
     * @access public
     */
    public function init() 
    {
        try {
            $this->objUser = $this->getObject('user', 'security');
            $this->objDist = $this->getObject('dist');
            $this->objTemp = $this->getObject('temp');
            $this->objVol = $this->getObject('vol');
            $this->objWeight = $this->getObject('weight');
            $this->objLanguage = $this->getObject('language', 'language');
            $this->objConfig = $this->getObject('altconfig', 'config');
        }
        catch(customException $e) {
            echo customException::cleanUp();
            die();
        }
    }
    /**
     * Method to process actions to be taken
     * @param string $action String indicating action to be taken
     */
    public function dispatch($action = Null) 
    {
        switch ($action) {
            default:
            case 'default':
                $goTo = $this->getParam('goTo');
                $this->setVarByRef('goTo', $goTo);
                return 'convertit_tpl.php';
                break;

            case 'dist':
                $value = $this->getParam('value');
                $from = $this->getParam('from');
                $to = $this->getParam('to');
                $this->setVarByRef('action', $action);
                $this->setVarByRef('value', $value);
                $this->setVarByRef('from', $from);
                $this->setVarByRef('to', $to);
                $goTo = 'dist';
                $this->setVarByRef('goTo', $goTo);
                return 'convertit_tpl.php';
                break;

            case 'temp':
                $value = $this->getParam('value');
                $from = $this->getParam('from');
                $to = $this->getParam('to');
                $this->setVarByRef('action', $action);
                $this->setVarByRef('value', $value);
                $this->setVarByRef('from', $from);
                $this->setVarByRef('to', $to);
                $goTo = 'temp';
                $this->setVarByRef('goTo', $goTo);
                return 'convertit_tpl.php';
                break;

            case 'vol':
                $value = $this->getParam('value');
                $from = $this->getParam('from');
                $to = $this->getParam('to');
                $this->setVarByRef('action', $action);
                $this->setVarByRef('value', $value);
                $this->setVarByRef('from', $from);
                $this->setVarByRef('to', $to);
                $goTo = 'vol';
                $this->setVarByRef('goTo', $goTo);
                return 'convertit_tpl.php';
                break;

            case 'weight':
                $value = $this->getParam('value');
                $from = $this->getParam('from');
                $to = $this->getParam('to');
                $this->setVarByRef('action', $action);
                $this->setVarByRef('value', $value);
                $this->setVarByRef('from', $from);
                $this->setVarByRef('to', $to);
                $goTo = 'weight';
                $this->setVarByRef('goTo', $goTo);
                return 'convertit_tpl.php';
                break;
        }
    }
}
?>
