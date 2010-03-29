<?php
//Assignment list template
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	    die("You cannot view this page directly");
}
	$this->loadClass('link', 'htmlelements');
	$objTableClass = $this->newObject('htmltable', 'htmlelements');
	$objTableClass->startHeaderRow();	
	$objTableClass->addHeaderCell('<strong>'.$this->objLanguage->languageText('mod_uwcelearningmobile_wordtitle', 'uwcelearningmobile').'</strong>', '40%');
	$objTableClass->addHeaderCell('<strong>'.$this->objLanguage->languageText('mod_uwcelearningmobile_wordduedate', 'uwcelearningmobile').'</strong>', '40%');
	$objTableClass->endHeaderRow();
	
	if (!empty($assignments)) {	
		foreach($assignments as $assignment) { 
			$objTableClass->startRow();
			$link = new link($this->URI(array('action' => 'viewassignment', 'id' => $assignment['id'])));
			$link->link = $assignment['name'];
			$objTableClass->addCell($link->show(), '', 'center', 'left', $class);
			$objTableClass->addCell($this->objDate->formatDate($assignment['closing_date']), '', 'center', 'left', $class);
					
		}
	}
	else
	{
		$norecords = $this->objLanguage->languageText('mod_uwcelearningmobile_wordnoass', 'uwcelearningmobile');
		$objTableClass->addCell($norecords, NULL, NULL, 'center', 'noRecordsMessage', 'colspan="7"');	
	}
	echo $objTableClass->show().'</br>';
	
	$homeLink = new link($this->URI(array()));
	$homeLink->link = 'Home';

	$backLink = new link($this->URI(array('action' => 'context', 'contextcode' => $this->contextCode)));
	$backLink->link = 'Back to Course';
	echo $homeLink->show().'/'.$backLink->show().'</br>';
?>
