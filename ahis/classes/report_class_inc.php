<?php
/**
 * ahis report Class
 *
 * File containing the report generation class
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
// security check - must be included in all scripts
if (!
/**
 * Description for $GLOBALS
 * @global entry point $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check


/**
 * ahis report Class
 * 
 * view class to generate ahis reports
 * 
 * @category  Chisimba
 * @package   ahis
 * @author    Nic Appleby <nappleby@uwc.ac.za>
 * @copyright 2009 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za
 */
class report extends object {
	
    /**
     * Standard Chisimba init method
     * 
     * @return void  
     * @access public
     */
	public function init() {
		try {
			$this->objPassive = $this->getObject('passive');
			$this->objLanguage = $this->getObject('language', 'language');
			$this->objGeo3 = $this->getObject('geolevel3');
			$this->objGeo2 = $this->getObject('geolevel2');
			$this->objTerritory = $this->getObject('territory');
			$this->objUser = $this->getObject('user', 'security');
			$this->objAge = $this->getObject('age');
			$this->objSex = $this->getObject('sex');
            $this->objOutbreak = $this->getObject('outbreak');
            $this->objDiagnosis = $this->getObject('diagnosis');
            $this->objControl = $this->getObject('control');
			$this->objProduction = $this->getObject('production');
            $this->objOutbreak = $this->getObject('outbreak');
            
		}
		catch (customException $e)
		{
			customException::cleanUp();
			exit;
		}
	}
	
