<?

$this->loadClass('textinput','htmlelements');
$this->loadClass('textarea','htmlelements');
$this->loadClass('form','htmlelements');
$this->loadClass('textinput','htmlelements');
$this->loadClass('textarea','htmlelements');
$this->loadClass('label','htmlelements');
$this->loadClass('button','htmlelements');
  $table = & $this->newObject('htmltable','htmlelements');
        $table->width = '75%';

/*
	//Load the link class
	$this->loadClass('iframe', 'htmlelements');
	$iframe = new iframe('iframe', 'htmlelements');//,$src=0,$align,$frameborder,$name='Squall',$scrolling='');
	$iframe->width = 300;
	$iframe->height = 300;
	$iframe->frameborder = 1 ;
	$iframe->src = "http://localhost/nextgen/usrfiles/course-shit.zip";
	echo $iframe->show(); 
	*/






//-------
/*
$this->objPop= $this->newObject('windowpop', 'htmlelements');
*   $this->objPop->set('location','/modules/htmltabledemo/popup.htm');
*   $this->objPop->set('linktext','Click me baby');
*   $this->objPop->set('width','200'); 
*   $this->objPop->set('height','200');
*   $this->objPop->set('left','300');
*   $this->objPop->set('top','400');





/*
 var $width;
    var $height;
    var $src;
    var $align;
    var $frameborder; //must be 0 or 1
    var $marginheight;
    var $marginwidth;
    var $name;
    var $id;
    var $scrolling;
    var $theFrame;
    var $style;
*/
//-------

	


	//$iframe->invisibleIFrame('/');
	// set up link to popup window
	/*
	$objLink = new link('javascript:void(0)');
	$objLink -> link = "SquallS";
	$objLink -> extra = "onclick = \"javascript:window.open('" .
	$this->uri(array('action'=>'search')) . "', 'branch', '$width, $height, $top, $left, scrollbars')\"";
	*/




	/**
	* to add meta data into the database
	*/
	$id = $this->getParam('id');
	
	//$dublin  = $this->objDC->getInputs(null, 'add');
	$form = new form('metadata',$this->uri(array('action'=>'metadataconfirm','id'=>$id)));
	
	$textarea = & $this->newObject('textinput','htmlelements');
        $textarea->size = '40';
        $textarea->value = '';
        $textarea->setId(null);
      
        $objButton=new button('save');
        $objButton->setToSubmit();
        $objButton->setValue($this->objLanguage->languageText("mod_contextadmin_save"));

        //title
        $title = $textarea;
        $title->name = 'title';
        // $title->setValue($arr['title']);
        $title->label = $this->objLanguage->languageText("word_title");

        //subject
        $subject = $textarea;
        $subject->name = 'subject';
        $subject->label = $this->objLanguage->languageText("mod_dublin_subject");

        //description
        $description = $textarea;
        $description->name = 'description';
        $description->label = $this->objLanguage->languageText("mod_dublin_description");

        //source
        $source = $textarea;
        $source->name = 'source';
        $source->label = $this->objLanguage->languageText("mod_dublin_source");

        //sourceURL
        $sourceurl = $textarea;
        $sourceurl->name = 'sourceurl';
        $sourceurl->label = $this->objLanguage->languageText("mod_dublin_sourceurl");


        //type
        $type = $textarea;
        $type->name = 'type';
        $type->label = $this->objLanguage->languageText("mod_dublin_type");

        //relationship
        $relationship = $textarea;
        $relationship->name = 'relationship';
        $relationship->label = $this->objLanguage->languageText("mod_dublin_relationship");

        //coverage
        $coverage = $textarea;
        $coverage->name = 'coverage';
        $coverage->label = $this->objLanguage->languageText("mod_dublin_coverage");

        //creator
        $creator = $textarea;
        $creator->name = 'creator';
        $creator->label = $this->objLanguage->languageText("mod_dublin_creator");

        //publisher
        $publisher = $textarea;
        $publisher->name = 'publisher';
        $publisher->label = $this->objLanguage->languageText("mod_dublin_publisher");

        //contributor
        $contributor = $textarea;
        $contributor->name = 'contributor';
        $contributor->label = $this->objLanguage->languageText("mod_dublin_contributor");

        //rights
        $rights = $textarea;
        $rights->name = 'rights';
        $rights->label = $this->objLanguage->languageText("mod_dublin_rights");

        //relationship
        $date = $textarea;
        $date->name = 'date';
        $date->label = $this->objLanguage->languageText("mod_dublin_date");

        //format
        $format = $textarea;
        $format->name = 'format';
        $format->label = $this->objLanguage->languageText("mod_dublin_format");

        //relationship
        $relationship = $textarea;
        $relationship->name = 'relationship';
        $relationship->label = $this->objLanguage->languageText("mod_dublin_relationship");

        //identifier
        $identifier = $textarea;
        $identifier->name = 'identifier';
        $identifier->label = $this->objLanguage->languageText("mod_dublin_identifier");

        //language
        $language = $textarea;
        $language->name = 'language';
        $language->label = $this->objLanguage->languageText("mod_dublin_language");

        //audience
        $audience = $textarea;
        $audience->name = 'audience';
        $audience->label = $this->objLanguage->languageText("mod_dublin_audience");



 	$table->addRow(array($title->label,$title->show()),'odd');
        $table->addRow(array($subject->label,$subject->show()),'even');
        $table->addRow(array($description->label,$description->show()),'odd');
        $table->addRow(array($source->label,$source->show()),'even');
        $table->addRow(array($type->label,$type->show()),'odd');
        $table->addRow(array($relationship->label,$relationship->show()),'even');
        $table->addRow(array($coverage->label,$coverage->show()),'odd');

        $table->addRow(array($creator->label,$creator->show()),'even');
        $table->addRow(array($publisher->label,$publisher->show()),'odd');
        $table->addRow(array($rights->label,$rights->show()),'even');
        $table->addRow(array($date->label,$date->show()),'odd');
        $table->addRow(array($format->label,$format->show()),'even');
        $table->addRow(array($identifier->label,$identifier->show()),'odd');
        $table->addRow(array($language->label,$language->show()),'even');
        $table->addRow(array($audience->label,$audience->show()),'odd');
        $table->addRow(array($sourceurl->label,$sourceurl->show()),'even');

		//back link
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
	
	$button = new button("submit",
	$objLanguage->code2Txt("word_save"));    //word_save
	$button->setToSubmit();
	$row = array($button->show().$BackLink);
	$table->addRow($row);

	$form->addToForm($table->show());

//shows edit form
echo $form->show();
	


?>