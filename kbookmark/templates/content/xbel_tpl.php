<?php

$status=$this->getParam('status');
$statusMsg=$this->getParam('title');

$this->loadClass('label','htmlelements');
$this->loadClass('form','htmlelements');
$this->loadClass('textinput','htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$xbelTitle=$this->objLanguage->languageText('mod_bookmark_xbel','kbookmark');
$xbelUpload=$this->objLanguage->languageText('mod_bookmark_xbelimportlabel','kbookmark');
$xbelView=$this->objLanguage->languageText('mod_bookmark_xbelexportlabel','kbookmark');
$xbelForm=new form('xbelForm');
$xbelAction=$this->uri(array('action'=>'xbelparse'));
$xbelForm->setAction($xbelAction);
$xbelForm->extra="enctype=\"multipart/form-data\"";
$xbelLabel=new label('', 'input_xbel');

//search term
$bkObject = new textinput('xbel');
$bkObject->size=30;
$bkObject->fldType="file";
$xbelForm->addToForm($xbelLabel->show());
$xbelForm->addToForm($bkObject->show());

$objButtonUpload = new button('upload');
$objButtonUpload ->setToSubmit();
$objButtonUpload ->setValue($this->objLanguage->languageText("mod_bookmark_xbelimport",'kbookmark'));
$xbelForm->addToForm("".$objButtonUpload ->show());

$objButtonView = new button('viewxbel');
$objButtonView ->setOnClick("window.location='".$this->uri(array('action'=>'viewxbel'))."'");
$objButtonView ->setValue($this->objLanguage->languageText("mod_bookmark_xbelexport",'kbookmark'));
//$xbelForm->addToForm("<br><hr><legend>".$xbelView."</legend></hr>".$objButtonView ->show());
$xbelForm->addToForm(" ".$xbelView." ".$objButtonView ->show());
$xbelForm=$xbelForm->show();
               
$xbelFieldset = $this->getObject('fieldset', 'htmlelements');
$xbelFieldset->setLegend($xbelTitle . $xbelUpload);
$xbelFieldset->addContent($xbelForm);
$xbelOutput= $xbelFieldset->show();       

$this->header = new htmlheading();
$this->header->type=1;

$this->header->str=$this->objLanguage->languageText('mod_bookmark_xbelmanage','kbookmark');
$text = '<p>The XML Bookmark Exchange Language, or XBEL, is an Internet "bookmarks" interchange format. It allows you to share bookmarks with various programs.<br /> For more information, visit: http://pyxml.sourceforge.net/topics/xbel/'.'<br /> Import allows you to load bookmarks from any other browser, as long as they are in a .xml file in DTD format.<br />Export allows you to create the DTD format files from the current bookmars.<br /> The output from export should be copied and saved in a text file, with the .xml extension.</p>';

$urlparse = $this->getObject('url', 'strings');

$text = $urlparse->makeClickableLinks($text);

echo $this->header->show();
echo $text ;
echo "<span class='confirm'>".$statusMsg."</span>";
echo $xbelOutput;

$link = $this->newObject('link','htmlelements');
$link->href = $this->uri(array('action'=>''));
$link->link=$this->objLanguage->languageText("word_back");
echo $link->show()

?>

