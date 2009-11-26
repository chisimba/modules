<?php
/**
 * ahis Country Class
 *
 * file housing Country Class
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
 * @author    Samuel Onyach <sonyach@icsit.jkuat.ac.ke,onyachsamuel@yahoo.com>
 * @copyright 2009 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id: country_class_inc.php
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

class country extends dbtable
{

    public function init()
    {
        parent::init('tbl_ahis_countries');
        
       
    }
    
    public function addData($isocountrycode, $common_name, $official_name, $default_lang, $default_currency,$countryidd,$northlat,$southlat,$westlong,$eastlong,$area,$unit_of_area,$date_created,$createdby)
    {
		$data = $this->insert(array(
			'iso_country_code' => stripslashes($isocountrycode),
			'common_name' =>  stripslashes($common_name),
			'official_name' =>stripslashes($official_name),
			'default_language' => stripslashes( $default_lang),
			'default_currency' => stripslashes( $default_currency),
			'country_idd' => $countryidd,
			'north_latitude' => $northlat,
			'south_latitude' => $southlat,
			'west_longitude' => $westlong,
			'east_longitude' => $eastlong,
			'area' => $area,
			'unit_of_area_id' => stripslashes($unit_of_area),
			'date_created' => $date_created,
			'created_by' => $creadtedby
			));
			if($data)
			return true;
			else
			return false;
			
	} 
   public function getCountry($id){	
	$sql="SELECT * FROM tbl_ahis_countries where id='$id' "; 
	$data=$this->getArray($sql);
	return $data;	
	}
}
?>