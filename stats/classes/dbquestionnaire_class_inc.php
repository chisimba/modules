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
 * dbquestionnaire class
 * 
 * Class to connnect to tbl_stats_questionnaire
 * 
 * @category  Chisimba
 * @package   stats
 * @author    Nic Appleby <nappleby@uwc.ac.za>
 * @copyright 2008 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za
 */

class dbquestionnaire extends dbtable {
    
    /**
    * Init method
    * 
    * Standard Chisimba Init() method
    * 
    * @return void  
    * @access public
    */
    public function init() {
        parent::init("tbl_stats_questionnaires");    
    }
    
    /**
     * Method to check if a student has completed the questionnaire
     *
     * @param string $userId The userId of the student
     * @return 1|0 whether or not the student has completed it
     * @access public
     */
    public function checkStudent($userId) {
        
       return ($this->valueExists('studentno',$userId))? 1 : 0; 
    }
    
}

?>