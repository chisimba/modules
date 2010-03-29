<?php
//MCQ Tests template
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	    die("You cannot view this page directly");
}
	$this->loadClass('fieldset','htmlelements');
	$this->loadClass('link','htmlelements');
	$newImgPath = $this->getResourceUri('img/new.png', 'uwcelearningmobile');
	
	echo '<b>'.$this->objLanguage->languageText('mod_uwcelearningmobile_wordcourse', 'uwcelearningmobile').': </b>'.$this->contextTitle;

	$objFields = new fieldset();
	$objFields->setLegend('<b>'.$this->objLanguage->languageText('mod_uwcelearningmobile_wordnewewcontent', 'uwcelearningmobile').'</b>');
	
	if (!empty($content)) {
		foreach($content as $con) {
			$objFields->addContent($con['chaptertitle'].' <img src="'.$newImgPath.'" border="0" alt="New" title="New"><br>');
        }	
	}
	else {
		$objFields->addContent($this->objLanguage->languageText('mod_uwcelearningmobile_wordnocontent', 'uwcelearningmobile'));
	}
	echo $objFields->show();

	$homeLink = new link($this->URI(array()));
	$homeLink->link = 'Home';

	$backLink = new link($this->URI(array('action' => 'context', 'contextcode' => $this->contextCode)));
	$backLink->link = 'Back to Course';
	echo $homeLink->show().'/'.$backLink->show().'</br>';
?>
