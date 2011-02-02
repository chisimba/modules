<?php

/*
 * @category  Chisimba
 * @package   apo (Academic Planning Office)
 * @author    Palesa Mokwena
 */
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

$formdata = $this->objformdata->getFormData("outcomesandassessmentone", $id);
if ($formdata != null){
    $mode = "edit";
}

$action = 'showoutcomesandassessmenttwo';
$form = new form('outcomesandassessmentoneform', $this->uri(array('action' => $action, 'id' => $id, 'formname' => 'outcomesandassessmentone')));

$xtitle = $this->objLanguage->languageText('mod_wicid_newdocument', 'wicid', 'Section D: Outcomes and Assessment - Page One');

$header = new htmlheading();
$header->type = 2;
$header->str = $xtitle;

echo $header->show();

$doclink = new link($this->uri(array("action" => "editdocument")));
$doclink->link = "Document";

$overviewlink = new link($this->uri(array("action" => "showoverview")));
$overviewlink->link = "Overview";

$rulesandsyllabusonelink = new link($this->uri(array("action" => "showrulesandsyllabusone")));
$rulesandsyllabusonelink->link = "Rules and Syllabus - Page One";

$rulesandsyllabustwolink = new link($this->uri(array("action" => "showrulesandsyllabustwo")));
$rulesandsyllabustwolink->link = "Rules and Syllabus - Page Two";

$subsidyrequirementslink = new link($this->uri(array("action" => "showsubsidyrequirements")));
$subsidyrequirementslink->link = "Subsidy Requirements";

$outcomesandassessmentonelink = new link($this->uri(array("action" => "showoutcomesandassessmentone")));
$outcomesandassessmentonelink->link = "Outcomes and Assessment - Page One";

$outcomesandassessmenttwolink = new link($this->uri(array("action" => "showoutcomesandassessmenttwo")));
$outcomesandassessmenttwolink->link = "Outcomes and Assessment - Page Two";

$outcomesandassessmentthreelink = new link($this->uri(array("action" => "showoutcomesandassessmentthree")));
$outcomesandassessmentthreelink->link = "Outcomes and Assessment - Page Three";

$resourceslink = new link($this->uri(array("action" => "showresources")));
$resourceslink->link = "Resources";

$collaborationandcontractslink = new link($this->uri(array("action" => "showcollaborationandcontracts")));
$collaborationandcontractslink->link = "Collaboration and Contracts";

$reviewlink = new link($this->uri(array("action" => "showreview")));
$reviewlink->link = "Review";

$contactdetailslink = new link($this->uri(array("action" => "showcontactdetails")));
$contactdetailslink->link = "Contact Details";

$links = $doclink->show() . '&nbsp;|&nbsp;' . $overviewlink->show() . '&nbsp;|&nbsp;' .
        $rulesandsyllabusonelink->show() . '&nbsp;|&nbsp;' . $rulesandsyllabustwolink->show() . '&nbsp;|&nbsp;' .
        $subsidyrequirementslink->show() . '&nbsp;|&nbsp;' . " <b> Outcomes and Assessments - Page one </b>" . '&nbsp;|&nbsp;' .
        $outcomesandassessmenttwolink->show() . '&nbsp;|&nbsp;' . $outcomesandassessmentthreelink->show() . '&nbsp;|&nbsp;' .
        $resourceslink->show() . '&nbsp;|&nbsp;' . $collaborationandcontractslink->show() . '&nbsp;|&nbsp;' .
        $reviewlink->show() . '&nbsp;|&nbsp;' . $contactdetailslink->show() . '<br/>';

$fs = new fieldset();
$fs->setLegend('<b>Navigation</b>');
$fs->addContent($links);

echo $fs->show() . '<br/>';

$legend = "<b>Section D: Outcomes and Assessments - Page One</b>";

$table = $this->newObject('htmltable', 'htmlelements');


$d1a = new dropdown('d1a');
//$dropdown->size = 150;
$d1a->addOption('');
$d1a->addOption("NQF 5");
$d1a->addOption("NQF 6");
$d1a->addOption("NQF 7");
$d1a->addOption("NQF 8");
if ($mode == "fixup") {
    $d1a->setSelected($d1a);
}
if ($mode == "edit") {
    $d1a->setSelected($formdata['d1a']);
}

