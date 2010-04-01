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
	$objFields->setLegend('<b></b>');
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
	echo '<br>'.$objFields->show().'<br>';

	$homeLink = new link($this->URI(array()));
	$homeLink->link = 'Home';

	$backLink = new link($this->URI(array('action' => 'context', 'contextcode' => $this->contextCode)));
	$backLink->link = 'Back to Course';
	echo $homeLink->show().'/'.$backLink->show().'</br>';
?>
