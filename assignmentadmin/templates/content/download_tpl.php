<?php
/**
* Empty template for downloading files.
* @package assignmentadmin
*/

/**
* This acts as a placeholder for the download template page
*/
// set up html elements 
$this->loadClass('textinput','htmlelements');
$this->loadClass('textarea','htmlelements');
$this->loadClass('radio','htmlelements');
$this->loadClass('button','htmlelements');
$this->loadClass('link','htmlelements');
$this->loadClass('label','htmlelements');
$this->loadClass('layer','htmlelements');
$this->loadClass('form','htmlelements');
$this->loadClass('htmltable','htmlelements');
$this->loadClass('dropdown','htmlelements');
$objIcon = $this->newObject('geticon','htmlelements');
//$objLink = $this->newObject('link','htmlelements');

$objForm = new form('upload',$this->uri(array('action' =>'uploadsubmit')));
$objForm->extra = " enctype = 'multipart/form-data'";
$objSelectFile = $this->newObject('selectfile','filemanager');
$objFile = $this->getObject('dbfile', 'filemanager');
//echo $objFile->getFileName($this->getParam('fileid'));
//echo $objFile->getFileSize($this->getParam('fileid'));
//echo $objFile->getFilePath($this->getParam('fileid'));
//echo $objFile->getFullFilePath($this->getParam('fileid'));
$filepath = "http://localhost/chisimba_framework/app/";
$filepath .= $objFile->getFilePath($this->getParam('fileid'));
        
       


	
	$objForm->addToForm($objSelectFile->show());
	$btnsubmit = 'asdads';
	// Save & exit buttons
	$objButton = new button('save',$btnsubmit);
	$objButton->setToSubmit();
	$btns = $objButton->show().'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	$objForm->addToForm($btns);

//echo $objForm->show();
// set up linked alphabet
       

 	$objLink = new link();
    	$objLink->link = "Scorchme";
$objLink->href = $filepath;
echo $objLink->show();
?>
