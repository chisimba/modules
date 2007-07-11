<?php

/**
 * Short description for file
 * 
 * Long description (if any) ...
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
 * @author    Administrative User <pscott@uwc.ac.za>
 * @copyright 2007 Administrative User
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   CVS: $Id$
 * @link      http://avoir.uwc.ac.za
 * @see       References to other sections (if any)...
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
 * A block to return the last blog entry
 *
 * @author Paul Scott based on a block by Derek Keats
 *         
 *         $Id$
 *         
 */
class block_latest extends object
{
    /**
     * @var string $title The title of the block
     */
    public $title;
    /**
     * @var object $objLastBlog String to hold the lastblog object
     */
    public $objLastBlog;
    /**
     * @var quickBlog
     *                Object to display the quick blog box
     */
    public $quickBlog;
    /**
     * Blog operations class
     *
     * @var object
     */
    public $blogOps;

    /**
     * Description for public
     * @var    object
     * @access public
     */
    public $objLanguage;
    /**
     * Standard init function to instantiate language and user objects
     * and create title
     */
    public function init() 
    {
        $this->objLanguage = &$this->getObject('language', 'language');
        $this->objUser = &$this->getObject('user', 'security');
        $userid = $this->objUser->userid();
        $this->blogOps = &$this->getObject('blogops', 'blog');
        $this->quickBlog = $this->blogOps->quickPost($userid, FALSE);
        $this->objLastBlog = NULL; //& $this->getObject('getlastentry', 'blog');
        $this->title = $this->objLanguage->languageText("mod_blog_block_quickpost", "blog");
    }
    /**
     * Standard block show method. It builds the output based
     * on data obtained via the getlast class
     */
    public function show() 
    {
        return $this->quickBlog;
    }
}
?>