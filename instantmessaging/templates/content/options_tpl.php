<?php
	$this->loadClass('form','htmlelements');
	$this->loadClass('checkbox','htmlelements');
	$this->loadClass('button','htmlelements');
	$objForm = new form('options',$this->uri(array('action'=>'optionsConfirm')));
	$objForm->displayType = 3;
	$objFieldset =& $this->getObject('fieldsetex','htmlelements');
	$objFieldset->setLegend($this->objLanguage->languageText('mod_instantmessaging_options','instantmessaging'));
	$objCheckbox = new checkbox('notifylogin',null,$notifylogin);
	$objFieldset->addLabelledField($objCheckbox->show(),$this->objLanguage->languageText('mod_instantmessaging_notifymelogin','instantmessaging'));
	$objCheckbox = new checkbox('notifyreceive',null,$notifyreceive);
	$objFieldset->addLabelledField($objCheckbox->show(),$this->objLanguage->languageText('mod_instantmessaging_notifymereceive','instantmessaging'));
	$objForm->addToForm($objFieldset);
	$objButton = new button('submit',$this->objLanguage->languageText('word_save'));
	$objButton->setToSubmit();
	$objForm->addToForm($objButton);	
	echo $objForm->show();
?>