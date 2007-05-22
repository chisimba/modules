<?php
$form=&$this->newObject('form','htmlelements');
$form->setAction($this->uri(array('action'=>'save')));
$form->setDisplayType(3);
$form->extra=' enctype="multipart/form-data" ';

$h3=$this->newObject('htmlheading','htmlelements');
$h3->str=$this->objLanguage->languageText("mod_freemind_upload", "freemind");

$file=&$this->newObject('textinput','htmlelements');
$file->fldType='file';
$file->name='mapupload';

$submit=&$this->newObject('navbuttons','navigation');

$objSelectFile = $this->newObject('selectfile', 'filemanager');

$objSelectFile->name = 'mapupload';



$form->addToForm($h3);
$form->addToForm($objSelectFile->show());
//$form->addToForm($file);
$form->addToForm('<br/>'.$submit->putSaveButton());
		
$this->contentNav = $this->getObject('layer','htmlelements');
$this->contentNav->id = "content";
$this->contentNav->str = $form->show();
echo $this->contentNav->addToLayer();

?>