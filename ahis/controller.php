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
            $this->objUserAdmin = $this->getObject('useradmin_model2', 'security');
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
            $this->objDisease = $this->getObject('disease');
            $this->objTest = $this->getObject('test');
            $this->objTestresult = $this->getObject('testresult');
            $this->objSample = $this->getObject('sample');
            $this->objSurvey = $this->getObject('survey');
            $this->objFarmingsystem = $this->getObject('farmingsystem');
            $this->objVaccination = $this->getObject('vaccinationhistory');
            $this->objBreed = $this->getObject('breed');
            $this->objSpecies = $this->getObject('species');
            $this->objActive = $this->getObject('active');
            $this->objCausative = $this->getObject('causative');
            $this->objNewherd = $this->getObject('newherd');
            $this->objViewReport = $this->getObject('report');
            $this->objSampledetails = $this->getObject('sampledetails');
            $this->objSampling = $this->getObject('sampling');
			$this->objMeatInspect = $this->getObject('db_meat_inspection');
			$this->objAnimalPopulation= $this->getObject('dbanimalpop');
			$this->objSlaughter= $this->getObject('ahis_slaughter');
			
			$this->objDeworming = $this->getObject('deworming');
			$this->objVaccineInventory=$this->getObject('vaccineinventory');
		
			$this->objAnimalmovement = $this->getObject('animalmovement');
			$this->objLivestockimport = $this->getObject('livestockimport');
			$this->objLivestockexport = $this->getObject('livestockexport');




                       
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
										'causative_add', 'causative_admin', 'causative_insert', 'causative_delete',
                                        'role_add', 'role_admin', 'role_insert', 'role_delete',
                                        'department_add', 'department_admin', 'department_insert', 'department_delete',
                                        'report_add', 'report_admin', 'report_insert', 'report_delete','disease_admin',
                                        'test_admin','testresult_admin','sample_admin','species_admin','breed_admin',
                                        'survey_admin','farmingsystem_admin','vaccinationhistory_admin','disease_add',
                                        'disease_insert','disease_delete','test_add','test_insert','disease_delete',
                                        'testresult_add','testresult_insert','testresult_delete','sample_add','sample_insert',
                                        'sample_delete','species_add','species_insert','species_delete','survey_add',
                                        'survey_insert','survey_delete','farmingsystem_add','farmingsystem_insert',
                                        'farmingsystem_delete','vaccinationhistory_add','vaccinationhistory_insert',
                                        'vaccinationhistory_delete','animal_population_add','animal_population_save','addinspectiondata','saveinspectiondata','animal_slaughter_add','animal_slaughter_save', 'animalmovement_add', 'livestockimport_add', 'livestockexport_add','deworming_add','deworming_save','vaccine_inventory_add','vaccine_inventory_save'
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
        $this->objTools = $this->getObject('tools','toolbar');
        
                
        if (!$this->objUser->isLoggedIn()) {
            $this->objTools->addToBreadCrumbs(array($this->objLanguage->languageText('word_login')));
            return 'login_tpl.php';
        }
        
        if (in_array($action, $this->adminActions) && !$this->objUser->isAdmin()) {
            $this->setVar('message', $this->objLanguage->languageText('mod_ahis_notadmin','ahis'));
            $this->setVar('location', $this->uri(array('action'=>'select_officer')));
            return 'redirect_tpl.php';
        }
        
        if (strstr($action, 'passive')) {
            $this->objTools->addToBreadCrumbs(array($this->objLanguage->languageText('phrase_passiveentry'),
                                                    $action));
        }
        
        switch ($action) {
			
			case 'unset':
				$this->unsetPassiveSession();
				$this->unsetActiveSession();
				return $this->nextAction('select_officer');
				
        	case 'select_officer':
                $this->setVar('userList', $this->objAhisUser->getList());
                $this->setVar('officerId', $this->getSession('ps_officerId', $this->objUser->userId()));
                $this->setVar('geo2Id', $this->getSession('ps_geo2Id', $this->objAhisUser->getGeo2Id()));
                $this->setVar('calendardate', $this->getSession('ps_calendardate', date('Y-m-d')));
                $this->setVar('reportType', $this->getSession('ps_reportType'));
                return 'select_officer_tpl.php';
            
            case 'report_filter':
                $this->setSession('ps_officerId', $this->getParam('officerId'));
                $this->setSession('ps_geo2Id' , $this->getParam('geo2Id'));

                $this->setSession('ps_calendardate', $this->getParam('calendardate'));
                $reportType = $this->getParam('reportType');

                $this->setSession('ps_reportType', $reportType);
                switch ($reportType) {
                    case "init_01":
                        return $this->nextAction('passive_surveillance');
					case "init_02":
                        return $this->nextAction('animal_population_add');
					case "init_03":
                        return $this->nextAction('addinspectiondata');
					case "init_04":
                        return $this->nextAction('animal_slaughter_add');
                    case "init_06":
						return $this->nextAction('animalmovement_add');
					case "init_07":
						return $this->nextAction('livestockimport_add');
					case "init_08":
						return $this->nextAction('livestockexport_add');
					case "init_09":
						return $this->nextAction('deworming_add');
					case "init_10":
						return $this->nextAction('vaccine_inventory_add');
					case "init_05":
					default:
                        return $this->nextAction('active_surveillance');
                }
            
            case 'passive_surveillance':
                $this->setVar('calendardate', $this->getSession('ps_calendardate',date('Y-m-d')));
                $this->setVar('arrayGeo2', $this->objGeo2->getAll("ORDER BY name"));
                $this->setVar('arrayOutbreakStatus', $this->objOutbreak->getAll("ORDER BY name"));
                $this->setVar('arrayQuality', $this->objQuality->getAll("ORDER BY name"));
                
                $this->setVar('geo2Id', $this->getSession('ps_geo2Id'));
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
                $oStatusId = $this->getParam('oStatusId', $this->getSession('ps_oStatusId'));
                $qualityId = $this->getParam('qualityId', $this->getSession('ps_qualityId'));
                $datePrepared = $this->getParam('datePrepared', $this->getSession('ps_datePrepared'));
                $dateIBAR = $this->getParam('dateIBAR', $this->getSession('ps_dateIBAR'));
                $dateReceived = $this->getParam('dateReceived', $this->getSession('ps_dateReceived'));
                $dateIsReported = $this->getParam('dateIsReported', $this->getSession('ps_dateIsReported'));
                $refNo = $this->getParam('refNo', $this->getSession('ps_refNo'));
                $remarks = $this->getParam('remarks', $this->getSession('ps_remarks'));

                $this->setSession('ps_oStatusId', $oStatusId);
                $this->setSession('ps_qualityId', $qualityId);
                $this->setSession('ps_datePrepared', $datePrepared);
                $this->setSession('ps_dateIBAR', $dateIBAR);
                $this->setSession('ps_dateReceived', $dateReceived);
                $this->setSession('ps_dateIsReported', $dateIsReported);
                $this->setSession('ps_refNo', $refNo);
                $this->setSession('ps_remarks', $remarks);
                
                $this->setVar('arrayGeo2', $this->objGeo2->getAll("ORDER BY name"));
                $this->setVar('arrayLocation', $this->objTerritory->getAll("ORDER BY name"));
                $this->setVar('arrayDisease', $this->objDisease->getAll("ORDER BY name"));
                $this->setVar('arrayCausative', $this->objCausative->getAll("ORDER BY name"));
				$this->setVar('calendardate', $this->getSession('ps_calendardate'));
                $this->setVar('refNo', $this->getSession('ps_refNo'));
                $this->setVar('geo2Id', $this->getSession('ps_geo2Id'));
                $this->setVar('dateVet', $this->getSession('ps_dateVet', date('Y-m-d')));
                $this->setVar('dateOccurence', $this->getSession('ps_dateOccurence', date('Y-m-d')));
                $this->setVar('dateDiagnosis', $this->getSession('ps_dateDiagnosis', date('Y-m-d')));
                $this->setVar('dateInvestigation', $this->getSession('ps_dateInvestigation', date('Y-m-d')));
                $this->setVar('locationId', $this->getSession('ps_locationId'));
                $this->setVar('latitude', $this->getSession('ps_latitude'));
                $this->setVar('longitude', $this->getSession('ps_longitude'));
                $this->setVar('diseaseId', $this->getSession('ps_diseaseId'));
                $this->setVar('causativeId', $this->getSession('ps_causativeId'));
                
                return 'passive_outbreak_tpl.php';
            
            case 'passive_species':
                $dateVet = $this->getParam('dateVet', $this->getSession('ps_dateVet'));
                $dateOccurence = $this->getParam('dateOccurence', $this->getSession('ps_dateOccurence'));
                $dateDiagnosis = $this->getParam('dateDiagnosis', $this->getSession('ps_dateDiagnosis'));
                $dateInvestigation = $this->getParam('dateInvestigation', $this->getSession('ps_dateInvestigation'));
                $locationId = $this->getParam('locationId', $this->getSession('ps_locationId'));
                $longitude = $this->getParam('longitude', $this->getSession('ps_longitude'));
                $latitude = $this->getParam('latitude', $this->getSession('ps_latitude'));
                $diseaseId = $this->getParam('diseaseId', $this->getSession('ps_diseaseId'));
                $causativeId = $this->getParam('causativeId', $this->getSession('ps_causativeId'));
                
                $this->setSession('ps_dateVet', $dateVet);
                $this->setSession('ps_dateOccurence', $dateOccurence);
                $this->setSession('ps_dateDiagnosis', $dateDiagnosis);
                $this->setSession('ps_dateInvestigation', $dateInvestigation);
                $this->setSession('ps_longitude', $longitude);
                $this->setSession('ps_latitude', $latitude);
                $this->setSession('ps_locationId', $locationId);
                $this->setSession('ps_diseaseId', $diseaseId);
                $this->setSession('ps_causativeId', $causativeId);
                
                $this->setVar('arrayGeo2', $this->objGeo2->getAll("ORDER BY name"));
                $this->setVar('arrayProduction', $this->objProduction->getAll("ORDER BY name"));
                $this->setVar('arrayAge', $this->objAge->getAll("ORDER BY name"));
                $this->setVar('arraySex', $this->objSex->getAll("ORDER BY name"));
                $this->setVar('arraySpecies', $this->objSpecies->getAll("ORDER BY name"));
                $this->setVar('arrayBasis', $this->objDiagnosis->getAll("ORDER BY name"));
                $this->setVar('arrayControl', $this->objControl->getAll("ORDER BY name"));
                
                $this->setVar('geo2Id', $this->getSession('ps_geo2Id'));
                $this->setVar('productionId', $this->getSession('ps_productionId'));
                $this->setVar('ageId', $this->getSession('ps_ageId'));
                $this->setVar('sexId', $this->getSession('ps_sexId'));
                $this->setVar('speciesId', $this->getSession('ps_speciesId'));
                $this->setVar('basisId', $this->getSession('ps_basisId'));
                $this->setVar('controlId', $this->getSession('ps_controlId'));
                
                $this->setVar('calendardate', $this->getSession('ps_calendardate'));
                $this->setVar('refNo', $this->getSession('ps_refNo'));
                $this->setVar('susceptible', $this->getSession('ps_susceptible'));
                $this->setVar('cases', $this->getSession('ps_cases'));
                $this->setVar('deaths', $this->getSession('ps_deaths'));
                $this->setVar('vaccinated', $this->getSession('ps_vaccinated'));
                $this->setVar('slaughtered', $this->getSession('ps_slaughtered'));
                $this->setVar('destroyed', $this->getSession('ps_destroyed'));
                $this->setVar('production', $this->getSession('ps_production'));
                $this->setVar('newcases', $this->getSession('ps_newcases'));
                $this->setVar('recovered', $this->getSession('ps_recovered'));
                $this->setVar('prophylactic', $this->getSession('ps_prophylactic'));
                
                return 'passive_species_tpl.php';
            
            case 'passive_vaccine':
                $this->setSession('ps_productionId', $this->getParam('productionId'));
                $this->setSession('ps_ageId', $this->getParam('ageId'));
                $this->setSession('ps_sexId', $this->getParam('sexId'));
                $this->setSession('ps_speciesId', $this->getParam('speciesId'));
                $this->setSession('ps_controlId', $this->getParam('controlId'));
                $this->setSession('ps_basisId', $this->getParam('basisId'));
                $this->setSession('ps_cases', $this->getParam('cases'));
                $this->setSession('ps_susceptible', $this->getParam('susceptible'));
                $this->setSession('ps_deaths', $this->getParam('deaths'));
                $this->setSession('ps_vaccinated', $this->getParam('vaccinated'));
                $this->setSession('ps_slaughtered', $this->getParam('slaughtered'));
                $this->setSession('ps_destroyed', $this->getParam('destroyed'));
                $this->setSession('ps_newcases', $this->getParam('newcases'));
                $this->setSession('ps_production', $this->getParam('production'));
                $this->setSession('ps_recovered', $this->getParam('recovered'));
                $this->setSession('ps_prophylactic', $this->getParam('prophylactic'));
                
                $this->setVar('calendardate', $this->getSession('ps_calendardate'));
                $this->setVar('refNo', $this->getSession('ps_refNo'));
                $this->setVar('arrayGeo2', $this->objGeo2->getAll("ORDER BY name"));
                $this->setVar('geo2Id', $this->getSession('ps_geo2Id'));
                
                return 'passive_vaccine_tpl.php';
            
            case 'passive_save':
                $ps_array['reporterid'] = $this->getSession('ps_officerId');
                $ps_array['geo2id'] = $this->getSession('ps_geo2Id');
                $ps_array['reportdate'] = $this->getSession('ps_calendardate');
                $ps_array['refno'] = $this->getSession('ps_refNo');
                
                $ps_array['statusid'] = $this->getSession('ps_oStatusId');
                $ps_array['qualityid'] = $this->getSession('ps_qualityId');
                $ps_array['prepareddate'] = $this->getSession('ps_datePrepared', date('Y-m-d'));
                $ps_array['ibardate'] = $this->getSession('ps_dateIBAR', date('Y-m-d'));
                $ps_array['dvsdate'] = $this->getSession('ps_dateReceived', date('Y-m-d'));
                $ps_array['reporteddate'] = $this->getSession('ps_dateisReported', date('Y-m-d'));
                $ps_array['remarks'] = $this->getSession('ps_remarks');
                
                $ps_array['vetdate'] = $this->getSession('ps_dateVet', date('Y-m-d'));
                $ps_array['occurencedate'] = $this->getSession('ps_dateOccurence', date('Y-m-d'));
                $ps_array['diagnosisdate'] = $this->getSession('ps_dateDiagnosis', date('Y-m-d'));
                $ps_array['investigationdate'] = $this->getSession('ps_dateInvestigation', date('Y-m-d'));
                $ps_array['latitude'] = $this->getSession('ps_latitude');
                $ps_array['longitude'] = $this->getSession('ps_longitude');
                
                $ps_array['locationid'] = $this->getSession('ps_locationId');
                $ps_array['diseaseid'] = $this->getSession('ps_diseaseId');
                $ps_array['causativeid'] = $this->getSession('ps_causativeId');
                $ps_array['speciesid'] = $this->getSession('ps_speciesId');
                $ps_array['ageid'] = $this->getSession('ps_ageId');
                $ps_array['sexid'] = $this->getSession('ps_sexId');
                $ps_array['productionid'] = $this->getSession('ps_productionId');
                $ps_array['controlmeasureid'] = $this->getSession('ps_controlId');
                $ps_array['basisofdiagnosisid'] = $this->getSession('ps_basisId');
                
                $ps_array['susceptible'] = $this->getSession('ps_susceptible');
                $ps_array['cases'] = $this->getSession('ps_cases');
                $ps_array['deaths'] = $this->getSession('ps_deaths');
                $ps_array['vaccinated'] = $this->getSession('ps_vaccinated');
                $ps_array['slaughtered'] = $this->getSession('ps_slaughtered');
                $ps_array['destroyed'] = $this->getSession('ps_destroyed');
                $ps_array['production'] = $this->getSession('ps_production');
                $ps_array['newcases'] = $this->getSession('ps_newcases');
                $ps_array['recovered'] = $this->getSession('ps_recovered');
                $ps_array['prophylactic'] = $this->getSession('ps_prophylactic');
                
                $ps_array['vaccinemanufacturedate'] = $this->getParam('dateManufactured');
                $ps_array['vaccineexpirydate'] = $this->getParam('dateExpire');
                $ps_array['vaccinesource'] = $this->getParam('source');
                $ps_array['vaccinebatch'] = $this->getParam('batch');
                $ps_array['vaccinetested'] = ($this->getParam('panvac') == 'on')? true : false;
                
                $result = $this->objPassive->insert($ps_array);
                
                return $this->nextAction('passive_feedback', array('success'=>$result));
            
            case 'passive_feedback':
                $success = $this->getParam('success');
                $this->setVar('success', $success);
                if ($success) {
                    $this->unsetPassiveSession();
                }
                
                return "passive_feedback_tpl.php";
                
            case 'view_reports':
                $outputType = $this->getParam('outputType', 2);
                $reportType = $this->getParam('reportType');
                $month = $this->getParam('month', date('m'));
                $year = $this->getParam('year', date('Y'));
                $reportName = $this->objReport->getRow('id', $reportType);
                $fileName = str_replace(" ", "_", "{$reportName['name']}_$month-$year");
                switch ($outputType) {
                    case 1:
                        //csv
                        $csv = $this->objViewReport->generateCSV($year, $month, $reportType);
                        header("Content-Type: application/csv"); 
                       //header("Content-length: " . sizeof($csv)); 
                        header("Content-Disposition: attachment; filename=$fileName.csv"); 
                        echo $csv;
                        break;
                    case 3:
                        //pdf
                        $html = $this->objViewReport->generateReport($year, $month, $reportType, 'true');
                        //$objPDF = $this->getObject('dompdfwrapper','dompdf');
                        //$objPDF->setPaper('a4', 'landscape');
                        //$objPDF->generatePDF($html, "$fileName.pdf");
                        echo "$html <br />not yet implemented";
                        break;
                    default:
                        $this->setVar('reportTypes', $this->objReport->getAll("ORDER BY name"));
                        $this->setVar('year', $year);
                        $this->setVar('month', $month);
                        $this->setVar('outputType', $outputType);
                        $this->setVar('reportType', $reportType);
                        $this->setVar('enter', $this->getParam('enter'));
                        return "view_reports_tpl.php";
                }
                
                break;
                
            case 'active_surveillance':
               $this->setVar('campName', $this->getSession('ps_campName'));


               $this->setVar('userList', $this->objAhisUser->getList());
               $this->setVar('officerId', $this->getSession('ps_officerId'));
               $this->setVar('arraydisease', $this->objDisease->getAll("ORDER BY NAME"));
               $this->setVar('arraysurvey', $this->objSurvey->getAll("ORDER BY NAME"));
               $this->setVar('disease', $this->getSession('ps_disease'));
               $this->setVar('surveyTypeId', $this->getSession('ps_surveyTypeId'));
               $this->setVar('comments', $this->getSession('ps_comments'));   
               return 'active_surveillance_tpl.php';  
                
            case 'active_addtest':
            
               $campName = $this->getParam('campName', $this->getSession('ps_campName'));
               $officerId = $this->getParam('officerId', $this->getSession('ps_officerId'));

               $disease = $this->getParam('disease', $this->getSession('ps_disease'));
               $surveyTypeId = $this->getParam('surveyTypeId', $this->getSession('ps_surveyTypeId'));
               $comments = $this->getParam('comments', $this->getSession('ps_comments'));

               $this->setSession('ps_campName',$campName);
	            $this->setSession('ps_officerId',$officerId);
	            $this->setSession('ps_disease',$disease);
	            $this->setSession('ps_surveyTypeId',$surveyTypeId);
	            $this->setSession('ps_comments',$comments);
	            




               $this->setVar('arraydisease', $this->objDisease->getAll("ORDER BY NAME"));
               $this->setVar('arraytest', $this->objTest->getAll("ORDER BY NAME"));
               $this->setVar('campName', $this->getSession('ps_campName'));
               $this->setVar('disease', $this->getSession('ps_disease'));
               $this->setVar('testtype', $testype);
               return 'active_addtest_tpl.php';
               
            case 'active_insert':
               $campName = $this->getParam('campName', $this->getSession('ps_campName'));
               $officerId = $this->getParam('officerId', $this->getSession('ps_officerId'));
               $disease = $this->getParam('disease', $this->getSession('ps_disease'));
               $surveyTypeId = $this->getParam('surveyTypeId', $this->getSession('ps_surveyTypeId'));
               $comments = $this->getParam('comments', $this->getSession('ps_comments'));

               $this->setSession('ps_campName',$campName);
	            $this->setSession('ps_officerId',$officerId);
	            $this->setSession('ps_disease',$disease);
	            $this->setSession('ps_surveyTypeId',$surveyTypeId);
	            $this->setSession('ps_comments',$comments);
        
                $ps_array = array();
                $ps_array['reporterid'] = $this->getSession('ps_officerId');
                $ps_array['campname'] = $this->getSession('ps_campName');
                $ps_array['disease'] =$this->getSession('ps_disease');
                $ps_array['surveytype'] = $this->getSession('ps_surveyTypeId');
                $ps_array['comments'] = $this->getSession('ps_comments');
                $ps_array['sensitivity'] = $this->getParam('sensitivity');
                $ps_array['specificity'] = $this->getParam('specificity');
                $ps_array['testtype'] = $this->getParam('testtype');
               //print_r($this->getSession('ps_officerId'));
                $result = $this->objActive->insert($ps_array);
                                            
                return $this->nextAction('active_addherd',array('success'=>$result));           
                
            
            case 'active_feedback':
                $success = $this->getParam('success');
                $campname = $this->getParam('campname');
                $this->setSession('ps_campName',$campName);
                $this->setVar('success', $success);
                $this->setVar('campaign', $this->getSession('ps_campName'));
                if ($success) {
                    $this->unsetActiveSession();
                } 

                return $this->nextAction('select_officer'); 
                         
                 
           

                          
             
            
            case 'active_herddetails':
               $campName = $this->getParam('campName',$this->getSession('ps_campName'));

               $this->setSession('ps_campName', $campName);

               $disease = $this->getParam('disease');             
               $this->setSession('ps_diseases', $disease);
               $this->setVar('arrayCamp', $this->objActive->listcamp());
               $this->setVar('arraydisease', $this->objActive->getall($this->getSession('ps_campName')));
               $this->setVar('disease', $this->getSession('ps_disease')); 
               $this->setVar('officerId', $this->getSession('ps_officerId'));

               return 'active_herddetails_tpl.php';   
                
            case 'active_addherd':
                $data =$this->objActive->getall($this->getSession('ps_campName'));
               $this->setVar('activeid',$data[0]['id']);
               $this->setVar('arrayTerritory',$this->objTerritory->getAll("ORDER BY NAME"));
               $this->setVar('arrayFarmingsystem',$this->objFarmingsystem->getAll("ORDER BY NAME"));
               $this->setVar('arraygeo2',$this->objGeo2->getAll("ORDER BY NAME"));
               $this->setVar('arraygeo3',$this->objGeo3->getAll("ORDER BY NAME"));
               return 'active_addherd_tpl.php';
       
       
            case 'newherd_insert':
                $id = $this->getParam('id');
                $this->setSession('ps_activeid',$this->getParam('activeid'));
                $this->setSession('ps_farm',$this->getParam('farm'));
                $this->setSession('ps_farmingsystem',$this->getParam('farmingsystem'));
                
                $arrayherd = array();
                $arrayherd['territory'] = $this->getParam('territory');
                $arrayherd['geolevel2'] = $this->getParam('Geo2');
                $arrayherd['geolevel3'] = $this->getParam('Geo3');
                $arrayherd['farmname'] = $this->getSession('ps_farm');
                $arrayherd['farmingtype'] = $this->getSession('ps_farmingsystem');
                $arrayherd['activeid'] = $this->getSession('ps_activeid');
                if ($id) {
                    $this->objNewherd->update('id', $id, $arrayherd);
                    $code = 3;
                } else {
                    $this->objNewherd->insert($arrayherd);  
                    $code = 1;
                }             
               $div = $this->getParam('next');
               //print_r($div);
               if($div ==$this->objLanguage->languageText('word_next'))
               {
                return $this->nextAction('active_addsample');
               }else
                return $this->nextAction('active_addherd');
                
            case 'newherd_delete':
               $id = $this->getParam('id');
               $this->objNewherd->delete('id', $id);
               return $this->nextAction('active_newherd', array('success'=>'2'));
          

            case 'active_sampleview':
               $newherdid = $this->getSession('ps_newherdid');
               $this->setSession('ps_number',$number);
               $data = $this->getSession('ps_newherd');
               $datan= $this->objSampledetails->getall();
               $this->setVar('newherdid',$newherdid);
               $this->setVar('calendardate', $this->getSession('ps_calendardate', date('Y-m-d')));
               $this->setVar('data',$data);
               $this->setVar('datan',$datan);
	            $this->setVar('number',$this->getSession('ps_number'));
	            $this->setVar('i',count($data));
               return 'active_sampleview_tpl.php';
               
            case 'active_addsample':
            
               $newherdid =$this->objNewherd->getherd($this->getSession('ps_activeid'));
               $this->setSession('ps_newherdid',$newherdid[0]['id']);
               $this->setSession('ps_newherd',$newherdid);
               $this->setVar('id',$this->getParam('id'));
               $this->setVar('farm',$this->getSession('ps_farm'));
               $this->setVar('farmingsystem',$this->getSession('ps_farmingsystem'));
               $this->setVar('campName', $this->getSession('ps_campName'));
               $this->setVar('newherd',$this->getSession('ps_newherd'));
               $this->setVar('arraySpecies',$this->objSpecies->getAll("ORDER BY NAME"));
               $this->setVar('arrayAge',$this->objAge->getAll("ORDER BY NAME"));
               $this->setVar('arraySex',$this->objSex->getAll("ORDER BY NAME"));
               $this->setVar('arraySample',$this->objSample->getAll("ORDER BY NAME"));
               $this->setVar('arrayTest',$this->objTest->getAll("ORDER BY NAME"));
               $this->setVar('arrayTestresult',$this->objTestresult->getAll("ORDER BY NAME"));
               $this->setVar('arrayVac',$this->objVaccination->getAll("ORDER BY NAME"));
               $this->setVar('calendardate', $this->getSession('ps_calendardate', date('Y-m-d')));
               return 'active_addsample_tpl.php';
               
           case 'sampleview_insert':
                $id = $this->getParam('id');
                $this->setSession('ps_newherdid',$this->getParam('farm'));
                $arrayherd = array();
                $arrayherd['newherdid'] = $this->getParam('newherdid',$this->getParam('farm'));
                $arrayherd['species'] = $this->getParam('species');
                $arrayherd['age'] = $this->getParam('age');
                $arrayherd['sex'] = $this->getParam('sex');
                $arrayherd['sampletype'] = $this->getParam('sampletype');
                $arrayherd['testtype'] = $this->getParam('testtype');
                $arrayherd['testresult'] = $this->getParam('testresult');
                $arrayherd['vachist'] = $this->getParam('vachistory');
                $arrayherd['sampleid'] = $this->getParam('sampleid');
                $arrayherd['animalid'] = $this->getParam('animalid');
                $arrayherd['samplingdate'] = $this->getSession('ps_calenda', date('Y-m-d'));
                $arrayherd['number'] = $this->getParam('number');
                $arrayherd['remarks'] = $this->getParam('remarks');
                $arrayherd['specification'] = $this->getParam('spec');
                $arrayherd['testdate'] = $this->getParam('calendardate');

                //print_r($arrayherd);
                $this->setSession('ps_data',$arrayherd);
               
                if ($id) {
                    $this->objSampledetails->update('id', $id, $arrayherd);
                    $code = 3;
                } else {
                    $this->objSampledetails->insert($arrayherd); 

                    $code = 1;
                } 
               
                return $this->nextAction('active_sampleview', array('success'=>$code));
                
            case 'sampleview_delete':
               $id = $this->getParam('id');
               $this->objSampledetails->delete('id', $id);
               return $this->nextAction('active_sampleview', array('success'=>'2'));
          
            
                return $this->nextAction('active_sampleview', array('success'=>$code));
                      
           
          
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
				$superDisabled = ($this->objAhisUser->isSuperUser($this->objUser->userId()))? 0 : 1;
				$this->setVar('superDisabled', $superDisabled);
                return "add_employee_tpl.php";
            
            case 'employee_insert':
                $id = $this->getParam('id');
                $record['surname'] = $this->getParam('surname');
                $record['firstname'] = $this->getParam('name');
                $test = $this->objUser->getAll("WHERE firstname = '{$record['firstname']}' AND surname = '{$record['surname']}'");
                $record['username'] = $this->getParam('username');
                $password = $this->getParam('password');
                $ahisRecord['titleid'] = $this->getParam('titleid');
                $ahisRecord['statusid'] = $this->getParam('statusid');
                if ($ahisRecord['statusid'] == "init_02") {
                    $record['isactive'] = 0;
                }
                $ahisRecord['ahisuser'] = $this->getParam('ahisuser');
                if ($ahisRecord['ahisuser']) {
                    $ahisRecord['ahisuser'] = 1;
					$record['isactive'] = 1;
                    if ((!$record['username'] || !$password) && !$id) {
                        return $this->nextAction('create_employee', array('error'=>1, 'id'=>$id));
                    }
                } else {
                    $ahisRecord['ahisuser'] = 0;
                    $record['isactive'] = 0;
                }
				if ($this->getParam('superuser')) {
					$ahisRecord['superuser'] = 1;
				} else {
					$ahisRecord['superuser'] = 0;
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
					if ($password) {
						$record['pass'] = sha1($password);
					}
                    $this->objUser->update('id', $id, $record);
                    $code = 3;
                } else {
                    if (!empty($test)) {
                        return $this->nextAction('employee_admin', array('success'=>'4'));
                    }
					$userid = $this->objUserAdmin->generateUserId();
                    $id = $this->objUserAdmin->addUser($userid, $record['username'], $password, NULL, $record['firstname'], $record['surname'], NULL, NULL, NULL, NULL, NULL, "useradmin", $record['isactive']);
					//$id = $this->objUser->insert($record);
                    $code = 1;
                }
				if ($this->getParam('adminuser')) {
					$urec = $this->objUserAdmin->getArray("SELECT perm_user_id FROM tbl_perms_perm_users WHERE auth_user_id = '$userid'");
					$groupAdmin = $this->getObject('groupadminmodel', 'groupadmin');
					$gid = $groupAdmin->getId("Site Admin");
					$groupAdmin->addGroupUser($gid, $urec[0]['perm_user_id']);
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
                $this->setVar('superUser', $this->objAhisUser->isSuperUser($this->objUser->userId()));
                $this->setVar('editAction', 'report_add');
                $this->setVar('success', $this->getParam('success'));
                return 'report_admin_tpl.php';
            
            case 'report_add':
				if (!$this->objAhisUser->isSuperUser($this->objUser->userId())) {
					return $this->nextAction('admin');
				}
                $this->setVar('id', $this->getParam('id'));
                return 'report_add_tpl.php';
            
            case 'report_insert':
                if (!$this->objAhisUser->isSuperUser($this->objUser->userId())) {
					return $this->nextAction('admin');
				}
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
                if (!$this->objAhisUser->isSuperUser($this->objUser->userId())) {
					return $this->nextAction('admin');
				}
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
            
			case 'causative_admin':
                $searchStr = $this->getParam('searchStr');
                $data = $this->objCausative->getAll("WHERE name LIKE '%$searchStr%' ORDER BY name");
                $this->setVar('addLinkUri', $this->uri(array('action'=>'causative_add')));
                $this->setVar('addLinkText', $this->objLanguage->languageText('mod_ahis_causativeadd','ahis'));
                $this->setVar('headingText', $this->objLanguage->languageText('mod_ahis_causativeadmin','ahis'));
                $this->setVar('action', $action);
                $this->setVar('columnName', $this->objLanguage->languageText('word_causative'));
                $this->setVar('deleteAction', 'causative_delete');
                $this->setVar('fieldName', 'name');
                $this->setVar('searchStr', $searchStr);
                $this->setVar('data', $data);
                $this->setVar('allowEdit', TRUE);
                $this->setVar('editAction', 'causative_add');
                $this->setVar('success', $this->getParam('success'));
                return 'admin_overview_tpl.php';
            
            case 'causative_add':
                $this->setVar('id', $this->getParam('id'));
                return 'causative_add_tpl.php';
            
            case 'causative_insert':
                $id = $this->getParam('id');
                $name = $this->getParam('name');
                if ($this->objCausative->valueExists('name', $name)) {
                    return $this->nextAction('causative_admin', array('success'=>'4'));
                }
                if ($id) {
                    $this->objCausative->update('id', $id, array('name'=>$name));
                    $code = 3;
                } else {
                    $this->objCausative->insert(array('name'=>$name));
                    $code = 1;
                }
                return $this->nextAction('causative_admin', array('success'=>$code));
            
            case 'causative_delete':
                $id = $this->getParam('id');
                $this->objCausative->delete('id', $id);
                return $this->nextAction('causative_admin', array('success'=>'2'));
            
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
            
            case 'disease_admin':
                $searchStr = $this->getParam('searchStr');
                $data = $this->objDisease->getAll("WHERE name LIKE '%$searchStr%' ORDER BY name");
                $this->setVar('addLinkUri', $this->uri(array('action'=>'disease_add')));
                $this->setVar('addLinkText', $this->objLanguage->languageText('mod_ahis_diseaseadd','ahis'));
                $this->setVar('headingText', $this->objLanguage->languageText('mod_ahis_diseaseadmin','ahis'));
                $this->setVar('action', $action);
                $this->setVar('columnName', $this->objLanguage->languageText('word_disease'));
                $this->setVar('deleteAction', 'disease_delete');
                $this->setVar('fieldName', 'name');
                $this->setVar('searchStr', $searchStr);
                $this->setVar('data', $data);
                $this->setVar('allowEdit', TRUE);
                $this->setVar('editAction', 'disease_add');
                $this->setVar('success', $this->getParam('success'));
                return 'admin_overview_tpl.php';
            
            case 'disease_add':
                $this->setVar('id', $this->getParam('id'));
                return 'disease_add_tpl.php';
               
             case 'disease_insert':
                $id = $this->getParam('id');
                $name = $this->getParam('name');
                if ($this->objDisease->valueExists('name', $name)) {
                    return $this->nextAction('disease_admin', array('success'=>'4'));
                }
                if ($id) {
                    $this->objDisease->update('id', $id, array('name'=>$name));
                    $code = 3;
                } else {
                    $this->objDisease->insert(array('name'=>$name));
                    $code = 1;
                }
                return $this->nextAction('disease_admin', array('success'=>$code));
            case 'disease_delete':
                $id = $this->getParam('id');
                $this->objDisease->delete('id', $id);
                return $this->nextAction('disease_admin', array('success'=>'2'));
              
            case 'test_admin':
                $searchStr = $this->getParam('searchStr');
                $data = $this->objTest->getAll("WHERE name LIKE '%$searchStr%' ORDER BY name");
                $this->setVar('addLinkUri', $this->uri(array('action'=>'test_add')));
                $this->setVar('addLinkText', $this->objLanguage->languageText('mod_ahis_testadd','ahis'));
                $this->setVar('headingText', $this->objLanguage->languageText('mod_ahis_testadmin','ahis'));
                $this->setVar('action', $action);
                $this->setVar('columnName', $this->objLanguage->languageText('word_test'));
                $this->setVar('deleteAction', 'test_delete');
                $this->setVar('fieldName', 'name');
                $this->setVar('searchStr', $searchStr);
                $this->setVar('data', $data);
                $this->setVar('allowEdit', TRUE);
                $this->setVar('editAction', 'test_add');
                $this->setVar('success', $this->getParam('success'));
                return 'admin_overview_tpl.php';
                
                
            case 'test_add':
                $this->setVar('id', $this->getParam('id'));
                return 'test_add_tpl.php';
             case 'test_insert':
                $id = $this->getParam('id');
                $name = $this->getParam('name');
                if ($this->objTest->valueExists('name', $name)) {
                    return $this->nextAction('test_admin', array('success'=>'4'));
                }
                if ($id) {
                    $this->objTest->update('id', $id, array('name'=>$name));
                    $code = 3;
                } else {
                    $this->objTest->insert(array('name'=>$name));
                    $code = 1;
                }
                return $this->nextAction('test_admin', array('success'=>$code));
            case 'test_delete':
                $id = $this->getParam('id');
                $this->objTest->delete('id', $id);
                return $this->nextAction('test_admin', array('success'=>'2'));
             
            case 'testresult_admin':
                $searchStr = $this->getParam('searchStr');
                $data = $this->objTestresult->getAll("WHERE name LIKE '%$searchStr%' ORDER BY name");
                $this->setVar('addLinkUri', $this->uri(array('action'=>'testresult_add')));
                $this->setVar('addLinkText', $this->objLanguage->languageText('mod_ahis_testresultadd','ahis'));
                $this->setVar('headingText', $this->objLanguage->languageText('mod_ahis_testresultadmin','ahis'));
                $this->setVar('action', $action);
                $this->setVar('columnName', $this->objLanguage->languageText('phrase_testresult'));
                $this->setVar('deleteAction', 'testresult_delete');
                $this->setVar('fieldName', 'name');
                $this->setVar('searchStr', $searchStr);
                $this->setVar('data', $data);
                $this->setVar('allowEdit', TRUE);
                $this->setVar('editAction', 'testresult_add');
                $this->setVar('success', $this->getParam('success'));
                return 'admin_overview_tpl.php';
            
            case 'testresult_add':
                $this->setVar('id', $this->getParam('id'));
                return 'testresult_add_tpl.php';
             case 'testresult_insert':
                $id = $this->getParam('id');
                $name = $this->getParam('name');
                if ($this->objTestresult->valueExists('name', $name)) {
                    return $this->nextAction('testresult_admin', array('success'=>'4'));
                }
                if ($id) {
                    $this->objTestresult->update('id', $id, array('name'=>$name));
                    $code = 3;
                } else {
                    $this->objTestresult->insert(array('name'=>$name));
                    $code = 1;
                }
                return $this->nextAction('testresult_admin', array('success'=>$code));
            case 'testresult_delete':
                $id = $this->getParam('id');
                $this->objTestresult->delete('id', $id);
                return $this->nextAction('testresult_admin', array('success'=>'2'));
                 
            case 'sample_admin':
                $searchStr = $this->getParam('searchStr');
                $data = $this->objSample->getAll("WHERE name LIKE '%$searchStr%' ORDER BY name");
                $this->setVar('addLinkUri', $this->uri(array('action'=>'sample_add')));
                $this->setVar('addLinkText', $this->objLanguage->languageText('mod_ahis_sampleadd','ahis'));
                $this->setVar('headingText', $this->objLanguage->languageText('mod_ahis_sampleadmin','ahis'));
                $this->setVar('action', $action);
                $this->setVar('columnName', $this->objLanguage->languageText('word_sample'));
                $this->setVar('deleteAction', 'sample_delete');
                $this->setVar('fieldName', 'name');
                $this->setVar('searchStr', $searchStr);
                $this->setVar('data', $data);
                $this->setVar('allowEdit', TRUE);
                $this->setVar('editAction', 'sample_add');
                $this->setVar('success', $this->getParam('success'));
                return 'admin_overview_tpl.php';
            
            case 'sample_add':
                $this->setVar('id', $this->getParam('id'));
                return 'sample_add_tpl.php';
            
             case 'sample_insert':
                $id = $this->getParam('id');
                $name = $this->getParam('name');
                if ($this->objSample->valueExists('name', $name)) {
                    return $this->nextAction('sample_admin', array('success'=>'4'));
                }
                if ($id) {
                    $this->objSample->update('id', $id, array('name'=>$name));
                    $code = 3;
                } else {
                    $this->objSample->insert(array('name'=>$name));
                    $code = 1;
                }
                return $this->nextAction('sample_admin', array('success'=>$code));
            case 'sample_delete':
                $id = $this->getParam('id');
                $this->objSample->delete('id', $id);
                return $this->nextAction('sample_admin', array('success'=>'2'));
             
            case 'survey_admin':
                $searchStr = $this->getParam('searchStr');
                $data = $this->objSurvey->getAll("WHERE name LIKE '%$searchStr%' ORDER BY name");
                $this->setVar('addLinkUri', $this->uri(array('action'=>'survey_add')));
                $this->setVar('addLinkText', $this->objLanguage->languageText('mod_ahis_surveyadd','ahis'));
                $this->setVar('headingText', $this->objLanguage->languageText('mod_ahis_surveyadmin','ahis'));
                $this->setVar('action', $action);
                $this->setVar('columnName', $this->objLanguage->languageText('word_survey'));
                $this->setVar('deleteAction', 'survey_delete');
                $this->setVar('fieldName', 'name');
                $this->setVar('searchStr', $searchStr);
                $this->setVar('data', $data);
                $this->setVar('allowEdit', TRUE);
                $this->setVar('editAction', 'survey_add');
                $this->setVar('success', $this->getParam('success'));
                return 'admin_overview_tpl.php';
                
            case 'survey_add':
                $this->setVar('id', $this->getParam('id'));
                return 'survey_add_tpl.php';
            
             case 'survey_insert':
                $id = $this->getParam('id');
                $name = $this->getParam('name');
                if ($this->objSurvey->valueExists('name', $name)) {
                    return $this->nextAction('survey_admin', array('success'=>'4'));
                }
                if ($id) {
                    $this->objSurvey->update('id', $id, array('name'=>$name));
                    $code = 3;
                } else {
                    $this->objSurvey->insert(array('name'=>$name));
                    $code = 1;
                }
                return $this->nextAction('survey_admin', array('success'=>$code));
            case 'survey_delete':
                $id = $this->getParam('id');
                $this->objSurvey->delete('id', $id);
                return $this->nextAction('survey_admin', array('success'=>'2'));
             
            
            case 'farmingsystem_admin':
                $searchStr = $this->getParam('searchStr');
                $data = $this->objFarmingsystem->getAll("WHERE name LIKE '%$searchStr%' ORDER BY name");
                $this->setVar('addLinkUri', $this->uri(array('action'=>'farmingsystem_add')));
                $this->setVar('addLinkText', $this->objLanguage->languageText('mod_ahis_farmingsystemadd','ahis'));
                $this->setVar('headingText', $this->objLanguage->languageText('mod_ahis_farmingsystemadmin','ahis'));
                $this->setVar('action', $action);
                $this->setVar('columnName', $this->objLanguage->languageText('word_farming')." ".$this->objLanguage->languageText('word_system'));
                $this->setVar('deleteAction', 'farmingsystem_delete');
                $this->setVar('fieldName', 'name');
                $this->setVar('searchStr', $searchStr);
                $this->setVar('data', $data);
                $this->setVar('allowEdit', TRUE);
                $this->setVar('editAction', 'farmingsystem_add');
                $this->setVar('success', $this->getParam('success'));
                return 'admin_overview_tpl.php';
           
            case 'farmingsystem_add':
                $this->setVar('id', $this->getParam('id'));
                return 'farmingsystem_add_tpl.php';
				
				
            
            case 'farmingsystem_insert':
                $id = $this->getParam('id');
                $name = $this->getParam('name');
                if ($this->objFarmingsystem->valueExists('name', $name)) {
                    return $this->nextAction('farmingsystem_admin', array('success'=>'4'));
                }
                if ($id) {
                    $this->objFarmingsystem->update('id', $id, array('name'=>$name));
                    $code = 3;
                } else {
                    $this->objFarmingsystem->insert(array('name'=>$name));
                    $code = 1;
                }
                return $this->nextAction('farmingsystem_admin', array('success'=>$code));
				
				
            case 'farmingsystem_delete':
                $id = $this->getParam('id');
                $this->objFarmingsystem->delete('id', $id);
                return $this->nextAction('survey_admin', array('success'=>'2'));
             
            
            case 'vaccinationhistory_admin':
                $searchStr = $this->getParam('searchStr');
                $data = $this->objVaccination->getAll("WHERE name LIKE '%$searchStr%' ORDER BY name");
                $this->setVar('addLinkUri', $this->uri(array('action'=>'vaccination_add')));
                $this->setVar('addLinkText', $this->objLanguage->languageText('mod_ahis_vaccinationadd','ahis'));
                $this->setVar('headingText', $this->objLanguage->languageText('mod_ahis_vaccinationadmin','ahis'));
                $this->setVar('action', $action);
                $this->setVar('columnName', $this->objLanguage->languageText('phrase_vaccinationhistory'));
                $this->setVar('deleteAction', 'vaccination_delete');
                $this->setVar('fieldName', 'name');
                $this->setVar('searchStr', $searchStr);
                $this->setVar('data', $data);
                $this->setVar('allowEdit', TRUE);
                $this->setVar('editAction', 'vaccination_add');
                $this->setVar('success', $this->getParam('success'));
                return 'admin_overview_tpl.php';
            
            case 'vaccination_add':
                $this->setVar('id', $this->getParam('id'));
                return 'vaccination_add_tpl.php';
             case 'vaccination_insert':
                $id = $this->getParam('id');
                $name = $this->getParam('name');
                if ($this->objSurvey->valueExists('name', $name)) {
                    return $this->nextAction('vaccination_admin', array('success'=>'4'));
                }
                if ($id) {
                    $this->objVaccination->update('id', $id, array('name'=>$name));
                    $code = 3;
                } else {
                    $this->objVaccination->insert(array('name'=>$name));
                    $code = 1;
                }
                return $this->nextAction('vaccinationhistory_admin', array('success'=>$code));
            case 'vaccination_delete':
                $id = $this->getParam('id');
                $this->objVaccination->delete('id', $id);
                return $this->nextAction('vaccinationhistory_admin', array('success'=>'2'));
             
            
            case 'species_admin':
                $searchStr = $this->getParam('searchStr');
                $data = $this->objSpecies->getAll("WHERE name LIKE '%$searchStr%' ORDER BY name");
                $this->setVar('addLinkUri', $this->uri(array('action'=>'species_add')));
                $this->setVar('addLinkText', $this->objLanguage->languageText('mod_ahis_speciesadd','ahis'));
                $this->setVar('headingText', $this->objLanguage->languageText('mod_ahis_speciesadmin','ahis'));
                $this->setVar('action', $action);
                $this->setVar('columnName', $this->objLanguage->languageText('word_species'));
                $this->setVar('deleteAction', 'species_delete');
                $this->setVar('fieldName', 'name');
                $this->setVar('searchStr', $searchStr);
                $this->setVar('data', $data);
                $this->setVar('allowEdit', TRUE);
                $this->setVar('editAction', 'species_add');
                $this->setVar('success', $this->getParam('success'));
                return 'admin_overview_tpl.php';
            
            case 'species_add':
                $this->setVar('id', $this->getParam('id'));
                return 'species_add_tpl.php';
             case 'species_insert':
                $id = $this->getParam('id');
                $name = $this->getParam('name');
                if ($this->objSurvey->valueExists('name', $name)) {
                    return $this->nextAction('species_admin', array('success'=>'4'));
                }
                if ($id) {
                    $this->objSpecies->update('id', $id, array('name'=>$name));
                    $code = 3;
                } else {
                    $this->objSpecies->insert(array('name'=>$name));
                    $code = 1;
                }
                return $this->nextAction('species_admin', array('success'=>$code));
            case 'species_delete':
                $id = $this->getParam('id');
                $this->objSpecies->delete('id', $id);
                return $this->nextAction('species_admin', array('success'=>'2'));
             
            
            case 'breed_admin':
                $searchStr = $this->getParam('searchStr');
                $data = $this->objBreed->getAll("WHERE name LIKE '%$searchStr%' ORDER BY name");
                $this->setVar('addLinkUri', $this->uri(array('action'=>'breed_add')));
                $this->setVar('addLinkText', $this->objLanguage->languageText('mod_ahis_breedadd','ahis'));
                $this->setVar('headingText', $this->objLanguage->languageText('mod_ahis_breedadmin','ahis'));
                $this->setVar('action', $action);
                $this->setVar('columnName', $this->objLanguage->languageText('word_breed'));
                $this->setVar('deleteAction', 'breed_delete');
                $this->setVar('fieldName', 'name');
                $this->setVar('searchStr', $searchStr);
                $this->setVar('data', $data);
                $this->setVar('allowEdit', TRUE);
                $this->setVar('editAction', 'breed_add');
                $this->setVar('success', $this->getParam('success'));
                return 'admin_overview_tpl.php';
          
             case 'breed_add':
                $this->setVar('id', $this->getParam('id'));
                return 'breed_add_tpl.php';
             case 'breed_insert':
                $id = $this->getParam('id');
                $name = $this->getParam('name');
                if ($this->objBreed->valueExists('name', $name)) {
                    return $this->nextAction('breed_admin', array('success'=>'4'));
                }
                if ($id) {
                    $this->objBreed->update('id', $id, array('name'=>$name));
                    $code = 3;
                } else {
                    $this->objBreed->insert(array('name'=>$name));
                    $code = 1;
                }
                return $this->nextAction('breed_admin', array('success'=>$code));
            case 'breed_delete':
                $id = $this->getParam('id');
                $this->objSurvey->delete('id', $id);
                return $this->nextAction('breed_admin', array('success'=>'2'));
			
			case 'animalmovement_add':
					$id=$this->getSession('ps_geo2Id');
			 		$this->setVar('dist',$this->objAnimalmovement->getDistrict($id));
                	$this->setVar('species', $this->objSpecies ->getAll("ORDER BY name"));
				//$this->setVar('id', $this->getParam('id'));
				//$this->setVar('d',$this->getSession('ps_geo2Id'));
				//$this->setVar('arrayGeo2', $this->objGeo2->getAll("ORDER BY name"));
				return 'add_animalmovement_tpl.php';
				
			case 'livestockimport_add':
					$id=$this->getSession('ps_geo2Id');
			 		$this->setVar('dist',$this->objLivestockimport->getDistrict($id));
                	$this->setVar('species', $this->objSpecies ->getAll("ORDER BY name"));
					$this->setVar('geo2', $this->objGeo2 ->getAll("ORDER BY name"));
				return 'add_livestockimport_tpl.php';
				
			case 'livestockexport_add':
					$id=$this->getSession('ps_geo2Id');
			 		$this->setVar('dist',$this->objLivestockexport->getDistrict($id));
                	$this->setVar('species', $this->objSpecies ->getAll("ORDER BY name"));
					$this->setVar('geo2', $this->objGeo2 ->getAll("ORDER BY name"));
				return 'add_livestockexport_tpl.php';
				
             case 'animal_population_add':
			 		$id=$this->getSession('ps_geo2Id');
			 		$this->setVar('dist',$this->objAnimalPopulation->getDistrict($id));
                	$this->setVar('species', $this->objSpecies ->getAll("ORDER BY name"));		
				return 'animal_population_tpl.php';
				
			case 'addinspectiondata':
				$id=$this->getSession('ps_geo2Id');
			 	$this->setVar('dist',$this->objAnimalPopulation->getDistrict($id));
			return 'meat_inspection_tpl.php';
			
			case 'animal_slaughter_add':
				$id=$this->getSession('ps_geo2Id');
				 $this->setVar('dist',$this->objAnimalPopulation->getDistrict($id));
			return 'slaughter_tpl.php';
		 		$this->setVar('arrayGeo2', $this->objGeo2->getAll("ORDER BY name"));				
				return 'slaughter_tpl.php';
			case 'animal_population_save':
				return $this->SaveData();				
			case 'saveinspectiondata':
				return $this->saveInspectionData();
			case 'animal_slaughter_save':
				return $this->saveSlaughterData();
			
			case 'animalmovement_save':
				return $this->saveAnimalmovementData();
			case 'livestockimport_save':
				return $this->saveLivestockimportData();
			case 'livestockexport_save':
            	return $this->saveLivestockexportData();
				
			case 'vaccine_inventory_add':
				return 'vaccine_inventory_tpl.php';
			case 'vaccine_inventory_save':
				return $this->saveVaccineInventoryData();
			case 'deworming_add':
				return 'deworming_tpl.php';
			case 'deworming_save':
				return $this->saveDewormingData();
				
            case 'view_reports':
            
            default:
                return $this->nextAction('select_officer');
            	
        }
    }
    
    /**
     * Method to unset the passive surveillance session variables
     *
     */
    private function unsetPassiveSession() {
        $this->unsetSession('ps_oStatusId');
        $this->unsetSession('ps_qualityId');
        $this->unsetSession('ps_datePrepared');
        $this->unsetSession('ps_dateIBAR');
        $this->unsetSession('ps_dateReceived');
        $this->unsetSession('ps_dateIsReported');
        $this->unsetSession('ps_refNo');
        $this->unsetSession('ps_remarks');
        $this->unsetSession('ps_dateVet');
        $this->unsetSession('ps_dateOccurence');
        $this->unsetSession('ps_dateDiagnosis');
        $this->unsetSession('ps_dateInvestigation');
        $this->unsetSession('ps_locationId');
        $this->unsetSession('ps_longitude');
        $this->unsetSession('ps_latitude');
        $this->unsetSession('ps_diseaseId');
        $this->unsetSession('ps_causativeId');
        $this->unsetSession('ps_productionId');
        $this->unsetSession('ps_ageId');
        $this->unsetSession('ps_sexId');
        $this->unsetSession('ps_speciesId');
        $this->unsetSession('ps_controlId');
        $this->unsetSession('ps_basisId');
        $this->unsetSession('ps_cases');
        $this->unsetSession('ps_susceptible');
        $this->unsetSession('ps_deaths');
        $this->unsetSession('ps_vaccinated');
        $this->unsetSession('ps_slaughtered');
        $this->unsetSession('ps_destroyed');
        $this->unsetSession('ps_newcases');
        $this->unsetSession('ps_production');
        $this->unsetSession('ps_recovered');
        $this->unsetSession('ps_prophylactic');
    }
    
    
    /**
     * Method to unset the active surveillance session variables
     *
     */
    private function unsetActiveSession() {
    
        $this->unsetSession('ps_officerId');
        $this->unsetSession('ps_disease');
        $this->unsetSession('ps_comments');
        $this->unsetSession('ps_surveyTypeId');
        $this->unsetSession('ps_campName');
        
       }
	   private function AddData()
    {
        return 'add_data.php';
    }
	
	
	private function SaveData()
	{
	//capture input		
		$district = $this->getParam('district');
		$classification = $this->getParam('classification');
		$num_animals = $this->getParam('num_animals');
		$animal_production = $this->getParam('animal_production');
		$source = $this->getParam('source');		
		$data= $this->objAnimalPopulation ->addData($district, $classification, $num_animals, $animal_production,$source);
		
		return $this->nextAction('');
	
	}
	
	private function saveInspectionData()
	{
	//capture input		
		$district = $this->getParam('district');
		$date =$this->getParam('inspectiondate');
		$num_of_cases = $this->getParam('num_of_cases');
		$num_at_risk = $this->getParam('num_at_risk');
				
		$data= $this->objMeatInspect->addMeatInspectionData($district, $date, $num_of_cases, $num_at_risk);
		
		return $this->nextAction('');
	
	}
	private function saveSlaughterData()
	{
	//capture input		
		$district = $this->getParam('district');
		$num_cattle =$this->getParam('num_cattle');
		$num_sheep = $this->getParam('num_sheep');
		$num_goats = $this->getParam('num_goats');
		$num_pigs = $this->getParam('num_pigs');
		$num_poultry = $this->getParam('num_poultry');
		$other = $this->getParam('other');
		$name = $this->getParam('name');
		$remarks = $this->getParam('remarks');					
		$data= $this->objSlaughter->addSlaughterData($district, $num_cattle, $num_sheep, $num_goats,$num_pigs,$num_poultry,$other,$name,$remarks);
		
		return $this->nextAction('');
	
	}
	
	//function to add animal movement data
	private function saveAnimalMovementData()
	{
		$district = $this->getParam('district');
		$classification = $this->getParam('classification');
		$purpose = $this->getParam('purpose');
		$origin = $this->getParam('origin');
		$destination = $this->getParam('destination');
		$remarks = $this->getParam('remarks');
		
		/*$error = '';
		if(empty($district))
		{
			$error .= $this->objLanguage->languageText('mod_ahis_districterror','ahis');
		}
		if(empty($classification))
		{
			$error .= $this->objLanguage->languageText('mod_ahis_classificationerror','ahis');
		}
		if(empty($purpose))
		{
			$error .= $this->objLanguage->languageText('mod_ahis_purposeerror','ahis');
		}
		if(empty($origin))
		{
			$error .= $this->objLanguage->languageText('mod_ahis_orginerror','ahis');
		}
		if(empty($destination))
		{
			$error .= $this->objLanguage->languageText('mod_ahis_destinationerror','ahis');
		}
		if(empty($remarks))
		{
			$error .= $this->objLanguage->languageText('mod_ahis_remarkserror','ahis');
		}*/
		//echo $error;
		
		$data = $this->objAnimalmovement->addAnimalMovementData($district,$classification,$purpose,$origin,$destination,$remarks);  
							
		return $this->nextAction('');
	}
	
	//function to add livestock import data
	private function saveLivestockimportData()
	{
		$district = $this->getParam('district');
		$entrypoint = $this->getParam('entrypoint');
		$destination = $this->getParam('destination');
		$classification = $this->getParam('classification');
		$origin = $this->getParam('origin');
		$eggs = $this->getParam('eggs');
		$milk = $this->getParam('milk');
		$cheese = $this->getParam('cheese');
		$poultry = $this->getParam('poultry');
		$beef = $this->getParam('beef');
		
		/*$error = '';
		if(empty($district))
		{
			$error .= $this->objLanguage->languageText('mod_ahis_districterror','ahis');
		}
		if(empty($entrypoint))
		{
			$error .= $this->ojLanguage->languageText('mod_ahis_entrypointerror','ahis');
		}
		if(empty($destination))
		{
			$error .= $this->objLanguage->languageText('mod_ahis_destinationerror','ahis');
		}
		if(empty($origin))
		{
			$error .= $this->objLanguage->languageText('mod_ahis_originerror','ahis');
		}
		if(empty($eggs))
		{
			$error .= $this->objLanguage->languageText('mod_ahis_eggserror','ahis');
		}
		if(empty($milk))
		{
			$error .= $this->objLanguage->languageText('mod_ahis_milkerror','ahis');
		}
		if(empty($cheese))
		{
			$error .= $this->objLanguage->languageText('mod_ahis_cheeseerror','ahis');
		}
		if(empty($poultry))
		{
			$error .= $this->objLanguage->languageText('mod_ahis_poultryerror','ahis');
		}
		if(empty($beef))
		{
			$error .= $this->objLanguage->languageText('mod_ahis_beeferror','ahis');
		}*/
		
		$data= $this->objLivestockimport->addLivestockimportData($district,$entrypoint,$destination,$classification,$origin,$eggs,$milk,$cheese,$poultry,$beef);
							
		return $this->nextAction('');
	}
	
	//function to add livestock export data
	private function saveLivestockexportData()
	{
		$district = $this->getParam('district');
		$entrypoint = $this->getParam('entrypoint');
		$destination = $this->getParam('destination');
		$classification = $this->getParam('classification');
		$origin = $this->getParam('origin');
		$eggs = $this->getParam('eggs');
		$milk = $this->getParam('milk');
		$cheese = $this->getParam('cheese');
		$poultry = $this->getParam('poultry');
		$beef = $this->getParam('beef');
		
		/*$error = '';
		if(empty($district))
		{
			$error .= $this->objLanguage->languageText('mod_ahis_districterror','ahis');
		}
		if(empty($entrypoint))
		{
			$error .= $this->ojLanguage->languageText('mod_ahis_entrypointerror','ahis');
		}
		if(empty($destination))
		{
			$error .= $this->objLanguage->languageText('mod_ahis_destinationerror','ahis');
		}
		if(empty($origin))
		{
			$error .= $this->objLanguage->languageText('mod_ahis_originerror','ahis');
		}
		if(empty($eggs))
		{
			$error .= $this->objLanguage->languageText('mod_ahis_eggserror','ahis');
		}
		if(empty($milk))
		{
			$error .= $this->objLanguage->languageText('mod_ahis_milkerror','ahis');
		}
		if(empty($cheese))
		{
			$error .= $this->objLanguage->languageText('mod_ahis_cheeseerror','ahis');
		}
		if(empty($poultry))
		{
			$error .= $this->objLanguage->languageText('mod_ahis_poultryerror','ahis');
		}
		if(empty($beef))
		{
			$error .= $this->objLanguage->languageText('mod_ahis_beeferror','ahis');
		}*/
		
		$data= $this->objLivestockexport->addLivestockexportData($district,$entrypoint,$destination,$classification,$origin,$eggs,$milk,$cheese,$poultry,$beef);
														
		return $this->nextAction('');
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
