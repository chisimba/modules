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
 * A block to return the last 10 blog posts
 *
 * @author    Megan Watson
 * @version   0.1
 * @copyright (c) UWC 2007
 *            
 */
class block_lastten extends object
{
    /**
     * @var string $title The title of the block
     */
    public $title;
    /**
     * @var display
     *              Object to display the last ten posts box
     */
    public $display;
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
        $this->blogOps = &$this->getObject('blogops', 'blog');
        $this->display = $this->blogOps->showLastTenPosts();
        $this->title = $this->objLanguage->languageText("mod_blog_block_latestblogs", "blog");
    }
    /**
     * Standard block show method.
     */
    public function show() 
    {
        return $this->display;
    }
}
?>