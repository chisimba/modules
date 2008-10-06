<?php
// Load classes.
$this->loadClass("form", "htmlelements");
$this->loadClass("textinput", "htmlelements");
$this->loadClass("button", "htmlelements");
$this->loadClass("htmltable", 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
//	$objLabel =& $this->newObject('label', 'htmlelements');
$objWindow = &$this->newObject('windowpop', 'htmlelements');
$type = 'Place';
$categorytypeList = $this->objDbCategorytypeList->listCategorytype($type);
$modetype = 'Mode';
$modetypeList = $this->objDbCategorytypeList->listCategorytype($modetype);
$objHeading = &$this->getObject('htmlheading', 'htmlelements');
$objHeading->type = 1;
$objHeading->str = $objLanguage->languageText("mod_eportfolio_addcontact", 'eportfolio');
echo $objHeading->show();
$form = new form("add", $this->uri(array(
    'module' => 'eportfolio',
    'action' => 'addcontactconfirm'
)));
$objTable = new htmltable();
$objTable->width = '100%';
$objTable->attributes = " align='center' border='0'";
$objTable->cellspacing = '5';
$row = array(
    "<b>" . $objLanguage->code2Txt("word_name") . ":</b>"
);
$objTable->startRow();
$objTable->addCell($row[0], 140, 'top', 'right');
$row = array(
    $objUser->fullName()
);
$objTable->addCell($row[0], Null, 'top', 'left');
$objTable->endRow();
//type drop down list
$dropdown = new dropdown('contact_type');
if (!empty($categorytypeList)) {
    foreach($categorytypeList as $categories) {
        $dropdown->addOption($categories['id'], $categories['type']);
    }
} else {
    $dropdown->addOption('None', "-There are no Types-");
}
$row = array(
    "<b>" . $label = $objLanguage->languageText("mod_eportfolio_contype", 'eportfolio') . ":</b>"
);
$objTable->startRow();
$objTable->addCell($row[0], 140, 'top', 'right');
$row = array(
    $dropdown->show()
);
$objTable->addCell($row[0], Null, 'top', 'left');
$objTable->endRow();
//Contact type dropdown
$mydropdown = new dropdown('contactType');
if (!empty($modetypeList)) {
    foreach($modetypeList as $categories) {
        $mydropdown->addOption($categories['id'], $categories['type']);
    }
} else {
    $mydropdown->addOption('None', "-There are no Types-");
}
$row = array(
    "<b>" . $label = $objLanguage->languageText("mod_eportfolio_contacttype", 'eportfolio') . ":</b>"
);
$objTable->startRow();
$objTable->addCell($row[0], 140, 'top', 'right');
$row = array(
    $mydropdown->show()
);
$objTable->addCell($row[0], Null, 'top', 'left');
$objTable->endRow();
//country_code text box
$textinput = new textinput("country_code", "");
$textinput->size = 15;
$form->addRule('country_code', 'Please enter the country code', 'required');
$row = array(
    "<b>" . $label = $objLanguage->languageText("mod_eportfolio_countrycode", 'eportfolio') . ":</b>"
);
$objTable->startRow();
$objTable->addCell($row[0], 140, 'top', 'right');
$row = array(
    $textinput->show()
);
$objTable->addCell($row[0], Null, 'top', 'left');
$objTable->endRow();
//area_code text field
$textinput = new textinput("area_code", "");
$textinput->size = 15;
$form->addRule('area_code', 'Please enter the area code', 'required');
$row = array(
    "<b>" . $label = $objLanguage->languageText("mod_eportfolio_areacode", 'eportfolio') . ":</b>"
);
$objTable->startRow();
$objTable->addCell($row[0], 140, 'top', 'right');
$row = array(
    $textinput->show()
);
$objTable->addCell($row[0], Null, 'top', 'left');
$objTable->endRow();
//contact number text field
$textinput = new textinput("id_number", "");
$textinput->size = 30;
$form->addRule('id_number', 'Please enter the Id number', 'required');
$row = array(
    "<b>" . $label = $objLanguage->languageText("mod_eportfolio_contactnumber", 'eportfolio') . ":</b>"
);
$objTable->startRow();
$objTable->addCell($row[0], 140, 'top', 'right');
$row = array(
    $textinput->show()
);
$objTable->addCell($row[0], Null, 'top', 'left');
$objTable->endRow();
//Save button
$button = new button("submit", $objLanguage->languageText("word_save")); //word_save
$button->setToSubmit();
// Show the cancel link
$buttonCancel = new button("submit", $objLanguage->languageText("word_cancel"));
$objCancel = &$this->getObject("link", "htmlelements");
$objCancel->link($this->uri(array(
    'module' => 'eportfolio',
    'action' => 'view_contact'
)));
$objCancel->link = $buttonCancel->show();
$linkCancel = $objCancel->show();
$row = array(
    $button->show() . ' ' . $linkCancel
);
$objTable->startRow();
$objTable->addCell('&nbsp;', 140, 'top', 'right');
$objTable->addCell($row[0], Null, 'top', 'left');
$objTable->endRow();
$form->addToForm($objTable->show());
echo $form->show();
?>
