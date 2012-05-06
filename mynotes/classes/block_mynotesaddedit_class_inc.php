<?php
/**
 *
 * A middle block for adding or editing notes.
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
 * @author     Nguni Phakela nguni52@gmail.com
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
 * @category  Chisimba
 * @author    Nguni Phakela nguni52@gmail.com
 * @version   0.001
 * @copyright 2011 AVOIR
 *
 */
class block_mynotesaddedit extends object
{
    /**
     * The title of the block
     *
     * @var    object
     * @access public
     */
    public $title;
    
    /*
     * The mode of the note - add or edit
     * 
     * @var     object
     * @access  private
     */
    private $mode;
    
    /**
     * Standard init function
     *
     * Create title
     *
     * @return NULL
     */
    public function init() {
        // Load the functions specific to this page.
        $this->appendArrayVar('headerParams', $this->getJavaScriptFile('js/jquery.form.js'));
        $this->appendArrayVar('headerParams', $this->getJavaScriptFile('js/addedit.js'));
        
        $this->objNoteOps = $this->getObject('noteops', 'mynotes');
        // Load language class.
        $this->objLanguage = $this->getObject('language', 'language');
        
        $this->mode = $this->getParam('mode');
        if ($this->mode == 'add')
        {
            $this->title = $this->objLanguage->code2Txt('mod_mynotes_addnote', 'mynotes', NULL, 'TEXT: mod_mynotes_addnote, not found');
        } else {
            $this->title = $this->title = $this->objLanguage->code2Txt('mod_mynotes_editnote', 'mynotes', NULL, 'TEXT: mod_mynotes_editnote, not found');
        }
    }
    
    /**
     * Standard block show method.
     *
     * @return string $this->display block rendered
     */
    public function show() {
        return $this->objNoteOps->addEditNote($this->mode);
    }
}
?>