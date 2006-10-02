<?
$this->loadClass('form','htmlelements');
$this->loadClass('dropdown','htmlelements');
$this->loadClass('textinput','htmlelements');
$this->loadClass('textarea','htmlelements');
	$objIcon = &$this->getObject('geticon','htmlelements');
	$objLink = & $this->getObject('link','htmlelements');

/**ICONS**/
// 'Add' Icon 
	$icon = $objIcon;
	$icon->setIcon('add metadata');
	$icon->alt = "Add metadata";
	$icon->align=false;
	$link = $this->getObject('link','htmlelements');
	$link->href = $this->uri(array('action'=>'add'));
	$link->link = $icon->show();
   	$addIcon = $link->show();
	echo  $addIcon;
	//--------------


/**Words**/
$word_title 		= $objLanguage->languageText('mod_klorclient_title');
$word_description	= $objLanguage->languageText('mod_klorclient_description');
$word_upload 		= $objLanguage->languageText('mod_klorclient_upload');
$word_file 			= $objLanguage->languageText('mod_klorclient_file');
$word_version 		= $objLanguage->languageText('mod_klorclient_version');
$word_save 			= $objLanguage->languageText('mod_klorclient_save');

	$form =& new form('uploadForm',$this->uri(array('action'=>'uploadfile')));
	$form->extra=" ENCTYPE='multipart/form-data'";
	
				$filetypestring = 'Documents';
	$form->addToForm("<h3>".$word_upload." - ".$filetypestring."</h3>");
	
	$title_errormsg= $word_title ;
	$description_errormsg= $word_description;
	$version_errormsg= $word_version ;
	
	
	$objTable =& $this->newObject('htmltable','htmlelements');
	$objTable->cellpadding = "5";
	$objTable->cellspacing = "5";
	
	$row = array($word_file, "<input type='file' name='file' size='50'>");
	$objTable->addRow($row, 'even');
	$textinput = new textinput('title',"",null,50);
	
	$form->addRule('title', $title_errormsg ,'required');
	$row = array($word_title ,$textinput->show());
	$objTable->addRow($row, 'even');
	
	$textarea = new textarea('description',"", 4, 50);
	$form->addRule('description', $description_errormsg ,'required');
	$row = array($word_description,$textarea->show());
	$objTable->addRow($row, 'even');
	
	$textinput = new textinput('version',"");
	$form->addRule('version', $version_errormsg ,'required');
	$row = array($word_version,$textinput->show());
	$objTable->addRow($row, 'even');
	$row = array("<input type='submit' class='button' value='".$word_save."'>","&nbsp;");
	$objTable->addRow($row, 'even');

	$form->addToForm($objTable->show());
	
	/**Dublin core metadata
	* form: 
	*/
	


echo $form->show();


?>