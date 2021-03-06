<?php
/**
 *
 * A middle block for schools.
 *
 * A middle block for schools. Simple facility to store school basic data.
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
 * @package    schools
 * @author     Kevin Cyster kcyster@gmail.com
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
 * A middle block for schools.
 *
 * A middle block for schools. Simple facility to store school basic data.
 *
 * @category  Chisimba
 * @author    Kevin Cyster kcyster@gmail.com
 * @version   0.001
 * @copyright 2011 AVOIR
 *
 */
class block_schoolsaddedit extends object
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
        // Load language class.
        $this->objLanguage = $this->getObject('language', 'language');
        $this->mode = $this->getParam('mode');
        if ($this->mode == 'add')
        {
            $this->title = $this->objLanguage->code2Txt('mod_schools_addschool', 'schools', NULL, 'TEXT: mod_schools_addschool, not found');
        }
        else
        {
            $this->title = $this->objLanguage->code2Txt('mod_schools_editschool', 'schools', NULL, 'TEXT: mod_schools_editschool, not found');
        }
        // Load operations class for schools.
        $this->objOps = $this->getObject('schoolsops', 'schools');
    }

    /**
     * Standard block show method.
     *
     * @return string $this->display block rendered
     */
    public function show() 
    {
        return $this->objOps->addEditSchool($this->mode);
    }
}
?>