<?php
/**
 * 
 * countrylanguages class
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
 * @author    Joseph Gatheru
 * @copyright 2009 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id: 
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


 class countrylanguages extends dbtable{
 /**
     * Standard Chisimba init method
     * 
     * @return void  
     * @access public
     */
	public function init() {
		try {
			parent::init('tbl_ahis_countrylanguages');
			$this->objUser = $this->getObject('user', 'security');
		}
		catch (customException $e)
		{
			customException::cleanUp();
			exit;
		}
	}
	
	
	public function addCountryCurrency($country,$language,$startdate,$enddate)
    {
		
			$sql = $this->insert(array(
			'country' => $country,
			'language' => $language,
			'startdate' => $startdate,
			'enddate' => $enddate,
			'createdon' => date('Y-m-d H:i:s',time()),
			'createdby' => $this->objUser->userId(),
			'modifiedon' => date('Y-m-d H:i:s',time()),
			'modifiedby' => $this->objUser->userid()
			));
			if($sql)
			return true;
			else
			return false;
			
	} 
	
 }
 


?>