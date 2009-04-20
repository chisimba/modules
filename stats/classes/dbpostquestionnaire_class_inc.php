<?php
/**
 * Stats tutorials on Chisimba
 * 
 * database model class for stats module
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
 * @package   stats
 * @author    Nic Appleby <nappleby@uwc.ac.za>
 * @copyright 2008 AVOIR
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
 * dbpostquestionnaire class
 * 
 * Class to connnect to tbl_stats_postquestionnaire
 * 
 * @category  Chisimba
 * @package   stats
 * @author    Nic Appleby <nappleby@uwc.ac.za>
 * @copyright 2008 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za
 */

class dbpostquestionnaire extends dbtable {

    /**
    * Init method
    * 
    * Standard Chisimba Init() method
    * 
    * @return void  
    * @access public
    */
    public function init() {
        parent::init("tbl_stats_postquestionnaire");
    }
    
    /**
     * Method to remove all data from the table, done at
     * the start of a new year
     *
     * @return void
     * @access public
     */
    public function removeAll() {
        $all = $this->getAll();
        foreach ($all as $each) {
            $this->delete('id',$each['id']);
        }
        
    }
    
    /**
     * Method to export the table contents to csv
     * for viewing in spreadsheet software
     *
     * @return string csv formatted table contents
     * @access public
     */
    public function export() {
        $all = $this->getAll("ORDER BY studentno");
        $first = current($all);
        $data = 'Full Name';
        
        foreach ($first as $key => $value) {
            if ($key != 'id' && $key != 'puid') {
                $data .= ",$key";
            }
        }
        $data .= "\n";
        
        foreach ($all as $one) {
            $userId = $this->objUser->getUserId($one['studentno']);
            $line = $this->objUser->fullName($userId);
            foreach ($one as $key => $field) {
                if ($key != 'id' && $key != 'puid') {
                    $line .= ",".$field;
                }
            }
            $data .= "$line\n";
        }
        return $data;
    }
    
}

?>