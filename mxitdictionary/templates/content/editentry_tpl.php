	<?php
	/**
	 * Model extension of controller that displays the interface for editing entries
	 * @authors:Godwin Du Plessis, Ewan Burns, Helio Rangeiro, Jacques Cilliers, Luyanda Mgwexa, George Amabeoku, Charl Daniels, and Qoane Seitlheko.
	 * @copyright 2007 University of the Western Cape
	 */
	// Create an instance of the css layout class
	$cssLayout = &$this->newObject('csslayout', 'htmlelements');
	// Set columns to 2
	$cssLayout->setNumColumns(2);
	// get the sidebar object
	$this->leftMenu = $this->newObject('usermenu', 'toolbar');
	// Initialize left column
	$leftSideColumn = $this->leftMenu->show();
	$rightSideColumn = NULL;
	$middleColumn = NULL;
	// Create link icon and link to view template
	$this->loadClass('link', 'htmlelements');
	$objIcon = $this->newObject('geticon', 'htmlelements');
	$link = new link($this->uri(array(
	    'action' => 'default'
	)));
	$objIcon->setIcon('prev');
	$link->link = $objIcon->show();
	$update = $link->show();
	// Create header with add icon and set the action
	$pgTitle = &$this->getObject('htmlheading', 'htmlelements');
	$pgTitle->type = 1;
	$pgTitle->str = $objLanguage->languageText('mod_mxit_return', 'mxitdictionary') . "&nbsp;" . $update;
	//$this->objUser = $this->getObject('user', 'security');
	$cform = new form('mxitdictionary', $this->uri(array(
	    'action' => 'updateentry'
	)));
	//start a fieldset
	$cfieldset = $this->getObject('fieldset', 'htmlelements');
	$ct = $this->newObject('htmltable', 'htmlelements');
	$ct->cellpadding = 5;
	//start a row, hide the id from the user and check whether there is any input
	$ct->startRow();
	$this->loadClass('hiddeninput', 'htmlelements');
	$ctv = new hiddeninput('id');
	if (isset($oldrec['id'])) {
	    $ctv->value = $oldrec['id'];
	}
	$ct->addCell($ctv->show() == false);
	$ct->addCell($ctv->show());
	// end of the row
	$ct->endRow();
	//value textfield
	//start a row, and does the same check
	$ct->startRow();
	$ctvlabel = new label($this->objLanguage->languageText('mod_mxit_word', 'mxitdictionary') . ':', 'input_cvalue');
	$ctv = new textinput('word');
	if (isset($oldrec['word'])) {
	    $ctv->value = $oldrec['word'];
	}
	$ct->addCell($ctvlabel->show());
	$ct->addCell($ctv->show());
	// end of the row
	$ct->endRow();
	// start row and add cell just to make form look better
	$ct->startRow();
	$ct->addCell('&nbsp;');
	// end of the row
	$ct->endRow();
	//value textfield
	//start a row, and does the same check
	$ct->startRow();
  $ctvlabel = new label($this->objLanguage->languageText('mod_mxit_definition', 'mxitdictionary') . ':', 'input_cvalue');
	$ctv = new textinput('definition');
	if (isset($oldrec['definition'])) {
	    $ctv->value = $oldrec['definition'];
	}
	$ct->addCell($ctvlabel->show());
	$ct->addCell($ctv->show());
	// end of the row
	$ct->endRow();
	// start row and add cell just to make form look better
	$ct->startRow();
	$ct->addCell('&nbsp;');
	// end of the row
	$ct->endRow();









	//end off the form and add the buttons
	$this->objconvButton = new button($this->objLanguage->languageText('mod_mxit_update', 'mxitdictionary'));
	$this->objconvButton->setValue($this->objLanguage->languageText('mod_mxit_update', 'mxitdictionary'));
	$this->objconvButton->setToSubmit();
	$cfieldset->addContent($ct->show());
	$cform->addToForm($cfieldset->show());
	$cform->addToForm($this->objconvButton->show());
	$cform = $cform->show();
	//create the feature box and display the form
	$objFeatureBox = $this->getObject('featurebox', 'navigation');
	$ret = $objFeatureBox->showContent($this->objLanguage->languageText("mod_mxit_update", "mxitdictionary") , $cform);
	$middleColumn = $pgTitle->show() . $ret;
	// Create link back to my view template
	$objBackLink = &$this->getObject('link', 'htmlelements');
	$objBackLink->link($this->uri(array(
	    'module' => 'mxitdictionary'
	)));
	$objBackLink->link = $objLanguage->languageText('mod_mxit_return', 'mxitdictionary');
	//add left column
	$cssLayout->setLeftColumnContent($leftSideColumn);
	$cssLayout->setRightColumnContent($rightSideColumn);
	//add middle column
	$cssLayout->setMiddleColumnContent($middleColumn);
	echo $cssLayout->show();
	?>
