<?php
$hasAccess = $this->objUser->isContextLecturer();
$hasAccess|= $this->objUser->isAdmin();
$this->_objUser = $this->getObject('user', 'security');
$hasAccess = $this->_objUser->isContextLecturer();
$hasAccess = $this->_objUser->isAdmin();
$this->setVar('pageSuppressXML', true);
if (!$hasAccess) {
    // Redirect
    return $this->nextAction('main', array());
    break;
} else {
    // Load classes.
    $this->loadClass("form", "htmlelements");
    $this->loadClass("textinput", "htmlelements");
    $this->loadClass('textarea', 'htmlelements');
    $this->loadClass("button", "htmlelements");
    $this->loadClass("htmltable", 'htmlelements');
    $this->loadClass('dropdown', 'htmlelements');
    $objWindow = &$this->newObject('windowpop', 'htmlelements');
    $objHeading = &$this->getObject('htmlheading', 'htmlelements');
    $objHeading->type = 1;
    $objHeading->str = $objLanguage->languageText("mod_eportfolio_addAssertion", 'eportfolio');
    echo $objHeading->show();
    $form = new form("add", $this->uri(array(
        'module' => 'eportfolio',
        'action' => 'addassertionconfirm'
    )));
    $objTable = new htmltable();
    $objTable->width = '100%';
    $objTable->attributes = " align='left' border='0'";
    $objTable->cellspacing = '5';
    $row = array(
        "<b>" . $objLanguage->languageText("word_name") . ":</b>"
    );
    $objTable->startRow();
    $objTable->addCell($row[0], 140, 'top', 'right');
    $row = array(
        $objUser->fullName()
    );
    $objTable->addCell($row[0], Null, 'top', 'left');
    $objTable->endRow();
    //language Text box
    $language = new textinput("language", "");
    $language->size = 30;
    $form->addRule('language', 'Please enter the Language', 'required');
    $row = array(
        "<b>" . $label = $objLanguage->languageText("mod_eportfolio_language", 'eportfolio') . ":</b>"
    );
    $objTable->startRow();
    $objTable->addCell($row[0], 140, 'top', 'right');
    $row = array(
        $language->show()
    );
    $objTable->addCell($row[0], Null, 'top', 'left');
    $objTable->endRow();
    //rationale text box
    $textinput = new textarea("rationale", "");
    $form->addRule('rationale', 'Please enter the rationale', 'required');
    $row = array(
        "<b>" . $label = $objLanguage->languageText("mod_eportfolio_assertionRationale", 'eportfolio') . ":</b>"
    );
    $objTable->startRow();
    $objTable->addCell($row[0], 200, 'top', 'right');
    $row = array(
        $textinput->show()
    );
    $objTable->addCell($row[0], Null, 'top', 'left');
    $objTable->endRow();
    //date calendar
    $row = array(
        "<b>" . $label = $objLanguage->languageText("mod_eportfolio_creationDate", 'eportfolio') . ":</b>"
    );
    $objTable->startRow();
    $objTable->addCell($row[0], 140, 'bottom', 'right');
    //$startField = $this->objPopupcal->show('creation_date', 'yes', 'no', "");
    $strtdate = &$this->getObject('datepicker', 'htmlelements');
    $strtdate->setName('creation_date');
    $strtdate->setDateFormat("YYYY-MM-DD");
    $form->addRule('creation_date', 'Please enter the creation date', 'required');
    $row = array(
        $strtdate->show()
    );
    $objTable->addCell($row[0], Null, 'top', 'left');
    $objTable->endRow();
    //short description text box
    $textinput = new textarea("shortdescription", "");
    $form->addRule('shortdescription', 'Please enter a short description', 'required');
    $row = array(
        "<b>" . $label = $objLanguage->languageText("mod_eportfolio_shortdescription", 'eportfolio') . ":</b>"
    );
    $objTable->startRow();
    $objTable->addCell($row[0], 140, 'top', 'right');
    $row = array(
        $textinput->show()
    );
    $objTable->addCell($row[0], Null, 'top', 'left');
    $objTable->endRow();
    //Full description text field
    $row = array(
        "<b>" . $label = $objLanguage->languageText("mod_eportfolio_longdescription", 'eportfolio') . ":</b>"
    );
    $objTable->startRow();
    $objTable->addCell($row[0], 140, 'top', 'right');
    //Add the WYSWYG editor
    $editor = $this->newObject('htmlarea', 'htmlelements');
    $editor->setName('longdescription');
    $editor->height = '300px';
    $editor->width = '450px';
    $longdescription = '';
    $editor->setContent($longdescription);
    $row = array(
        $editor->show()
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
        'action' => 'view_assertion'
    )));
    //$objCancel->link = $buttonCancel->show();
    $objCancel->link = $objLanguage->languageText("mod_filemanager_returnto", "filemanager") . " " . $objLanguage->languageText("mod_eportfolio_eportfoliohome", "eportfolio");
    $linkCancel = $objCancel->show();
    $row = array(
        $button->show()
    );
    $objTable->startRow();
    $objTable->addCell('&nbsp;', 140, 'top', 'right');
    $objTable->addCell($row[0], Null, 'top', 'left');
    $objTable->endRow();
    $objTable->startRow();
    $objTable->addCell('&nbsp;', 140, 'top', 'right');
    $objTable->addCell($linkCancel, Null, 'top', 'left');
    $objTable->endRow();
    $form->addToForm($objTable->show());
    echo $form->show();
}
?>
