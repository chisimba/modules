<?php
/**
 * Administrators can enter a list of IP addresses which can 
 * be used to identify local users.
 * 
 * Administrators can enter a list of IP addresses which can be used 
 * to identify local users in order to provide the possibility to 
 * redirect content to a local source for local users, or an online 
 * source for external users. The purpose of this is to save local 
 * bandwidth, but it can also have local security implications if 
 * developers want to use it that way.
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
 * @package   detectlocalip
 * @author    zDerek Keats derek@dkeats.com
 * @copyright 2007 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   CVS: $Id: db_MODULECODE.php,v 1.1 2007-11-25 09:13:27 dkeats Exp $
 * @link      http://avoir.uwc.ac.za
 */

// security check - must be included in all scripts
if (!
/**
 * The $GLOBALS is an array used to control access to certain constants.
 * Here it is used to check if the file is opening in engine, if not it
 * stops the file from running.
 *
 * @global entry point $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 *
 */
$GLOBALS['kewl_entry_point_run'])
{
        die("You cannot view this page directly");
}
// end security check

/**
*
* Accesss class for local ip address data. The class can retrieve and save
* a list of local IP addresses
*
* @author Derek Keats
* @package deteectlocalip
*
*/
class dbipaddresses extends object
{
    
    /**
    * 
    * The path to the data file holding the IP addresses
    * that are considered local.
    * 
    * @access public
    *  
    */
    public $dataFilePath;

    /**
    *
    * Intialiser for the dbipaddress class
    * @access public
    *
    */
    public function init()
    {
        // Identify the file containing the IP address list
        $this->objConfig = $this->getObject('altconfig', 'config');
        $this->dataFilePath = $this->objConfig->getcontentBasePath() . 'detectlocalip/local-ip-list.txt';
        if (!file_exists($this->dataFilePath)) {
            touch($this->dataFilePath);
            $ipTextList = "127.0.0, 192.168.10, 192.168.1";
            $this->saveData($ipTextList);
        }
    }
    
    /**
    * 
    * Method to read the saved local IP addresses
    * 
    * @access Public
    * @return VOID
    * 
    */
    public function readData()
    {
        // Read the contents of the file.
        $ipTextList = file_get_contents($this->dataFilePath);
        return $ipTextList;
    }
    
    /**
    * 
    * Write a string of IP addresses to a text file
    * 
    * @access public
    * @return VOID
    *  
    */
    public function saveData($ipTextList)
    {
        file_put_contents($this->dataFilePath, $ipTextList);
    }
    
    /**
    * 
    * Build an array of IP addresses from the comna delimited
    * string in the form x.y.z
    * 
    * @access public
    * @return string Array An array of ipAddresses 
    * 
    */
    private function __buildArray(& $ipTextList)
    {
        return explode(",", $ipTextList);
    }
    /**
    * 
    * Determine if a given user is on a local IP address. If so, return
    * TRUE, otherwise return FALSE. If TRUE, then cache it in the session
    * to avoid having to read the file more than once per session
    * 
    * @access Public
    * @returrn boolean TRUE|FALSE
    *  
    */
    public function isLocal()
    {
        $isLocalIp = NULL; //$this->getSession("ISLOCALIP", NULL);
        if (!$isLocalIp) {
            $myIp = $this->__prepareIp();
            $ipTextList = $this->readData();
            $srLocalIps = $this->__buildArray($ipTextList);
            if (in_array($myIp, $srLocalIps)) {
                $ret = TRUE;
            } else {
                $ret = FALSE;   
            }
            //$this->setSession("ISLOCALIP", $ret);
            
        } else {
            $ret = TRUE;
        } 
        return $ret;
    }
    
    /**
    * 
    * Remove the last number from the IP address so it is
    * of the form x.y.z
    * 
    * @access private
    * @return string The trimmed IP address
    * 
    */
    private function __prepareIp()
    {
         $myIp = $_SERVER['REMOTE_ADDR'];
         $myIpArray = explode(".", $myIp);
         unset($myIp);
         return  $myIpArray[0] . "." . $myIpArray[1] . "." . $myIpArray[2]; 
    }

}
?>
