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
            //$objGa = $this->getObject('gamodel','groupadmin');
            
            $objGroups = $this->getObject('groupadminmodel', 'groupadmin');
            $groupId = $objGroups->getId("Usermanagers");
            $objGroupOps = $this->getObject("groupops", "groupadmin");
            $userId = $this->objUser->userId();
            if ($this->objUser->isAdmin() || 
              $objGroupOps->isGroupMember($groupId, $userId )) {
                  $linkText = $this->objLanguage->languageText(
                    'mod_oeruserdata_adminreg', 'oeruserdata');
                    $ret .= "<br /><br />" 
                    . $this->putLink($linkText, 'adduser');
            }
        } else {
            // Put a registration link
            $linkText = $this->objLanguage->languageText(
              'mod_oeruserdata_selfreg', 'oeruserdata');
            $ret = $this->putLink($linkText, 'selfregister');
            
        }
        
        return $ret;
    }
    
    /**
     *
     * Add a link to register or add user
     * 
     * @param string $linkText The text to display
     * @param string $linkType The type of link (selfregister, or adduser)
     * @return string The rendered link 
     * @access private
     * 
     */
    private function putLink($linkText, $linkType)
    {
        // Put a registration link
        $uri = $this->uri(array(
          'action' => $linkType), 
          'oeruserdata');
        $link = new link($uri);
        $link->link = $linkText;
        return $link->show();
    }
    
    /**
     *
     * Show a paginated list of users
     * 
     * @return string A rendered list with edit/delete links
     * @access public
     * 
     */
    public function showUserList($addDiv=TRUE)
    {
        $start = $this->getParam('start', 1);
        $objDb = $this->getObject('dboeruserdata', 'oeruserdata');
        $rs = $objDb->getForListing($start, 10);
        $ret = '';
        $this->loadClass('htmltable','htmlelements');
        $table = $this->newObject('htmltable', 'htmlelements');
        // Edit icon
        $edIcon = $this->newObject('geticon', 'htmlelements');
        $edIcon->setIcon('edit');
        $editIcon = $edIcon->show();
        unset($edIcon);
        // Delete icon.
        $delIcon = $this->newObject('geticon', 'htmlelements');
        $delIcon->setIcon('delete');
        $deleteIcon = $delIcon->show();
        unset($delIcon);
        // Next icon.
        $nextIcon = $this->newObject('geticon', 'htmlelements');
        $nextIcon->setIcon('next');
        $next = $nextIcon->show();
        // Next icon greyed.
        $nextIcon = $this->newObject('geticon', 'htmlelements');
        $nextIcon->setIcon('next_grey');
        $nextGr = $nextIcon->show();
        unset($nextIcon);
        // Previous icon.
        $prIcon = $this->newObject('geticon', 'htmlelements');
        $prIcon->setIcon('prev');
        $prev = $prIcon->show();
        // Previous icon greyed.
        $prIcon = $this->newObject('geticon', 'htmlelements');
        $prIcon->setIcon('prev_grey');
        $prev = $prIcon->show();
        unset($prIcon);
        // Display the records.
        if (!empty($rs)) {
            foreach($rs as $record) {
                $edUrl = $this->uri(array(
                  'action' => 'edituser', 
                  'id' => $record['id'], 
                  'mode' => 'edit'), 'oeruserdata');
                $link = new link($edUrl);
                $link->link = $editIcon;
                $delUrl = 'javascript:void(0);';
                $delLink = new link($delUrl);
                $delLink->cssId = $record['id'];
                $delLink->cssClass = "dellink";
                $delLink->link = $deleteIcon;
                $table->startRow();
                $table->addCell($record['title']);
                $table->addCell($record['firstname']);
                $table->addCell($record['surname']);
                $table->addCell($record['username']);
                $table->addCell($link->show() . ' ' . $delLink->show());
                $table->endRow();
            }
        }
        $ret = $table->show();
        return $ret;
    }
    
}
?>