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

// set up javascript headers
$headerParams = $this->getJavascriptFile('new_sorttable.js', 'htmlelements');
$this->appendArrayVar('headerParams', $headerParams);

// set up html elements
$objIcon = &$this->newObject('geticon', 'htmlelements');
$objHeader = &$this->loadClass('htmlheading', 'htmlelements');
$objTable = &$this->loadClass('htmltable', 'htmlelements');
$objLink = &$this->loadClass('link', 'htmlelements');
$objInput = &$this->loadClass('textinput', 'htmlelements');
$objEditor = &$this->loadClass('htmlarea', 'htmlelements');
$objTabbedbox = &$this->loadClass('tabbedbox', 'htmlelements');
$objFieldset = &$this->loadClass('fieldset', 'htmlelements');
$objLayer = &$this->loadClass('layer', 'htmlelements');

// set up language items
$heading = $this->objLanguage->languageText('mod_email_manageaddressbooks', 'email');
$showHeading = $this->objLanguage->languageText('mod_email_addressbooks', 'email');
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

// set up heading
if($mode == 'show'){
    $objHeader = new htmlHeading();
    $objHeader->str = $showHeading;
    $objHeader->type = 1;
    $currentFolderId = '';
}else{
    $objIcon->title = $addBooksLabel;
    $addIcon = $objIcon->getLinkedIcon($this->uri(array(
        'action' => 'manageaddressbooks',
        'mode' => 'add',
        'currentFolderId' => $currentFolderId,
    )) , 'add');

    $objHeader = new htmlHeading();
    $objHeader->str = $heading.'&nbsp;'.$addIcon;
    $objHeader->type = 1;
}
$pageData = $objHeader->show();

$objTable = new htmltable();
$objTable->id = 'bookList';
$objTable->css_class = 'sorttable';
$objTable->cellpadding = '4';
$objTable->row_attributes = ' name="row_'.$objTable->id.'"';
$objTable->startRow();
$objTable->addCell($booksLabel, '', '', '', 'heading', '');
$objTable->addCell($entriesLabel, '20%', '', 'center', 'heading', '');
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
                $context
            ));
            $arrContextUserList = $this->objGroupAdmin->getSubGroupUsers($groupId, array(
                'userId',
                'firstName',
                'surname',
                'username'
            ));
            $entries = count($arrContextUserList);

            // set up link
            if($mode == 'show'){
                $objLink = new link($this->uri(array(
                    'action' => 'showentries',
                    'contextcode' => $context,
                    'recipientList' => $recipientList,
                    'subject' => $subject,
                    'message' => $message,
                )) , 'email');
            }else{
                $objLink = new link($this->uri(array(
                    'action' => 'addressbook',
                    'contextcode' => $context,
                    'currentFolderId' => $currentFolderId,
                )) , 'email');
            }
            $objLink->link = $context;
            $contextLink = $objLink->show();

            $objTable->startRow();
            $objTable->addCell($contextLink, '', '', '', '', '');
            $objTable->addCell($entries, '', '', 'center', '', '');
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
                'currentFolderId' => $currentFolderId,
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
                if($mode == 'show'){                    
                    $objLink = new link($this->uri(array(
                        'action' => 'showentries',
                        'bookId' => $book['id'],
                        'recipientList' => $recipientList,
                        'subject' => $subject,
                        'message' => $message,
                    )) , 'email');
                }else{
                    $objLink = new link($this->uri(array(
                        'action' => 'addressbook',
                        'bookId' => $book['id'],
                        'currentFolderId' => $currentFolderId,
                    )) , 'email');
                }
                $objLink->link = $book['book_name'];
                if($entries == 0 && $mode == 'show'){
                    $bookName = $book['book_name'];                
                }else{
                    $bookName = $objLink->show();
                }
            }
            // set up delete icon
            $deleteArray = array(
                'action' => 'manageaddressbooks',
                'mode' => 'delete',
                'currentFolderId' => $currentFolderId,
                'bookId' => $book['id']
            );
            $deleteIcon = $objIcon->getDeleteIconWithConfirm('', $deleteArray, 'email', $confirmLabel);
            
            if($mode == 'show'){
                $icons ='';
            }else{
                $icons = $editIcon.'&nbsp;'.$deleteIcon;
            }

            $objTable->startRow();
            $objTable->addCell($bookName, '', '', '', '', '');
            $objTable->addCell($entries, '', '', 'center', '', '');
            $objTable->addCell($icons, '', '', '', '', '');
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
    $buttons = '<br />'.$objButton->show();

    $objButton = new button('cancelbutton', $cancelLabel);
    $objButton->extra = ' onclick="javascript:
        document.getElementById(\'input_cancelbutton\').value=\'Cancel\';
        document.getElementById(\'form_hiddenform\').submit();"';
    $buttons.= '&nbsp;'.$objButton->show();
} elseif ($mode == 'edit') {
    $objButton = new button('editbutton', $submitLabel);
    $objButton->setToSubmit();
    $buttons = '<br />'.$objButton->show();

    $objButton = new button('cancelbutton', $cancelLabel);
    $objButton->extra = ' onclick="javascript:
        document.getElementById(\'input_cancelbutton\').value=\'Cancel\';
        document.getElementById(\'form_hiddenform\').submit();"';
    $buttons.= '&nbsp;'.$objButton->show();
} else {
    $buttons = '';
}
// set up form
$objForm = new form('bookform', $this->uri(array(
    'action' => 'manageaddressbooks',
    'currentFolderId' => $currentFolderId
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
    'action' => 'manageaddressbooks',
    'currentFolderId' => $currentFolderId
)));
$objForm->addToForm($hiddenInput);
$hiddenForm = $objForm->show();
$pageData.= $folderForm.$hiddenForm;

// set up exit link
if($mode == 'show'){
    $objLink = new link($this->uri(array(
        'action' => 'compose',
        'userId' => $recipientList,
        'subject' => $subject,
        'message' => $message,
    )), 'email');    
}else{
    $objLink = new link($this->uri(array(
        'action' => 'gotofolder',
        'folderId' => $currentFolderId
    )) , 'email');
}
$objLink->link = $backLabel;
$pageData.= '<br />'.$objLink->show();

$objLayer = new layer();
$objLayer->padding = '10px';
$objLayer->addToStr($pageData);
$pageLayer = $objLayer->show();
echo $pageLayer;
?>