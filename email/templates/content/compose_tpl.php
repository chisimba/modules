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

// set up scriptaculous
//$this->objScriptaculous =& $this->getObject('scriptaculous', 'ajaxwrapper');
//$this->objScriptaculous->show();

$headerParams = $this->getJavascriptFile('compose.js', 'email');
$this->appendArrayVar('headerParams', $headerParams);

// set up style for autocomplete
$style = '<style type="text/css">
    div.autocomplete {
        position:absolute;
        background-color:white;
    }    
    div.autocomplete ul {
        list-style-type:none;
        margin:0px;
        padding:0px;
    }    
    div.autocomplete ul li.selected {
        border:1px solid #888;
        background-color: #ffb;
    }
    div.autocomplete ul li {
        border:1px solid #888;
        list-style-type:none;
        display:block;
        margin:0;
        cursor:pointer;
    }
</style>';
echo $style;

// set up html elements
$objIcon = &$this->newObject('geticon', 'htmlelements');
$objHeader = &$this->loadClass('htmlheading', 'htmlelements');
$objTable = &$this->loadClass('htmltable', 'htmlelements');
$objLink = &$this->loadClass('link', 'htmlelements');
$objInput = &$this->loadClass('textinput', 'htmlelements');
$objText = &$this->loadClass('textarea', 'htmlelements');
$objTabbedbox = &$this->loadClass('tabbedbox', 'htmlelements');
$objFieldset = &$this->loadClass('fieldset', 'htmlelements');
$objLayer = &$this->loadClass('layer', 'htmlelements');

$objIcon = $this->newObject('geticon', 'htmlelements');
$objIcon->setIcon('loading_circles_big');

// set up language items
$heading = $this->objLanguage->languageText('mod_email_compose', 'email');
$backLabel = $this->objLanguage->languageText('word_back');
$composeLabel = $this->objLanguage->languageText('mod_email_compose', 'email');
$toLabel = $this->objLanguage->languageText('word_to');
$subjectLabel = $this->objLanguage->languageText('word_subject');
$messageLabel = $this->objLanguage->languageText('mod_email_message', 'email');
$sendLabel = $this->objLanguage->languageText('word_send');
$cancelLabel = $this->objLanguage->languageText('word_reset');
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
    $message = $message."\n".'--'."\n".$signature;
}

// set up heading
$objHeader = new htmlHeading();
$objHeader->str = $heading;
$objHeader->type = 1;
$pageData = $objHeader->show();

// set up html elements
$objInput = new textinput('firstname', '', '', '50');
$objInput->extra = ' onfocus="javascript:this.value=\'\'" onkeyup="javascript:listfirstname();"';
$firstnameInput = $objInput->show();

$objLayer = new layer();
$objLayer->id = 'firstnameDiv';
$objLayer->cssClass = 'autocomplete';
$nameLayer = $objLayer->show();

$objInput = new textinput('surname', '', '', '50');
$objInput->extra = ' onfocus="javascript:this.value=\'\'" onkeyup="javascript:listsurname();"';
$surnameInput = $objInput->show();

$objLayer = new layer();
$objLayer->id = 'surnameDiv';
$objLayer->cssClass = 'autocomplete';
$surnameLayer = $objLayer->show();

$objLayer = new layer();
$objLayer->id = 'add_load';
$objLayer->floating = 'left';
$objLayer->visibility = 'hidden';
$objLayer->str = $objIcon->show();
$loadLayer = $objLayer->show();

$objLayer = new layer();
$objLayer->id = 'toList';
$objLayer->str = $toList;
$toLayer = $objLayer->show();

$objFieldset = new fieldset();
$objFieldset->extra = ' style="height: 100px; border: 1px solid #808080; margin: 3px; padding: 10px;"';
$objFieldset->contents = $loadLayer.$toLayer;
$toFieldset = $objFieldset->show();

$objInput = new textinput('recipient', $recipientList, 'hidden', '');
$recipientInput = $objInput->show();

$objInput = new textinput('subject', $subject, '', '115');
$subjectInput = $objInput->show();

$objText = new textarea('message', $message, 12, '132');
$messageText = $objText->show();

