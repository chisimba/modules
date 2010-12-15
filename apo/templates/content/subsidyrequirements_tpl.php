<?php

$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('fieldset', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');
$this->loadClass('radio','htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('iframe', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');

$this->setVar('pageSuppressXML', TRUE);

$xtitle = $this->objLanguage->languageText('mod_wicid_document', 'wicid', 'Section C: Subsidy Requirements');

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

$outcomesandassessmentonelink = new link($this->uri(array("action" => "addoutcomesandassessmentone")));
$outcomesandassessmentonelink->link = "Outcomes and Assessment (page one)";

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
        "<b>Subsidy Requirements</b>" . '&nbsp;|&nbsp;' . $outcomesandassessmentonelink->show() . '&nbsp;|&nbsp;' .
        $outcomesandassessmenttwolink->show() . '&nbsp;|&nbsp;' . $outcomesandassessmentthreelink->show() . '&nbsp;|&nbsp;' .
        $resourceslink->show() . '&nbsp;|&nbsp;' . $collaborationandcontractslink->show() . '&nbsp;|&nbsp;' .
        $reviewlink->show() . '&nbsp;|&nbsp;' . $contactdetailslink->show() . '<br/>';

$fs = new fieldset();
$fs->setLegend('<b>Navigation</b>');
$fs->addContent($links);

echo $fs->show() . '<br/>';

/* if ($mode == 'edit') {
  $legend = "Edit document";
  } */
$table = $this->newObject('htmltable', 'htmlelements');

$textarea = new textarea('c1');
$table->startRow();
$table->addCell('C.1. The mode of instruction is understood to be contact/face-to-face lecturing. Provide details if any other mode of delivery is to be used:');
$table->addCell($textarea->show());
$table->endRow();

$radio = new radio ('c2');
$radio->addOption('1',"off-campus");
$radio->addOption('2',"on-campus");
$radio->setSelected('1');
$table->startRow();
$table->addCell("C.2.a. The course/unit is taught:");
$table->addCell($radio->show());
$table->endRow();


$textarea = new textarea('c2b');
$table->startRow();
$table->addCell('C.2.b. If the course/unit is taught off-campus provide details:');
$table->addCell($textarea->show());
$table->endRow();

//Section C.3.

$textinput = new textinput('c3');
$textinput->size = 60;
$textinput->value = $c3;
$table->startRow();
$table->addCell('C.3. What is the third order CESM (Classification of Education Subject Matter) category for the course/unit? (The CESM manual can be downloaded from http://intranet.wits.ac.za/Academic/APO/CESMs.htm):');
$table->addCell($textinput->show());
$table->endRow();

//Section C.4.
$radio = new radio ('c4a');
$radio->addOption('1',"Yes");
$radio->addOption('2',"No");
$radio->setSelected('1');
$table->startRow();
$table->addCell("C.4.a. Is any other School/Entity involved in teaching this unit?:");
$table->addCell($radio->show());
$table->endRow();


$textarea = new textarea('c4b');
$textarea->size = 60;
$textarea->value = $c4b;
$table->startRow();
$table->addCell('C.4.b. If yes, state the name of the School/Entity:');
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('c4c');
$textarea->size = 60;
$textarea->value = $c4c;
$table->startRow();
$table->addCell('Percentage each teaches.:');
$table->addCell($textarea->show());
$table->endRow();

$legend = "<b>Subsidy Requirements</b>";

$fs = new fieldset();
$fs->setLegend($legend);
$fs->addContent($table->show());
//echo $fs->show();
$form = new form('subsidyrequirementsform', $this->uri(array('action' => $action)));

$hiddenSelected = new hiddeninput('selected', $cfile);
$form->addToForm($hiddenSelected->show());

$form->addToForm($fs->show());


$button = new button('next', $this->objLanguage->languageText('word_next'));
$uri = $this->uri(array('action' => 'addoutcomesandassessmentone'));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$form->addToForm('<br/>'.$button->show());

$button = new button('back', $this->objLanguage->languageText('word_back'));
$uri = $this->uri(array('action' => 'addrulesandsyllabustwo'));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$form->addToForm($button->show());

$button = new button('cancel', $this->objLanguage->languageText('word_cancel'));
$uri = $this->uri(array('action' => 'home'));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$form->addToForm($button->show());


echo $form->show();
?>
