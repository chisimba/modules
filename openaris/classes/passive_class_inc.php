<?php
/**
 * ahis passive Class
 *
 * passive class
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
 * @version   $Id: passive_class_inc.php 13884 2009-07-08 14:32:28Z nic $
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
 * ahis passive Class
 * 
 * Class to access passive surveillance reports in the DB
 * 
 * @category  Chisimba
 * @package   ahis
 * @author    Nic Appleby <nappleby@uwc.ac.za>
 * @copyright 2009 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id: passive_class_inc.php 13884 2009-07-08 14:32:28Z nic $
 * @link      http://avoir.uwc.ac.za
 */
class passive extends dbtable {
	
    /**
     * Standard Chisimba init method
     * 
     * @return void  
     * @access public
     */
	public function init() {
		try {
			parent::init('tbl_ahis_passive_surveillance');
			$this->objSpecies = $this->getObject('species');
			$this->objTerritory = $this->getObject('territory');
			$this->objDisease = $this->getObject('disease');
			$this->objGeo2 = $this->getObject('geolevel2');
			$this->objGeo3 = $this->getObject('geolevel3');
			$this->objUser = $this->getObject('user', 'security');
			
		}
		catch (customException $e)
		{
			customException::cleanUp();
			exit;
		}
	}
	
	/**
	 * Method to return the next outbreak reference number
	 *
	 * @return int Reference no.
	 */
	public function nextRefNo($javaRosa = FALSE) {
		/*$sql = "SELECT MAX(refno) AS refno
				FROM tbl_ahis_passive_surveillance";
		$result = $this->getArray($sql);
		return (empty($result[0]))? 1000 : $result[0]['refno'] + 1;*/
		return ($javaRosa)? 'J'.rand(100000,999999).substr(mktime(), 7, 3) :
							rand(1000000,9999999).substr(mktime(), 7, 3);
		
	}
	
	/**
	 * Method to insert data submitted in xml
	 * via a JavaRosa client into the database
	 *
	 * @param string xmlString the xml containing the data
	 * @return int 0 for success or error code
	 */
	public function insertDataFromJavaRosa($xmlString) {
		if (!$xml = simplexml_load_string($xmlString)) {
			return FALSE;
		} else {
			$officerName = (string)$xml->reporting_officer;
			$geo2Name = (string)$xml->geo2;
			$locationName = (string)$xml->location;
			
			$officer = $this->objUser->getAll("WHERE CONCAT_WS(' ', firstname, surname) LIKE '$officerName'");
			if (empty($officer)) return 1;
			$geo2 = $this->objGeo2->getRow('name', $geo2Name);
			if (!isset($geo2)) return 2;
			$location = $this->objTerritory->getRow('name', $locationName);
			if (!isset($location)) return 3;
			
			$ps_array['reporterid'] = $officer[0]['userid'];
            $ps_array['geo2id'] = $geo2['id'];
            $ps_array['reportdate'] = (string)$xml->report_date;
            $ps_array['refno'] = $this->nextRefNo(TRUE);
            
            $ps_array['statusid'] = (string)$xml->outbreak_status;
            $ps_array['prepareddate'] = (string)$xml->prepared_date;
            $ps_array['ibardate'] = (string)$xml->ibar_date;
            $ps_array['dvsdate'] = (string)$xml->dvs_date;
            $ps_array['reporteddate'] = (string)$xml->is_date;
            $ps_array['qualityid'] = (string)$xml->tested_for_quality;
            $ps_array['remarks'] = (string)$xml->remarks;
            
            $ps_array['vetdate'] = (string)$xml->vet_date;
            $ps_array['occurencedate'] = (string)$xml->occurence_date;
            $ps_array['diagnosisdate'] = (string)$xml->diagnosis_date;
            $ps_array['investigationdate'] = (string)$xml->investigation_date;
            $ps_array['latdeg'] = (integer)$xml->latitude->degrees;
            $ps_array['latmin'] = (float)$xml->latitude->minutes;
            $ps_array['latdirec'] = (string)$xml->latitude->direction;
            $ps_array['longdeg'] = (integer)$xml->longitude->degrees;
            $ps_array['longmin'] = (float)$xml->longitude->minutes;
            $ps_array['longdirec'] = (string)$xml->longitude->direction;
            
            $ps_array['locationid'] = $location['id'];
            $ps_array['diseaseid'] = (string)$xml->disease;
            $ps_array['causativeid'] = (string)$xml->causative;
            $ps_array['speciesid'] = (string)$xml->species;
            $ps_array['ageid'] = (string)$xml->age_group;
            $ps_array['sexid'] = (string)$xml->sex;
            $ps_array['productionid'] = (string)$xml->production_type;
            $ps_array['controlmeasureid'] = (string)$xml->control_measure;
            $ps_array['basisofdiagnosisid'] = (string)$xml->basis_of_diagnosis;
            
            $ps_array['susceptible'] = (integer)$xml->susceptible;
            $ps_array['cases'] = (integer)$xml->cases;
            $ps_array['deaths'] = (integer)$xml->deaths;
            $ps_array['vaccinated'] = (integer)$xml->vaccinated;
            $ps_array['slaughtered'] = (integer)$xml->slaughtered;
            $ps_array['destroyed'] = (integer)$xml->destroyed;
            $ps_array['production'] = (integer)$xml->production;
            $ps_array['newcases'] = (integer)$xml->new_cases;
            $ps_array['recovered'] = (integer)$xml->recovered;
            $ps_array['prophylactic'] = (integer)$xml->prophylactic;
            
            $ps_array['vaccinemanufacturedate'] = (string)$xml->vaccine_manufacture_date;
            $ps_array['vaccineexpirydate'] = (string)$xml->vaccine_expire_date;
            $ps_array['vaccinesource'] = (string)$xml->vaccine_source;
            $ps_array['vaccinebatch'] = (string)$xml->vaccine_batch;
            $ps_array['vaccinetested'] = (integer)$xml->panvac_tested;
            
            return ($this->insert($ps_array))? 0 : 4;
		}
	}
	
