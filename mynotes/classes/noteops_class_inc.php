<?php

/**
 *
 * A simple notes module operations object
 * 
 * Arbitrary notes about anything, and organized using tags. These notes can be
 * shared with friends, members of a context, or made public.
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
 * @version
 * @package    mynotes
 * @author     Nguni Phakela info@nguni52.co.za
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
 * A simple notes module operations object
 *
 * @category  Chisimba
 * @author    Nguni Phakela
 * @version
 * @copyright 2010 AVOIR
 *
 */
class noteops extends object {

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
    public function init() {
        try {
            // Load the functions specific to this module.
            $this->appendArrayVar('headerParams', $this->getJavaScriptFile('mynotes.js'));
            // Instantiate the language object.
            $this->objLanguage = $this->getObject('language', 'language');
            $this->objUser = $this->getObject('user', 'security');

            $this->objTable = $this->loadClass('htmltable', 'htmlelements');
            $this->objLink = $this->loadClass('link', 'htmlelements');
            $this->objIcon = $this->getObject('geticon', 'htmlelements');
            $this->objInput = $this->loadClass('textinput', 'htmlelements');
            $this->objForm = $this->loadClass('form', 'htmlelements');


            $this->objDbmynotes = $this->getObject('dbmynotes', 'mynotes');
        } catch (customException $e) {
            echo customException::cleanUp();
            die();
        }
    }

    /**
     * Render the input box for creating a note.
     *
     * @return string The input box and button
     *
     */
    public function showNotes() {
        $addLabel = $this->objLanguage->languageText('word_add', 'system', 'WORD: word_add, not found');
        $addNoteLabel = $this->objLanguage->code2Txt('mod_mynotes_addnote', 'mynotes', NULL, 'TEXT: mod_mynotes_addnote, not found');
        $ret = '';

        $this->objIcon->title = $addLabel;
        $this->objIcon->alt = $addLabel;
        $this->objIcon->setIcon('add', 'png');
        $addIcon = $this->objIcon->show();

        $objLink = new link($this->uri(array('action' => 'add', 'mode' => 'add')));
        $objLink->link = $addIcon . '&nbsp;' . $addNoteLabel;
        $addLink = $objLink->show();

        $ret .= $error . '<br />' . $addLink;

        return $ret;
    }

    /**
     * Method to display the posts editor in its entirety
     * 
     * title
     * tags
     * editor
     *
     * @param  integer $userid
     * @param  integer $editid
     * @param  string $defaultText Default Text to be populated in the Editor
     * @return boolean
     */
    public function addEditNote($mode) {
        if ($mode == 'add') {
            $titleInputValue = NULL;
            $tagInputValue = NULL;
            $editorInputValue = NULL;
            $mainText = NULL;

            $idInput = NULL;
        }

        $errorArray = $this->getSession('errors');
        $titleInputError = (!empty($errorArray) && array_key_exists('title', $errorArray['errors'])) ? $errorArray['errors']['title'] : NULL;
        $tagInputError = (!empty($errorArray) && array_key_exists('tags', $errorArray['errors'])) ? $errorArray['errors']['tags'] : NULL;
        $editorInputError = (!empty($errorArray) && array_key_exists('content', $errorArray['errors'])) ? $errorArray['errors']['content'] : NULL;

        $titleInputValue = !empty($errorArray) ? $errorArray['data']['title'] : $titleInputValue;
        $tagInputValue = !empty($errorArray) ? $errorArray['data']['tag'] : $tagInputValue;
        $editorInputValue = !empty($errorArray) ? $errorArray['data']['content'] : $editorInputValue;

        $saveLabel = $this->objLanguage->languageText('word_save', 'system', 'WORD: word_save, not found');
        $cancelLabel = $this->objLanguage->languageText('word_cancel', 'system', 'WORD: word_cancel, not found');
        $noteTitleLabel = ucfirst($this->objLanguage->code2Txt('mod_mynotes_notetitle', 'mynotes', NULL, 'TEXT: mod_mynotes_notetitle, not found'));
        $noteTagLabel = ucfirst($this->objLanguage->code2Txt('mod_mynotes_notetag', 'mynotes', NULL, 'TEXT: mod_mynotes_notetag, not found'));
        $noteEditorLabel = ucfirst($this->objLanguage->code2Txt('mod_mynotes_noteeditor', 'mynotes', NULL, 'TEXT: mod_mynotes_noteeditor, not found'));

        $objInput = new textinput('title', $titleInputValue, '', '40');
        $titleInput = $objInput->show();

        $objInput = new textinput('tags', $tagInputValue, '', '40');
        $tagInput = $objInput->show();

        $objTable = new htmltable();
        $objTable->cellpadding = '4';

        /* Add Title Row */
        $objTable->startRow();
        $objTable->addCell($noteTitleLabel . ': ', '100px', '', '', '', '');
        $objTable->addCell($titleInputError . $titleInput, '', '', '', '', '');
        $objTable->endRow();

        /* Add Tags Row */
        $objTable->startRow();
        $objTable->addCell($noteTagLabel . ': ', '100px', '', '', '', '');
        $objTable->addCell($tagInputError . $tagInput, '', '', '', '', '');
        $objTable->endRow();

        /* Add Editor Rows */
        //Add the WYSIWYG editor label
        $objTable->startRow();
        $objTable->addCell($noteEditorLabel . ":&nbsp;", NULL, 'top', '', NULL, '');

        //Add the WYSIWYG editor
        $editor = $this->newObject('htmlarea', 'htmlelements');
        $editor->name = ucfirst($this->objLanguage->code2Txt('mod_mynotes_notecontentname', 'mynotes', NULL, 'TEXT: mod_mynotes_notecontentname, not found'));
        $editor->height = '300px';
        $editor->width = '550px';
        $editor->setContent($mainText);
        $objTable->addCell($editor->show(), NULL, '', '', NULL, '');
        $objTable->endRow();

        $objButton = new button('save', $saveLabel);
        $objButton->setToSubmit();
        $saveButton = $objButton->show();

        $objButton = new button('cancel', $cancelLabel);
        $objButton->setToSubmit();
        $cancelButton = $objButton->show();

        $objTable->startRow();
        $objTable->addCell($idInput . $saveButton . '&nbsp;' . $cancelButton, '', '', '', '', 'colspan="2"');
        $objTable->endRow();

        $objForm = new form('mynotes', $this->uri(array(
                            'action' => 'validatenote',
                            'mode' => $mode
                        )));

        $objForm->addToForm($objTable->show());

        return $objForm->show();
    }

