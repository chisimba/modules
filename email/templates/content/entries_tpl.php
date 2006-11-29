<?php
// security check-must be included in all scripts
if(!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}
// end security check

/**
* @package email
*/

/**
* Default template for the new email module
* Author Kevin Cyster
* */

    $headerParams=$this->getJavascriptFile('selectall.js','htmlelements');
    $this->appendArrayVar('headerParams',$headerParams);
    $headerParams=$this->getJavascriptFile('new_sorttable.js','htmlelements');
    $this->appendArrayVar('headerParams',$headerParams);
//    $body = ' onload="var SORT_COLUMN_INDEX; sortables_init();"';
//    $this -> setVarByRef('bodyParams', $body);
//    $this->setLayoutTemplate('layout_tpl.php');

// set up html elements
    $objHeader=&$this->newObject('htmlheading','htmlelements');
    $objTable=&$this->newObject('htmltable','htmlelements');
    $objIcon=&$this->newObject('geticon','htmlelements');
    $objLink=&$this->newObject('link','htmlelements');
    $objInput=&$this->newObject('textinput','htmlelements');
    $objEditor=&$this->newObject('htmlarea','htmlelements');
    $objTabbedbox=&$this->newObject('tabbedbox','htmlelements');
    $objRadio=&$this->newObject('radio','htmlelements');
    $objFieldset=&$this->newObject('fieldset','htmlelements');
    $objLayer=&$this->newObject('layer','htmlelements');

// set up language items
    $heading=$this->objLanguage->languageText('mod_email_addressbookentries','email');
    $backLabel=$this->objLanguage->languageText('word_back');
    $submitLabel=$this->objLanguage->languageText('word_submit');
    $cancelLabel=$this->objLanguage->languageText('word_cancel');
    $searchSurnameLabel=$this->objLanguage->languageText('phrase_searchbysurname');
    $searchNameLabel=$this->objLanguage->languageText('phrase_searchbyfirstname');
    $searchUsernameLabel=$this->objLanguage->languageText('phrase_searchbyusername');
    $addEntryLabel=$this->objLanguage->languageText('mod_email_addentry','email');
    $deleteEntryLabel=$this->objLanguage->languageText('mod_email_deleteentry','email');
    $confirmLabel=$this->objLanguage->languageText('mod_email_confirmentry','email');
    $usernameLabel=$this->objLanguage->languageText('word_username');
    $fullnameLabel=$this->objLanguage->languageText('phrase_fullname');
    $noEntriesLabel=$this->objLanguage->languageText('mod_email_noentries','email');
    $selectallLabel=$this->objLanguage->languageText('phrase_selectall');
    $deselectLabel=$this->objLanguage->languageText('phrase_deselectall');
    $sendMailLabel=$this->objLanguage->languageText('phrase_sendmail');
    $surnameLabel=$this->objLanguage->languageText('word_surname');
    $nameLabel=$this->objLanguage->languageText('phrase_firstname');

    // set up add icon
    if($bookId!=NULL){
        $objIcon->title=$addEntryLabel;
        $addIcon=$objIcon->getLinkedIcon($this->uri(array('action'=>'addentry','bookId'=>$bookId)),'add');

        $arrBookData=$this->dbBooks->getBook($bookId);
        $subHeading=$arrBookData['book_name'];
    }else{
        $addIcon='';
        $arrContextData=$this->objContext->getContextDetails($contextCode);
        $subHeading=$arrContextData['menutext'];
    }

