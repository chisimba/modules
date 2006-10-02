<?

/*
<!-- The data encoding type, enctype, MUST be specified as below -->
<form enctype="multipart/form-data" action="" method="POST">
    <!-- MAX_FILE_SIZE must precede the file input field -->
    <input type="hidden" name="MAX_FILE_SIZE" value="300000000000" />
    <!-- Name of input element determines name in $_FILES array -->
    Send this file: <input name="userfile" type="file" />
    <input type="submit" value="Send File" />
</form>
*/
	$this->loadClass('multitabbedbox','htmlelements');
	$this->loadClass('mouseoverpopup','htmlelements');
	$this->loadClass('multitabbedbox','htmlelements');
	$this->loadClass('form','htmlelements');
	$this->loadClass('dropdown','htmlelements');
	$this->loadClass('textinput','htmlelements');
	$this->loadClass('textarea','htmlelements');
	$objIcon = &$this->getObject('geticon','htmlelements');
	$objLink = & $this->getObject('link','htmlelements');

		$objlinkcontext = & $this->getObject('link','htmlelements');
		//Add all the above to a tabbedbox
		
	
	// 'Add' Icon 
	$icon = $objIcon;
	$icon->setIcon('add metadata');
	$icon->alt = "Add metadata";
	$icon->align=false;
	$link = $this->getObject('link','htmlelements');
	$link->href = $this->uri(array('action'=>'add'));
	$link->link = $icon->show();
   	$addIcon = $link->show();
	//echo  $addIcon;
	


	/**Words**/
	$word_title 		= $objLanguage->languageText('mod_opencourseware_code');
	$word_description	= $objLanguage->languageText('mod_opencourseware_courseinfo');
	$word_upload 		= $objLanguage->languageText('mod_opencourseware_upload');
	$word_file			= $objLanguage->languageText('mod_opencourseware_coursename');
	$word_version 		= $objLanguage->languageText('mod_opencourseware_version');
	$word_license 		= $objLanguage->languageText('mod_opencourseware_license');
	
	$word_save 		= $objLanguage->languageText('mod_opencourseware_save');

	$form =& new form('uploadForm',$this->uri(array('action'=>'uploadfile')));
	//$form->addToForm('<input type="hidden" name="MAX_FILE_SIZE" value="300000000000" />') ; 
	
	$form->extra=" ENCTYPE='multipart/form-data'";
	
	$filetypestring = 'Zipped Courses';
	$form->addToForm("<h3>".$word_upload." - ".$filetypestring.$this->objHelp->show('upload','klorclient')."</h3>");

	 
    


	$title_errormsg= $word_title ;
	$description_errormsg= $word_description;
	$version_errormsg= $word_version ;
	
	
	$objTable =& $this->newObject('htmltable','htmlelements');
	$objTable->cellpadding = "2";
	$objTable->cellspacing = "2";
	
	$row = array($word_file, "<input type='file' name='file' size='50'>");
	$objTable->addRow($row, 'odd');
	$textinput = new textinput('title',"",null,50);

	$row = array($word_license, "<input type='file' name='license' size='50'>");
	$objTable->addRow($row, 'odd');
	$textinput = new textinput('license',"",null,50);
	

	$form->addRule('title', $title_errormsg ,'required');
	$row = array($word_title ,$textinput->show());
	$objTable->addRow($row, 'odd');
	
	$textarea = new textarea('description',"", 4, 50);
	$form->addRule('description', $description_errormsg ,'required');
	$row = array($word_description,$textarea->show());
	$objTable->addRow($row, 'odd');
	
		
	//-----
	//$objElement = new tabbedbox();
	//$objElement->addTabLabel('Bittorrent Details');		
	//$objElement->addBoxContent();
	//$tab = '<br>'.$objElement->show().'<br>';
	//	echo $tab."<p>";

	

	$textinput = new textinput('announce',"http://",null,40);
	$row = array('announce' ,$textinput->show());
	//$objTable->addRow($row, 'odd');
	
	$textarea = new textarea('comment',"", '', 40);
	$row = array('comment',$textarea->show());
	//$objTable->addRow($row, 'odd');
	//----
	
	
	
	$textinput = new textinput('version',"");
	$form->addRule('version', $version_errormsg ,'required');
	$row = array($word_version,$textinput->show());
	$objTable->addRow($row, 'odd');
	
	
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

	$BackLink   = "<a href=\"{$uriBack}\">"."&nbsp;&nbsp;".'Back'."</a>";
	

	
	$row = array("<input type='submit' class='button' value='".$word_save."'>".'&nbsp;'.$BackLink,"&nbsp;");
	
	$objTable->addRow($row, 'odd');


	
		

	$form->addToForm($objTable->show());
	
	/**Dublin core metadata
	* form: 
	*/
	


echo $form->show();

	















?>
