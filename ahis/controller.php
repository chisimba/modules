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
            $this->objGeo2 = $this->getObject('geolevel2');
            $this->objTerritory = $this->getObject('territory');
            $this->objAhisUser = $this->getObject('ahisuser');
            $this->objProduction = $this->getObject('production');
            $this->objTitle = $this->getObject('title');
            $this->objStatus = $this->getObject('status');
            $this->objDepartment = $this->getObject('department');
            $this->objRole = $this->getObject('role');
            $this->objSex = $this->getObject('sex');
            $this->objOutbreak = $this->getObject('outbreak');
            $this->objDiagnosis = $this->getObject('diagnosis');
            $this->objControl = $this->getObject('control');
            $this->objQuality = $this->getObject('quality');
            $this->objAge = $this->getObject('age');
            $this->objRole = $this->getObject('role');
            $this->objDepartment = $this->getObject('department');
            $this->objPassive = $this->getObject('passive');
            $this->objReport = $this->getObject('reporttype');
                       
            $this->adminActions = array('admin', 'employee_admin', 'geography_level3_admin',
                                        'age_group_admin', 'title_admin', 'sex_admin', 'status_admin',
                                        'geography_level2_admin', 'prodution_admin', 'territory_admin',
                                        'report_admin', 'quality_admin', 'diagnosis_admin',
                                        'control_admin', 'outbreak_admin', 'geography_level3_delete',
                                        'geography_level3_add', 'geography_level3_insert', 'create_territory',
                                        'territory_insert', 'employee_admin', 'employee_insert', 'create_employee',
                                        'production_admin', 'production_add', 'production_insert', 'production_delete',
                                        'title_admin', 'title_add', 'title_insert', 'title_delete',
                                        'status_admin', 'status_add', 'sex_insert', 'sex_delete',
                                        'sex_admin', 'sex_add', 'sex_insert', 'sex_delete',
                                        'outbreak_admin', 'outbreak_add', 'outbreak_insert', 'outbreak_delete',
                                        'control_admin', 'control_add', 'control_insert', 'control_delete',
                                        'quality_admin', 'quality_add', 'quality_insert', 'quality_delete',
                                        'age_add', 'age_admin', 'age_insert', 'age_delete',
                                        'role_add', 'role_admin', 'role_insert', 'role_delete',
                                        'department_add', 'department_admin', 'department_insert', 'department_delete',
                                        'report_add', 'report_admin', 'report_insert', 'report_delete'
                                        );
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
                $this->setVar('userList', $this->objAhisUser->getList());
                $this->setVar('officerId', $this->getSession('ps_officerId', $this->objUser->userId()));
                $this->setVar('districtId', $this->getSession('ps_districtId', $this->objAhisUser->getTerritory()));
                $this->setVar('calendardate', $this->getSession('ps_calendardate', date('Y-m-d')));
                $this->setVar('reportType', $this->getSession('ps_reportType'));
                return 'select_officer_tpl.php';
            
            case 'report_filter':
                $this->setSession('ps_officerId', $this->getParam('officer'));
                $this->setSession('ps_districtId' , $this->getParam('district'));
                $this->setSession('ps_calendardate', $this->getParam('calendardate'));
                $reportType = $this->getParam('reportType');
                $this->setSession('ps_reportType', $reportType);
                switch ($reportType) {
                    case "init_01":
                        return $this->nextAction('passive_surveillance');
                    default:
                        return $this->nextAction('');
                }
            
            case 'passive_surveillance':
                $this->setVar('calendardate', $this->getSession('ps_calendardate',date('Y-m-d')));
                $this->setVar('arrayTerritory', $this->objTerritory->getAll("ORDER BY NAME"));
                $this->setVar('arrayOutbreakStatus', $this->objOutbreak->getAll("ORDER BY NAME"));
                $this->setVar('arrayQuality', $this->objQuality->getAll("ORDER BY NAME"));
                //$this->setVar('territoryId', $this->getSession('ps_territoryId'));
                $this->setVar('territoryId', $this->getSession('ps_districtId'));
                $this->setVar('oStatusId', $this->getSession('ps_oStatusId'));
                $this->setVar('qualityId', $this->getSession('ps_qualityId'));
                $this->setVar('datePrepared', $this->getSession('ps_datePrepared', date('Y-m-d')));
                $this->setVar('dateIBAR', $this->getSession('ps_dateIBAR', date('Y-m-d')));
                $this->setVar('dateReceived', $this->getSession('ps_dateReceived', date('Y-m-d')));
                $this->setVar('dateIsReported', $this->getSession('ps_dateisReported', date('Y-m-d')));
                $this->setVar('refNo', $this->getSession('ps_refNo', $this->objPassive->nextRefNo()));
                $this->setVar('remarks', $this->getSession('ps_remarks'));
                return "passive_surveillance_tpl.php";
            
            case 'passive_outbreak':
                //$this->setSession('ps_territoryId', $this->getParam('territoryId'));
                $this->setSession('ps_oStatusId', $this->getParam('oStatusId'));
                $this->setSession('ps_qualityId', $this->getParam('qualityId'));
                $this->setSession('ps_datePrepared', $this->getParam('datePrepared'));
                $this->setSession('ps_dateIBAR', $this->getParam('dateIBAR'));
                $this->setSession('ps_dateReceived', $this->getParam('dateReceived'));
                $this->setSession('ps_dateIsReported', $this->getParam('dateIsReported'));
                $this->setSession('ps_refNo', $this->getParam('refNo'));
                $this->setSession('ps_remarks', $this->getParam('remarks'));
                
                $this->setVar('arrayTerritory', $this->objTerritory->getAll("ORDER BY NAME"));
                $this->setVar('calendardate', $this->getSession('ps_calendardate'));
                $this->setVar('refNo', $this->getSession('ps_refNo'));
                $this->setVar('territoryId', $this->getSession('ps_districtId'));
                $this->setVar('dateVet', $this->getSession('ps_dateVet', date('Y-m-d')));
                $this->setVar('dateOccurence', $this->getSession('ps_dateOccurence', date('Y-m-d')));
                $this->setVar('dateDiagnosis', $this->getSession('ps_dateDiagnosis', date('Y-m-d')));
                $this->setVar('dateInvestigation', $this->getSession('ps_dateinvestigation', date('Y-m-d')));
                $this->setVar('location', $this->getSession('ps_location'));
                $this->setVar('latitude', $this->getSession('ps_latitude'));
                $this->setVar('longitude', $this->getSession('ps_longitude'));
                $this->setVar('disease', $this->getSession('ps_disease'));
                $this->setVar('causitive', $this->getSession('ps_causitive'));
                
                return 'passive_outbreak_tpl.php';
                            
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
                $this->setVar('allowEdit', FALSE);
                return 'admin_overview_tpl.php';
            
            case 'geography_level2_admin':
                if ($this->objGeo3->getRecordCount() < 1) {
                    $this->setVar('message', $this->objLanguage->languageText('mod_ahis_nogeo3','ahis'));
                    $this->setVar('location', $this->uri(array('action'=>'geography_level3_admin')));
                    return 'redirect_tpl.php';
                }
                $searchStr = $this->getParam('searchStr');
                $data = $this->objGeo2->getAll("WHERE name LIKE '%$searchStr%' ORDER BY name");
                $this->setVar('addLinkUri', $this->uri(array('action'=>'geography_level2_add')));
                $this->setVar('addLinkText', $this->objLanguage->languageText('mod_ahis_geo2add','ahis'));
                $this->setVar('headingText', $this->objLanguage->languageText('mod_ahis_geo2adminheading','ahis'));
                $this->setVar('action', $action);
                $this->setVar('columnName', $this->objLanguage->languageText('word_name'));
                $this->setVar('deleteAction', 'geography_level2_delete');
                $this->setVar('fieldName', 'name');
                $this->setVar('searchStr', $searchStr);
                $this->setVar('data', $data);
                $this->setVar('allowEdit', TRUE);
                $this->setVar('editAction', 'geography_level2_add');
                $this->setVar('success', $this->getParam('success'));
                return 'admin_overview_tpl.php';
            
            case 'geography_level3_add':
                return 'geo3_add_tpl.php';
            
            case 'geography_level3_insert':
                $name = $this->getParam('name');
                if ($this->objGeo3->valueExists('name',$name)) {
                    return $this->nextAction('geography_level3_admin', array('success'=>'4'));
                }
                $this->objGeo3->insert(array('name'=>$name));
                return $this->nextAction('geography_level3_admin', array('success'=>'1'));
            
            case 'geography_level3_delete':
                $id = $this->getParam('id');
                $this->objGeo3->delete('id', $id);
                return $this->nextAction('geography_level3_admin', array('success'=>'2'));
            
            case 'geography_level2_add':
                $geo3 = $this->objGeo3->getAll("ORDER BY name");
                $this->setVar('geo3',$geo3);
                $this->setVar('id',$this->getParam('id'));
                return 'geo2_add_tpl.php';
            
            case 'geography_level2_insert':
                $id = $this->getParam('id');
                $name = $this->getParam('name');
                $geo3Id = $this->getParam('geo3id');
                if ($this->objGeo2->valueExists('name',$name)) {
                    return $this->nextAction('geography_level2_admin', array('success'=>'4'));
                }
                if ($id) {
                    $this->objGeo2->update('id', $id, array('name'=>$name, 'geo3id' => $geo3Id));
                    $success = '3';
                } else {
                    $this->objGeo2->insert(array('name'=>$name, 'geo3id' => $geo3Id));
                    $success = '1';
                }
                return $this->nextAction('geography_level2_admin', array('success'=>$success));
            
            case 'geography_level2_delete':
                $id = $this->getParam('id');
                $this->objGeo2->delete('id', $id);
                return $this->nextAction('geography_level2_admin', array('success'=>'2'));
            
            case 'create_territory':
                if ($this->objGeo2->getRecordCount() < 1) {
                    $this->setVar('message', $this->objLanguage->languageText('mod_ahis_nogeo2','ahis'));
                    $this->setVar('location', $this->uri(array('action'=>'geography_level2_admin')));
                    return 'redirect_tpl.php';
                }
                $geo2 = $this->objGeo2->getAll("ORDER BY name");
                $this->setVar('geo2',$geo2);
                return "add_territory_tpl.php";
            
            case 'territory_insert':
                $rec['name'] = $this->getParam('territory');
                $rec['northlatitude'] = $this->getParam('latitude_north');
                $rec['southlatitude'] = $this->getParam('latitude_south');
                $rec['eastlongitude'] = $this->getParam('longitude_east');
                $rec['westlongitude'] = $this->getParam('longitude_west');
                $rec['geo2id'] = $this->getParam('geo2');
                $rec['area'] = $this->getParam('area');
                $rec['unitofmeasure'] = $this->getParam('unit_of_measure');
                $this->objTerritory->insert($rec);
                return $this->nextAction('admin');
            
            case 'employee_admin':
                $searchStr = $this->getParam('searchStr');
                $data = $this->objUser->getAll("WHERE firstname LIKE '%$searchStr%' OR surname LIKE '%$searchStr%' OR username LIKE '%$searchStr%' ORDER BY surname");
                $this->setVar('searchStr', $searchStr);
                $this->setVar('data', $data);
                $this->setVar('success', $this->getParam('success'));
                return 'admin_employee_tpl.php';
            
            case 'create_employee':
                if ($this->objTitle->getRecordCount() < 1) {
                    $this->setVar('message', $this->objLanguage->languageText('mod_ahis_notitle','ahis'));
                    $this->setVar('location', $this->uri(array('action'=>'title_admin')));
                    return 'redirect_tpl.php';
                }
                if ($this->objStatus->getRecordCount() < 1) {
                    $this->setVar('message', $this->objLanguage->languageText('mod_ahis_nostatus','ahis'));
                    $this->setVar('location', $this->uri(array('action'=>'status_admin')));
                    return 'redirect_tpl.php';
                }
                if ($this->objRole->getRecordCount() < 1) {
                    $this->setVar('message', $this->objLanguage->languageText('mod_ahis_norole','ahis'));
                    $this->setVar('location', $this->uri(array('action'=>'role_admin')));
                    return 'redirect_tpl.php';
                }
                if ($this->objDepartment->getRecordCount() < 1) {
                    $this->setVar('message', $this->objLanguage->languageText('mod_ahis_nodepartment','ahis'));
                    $this->setVar('location', $this->uri(array('action'=>'department_admin')));
                    return 'redirect_tpl.php';
                }
                if ($this->objTerritory->getRecordCount() < 1) {
                    $this->setVar('message', $this->objLanguage->languageText('mod_ahis_noterritory','ahis'));
                    $this->setVar('location', $this->uri(array('action'=>'create_territory')));
                    return 'redirect_tpl.php';
                }
                
                $this->setVar('id', $this->getParam('id'));
                $this->setVar('error', $this->getParam('error'));
                $this->setVar('titles', $this->objTitle->getAll());
                $this->setVar('status', $this->objStatus->getAll());
                $this->setVar('locations', $this->objTerritory->getAll());
                $this->setVar('departments', $this->objDepartment->getAll());
                $this->setVar('roles', $this->objRole->getAll());
                return "add_employee_tpl.php";
            
            case 'employee_insert':
                $id = $this->getParam('id');
                $record['surname'] = $this->getParam('surname');
                $record['firstname'] = $this->getParam('name');
                $test = $this->objUser->getAll("WHERE firstname = '{$record['firstname']}' AND surname = '{$record['surname']}'");
                $record['username'] = $this->getParam('username');
                $password = $this->getParam('password');
                if ($password) {
                    $record['pass'] = sha1($password);
                }
                $ahisRecord['titleid'] = $this->getParam('titleid');
                $ahisRecord['statusid'] = $this->getParam('statusid');
                if ($ahisRecord['statusid'] == "init_02") {
                    $record['isactive'] = 0;
                }
                $ahisRecord['ahisuser'] = $this->getParam('ahisuser');
                if ($ahisRecord['ahisuser']) {
                    $ahisRecord['ahisuser'] = 1;
                    if ((!$record['username'] || !$password) && !$id) {
                        return $this->nextAction('create_employee', array('error'=>1, 'id'=>$id));
                    }
                } else {
                    $ahisRecord['ahisuser'] = 0;
                    $record['isactive'] = 0;
                }
                $ahisRecord['locationid'] = $this->getParam('locationid');
                $ahisRecord['departmentid'] = $this->getParam('departmentid');
                $ahisRecord['roleid'] = $this->getParam('roleid');
                $ahisRecord['dateofbirth'] = $this->getParam('datebirth');
                $ahisRecord['datehired'] = $this->getParam('hireddate');
                $ahisRecord['retired'] = $this->getParam('retired');
                if ($ahisRecord['retired']) {
                    $ahisRecord['retired'] = 1;
                    $record['isactive'] = 0;
                    $ahisRecord['dateretired'] = $this->getParam('retireddate');
                } else {
                    $ahisRecord['retired'] = 0;
                }

                if ($id) {
                    $this->objUser->update('id', $id, $record);
                    $code = 3;
                } else {
                    if (!empty($test)) {
                        return $this->nextAction('employee_admin', array('success'=>'4'));
                    }
                    $id = $this->objUser->insert($record);
                    $code = 1;
                }
                if ($this->objAhisUser->valueExists('id', $id)) {
                    $this->objAhisUser->update('id', $id, $ahisRecord);
                } else {
                    $ahisRecord['id'] = $id;
                    $this->objAhisUser->insert($ahisRecord);
                }
                
                return $this->nextAction('employee_admin', array('success'=>$code));
            
            case 'production_admin':
                $searchStr = $this->getParam('searchStr');
                $data = $this->objProduction->getAll("WHERE name LIKE '%$searchStr%' ORDER BY name");
                $this->setVar('addLinkUri', $this->uri(array('action'=>'production_add')));
                $this->setVar('addLinkText', $this->objLanguage->languageText('mod_ahis_productionadd','ahis'));
                $this->setVar('headingText', $this->objLanguage->languageText('mod_ahis_productionadmin','ahis'));
                $this->setVar('action', $action);
                $this->setVar('columnName', $this->objLanguage->languageText('word_name'));
                $this->setVar('deleteAction', 'production_delete');
                $this->setVar('fieldName', 'name');
                $this->setVar('searchStr', $searchStr);
                $this->setVar('data', $data);
                $this->setVar('allowEdit', TRUE);
                $this->setVar('editAction', 'production_add');
                $this->setVar('success', $this->getParam('success'));
                return 'admin_overview_tpl.php';
            
            case 'production_add':
                $this->setVar('id', $this->getParam('id'));
                return 'production_add_tpl.php';
            
            case 'production_insert':
                $id = $this->getParam('id');
                $name = $this->getParam('name');
                if ($this->objProduction->valueExists('name', $name)) {
                    return $this->nextAction('production_admin', array('success'=>'4'));
                }
                if ($id) {
                    $this->objProduction->update('id', $id, array('name'=>$name));
                    $code = 3;
                } else {
                    $this->objProduction->insert(array('name'=>$name));
                    $code = 1;
                }
                return $this->nextAction('production_admin', array('success'=>$code));
            
            case 'production_delete':
                $id = $this->getParam('id');
                $this->objProduction->delete('id', $id);
                return $this->nextAction('production_admin', array('success'=>'2'));
            
            case 'title_admin':
                $searchStr = $this->getParam('searchStr');
                $data = $this->objTitle->getAll("WHERE name LIKE '%$searchStr%' ORDER BY name");
                $this->setVar('addLinkUri', $this->uri(array('action'=>'title_add')));
                $this->setVar('addLinkText', $this->objLanguage->languageText('mod_ahis_titleadd','ahis'));
                $this->setVar('headingText', $this->objLanguage->languageText('mod_ahis_titleadmin','ahis'));
                $this->setVar('action', $action);
                $this->setVar('columnName', $this->objLanguage->languageText('word_title'));
                $this->setVar('deleteAction', 'title_delete');
                $this->setVar('fieldName', 'name');
                $this->setVar('searchStr', $searchStr);
                $this->setVar('data', $data);
                $this->setVar('allowEdit', TRUE);
                $this->setVar('editAction', 'title_add');
                $this->setVar('success', $this->getParam('success'));
                return 'admin_overview_tpl.php';
            
            case 'title_add':
                $this->setVar('id', $this->getParam('id'));
                return 'title_add_tpl.php';
            
            case 'title_insert':
                $id = $this->getParam('id');
                $name = $this->getParam('name');
                if ($this->objTitle->valueExists('name', $name)) {
                    return $this->nextAction('title_admin', array('success'=>'4'));
                }
                if ($id) {
                    $this->objTitle->update('id', $id, array('name'=>$name));
                    $code = 3;
                } else {
                    $this->objTitle->insert(array('name'=>$name));
                    $code = 1;
                }
                return $this->nextAction('title_admin', array('success'=>$code));
            
            case 'title_delete':
                $id = $this->getParam('id');
                $this->objTitle->delete('id', $id);
                return $this->nextAction('title_admin', array('success'=>'2'));
            
            case 'status_admin':
                $searchStr = $this->getParam('searchStr');
                $data = $this->objStatus->getAll("WHERE name LIKE '%$searchStr%' ORDER BY name");
                $this->setVar('addLinkUri', $this->uri(array('action'=>'status_add')));
                $this->setVar('addLinkText', $this->objLanguage->languageText('mod_ahis_statusadd','ahis'));
                $this->setVar('headingText', $this->objLanguage->languageText('mod_ahis_statusadmin','ahis'));
                $this->setVar('action', $action);
                $this->setVar('columnName', $this->objLanguage->languageText('word_status'));
                $this->setVar('deleteAction', 'status_delete');
                $this->setVar('fieldName', 'name');
                $this->setVar('searchStr', $searchStr);
                $this->setVar('data', $data);
                $this->setVar('allowEdit', TRUE);
                $this->setVar('editAction', 'status_add');
                $this->setVar('success', $this->getParam('success'));
                return 'admin_overview_tpl.php';
            
            case 'status_add':
                $this->setVar('id', $this->getParam('id'));
                return 'status_add_tpl.php';
            
            case 'status_insert':
                $id = $this->getParam('id');
                $name = $this->getParam('name');
                if ($this->objStatus->valueExists('name', $name)) {
                    return $this->nextAction('status_admin', array('success'=>'4'));
                }
                if ($id) {
                    $this->objStatus->update('id', $id, array('name'=>$name));
                    $code = 3;
                } else {
                    $this->objStatus->insert(array('name'=>$name));
                    $code = 1;
                }
                return $this->nextAction('status_admin', array('success'=>$code));
            
            case 'status_delete':
                $id = $this->getParam('id');
                $this->objStatus->delete('id', $id);
                return $this->nextAction('status_admin', array('success'=>'2'));
            
            case 'sex_admin':
                $searchStr = $this->getParam('searchStr');
                $data = $this->objSex->getAll("WHERE name LIKE '%$searchStr%' ORDER BY name");
                $this->setVar('addLinkUri', $this->uri(array('action'=>'sex_add')));
                $this->setVar('addLinkText', $this->objLanguage->languageText('mod_ahis_sexadd','ahis'));
                $this->setVar('headingText', $this->objLanguage->languageText('mod_ahis_sexadmin','ahis'));
                $this->setVar('action', $action);
                $this->setVar('columnName', $this->objLanguage->languageText('word_sex'));
                $this->setVar('deleteAction', 'sex_delete');
                $this->setVar('fieldName', 'name');
                $this->setVar('searchStr', $searchStr);
                $this->setVar('data', $data);
                $this->setVar('allowEdit', TRUE);
                $this->setVar('editAction', 'sex_add');
                $this->setVar('success', $this->getParam('success'));
                return 'admin_overview_tpl.php';
            
            case 'sex_add':
                $this->setVar('id', $this->getParam('id'));
                return 'sex_add_tpl.php';
            
            case 'sex_insert':
                $id = $this->getParam('id');
                $name = $this->getParam('name');
                if ($this->objSex->valueExists('name', $name)) {
                    return $this->nextAction('sex_admin', array('success'=>'4'));
                }
                if ($id) {
                    $this->objSex->update('id', $id, array('name'=>$name));
                    $code = 3;
                } else {
                    $this->objSex->insert(array('name'=>$name));
                    $code = 1;
                }
                return $this->nextAction('sex_admin', array('success'=>$code));
            
            case 'sex_delete':
                $id = $this->getParam('id');
                $this->objSex->delete('id', $id);
                return $this->nextAction('sex_admin', array('success'=>'2'));
            
            case 'outbreak_admin':
                $searchStr = $this->getParam('searchStr');
                $data = $this->objOutbreak->getAll("WHERE name LIKE '%$searchStr%' ORDER BY name");
                $this->setVar('addLinkUri', $this->uri(array('action'=>'outbreak_add')));
                $this->setVar('addLinkText', $this->objLanguage->languageText('mod_ahis_outbreakadd','ahis'));
                $this->setVar('headingText', $this->objLanguage->languageText('mod_ahis_outbreakstatusadmin','ahis'));
                $this->setVar('action', $action);
                $this->setVar('columnName', $this->objLanguage->languageText('phrase_outbreak'));
                $this->setVar('deleteAction', 'outbreak_delete');
                $this->setVar('fieldName', 'name');
                $this->setVar('searchStr', $searchStr);
                $this->setVar('data', $data);
                $this->setVar('allowEdit', TRUE);
                $this->setVar('editAction', 'outbreak_add');
                $this->setVar('success', $this->getParam('success'));
                return 'admin_overview_tpl.php';
            
            case 'outbreak_add':
                $this->setVar('id', $this->getParam('id'));
                return 'outbreak_add_tpl.php';
            
            case 'outbreak_insert':
                $id = $this->getParam('id');
                $name = $this->getParam('name');
                if ($this->objOutbreak->valueExists('name', $name)) {
                    return $this->nextAction('outbreak_admin', array('success'=>'4'));
                }
                if ($id) {
                    $this->objOutbreak->update('id', $id, array('name'=>$name));
                    $code = 3;
                } else {
                    $this->objOutbreak->insert(array('name'=>$name));
                    $code = 1;
                }
                return $this->nextAction('outbreak_admin', array('success'=>$code));
            
            case 'outbreak_delete':
                $id = $this->getParam('id');
                $this->objOutbreak->delete('id', $id);
                return $this->nextAction('outbreak_admin', array('success'=>'2'));
            
            case 'diagnosis_admin':
                $searchStr = $this->getParam('searchStr');
                $data = $this->objDiagnosis->getAll("WHERE name LIKE '%$searchStr%' ORDER BY name");
                $this->setVar('addLinkUri', $this->uri(array('action'=>'diagnosis_add')));
                $this->setVar('addLinkText', $this->objLanguage->languageText('mod_ahis_diagnosisadd','ahis'));
                $this->setVar('headingText', $this->objLanguage->languageText('mod_ahis_diagnosisadmin','ahis'));
                $this->setVar('action', $action);
                $this->setVar('columnName', $this->objLanguage->languageText('phrase_diagnosis'));
                $this->setVar('deleteAction', 'diagnosis_delete');
                $this->setVar('fieldName', 'name');
                $this->setVar('searchStr', $searchStr);
                $this->setVar('data', $data);
                $this->setVar('allowEdit', TRUE);
                $this->setVar('editAction', 'diagnosis_add');
                $this->setVar('success', $this->getParam('success'));
                return 'admin_overview_tpl.php';
            
            case 'diagnosis_add':
                $this->setVar('id', $this->getParam('id'));
                return 'diagnosis_add_tpl.php';
            
            case 'diagnosis_insert':
                $id = $this->getParam('id');
                $name = $this->getParam('name');
                if ($this->objDiagnosis->valueExists('name', $name)) {
                    return $this->nextAction('diagnosis_admin', array('success'=>'4'));
                }
                if ($id) {
                    $this->objDiagnosis->update('id', $id, array('name'=>$name));
                    $code = 3;
                } else {
                    $this->objDiagnosis->insert(array('name'=>$name));
                    $code = 1;
                }
                return $this->nextAction('diagnosis_admin', array('success'=>$code));
            
            case 'diagnosis_delete':
                $id = $this->getParam('id');
                $this->objDiagnosis->delete('id', $id);
                return $this->nextAction('diagnosis_admin', array('success'=>'2'));
            
            case 'control_admin':
                $searchStr = $this->getParam('searchStr');
                $data = $this->objControl->getAll("WHERE name LIKE '%$searchStr%' ORDER BY name");
                $this->setVar('addLinkUri', $this->uri(array('action'=>'control_add')));
                $this->setVar('addLinkText', $this->objLanguage->languageText('mod_ahis_controladd','ahis'));
                $this->setVar('headingText', $this->objLanguage->languageText('mod_ahis_controladmin','ahis'));
                $this->setVar('action', $action);
                $this->setVar('columnName', $this->objLanguage->languageText('phrase_control'));
                $this->setVar('deleteAction', 'control_delete');
                $this->setVar('fieldName', 'name');
                $this->setVar('searchStr', $searchStr);
                $this->setVar('data', $data);
                $this->setVar('allowEdit', TRUE);
                $this->setVar('editAction', 'control_add');
                $this->setVar('success', $this->getParam('success'));
                return 'admin_overview_tpl.php';
            
            case 'control_add':
                $this->setVar('id', $this->getParam('id'));
                return 'control_add_tpl.php';
            
            case 'control_insert':
                $id = $this->getParam('id');
                $name = $this->getParam('name');
                if ($this->objControl->valueExists('name', $name)) {
                    return $this->nextAction('control_admin', array('success'=>'4'));
                }
                if ($id) {
                    $this->objControl->update('id', $id, array('name'=>$name));
                    $code = 3;
                } else {
                    $this->objControl->insert(array('name'=>$name));
                    $code = 1;
                }
                return $this->nextAction('control_admin', array('success'=>$code));
            
            case 'control_delete':
                $id = $this->getParam('id');
                $this->objControl->delete('id', $id);
                return $this->nextAction('control_admin', array('success'=>'2'));
            
            case 'quality_admin':
                $searchStr = $this->getParam('searchStr');
                $data = $this->objQuality->getAll("WHERE name LIKE '%$searchStr%' ORDER BY name");
                $this->setVar('addLinkUri', $this->uri(array('action'=>'quality_add')));
                $this->setVar('addLinkText', $this->objLanguage->languageText('mod_ahis_qualityadd','ahis'));
                $this->setVar('headingText', $this->objLanguage->languageText('mod_ahis_qualityadmin','ahis'));
                $this->setVar('action', $action);
                $this->setVar('columnName', $this->objLanguage->languageText('phrase_quality'));
                $this->setVar('deleteAction', 'quality_delete');
                $this->setVar('fieldName', 'name');
                $this->setVar('searchStr', $searchStr);
                $this->setVar('data', $data);
                $this->setVar('allowEdit', TRUE);
                $this->setVar('editAction', 'quality_add');
                $this->setVar('success', $this->getParam('success'));
                return 'admin_overview_tpl.php';
            
            case 'quality_add':
                $this->setVar('id', $this->getParam('id'));
                return 'quality_add_tpl.php';
            
            case 'quality_insert':
                $id = $this->getParam('id');
                $name = $this->getParam('name');
                if ($this->objQuality->valueExists('name', $name)) {
                    return $this->nextAction('quality_admin', array('success'=>'4'));
                }
                if ($id) {
                    $this->objQuality->update('id', $id, array('name'=>$name));
                    $code = 3;
                } else {
                    $this->objQuality->insert(array('name'=>$name));
                    $code = 1;
                }
                return $this->nextAction('quality_admin', array('success'=>$code));
            
            case 'quality_delete':
                $id = $this->getParam('id');
                $this->objQuality->delete('id', $id);
                return $this->nextAction('quality_admin', array('success'=>'2'));
            
            case 'report_admin':
                $searchStr = $this->getParam('searchStr');
                $data = $this->objReport->getAll("WHERE name LIKE '%$searchStr%' ORDER BY name");
                $this->setVar('addLinkUri', $this->uri(array('action'=>'report_add')));
                $this->setVar('addLinkText', $this->objLanguage->languageText('mod_ahis_reportadd','ahis'));
                $this->setVar('headingText', $this->objLanguage->languageText('mod_ahis_reportadmin','ahis'));
                $this->setVar('action', $action);
                $this->setVar('columnName', $this->objLanguage->languageText('phrase_report'));
                $this->setVar('deleteAction', 'report_delete');
                $this->setVar('fieldName', 'name');
                $this->setVar('searchStr', $searchStr);
                $this->setVar('data', $data);
                $this->setVar('allowEdit', TRUE);
                $this->setVar('editAction', 'report_add');
                $this->setVar('success', $this->getParam('success'));
                return 'admin_overview_tpl.php';
            
            case 'report_add':
                $this->setVar('id', $this->getParam('id'));
                return 'report_add_tpl.php';
            
            case 'report_insert':
                $id = $this->getParam('id');
                $name = $this->getParam('name');
                if ($this->objReport->valueExists('name', $name)) {
                    return $this->nextAction('report_admin', array('success'=>'4'));
                }
                if ($id) {
                    $this->objReport->update('id', $id, array('name'=>$name));
                    $code = 3;
                } else {
                    $this->objReport->insert(array('name'=>$name));
                    $code = 1;
                }
                return $this->nextAction('report_admin', array('success'=>$code));
            
            case 'report_delete':
                $id = $this->getParam('id');
                $this->objReport->delete('id', $id);
                return $this->nextAction('report_admin', array('success'=>'2'));
            
            case 'age_admin':
                $searchStr = $this->getParam('searchStr');
                $data = $this->objAge->getAll("WHERE name LIKE '%$searchStr%' ORDER BY name");
                $this->setVar('addLinkUri', $this->uri(array('action'=>'age_add')));
                $this->setVar('addLinkText', $this->objLanguage->languageText('mod_ahis_ageadd','ahis'));
                $this->setVar('headingText', $this->objLanguage->languageText('mod_ahis_ageadmin','ahis'));
                $this->setVar('action', $action);
                $this->setVar('columnName', $this->objLanguage->languageText('phrase_age'));
                $this->setVar('deleteAction', 'age_delete');
                $this->setVar('fieldName', 'name');
                $this->setVar('searchStr', $searchStr);
                $this->setVar('data', $data);
                $this->setVar('allowEdit', TRUE);
                $this->setVar('editAction', 'age_add');
                $this->setVar('success', $this->getParam('success'));
                return 'admin_overview_tpl.php';
            
            case 'age_add':
                $this->setVar('id', $this->getParam('id'));
                return 'age_add_tpl.php';
            
            case 'age_insert':
                $id = $this->getParam('id');
                $name = $this->getParam('name');
                if ($this->objAge->valueExists('name', $name)) {
                    return $this->nextAction('age_admin', array('success'=>'4'));
                }
                if ($id) {
                    $this->objAge->update('id', $id, array('name'=>$name));
                    $code = 3;
                } else {
                    $this->objAge->insert(array('name'=>$name));
                    $code = 1;
                }
                return $this->nextAction('age_admin', array('success'=>$code));
            
            case 'age_delete':
                $id = $this->getParam('id');
                $this->objAge->delete('id', $id);
                return $this->nextAction('age_admin', array('success'=>'2'));
            
            case 'role_admin':
                $searchStr = $this->getParam('searchStr');
                $data = $this->objRole->getAll("WHERE name LIKE '%$searchStr%' ORDER BY name");
                $this->setVar('addLinkUri', $this->uri(array('action'=>'role_add')));
                $this->setVar('addLinkText', $this->objLanguage->languageText('mod_ahis_roleadd','ahis'));
                $this->setVar('headingText', $this->objLanguage->languageText('mod_ahis_roleadmin','ahis'));
                $this->setVar('action', $action);
                $this->setVar('columnName', $this->objLanguage->languageText('word_role'));
                $this->setVar('deleteAction', 'role_delete');
                $this->setVar('fieldName', 'name');
                $this->setVar('searchStr', $searchStr);
                $this->setVar('data', $data);
                $this->setVar('allowEdit', TRUE);
                $this->setVar('editAction', 'role_add');
                $this->setVar('success', $this->getParam('success'));
                return 'admin_overview_tpl.php';
            
            case 'role_add':
                $this->setVar('id', $this->getParam('id'));
                return 'role_add_tpl.php';
            
            case 'role_insert':
                $id = $this->getParam('id');
                $name = $this->getParam('name');
                if ($this->objRole->valueExists('name', $name)) {
                    return $this->nextAction('role_admin', array('success'=>'4'));
                }
                if ($id) {
                    $this->objRole->update('id', $id, array('name'=>$name));
                    $code = 3;
                } else {
                    $this->objRole->insert(array('name'=>$name));
                    $code = 1;
                }
                return $this->nextAction('role_admin', array('success'=>$code));
            
            case 'role_delete':
                $id = $this->getParam('id');
                $this->objRole->delete('id', $id);
                return $this->nextAction('role_admin', array('success'=>'2'));
            
            case 'department_admin':
                $searchStr = $this->getParam('searchStr');
                $data = $this->objDepartment->getAll("WHERE name LIKE '%$searchStr%' ORDER BY name");
                $this->setVar('addLinkUri', $this->uri(array('action'=>'department_add')));
                $this->setVar('addLinkText', $this->objLanguage->languageText('mod_ahis_departmentadd','ahis'));
                $this->setVar('headingText', $this->objLanguage->languageText('mod_ahis_departmentadmin','ahis'));
                $this->setVar('action', $action);
                $this->setVar('columnName', $this->objLanguage->languageText('word_department'));
                $this->setVar('deleteAction', 'department_delete');
                $this->setVar('fieldName', 'name');
                $this->setVar('searchStr', $searchStr);
                $this->setVar('data', $data);
                $this->setVar('allowEdit', TRUE);
                $this->setVar('editAction', 'department_add');
                $this->setVar('success', $this->getParam('success'));
                return 'admin_overview_tpl.php';
            
            case 'department_add':
                $this->setVar('id', $this->getParam('id'));
                return 'department_add_tpl.php';
            
            case 'department_insert':
                $id = $this->getParam('id');
                $name = $this->getParam('name');
                if ($this->objDepartment->valueExists('name', $name)) {
                    return $this->nextAction('department_admin', array('success'=>'4'));
                }
                if ($id) {
                    $this->objDepartment->update('id', $id, array('name'=>$name));
                    $code = 3;
                } else {
                    $this->objDepartment->insert(array('name'=>$name));
                    $code = 1;
                }
                return $this->nextAction('department_admin', array('success'=>$code));
            
            case 'department_delete':
                $id = $this->getParam('id');
                $this->objDepartment->delete('id', $id);
                return $this->nextAction('department_admin', array('success'=>'2'));
            
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