// set up heading
    $objHeader->str=$heading;
    $objHeader->type=1;
    $pageData=$objHeader->show();

    $objHeader->str=$subHeading."&nbsp;".$addIcon;
    $objHeader->type=3;
    $pageData.=$objHeader->show();

    if($mode=='add'){
        $objInput=new textinput('username','','','30');
        $objInput->extra=' onkeyup="xajax_searchList(this.value,\'username\');"';
        $usernameInput=$objInput->show();

        $objTable=new htmltable();
//        $objTable->cellspacing='2';
        $objTable->cellpadding='4';

        $objTable->startRow();
        $objTable->addCell($usernameInput,'50%','','','','');
        $objTable->addCell("<div id =\"usernameDiv\"></div>",'','','','','');
        $objTable->endRow();
        $usernameTable=$objTable->show();

        $objFieldset=new fieldset();
        $objFieldset->legend="<b>".$searchUsernameLabel."</b>";
        $objFieldset->contents=$usernameTable;
        $usernameFieldset=$objFieldset->show();

        $objInput=new textinput('firstname','','','30');
        $objInput->extra=' onkeyup="javascript:xajax_searchList(this.value,\'firstname\');"';
        $firstnameInput=$objInput->show();

        $objTable=new htmltable();
//        $objTable->cellspacing='2';
        $objTable->cellpadding='4';

        $objTable->startRow();
        $objTable->addCell($firstnameInput,'50%','','','','');
        $objTable->addCell("<div id =\"firstnameDiv\"></div>",'','','','','');
        $objTable->endRow();
        $firstnameTable=$objTable->show();

        $objFieldset=new fieldset();
        $objFieldset->legend="<b>".$searchNameLabel."</b>";
        $objFieldset->contents=$firstnameTable;
        $nameFieldset=$objFieldset->show();

        $objInput=new textinput('surname','','','30');
        $objInput->extra=' onkeyup="javascript:xajax_searchList(this.value,\'surname\');"';
        $surnameInput=$objInput->show();

        $objTable=new htmltable();
//        $objTable->cellspacing='2';
        $objTable->cellpadding='4';

        $objTable->startRow();
        $objTable->addCell($surnameInput,'50%','','','','');
        $objTable->addCell("<div id =\"surnameDiv\"></div>",'','','','','');
        $objTable->endRow();
        $surnameTable=$objTable->show();

        $objFieldset=new fieldset();
        $objFieldset->legend="<b>".$searchSurnameLabel."</b>";
        $objFieldset->contents=$surnameTable;
        $surnameFieldset=$objFieldset->show();

        $objTable=new htmltable();
//        $objTable->cellspacing='2';
        $objTable->cellpadding='4';

        $objTable->startRow();
        $objTable->addCell($usernameFieldset,'','','','','');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($nameFieldset,'','','','','');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($surnameFieldset,'','','','','');
        $objTable->endRow();
        $entryTable=$objTable->show();

        $objButton=new button('addbutton',$submitLabel);
        $objButton->setToSubmit();
        $buttons="<br />".$objButton->show();

        $objButton=new button('cancelbutton',$cancelLabel);
        $objButton->extra=' onclick="javascript:document.getElementById(\'input_cancelbutton\').value=\'Cancel\';document.getElementById(\'form_hiddenform\').submit();"';
        $buttons.="&nbsp;".$objButton->show();

    // set up form
        $objForm=new form('entryform',$this->uri(array('action'=>'submitentry','bookId'=>$bookId)));
        $objForm->addToForm($entryTable);
        $objForm->addToForm($buttons);
        $entryForm=$objForm->show();

    // hidden element
        $objInput=new textinput('cancelbutton','','hidden','');
        $hiddenInput=$objInput->show();

        $objForm=new form('hiddenform',$this->uri(array('action'=>'addressbook','bookId'=>$bookId)));
        $objForm->addToForm($hiddenInput);
        $hiddenForm=$objForm->show();

        $objTabbedbox=new tabbedbox();
        $objTabbedbox->addTabLabel($addEntryLabel);
        $objTabbedbox->addBoxContent($entryForm.$hiddenForm);
        $entryTab=$objTabbedbox->show();
        $pageData.=$entryTab;
    }

    // set up check all button
    $objButton=new button('checkallbutton',$selectallLabel);
    $objButton->setOnClick("javascript:SetAllCheckBoxes('sendform','userId[]',true);");
    $selectAllButton=$objButton->show();

    // set up uncheck all button
    $objButton=new button('uncheckallbutton',$deselectLabel);
    $objButton->setOnClick("javascript:SetAllCheckBoxes('sendform','userId[]',false);");
    $selectNoneButton=$objButton->show();

    // set up send button
    $objButton=new button('sendmail',$sendMailLabel);
    $objButton->setToSubmit();
    $sendButton=$objButton->show();

    $buttons=$selectAllButton."&nbsp;".$selectNoneButton."&nbsp;".$sendButton;

    $objTable=new htmltable();
