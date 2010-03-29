<?php
//Forum list template
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	    die("You cannot view this page directly");
}
	$this->loadClass('link', 'htmlelements');
	$objTableClass = $this->newObject('htmltable', 'htmlelements');
	$objTableClass->startHeaderRow();
	$objTableClass->addHeaderCell('<strong>'.$this->objLanguage->languageText('mod_uwcelearningmobile_wordtopic', 'uwcelearningmobile').'</strong>', '40%');
	$objTableClass->addHeaderCell('<strong>'.$this->objLanguage->languageText('mod_uwcelearningmobile_wordforum', 'uwcelearningmobile').'</strong>', '40%');
	$objTableClass->endHeaderRow();
	
	if (!empty($forums)) {		
		foreach($forums as $forum) { 
			$objTableClass->startRow();
			$link = new link($this->URI(array('action' => 'viewforum', 'id' => $forum['forum_id'])));
			$link->link = $forum['forum_name'];
			$objTableClass->addCell($forum['topics'], '', 'center', 'left', $class);
			$objTableClass->addCell($link->show(), '', 'center', 'left', $class);		
		}
	}
	else
	{
		$norecords = $this->objLanguage->languageText('mod_uwcelearningmobile_wordnoforum', 'uwcelearningmobile');
		$objTableClass->addCell($norecords, NULL, NULL, 'center', 'noRecordsMessage', 'colspan="7"');	
	}
	echo $objTableClass->show().'</br>';
	
	$homeLink = new link($this->URI(array()));
	$homeLink->link = 'Home';

	$backLink = new link($this->URI(array('action' => 'context', 'contextcode' => $this->contextCode)));
	$backLink->link = 'Back to Course';
	echo $homeLink->show().'/'.$backLink->show().'</br>';
?>
