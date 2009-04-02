<?php
/**
 * ahis sampledetails Class
 *
 * file housing sampledetails class
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
 * @dewormingreport   ahis
 * @author    Joseph Gatheru<jgatheru@icsit.jkuat.ac.ke>
 * @copyright 2009 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id: sampledetails_class_inc.php 12186 2009-01-21 14:55:17Z nic $
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
    die("You cannot view this psampledetails directly");
}
// end security check


/**
 * ahis deworming  Class
 * 
 * class to connect to sampledetails table
 * 
 * @category  Chisimba
 * @deworming   ahis
 * @author    Joseph Gatheru<jgatheru@icsit.jkuat.ac.ke>
 * @copyright 2009 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id: sampledetails_class_inc.php 
 * @link      http://avoir.uwc.ac.za
 */
class deworming extends dbtable {
	
    /**
     * Standard Chisimba init method
     * 
     * @return void  
     * @access public
     */
	public function init() {
		try {
			parent::init('tbl_ahis_deworming');
		}
		catch (customException $e)
		{
			customException::cleanUp();
			exit;
		}
	}
	
	public function getdewormedanimals(){
	
	$sql="SELECT * FROM tbl_ahis_deworming";
	     
	return $this->getArray($sql);
	
	
	}
} 
