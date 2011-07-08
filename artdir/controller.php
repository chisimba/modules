<?php
/**
 * artdir controller class
 *
 * Class to control the artdir module
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
 * @category  chisimba
 * @package   artdir
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2011 Paul Scott
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za
 */

// security check - must be included in all scripts
if (!
/**
 * Description for $GLOBALS
 * @global unknown $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
 * artdir controller class
 *
 * Class to control the artdir module.
 *
 * @category  Chisimba
 * @package   artdir
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2011 Paul Scott
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za
 */
class artdir extends controller
{
    public $objLanguage;
    public $objSysConfig;
    public $objUser;
    public $objConfig;
    
    /**
     * Object of terms dialogue class in the blog module.
     *
     * @access protected
     * @var object $objTermsDialogue
     */
    protected $objTermsDialogue;

    /**
     * Initialises the instance variables.
     *
     * @access public
     */
    public function init()
    {
        try {
            $this->objLanguage      = $this->getObject ( 'language', 'language' );
            $this->objConfig        = $this->getObject('altconfig', 'config');
            $this->objSysConfig     = $this->getObject ( 'dbsysconfig', 'sysconfig' );
            $this->objUser          = $this->getObject('user', 'security');
            $this->objModuleCat     = $this->getObject('modules', 'modulecatalogue');
            $this->objFoaf          = $this->getObject('foafcreator', 'foaf');
            $this->objFoafParser    = $this->getObject('foafparser', 'foaf');
            $this->objFoafOps       = $this->getObject('foafops', 'foaf');
            $this->dbFoaf           = $this->getObject('dbfoaf', 'foaf');
            $this->objTermsDialogue = $this->getObject('artdirtermsdialogue');
            $this->objCats          = $this->getObject('artdircategories');
            $this->objDbArtdir      = $this->getObject('dbartdir');
        }
        catch ( customException $e ) {
            customException::cleanUp ();
            exit ();
        }
    }

    /**
     * Standard dispatch method
     *
     * @access public
     * @param void
     * @return void
     */
    public function dispatch() {
        $action = $this->getParam ( 'action' );
        switch ($action) {
            case NULL:
                $userid = $this->objUser->userId();
                $this->setVarByRef('userid', $userid);
                //create the basic foaf profile from tbl_users
                $this->objFoafOps->newPerson($this->objUser->userId());
                //add in other details if they exist
                $this->objFoafOps->myFoaf($this->objUser->userId());
                $this->objFoafOps->writeFoaf();
                $midcontent = $this->objFoafOps->foaf2Object($this->objUser->userId());
                
                return 'default_tpl.php';
                break;
                
            case 'catadd':
                if ($this->objUser->isLoggedIn() == FALSE) {
                    //not logged in - send to default action
                    $this->nextAction('');
                    exit;
                }
                //check the mode and cat name as wel as user id
                $mode = $this->getParam('mode');
                $list = $this->getParam('catname');
                $userid = $this->objUser->userId();
                $catname = $this->getParam('catname');
                $catparent = $this->getParam('catparent');
                $catdesc = $this->getParam('catdesc');
                $id = $this->getParam('id');
                //category quick add
                if ($mode == 'quickadd') {
                    if (empty($list)) {
                        $this->nextAction('');
                        break;
                    }
                    $this->objblogCategories->quickCatAdd($list, $userid);
                    $this->nextAction('');

                    break;
                }
                if ($mode == 'edit') {
                    //update the records in the db
                    //build the array again
                    $entry = $this->objDbArtdir->getCatForEdit($userid, $id);
                    $catarr = array(
                            'userid' => $userid,
                            'cat_name' => $entry['cat_name'],
                            'cat_nicename' => $entry['cat_nicename'],
                            'cat_desc' => $entry['cat_desc'],
                            'cat_parent' => $entry['cat_parent'],
                            'id' => $id
                    );
                    //display the cat editor with the values in the array, set that form to editcommit
                    $this->setVarByRef('catarr', $catarr);
                    $this->setVarByRef('userid', $userid);
                    $this->setVarByRef('catid', $id);
                    return 'cedit_tpl.php';
                    break;
                }
                if ($mode == 'editcommit') {
                    $catarr = array(
                            'userid' => $userid,
                            'cat_name' => $catname,
                            'cat_nicename' => $catname,
                            'cat_desc' => $catdesc,
                            'cat_parent' => $catparent,
                            'id' => $id
                    );
                    $this->objDbArtdir->setCats($userid, $catarr, $mode);
                    $this->nextAction('artdiradmin', array(
                            'mode' => 'editcats'
                    ));
                }
                if ($mode == NULL) {
                    $catarr = array(
                            'userid' => $userid,
                            'cat_name' => $catname,
                            'cat_nicename' => $catname,
                            'cat_desc' => $catdesc,
                            'cat_parent' => $catparent
                    );
                    //insert the category into the db
                    $this->objDbArtdir->setCats($userid, $catarr);
                    $this->nextAction('artdiradmin', array(
                            'mode' => 'editcats'
                    ));
                    break;
                }
                break;
                
            case 'artdiradmin':
                //make sure the user is logged in
                if ($this->objUser->isLoggedIn() == FALSE) {
                    //bail to the default page
                    $this->nextAction('');
                    //exit this action
                    exit;
                }
                //get the user id
                $userid = $this->objUser->userId();
                $this->setVarByRef('userid', $userid);
                // Check if the user is allowed to use the dir
                if (!($this->approvedArtist())) {
                    return 'not_approved_tpl.php';
                    exit;
                }
                // Check to see if the user needs to accept terms and conditions before being able to blog.
                $terms = $this->objSysConfig->getValue('mod_artdir_terms', 'artdir');
                if ($terms) {
                    $acceptedBlogTerms = $this->objUserParams->getValue('accepted_artdir_terms');
                    if (!$acceptedBlogTerms) {
                        $dialogueContent = file_get_contents($terms);
                        $this->objTermsDialogue->setContent($dialogueContent);
                    }
                }
                //check the mode
                $mode = $this->getParam('mode');
                switch ($mode) {
                    //return a specific template for the chosen mode

                    case 'writepost':
                        return 'writepost_tpl.php';
                        break;

                    case 'editpost':
                    //$this->setPageTemplate(NULL);
                        //$this->setLayoutTemplate("block_2layout_tpl.php");
                        return 'editpost_tpl.php';
                        break;

                    case 'editcats':
                        return 'editcats_tpl.php';
                        break;

                    case 'acceptterms':
                        $value = $this->objUserParams->setItem('accepted_blog_terms', 1);
                        $data = array('value' => $value);
                        $json = json_encode($data);
                        $this->setContentType('application/json');
                        echo $json;
                        return;
                }
                // return the default template for no mode set
                return 'blogadminmenu_tpl.php';
                break;
                
            case 'showsignin' :
                return 'signin_tpl.php';
                break;
                
            case 'search' :
                $term = $this->getParam('search');
                echo $term;
                break;

                
            default:
                $this->nextAction('');
                break;
        }
    }

    /**
     * Method to turn off login for selected actions
     *
     * @access public
     * @param string $action Action being run
     * @return boolean Whether the action requires the user to be logged in or not
     */
    function requiresLogin($action='') {
        $allowedActions = array('', 'search', 'showsignin', NULL);

        if (in_array($action, $allowedActions)) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * Check if user is allowed to use the system
     * @returns boolean
     */
    public function approvedArtist() {
        $artdirSetting = $this->objSysConfig->getValue('limited_users', 'artdir');
        if ($artdirSetting) {
            $groupId = $this->objGroup->getId('Artists');
            $userId = $this->objUser->userid();
            return $this->objGroup->isGroupMember($userId, $groupId);
        } else {
            return TRUE;
        }
    }
}
?>
