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
            $this->objGeo3 = $this->getObject('geolevel3');
            
            $this->adminActions = array('admin', 'employee_admin', 'geography_level3_admin',
                                        'age_group_admin', 'title_admin', 'sex_admin', 'status_admin',
                                        'geography_level2_admin', 'prodution_admin', 'territory_admin',
                                        'report_admin', 'quality_admin', 'diagnosis_admin',
                                        'control_admin', 'outbreak_admin', 'geography_level3_delete',
                                        'geography_level3_add', 'geography_level3_insert');
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
            $this->setVar('message', $this->objLanguage->languageText('mod_ahis_notadmin','ahis'));
            $this->setVar('location', $this->uri(array('action'=>'select_officer')));
            return 'redirect_tpl.php';
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
            
            case 'geography_level3_admin':
                $searchStr = $this->getParam('searchStr');
                $data = $this->objGeo3->getAll("WHERE name LIKE '%$searchStr%' ORDER BY name");
                $this->setVar('addLinkUri', $this->uri(array('action'=>'geography_level3_add')));
                $this->setVar('addLinkText', $this->objLanguage->languageText('mod_ahis_geo3add','ahis'));
                $this->setVar('headingText', $this->objLanguage->languageText('mod_ahis_geo3adminheading','ahis'));
                $this->setVar('action', $action);
                $this->setVar('columnName', $this->objLanguage->languageText('word_name'));
                $this->setVar('deleteAction', 'geography_level3_delete');
                $this->setVar('fieldName', 'name');
                $this->setVar('data', $data);
                $this->setVar('searchStr', $searchStr);
                $this->setVar('success', $this->getParam('success'));
                return 'admin_overview_tpl.php';
            
            case 'geography_level2_admin':
                if ($this->objGeo3->getRecordCount() < 1) {
                    $this->setVar('message', $this->objLanguage->languageText('mod_ahis_nogeo3','ahis'));
                    $this->setVar('location', $this->uri(array('action'=>'geography_level3_admin')));
                    return 'redirect_tpl.php';
                }
                $searchStr = $this->getParam('searchStr');
                //$data = $this->objGeo2->getAll("WHERE name LIKE '%$searchStr%' ORDER BY name");
                $data = array(array('id'=>1,'name'=>'the first one'),array('id'=>2,'name'=>'second one'));
                $this->setVar('addLinkUri', $this->uri(array('action'=>'geography_level2_add')));
                $this->setVar('addLinkText', $this->objLanguage->languageText('mod_ahis_geo2add','ahis'));
                $this->setVar('headingText', $this->objLanguage->languageText('mod_ahis_geo2adminheading','ahis'));
                $this->setVar('action', $action);
                $this->setVar('columnName', $this->objLanguage->languageText('word_name'));
                $this->setVar('deleteAction', 'geography_level2_delete');
                $this->setVar('fieldName', 'name');
                $this->setVar('searchStr', $searchStr);
                $this->setVar('data', $data);
                $this->setVar('success', $this->getParam('success'));
                return 'admin_overview_tpl.php';
            
            case 'geography_level3_add':
                return 'geo3_add_tpl.php';
            
            case 'geography_level3_insert':
                $name = $this->getParam('name');
                $this->objGeo3->insert(array('name'=>$name));
                return $this->nextAction('geography_level3_admin', array('success'=>'1'));
            
            case 'geography_level3_delete':
                $id = $this->getParam('id');
                $this->objGeo3->delete('id', $id);
                return $this->nextAction('geography_level3_admin', array('success'=>'2'));
            
            case 'geography_level3_add':
                return 'geo2_add_tpl.php';
            
            case 'geography_level2_insert':
                $name = $this->getParam('name');
                $this->objGeo2->insert(array('name'=>$name));
                return $this->nextAction('geography_level2_admin', array('success'=>'1'));
            
            case 'geography_level3_delete':
                $id = $this->getParam('id');
                $this->objGeo2->delete('id', $id);
                return $this->nextAction('geography_level2_admin', array('success'=>'2'));
            
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
