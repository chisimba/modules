<?php

/*template for adding past papers

*/
$content = "";
$this->loadClass('htmltable','htmlelements');
$heading = $this->getObject('htmlheading','htmlelements');
$this->loadClass('textinput','htmlelements');
$this->loadClass('dropdown','htmlelements');
$this->objPopupcal = &$this->getObject('datepickajax', 'popupcalendar');
$this->loadClass('button','htmlelements');

$form = $this->getObject('form','htmlelements');
$form->action = $this->uri(array('action'=>'savepaper'));
$form->extra ="enctype='multipart/form-data'";

$heading->str = $this->objLanguage->languageText('mod_pastpapers_addpastpaper','pastpapers')."&nbsp;".$this->_objDBContext->getTitle($this->_objDBContext->getContextCode());
$heading->align= "center";

$content .= $heading->show();

//fields to add to the form
$fileuploadlabel = $this->objLanguage->languageText('mod_pastpapers_uploadfile','pastpapers');
$uploadfield = new textinput('filename','','file');

$examtimelabel = $this->objLanguage->languageText('mod_pastpapers_examtime','pastpapers');
$defaultDate = date('Y-m-d');
$Date = $this->objPopupcal->show('date', 'no', 'yes',$defaultDate);

//filed for the topic field
$topicfield = new textinput('topic');

//button
$objButton = new button("save",$objLanguage->languageText("word_save"));   
$objButton->setToSubmit();

//create the table and fill it with the form items
$table = new htmltable();

$table->startRow();
$table->addCell($fileuploadlabel);
$table->addCell($uploadfield->show());
$table->endRow();

$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_pastpapers_topic','pastpapers'));
$table->addCell($topicfield->show());
$table->endRow();

$table->startRow();
$table->addCell($examtimelabel);
$table->addCell($Date);
$table->endRow();

$table->startRow();
$table->addCell("");
$table->addCell($objButton->show());
$table->endRow();


$form->addToform($table->show());

$content .= $form->show();
echo $content;

?>