/*if ($mode == 'fixup') {
    $documentNumber->setSelected($oldNGF);
}
if ($mode == 'edit') {
    $dropdown->setSelected($formdata['d1a']);
}


$table->startRow();
$table->addCell("D.1.a. On which OLD NQF (National Qualifications Framework) level (e.g. NQF 5, 6, 7 & 8) is the course/unit positioned?:");
$table->endRow();
$table->startRow();
$table->addCell($dropdown->show());
$table->endRow();

//if ($mode == 'edit') {
  //  $table->startRow();
   // $table->addCell($document['refno'] . '-' . $document['version']);
    //$table->startRow();
//} else {
    $table->startRow();
    $table->addCell($d1a->show());
    $table->endRow();
//}

$d1b = new dropdown('d1b');
$d1b->addOption('');
$d1b->addOption("<b>NQF 5</b>");
$d1b->addOption("NQF 6");
$d1b->addOption("NQF 7");
$d1b->addOption("NQF 8");
$d1b->addOption("NQF 9");
$d1b->addOption("NQF 10");
if ($mode == "fixup") {
    $d1b->setSelected($d1b);
}
if ($mode == "edit") {
    $d1b->setSelected($formdata['d1b']);
}


$table->startRow();
$table->addCell("D.1.b. On which NEW NQF (National Qualifications Framework) level (e.g. NQF 5, 6, 7, 8, 9 & 10) is the course/unit positioned?:");
$table->endRow();
//if ($mode == 'edit') {
   // $table->startRow();
   // $table->addCell($document['refno'] . '-' . $document['version']);
  //  $table->endRow();
//} else {
    $table->startRow();
    $table->addCell($d1b->show());
    $table->endRow();
//}
//Section D.2.

$table->startRow();
$table->addCell('D.2. Specify the course/unit outcomes, assessment criteria and methods of assessment in the tables below.');
$table->endRow();

$textarea = new textarea('d2a');
$textarea->size = 60;
$textarea->cols = 100;
if ($mode == "fixup") {
    $textarea->value = $d2a;
}
if ($mode == "edit") {
    $textarea->value = $formdata['d2a'];
}
if ($mode == "fixup") {
    $textarea->value = $d2a;
}
if ($mode == "edit") {
    $textarea->value = $formdata['d2a'];
}

$table->startRow();
$table->addCell('Learning Outcomes of the Course/Unit');
$table->endRow();

$table->startRow();
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('d2b');
$textarea->size = 60;
$textarea->cols = 100;
if ($mode == "fixup") {
    $textarea->value = $d2b;
}
if ($mode == "edit") {
    $textarea->value = $formdata['d2b'];
}
if ($mode == "fixup") {
    $textarea->value = $d2b;
}
if ($mode == "edit") {
    $textarea->value = $formdata['d2b'];
}

$table->startRow();
$table->addCell('Assessment Criteria for the Learning Outcomes');
$table->endRow();

$table->startRow();
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('d2c');
$textarea->size = 60;
$textarea->cols = 100;
if ($mode == "fixup") {
    $textarea->value = $d2c;
}
if ($mode == "edit") {
    $textarea->value = $formdata['d2c'];
}
if ($mode == "fixup") {
    $textarea->value = $d2c;
}
if ($mode == "edit") {
    $textarea->value = $formdata['d2c'];
}

$table->startRow();
$table->addCell('Assessment Methods to be Used');
$table->endRow();

$table->startRow();
$table->addCell($textarea->show());
$table->endRow();

//Section D.3.
$textarea = new textarea('d3');
$textarea->size = 60;
$textarea->cols = 100;
if ($mode == "fixup") {
    $textarea->value = $d3;
}
if ($mode == "edit") {
    $textarea->value = $formdata['d3'];
}
if ($mode == "fixup") {
    $textarea->value = $d3;
}
if ($mode == "edit") {
    $textarea->value = $formdata['d3'];
}

$table->startRow();
$table->addCell('D.3. How do the course/unit outcomes contribute to the acheivement of the overall qualification/programme outcomes?:');
$table->endRow();

$table->startRow();
$table->addCell($textarea->show());
$table->endRow();

//Form
$fs = new fieldset();
$fs->setLegend($legend);
$fs->addContent($table->show());

$hiddenSelected = new hiddeninput('selected', $cfile);
$form->addToForm($hiddenSelected->show());

$efs = new fieldset();
$efs->setLegend('Errors');
if (count($errormessages) > 0) {

    $errorstr = '<ul>';

    foreach ($errormessages as $errormessage) {
        $errorstr.='<li class="error">' . $errormessage . '<li/>';
    }
    $errorstr.='</li>';
    $efs->addContent($errorstr);
    $form->addToForm($efs);
}

$form->addToForm($fs->show());

$button = new button('next', $this->objLanguage->languageText('word_next'));
$button->setToSubmit();
$form->addToForm('<br/>' . $button->show());

$button = new button('back', $this->objLanguage->languageText('word_back'));
$uri = $this->uri(array('action' => 'showsubsidyrequirements'));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$form->addToForm($button->show());

$button = new button('cancel', $this->objLanguage->languageText('word_cancel'));
$uri = $this->uri(array('action' => 'home'));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$form->addToForm($button->show());

echo $form->show();
?>
