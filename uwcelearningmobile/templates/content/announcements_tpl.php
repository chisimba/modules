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
	if(!empty($coursesann))
    {
	    foreach($coursesann as $ann)
	    {
		    $link = new link ($this->uri(array('action'=>'viewannouncements', 'id' => $ann['id'])));
		    $link->link = $ann['title'];
		    $objFields->addContent($link->show().'<br/>');
	    }
    }
	else 
    {
         $objFields->addContent($this->objLanguage->code2Txt('mod_uwcelearningmobile_wordmynocontextann', 'uwcelearningmobile'));
    }

	echo $objFields->show().'<br>';

	//All my courses announcement
	$objFields = new fieldset();
	$objFields->setLegend('<b>'.$this->objLanguage->languageText('mod_uwcelearningmobile_wordallann', 'uwcelearningmobile').'('.$allanncount.')</b>');
    if(!empty($allann))
    {
	    foreach($allann as $ann)
	    {
		    $link = new link ($this->uri(array('action'=>'viewannouncements', 'id' => $ann['id'])));
		    $link->link = $ann['title'];
		    $objFields->addContent($link->show().'<br/>');
	    }
    }
	else 
    {
         $objFields->addContent($this->objLanguage->languageText('mod_uwcelearningmobile_wordmynoann', 'uwcelearningmobile'));
    }

	echo $objFields->show().'<br>'.$this->homeAndBackLink.'</br>';
?>
