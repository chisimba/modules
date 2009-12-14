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
 * ahis disease report Class
 * 
 * Class to access disease reports in the DB
 * 
 * @category  Chisimba
 * @package   ahis
 * @author    Nic Appleby <nappleby@uwc.ac.za>
 * @copyright 2009 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id: passive_class_inc.php 13884 2009-07-08 14:32:28Z nic $
 * @link      http://avoir.uwc.ac.za
 */
class diseasereport extends dbtable {
	
    /**
     * Standard Chisimba init method
     * 
     * @return void  
     * @access public
     */
	public function init() {
		try {
			parent::init('tbl_ahis_diseasereport');
			$this->objCountry 	= $this->getObject('country');
			$this->objUser 		= $this->getObject('user', 'security');
			$this->objDisease	= $this->getObject('diseases');
			$this->objPartition = $this->getObject('partitions');
			
			$this->objPartitionLevel 	= $this->getObject('partitionlevel');
			$this->objPartitionCategory = $this->getObject('partitioncategory');
			
			
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
	public function genOutbreakCode($countryId, $diseaseId, $year) {
		$year 	 = date('y',mktime(1,1,1,1,1,$year));
		$country = $this->objCountry->getRow('id', $countryId);
		$disease = $this->objDisease->getRow('id', $diseaseId);
		$count = $this->getRecordCount("WHERE countryid = '$countryId' AND diseaseid = '$diseaseId'");
		$count++;
		switch ($count) {
			case $count < 10:
				$count = '00'.$count;
				break;
			case $count > 9 && $count < 100:
				$count = '0'.$count;
				break;
		}
		return $country['iso_country_code'].$disease['disease_code'].$count.$year;
		
	}
	
	public function getOutbreaks() {
		$outbreaks = $this->getAll("ORDER BY outbreakcode");
		$array = array();
		foreach ($outbreaks as $outbreak) {
			$partition = $this->objPartition->getRow('id', $outbreak['partitionid']);
			$partitionLevel = $this->objPartitionLevel->getRow('id', $partition['partitionlevelid']);
			$partitionType = $this->objPartitionCategory->getRow('id', $partitionLevel['partitioncategoryid']);
			$array[] = array('outbreakCode'=>$outbreak['outbreakcode'],
							 'partitionType'=>$partitionType['partitioncategory'],
							 'partitionLevel'=>$partitionLevel['partitionlevel'],
							 'partitionName'=>$partition['partitionname'],
							 'month'=>date('F', strtotime($outbreak['reportdate'])),
							 'year'=>date('Y', strtotime($outbreak['reportdate'])));
		}
		return $array;
	}
	
}