	/**
	 * Method to return all passive surveillance data in JSON
	 * format to be used in the Google Earth GIS plugin
	 *
	 * @return JSON array of all data
	 * */
	public function getJSONData() {
		$allData = $this->getAll("ORDER BY reportdate DESC");
		$count = 0;
		$results = array();
		foreach ($allData as $row) {
			
			$geo2 = $this->objGeo2->getRow('id', $row['geo2id']);
			
			$latitude = $row['latdeg'] + ($row['latmin']/60);
			if ($row['latdirec'] == "S") {
				$latitude *= -1;
			}
			$longitude = $row['longdeg'] + ($row['longmin']/60);
			if ($row['longdirec'] == "W") {
				$longitude *= -1;
			}
			//log_debug("GIS: $latitude  $longitude {$row['latdirec']}{$row['longdirec']}");
			$results[] = array(
				'row' 			=> "$count",
				'refno'			=> $row['refno'],
				'lat'			=> $latitude,
				'long'			=> $longitude,
				'geolayer3'		=> $this->objGeo3->getName($geo2['geo3id']),
				'geolayer2'		=> $geo2['name'],
				'year'			=> date('Y', strtotime($row['reportdate'])),
				'month'			=> date('n', strtotime($row['reportdate'])),
				'animal'		=> $this->objSpecies->getName($row['speciesid']),
				'diseasetype'	=> $this->objDisease->getName($row['diseaseid']),
				'period'		=> 'Unknown',
				'locationname'	=> $this->objTerritory->getName($row['locationid']),
				'outbreakstart'	=> $row['occurencedate'],
				'poultryatrisk'	=> $row['susceptible'],
				'cases'			=> $row['cases'],
				'deaths'		=> $row['deaths'],
				'destroyed'		=> $row['destroyed'],
				'slaughtered'	=> $row['slaughtered'],
				'culled'		=> 'Unknown',
				'vaccinated'	=> $row['vaccinated'],
				'reportdate'	=> $row['reportdate'],
				'source'		=> 'Open ARIS'
			);
			$count++;
		}
		return json_encode(array('results'=>$results));
	}
}