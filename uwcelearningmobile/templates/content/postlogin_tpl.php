<?php
//Mobile Prelogin template
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	    die("You cannot view this page directly");
}
	$this->loadClass('fieldset','htmlelements');
	$this->loadClass('link','htmlelements');
	$objFields = new fieldset();
	$objFields->setLegend('<b>'.$this->objLanguage->languageText('mod_uwcelearningmobile_wordmycourse', 'uwcelearningmobile').'</b>');
		
	foreach($usercontexts as $context)
	{
		$con = $this->dbContext->getContext($context);
		$link = new link($this->URI(array('action' => 'context', 'contextcode'=> $con['contextcode'])));
		$link->link = $con['title'];
		$objFields->addContent($link->show().'<br>');
	}
	
	echo '<br>'.$objFields->show();
	

	//Tools that can be access before you enter the course
	$objFields = new fieldset();
			
	foreach($tools as $mod)
	{
		$link = new link($this->URI(array('action' => $mod)));
		$link->link = ucwords($this->objLanguage->code2Txt('mod_'.$mod.'_name', $mod));
		$objFields->addContent($link->show().'<br>');
	}
	if(!empty($tools))
	{
		echo '<br>'.$objFields->show().'<br>';
	}
?>
