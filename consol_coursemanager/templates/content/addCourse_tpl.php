<?php

//add step 1 template
$pgTitle = &$this->getObject('htmlheading', 'htmlelements');
$pgTitle->type = 3;
$pgTitle->str = "&nbsp;"."Add Consol Category";

$objForm = & $this->newObject('form','htmlelements');

$inpContextCode =  & $this->newObject('textinput','htmlelements');
$inpMenuText = & $this->newObject('textinput','htmlelements');
$inpTitle = & $this->newObject('textinput','htmlelements');
$inpButton =  $this->newObject('button','htmlelements');
$inpCButton =  $this->newObject('button','htmlelements');
$dropAccess = $this->newObject('dropdown','htmlelements');


// Create a table
$objTableClass = $this->newObject('htmltable', 'htmlelements');
$objTableClass->cellspacing = "2";
$objTableClass->cellpadding = "12";
$objTableClass->width = "40%";
$objTableClass->attributes = "border='0'";
// Create the array for the table header
$tableRow = array();

//setup the form
$objForm->name = 'addfrm';
//remember to add a action
$objForm->action = $this->uri(array('action' => ''));
//$objForm->extra = 'class="f-wrap-1"';
$objForm->displayType = 3;

$inpContextCode->name = 'contextcode';
$inpContextCode->cssId = 'input_contextcode';
$inpContextCode->value = '';
$inpContextCode->cssClass = 'f-name';

$inpTitle->name = 'title';
$inpTitle->cssId = 'input_title';
$inpTitle->value = '';
$inpTitle->cssClass = 'f-name';

$inpMenuText->value = '';
$inpMenuText->name = 'menutext';
$inpMenuText->cssId = 'input_menutext';
$inpMenuText->cssClass = 'f-name';

//status
$dropAccess->name = 'status';
			$dropAccess->addOption('Published','Published');
			$dropAccess->addOption('Unpublished','Unpublished');

$dropAccess->setSelected('Published');

$drop = '<fieldset class="f-radio-wrap">
				<fieldset>
				
				<label for="Public">
				<input id="Public" type="radio" name="access" value="Public" class="f-radio" tabindex="8" />
				
							'.'Public'.' <span class="caption">  -  '.'PublicHelp'.'</span></label><br/>
				
				<label for="Open">
				<input id="Open" type="radio" name="access" value="Open" class="f-radio" tabindex="9" />
				'.'Open'.' <span class="caption">  - '. 'Open'.'</span></label><br/>

				
				<label for="Private">

				<input id="Private" type="radio" name="access" value="Private" class="f-radio" tabindex="10" />
				'.'Private'.' <span class="caption">  -  '.'Private'.'</span></label><br/>
				
	
				</fieldset>
			
			</fieldset>';

$inpButton->setToSubmit();
$inpButton->cssClass = 'f-submit';
$inpButton->value = "add";

$inpCButton->setToSubmit();
$inpCButton->cssClass = 'f-submit';
$inpCButton->value = "cancel";

//validation
$objForm->addRule('contextcode','[-context-] Code is a required field!', 'required');
$objForm->addRule('menutext','Menu Text is a required field', 'required!');
$objForm->addRule('title','Title is a required field', 'required!');


$objForm->addToForm($pgTitle->show());

$objForm->addToForm('<div class="req">&nbsp;<b>*</b> Indicates required field</div>');
$objForm->addToForm('<fieldset>');

$objTableClass->startRow();

$objTableClass->addCell("&nbsp;".'<label for="contextcode"><b><span class="req">*</span>'.'context'.':</b></label>', '', 'top');
$objTableClass->addCell($inpContextCode->show(), '', 'top');
$objTableClass->endRow();

$objTableClass->startRow();
$objTableClass->addCell("&nbsp;".'<label for="title"><b><span class="req">*</span>'."title".':</b></label>', '', 'top');
$objTableClass->addCell($inpTitle->show(), '', 'top');
$objTableClass->endRow();

$objTableClass->startRow();
$objTableClass->addCell("&nbsp;".'<label for="menutext"><b><span class="req">*</span>'.'menu text'.':</b></label>', '', 'top');
$objTableClass->addCell($inpMenuText->show(), '', 'top');
$objTableClass->endRow();

$objTableClass->startRow();
$objTableClass->addCell("&nbsp;".'<label for="access"><b><span class="req">*</span>'.'Access'.':</b></label>', '', 'top');
$objTableClass->addCell($dropAccess->show(), '', 'top');
$objTableClass->endRow();

$objTableClass->startRow();
$objTableClass->addCell("&nbsp;".'<label for="access"><b>'.'Access'.':</b></label>', '', 'top');
$objTableClass->addCell($drop, '', 'top');
$objTableClass->endRow();

$objTableClass->startRow();
$objTableClass->addCell('', '', 'top', 'center');
$objTableClass->addCell($inpButton->show()."&nbsp;".$inpCButton->show(), '', 'top');
$objTableClass->endRow();

$objForm->addToForm($objTableClass);


//$objForm->addToForm('<br/><br/><div class="f-submit-wrap">'.$inpButton->show().'</div></fieldset>');
$objForm->addToForm('<br/><br/>'.'</fieldset>');
print $objForm->show().'<br/>';

//print '<br/><div class="f-submit-wrap">'.$inpButton->show().'</div></fieldset>';
?>