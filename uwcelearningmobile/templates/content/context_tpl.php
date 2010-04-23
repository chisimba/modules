<?php
//Content of a context tamplates
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	    die("You cannot view this page directly");
}
	$this->loadClass('fieldset','htmlelements');
	$this->loadClass('link','htmlelements');
	echo '<b>'.$this->objLanguage->languageText('mod_uwcelearningmobile_wordcourse', 'uwcelearningmobile').': </b>'.$conexttitle;

	$objFields = new fieldset();
	$objFields->setLegend('<b>'.$this->objLanguage->languageText('mod_uwcelearningmobile_wordcoursetools', 'uwcelearningmobile').'</b>');

	 	foreach($tools as $tool)
		{
			$toolLink = new link($this->URI(array('action' => $tool)));
			$toolLink->link = ucwords($this->objLanguage->code2Txt('mod_'.$tool.'_name', $tool));
			$objFields->addContent($toolLink->show().'<br/>');
		}

	echo $objFields->show();

	$homeLink = new link($this->URI(array()));
	$homeLink->link = 'Home';
	echo $homeLink->show();
?>
