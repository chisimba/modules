<?php

/*
 * @category  Chisimba
 * @package   apo (Academic Planning Office)
 * @author    Jacqueline Gil
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
if ($formdata != null) {
    $mode = "edit";
}

$action = 'showoutcomesandassessmenttwo';
$form = new form('outcomesandassessmentoneScienceform', $this->uri(array('action' => $action, 'id' => $id, 'formname' => 'outcomesandassessmentoneScience')));

$xtitle = $this->objLanguage->languageText('mod_wicid_newdocument', 'wicid', 'Section D: Outcomes and Assessment - Page One');

$header = new htmlheading();
$header->type = 2;
$header->str = $xtitle;

echo $header->show();

$doclink = new link($this->uri(array("action" => "editdocument","id"=>$id)));
$doclink->link = "Document";

$overviewlink = new link($this->uri(array("action" => "showoverview","id"=>$id)));
$overviewlink->link = "Overview";

$rulesandsyllabusonelink = new link($this->uri(array("action" => "showrulesandsyllabusone","id"=>$id)));
$rulesandsyllabusonelink->link = "Rules and Syllabus - Page One";

$rulesandsyllabustwolink = new link($this->uri(array("action" => "showrulesandsyllabustwo","id"=>$id)));
$rulesandsyllabustwolink->link = "Rules and Syllabus - Page Two";

$subsidyrequirementslink = new link($this->uri(array("action" => "showsubsidyrequirements","id"=>$id)));
$subsidyrequirementslink->link = "Subsidy Requirements";

$outcomesandassessmentonelink = new link($this->uri(array("action" => "showoutcomesandassessmentone","id"=>$id)));
$outcomesandassessmentonelink->link = "Outcomes and Assessment - Page One";

$outcomesandassessmenttwolink = new link($this->uri(array("action" => "showoutcomesandassessmenttwo","id"=>$id)));
$outcomesandassessmenttwolink->link = "Outcomes and Assessment - Page Two";

$outcomesandassessmentthreelink = new link($this->uri(array("action" => "showoutcomesandassessmentthree","id"=>$id)));
$outcomesandassessmentthreelink->link = "Outcomes and Assessment - Page Three";

$resourceslink = new link($this->uri(array("action" => "showresources","id"=>$id)));
$resourceslink->link = "Resources";

$collaborationandcontractslink = new link($this->uri(array("action" => "showcollaborationandcontracts","id"=>$id)));
$collaborationandcontractslink->link = "Collaboration and Contracts";

$reviewlink = new link($this->uri(array("action" => "showreview","id"=>$id)));
$reviewlink->link = "Review";

$contactdetailslink = new link($this->uri(array("action" => "showcontactdetails","id"=>$id)));
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

//Section D.1.

$dropdown = new dropdown('d1');
$dropdown->addOption('');
$dropdown->addOption("<b>NQF 5</b>");
$dropdown->addOption("NQF 6");
$dropdown->addOption("NQF 7");
$dropdown->addOption("NQF 8");
$dropdown->addOption("NQF 9");
$dropdown->addOption("NQF 10");

if ($mode == 'fixup') {
    $dropdown->setSelected($d1);
}
if ($mode == 'edit') {
    $dropdown->setSelected($formdata['d1']);
}

$table = $this->newObject('htmltable', 'htmlelements');
$table->startRow();
$table->addCell("D.1. On which NQF (National Qualifications Framework) level (e.g. NQF 5, 6, 7, 8, 9 & 10) is the course/unit positioned?:");
$table->endRow();
$table->startRow();
$table->addCell($dropdown->show());
$table->endRow();

//Section D.2.

$textarea = new textarea('d21');
$textarea->size = 60;
$textarea->cols = 100;
if ($mode == "fixup") {
    $textarea->value = $d21;
}
if ($mode == "edit") {
    $textarea->value = $formdata['d21'];
}

$table->startRow();
$table->addCell('D.2.1. What are the basic aims of this course/unit?');
$table->endRow();

$table->startRow();
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('d22');
$textarea->size = 60;
$textarea->cols = 100;
if ($mode == "fixup") {
    $textarea->value = $d22;
}
if ($mode == "edit") {
    $textarea->value = $formdata['d22'];
}

$table->startRow();
$table->addCell('D.2.2. What is the main content that will be covered within this course/unit?');
$table->endRow();

$table->startRow();
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('d23');
$textarea->size = 60;
$textarea->cols = 100;
if ($mode == "fixup") {
    $textarea->value = $d23;
}
if ($mode == "edit") {
    $textarea->value = $formdata['d23'];
}

$table->startRow();
$table->addCell('D.2.3. What are the outcomes you expect a learner to have achieved on completion of the course/unit?');
$table->endRow();

$table->startRow();
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('d24');
$textarea->size = 60;
$textarea->cols = 100;
if ($mode == "fixup") {
    $textarea->value = $d24;
}
if ($mode == "edit") {
    $textarea->value = $formdata['d24'];
}

$table->startRow();
$table->addCell('D.2.4. What are the criteris you will use to assess achievement of the specific learning outcomes identified in question D.2.3 above?');
$table->endRow();

$table->startRow();
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('d25');
$textarea->size = 60;
$textarea->cols = 100;
if ($mode == "fixup") {
    $textarea->value = $d25;
}
if ($mode == "edit") {
    $textarea->value = $formdata['d25'];
}

$table->startRow();
$table->addCell('D.2.5. What are the assessment methods that will be used? Specify the weighting of each.');
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
