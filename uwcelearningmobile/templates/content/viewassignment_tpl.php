<?php
//Assignment spec template
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	    die("You cannot view this page directly");
}
	$this->loadClass('link', 'htmlelements');
	$this->loadClass('fieldset','htmlelements');
	$table = $this->newObject('htmltable', 'htmlelements');
	$objFields = new fieldset();
	$objWashout = $this->getObject('washout', 'utilities');
	$objFields->setLegend('<b>'.$assignment['name'].'</b>');
	$table->startRow();
	$table->addCell('<strong>'.$this->objLanguage->languageText('word_description', 'system', 'Description').'</strong>', 130);
	$table->endRow();
	$table->startRow();
	$table->addCell($objWashout->parseText($assignment['description']), NULL, NULL, NULL, NULL, ' colspan="3"');
	$table->endRow();

	$table->startRow();
	$table->addCell('<strong>'.$this->objLanguage->code2Txt('mod_assignment_lecturer', 'assignment', NULL, '[-author-]').':</strong>', 130);
	$table->addCell($this->objUser->fullName($assignment['userid']));
	$table->endRow();

	$table->startRow();
	$table->addCell('<strong>'.$this->objLanguage->languageText('mod_assignment_openingdate', 'assignment', 'Opening Date').'</strong>', 130);
	$table->addCell($this->objDate->formatDate($assignment['opening_date']));
	$table->endRow();

	$table->startRow();
	$table->addCell('<strong>'.$this->objLanguage->languageText('mod_assignment_closingdate', 'assignment', 'Closing Date').'</strong>', 130);
	$table->addCell($this->objDate->formatDate($assignment['closing_date']));
	$table->endRow();

	$table->startRow();
	$table->addCell('<strong>'.$this->objLanguage->languageText('mod_worksheet_totalmark', 'worksheet', 'Total Mark').'</strong>', 130);
	$table->addCell($assignment['mark']);
	$table->endRow();

	$table->startRow();
	$table->addCell('<strong>'.$this->objLanguage->languageText('mod_assignment_percentyrmark', 'assignment', 'Percentage of year mark').':</strong>', 200, NULL, NULL, 'nowrap');
	$table->addCell($assignment['percentage'].'%');
	$table->endRow();

	$table->startRow();
	$table->addCell('<strong>'.$this->objLanguage->languageText('mod_assignment_assignmenttype', 'assignment', 'Assignment Type').'</strong>', 130);
	if ($assignment['format'] == '0') {
		$table->addCell($this->objLanguage->languageText('mod_assignment_online', 'assignment', 'Online'));
	} else {
		$table->addCell($this->objLanguage->languageText('mod_assignment_upload', 'assignment', 'Upload'));
	}
	$table->endRow();

	$objFields->addContent($table->show());
	echo '<br>'.$objFields->show();
	
	$homeLink = new link($this->URI(array()));
	$homeLink->link = 'Home';

	$backLink = new link($this->URI(array('action' => 'context', 'contextcode' => $this->contextCode)));
	$backLink->link = 'Back to Course';
	echo $homeLink->show().'/'.$backLink->show().'</br>';
?>
