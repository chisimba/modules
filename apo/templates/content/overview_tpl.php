<?php

$this->loadClass('fieldset', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('iframe', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('radio', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');
$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');

$this->setVar('pageSuppressXML', TRUE);
$this->baseDir = $this->objSysConfig->getValue('FILES_DIR', 'wicid');
$action = 'addrulesandsyllabusone';

$form = new form('overviewform', $this->uri(array('action' => $action)));

$xtitle = $this->objLanguage->languageText('mod_wicid_document', 'wicid', 'Section A: Overview');

$header = new htmlheading();
$header->type = 2;
$header->str = $xtitle;

echo $header->show();

$doclink = new link($this->uri(array("action" => "editdocument")));
$doclink->link = "Document";

$rulesandsyllabusonelink = new link($this->uri(array("action" => "addrulesandsyllabusone")));
$rulesandsyllabusonelink->link = "Rules and Syllabus (page one)";

$rulesandsyllabustwolink = new link($this->uri(array("action" => "addrulesandsyllabustwo")));
$rulesandsyllabustwolink->link = "Rules and Syllabus (page two)";

$subsidyrequirementslink = new link($this->uri(array("action" => "addsubsidyrequirements")));
$subsidyrequirementslink->link = "Subsidy Requirements";

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

$links = $doclink->show() . '&nbsp;|&nbsp;' . "<b>Overview</b>" . '&nbsp;|&nbsp;' .
        $rulesandsyllabusonelink->show() . '&nbsp;|&nbsp;' . $rulesandsyllabustwolink->show() . '&nbsp;|&nbsp;' .
        $subsidyrequirementslink->show() . '&nbsp;|&nbsp;' . $outcomesandassessmentonelink->show() . '&nbsp;|&nbsp;' .
        $outcomesandassessmenttwolink->show() . '&nbsp;|&nbsp;' . $outcomesandassessmentthreelink->show() . '&nbsp;|&nbsp;' .
        $resourceslink->show() . '&nbsp;|&nbsp;' . $collaborationandcontractslink->show() . '&nbsp;|&nbsp;' .
        $reviewlink->show() . '&nbsp;|&nbsp;' . $contactdetailslink->show() . '<br/>';

$fs = new fieldset();
$fs->setLegend('<b>Navigation</b>');
$fs->addContent($links);

echo $fs->show() . '<br/>';

$table = $this->newObject('htmltable', 'htmlelements');

$textinput = new textinput('a1');
$textinput->size = 50;
if ($mode == 'edit') {
  $textinput->value = $document['docname'];
  }/*
  if ($mode == "fixup") {
  $textinput->value = $a1;
  } */
$table->startRow();
$table->addCell("A.1. Name of course/unit:");
$table->addCell($textinput->show());
$table->endRow();

$radio = new radio('a2');
$radio->addOption('1', "proposal for a new course/unit ");
$radio->addOption('2', "change to the outcomes or credit value of a course/unit");
$radio->setSelected('1');
$radio->setBreakSpace('</p><p>');
$table->startRow();
$table->addCell("A.2. This is a:");
$table->addCell($radio->show());
$table->endRow();

$textarea = new textarea('a3');
$textarea->height = '70px';
//$textarea->width = '500px';
$table->startRow();
$table->addCell("A.3. Provide a brief motivation for the introduction/amendment of the course/unit:");
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('a4');
$textarea->height = '70px';
$textarea->width = '500px';
$table->startRow();
$table->addCell("A.4. Towards which qualification(s) can the course/unit be taken?");
$table->addCell($textarea->show());
$table->endRow();

$radio = new radio('a5');
$radio->addOption('1', "linked to other recent course/unit proposal/s, or proposal/s currently in development");
$radio->addOption('2', "linked to other recent course/unit amendment/s, or amendment/s currently in development");
$radio->addOption('3', "linked to a new qualification/ programme proposal, or one currently in development");
$radio->addOption('4', "linked to a recent qualification/ programme amendment, or one currently in development");
$radio->addOption('5', "not linked to any other recent academic developments, nor those currently in development");
$radio->setSelected('1');
$radio->setBreakSpace('</p><p>');
$table->startRow();
$table->addCell("A.5. This new or amended course proposal is:");
$table->addCell($radio->show());
$table->endRow();

/* $efs = new fieldset();
  $efs->setLegend('Errors');
  if (count($errormessages) > 0) {

  $errorstr = '<ul>';

  foreach ($errormessages as $errormessage) {
  $errorstr.='<li class="error">' . $errormessage . '<li/>';
  }
  $errorstr.='</li>';
  $efs->addContent($errorstr);
  $form->addToForm($efs);
  } */

$legend = "<b>A: Overview</b>";
$fs = new fieldset();
$fs->setLegend($legend);
$fs->addContent($table->show());
$form->addToForm($fs->show());

$button = new button('next', $this->objLanguage->languageText('word_next'));
$uri = $this->uri(array('action' => $action));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$form->addToForm($button->show());

$button = new button('back', $this->objLanguage->languageText('word_back'));
$uri = $this->uri(array('action' => 'editdocument'));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$form->addToForm($button->show());

$button = new button('cancel', $this->objLanguage->languageText('word_cancel'));
$uri = $this->uri(array('action' => 'home'));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$form->addToForm($button->show());

echo $form->show();
?>
