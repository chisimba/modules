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

//    $this->setLayoutTemplate('layout_tpl.php');

// set up html elements
    $objHeader=&$this->newObject('htmlheading','htmlelements');
    $objTable=&$this->newObject('htmltable','htmlelements');
    $objIcon=&$this->newObject('geticon','htmlelements');
    $objInput=&$this->newObject('textinput','htmlelements');
    $objButton=&$this->newObject('button','htmlelements');
    $objForm=&$this->newObject('form','htmlelements');
    $objLink=&$this->newObject('link','htmlelements');
    $objLayer=&$this->newObject('layer','htmlelements');
    $objFieldset=&$this->newObject('fieldset','htmlelements');

// set up language items
    $heading=$this->objLanguage->languageText('mod_email_managefolders','email');
    $backLabel=$this->objLanguage->languageText('word_back');
    $folderListLabel=$this->objLanguage->languageText('mod_email_folderlist','email');
    $addFolderLabel=$this->objLanguage->languageText('mod_email_addfolder','email');
    $editFolderLabel=$this->objLanguage->languageText('mod_email_editfolder','email');
    $deleteFolderLabel=$this->objLanguage->languageText('mod_email_deletefolder','email');
    $folderLabel=$this->objLanguage->languageText('word_folder');
    $readLabel=$this->objLanguage->languageText('word_read');
    $unreadLabel=$this->objLanguage->languageText('word_unread');
    $totalLabel=$this->objLanguage->languageText('word_total');
    $submitLabel=$this->objLanguage->languageText('word_submit');
    $cancelLabel=$this->objLanguage->languageText('word_cancel');
    $confirmLabel=$this->objLanguage->languageText('mod_email_confirmfolder','email');
    $requiredLabel=$this->objLanguage->languageText('mod_email_requiredfolder','email');

// set up add icon
    $objIcon->title=$addFolderLabel;
    $addIcon=$objIcon->getLinkedIcon($this->uri(array('action'=>'managefolders','mode'=>'add','currentFolderId'=>$currentFolderId)),'add');

// set up heading
    $objHeader->str=$heading."&nbsp;".$addIcon;
    $objHeader->type=1;
    $pageData=$objHeader->show();

// set up htmlelements
    $objInput=new textinput('folderName','','','60');
    $objInput->extra='MAXLENGTH="50"';
    $folderNameInput=$objInput->show();

// set up folders table
    $objTable=new htmltable();
//    $objTable->cellspacing='2';
    $objTable->cellpadding='4';

    $objTable->startRow();
    $objTable->addCell("<b>".$folderLabel."</b>",'50%','','','heading','');
    $objTable->addCell("<b>".$unreadLabel."</b>",'20%','','','heading','');
    $objTable->addCell("<b>".$totalLabel."</b>",'20%','','','heading','');
    $objTable->addCell('','10%','','','heading','');
    $objTable->endRow();

    foreach($arrFolderList as $folder){
        // set up edit icon
        if($folder['user_id']!='system'){
            $objIcon->title=$editFolderLabel;
            $editIcon=$objIcon->getEditIcon($this->uri(array('action'=>'managefolders','mode'=>'edit','folderId'=>$folder['id'],'currentFolderId'=>$currentFolderId)));
        }else{
            $editIcon='';
        }

        // set up edit input
        if($mode=='edit' && $folder['id']==$folderId){
            $objInput=new textinput('folderId',$folderId,'hidden','');
            $folderName=$objInput->show();

            $objInput=new textinput('folderName',$folder['folder_name'],'','60');
            $objInput->extra='MAXLENGHTH="50"';
            $folderName.=$objInput->show();
        }else{
            $folderName=$folder['folder_name'];
        }

        // set up delete icon
        if($folder['user_id']!='system'){
            $deleteArray=array('action'=>'managefolders','mode'=>'delete','folderId'=>$folder['id'],'currentFolderId'=>$currentFolderId);
            $deleteIcon=$objIcon->getDeleteIconWithConfirm('',$deleteArray,'email',$confirmLabel);
        }else{
            $deleteIcon='';
        }

        if($folder['unreadmail']!=0){
            $unreadMail="<font class='error'><b>".$folder['unreadmail']."</b></font>";
        }else{
            $unreadMail=$folder['unreadmail'];
        }

        $objTable->startRow();
        $objTable->addCell($folderName,'','','','','');
        $objTable->addCell($unreadMail,'','','right','','');
        $objTable->addCell($folder['allmail'],'','','right','','');
        $objTable->addCell($editIcon."&nbsp;".$deleteIcon,'','','center','','');
        $objTable->endRow();
    }
    if($mode=='add'){
        $objTable->startRow();
        $objTable->addCell($folderNameInput,'','','','odd','');
        $objTable->addCell('','','','right','odd','');
        $objTable->addCell('','','','right','odd','');
        $objTable->addCell('','','','right','odd','');
        $objTable->endRow();
    }
    $folderTable=$objTable->show();

    $objFieldset=new fieldset();
    $objFieldset->contents=$folderTable;
    $folderFieldset=$objFieldset->show();

// set up buttons
    if($mode=='add'){
        $objButton=new button('addbutton',$submitLabel);
        $objButton->setToSubmit();
        $buttons="<br />".$objButton->show();

        $objButton=new button('cancelbutton',$cancelLabel);
        $objButton->extra=' onclick="javascript:document.getElementById(\'input_cancelbutton\').value=\'Cancel\';document.getElementById(\'form_hiddenform\').submit();"';
        $buttons.="&nbsp;".$objButton->show();
    }elseif($mode=='edit'){
        $objButton=new button('editbutton',$submitLabel);
        $objButton->setToSubmit();
        $buttons="<br />".$objButton->show();

        $objButton=new button('cancelbutton',$cancelLabel);
        $objButton->extra=' onclick="javascript:document.getElementById(\'input_cancelbutton\').value=\'Cancel\';document.getElementById(\'form_hiddenform\').submit();"';
        $buttons.="&nbsp;".$objButton->show();
    }else{
        $buttons='';
    }

// set up form
    $objForm=new form('folderform',$this->uri(array('action'=>'managefolders','currentFolderId'=>$currentFolderId)));
    $objForm->addToForm($folderFieldset);
    $objForm->addToForm($buttons);
    if($mode=='add' || $mode=='edit'){
        $objForm->addRule('folderName',$requiredLabel,'required');
    }
    $folderForm=$objForm->show();

// hidden element
    $objInput=new textinput('cancelbutton','','hidden','');
    $hiddenInput=$objInput->show();

    $objForm=new form('hiddenform',$this->uri(array('action'=>'managefolders','currentFolderId'=>$currentFolderId)));
    $objForm->addToForm($hiddenInput);
    $hiddenForm=$objForm->show();
    $pageData.=$folderForm.$hiddenForm;

// set up exit link
    $objLink=new link($this->uri(array('action'=>'gotofolder','folderId'=>$currentFolderId)),'email');
    $objLink->link=$backLabel;
    $pageData.="<br />".$objLink->show();

    $objLayer->padding='10px';
    $objLayer->addToStr($pageData);
    $pageLayer=$objLayer->show();
    echo $pageLayer;
?>