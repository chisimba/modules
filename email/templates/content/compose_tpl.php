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
$objText = &$this->newObject('textarea', 'htmlelements');
$objTabbedbox = &$this->newObject('tabbedbox', 'htmlelements');
$objFieldset = &$this->newObject('fieldset', 'htmlelements');
$objLayer = &$this->newObject('layer', 'htmlelements');

// set up language items
$heading = $this->objLanguage->languageText('mod_email_compose', 'email');
$backLabel = $this->objLanguage->languageText('word_back');
$composeLabel = $this->objLanguage->languageText('mod_email_compose', 'email');
$toLabel = $this->objLanguage->languageText('word_to');
$subjectLabel = $this->objLanguage->languageText('word_subject');
$messageLabel = $this->objLanguage->languageText('word_message');
$sendLabel = $this->objLanguage->languageText('word_send');
$cancelLabel = $this->objLanguage->languageText('word_cancel');
$requiredLabel = $this->objLanguage->languageText('mod_email_requiredrecipient', 'email');
$searchSurnameLabel = $this->objLanguage->languageText('phrase_searchbysurname');
$emailLabel = $this->objLanguage->languageText('word_email');
$textLabel = $this->objLanguage->languageText('mod_email_text', 'email');
$addressLabel = $this->objLanguage->languageText('mod_email_addressbooks', 'email');
$attachmentsLabel = $this->objLanguage->languageText('word_attachments');
$uploadLabel = $this->objLanguage->languageText('word_upload');
$errorLabel = $this->objLanguage->languageText('mod_email_nofile', 'email');
$filesLabel = $this->objLanguage->languageText('mod_email_attachments', 'email');
$deleteLabel = $this->objLanguage->languageText('phrase_deleteattachment');
$confirmLabel = $this->objLanguage->languageText('mod_email_delattachment', 'email');
$searchFirstnameLabel = $this->objLanguage->languageText('phrase_searchbyfirstname');
$blankErrorLabel = $this->objLanguage->code2Txt('mod_email_blankfile', 'email');

// set up code to text
$array = array(
    'filesize' => $this->maxSize
);
$maxUploadLabel = $this->objLanguage->code2Txt('mod_email_filesize', 'email', $array);
$sizeErrorLabel = $this->objLanguage->code2Txt('mod_email_errorfilesize', 'email', $array);

// set up data
$configs = $this->getSession('configs');
$signature = isset($configs['signature']) ? $configs['signature'] : NULL;
$text = substr($message, (-1*(strlen($signature))));
if ($text != $signature) {
    $message = $message."--\n".$signature;
}

// set up heading
$objHeader->str = $heading;
$objHeader->type = 1;
$pageData = $objHeader->show();

// set up html elements
$objInput = new textinput('firstname', '', '', '30');
$objInput->extra = ' onkeyup="javascript:xajax_composeList(\'firstName\',this.value,document.getElementById(\'input_recipient\').value);"';
$firstnameInput = $objInput->show();
$objInput = new textinput('surname', '', '', '30');
$objInput->extra = ' onkeyup="javascript:xajax_composeList(\'surname\',this.value,document.getElementById(\'input_recipient\').value);"';
$surnameInput = $objInput->show();
$objLayer = new layer();
$objLayer->id = 'toList';
$objLayer->str = $toList;
$toLayer = $objLayer->show();
$objFieldset = new fieldset();
$objFieldset->extra = ' style="width:620px; height:100px;"';
$objFieldset->contents = $toLayer;
$toFieldset = $objFieldset->show();
$objInput = new textinput('recipient', $recipientList, 'hidden', '');
$recipientInput = $objInput->show();
$objInput = new textinput('subject', $subject, '', '103');
$subjectInput = $objInput->show();
$objText = new textarea('message', $message, 12, '100');
$messageText = $objText->show();

// set up address book icon
$objIcon->title = $addressLabel;
$addressIcon = $objIcon->getLinkedIcon($this->uri(array(
    'action' => 'manageaddressbooks'
)) , 'addressbook');

