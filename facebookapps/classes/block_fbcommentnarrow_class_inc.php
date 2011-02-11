<?php
/**
 *
 * A Facebook comment block
 *
 * A Facebook comment block that can be added to anything requiring comments
 * to use Facebook as a commenting mechanism.
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
 * @package    facebookapps
 * @author     Derek Keats <derek@dkeats.com>
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
 * A Facebook comment block
 *
 * A Facebook comment block that can be added to anything requiring comments
 * to use Facebook as a commenting mechanism.
 *
 * @category  Chisimba
 * @author    Derek Keats <derek@dkeats.com>
 * @version   0.001
 * @copyright 2011 AVOIR
 *
 */
class block_fbcommentnarrow extends object
{
    /**
     *
     * @var string The title of the block
     * @access public
     * 
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
     *
     * @var string Object $objLanguage String for the language object
     * @access public
     *
     */
    public $objLanguage;

    /**
     * Standard init function
     *
     * Create title
     *
     * @return NULL
     */
    public function init() 
    {
        // Get an instance of the languate object
        $this->objLanguage = $this->getObject('language', 'language');
        $this->title = "Facebook comment sideblock";
    }
    /**
     * Standard block show method.
     *
     * @return string $this->display block rendered
     */
    public function show() 
    {
        $objApps = $this->getObject('fbapps', 'facebookapps');
        return $objApps->getComments(160);
        
    }
}
?>
