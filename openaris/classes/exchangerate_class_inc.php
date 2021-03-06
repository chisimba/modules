<?php
/**
 * ahis exchange rate Class
 *
 * exchange rate class
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
 * @author    Isaac Oteyo<ioteyo@icsit.jkuat.ac.ke>
 * @copyright 2009 AVOIR-JKUAT
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id: exchangerate_class_inc.php 12627 2009-09-27 14:29:10Z ioteyo$
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
 * ahis exchangerate Class
 * 
 * Class to access exchange rate DB
 * 
 * @category  Chisimba
 * @package   ahis
 * @author    Isaac Oteyo <ioteyo@icsit.jkuat.ac.ke>
 * @copyright 2009 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id: exchangerate_class_inc.php 
 * @link      http://avoir.uwc.ac.za
 */
 
 class exchangerate extends dbtable{
 /**
     * Standard Chisimba init method
     * 
     * @return void  
     * @access public
     */
	public function init() {
		try {
			parent::init('tbl_ahis_exchangerate');
		}
		catch (customException $e)
		{
			customException::cleanUp();
			exit;
		}
	}
	
	public function addExchangeRateData($default_currency,$exchange_currency,$dateStartPicker,$dateEndPicker,$dateCreatedPicker,$createdby,$dateModifiedPicker,$modifiedby)
    {
		
			$sql = $this->insert(array(
			'defaultcurrencyid' => stripslashes($default_currency),
			'exchangecurrencyid' => stripslashes($exchange_currency),
			'startdate' => stripslashes($dateStartPicker),
			'enddate' => stripslashes($dateEndPicker),
			'datecreated' => stripslashes($dateCreatedPicker),
			'createdby' => stripslashes($createdby),
			'datemodified' => stripslashes($dateModifiedPicker),
			'modifiedby' => stripslashes($modifiedby)
			));//echo $sql;
			if($sql)
			return true;
			else
			return false;
			
	} 
	
	public function getExchangerates($level,$searchStr,$parent){
	if($level!=01)
	  $level="0".$level;
	if(empty($parent))
	{
	  $sql="SELECT tbl_ahis_exchangerate where id='$id'";
	}
	//echo $sql;
	return $this->getArray($sql);	
	}
	
	
	
 }


?>