<?php
	//$this->setLayoutTemplate('popuplayout_tpl.php')
	$this->setVar('pageSuppressContainer',TRUE);
	$this->setVar('pageSuppressBanner',TRUE);
	$this->setVar('pageSuppressToolbar',TRUE);
	$this->setVar('suppressFooter',TRUE);
	
    // Load classes.
	$this->loadClass("form","htmlelements");
	$this->loadClass("textarea","htmlelements");
	$this->loadClass("button","htmlelements");
	$this->loadClass("textinput","htmlelements");
	$objLabel =& $this->newObject('label', 'htmlelements');
	
	
	$form = new form("comment", 
		$this->uri(array(
	    	
	   		'action'=>'commentconfirm'
	)));
	
	if(isset($showConfirm) && $showConfirm){
	  echo '<p class="confirm">comment saved</p>';
	  
	}
	
	$objTable =& $this->newObject('htmltable','htmlelements');
        $objTable->width='20';
        $objTable->cellspacing='12';
        $objTable->cellpadding='12';
        
		
					
		
		//... Comment text Area
		$textarea = new textarea("comment");
        $textarea->size = 100;
        $textarea->scrollbars = 'yes';
        $row = array("<b>".$label=$objLanguage->languageText("mod_readinglist_comment",'readinglist').":</b>".$textarea->show());
		$objTable->addRow($row, 'even');
		
    
		
		$exitLabel = $this->objLanguage->languageText('word_cancel');
    	//Save button
		$button = new button("submit",$objLanguage->languageText("word_save"));    //word_save
		$button->setToSubmit();
		
		$row = array($button->show());
		$objTable->addRow($row, 'even');
		
		
		//the exit button
		/*$button1 = new button("submit");
		$button1 = $objLanguage->languageText("word_exit");
		$comm = $this->uri(array(
                'action'=>''
            ));
		
		$button1->extra = "onclick=\"javascript:window.close($comm);\"";
		$button1->setToSubmit();*/
		
		//$row = array($button1->show());
		//$objTable->addRow($row, 'even');
		$form->addToForm($objTable->show());
		
		
		echo $form->show();
?>	