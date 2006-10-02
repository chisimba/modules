<?
	$this->loadClass('form','htmlelements');
	$this->loadClass('dropdown','htmlelements');
	$this->loadClass('textinput','htmlelements');
	$this->loadClass('textarea','htmlelements');
	$objIcon = &$this->getObject('geticon','htmlelements');
	$objLink = & $this->getObject('link','htmlelements');

	$objlinkcontext = & $this->getObject('link','htmlelements');


	//---WORDS
	$word_save = 'Save';
	//---WORDS


	//make table
	$objTable =& $this->newObject('htmltable','htmlelements');
	$objTable->cellpadding = "5";
	$objTable->cellspacing = "5";

	//form 
	$form =& new form('Form',$this->uri(array('action'=>'btformconfirm')));
	$form->extra=" ENCTYPE='multipart/form-data'";
	$filetypestring = 'Bittorrent Courses';
	$form->addToForm("<h3>".$word_upload."  ".$filetypestring."</h3>");
	
	
	//make row field
	//input name="announce" value="http://" type="text" >
	//$row = array('filelocation',"<input name='filelocation' value='' id='input_filelocation' type='file' size='50' id='input_filelocation'>");
	
	
	/*$textinput = new textinput('filenamepath',"",'file',40);
	
	$row = array('filenamepath' ,$textinput->show());
	$objTable->addRow($row, 'even');
	*/
	
	$textinput = new textinput('announce',"http://",null,40);
	$row = array('announce' ,$textinput->show());
	$objTable->addRow($row, 'even');
	
	
	$textarea = new textarea('comment',"", '', 40);
	$row = array('comment',$textarea->show());
	$objTable->addRow($row, 'even');
	
	
/*	$textinput = new textinput('setlocation',"",'file',40);
	$row = array('set location',$textinput->show());
	$objTable->addRow($row, 'even');
	*/
	
	/*
	$textinput = new textinput('setfilename',"",'',40);
	$row = array('set filename',$textinput->show());
	$objTable->addRow($row, 'even');
*/
	//------------------SAVE BUTTON
	$row = array("<input type='submit' class='button' value='".$word_save."'>","&nbsp;");
	$objTable->addRow($row, 'even');

	$form->addToForm($objTable->show());
	
	/**
	* form: 
	*/
	


echo $form->show();

	$back_icon;
	$back_icon = $this->getObject('geticon','htmlelements');
	$back_icon->setIcon('importcvs');
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

	$BackLink   = "<a href=\"{$uriBack}\">"."&nbsp;&nbsp;".$back_icon->show()."</a>";
	echo $BackLink;

?>
