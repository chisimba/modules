<?php
// Load classes.
$this->loadClass("form", "htmlelements");
$this->loadClass("textinput", "htmlelements");
$this->loadClass('textarea', 'htmlelements');
$this->loadClass("button", "htmlelements");
$this->loadClass("htmltable", 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$objWindow = &$this->newObject('windowpop', 'htmlelements');
$objHeading = &$this->getObject('htmlheading', 'htmlelements');
$objWashout = $this->getObject('washout', 'utilities');
$objTable = new htmltable();
$objTable->width = '100%';
$objTable->border = 1;
$objTable->attributes = "rules=none frame=box";
$objTable->cellspacing = '3';
//Select Owner Home
$iconSelect = $this->getObject('geticon', 'htmlelements');
$iconSelect->setIcon('home');
$iconSelect->alt = $objLanguage->languageText("mod_eportfolio_eportfoliohome", 'eportfolio');
$mnglink = new link($this->uri(array(
    'module' => 'eportfolio'
)));
$mnglink->link = $iconSelect->show();
$linkManage = $mnglink->show();
//Heading
$objHeading->type = 1;
$objHeading->str = $objUser->fullname() . $objLanguage->languageText("mod_eportfolio_viewEportfolio", 'eportfolio') . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $linkManage;
echo "<div>" . $objHeading->show() . "</div>";
//display user's names
// Spacer
$objTable->startRow();
$objTable->addCell($identification . $demographics . $address . $contacts . $emails . $affiliation . $goals . $interests, 340, 'top', 'left');
$objTable->addCell($qualification . $transcripts . $activity . $competency . $reflections . $assertions, Null, 'top', 'right');
$objTable->endRow();
$objTable->startRow();
$objTable->addCell('&nbsp;');
$objTable->addCell($linkManage, Null, 'top', 'right');
$objTable->endRow();
echo $objTable->show();
?>
