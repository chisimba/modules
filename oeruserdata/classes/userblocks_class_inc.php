<?php
/**
 *
 * User blocks functionality for oeruserdata module
 *
 * User blocks functionality for oeruserdata module provides 
 * blocks that give access to various user related functionality.
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
 * @package   oer
 * @author    Derek Keats derek@dkeats.com
 * @author    David Wafula
 * @copyright 2007 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   0.001
 * @link      http://www.chisimba.com
 *
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
 * User blocks functionality for oeruserdata module
 *
 * User blocks functionality for oeruserdata module provides 
 * blocks that give access to various user related functionality.
*
* @package   oer
* @author    Derek Keats derek@dkeats.com
*
*/
class userblocks extends object
{

    public $objLanguage;
    public $objUser;

    /**
    *
    * Intialiser for insitution editor UI builder class. It instantiates
    * language object and loads the required classes.
    * 
    * @access public
    * @return VOID
    *
    */
    public function init()
    {
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objUser = $this->getObject('user', 'security');
    }

    /**
     *
     * Render the input form for the user data.
     *
     * @return string The rendered form
     * @access public
     * 
     */
    public function showEditMyDetails()
    {
        $this->loadClass('link', 'htmlelements');
        if ($this->objUser->isLoggedIn()) {
            // Put a link for them to edit their own data.
            $id = $this->objUser->PKId();
            $uri = $this->uri(array(
              'action' => 'edituser', 'id' => $id, 'mode' => 'edit'), 
              'oeruserdata');
            $link = new link($uri);
            $link->link = $this->objLanguage->languageText(
              'mod_oeruserdata_edityou', 'oeruserdata');
            $ret = $link->show();
            
            // Put a register link for admins
            $objGa = $this->getObject('gamodel','groupadmin');
            $userId = $this->objUser->userId();
            $edGroup = $objGa->isGroupMember($userId, "Usermanagers");
//-=-=
            if ($this->objUser->isAdmin()|| $edGroup ) {
                $linkText = $this->objLanguage->languageText(
                  'mod_oeruserdata_adminreg', 'oeruserdata');
                   $ret .= "<br /><br />" . $this->putRegLink($linkText);
            }
        } else {
            // Put a registration link
            $linkText = $this->objLanguage->languageText(
              'mod_oeruserdata_selfreg', 'oeruserdata');
            $ret = $this->putRegLink($linkText);
            
        }
        
        return $ret;
    }
    
    public function putRegLink($linkText)
    {
        // Put a registration link
        $uri = $this->uri(array(
          'action' => 'selfregister'), 
          'oeruserdata');
        $link = new link($uri);
        $link->link = $linkText;
        return $link->show();
    }
    
    public function showUserList()
    {
        return "WORKING HERE";
    }
    
}
?>