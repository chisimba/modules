<?php
	
	//create the heading
    $this->objH =& $this->getObject('htmlheading', 'htmlelements');
    $this->objH->type=1;
    $this->objH->str=ucwords($this->objLanguage->code2Txt('mod_contextadmin_addnewcontext',array('context'=>'course')));
    

    //setting up the instances of the htmlelements to be used in creating tables, buttons, textfield, buttons etc.
    $objLang=& $this->getObject('language', 'language');
    
    //Load objects
    $this->loadClass('radio','htmlelements');
    $this->loadClass('form','htmlelements');
    $this->loadClass('textinput','htmlelements');
    $this->loadClass('checkbox','htmlelements');                    
    $this->loadClass('button','htmlelements');                    
    $editor=&$this->newObject('htmlarea','htmlelements');
    $table=& $this->newObject('htmltable','htmlelements');
	$objDublinCore =& $this->newObject('dublincore','klorserver');
    //$objDublinCore = & $this->newObject('dublincore', 'dublincoremetadata');
	$multiTab  = & $this->newObject('multitabbedbox','htmlelements');
	
    //setup form
    $objForm = $this->newObject('form','htmlelements');
    $objForm->name='testform';
    $objForm->setAction($this->uri(array('action'=>'uploadfile'),'klorserver'));
    $objForm->setDisplayType(3);    
    
	if(isset($error))
	{
		$table->startRow();
		$table->addCell('&nbsp;');   
		$table->addCell('<span class="error">'.$error.'</span>');   
		$table->endRow();
	}
	
    //add context code
    $table->startRow();
    $objElement = new textinput('contextCode');
    $objElement->size = '20';
    $objElement->searchField='contextCode';
    $table->addCell(ucwords($this->objLanguage->code2Txt('mod_contextadmin_contextcode',array('context'=>'course'))),'100px');
    $table->addCell($objElement->show());   
    $table->endRow();
    $objForm->addRule('contextCode',$this->objLanguage->languageText("mod_contextadmin_err_required"), 'required');
    $objForm->addRule(array('name'=>'contextCode','length'=>20),ucwords($this->objLanguage->code2Txt('mod_contextadmin_error_length',array('length'=>'20'))),'maxlength');
    
    //add title
    $table->startRow();
    $objElement = new textinput('contexttitle');     
	$objElement->value = $this->getParam('title');     
    $objElement->size = '55';
    $table->addCell($this->objLanguage->languageText("mod_contextadmin_title"));
    $table->addCell($objElement->show());
    $table->endRow();
    $objForm->addRule('title',$this->objLanguage->languageText("mod_contextadmin_err_required"), 'required');
    $objForm->addRule(array('name'=>'title','length'=>250),ucwords($this->objLanguage->code2Txt('mod_contextadmin_error_length',array('length'=>'50'))),'maxlength');
    
    //add menu text
    $table->startRow();
    $objElement = new textinput('menutext');
	$objElement->value = $this->getParam('menutext');     
    $objElement->size = '55';
    $table->addCell($this->objLanguage->languageText("mod_contextadmin_menutext"));
    $table->addCell($objElement->show()."<br>");
    $table->endRow();
    $objForm->addRule('menutext',$this->objLanguage->languageText("mod_contextadmin_err_required"), 'required');
    $objForm->addRule(array('name'=>'menutext','length'=>250),ucwords($this->objLanguage->code2Txt('mod_contextadmin_error_length',array('length'=>'50'))),'maxlength');

    //add isclosed
    $table->startRow();
    $objElement = new radio('isclosed');
    $objElement->addOption('0',$this->objLanguage->languageText("mod_contextadmin_isopen"));
    $objElement->addOption('1',$this->objLanguage->languageText("mod_contextadmin_isclosed"));
    
    $tmp = $objElement->show();
    //add isactive
    $objElement = new radio('isactive');
    $objElement->addOption('1',$this->objLanguage->languageText("mod_contextadmin_active"));
    $objElement->addOption('0',$this->objLanguage->languageText("mod_contextadmin_inactive"));
    $objElement->setSelected('1');
    
    $table->addCell($this->objLanguage->languageText("mod_contextadmin_status"));
    $table->addCell($objElement->show().'<br>'. $tmp);
    $table->endRow();
    
    //about
    $table->startRow();
    $editor->setName('about');
    $editor->setBasicToolBar();
	$editor->setContent(stripslashes($this->getParam('about')));
    $editor->context = TRUE;
    $editor->width = '300';
    $editor->height = '200';
    $table->addCell($this->objLanguage->languageText("mod_contextadmin_about"));
    $table->addCell($editor->show()); 
    $table->endRow();    
    
    //submit button
    $table->startRow();
    $objElement = new button('mybutton');    
    $objElement->setToSubmit();    
    $objElement->setValue($this->objLanguage->languageText("mod_contextadmin_save"));
    $table->addCell("");
    $table->addCell($objElement->show());
    $table->endRow();
	$button = $objElement->show();
    
    //add  link  
    $objLink = & $this->newObject('link','htmlelements');
    $objLink->cssClass = 'pseudbutton';
    $objLink->href = $this->uri(array(), 'contextadmin');
    $objLink->link = $this->objLanguage->languageText("word_back");
	
	//========== META DATA========
	$dublin = $objDublinCore->getInputs(null, 'add');
	//==============================
   
   
   //The mnulti Tab box
   //===============================
     $multiTab->width ='600px';
     $multiTab->height = '410px';
     $multiTab->addTab(array('name'=>$this->objLanguage->languageText("mod_context_content"),'url'=>'http://localhost','content' => $table->show(),'default' => true));
     $multiTab->addTab(array('name'=>$this->objLanguage->languageText("mod_dublin_dcm"),'url'=>'http://localhost','content' => $dublin));
        // $multiTab->addTab(array('name'=>$this->objLanguage->languageText("mod_context_javascript"),'url'=>'http://localhost','content' => $tab3));	
	
   
   //==============================
   
   
    $objForm->addToForm($multiTab);
    $center = $this->objH->show();
    $center .=  $objForm->show();
    $center .=  $objLink->show();
    
    $this->contentNav = & $this->newObject('layer','htmlelements');
    $this->contentNav->id = "content";
    $this->contentNav->addToStr($center);
	$this->contentNav->height = '500px';
    echo $this->contentNav->show();
    echo "<br />\n";
    

?>