// set up search fieldset
$objTable = new htmltable();
//    $objTable->cellspacing='2';
$objTable->cellpadding = '4';
$objTable->startRow();
$objTable->addCell($textLabel, '', '', '', 'warning', 'colspan="2"');
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($firstnameInput, '50%', '', '', '', '');
$objTable->addCell("<div id =\"firstnameDiv\"></div>", '', '', '', '', '');
$objTable->endRow();
$searchTable = $objTable->show();
$objFieldset = new fieldset();
$objFieldset->legend = "<b>".$searchFirstnameLabel."</b>";
$objFieldset->contents = $searchTable;
$searchFieldset = $objFieldset->show();

// set up search fieldset
$objTable = new htmltable();
//    $objTable->cellspacing='2';
$objTable->cellpadding = '4';
$objTable->startRow();
$objTable->addCell($textLabel, '', '', '', 'warning', 'colspan="2"');
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($surnameInput, '50%', '', '', '', '');
$objTable->addCell("<div id =\"surnameDiv\"></div>", '', '', '', '', '');
$objTable->endRow();
$searchTable = $objTable->show();
$objFieldset = new fieldset();
$objFieldset->legend = "<b>".$searchSurnameLabel."</b>";
$objFieldset->contents = $searchTable;
$searchFieldset.= $objFieldset->show();

// set up tables and tabbedboxes
$objTable = new htmltable();
//    $objTable->cellspacing='2';
$objTable->cellpadding = '4';
$objTable->startRow();
$objTable->addCell($searchFieldset, '', '', '', '', '');
$objTable->endRow();
$objTable->startRow();
$objTable->addCell("<b>".$toLabel.":</b><br />".$recipientInput, '', '', '', '', '');
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($toFieldset, '', '', '', '', '');
$objTable->endRow();
$objTable->startRow();
$objTable->addCell("<b>".$subjectLabel.":</b><br />".$subjectInput, '', '', '', '', '');
$objTable->endRow();
$objTable->startRow();
$objTable->addCell("<b>".$messageLabel.":</b><br />".$messageText, '', '', '', '', '');
$objTable->endRow();
$emailTable = $objTable->show();
$objTabbedbox = new tabbedbox();
$objTabbedbox->addTabLabel($emailLabel."&nbsp;&#160;".$addressIcon);
$objTabbedbox->addBoxContent($emailTable);
$emailTab = $objTabbedbox->show();
$objTable = new htmltable();
//    $objTable->cellspacing='2';
$objTable->cellpadding = '4';
$objTable->startRow();
$objTable->addCell($emailTab, '', '', '', '', '');
$objTable->endRow();
$composeTable = $objTable->show();