	public function generateReport($year, $month, $reportType, $static = FALSE) {
		
		$objTable = $this->newObject('htmltable', 'htmlelements');
		
		switch ($reportType) {
			case 'init_01': 			//passive surveillance
				
				//$objTable->width = '3500px';
				$objTable->cellspacing = '2px';
				if ($static) {	
					$objTable->border = '1px';
				}
				
				$objTable->cssClass = "stat";
				$headerArray = array($this->objLanguage->languageText('phrase_geolevel3'),$this->objLanguage->languageText('phrase_outbreakref'),
									 $this->objLanguage->languageText('mod_ahis_reportofficer','ahis'),$this->objLanguage->languageText('word_disease'),
									 $this->objLanguage->languageText('mod_ahis_isreporteddate','ahis'),$this->objLanguage->languageText('mod_ahis_vetdate','ahis'),
									 $this->objLanguage->languageText('mod_ahis_investigationdate','ahis'),$this->objLanguage->languageText('mod_ahis_diagnosisdate','ahis'),
									 $this->objLanguage->languageText('word_location'),$this->objLanguage->languageText('word_latitude'),$this->objLanguage->languageText('word_longitude'),
									 $this->objLanguage->languageText('word_species'),$this->objLanguage->languageText('word_age'),$this->objLanguage->languageText('word_sex'),
									 $this->objLanguage->languageText('word_production'),$this->objLanguage->languageText('phrase_control'),
									 $this->objLanguage->languageText('phrase_diagnosis'),$this->objLanguage->languageText('word_susceptible'),
									 $this->objLanguage->languageText('phrase_newcases'),$this->objLanguage->languageText('word_deaths'),$this->objLanguage->languageText('word_slaughtered'),
									 $this->objLanguage->languageText('word_recovered'),$this->objLanguage->languageText('word_destroyed'),$this->objLanguage->languageText('phrase_outbreak'),
									 $this->objLanguage->languageText('word_vaccinated'),$this->objLanguage->languageText('word_prophylactic'),
									 $this->objLanguage->languageText('mod_ahis_vacsource','ahis'),$this->objLanguage->languageText('mod_ahis_batch','ahis'),
									 $this->objLanguage->languageText('mod_ahis_manufacturedate','ahis'),$this->objLanguage->languageText('mod_ahis_expiredate','ahis'));
				$objTable->addHeader($headerArray);
				
				$passiveRecords = $this->objPassive->getALL("WHERE YEAR(reportdate) = '$year'
														   AND MONTH(reportdate) = '$month'
														   ORDER BY reportdate");
				$class = 'odd';
				foreach ($passiveRecords as $report) {
					$objTable->startRow($class);
					
					$territory = $this->objTerritory->getRow('id', $report['territoryid']);
					$geo2 = $this->objGeo2->getRow('id', $territory['geo2id']);
					$geo3 = $this->objGeo3->getRow('id', $geo2['geo3id']);
					$objTable->addCell($geo3['name']);
					
					$objTable->addCell($report['refno']);
					$objTable->addCell($this->objUser->fullname($report['reporterid']));
					$objTable->addCell($report['disease']);
					$objTable->addCell($report['reporteddate']);
					$objTable->addCell($report['vetdate']);
					$objTable->addCell($report['investigationdate']);
					$objTable->addCell($report['diagnosisdate']);
					$objTable->addCell($report['location']);
					$objTable->addCell($report['latitude']);
					$objTable->addCell($report['longitude']);
					
					$objTable->addCell('');
					
					$age = $this->objAge->getRow('id', $report['ageid']);
					$objTable->addCell($age['name']);
					
					$sex = $this->objSex->getRow('id',$report['sexid']);
					$objTable->addCell($sex['name']);
					
					$production = $this->objProduction->getRow('id', $report['productionid']);
					$objTable->addCell($production['name']);
					
					$control = $this->objControl->getRow('id', $report['controlmeasureid']);
					$objTable->addCell($control['name']);
					
					$basis = $this->objDiagnosis->getRow('id', $report['basisofdiagnosisid']);
					$objTable->addCell($basis['name']);
					
					$objTable->addCell($report['susceptible']);
					$objTable->addCell($report['newcases']);
					$objTable->addCell($report['deaths']);
					$objTable->addCell($report['slaughtered']);
					$objTable->addCell($report['recovered']);
					$objTable->addCell($report['destroyed']);
					
					$status = $this->objOutbreak->getRow('id', $report['statusid']);
					$objTable->addCell($status['name']);
					
					$objTable->addCell($report['vaccinated']);
					$objTable->addCell($report['prophylactic']);
					$objTable->addCell($report['vaccinesource']);
					$objTable->addCell($report['vaccinebatch']);
					$objTable->addCell($report['vaccinemanufacturedate']);
					$objTable->addCell($report['vaccineexpirydate']);
					
					$objTable->endRow();
					$class = ($class == 'even')? 'odd' : 'even';
				}
				break;
				
		}
		$css = "<style>
					table.stat th, table.stat td {
					    font-size : 8px;
					    font-family : 'Myriad Web',Verdana,Helvetica,Arial,sans-serif;
						line-height: 1.5em;
                    }
                </style>";
				
		return "<br />$css".$objTable->show();
	}
	
	public function generateCSV($year, $month, $reportType) {
		switch ($reportType) {
			case 'init_01': 			//passive surveillance
				
				$headerArray = array($this->objLanguage->languageText('phrase_geolevel3'),$this->objLanguage->languageText('phrase_outbreakref'),
									 $this->objLanguage->languageText('mod_ahis_reportofficer','ahis'),$this->objLanguage->languageText('word_disease'),
									 $this->objLanguage->languageText('mod_ahis_isreporteddate','ahis'),$this->objLanguage->languageText('mod_ahis_vetdate','ahis'),
									 $this->objLanguage->languageText('mod_ahis_investigationdate','ahis'),$this->objLanguage->languageText('mod_ahis_diagnosisdate','ahis'),
									 $this->objLanguage->languageText('word_location'),$this->objLanguage->languageText('word_latitude'),$this->objLanguage->languageText('word_longitude'),
									 $this->objLanguage->languageText('word_species'),$this->objLanguage->languageText('word_age'),$this->objLanguage->languageText('word_sex'),
									 $this->objLanguage->languageText('word_production'),$this->objLanguage->languageText('phrase_control'),
									 $this->objLanguage->languageText('phrase_diagnosis'),$this->objLanguage->languageText('word_susceptible'),
									 $this->objLanguage->languageText('phrase_newcases'),$this->objLanguage->languageText('word_deaths'),$this->objLanguage->languageText('word_slaughtered'),
									 $this->objLanguage->languageText('word_recovered'),$this->objLanguage->languageText('word_destroyed'),$this->objLanguage->languageText('phrase_outbreak'),
									 $this->objLanguage->languageText('word_vaccinated'),$this->objLanguage->languageText('word_prophylactic'),
									 $this->objLanguage->languageText('mod_ahis_vacsource','ahis'),$this->objLanguage->languageText('mod_ahis_batch','ahis'),
									 $this->objLanguage->languageText('mod_ahis_manufacturedate','ahis'),$this->objLanguage->languageText('mod_ahis_expiredate','ahis'));
				
				$passiveRecords = $this->objPassive->getALL("WHERE YEAR(reportdate) = '$year'
														   AND MONTH(reportdate) = '$month'
														   ORDER BY reportdate");
				$csv = implode(",", $headerArray)."\n";
				
				foreach ($passiveRecords as $report) {
										
					$territory = $this->objTerritory->getRow('id', $report['territoryid']);
					$geo2 = $this->objGeo2->getRow('id', $territory['geo2id']);
					$geo3 = $this->objGeo3->getRow('id', $geo2['geo3id']);
					$age = $this->objAge->getRow('id', $report['ageid']);
					$sex = $this->objSex->getRow('id',$report['sexid']);
					$production = $this->objProduction->getRow('id', $report['productionid']);
					$control = $this->objControl->getRow('id', $report['controlmeasureid']);
					$basis = $this->objDiagnosis->getRow('id', $report['basisofdiagnosisid']);
					$status = $this->objOutbreak->getRow('id', $report['statusid']);
					
					$row = array($geo3['name'], $report['refno'], $this->objUser->fullname($report['reporterid']),
								$report['disease'], $report['reporteddate'], $report['vetdate'],
								$report['investigationdate'], $report['diagnosisdate'], $report['location'],
								$report['latitude'], $report['longitude'], '', $age['name'], $sex['name'],
								$production['name'], $control['name'], $basis['name'], $report['susceptible'],
								$report['newcases'], $report['deaths'], $report['slaughtered'], $report['recovered'],
								$report['destroyed'], $status['name'], $report['vaccinated'], $report['prophylactic'],
								$report['vaccinesource'], $report['vaccinebatch'], $report['vaccinemanufacturedate'],
								$report['vaccineexpirydate']);
					
					$csv .= implode(",", $row)."\n";
				}
				return $csv;
				
		}
		
		return "<br />".$objTable->show();
	}
}