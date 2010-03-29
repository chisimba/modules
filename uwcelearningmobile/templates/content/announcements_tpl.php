<?php
//Announcements template
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	    die("You cannot view this page directly");
}
	$this->loadClass('fieldset','htmlelements');
	$this->loadClass('link','htmlelements');

	//Current Course announcements 
	$objFields = new fieldset();
	$objFields->setLegend('<b>'.$this->contextTitle.'('.$coursesanncount.')</b>');
	foreach($coursesann as $ann)
	{
		$link = new link ($this->uri(array('action'=>'viewannouncements', 'id' => $ann['id'])));
		$link->link = $ann['title'];
		$objFields->addContent($link->show().'<br/>');
	}
	echo $objFields->show();

	//All my courses announcement
	$objFields = new fieldset();
	$objFields->setLegend('<b>'.$this->objLanguage->languageText('mod_uwcelearningmobile_wordallann', 'uwcelearningmobile').'('.$allanncount.')</b>');
	foreach($allann as $ann)
	{
		$link = new link ($this->uri(array('action'=>'viewannouncements', 'id' => $ann['id'])));
		$link->link = $ann['title'];
		$objFields->addContent($link->show().'<br/>');
	}
	echo $objFields->show();

	$homeLink = new link($this->URI(array()));
	$homeLink->link = 'Home';

	$backLink = new link($this->URI(array('action' => 'context', 'contextcode' => $this->contextCode)));
	$backLink->link = 'Back to Course';
	echo $homeLink->show().'/'.$backLink->show().'</br>';
?>
