<?php
//View snipets on the new course content
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	    die("You cannot view this page directly");
}	
	$this->loadClass('fieldset','htmlelements');
	$this->loadClass('link','htmlelements');
    
	$objFields = new fieldset();
    $objFields->setLegend($chapter['chaptername']);
	$objFields->addContent();
	echo $objFields->show();
	
	$backLink = new link($this->URI(array('action' => 'contextcontent')));
	$backLink->link = 'Back to Course Content';
	echo $backLink->show().'</br>';
?>
