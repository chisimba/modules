<?php
/**
 * Provides a form to enable the addition of a new
 * bookmark into the user's bookmarks
 * @author James Kariuki Njenga
 * @version $Id$
 * @copyright 2005, University of the Western Cape & AVOIR Project
 * @license GNU GPL
 * @package context
*/

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
$this->loadClass('label', 'htmlelements');


//add the form
//get the the action
if($this->getParam('action')=="edit") {
    $action='save_edit';
} else {
    $action='save_add';
}
$item=$this->getParam('item');
if ($item=='bookmark') {

    $headerString = $this->objLanguage->LanguageText('mod_bookmark_addbookmark','kbookmark');

    $titleLine=$this->objLanguage->LanguageText('phrase_new_bookmark');
    $infoBookmark=$this->objLanguage->LanguageText('mod_bookmark_addbookmark','kbookmark');
    $bkForm= new form('addForm');
    $bkForm->setAction($this->uri(array('action'=>$action,'id'=>$this->getParam('id'),'item'=>$this->getParam('item'))));
    $bkForm->setDisplayType(3);

    $tblclass=$this->newObject('htmltable','htmlelements');
    $tblclass->width='';
    $tblclass->cellspacing='0';
    $tblclass->cellpadding='5';


    $this->loadClass('textinput','htmlelements');


    $titleLabel=new label($this->objLanguage->languageText('mod_bookmark_name','kbookmark').':', 'input_title');

    //name of bookmark
    $bkObject = new textinput('title');
    //$bkObject->setValue("Name");
    $bkObject->size=30;
    $bkObject->fldType="text";

    $row=array($titleLabel->show(),$bkObject->show());
    $tblclass->addRow($row);


    //The url of the bookmark
    $urlLabel=new label($this->objLanguage->languageText('mod_bookmark_location','kbookmark').':', 'input_url');
    $bkObject = new textinput('url');
    $bkObject->setValue("http://");
    $bkObject->size=30;
    $bkObject->fldType="text";

    $row=array($urlLabel->show(),$bkObject->show());
    $tblclass->addRow($row);

    //For a brief description of the bookmark
    $descLabel=new label($this->objLanguage->languageText('mod_bookmark_description','kbookmark').':', 'input_description');
    $tArea=new textarea("description",'',3,30);
    //$tArea->setContent("Brief description");

    $row=array($descLabel->show(),$tArea->show());
    $tblclass->addRow($row);


    //Radio to determine if private or public
    $sharedText=$this->objLanguage->languageText('mod_bookmark_shared','kbookmark');
    $privateText=$this->objLanguage->languageText('mod_bookmark_private','kbookmark');
    
    $rButton=new radio("private");
    $rButton->addOption(1,$privateText);
    $rButton->addOption(0,$sharedText);
    $rButton->setSelected(0);

    $row=array('',$rButton->show());
    $tblclass->addRow($row);

    //Setting the folder_parent
    
    $this->loadClass("dropdown","htmlelements");
    $parentLabel=new label($this->objLanguage->languageText('mod_bookmark_folder','kbookmark').':', 'input_parent');
    $dropdown = new dropdown("parent");
    foreach($listFolders as $line){
        $dropdown->addOption($line["id"],$line["title"]);
    }
    $folderId=$this->getParam('folderId');
    if (isset($folderId)){
        $dropdown->setSelected($folderId);
    }
    $row=array($parentLabel->show(),$dropdown->show());
    $tblclass->addRow($row);
    //button to save
    $this->loadClass('button', 'htmlelements');
    $objElement = new button('submit');
    $objElement->setToSubmit();
    $objElement->setValue($this->objLanguage->languageText('word_save'));

    $objElement1 = new button('cancel');
    $objElement1->setToSubmit();
    $objElement1->setValue($this->objLanguage->languageText('word_cancel'));
    $cancelButton=$objElement1->show();

   /** $cancelButton="<a href=\"". $this->uri(array(
	    	'module'=>'kbookmark',
	   		'action'=>'',
			'folderId'=>$folderId
		))
		. "\" class=pseudobutton>" . $objLanguage->languageText("word_cancel") . "</a>"; */

     $tblclass->startRow();
     $tblclass->addCell($objElement->show()." &nbsp;&nbsp;&nbsp;&nbsp; ".$cancelButton, "", Null, 'center', '','colspan="2"');
     //$tblclass->addCell($objElement->show(), "", Null, 'center','', 'colspan="2"');
     //$tblclass->addCell($cancelButton, "", Null, 'center', 'heading');
     $tblclass->endRow();

     $bkForm->addToForm($tblclass->show());
     $newform->displayType=3;

     //print $bkForm->show();
} else {
    //add folder
     $headerString=$this->objLanguage->LanguageText('mod_bookmark_addfolder','kbookmark');
     $myFormAction=$this->uri(array('action'=>$action,'id'=>$this->getParam('id'),'item'=>$this->getParam('item')));
     $this->bkContent= & $this->newObject('layer','htmlelements');
     $this->bkContent->id="Bookmark";

     $this->loadClass('form','htmlelements');
     $bkForm= new form('bkform');
     $bkForm->setAction($myFormAction);

     $tblclass=$this->newObject('htmltable','htmlelements');
     $tblclass->width='';
     $tblclass->cellspacing='0';
     $tblclass->cellpadding='5';

     $this->loadClass('textinput','htmlelements');


     //$tblclass->addRow($bkObject->show(),'even');

     //name of bookmark
     $titleLabel=new label($this->objLanguage->languageText('mod_bookmark_name','kbookmark').':', 'input_title');
     $bkObject = new textinput('title');
     //$bkObject->setValue("Name");
     $bkObject->size=30;
     $bkObject->fldType="text";

     $row=array($titleLabel->show(),$bkObject->show());
     $tblclass->addRow($row);


     $this->loadClass('textarea','htmlelements');
     $descLabel=new label($this->objLanguage->languageText('mod_bookmark_description','kbookmark').':', 'input_description');
     $tArea=new textarea("description",'',3,30);
     //$tArea->setContent("Brief description");

     $row=array($descLabel->show(),$tArea->show());
     $tblclass->addRow($row);


     //Radio to determine if private or public
     $sharedText=$this->objLanguage->languageText('mod_bookmark_shared','kbookmark');
     $privateText=$this->objLanguage->languageText('mod_bookmark_private','kbookmark');
     $this->loadClass('radio', 'htmlelements');
     
     $rButton=new radio("private");
     $rButton->addOption(1,$privateText);
     $rButton->addOption(0,$sharedText);
     $rButton->setSelected(1);

     $row=array('',$rButton->show());
     $tblclass->addRow($row);

     //button to save
     $this->loadClass('button', 'htmlelements');
     $objElement = new button('submit');
     $objElement->setToSubmit();
     $objElement->setValue($this->objLanguage->languageText('word_save'));
     



    /** $cancelButton="<a href=\"". $this->uri(array(
	    	'module'=>'kbookmark',
	   		'action'=>''
		))
		. "\" class=pseudobutton>" . $objLanguage->languageText("word_cancel") . "</a>";
     */
     $objElement1 = new button('cancel',$this->objLanguage->languageText('word_cancel'));
     $returnUrl=$this->uri(array('action'=>'options'));
     $objElement1->setOnClick("window.location='$returnUrl'");
     $cancelButton=$objElement1->show();
     $tblclass->startRow();
     //$tblclass->addCell($objElement->show()." / ".$cancelButton, "", Null, 'center', 'heading', 'colspan="2"');
     $tblclass->addCell($objElement->show()."   ".$cancelButton, "", Null, 'center', '', 'colspan="2"');
     //$tblclass->addCell($cancelButton, "", Null, 'center', 'heading', '');
     $tblclass->endRow();

     $bkObject = new textinput('folder');
     $bkObject->setValue("1");
     $bkObject->size=30;
     $bkObject->fldType="hidden";
     $bkForm->addToForm($bkObject->show());

     $bkForm->addToForm($tblclass->show());


     //hidden field to distinguish between a favourite and a folder

     
}


//put the heading
$this->header = new htmlheading();

$this->header->type=1;
$this->header->str=$headerString;
echo $this->header->show();

print $bkForm->show();

?>

