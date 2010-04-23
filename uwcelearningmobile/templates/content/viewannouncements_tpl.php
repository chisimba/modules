<?php
//View a single announcement
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	    die("You cannot view this page directly");
}
	$this->loadClass('fieldset','htmlelements');
	$this->loadClass('link','htmlelements');

	//All my courses announcement
	$objFields = new fieldset();
	$objFields->setLegend('<b>'.$announcement['title'].'</b>');
	$str = '<p><strong>By:</strong> '.$this->objUser->fullName($announcement['createdby']).' - '.$this->objDate->formatDate($announcement['createdon']);

	$str .= '<br />'.$announcement['message'];
	$objFields->addContent($str.'<br/>');
	echo $objFields->show().'</br>';

	$backLink = new link($this->URI(array('action' => 'announcements')));
	$backLink->link = 'Back to Announcements';
	echo $this->homeAndBackLink.' - '.$backLink->show();
?>
