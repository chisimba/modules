<?php
	
	/**
	* Template to create rooms
	* 
	*/
	
	//table to hold elements
	
	$table =& $this->newObject('htmltable','htmlelements');
	
	//radio buttons
	$this->loadClass('radio','htmlelements');
	
	//textinput
	$this->loadClass('textinput','htmlelements');
	
	//textarea
	
	$this->loadClass('textarea','htmlelements');
	
	$this->loadClass('form','htmlelements');
	
	
	
	
	$this->objHeading->type = 2;
	$this->objHeading->str = $this->objLanguage->languageText('mod_ajaxchat_createroom');
	
	echo $this->objHeading->show();
	
	
	
	
	
	
	//name for chat room
	$table->startRow();
	
	$table->addCell($this->objLanguage->languageText('word_name').':','15%');
	
	$name = new textinput('name','',4,50);
	$table->addCell($name->show());
	
	$table->endRow();
	
	
	//type of chat room
	$radio = new radio('type');
	$radio->addOption('personal',$this->objLanguage->languageText('word_personal'));
	$radio->addOption('context',$this->objLanguage->languageText('word_context'));	
	$radio->setselected('personal');
	
	$table->startRow();
	$table->addCell($this->objLanguage->languageText('phrase_typeroom').':','15%');
	$table->addCell($radio->show());
	$table->endRow();
	
	
	
	//description of the chat room
	
	$table->startRow();
	
	$table->addCell($this->objLanguage->languageText('word_description').':','15%');
	
	$description = new textarea('description');
	
	$table->addCell($description->show());
	
	$table->endRow();
	
	
	//buttons
	$table->startRow();
	
	//cancel all operations
	$objCancel = new button('cancel');
	
	$objCancel->setOnClick("window.location='".$this->uri(null)."';");
	$objCancel->setValue(' ' . $this->objLanguage->languageText("word_close"));
	
	$this->objButton->setValue($this->objLanguage->languageText('word_submit'));
	$this->objButton->setToSubmit();
	
	$table->addCell('');
	
	$buttons = $this->objButton->show(). ' / '.$objCancel->show();
	$table->addCell($buttons);
	
	
	$table->endRow();
	
	//submit button
	
	$table->startRow();
	
	$form = new form('create_room',$this->uri(array('action'=>'create_room')));
	$form->addToForm($table->show());
	echo $form->show();
	
?>
