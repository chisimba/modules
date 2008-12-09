<?php
/**
 * AHIS
 *
 * Controller for AHIS
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
 * @package   ahis
 * @author    Nic Appleby <nappleby@uwc.ac.za>
 * @copyright 2009 AVOIR
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
 *  ahis class
 * 
 *  controller class for Chisimba AHIS
 * 
 * @category  Chisimba
 * @package   ahis
 * @author    Nic Appleby <nappleby@uwc.ac.za>
 * @copyright 2009 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za
 */
class ahis extends controller {
     
    /**
     * Admin actions array
     * @var array used to store a list of admin only actions
     * @access private
     */
    private $adminActions;
     
    /**
     * Language object
     * @var object used to fetch language items
     * @access public
     */
    public $objLanguage;
    
    /**
     * User object
     * @var object used to fetch user info
     * @access public
     */
    public $objUser;
    
    /**
     * Logger object
     * @var object used to log module usage
     * @access public
     */
    public $objLogger;
     
     /**
     * Standard Chisimba init method
     * 
     * @return void  
     * @access public
     */
	public function init() {
        try {
            $this->objLanguage = $this->getObject('language', 'language');
            $this->objUser = $this->getObject('user', 'security');
            
            //Log this module call
            $this->objLog = $this->newObject('logactivity', 'logger');
            $this->objLog->log();
            $this->setVar('pageSuppressToolbar', TRUE);
            $this->setLayoutTemplate('ahis_layout_tpl.php');
            
            $this->adminActions = array('admin', 'employee_admin', 'geography_level3_admin',
                                        'age_group_admin', 'title_admin', 'sex_admin', 'status_admin',
                                        'geography_level2_admin', 'prodution_admin', 'territory_admin',
                                        'report_admin', 'quality_admin', 'diagnosis_admin',
                                        'control_admin', 'outbreak_admin');
        }
        catch(customException $e) {
        	customException::cleanUp();
        	exit;
        }
    }
    
    /**
     * Standard Chisimba dispatch method for parsing the querystring
     * 
     * @param  string $action The REQUEST string for action
     * @return void   
     * @access public 
     */
    public function dispatch($action = NULL) {
        if (!$this->objUser->isLoggedIn()) {
            return 'login_tpl.php';
        }
        if (in_array($action, $this->adminActions) && !$this->objUser->isAdmin()) {
            return 'notadmin_tpl.php';
        }
        
        switch ($action) {
        	case 'select_officer':
                $this->setVar('officer', $this->getParam('officer'));
                $this->setVar('district', $this->getParam('district'));
                $this->setVar('calendardate', $this->getParam('calendardate',date('Y-m-d')));
                $this->setVar('reportType', $this->getParam('reportType'));
                return 'select_officer_tpl.php';
            
            case 'admin':
               return 'admin_tpl.php';
            
            case 'view_reports':
                
            
            default:
                return $this->nextAction('select_officer');
            	
        }
    }
    
    /**
     * Method to determine whether the user needs to be logged in
     * 
     * @return boolean TRUE|FALSE 
     * @access public 
     */
     public function requiresLogin() {
            return FALSE;
     }
     
}
