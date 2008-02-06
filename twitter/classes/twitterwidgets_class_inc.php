<?php
/**
 *
 * Twitter widgets
 *
 * Twitter is a module that creates an integration between your Chisimba 
 * site using your Twitter account. This class provides twitter wigets for
 * Chisimba
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
 * @package   twitter
 * @author    Derek Keats dkeats@uwc.ac.za
 * @copyright 2007 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   CVS: $Id$
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
* Database accesss class for Chisimba for the module twitter
*
* @author Derek Keats
* @package twitter
*
*/
class twitterwidgets extends object
{
    /**
    *
    * @var $url The URL to which to submit the form 
    * @access public
    * 
    */
    public $url;
    /**
    * 
    * @var string $objLanguage String object property for holding the 
    * language object
    * @access public
    * 
    */
    public $objLanguage;
    
    /**
    *
    * Constructor for the twitterremote class
    * @access public
    * @return VOID
    *
    */
    public function init()
    {
        $this->url = $this->uri(array(
          "action" => "sendtweet"), "twitter");
        $this->objLanguage = $this->getObject('language', 'language');
    }
    
    public function sendWidget()
    {
        if ($this->hasTwitterLogon()) {
    	   $ret = "<form action=\"" . $this->url . "\" method=\"post\">"
              . "<table><tr><td>"
              . $this->objLanguage->languageText("mod_twitter_entertext", "twitter")
              . "</td><td><div id=\"charlimitinfo\">140</div></td></tr></table>"
              . "<textarea name=\"tweet\" id=\"tweet\" cols=\"22\" rows=\"8\">"
              . "</textarea><br />"
              . "<input type=\"submit\" value=\"Tweet\" />"
              . "</form>";
        } else {
        	$ret = $this->objLanguage->languageText("mod_twitter_nologon", "twitter");
        }
        return $ret;
    }
    
    function hasTwitterLogon()
    {
    	$objUserParams = $this->getObject("dbuserparamsadmin","userparamsadmin");
        $objUserParams->readConfig();
        $userName = $objUserParams->getValue("twittername");
        $password = $objUserParams->getValue("twitterpassword");
        if ($userName == "" && $password=="") {
        	return FALSE;
        } else {
        	return TRUE;
        }
    }
 

}
?>
