<?
$id 	= $this->getParam('id');
$name 	= $this->getParam('name');
$this->loadClass('textinput','htmlelements');
$this->loadClass('textarea','htmlelements');
$this->loadClass('form','htmlelements');
$this->loadClass('textinput','htmlelements');
$this->loadClass('textarea','htmlelements');
$this->loadClass('label','htmlelements');
$this->loadClass('button','htmlelements');
$table  = & $this->newObject('htmltable','htmlelements');
$heading  = & $this->newObject('htmlheading','htmlelements');

$button = & $this->newObject('button','htmlelements');

$table->width = '75%';
$courseware = 'Rate the course';

	//$form = new form('rating',$this->uri(array('action'=>'ratingconfirm','id'=>$id)));
	$form = new form('rating',$this->uri(array('action'=>'ratingconfirm','id'=>$id)));


	$dropdown =& $this->newObject("dropdown", "htmlelements");
   	$dropdown->name = 'rating';
   	for ($i = null; $i <= 5; $i++){
   	$dropdown->addOption($i,$i);
   	}
   	/*
   		$rating_icon;
		$rating_icon = $this->getObject('geticon','htmlelements');
		$rating_icon->setIcon('icq_on');
		$lblView = "Rate this course";	
		$rating_icon->alt = $lblView;
		$rating_icon->align=false;

		$ratingoff_icon;
		$ratingoff_icon = $this->getObject('geticon','htmlelements');
		$ratingoff_icon->setIcon('icq_noac');
		$lblView = "Rate this course";	
		$ratingoff_icon->alt = $lblView;
		$ratingoff_icon->align=false;
   	*/
   	
   	$good_icon = $this->getObject('geticon','htmlelements');
	$good_icon->setIcon('star');
	$lblView = "Good";	
	$good_icon->alt = $lblView;
	$good_icon->align=false;

	//echo $good_icon->show();
	
	
	$bad_icon = $this->getObject('geticon','htmlelements');
	$bad_icon->setIcon('star-g');
	$lblView = "Bad";	
	$bad_icon->alt = $lblView;
	$bad_icon->align=false;

	//echo $bad_icon->show();
	
	$row = array($bad_icon->show()."&nbsp;&nbsp;&nbsp;"."0 stars bad");
	$table->addRow($row);
	$row = array($good_icon->show()."&nbsp;&nbsp;&nbsp;"."5 stars excellent");
	$table->addRow($row);
	
   	
	$row = array("<b>".$label =$courseware.' : '."</b>"."&nbsp;".$dropdown->show());
	$table->addRow($row);
	
	
	
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
	//echo $BackLink;


	 //---------------------------------Save button------------------------------
   	$button = new button("submit",
	$objLanguage->code2Txt("word_save"));    //word_save
	$button->setToSubmit();
	$button1 = new button("cancel",
	$objLanguage->code2Txt("word_cancel")); 
	$button1->setToSubmit();
	
	$row = array($button->show()."&nbsp;".$button1->show());
	$table->addRow($row);

    //--------------------------------------------------------------------------------

	$objHeading = &$this->getObject('htmlheading','htmlelements');
	$pgTitle = $objHeading;
	$pgTitle->type = 1;
	$pgTitle->str ="Course Rating of"."&nbsp;".$name;

	$heading = $table;
	$heading->width = NULL;
	$heading->endRow();



	//Heading-------------------------------------

	$form->addToForm($pgTitle->show()."<br>".$table->show());
	echo $form->show();

   	
   


?>
