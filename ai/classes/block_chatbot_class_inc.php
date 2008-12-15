<?php
/**
 * Short description for file.
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
 * @version    $Id: block_latest_class_inc.php 11076 2008-10-25 18:13:10Z charlvn $
 * @package    blog
 * @subpackage blocks
 * @author     Paul Scott <pscott@uwc.ac.za>
 * @copyright  2006-2007 AVOIR
 * @license    http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @link       http://avoir.uwc.ac.za
 * @see        References to other sections (if any)...
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
 *         $Id: block_latest_class_inc.php 11076 2008-10-25 18:13:10Z charlvn $
 *
 */
class block_chatbot extends object
{
    /**
     * The title of the block
     *
     * @var    string
     * @access public
     */
    public $title;

    public $objLanguage;

    /**
     * Standard init function
     *
     * Instantiates language and user objects and creates title
     *
     * @return NULL
     */
    public function init()
    {
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objUser = $this->getObject('user', 'security');
        $this->userid = $this->objUser->userid();
        $this->chatbot = $this->getObject('chatterbot', 'ai');
        $this->title = $this->objLanguage->languageText("mod_ai_chat", "ai");
    }
    /**
     * Standard block show method.
     *
     * @return string the box rendered
     */
    public function show()
    {
        // bang up a form and a textarea to see the responses
        return $this->chatbot->chat("hello there!", $this->userid);

    }
}
?>