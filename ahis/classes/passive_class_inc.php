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
 * ahis passive Class
 * 
 * Class to access passive surveillance reports in the DB
 * 
 * @category  Chisimba
 * @package   ahis
 * @author    Nic Appleby <nappleby@uwc.ac.za>
 * @copyright 2009 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
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
	public function nextRefNo() {
		/*$sql = "SELECT MAX(refno) AS refno
				FROM tbl_ahis_passive_surveillance";
		$result = $this->getArray($sql);
		return (empty($result[0]))? 1000 : $result[0]['refno'] + 1;*/
		return rand(100000,999999).substr(mktime(), 7, 3);
		
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