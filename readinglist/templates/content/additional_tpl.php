<?php
	//$this->setLayoutTemplate('popuplayout_tpl.php')
	//$this->setVar('pageSuppressContainer',TRUE);
	//$this->setVar('pageSuppressBanner',TRUE);
	//$this->setVar('pageSuppressToolbar',TRUE);
	//$this->setVar('suppressFooter',TRUE);
    // Load classes.
	$this->loadClass("form","htmlelements");
	$this->loadClass("textinput","htmlelements");
	$this->loadClass("button","htmlelements");
	$this->loadClass("link","htmlelements");
	$this->loadClass("tabbedbox","htmlelements");
	$objLabel =& $this->newObject('label', 'htmlelements');
	$objHeading =& $this->getObject('htmlheading','htmlelements');
	$objHeading1 =& $this->getObject('htmlheading','htmlelements');
	
	//Heading 1
	$objHeading->type=1;
	$objHeading->str =$objLanguage->languageText("mod_readinglist_additionals",'readinglist');
	echo $objHeading->show();
		
        	
	$objTable =& $this->newObject('htmltable','htmlelements');
        $objTable->width='20';
        $objTable->cellspacing='12';
        $objTable->cellpadding='12';
        $row =
 array("<b>".$objLanguage->code2Txt("word_name").":</b>",
        $objUser->fullName());
        $objTable->addRow($row, 'odd');
        $row =
        array("<b>".ucwords($objLanguage->code2Txt("mod_context_context",'context')).":</b>",
        $contextTitle);
        $objTable->addRow($row, 'odd');
		
		//Heading 2
		
		
		$objHeading1->type=2;
		$objHeading1->str =$objLanguage->languageText("mod_readinglist_additionalcomment",'readinglist');
		
		
		
		
	//... Author text box
	//$textinput = new textinput("author",$author);
        //$textinput->size = 70;
        $row = array("<b>".$label=$objLanguage->languageText("mod_readinglist_author",'readinglist').":</b>",$author);
        //$textinput->show());
		$objTable->addRow($row, 'even');
		
    	//Title text box
	//$textinput = new textinput("title",$title);
        //$textinput->size = 70;
        $row = array("<b>".$objLanguage->languageText("mod_readinglist_title",'readinglist').":</b>",$title);
        //$textinput->show());
		$objTable->addRow($row, 'even');
		
    	//Publisher text box
        //$textinput = new textinput("publisher",$publisher);
        //$textinput->size = 70;
        $row = array("<b>".$objLanguage->languageText("mod_readinglist_publisher",'readinglist').":</b>",$publisher);
        //$textinput->show());
		$objTable->addRow($row, 'even');				     
	
    	//Publishing year text box
        //$textinput = new textinput("publishingYear",$publishingYear);
        //$textinput->size = 4;
        $row = array("<b>".$objLanguage->languageText("mod_readinglist_year",'readinglist').":</b>",$publishingYear);
        //$textinput->show());
		$objTable->addRow($row, 'even');
		  
        //Link text box
        //$textinput = new textinput("link",$link);
        //$textinput->size = 70;
        //$row = array("<b>".$label=$objLanguage->languageText("mod_readinglist_link",'readinglist').":</label></b>",
        //$textinput->show());	
		$row = array('<b>'.$objLanguage->languageText("mod_readinglist_link",'readinglist').':</b>',$link);
		$objTable->addRow($row, 'even');
		
	//Publication text box
        //$textinput = new textinput("publication",$publication);
        //$textinput->size = 70;
        $row = array("<b>".$label=$objLanguage->languageText("mod_readinglist_publication",'readinglist').":</b>",$publication);
        //$textinput->show());
		$objTable->addRow($row, 'even');
	
	//... Country text box
	//$textinput = new textinput("country",$country);
        //$textinput->size = 70;
        $row = array("<b>".$label=$objLanguage->languageText("mod_readinglist_country",'readinglist').":</b>",$country);
        //$textinput->show());
		$objTable->addRow($row, 'even');
	
	//... Language text box
	//$textinput = new textinput("language",$language);
        //$textinput->size = 70;
        $row = array("<b>".$label=$objLanguage->languageText("mod_readinglist_language",'readinglist').":</b>",$language);
        //$textinput->show());
		$objTable->addRow($row, 'even');
	
	
		//Add reference
        
		$refLink = new link('#');
		$url = $this->uri(array(
                'action'=>'comment',
                'id' => $id
            ));
		$refLink->link = $objLanguage->languageText("phrase_addreference");
		$refLink->extra = "onclick=\"javascript:window.open('{$url}', 'refs', 'width=500, height=240, left=100,top=100');\"";
		$linkWindow = $refLink->show();
		
		
    	//Close button
		$button = new button("Back",
		$objLanguage->languageText("word_back"));    
		$button->setToSubmit();
		$str = $button->show();
		
	
		
		// The form
		$form = new form("additionals", 
			$this->uri(array(
		    		'module'=>'readinglist'	
		)));
		$form->addToForm($str);
		
		//creating the tabbed box that contains the additional information table
		$objTab = new tabbedbox();
		$objTab->addBoxContent($objTable->show());
		$display = '<p>'.$objTab->show().'</p>';
		
		//creating the tabbed table that will hold the comment to be viewed
		$commentData = $this->uri(array(
                  'action' => 'comment',
                  'id' => $id));
		if(empty($commentData)){
			$objTab2 = new tabbedbox();
			$objTab2->addBoxContent($objHeading1->show());
			$display .= '<p>'.$objTab2->show().'</p>';
		}
			
		echo $display;	
		echo '<p>'.$linkWindow.'</p>';
		echo '<p>'.$form->show().'</p>';
?>