// set up attachments tabbed box
$objInput = new textinput('attachment', '', 'file', '50');
$objInput->extra = ' maxlength="100"';
$attachInput = $objInput->show();
$action = $this->uri(array(
    'action' => 'upload'
));
$objButton = new button('upload', $uploadLabel);
//    $objButton->setToSubmit();
$objButton->extra = ' onclick="javascript:document.getElementById(\'form_composeform\').action=\''.$action.'\';document.getElementById(\'form_composeform\').submit();"';
$uploadButton = $objButton->show();
$objTable = new htmltable();
//    $objTable->cellspacing='2';
$objTable->cellpadding = '4';
if ($error == 1) {
    $objTable->startRow();
    $objTable->addCell("<b>".$sizeErrorLabel."</b>", '', '', '', 'error', 'colspan="3"');
    $objTable->endRow();
} elseif ($error == 4) {
    $objTable->startRow();
    $objTable->addCell("<b>".$errorLabel."</b>", '', '', '', 'error', 'colspan="3"');
    $objTable->endRow();
} elseif ($error == 'blank') {
    $objTable->startRow();
    $objTable->addCell("<b>".$blankErrorLabel."</b>", '', '', '', 'error', 'colspan="3"');
    $objTable->endRow();
}
$objTable->startRow();
$objTable->addCell($attachInput."&#160;&#160;".$uploadButton, '', '', '', '', 'colspan="3"');
$objTable->endRow();
$objTable->startRow();
$objTable->addCell("<b>".$maxUploadLabel."</b>", '', '', '', 'warning', 'colspan="3"');
$objTable->endRow();
$files = $this->attachLocation.'*';
if ($emailId != NULL) {
    $arrAttachments = $this->dbAttachments->getAttachments($emailId);
    if ($arrAttachments) {
        $checkDirectory = file_exists($this->attachLocation);
        if ($checkDirectory === FALSE) {
            mkdir($this->attachLocation, 0777);
        }
        foreach($arrAttachments as $attachment) {
            $handle = fopen($this->attachLocation.$attachment['file_name'], "wb");
            $contents = fwrite($handle, $attachment['file_data']);
            fclose($handle);
        }
    }
}
if (glob($files) != FALSE) {
    $objTable->startRow();
    $objTable->addCell("<b>".$filesLabel."</b>", '', '', '', '', 'colspan="3"');
    $objTable->endRow();
    $i = 1;
    foreach(glob($files) as $filename) {
        // set up delete attachment icon
        $deleteArray = $this->uri(array(
            'action' => 'deleteattachment',
            'file' => $filename
        ));
        $objIcon->title = $deleteLabel;
        $objIcon->setIcon('delete');
        $objIcon->extra = ' onclick="javascript:if(confirm(\''.$confirmLabel.'\')){document.getElementById(\'form_composeform\').action=\''.$deleteArray.'\';document.getElementById(\'form_composeform\').submit();}"';
        $deleteIcon = "<a href=\"#\">".$objIcon->show() ."</a>";
        $objTable->startRow();
        $objTable->addCell($i++.".", '3%', '', '', '', '');
        $objTable->addCell(basename($filename) , '50%', '', '', '', '');
        $objTable->addCell($deleteIcon, '', '', 'left', '', '');
        $objTable->endRow();
    }
}
$fileTable = $objTable->show();
$objTabbedbox = new tabbedbox();
$objTabbedbox->addTabLabel($attachmentsLabel);
$objTabbedbox->addBoxContent($fileTable);
$attachmentsTab = $objTabbedbox->show();
$objTable = new htmltable();
//    $objTable->cellspacing='2';
$objTable->cellpadding = '4';
$objTable->startRow();
$objTable->addCell($attachmentsTab, '', '', '', '', '');
$objTable->endRow();
$attachmentsTable = $objTable->show();

// set up buttons
$objButton = new button('submitbutton', $sendLabel);
$objButton->extra = ' onclick="javascript:if(document.getElementById(\'input_recipient\').value!=\'\'){document.getElementById(\'form_composeform\').sendbutton.value=\'Send\';document.getElementById(\'form_composeform\').submit();}else{alert(\''.$requiredLabel.'\');document.getElementById(\'form_composeform\').surname.focus();}"';
$buttons = "<br />".$objButton->show();
$objButton = new button('cancelbutton', $cancelLabel);
$objButton->extra = ' onclick="javascript:document.getElementById(\'form_hiddenform\').cancelbutton.value=\'Cancel\';document.getElementById(\'form_hiddenform\').submit();"';
$buttons.= "&#160;".$objButton->show();

// set up form
$objInput = new textinput('sendbutton', '', 'hidden', '');
$hiddenInput = $objInput->show();
$objForm = new form('composeform', $this->uri(array(
    'action' => 'sendemail'
)));
$objForm->extra = ' enctype="multipart/form-data"';
$objForm->addToForm($composeTable);
$objForm->addToForm($attachmentsTable);
$objForm->addToForm($hiddenInput);
$objForm->addToForm($buttons);
$composeForm = $objForm->show();

// set up hidden form
$objInput = new textinput('cancelbutton', '', 'hidden', '');
$hiddenInput = $objInput->show();
$objForm = new form('hiddenform', $this->uri(array(
    'action' => 'sendemail'
)));
$objForm->addToForm($hiddenInput);
$hiddenForm = $objForm->show();
$pageData.= $composeForm.$hiddenForm;

// set up exit link
$objLink = new link($this->uri(array(
    ''
) , 'email'));
$objLink->link = $backLabel;
$pageData.= "<br />".$objLink->show();
$objLayer = new layer();
$objLayer->padding = '10px';
$objLayer->str = $pageData;
$pageLayer = $objLayer->show();
echo $pageLayer;
?>