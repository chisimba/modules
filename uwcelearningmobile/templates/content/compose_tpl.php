<?php
//Read mail tamplate
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	    die("You cannot view this page directly");
}
	$this->loadClass('fieldset','htmlelements');
	$this->loadClass('htmltable', 'htmlelements');
	$this->loadClass('link', 'htmlelements');
	$this->loadClass('textinput', 'htmlelements');
	$this->loadClass('textarea', 'htmlelements');
	$washer = $this->getObject('washout', 'utilities');
	$objTable = new htmltable();
	$objFields = new fieldset();
	$objFields->setLegend('<b>'.$this->objLanguage->languageText('mod_internalmail_compose', 'internalmail').'</b>');
	
	$composeform = new form('uwcelearningmobile', $this->uri(array(
	    'action' => 'sendmail',
		'userid' => $recipientList
	)));

	$reFields = new fieldset();
	$reFields->setLegend('<b>'.$this->objLanguage->languageText('word_to', 'system').':</b>');
	$reFields->addContent($toList);
	$objFields->addContent($reFields->show());

	$txtsubject = new textinput('subject');
	if (isset($subject)) {
	    $txtsubject->value = $subject;
	}

	$txtmessage = new textarea('message', $message, '', '');
	
	$objTable->startRow();
    $objTable->addCell($this->objLanguage->languageText('word_subject', 'system').':', '', '', '', '', '');
	$objTable->endRow();
	
	$objTable->startRow();
    $objTable->addCell($txtsubject->show(), '', '', '', '', '');
	$objTable->endRow();

	$objTable->startRow();
    $objTable->addCell($this->objLanguage->languageText('word_message', 'system').':', '', '', '', '', '');
	$objTable->endRow();

	$objTable->startRow();
    $objTable->addCell($txtmessage->show(), '', '', '', '', '');
	$objTable->endRow();
	
	$objconvButton = new button($this->objLanguage->languageText('word_send', 'system'));
	$objconvButton->setValue($this->objLanguage->languageText('word_send', 'system'));
	$objconvButton->setToSubmit();

	$objTable->startRow();
    $objTable->addCell($objconvButton->show(), '', '', '', '', '');
	$objTable->endRow();

	$objFields->addContent($objTable->show());
	
	$composeform->addToForm($objFields->show());
		
	$cform = $composeform->show();
	echo $cform;

	$backLink = new link($this->URI(array('action' => 'internalmail')));
	$backLink->link = $this->objLanguage->languageText('mod_uwcelearningmobile_wordbacktomail', 'uwcelearningmobile');
	echo '<br/>'.$this->homeAndBackLink.' - '.$backLink->show().'</br>';
?>