// set up address book icon
$action = $this->uri(array(
    'action' => 'showbooks'    
));
$objIcon->title = $addressLabel;
$objIcon->setIcon('addressbook');
$objIcon->extra=' onclick="javascript:
    document.getElementById(\'form_composeform\').action=\''.$action.'\';
    document.getElementById(\'form_composeform\').submit();"';
$addressIcon='<a href="#">'.$objIcon->show().'</a>';

// set up search fieldset
$objTable = new htmltable();
$objTable->cellpadding = '4';
$objTable->startRow();
$objTable->addCell($textLabel, '', '', '', 'warning', 'colspan="2"');
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($firstnameInput.$nameLayer, '50%', '', '', '', '');
$objTable->endRow();
$searchTable = $objTable->show();

$objFieldset = new fieldset();
$objFieldset->extra = ' style="border: 1px solid #808080; margin: 3px; padding: 10px;"';
$objFieldset->legend = '<b>'.$searchFirstnameLabel.'</b>';
$objFieldset->contents = $searchTable;
$searchFieldset = $objFieldset->show();

// set up search fieldset
$objTable = new htmltable();
$objTable->cellpadding = '4';
$objTable->startRow();
$objTable->addCell($textLabel, '', '', '', 'warning', 'colspan="2"');
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($surnameInput.$surnameLayer, '50%', '', '', '', '');
$objTable->endRow();
$searchTable = $objTable->show();

$objFieldset = new fieldset();
$objFieldset->extra = ' style="border: 1px solid #808080; margin: 3px; padding: 10px;"';
$objFieldset->legend = '<b>'.$searchSurnameLabel.'</b>';
$objFieldset->contents = $searchTable;
$searchFieldset.= $objFieldset->show();

// set up tables and tabbedboxes
$objTable = new htmltable();
$objTable->cellpadding = '4';
$objTable->startRow();
$objTable->addCell($searchFieldset, '', '', '', '', '');
$objTable->endRow();
$objTable->startRow();
$objTable->addCell('<b>'.$toLabel.':</b><br />'.$recipientInput, '', '', '', '', '');
$objTable->endRow();
$objTable->startRow();
$objTable->addCell($toFieldset, '', '', '', '', '');
$objTable->endRow();
$objTable->startRow();
$objTable->addCell('<b>'.$subjectLabel.':</b><br />'.$subjectInput, '', '', '', '', '');
$objTable->endRow();
$objTable->startRow();
$objTable->addCell('<b>'.$messageLabel.':</b><br />'.$messageText, '', '', '', '', '');
$objTable->endRow();
$emailTable = $objTable->show();

$objTabbedbox = new tabbedbox();
$objTabbedbox->extra = 'style="padding: 10px;"';
$objTabbedbox->addTabLabel($emailLabel.'&nbsp;&#160;'.$addressIcon);
$objTabbedbox->addBoxContent($emailTable);
$emailTab = $objTabbedbox->show();

$objTable = new htmltable();
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
$objButton->extra = ' onclick="javascript:
    document.getElementById(\'form_composeform\').action=\''.$action.'\';
    document.getElementById(\'form_composeform\').submit();"';
$uploadButton = $objButton->show();

$objTable = new htmltable();
$objTable->cellpadding = '4';
if ($error) {
    $objTable->startRow();
    $objTable->addCell('<b>'.$error.'</b>', '', '', '', 'error', 'colspan="3"');
    $objTable->endRow();
}
$objTable->startRow();
$objTable->addCell($attachInput.'&#160;&#160;'.$uploadButton, '', '', '', '', 'colspan="3"');
$objTable->endRow();
$objTable->startRow();
$objTable->addCell('<b>'.$maxUploadLabel.'</b>', '', '', '', 'warning', 'colspan="3"');
$objTable->endRow();
if ($emailId != NULL) {
    $this->emailFiles->createAttachments($emailId);
}
$attachments = $this->emailFiles->getAttachments();
if ($attachments != NULL) {
    $objTable->startRow();
    $objTable->addCell('<b>'.$filesLabel.'</b>', '', '', '', '', 'colspan="3"');
    $objTable->endRow();
    $i = 1;
    foreach($attachments as $attachment) {
        $deleteArray = $this->uri(array(
            'action' => 'deleteattachment',
            'file' => $attachment['filename'],
        ));
        $objIcon->title = $deleteLabel;
        $objIcon->setIcon('delete');
        $objIcon->extra = ' onclick="javascript:
            if(confirm(\''.$confirmLabel.'\')){
                document.getElementById(\'form_composeform\').action=\''.$deleteArray.'\';
                document.getElementById(\'form_composeform\').submit();
            }"';
        $deleteIcon = '<a href="#">'.$objIcon->show() .'</a>';
        $objTable->startRow();
        $objTable->addCell($i++.'.', '3%', '', '', '', '');
        $objTable->addCell($attachment['filename'], '50%', '', '', '', '');
        $objTable->addCell($deleteIcon, '', '', 'left', '', '');
        $objTable->endRow();
    }
}
$fileTable = $objTable->show();

$objTabbedbox = new tabbedbox();
$objTabbedbox->extra = 'style="padding: 10px;"';
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
$objButton->extra = ' onclick="javascript:
    if(document.getElementById(\'input_recipient\').value!=\'\'){
        document.getElementById(\'form_composeform\').sendbutton.value=\'Send\';
        document.getElementById(\'form_composeform\').submit();
    }else{
        alert(\''.$requiredLabel.'\');
        document.getElementById(\'form_composeform\').surname.focus();
    }"';
$buttons = '<br />'.$objButton->show();

$objButton = new button('cancelbutton', $cancelLabel);
$objButton->extra = ' onclick="javascript:
    document.getElementById(\'form_hiddenform\').cancelbutton.value=\'Cancel\';
    document.getElementById(\'form_hiddenform\').submit();"';
$buttons.= '&#160;'.$objButton->show();

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
$pageData.= '<br />'.$objLink->show();

$objLayer = new layer();
$objLayer->padding = '10px';
$objLayer->str = $pageData;
$pageLayer = $objLayer->show();
echo $pageLayer;
?>