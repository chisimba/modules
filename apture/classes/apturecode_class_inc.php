<?php
/**
 *
 * Apture plugin for Chisimba
 *
 *  Apture is a service that allows publishers and bloggers to link and 
 *  incorporate multimedia into a dynamic layer above their pages. Apture 
 *  is currently being used by several large organizations and publishers 
 *  including The Washington Post, The San Francisco Chronicle, O'Reilly 
 *  Radar, and the World Wide Fund for Nature as well as individual 
 *  bloggers. Plugin are available for a number of platforms, including
 *  now Chisimba.
 *  
 *  @See http://www.apture.com
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
 * @package   apture
 * @author    Derek Keats derek.keats@wits.ac.za
 * @copyright 2007 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id: twitterremote_class_inc.php 13770 2009-06-26 14:02:07Z paulscott $
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
* Apture plugin for Chisimba
* 
*  Apture is a service that allows publishers and bloggers to link and 
*  incorporate multimedia into a dynamic layer above their pages. Apture 
*  is currently being used by several large organizations and publishers 
*  including The Washington Post, The San Francisco Chronicle, O'Reilly 
*  Radar, and the World Wide Fund for Nature as well as individual 
*  bloggers. Plugin are available for a number of platforms, including
*  now Chisimba.
*  
*  @See http://www.apture.com
*
* @author Derek Keats
* @package apture
*
*/
class apturecode extends object
{
    /**
    *
    * @var string $userName The apture username of the user
    * @access public
    *
    */
    public $userName='';
    public $aptureToken;
    private $_uid;
    
    /**
    *
    * Constructor for the twitterremo$aptureTokente class
    * @access public
    * @return VOID
    *
    */
    public function init()
    {
        $objGuess = $this->getObject('bestguess', 'utilities');
        $this->_uid = $objGuess->guessUserId();
    }
    
    /**
    * 
    * Method to return the Apture Script 
    * 
    * @param $token The string value of the apture token
    * @return string The apture script for insertion 
    * @access public
    *
    */
    public function getAptureScript($token)
    {
        return '<script id="aptureScript" '
          . 'type="text/javascript" '
          . 'src="http://www.apture.com/js/apture.js?siteToken='
          . $token .'" charset="utf-8">'
          . '</script>';
    }
    
    
    
    /**
    * 
    * Method to get the Apture token from the user identified 
    * by $this->_uid
    * 
    */
    private function getAptureToken()
    {
        if ($this->_uid) {
        	if ($this->hasAptureToken($this->_uid)) {
        	    return $this->_aptureToken($this->uid);
        	} else {
        	    return NULL;
        	}
        } else {
            return NULL;
        }
    }
    

    /**
    *
    * Method to determing if the user has a apture token specified
    * in userparams.
    *
    * @access public
    * @return boolean TRUE|FALSE
    *
    */
    public function hasAptureToken($userName)
    {
        $objUserParams = $this->getObject("dbuserparamsadmin","userparamsadmin");
        $objUserParams->readConfig();
        $aptureToken = $objUserParams->getValue("apturetoken");
        if ($aptureToken == NULL) {
            return FALSE;
        } else {
            $this->aptureToken = $aptureToken;
            return TRUE;
        }
    }
}
?>