    public function validateNote($mode = NULL) {
        if ($mode == add) {
            $idInput = NULL;
        } else {
            $idInputValue = $this->getParam('sid');
            $objInput = new textinput('sid', $idInputValue, 'hidden', '50');
            $idInput = $objInput->show();
        }

        $titleInputValue = $this->getParam('title');
        $tagInputValue = $this->getParam('tags');
        $editorInputValue = $this->getParam('NoteContent');

        $confirmLabel = $this->objLanguage->languageText('word_confirm', 'system', 'WORD: word_save, not found');
        $backLabel = $this->objLanguage->languageText('word_back', 'system', 'WORD: word_cancel, not found');
        $noteTitleLabel = ucfirst($this->objLanguage->code2Txt('mod_mynotes_notetitle', 'mynotes', NULL, 'TEXT: mod_mynotes_notetitle, not found'));
        $noteTagLabel = ucfirst($this->objLanguage->code2Txt('mod_mynotes_notetag', 'mynotes', NULL, 'TEXT: mod_mynotes_notetag, not found'));
        $noteEditorLabel = ucfirst($this->objLanguage->code2Txt('mod_mynotes_noteeditor', 'mynotes', NULL, 'TEXT: mod_mynotes_noteeditor, not found'));

        $objInput = new textinput('title', $titleInputValue, 'hidden', '40');
        $titleInput = $objInput->show();

        $objInput = new textinput('tags', $tagInputValue, 'hidden', '40');
        $tagInput = $objInput->show();

        $objInput = new textinput('content', $editorInputValue, 'hidden', '40');
        $editorInput = $objInput->show();

        $objTable = new htmltable();
        $objTable->cellpadding = '4';

        /* Add Title Row */
        $objTable->startRow();
        $objTable->addCell("<strong>".$noteTitleLabel . '</strong>: ', '100px', '', '', '', '');
        $objTable->addCell($titleInputValue, '', '', '', '', '');
        $objTable->endRow();

        /* Add Tags Row */
        $objTable->startRow();
        $objTable->addCell("<strong>".$noteTagLabel . '</strong>: ', '100px', '', '', '', '');
        $objTable->addCell($tagInputValue, '', '', '', '', '');
        $objTable->endRow();

        /* Add Editor Rows */
        $objTable->startRow();
        $objTable->addCell("<strong>".$noteEditorLabel . "</strong>:&nbsp;", NULL, 'top', '', NULL, '');
        $objTable->addCell($editorInputValue, NULL, 'top', '', NULL, '');
        $objTable->endRow();

        $objButton = new button('save', $confirmLabel);
        $objButton->setToSubmit();
        $confirmButton = $objButton->show();

        $objButton = new button('cancel', $backLabel);
        $backButton = $objButton->show();

        $objTable->startRow();
        $objTable->addCell($idInput . $confirmButton . '&nbsp;' . $backButton, '', '', '', '', 'colspan="2"');
        $objTable->endRow();

        $validateForm = new form('mynotes', $this->uri(array(
                            'action' => 'save',
                            'mode' => $mode
                        )));

        $validateForm->addToForm($objTable);
        $validateForm->addToForm($idInput);
        $validateForm->addToForm($titleInput);
        $validateForm->addToForm($tagInput);
        $validateForm->addToForm($editorInput);

        return $validateForm->show();
    }

    public function getNotes() {
        // Set up text elements.
        $noNotesLabel = $this->objLanguage->languageText('mod_mynotes_nonotes', 'mynotes', 'TEXT: mod_mynotes_nonotes, not found');

        $uid = $this->objUser->userId();

        $notesArray = $this->objDbmynotes->getNotes($uid);

        $ret = '';
        if (!empty($notesArray)) {
            $list = "<div><ul>";
            foreach ($notesArray as $value) {
                $list .= "<li>" . strip_tags($value['content']) . "</li>";
            }
            $list .= "</ul></div>";
        } else {
            $error = $this->error($noNotesLabel);

            $list = "<div><ul>" . $error . "</ul></div>";
        }
        $ret .= $list;

        echo $ret;
        die();
    }

}

?>