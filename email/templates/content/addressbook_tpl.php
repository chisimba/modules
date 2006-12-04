<?php
// security check-must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
* @package email
* Default template for the new email module
* Author Kevin Cyster
*/
// set up html elements
$objHeader = &$this->newObject('htmlheading', 'htmlelements');
$objTable = &$this->newObject('htmltable', 'htmlelements');
$objIcon = &$this->newObject('geticon', 'htmlelements');
$objLink = &$this->newObject('link', 'htmlelements');
$objInput = &$this->newObject('textinput', 'htmlelements');
$objEditor = &$this->newObject('htmlarea', 'htmlelements');
$objTabbedbox = &$this->newObject('tabbedbox', 'htmlelements');
$objFieldset = &$this->newObject('fieldset', 'htmlelements');
$objLayer = &$this->newObject('layer', 'htmlelements');

// set up language items
$heading = $this->objLanguage->languageText('mod_email_manageaddressbooks', 'email');
$backLabel = $this->objLanguage->languageText('word_back');
$submitLabel = $this->objLanguage->languageText('word_submit');
$cancelLabel = $this->objLanguage->languageText('word_cancel');
$noBooksLabel = $this->objLanguage->languageText('mod_email_nobooks', 'email');
$addBooksLabel = $this->objLanguage->languageText('mod_email_addbook', 'email');
$editBooksLabel = $this->objLanguage->languageText('mod_email_editbook', 'email');
$deleteBooksLabel = $this->objLanguage->languageText('mod_email_deletebook', 'email');
$confirmLabel = $this->objLanguage->languageText('mod_email_confirmbook', 'email');
$requiredLabel = $this->objLanguage->languageText('mod_email_requiredbook', 'email');
$entriesLabel = $this->objLanguage->languageText('word_entries');
$booksLabel = $this->objLanguage->languageText('mod_email_addressbooks', 'email');

// set up add icon
$objIcon->title = $addBooksLabel;
$addIcon = $objIcon->getLinkedIcon($this->uri(array(
    'action' => 'manageaddressbooks',
    'mode' => 'add'
)) , 'add');

// set up heading
$objHeader->str = $heading."&nbsp;".$addIcon;
$objHeader->type = 1;
$pageData = $objHeader->show();
$objTable = new htmltable();

