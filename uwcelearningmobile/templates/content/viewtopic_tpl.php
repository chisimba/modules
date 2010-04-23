<?php
//View Topic template
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	    die("You cannot view this page directly");
}
	$this->loadClass('fieldset','htmlelements');
	$this->loadClass('link','htmlelements');

	$objFields = new fieldset();
	$objFields->setLegend('<b>');
	if(!empty($posts)){

		foreach($posts as $post){
			$str = '<strong>Subject :</strong>'.$post['post_title'].'<br>';
			$str .= '<strong>By :</strong>'.$post['firstname'].' '.$post['surname'].'<br>';
			$str .= $post['post_text'].'<br>';
			$objFields->addContent($str);
			}
	}
	else{
		$norecords = 'No Posts for this Topic';
		$objTableClass->addCell($norecords, NULL, NULL, 'center', 'noRecordsMessage', 'colspan="7"');
	}
	echo $objFields->show().'<br>';

	$backLink = new link($this->URI(array('action' => 'forum')));
	$backLink->link = 'Back to Forum';
	echo $this->homeAndBackLink.' - '.$backLink->show();
?>
