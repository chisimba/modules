<?php
/**
 * ahis Other Control Measures  Class
 *
 * file housing Other Control Measures Class
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
 * @version   $Id: other_control_measures_class_inc.php
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

class other_control_measures extends dbtable
{

    public function init()
    {
        parent::init('tbl_ahis_other_control_measures');
        
       
    }
    
    public function addData($code,$measure,$abbrev,$desc,$datecreated,$createdby,$sdate,$edate)
    {
		$data = $this->insert(array(
			'control_measure_code' =>  stripslashes($code),
			'control_measure' =>  stripslashes($measure),
			'abbreviation' =>stripslashes($abbrev),
			'description' => stripslashes($desc),
			'start_date' => $sdate,
			'end_date' => $edate,
			'date_created' => $datecreated,
			'created_by' => $createdby			
			));
			if($data)
			return true;
			else
			return false;
			
	} 
   public function getMeasures($id){	
	$sql="SELECT * FROM tbl_ahis_other_control_measures where id='$id' "; 
	$data=$this->getArray($sql);
	return $data;	
	}
}
?>