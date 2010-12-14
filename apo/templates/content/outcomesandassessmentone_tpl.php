<?php
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('fieldset', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('iframe', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');

$this->setVar('pageSuppressXML', TRUE);

$xtitle = $this->objLanguage->languageText('mod_wicid_newdocument', 'wicid', 'Section D: Outcomes and Assessment - Page One');

$header = new htmlheading();
$header->type = 2;
$header->str = $xtitle;

echo $header->show();

$doclink = new link($this->uri(array("action" => "editdocument")));
$doclink->link = "Document";

$overviewlink = new link($this->uri(array("action" => "addoverview")));
$overviewlink->link = "Overview";

$rulesandsyllabusonelink = new link($this->uri(array("action" => "addrulesandsyllabusone")));
$rulesandsyllabusonelink->link = "Rules and Syllabus (page one)";

$rulesandsyllabustwolink = new link($this->uri(array("action" => "addrulesandsyllabustwo")));
$rulesandsyllabustwolink->link = "Rules and Syllabus (page two)";

$subsidyrequirementslink = new link($this->uri(array("action" => "addsubsidyrequirements")));
$subsidyrequirementslink->link = "Subsidy Requirements";

$outcomesandassessmenttwolink = new link($this->uri(array("action" => "addoutcomesandassessmenttwo")));
$outcomesandassessmenttwolink->link = "Outcomes and Assessment (page two)";

$outcomesandassessmentthreelink = new link($this->uri(array("action" => "addoutcomesandassessmentthree")));
$outcomesandassessmentthreelink->link = "Outcomes and Assessment (page three)";

$resourceslink = new link($this->uri(array("action" => "addresources")));
$resourceslink->link = "Resources";

$collaborationandcontractslink = new link($this->uri(array("action" => "addcollaborationandcontracts")));
$collaborationandcontractslink->link = "Collaboration and Contracts";

$reviewlink = new link($this->uri(array("action" => "addreview")));
$reviewlink->link = "Review";

$contactdetailslink = new link($this->uri(array("action" => "addcontactdetails")));
$contactdetailslink->link = "Contact Details";

$links = $doclink->show() . '&nbsp;|&nbsp;' . $overviewlink->show() . '&nbsp;|&nbsp;' .
        $rulesandsyllabusonelink->show() . '&nbsp;|&nbsp;' . $rulesandsyllabustwolink->show() . '&nbsp;|&nbsp;' .
        $subsidyrequirementslink->show() . '&nbsp;|&nbsp;' . "<b>Outcomes and Assessment (page one)</b>" . '&nbsp;|&nbsp;' .
        $outcomesandassessmenttwolink->show() . '&nbsp;|&nbsp;' . $outcomesandassessmentthreelink->show() . '&nbsp;|&nbsp;' .
        $resourceslink->show() . '&nbsp;|&nbsp;' . $collaborationandcontractslink->show() . '&nbsp;|&nbsp;' .
        $reviewlink->show() . '&nbsp;|&nbsp;' . $contactdetailslink->show() . '<br/>';

$fs = new fieldset();
$fs->setLegend('<b>Navigation</b>');
$fs->addContent($links);

echo $fs->show() . '<br/>';

$legend = "Outcomes and Assessments";

//Section D.1.

$d1a = new dropdown('d1a');
$d1a->addOption("NQF 5");
$d1a->addOption("NQF 6");
$d1a->addOption("NQF 7");
$d1a->addOption("NQF 8");

/*if ($mode == 'fixup') {
    $documentNumber->setSelected($oldNGF);
}
if ($mode == 'edit') {
    $documentNumber->setSelected(substr($document['refno'], 0, 1));
}*/

$table = $this->newObject('htmltable', 'htmlelements');
$table->startRow();
$table->addCell("<b>D.1.a. On which OLD NQF (National Qualifications Framework) level (e.g. NQF 5, 6, 7 & 8) is the course/unit positioned?:</b>");
if ($mode == 'edit') {
    $table->addCell($document['refno'] . '-' . $document['version']);
} else {
    $table->addCell($d1a->show());
}
$table->endRow();

$d1b = new dropdown('d1b');
$d1b->addOption("NQF 5");
$d1b->addOption("NQF 6");
$d1b->addOption("NQF 7");
$d1b->addOption("NQF 8");
$d1b->addOption("NQF 9");
$d1b->addOption("NQF 10");


if ($mode == 'fixup') {
    $documentNumber->setSelected($newNGF);
}
if ($mode == 'edit') {
    $documentNumber->setSelected(substr($document['refno'], 0, 1));
}

//$table = $this->newObject('htmltable', 'htmlelements');
$table->startRow();
$table->addCell("<b>D.1.b. On which NEW NQF (National Qualifications Framework) level (e.g. NQF 5, 6, 7, 8, 9 & 10) is the course/unit positioned?:</b>");
if ($mode == 'edit') {
    $table->addCell($document['refno'] . '-' . $document['version']);
} else {
    $table->addCell($d1b->show());
}
$table->endRow();

//Section D.2.

$table->startRow();
$table->addCell('<b>D.2. Specify the course/unit outcomes, assessment criteria and methods of assessment in the tables below.</b>');
$table->endRow();

$textarea = new textarea('d2a');
$textarea->size = 60;
$textarea->value = $courseOutcomes;

$table->startRow();
$table->addCell('<b>Learning Outcomes of the Course/Unit</b>');
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('d2b');
$textarea->size = 60;
$textarea->value = $assessCriteria;

$table->startRow();
$table->addCell('<b>Assessment Criteria for the Learning Outcomes</b>');
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('d2c');
$textarea->size = 60;
$textarea->value = $assessMethods;

$table->startRow();
$table->addCell('<b>Assessment Methods to be Used</b>');
$table->addCell($textarea->show());
$table->endRow();

//Section D.3.
$textarea = new textarea('d3');
$textarea->size = 60;
$textarea->value = $overallAchieve;

$table->startRow();
$table->addCell('<b>D.3. How do the course/unit outcomes contribute to the acheivement of the overall qualification/programme outcomes?:</b>');
$table->addCell($textarea->show());
$table->endRow();

//Form
$fs = new fieldset();
$fs->setLegend($legend);
$fs->addContent($table->show());

$form = new form('outcomesassessmentform', $this->uri(array('action' => $action)));

$hiddenSelected = new hiddeninput('selected', $cfile);
$form->addToForm($hiddenSelected->show());

$form->addToForm($fs->show());

$button = new button('next', $this->objLanguage->languageText('word_next'));
$uri = $this->uri(array('action' => 'addoutcomesandassessmentthree'));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$form->addToForm('<br/>' .$button->show());

$button = new button('back', $this->objLanguage->languageText('word_back'));
$uri = $this->uri(array('action' => 'addrulesandsyllabusone'));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$form->addToForm($button->show());

$button = new button('cancel', $this->objLanguage->languageText('word_cancel'));
$uri = $this->uri(array('action' => 'home'));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$form->addToForm($button->show());

echo $form->show();

?>
