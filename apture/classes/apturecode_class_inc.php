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
    
    /**
    *
    * @var string $aptureToken The apture token of the user
    * @access public
    *
    */
    public $aptureToken;
    
    /**
    *
    * @var string $_uid THe user name of the best guess user
    * @access public
    *
    */
    private $_uid;
    
    /**
    *
    * Constructor for the aptureToken class
    * @access public
    * @return VOID
    *
    */
    public function init()
    {
        // This enables the thing to work as a blog plugin.
        $objGuess = $this->getObject('bestguess', 'utilities');
        $un = $objGuess->guessUserName();
        if ($un) {
            $this->_uid = $un;
        }
        $this->mod = $objGuess->identifyModule();
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
    public function getAptureScript()
    {
        // Which modules should it work with?
        $permittedModules=array('blog');
        if (in_array($this->mod, $permittedModules)) {
            $token = $this->getAptureToken();
            if ($token) {
                return '<script id="aptureScript" '
                  . 'type="text/javascript" '
                  . 'src="http://www.apture.com/js/apture.js?siteToken='
                  . $token .'" charset="utf-8">'
                  . '</script>';
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }
    
    
    
    /**
    * 
    * Method to get the Apture token from the user identified 
    * by $this->_uid
    * 
    * @return string Token or NULL if no token found
    * @access private
    * 
    */
    private function getAptureToken()
    {
        
        if ($this->_uid) {
        	if ($this->hasAptureToken($this->_uid)) {
        	    return $this->aptureToken;
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
        $objUserParams->setUid($userName);
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