//    $objTable->cellspacing='2';
$objTable->cellpadding = '4';
$objTable->startRow();
$objTable->addCell($booksLabel, '', '', '', 'heading', '');
$objTable->addCell($entriesLabel, '20%', '', '', 'heading', '');
$objTable->addCell('', '10%', '', '', 'heading', '');
$objTable->endRow();
if ($arrBookList == FALSE && empty($arrContextList)) {
    $objTable->startRow();
    $objTable->addCell($noBooksLabel, '', '', '', 'noRecordsMessage', 'colspan="3"');
    $objTable->endRow();
} else {
    if (!empty($arrContextList)) {
        foreach($arrContextList as $context) {
            // get number of entries
            $groupId = $this->objGroupAdmin->getLeafId(array(
                $context['contextCode']
            ));
            $arrContextUserList = $this->objGroupAdmin->getSubGroupUsers($groupId, array(
                'userId',
                'firstName',
                'surname',
                'username'
            ));
            $entries = count($arrContextUserList);
            // set up link
            $objLink = new link($this->uri(array(
                'action' => 'addressbook',
                'contextCode' => $context['contextCode'],
                'menutext' => $context['menutext']
            )) , 'email');
            $objLink->link = $context['menutext'];
            $contextLink = $objLink->show();
            $objTable->startRow();
            $objTable->addCell($contextLink, '', '', '', '', '');
            $objTable->addCell($entries, '', '', 'right', '', '');
            $objTable->addCell('', '', '', '', '', '');
            $objTable->endRow();
        }
    }
    if ($arrBookList != FALSE) {
        foreach($arrBookList as $book) {
            // get number of entries
            $arrBookEntriesList = $this->dbBookEntries->listBookEntries($book['id']);
            $entries = $arrBookEntriesList != FALSE ? count($arrBookEntriesList) : 0;
            // set up edit icon
            $objIcon->title = $editBooksLabel;
            $editIcon = $objIcon->getEditIcon($this->uri(array(
                'action' => 'manageaddressbooks',
                'mode' => 'edit',
                'bookId' => $book['id']
            )));
            if ($mode == 'edit' && $bookId == $book['id']) {
                $objInput = new textinput('bookId', $bookId, 'hidden', '');
                $bookName = $objInput->show();
                $objInput = new textinput('bookName', $book['book_name'], '', '60');
                $objInput->extra = ' MAXLENGTH="50"';
                $bookName.= $objInput->show();
            } else {
                // set up link
                $objLink = new link($this->uri(array(
                    'action' => 'addressbook',
                    'bookId' => $book['id'],
                    'activeBook' => $book['book_name']
                )) , 'email');
                $objLink->link = $book['book_name'];
                $bookName = $objLink->show();
            }
            // set up delete icon
            $deleteArray = array(
                'action' => 'manageaddressbooks',
                'mode' => 'delete',
                'bookId' => $book['id']
            );
            $deleteIcon = $objIcon->getDeleteIconWithConfirm('', $deleteArray, 'email', $confirmLabel);
            $objTable->startRow();
            $objTable->addCell($bookName, '', '', '', '', '');
            $objTable->addCell($entries, '', '', 'right', '', '');
            $objTable->addCell($editIcon."&nbsp;".$deleteIcon, '', '', '', '', '');
            $objTable->endRow();
        }
    }
}
if ($mode == 'add') {
    $objInput = new textinput('bookName', '', '', '60');
    $objInput->extra = ' MAXLENGTH="50"';
    $bookInput = $objInput->show();
    $objTable->startRow();
    $objTable->addCell($bookInput, '', '', '', 'odd', '');
    $objTable->addCell('', '', '', '', 'odd', '');
    $objTable->addCell('', '', '', '', 'odd', '');
    $objTable->endRow();
}
$bookTable = $objTable->show();
$objFieldset = new fieldset();
$objFieldset->contents = $bookTable;
$bookFieldset = $objFieldset->show();

// set up buttons
if ($mode == 'add') {
    $objButton = new button('addbutton', $submitLabel);
    $objButton->setToSubmit();
    $buttons = "<br />".$objButton->show();
    $objButton = new button('cancelbutton', $cancelLabel);
    $objButton->extra = ' onclick="javascript:document.getElementById(\'input_cancelbutton\').value=\'Cancel\';document.getElementById(\'form_hiddenform\').submit();"';
    $buttons.= "&nbsp;".$objButton->show();
} elseif ($mode == 'edit') {
    $objButton = new button('editbutton', $submitLabel);
    $objButton->setToSubmit();
    $buttons = "<br />".$objButton->show();
    $objButton = new button('cancelbutton', $cancelLabel);
    $objButton->extra = ' onclick="javascript:document.getElementById(\'input_cancelbutton\').value=\'Cancel\';document.getElementById(\'form_hiddenform\').submit();"';
    $buttons.= "&nbsp;".$objButton->show();
} else {
    $buttons = '';
}

// set up form
$objForm = new form('bookform', $this->uri(array(
    'action' => 'manageaddressbooks'
)));
$objForm->addToForm($bookFieldset);
$objForm->addToForm($buttons);
if ($mode == 'add' || $mode == 'edit') {
    $objForm->addRule('bookName', $requiredLabel, 'required');
}
$folderForm = $objForm->show();

// hidden element
$objInput = new textinput('cancelbutton', '', 'hidden', '');
$hiddenInput = $objInput->show();
$objForm = new form('hiddenform', $this->uri(array(
    'action' => 'manageaddressbooks'
)));
$objForm->addToForm($hiddenInput);
$hiddenForm = $objForm->show();
$pageData.= $folderForm.$hiddenForm;

// set up exit link
$objLink = new link($this->uri(array(
    ''
) , 'email'));
$objLink->link = $backLabel;
$pageData.= "<br />".$objLink->show();
$objLayer->padding = '10px';
$objLayer->addToStr($pageData);
$pageLayer = $objLayer->show();
echo $pageLayer;
?>