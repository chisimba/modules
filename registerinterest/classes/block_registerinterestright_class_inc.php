<?php
/**
 *
 * A right block for Register interest.
 *
 * A right block for Register interest. Allows someone to enter name and email address in order to be contacted at sometime in the future about something of interest. For example, register to be notified about the upcoming API release, Lady GooGoo concert, etc..
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
 * @package    registerinterest
 * @author     Derek Keats derek@dkeats.com
 * @copyright  2010 AVOIR
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
 * A right block for Register interest.
 *
 * A right block for Register interest. Allows someone to enter name and email address in order to be contacted at sometime in the future about something of interest. For example, register to be notified about the upcoming API release, Lady GooGoo concert, etc..
 *
 * @category  Chisimba
 * @package    registerinterest
 * @author     Derek Keats derek@dkeats.com
 * @version   0.001
 * @copyright 2010 AVOIR
 *
 */
class block_registerinterestright extends object
{
    /**
     * The title of the block
     *
     * @var    object
     * @access public
     */
    public $title;
    /**
     * Standard init function
     *
     * Create title
     *
     * @return NULL
     */
    public function init() 
    {
        $this->title = "Right block sample";
    }
    /**
     * Standard block show method.
     *
     * @return string $this->display block rendered
     */
    public function show() 
    {
        return "This is a demonstration right block.";
    }
}
?>