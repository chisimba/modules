<?php

//Load all the required classes and objects
$this->loadClass('textarea','htmlelements');
$this->loadClass('textinput','htmlelements');
$this->loadClass('button','htmlelements');
$this->loadClass('form','htmlelements');
$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass("dropdown","htmlelements");
$this->loadClass('checkbox','htmlelements');
$this->loadClass('radio', 'htmlelements');

if (isset($listContent)) {
    foreach ($listContent as $line){
	    $id=$line["id"];
	    $title=htmlentities(stripslashes($line["title"]));
	    $url=$line["url"];
	    $description=htmlentities(stripslashes($line["description"]));
	    $isPrivate=$line["isprivate"];
	    $groupId=$line["groupid"];
    }
    $titleLine=$this->objLanguage->LanguageText('phrase_new_bookmark');
    $infoBookmark=$this->objLanguage->LanguageText('mod_bookmark_details','kbookmark');
    $bkForm= new form('addForm');
    $bkForm->setAction($this->uri(array('action'=>'save_edit','id'=>$this->getParam('id'),'item'=>$this->getParam('item'),'folderid'=>$this->getParam('folderId'))));
    $bkForm->setDisplayType(3);

    $tblclass=$this->newObject('htmltable','htmlelements');
    $tblclass->width='';
    $tblclass->cellspacing='0';
    $tblclass->cellpadding='5';
    $this->loadClass('textinput','htmlelements');

    //name of bookmark
    $bkObject = new textinput('title');
    //$bkObject->setValue("Name");
    $bkObject->size=30;
    $bkObject->fldType="text";
    $bkObject->setValue($title);
    
    $titleName = new label($objLanguage->languageText("mod_bookmark_name",'kbookmark'), 'input_title');    
    
    $row=array($titleName->show(),$bkObject->show());
    $tblclass->addRow($row);


    //The url of the bookmark
    $bkObject = new textinput('url');
    $bkObject->setValue($url);
    $bkObject->size=30;
    $bkObject->fldType="text";
    
    $titleLocation = new label($objLanguage->languageText("mod_bookmark_location",'kbookmark'), 'input_url');
    //$row=array("Location",$bkObject->show());
    $row=array($titleLocation->show(),$bkObject->show());
    $tblclass->addRow($row);

    //For a brief description of the bookmark

    $tArea=new textarea("description",'',3,30);
    $tArea->setContent($description);
    
    $titleDesc = new label($objLanguage->languageText("mod_bookmark_description",'kbookmark'), 'input_description');    
    
    //$row=array("Description",$tArea->show());
    $row=array($titleDesc->show(),$tArea->show());
    $tblclass->addRow($row);


    //Radio to determine if private or public
    $sharedText=$this->objLanguage->languageText('mod_bookmark_shared','kbookmark');
    $privateText=$this->objLanguage->languageText('mod_bookmark_private','kbookmark');
    $rButton=new radio("private");
    $rButton->addOption(1,$privateText);
    $rButton->addOption(0,$sharedText);
    $rButton->setSelected($isPrivate);

    $row=array('',$rButton->show());
    $tblclass->addRow($row);

    //Setting the folder_parent
    $this->loadClass("dropdown","htmlelements");
    $dropdown = new dropdown("parent");
    foreach($listFolders as $line){
        $dropdown->addOption($line["id"],$line["title"]);
    }
    $dropdown->setSelected($groupId);

    $titleFolder = new label($objLanguage->languageText("mod_bookmark_folder",'kbookmark'), 'input_parent');    
    
    //$row=array("Folder",$dropdown->show());
    $row=array($titleFolder->show(),$dropdown->show());
    $tblclass->addRow($row);
    //button to save
    $this->loadClass('button', 'htmlelements');
    $objElement = new button('submit');
    $objElement->setToSubmit();
    $objElement->setValue($objLanguage->languageText('word_save'));

    $objElement1 = new button('cancel');
    $objElement1->setToSubmit();
    $objElement1->setValue($objLanguage->languageText('word_cancel','faq'));

     $tblclass->startRow();
     $tblclass->addCell($objElement->show(), "", Null, 'center');
     $tblclass->addCell($objElement1->show(), "", Null, 'center');
     $tblclass->endRow();

     $bkForm->addToForm($tblclass->show());
     
     $bkObject = new textinput('id');
     $bkObject->setValue($id);
     $bkObject->size=30;
     $bkObject->fldType="hidden";
     $bkForm->addToForm($bkObject->show());
$this->header = new htmlheading();
$this->header->type=1;
$this->header->str=$this->objLanguage->languageText('mod_bookmark_bookmarkfolders','kbookmark')." > ".$title;

} else {
     foreach ($listFolders as $line) {
         $id=$line['id'];
         $title=htmlentities(stripslashes($line['title']));
         $description=htmlentities(stripslashes($line['description']));
         $isPrivate=$line['isprivate'];
     }
     $titleLine=$this->objLanguage->LanguageText('phrase_edit_folder');
     $myFormAction=$this->uri(array('action'=>'save_edit','id'=>$this->getParam('id'),'item'=>$this->getParam('item')));
     $this->bkContent= & $this->newObject('layer','htmlelements');
     $this->bkContent->id="Bookmark";

     $this->loadClass('form','htmlelements');
     $bkForm= new form('bkform');
     $bkForm->setAction($myFormAction);

     $tblclass=$this->newObject('htmltable','htmlelements');
     $tblclass->width='';
     $tblclass->attributes="  border='0'";
     $tblclass->cellspacing='0';
     $tblclass->cellpadding='5';


     //$this->loadClass('textinput','htmlelements');


     //$tblclass->addRow($bkObject->show(),'even');

     //name of bookmark
     $bkObject = new textinput('title');     
     $bkObject->size=30;
     $bkObject->fldType="text";
     $bkObject->setValue($title);
     
     $titleName = new label($objLanguage->languageText("mod_bookmark_name",'kbookmark'), 'input_title');

     $row=array($titleName->show(),$bkObject->show());
     $tblclass->addRow($row);


     $this->loadClass('textarea','htmlelements');
     $tArea=new textarea("description",'',3,30);
     $tArea->setContent($description);
     
     $titleDesc = new label($objLanguage->languageText("mod_bookmark_description",'kbookmark'), 'input_description');     
     $row=array($titleDesc->show(),$tArea->show());
     $tblclass->addRow($row);


     //Radio to determine if private or public
    $sharedText=$this->objLanguage->languageText('mod_bookmark_shared','kbookmark');
    $privateText=$this->objLanguage->languageText('mod_bookmark_private','kbookmark');
     $this->loadClass('radio', 'htmlelements');
     $rButton=new radio("isprivate");
     $rButton->addOption(1,$privateText);
     $rButton->addOption(0,$sharedText);
     $rButton->setSelected($isPrivate);

     $row=array("",$rButton->show());
     $tblclass->addRow($row);

     //button to save
     $this->loadClass('button', 'htmlelements');
     $objElement = new button('submit');
     $objElement->setToSubmit();
     $objElement->setValue($this->objLanguage->languageText('word_save'));
     
     $objElement1= new button('cancel');
     $objElement1->setToSubmit();
     $objElement1->setValue($this->objLanguage->languageText('word_cancel','faq'));

     $tblclass->startRow();
     $tblclass->addCell($objElement->show()." &nbsp;&nbsp;&nbsp;&nbsp; ".$objElement1->show(), "", Null, 'center', NULL, 'colspan="2"');
     $tblclass->endRow();

     $bkObject = new textinput('folder');
     $bkObject->setValue("1");
     $bkObject->size=30;
     $bkObject->fldType="hidden";
     $bkForm->addToForm($bkObject->show());

     $bkForm->addToForm($tblclass->show());

     $bkObject = new textinput('id');
     $bkObject->setValue($id);
     $bkObject->size=30;
     $bkObject->fldType="hidden";
     $bkForm->addToForm($bkObject->show());

    $this->header = new htmlheading();
    $this->header->type=1;
    $this->header->str=$titleLine." > ".$title;


     //hidden field to distinguish between a favourite and a folder
}
echo $this->header->show();
echo $bkForm->show();

?>
