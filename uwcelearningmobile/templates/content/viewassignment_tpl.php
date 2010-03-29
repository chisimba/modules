<?php
//Assignment spec template
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	    die("You cannot view this page directly");
}
	$this->loadClass('link', 'htmlelements');
	$this->loadClass('fieldset','htmlelements');
	$objFields = new fieldset();
	$objFields->setLegend('<b>'.$assignment['name'].'</b>');

	$objFields->addContent($assignment['description'].'<br/>');

	echo $objFields->show();
	
	$homeLink = new link($this->URI(array()));
	$homeLink->link = 'Home';

	$backLink = new link($this->URI(array('action' => 'context', 'contextcode' => $this->contextCode)));
	$backLink->link = 'Back to Course';
	echo $homeLink->show().'/'.$backLink->show().'</br>';
?>
