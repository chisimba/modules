<?php
/**
 * Stats tutorials on Chisimba
 * 
 * Statistics module to load authorware tests and tutorials within Chisimba framework
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
 * @package   stats
 * @author    Nic Appleby <nappleby@uwc.ac.za>
 * @copyright 2008 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za
 */

if (!
/**
 * Description for $GLOBALS
 * @global entry point $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
                                                                                                                                             
/**
 * stats class
 * 
 * stats module controller for Chisimba
 * 
 * @category  Chisimba
 * @package   stats
 * @author    Nic Appleby <nappleby@uwc.ac.za>
 * @copyright 2008 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za
 */

class stats extends controller {
    
    /**
     * User object 
     * @var object holds user details
     * @access public
     */
    public $objUser;
    
    /**
     * Language object
     * @var object used to fetch language items
     * @access public
     */
    public $objLanguage;
    
    /**
     * Logger object
     * @var object used to log module usage
     * @access public
     */
    public $objLogger;
    
    /**
     * User Id
     * @var string The id of the current user
     * @access public
     */
    public $userId;
    
    /**
     * Questionnaire model object
     * @var object used to model the tbl_questionnaire table
     * @access public
     */
    public $objQuestionnaire;

    /**
    * Init method
    * 
    * Standard Chisimba Init() method
    * 
    * @return void  
    * @access public
    */
    public function init() {
        $this->objUser = $this->getObject('user', 'security');
        $this->objLanguage = $this->getObject('language','language');
        $this->objQuestionnaire = $this->getObject('dbquestionnaire','stats');
        
        //Log this module call
        $this->objLog = $this->newObject('logactivity', 'logger');
        $this->objLog->log();
        
        $this->userId = $this->objUser->userId();
        $this->setLayoutTemplate('stats_layout_tpl.php');
    }
    
    /**
    * Main dispatch function controlls execution based on
    * the action parameter from the get or post variable
    * 
    * @param string $action the action to take
    * @return string the name of the template to display
    * @access public
    */
    public function dispatch($action) {
        switch ($action) {
        case "isemp":
            //$user = $this->getParam('user');
            //$pword = $this->getParam('password');
            // check user exists and has completed the questionnaire
            echo $this->objQuestionnaire->checkStudent($this->userId);
            break;
        
        case "tutorials":
            return "tutorials_tpl.php";
        
        case "home":
        default:
            return "default_tpl.php";
        }
    }


    /** 
    * Method to determine if the user has to be logged in or not
    * in order to access the module
    *
    * @return void
    * @access public
    */
    public function requiresLogin() {
        $action = $this->getParam('action');
        switch ($action) {
            
            case "isemp":
                return FALSE;
            
            default:
                return TRUE;
        }
    }
    
}
?>