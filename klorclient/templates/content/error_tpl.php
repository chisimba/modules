<?
$id = $this->getParam('id');
$this->loadClass('textinput','htmlelements');
$this->loadClass('textarea','htmlelements');
$this->loadClass('form','htmlelements');
$this->loadClass('textinput','htmlelements');
$this->loadClass('textarea','htmlelements');
$this->loadClass('label','htmlelements');
$this->loadClass('button','htmlelements');
$table  = & $this->newObject('htmltable','htmlelements');
$button = & $this->newObject('button','htmlelements');


echo '<b><p>'.$objLanguage->languageText('mod_opencourseware_errormsg').'</p></b>'; 
   
$back_icon;
	$back_icon = $this->getObject('geticon','htmlelements');
	$back_icon->setIcon('bookopen');
	$lblView = "Back";	
	$back_icon->alt = $lblView;
	$back_icon->align=false;



		// Delete an entry in the table.
		$uriBack = $this->uri(
			array(
				'action' => ' ', 
				'id' =>$id 
			)
		);

	$BackLink   = "<a href=\"{$uriBack}\">"."Back"."</a>";
	echo $BackLink;


?>
