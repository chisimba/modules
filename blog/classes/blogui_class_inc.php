<?php

/**
 * Blog UI elements file
 * 
 * This file controls the blog UI elements. It wil allow users to configure the look of their blog
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
 * @package   blog
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2007 Paul Scott
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   CVS: $Id$
 * @link      http://avoir.uwc.ac.za
 * @see       References to other sections (if any)...
 */

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check


/**
 * class to control blog ui elements
 * 
 * This class controls the blog UI elements. It wil allow users to configure the look of their blog
 * 
 * @category  Chisimba
 * @package   blog
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2007 Paul Scott
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   CVS: $Id$
 * @link      http://avoir.uwc.ac.za
 * @see       References to other sections (if any)...
 */
class blogui extends object
{

    /**
     * Blog operations object
     * @var    object
     * @access public
     */
    public $objblogOps;

    /**
     * left Column layout
     * @var    object
     * @access public 
     */
    public $leftCol;

    /**
     * Right column layout
     * @var    object
     * @access public 
     */
    public $rightCol;

    /**
     * middle column layout
     * @var    object
     * @access public 
     */
    public $middleCol;

    /**
     * Template header
     * @var    object
     * @access public 
     */
    public $tplHeader;

    /**
     * CSS Layout
     * @var    object
     * @access public
     */
    public $cssLayout;

    /**
     * Left user menu
     * @var    object
     * @access public 
     */
    public $leftMenu;

    /**
     * user object
     * @var    object
     * @access public
     */
    public $objUser;

    /**
     * Standard init function
     * 
     * Initialises and constructs the object via the framework
     * 
     * @return void  
     * @access public
     */
    public function init() 
    {
        // load up the blogops class
        $this->objblogOps = $this->getObject('blogops');
        // user class
        $this->objUser = $this->getObject('user', 'security');
        // load up the htmlelements
        $this->loadClass('href', 'htmlelements');
        // get the css layout object
        $this->cssLayout = $this->newObject('csslayout', 'htmlelements');
        // get the sidebar object
        $this->leftMenu = $this->newObject('usermenu', 'toolbar');
        // initialise the columns
        // left column
        $this->leftCol = NULL;
        // right column
        $this->rightCol = NULL;
        // middle column
        $this->middleCol = NULL;
    }

    /**
     * reads config
     * 
     * Loads up and reads the config for a particular user to get the layout sequence
     * 
     * @return void  
     * @access public
     */
    public function myblog() 
    {
    }

    /**
     * three col layout
     * 
     * Creates a 3 column css layout
     * 
     * @return object CSS layout template header
     * @access public 
     */
    public function threeCols() 
    {
        // Set columns to 3
        $this->cssLayout->setNumColumns(3);
        $this->tplHeader = $this->cssLayout;
        return $this->tplHeader;
    }

    /**
     * Left blocks
     * 
     * Blocks that will show up in the left hand column
     * 
     * @param  integer $userid The User id
     * @return string  Return string
     * @access public 
     */
    public function leftBlocks($userid) 
    {
        $leftMenu = $this->newObject('usermenu', 'toolbar');
        // init the variable
        $leftCol = NULL;
        // get all the left column blocks
        if ($this->objUser->isLoggedIn()) {
            $guestid = $this->objUser->userId();
            if ($guestid == $userid) {
                $leftCol.= $leftMenu->show();
                $leftCol.= $this->objblogOps->showProfile($userid);
            } else {
                $leftCol.= $this->objblogOps->showFullProfile($userid);
            }
            $leftCol.= $this->objblogOps->showAdminSection(TRUE);
        } else {
            $leftCol = $this->objblogOps->loginBox(TRUE);
            $leftCol.= $this->objblogOps->showFullProfile($userid);
        }
        //show the feeds section
        $leftCol.= $this->objblogOps->showFeeds($userid, TRUE);
        $leftCol.= $this->objblogOps->showBlinks($userid, TRUE);
        $leftCol.= $this->objblogOps->showBroll($userid, TRUE);
        $leftCol.= $this->objblogOps->showPages($userid, TRUE);
        return $leftCol;
    }

    /**
     * Right side blocks
     * 
     * CSS layout for the right hand side blocks
     * 
     * @param  unknown $userid The user id
     * @param  unknown $cats   categories
     * @return string  string of blocks
     * @access public 
     */
    public function rightBlocks($userid, $cats = NULL) 
    {
        $rightSideColumn = NULL;
        $rightSideColumn.= $this->objblogOps->showBlogsLink(TRUE);
        $rightSideColumn.= $this->objblogOps->archiveBox($userid, TRUE);
        $rightSideColumn.= $this->objblogOps->blogTagCloud($userid);
        $rightSideColumn.= $this->objblogOps->showCatsMenu($cats, TRUE, $userid);
        $rightSideColumn.= $this->objblogOps->searchBox();
        if ($this->objUser->isLoggedIn()) {
            $rightSideColumn.= $this->objblogOps->quickCats(TRUE);
            //$rightSideColumn .= $this->objblogOps->quickPost($userid, TRUE);
            
        }
        return $rightSideColumn;
    }
}
?>