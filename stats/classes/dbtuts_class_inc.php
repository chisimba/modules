<?php
/**
 * Stats tutorials on Chisimba
 * 
 * database model class for stats module tutorials
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
 * dbtuts class
 * 
 * Class to connnect to tbl_stats_tuts
 * 
 * @category  Chisimba
 * @package   stats
 * @author    Nic Appleby <nappleby@uwc.ac.za>
 * @copyright 2008 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za
 */

class dbtuts extends dbtable {

    /**
     * User object 
     * @var object holds user details
     * @access public
     */
    public $objUser;
    
    /**
     * Config object 
     * @var object manipulate system configuration
     * @access public
     */
    public $objConfig;
    

    /**
    * Init method
    * 
    * Standard Chisimba Init() method
    * 
    * @return void  
    * @access public
    */
    public function init() {
        parent::init("tbl_stats_tuts");
        $this->objUser = $this->getObject('user','security');
        $this->objConfig = $this->getObject('altconfig','config');
    }
    
    /**
     * Method to save the result of a students tutorial
     *
     * @param string $userId The userId of the student
     * @param string $password The sha-1 hash of the password
     * @param string $test The test (tutorial) number
     * @param string $mark The students mark, as a percentage
     * @param string $time The time taken
     * @access public
     */
    public function saveMark($userId, $password, $test, $mark, $time) {
        $user = $this->objUser->getUserDetails($userId);
        if ($password == $user['pass']) {
            $this->insert(array('studentno'=>$userId, 'testno'=>$test, 'mark'=>$mark, 'time'=>$time));
        }
    }
    
    /**
     * Method to create a nonce and save it so that the framework
     * can be sure the user actually took the tutorial and did
     * not attempt to inject the mark
     *
     * @param string $user the userid
     * @return string the nonce
     * @access public
     */
    public function createNonce($user) {
        $nonce = sha1(mt_rand(1000000000000,999999999999));
        $fname = $this->objConfig->getModulePath()."/stats/resources/sec/.".sha1($user);
        $fp = fopen($fname,"wb");
        fwrite($fp,$nonce);
        fclose($fp);
        chmod($fname,"0700");
        return $nonce;
    }
    
    /**
     * Method for the framework to read the nonce
     *
     * @param string $user the userid
     * @return string the nonce
     * @access public
     */
    public function readNonce($user) {
        $filename = $this->objConfig->getModulePath()."/stats/resources/sec/.".sha1($user);
        chmod($filename,"0400");
        $nonce = file_get_contents($filename);
        chmod($filename,"0700");
        return $nonce;
    }
    
}

?>