//    $objTable->cellspacing='2';
    $objTable->cellpadding='4';
    $objTable->id="userListTable";
    $objTable->css_class="sorttable";
    $objTable->row_attributes=' name="row_'.$objTable->id.'"';

    $objTable->startRow();
    $objTable->addCell('','5%','','','heading','');
    $objTable->addCell($usernameLabel,'30%','','','heading','');
    $objTable->addCell($nameLabel,'30%','','','heading','');
    $objTable->addCell($surnameLabel,'','','','heading','');
    if(empty($contextCode)){
        $objTable->addCell('','10%','','','heading','');
    }
    $objTable->endRow();

    if(!empty($contextCode)){
        if(empty($arrContextUserList)){
            $objTable->startRow();
            $objTable->addCell($noEntriesLabel,'','','','noRecordsMessage','colspan="5"');
            $objTable->endRow();
        }else{
            foreach($arrContextUserList as $user){
                // set up checkbox
                $objCheck=new checkbox('userId[]');
                $objCheck->value=$user['userId'];
                $userCheck=$objCheck->show();

                $objTable->startRow();
                $objTable->addCell($userCheck,'','','center','','');
                $objTable->addCell($user['username'],'','','','','');
                $objTable->addCell(strtoupper($user['firstName']),'','','','','');
                $objTable->addCell(strtoupper($user['surname']),'','','','','');
                $objTable->endRow();
            }
        }
    }else{
        if(empty($arrBookEntryList)){
            $objTable->startRow();
            $objTable->addCell($noEntriesLabel,'','','','noRecordsMessage','colspan="5"');
            $objTable->endRow();
        }else{
            $i = 1;
            foreach($arrBookEntryList as $entry){

                // set up delete icon
                $deleteArray=array('action'=>'deleteentry','bookId'=>$bookId,'entryId'=>$entry['id']);
                $deleteIcon=$objIcon->getDeleteIconWithConfirm('',$deleteArray,'email',$confirmLabel);

                // set up checkbox
                $objCheck=new checkbox('userId[]');
                $objCheck->value=$entry['recipient_id'];
                $userCheck=$objCheck->show();

                $objTable->startRow();
                $objTable->addCell($userCheck,'','','center','','');
                $objTable->addCell($this->objUser->userName($entry['recipient_id']),'','','','','');
                $objTable->addCell(strtoupper($this->objUser->getFirstname($entry['recipient_id'])),'','','','','');
                $objTable->addCell(strtoupper($this->objUser->getSurname($entry['recipient_id'])),'','','','','');
                $objTable->addCell($deleteIcon,'','','center','','');
                $objTable->endRow();
            }
        }
    }
    $userTable=$objTable->show();

// set up form
    $objForm=new form('sendform',$this->uri(array('action'=>'compose')));
    if(!empty($arrContextUserList) || !empty($arrBookEntryList)){
        $objForm->addToForm($buttons);
    }
    $objForm->addToForm($userTable);
    if(!empty($arrContextUserList) || !empty($arrBookEntryList)){
        $objForm->addToForm($buttons);
    }
    $sendForm=$objForm->show();

    $objFieldset=new fieldset();
    $objFieldset->contents=$sendForm;
    $sendFieldset=$objFieldset->show();

    $pageData.=$sendFieldset;

// set up exit link
    $objLink=new link($this->uri(array('action'=>'manageaddressbooks'),'email'));
    $objLink->link=$backLabel;
    $pageData.="<b />".$objLink->show();

    $objLayer->padding='10px';
    $objLayer->addToStr($pageData);
    $pageLayer=$objLayer->show();
    echo $pageLayer;
?>