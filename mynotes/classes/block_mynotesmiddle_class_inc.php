<?php
/**
 *
 * A middle block for My notes.
 *
 * A middle block for My notes. Take notes, organize them by tags, keep them private or share them with your friends, all user, or the world..
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
 * @version    0.001
 * @package    mynotes
 * @author     Derek Keats derek@localhost.local
 * @copyright  2011 AVOIR
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @link       http://www.chisimba.com
 * 
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
 * 
 * A middle block for My notes.
 *
 * A middle block for My notes. Take notes, organize them by tags, keep them private or share them with your friends, all user, or the world..
 *
 * @category  Chisimba
 * @author    Derek Keats derek@localhost.local
 * @version   0.001
 * @copyright 2011 AVOIR
 *
 */
class block_mynotesmiddle extends object
{
    /**
     * The title of the block
     *
     * @var    object
     * @access public
     */
    public $title;
    
    /**
     *
     * @var string Object $objUser String for the user object
     * @access public
     *
     */
    public $objUser;
    
    /**
     * Standard init function
     *
     * Create title
     *
     * @return NULL
     */
    public function init() 
    {
        $this->title = "My notes wideblock";
        // Instantiate the user object.
        $this->objUser = $this->getObject('user', 'security');
    }
    /**
     * Standard block show method.
     *
     * @return string $this->display block rendered
     */
    public function show() 
    {
        $userid = $this->objUser->userId();
        $this->notesOps->noteEditor($userid);
        return "With the new Chisimba dynamic canvas, it is better to render output as either wide or narrow blocks. Then use JSON templates to generate the rendered output. In this way, your module can use any block from any other module in addition to your own. In addition, your module will be able to render blocks to other sites, or as web widgets for use in other systems. Change this file to create your block but please don't be naughty and forget to edit the comment blocks. ALL OF THEM.";
    }
}
?>