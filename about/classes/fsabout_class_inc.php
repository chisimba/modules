<?php
/**
 *
 * File access for About
 *
 * File access for About. Accesses the About file for this site.
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
 * @package   about
 * @author    Derek Keats derek@dkeats.com
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
 * File access for About
 *
 * File access for About. Accesses the About file for this site.
*
* @package   about
* @author    Derek Keats derek@dkeats.com
*
*/
class fsabout extends dbtable
{

    /**
    *
    * Intialiser for the about database connector
    * @access public
    * @return VOID
    *
    */
    public function init()
    {
    }
    
    /**
     *
     * Get the contents of the default.html file from
     * the about directory in usrfiles. Format it for 
     * display.
     * 
     * @return string The rendered contents
     * @access public
     * 
     */
    public function getContentsForDisplay()
    {
        $strContent = $this->readContents();
        // Parse for filters
        $objWashOut = $this->getObject('washout', 'utilities');
        $strContent = $objWashOut->parseText($strContent);
        $canEdit = $this->checkEditRights();
        // Add the edit icon and link.
        if ($canEdit) {
            $ed = $this->getEditIcon();
        } else {
            $ed=NULL;
        }
        return $strContent . " " . $ed;
    }
    
    /**
     *
     * Get the contents of the about file for editing
     * 
     * @return string the file contents
     * @access public
     * 
     */
    public function getContentsForEdit()
    {
        $this->loadClass('form','htmlelements');
        //Set up the form processor
        $paramArray=array(
            'action'=>'save');
        $formAction=$this->uri($paramArray);
        $objForm = new form('about_text');
        $objForm->setAction($formAction);
        $objForm->displayType=3; 
        $strContent = $this->readContents();
        $editor =  $this->newObject('htmlarea', 'htmlelements');
        $editor->name = 'about';
        $editor->setContent($strContent);
        $objForm->addToForm($editor->show());
        return $objForm->show();
    }
    
    /**
     * 
     * Save the edited contents back to the file
     * @access public
     * @return VOID
     * 
     */
    public function save()
    {
        if ($this->checkEditRights()) {
            $about = $this->getParam('about', NULL);
            $objAltConfig = $this->getObject('altconfig', 'config');
            $targetPath = $objAltConfig->getSiteRootPath() . 'usrfiles/about/default.html';
            $strContent = file_put_contents($targetPath, $about);
        }
    }
    
    /**
     *
     * Create the linked edit icon
     * 
     * @return string The rendered icon
     * @access private
     * 
     */
    private function getEditIcon()
    {
        $this->loadClass('link', 'htmlelements');
        $uri = $this->uri(array('action' => 'edit'), 'about');
        $link = new link($uri);
        // Edit icon
        $edIcon = $this->newObject('geticon', 'htmlelements');
        $edIcon->setIcon('edit');
        $link->link = $edIcon->show();
        unset($edIcon);
        $ret = $link->show();
        return $ret;
    }

    /**
     *
     * Get the raw contents of the default.html file from
     * the about directory in usrfiles. 
     * 
     * @return string The raw contents
     * @access public
     * 
     */
    public function readContents()
    {
        $objAltConfig = $this->getObject('altconfig', 'config');
        $targetPath = $objAltConfig->getSiteRootPath() . 'usrfiles/about/default.html';
        $strContent = file_get_contents($targetPath);
        return $strContent;
    }
    
    public function checkEditRights()
    {
        $ret=FALSE;
        $objUser = $this->getObject('user', 'security');
        $userId = $objUser->userId();
        // Admins can edit.
        $objGa = $this->getObject('gamodel','groupadmin');
        $edGroup = $objGa->isGroupMember($userId, "AboutContact");
        if ($objUser->isLoggedIn()) {
            if ($objUser->isAdmin() || $edGroup ) {
                $ret = TRUE;
            }
        }
        return $ret;
    }

}
?>