<?php
//MCQ Tests template
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	    die("You cannot view this page directly");
}
	$this->loadClass('fieldset','htmlelements');
	$this->loadClass('link','htmlelements');
	
	echo '<b>'.$this->objLanguage->languageText('mod_uwcelearningmobile_wordcourse', 'uwcelearningmobile').': </b>'.$this->contextTitle;

	$objFields = new fieldset();
	$objFields->setLegend('<b>'.$this->objLanguage->languageText('mod_uwcelearningmobile_wordtests', 'uwcelearningmobile').'</b>');

	if (!empty($tests)) {
		$objTableClass = $this->newObject('htmltable', 'htmlelements');
		foreach($tests as $test) { 
			if($test['status'] == 'open')
			{
				$objTableClass->startRow();
		
				//add test name
				/*$link = '<a href="#"  onclick="javascript:window.open(\''.$this->URI(array(), 'mcqtests').'&amp;action=answertest&amp;id='.$tests['id'].'&amp;mode=notoolbar\', \'showtest\', \'fullscreen,scrollbars\')">'.$test['name'].'</a>';*/
				$link = $test['name'];
				$objTableClass->addCell($link, '', 'center', 'left', $class);
				
				//add due date
				$objTableClass->addCell(''.$this->objDate->formatDate($test['closingdate']), '', 'center', 'left', $class);
			}
		}$objFields->addContent($objTableClass->show().'</br>');
	}
	else {
		$norec = $this->objLanguage->languageText('mod_uwcelearningmobile_wordnotest', 'uwcelearningmobile');
		$objFields->addContent('<b>'.$norec.'</b>');
	}
	echo $objFields->show();

	$homeLink = new link($this->URI(array()));
	$homeLink->link = 'Home';

	$backLink = new link($this->URI(array('action' => 'context', 'contextcode' => $this->contextCode)));
	$backLink->link = 'Back to Course';
	echo $homeLink->show().'/'.$backLink->show().'</br>';
?>
