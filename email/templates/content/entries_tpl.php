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
$headerParams = $this->getJavascriptFile('selectall.js', 'htmlelements');
$this->appendArrayVar('headerParams', $headerParams);
$headerParams = $this->getJavascriptFile('new_sorttable.js', 'htmlelements');
$this->appendArrayVar('headerParams', $headerParams);
$this->objScriptaculous =& $this->getObject('scriptaculous', 'ajaxwrapper');
$this->objScriptaculous->show();
$this->setVar('pageSuppressXML', TRUE);

// set up style for autocomplete
$style = '<style type="text/css">
    div.autocomplete {
        position:absolute;
        //width:250px;
        background-color:white;
        //border:1px solid #888;
        //margin:0px;
        //padding:0px;
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
        //padding:2px;
        //height:32px;
        cursor:pointer;
    }
</style>';
echo $style;

$script = '<script type="text/javaScript">
    Event.observe(window, "load", init, false);
    
    function init(){
        var username = document.getElementById("input_username");
        var firstname = document.getElementById("input_firstname");
        var surname = document.getElementById("input_surname");

        if(username){
            Event.observe("input_username", "keyup", listusername, false);
        }
        if(firstname){
            Event.observe("input_firstname", "keyup", listfirstname, false);
        }
        if(surname){
            Event.observe("input_surname", "keyup", listsurname, false);
        }
    }

    function listusername(){        
        document.getElementById(\'input_firstname\').value=\'\';
        document.getElementById(\'input_surname\').value=\'\';
        document.getElementById(\'input_userid\').value=\'\';

        var pars = "module=email&action=searchlist&field=username";
        new Ajax.Autocompleter("input_username", "usernameDiv", "index.php", {parameters: pars});
    }

    function listfirstname(){        
        document.getElementById(\'input_username\').value=\'\';
        document.getElementById(\'input_surname\').value=\'\';
        document.getElementById(\'input_userid\').value=\'\';

        var pars = "module=email&action=searchlist&field=firstname";
        new Ajax.Autocompleter("input_firstname", "firstnameDiv", "index.php", {parameters: pars});
    }

    function listsurname(){        
        document.getElementById(\'input_username\').value=\'\';
        document.getElementById(\'input_firstname\').value=\'\';
        document.getElementById(\'input_userid\').value=\'\';

        var pars = "module=email&action=searchlist&field=surname";
        new Ajax.Autocompleter("input_surname", "surnameDiv", "index.php", {parameters: pars});
    }
</script>';
echo $script;

// set up html elements
$objHeader = &$this->loadClass('htmlheading', 'htmlelements');
$objIcon = &$this->newObject('geticon', 'htmlelements');
$objTable = &$this->loadClass('htmltable', 'htmlelements');
$objLink = &$this->loadClass('link', 'htmlelements');
$objInput = &$this->loadClass('textinput', 'htmlelements');
$objEditor = &$this->loadClass('htmlarea', 'htmlelements');
$objTabbedbox = &$this->loadClass('tabbedbox', 'htmlelements');
$objCheck = &$this->loadClass('checkbox', 'htmlelements');
$objRadio = &$this->loadClass('radio', 'htmlelements');
$objFieldset = &$this->loadClass('fieldset', 'htmlelements');
$objLayer = &$this->loadClass('layer', 'htmlelements');

// set up language items
$heading = $this->objLanguage->languageText('mod_email_addressbookentries', 'email');
$backLabel = $this->objLanguage->languageText('word_back');
$submitLabel = $this->objLanguage->languageText('word_submit');
$cancelLabel = $this->objLanguage->languageText('word_cancel');
$searchSurnameLabel = $this->objLanguage->languageText('phrase_searchbysurname');
$searchNameLabel = $this->objLanguage->languageText('phrase_searchbyfirstname');
$searchUsernameLabel = $this->objLanguage->languageText('phrase_searchbyusername');
$addEntryLabel = $this->objLanguage->languageText('mod_email_addentry', 'email');
$deleteEntryLabel = $this->objLanguage->languageText('mod_email_deleteentry', 'email');
$confirmLabel = $this->objLanguage->languageText('mod_email_confirmentry', 'email');
$usernameLabel = $this->objLanguage->languageText('word_username');
$fullnameLabel = $this->objLanguage->languageText('phrase_fullname');
$noEntriesLabel = $this->objLanguage->languageText('mod_email_noentries', 'email');
$selectallLabel = $this->objLanguage->languageText('phrase_selectall');
$deselectLabel = $this->objLanguage->languageText('phrase_deselectall');
$sendMailLabel = $this->objLanguage->languageText('phrase_sendmail');
$surnameLabel = $this->objLanguage->languageText('word_surname');
$nameLabel = $this->objLanguage->languageText('phrase_firstname');

// set up add icon
if ($bookId != NULL) {
    $objIcon->title = $addEntryLabel;
    $addIcon = $objIcon->getLinkedIcon($this->uri(array(
        'action' => 'addentry',
        'bookId' => $bookId
    )) , 'add');
    $arrBookData = $this->dbBooks->getBook($bookId);
    $subHeading = $arrBookData['book_name'];
} else {
    $addIcon = '';
    $arrContextData = $this->objContext->getContextDetails($contextCode);
    $subHeading = $arrContextData['menutext'];
}

// set up heading
$objHeader = new htmlHeading();
$objHeader->str = $heading;
$objHeader->type = 1;
$pageData = $objHeader->show();

$objHeader = new htmlHeading();
$objHeader->str = $subHeading.'&nbsp;'.$addIcon;
$objHeader->type = 3;
$pageData.= $objHeader->show();

// set up input table
if ($mode == 'add') {
    // set up username input
    $objInput = new textinput('username', '', '', '40');
    $usernameInput = $objInput->show();

    $objTable = new htmltable();
    //        $objTable->cellspacing='2';
    $objTable->cellpadding = '4';
    $objTable->startRow();
    $objTable->addCell($usernameInput.'<div id ="usernameDiv" class="autocomplete"></div>', '50%', '', '', '', '');
    $objTable->endRow();
    $usernameTable = $objTable->show();

    $objFieldset = new fieldset();
    $objFieldset->extra = ' style="border: 1px solid #808080; margin: 3px; padding: 10px;"';
    $objFieldset->legend = '<b>'.$searchUsernameLabel.'</b>';
    $objFieldset->contents = $usernameTable;
    $usernameFieldset = $objFieldset->show();

    // set up firstname input
    $objInput = new textinput('firstname', '', '', '40');
    $firstnameInput = $objInput->show();

    $objTable = new htmltable();
    //        $objTable->cellspacing='2';
    $objTable->cellpadding = '4';
    $objTable->startRow();
    $objTable->addCell($firstnameInput.'<div id ="firstnameDiv" class="autocomplete"></div>', '50%', '', '', '', '');
    $objTable->endRow();
    $firstnameTable = $objTable->show();

    $objFieldset = new fieldset();
    $objFieldset->extra = ' style="border: 1px solid #808080; margin: 3px; padding: 10px;"';
    $objFieldset->legend = '<b>'.$searchNameLabel.'</b>';
    $objFieldset->contents = $firstnameTable;
    $nameFieldset = $objFieldset->show();

    // set up surname input
    $objInput = new textinput('surname', '', '', '40');
    $surnameInput = $objInput->show();

    $objTable = new htmltable();
    //        $objTable->cellspacing='2';
    $objTable->cellpadding = '4';
    $objTable->startRow();
    $objTable->addCell($surnameInput.'<div id ="surnameDiv" class="autocomplete"></div>', '50%', '', '', '', '');
    $objTable->endRow();
    $surnameTable = $objTable->show();

    $objFieldset = new fieldset();
    $objFieldset->extra = ' style="border: 1px solid #808080; margin: 3px; padding: 10px;"';
    $objFieldset->legend = '<b>'.$searchSurnameLabel.'</b>';
    $objFieldset->contents = $surnameTable;
    $surnameFieldset = $objFieldset->show();

    // set up table
    $objTable = new htmltable();
    //        $objTable->cellspacing='2';
    $objTable->cellpadding = '4';
    $objTable->startRow();
    $objTable->addCell($usernameFieldset, '', '', '', '', '');
    $objTable->endRow();
    $objTable->startRow();
    $objTable->addCell($nameFieldset, '', '', '', '', '');
    $objTable->endRow();
    $objTable->startRow();
    $objTable->addCell($surnameFieldset, '', '', '', '', '');
    $objTable->endRow();
    $entryTable = $objTable->show();

    // set up hidden userid input
    $objInput = new textinput('userid', '', 'hidden', '');
    $useridInput = $objInput->show();

    $objButton = new button('addbutton', $submitLabel);
    $objButton->setToSubmit();
    $buttons = '<br />'.$objButton->show();

    $objButton = new button('cancelbutton', $cancelLabel);
    $objButton->extra = ' onclick="javascript:document.getElementById(\'input_cancelbutton\').value=\'Cancel\';document.getElementById(\'form_hiddenform\').submit();"';
    $buttons.= "&nbsp;".$objButton->show();

    // set up form
    $objForm = new form('entryform', $this->uri(array(
        'action' => 'submitentry',
        'bookId' => $bookId
    )));
    $objForm->addToForm($entryTable);
    $objForm->addToForm($useridInput);
    $objForm->addToForm($buttons);
    $entryForm = $objForm->show();

    // hidden element
    $objInput = new textinput('cancelbutton', '', 'hidden', '');
    $hiddenInput = $objInput->show();

    $objForm = new form('hiddenform', $this->uri(array(
        'action' => 'addressbook',
        'bookId' => $bookId
    )));
    $objForm->addToForm($hiddenInput);
    $hiddenForm = $objForm->show();

    $objTabbedbox = new tabbedbox();
    $objTabbedbox->extra = 'style="padding: 10px;"';
    $objTabbedbox->addTabLabel($addEntryLabel);
    $objTabbedbox->addBoxContent($entryForm.$hiddenForm);
    $entryTab = $objTabbedbox->show();
    $pageData.= $entryTab;
}

// set up check all button
$objButton = new button('checkallbutton', $selectallLabel);
$objButton->setOnClick('javascript:SetAllCheckBoxes(\'sendform\',\'userId[]\',true);');
$selectAllButton = $objButton->show();

// set up uncheck all button
$objButton = new button('uncheckallbutton', $deselectLabel);
$objButton->setOnClick('javascript:SetAllCheckBoxes(\'sendform\',\'userId[]\',false);');
$selectNoneButton = $objButton->show();

// set up send button
$objButton = new button('sendmail', $sendMailLabel);
$objButton->setToSubmit();
$sendButton = $objButton->show();
$buttons = $selectAllButton.'&nbsp;'.$selectNoneButton.'&nbsp;'.$sendButton;

// set up user list tabel
$objTable = new htmltable();
//    $objTable->cellspacing='2';
$objTable->cellpadding = '4';
$objTable->id = 'userListTable';
$objTable->css_class = 'sorttable';
$objTable->row_attributes = ' name="row_'.$objTable->id.'"';
$objTable->startRow();
$objTable->addCell('', '5%', '', '', 'heading', '');
$objTable->addCell($usernameLabel, '30%', '', '', 'heading', '');
$objTable->addCell($nameLabel, '30%', '', '', 'heading', '');
$objTable->addCell($surnameLabel, '', '', '', 'heading', '');
if (empty($contextCode)) {
    $objTable->addCell('', '10%', '', '', 'heading', '');
}
$objTable->endRow();
if (!empty($contextCode)) {
    if (empty($arrContextUserList)) {
        $objTable->startRow();
        $objTable->addCell($noEntriesLabel, '', '', '', 'noRecordsMessage', 'colspan="5"');
        $objTable->endRow();
    } else {
        foreach($arrContextUserList as $user) {
            // set up checkbox
            $objCheck = new checkbox('userId[]');
            $objCheck->value = $user['userid'];
            $userCheck = $objCheck->show();
            $objTable->startRow();
            $objTable->addCell($userCheck, '', '', 'center', '', '');
            $objTable->addCell($user['username'], '', '', '', '', '');
            $objTable->addCell(strtoupper($user['firstname']) , '', '', '', '', '');
            $objTable->addCell(strtoupper($user['surname']) , '', '', '', '', '');
            $objTable->endRow();
        }
    }
} else {
    if (empty($arrBookEntryList)) {
        $objTable->startRow();
        $objTable->addCell($noEntriesLabel, '', '', '', 'noRecordsMessage', 'colspan="5"');
        $objTable->endRow();
    } else {
        $i = 1;
        foreach($arrBookEntryList as $entry) {
            // set up delete icon
            $deleteArray = array(
                'action' => 'deleteentry',
                'bookId' => $bookId,
                'entryId' => $entry['id']
            );
            $deleteIcon = $objIcon->getDeleteIconWithConfirm('', $deleteArray, 'email', $confirmLabel);

            // set up checkbox
            $objCheck = new checkbox('userId[]');
            $objCheck->value = $entry['recipient_id'];
            $userCheck = $objCheck->show();
            $objTable->startRow();
            $objTable->addCell($userCheck, '', '', 'center', '', '');
            $objTable->addCell($this->objUser->userName($entry['recipient_id']) , '', '', '', '', '');
            $objTable->addCell(strtoupper($this->objUser->getFirstname($entry['recipient_id'])) , '', '', '', '', '');
            $objTable->addCell(strtoupper($this->objUser->getSurname($entry['recipient_id'])) , '', '', '', '', '');
            $objTable->addCell($deleteIcon, '', '', 'center', '', '');
            $objTable->endRow();
        }
    }
}
$userTable = $objTable->show();

// set up form
$objForm = new form('sendform', $this->uri(array(
    'action' => 'compose'
)));
if (!empty($arrContextUserList) || !empty($arrBookEntryList)) {
    $objForm->addToForm($buttons);
}
$objForm->addToForm($userTable);
if (!empty($arrContextUserList) || !empty($arrBookEntryList)) {
    $objForm->addToForm($buttons);
}
$sendForm = $objForm->show();

$objFieldset = new fieldset();
$objFieldset->contents = $sendForm;
$sendFieldset = $objFieldset->show();
$pageData.= $sendFieldset;

// set up exit link
$objLink = new link($this->uri(array(
    'action' => 'manageaddressbooks'
) , 'email'));
$objLink->link = $backLabel;
$pageData.= '<b />'.$objLink->show();

$objLayer = new layer();
$objLayer->padding = '10px';
$objLayer->addToStr($pageData);
$pageLayer = $objLayer->show();
echo $pageLayer;
?>