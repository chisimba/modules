<?php
/*
 * @category  Chisimba
 * @package   apo (Academic Planning Office)
 * @author    Jacqueline Gil
 */

$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('fieldset', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('iframe', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('radio', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');

$this->setVar('pageSuppressXML', TRUE);
$this->baseDir = $this->objSysConfig->getValue('FILES_DIR', 'wicid');
$action = 'addrulesandsyllabustwo';

$form = new form('rulesandsyllabusoneform', $this->uri(array('action' => $action)));

$xtitle = $this->objLanguage->languageText('mod_wicid_document', 'wicid', 'Section B: Rules and Syllabus');

$header = new htmlheading();
$header->type = 2;
$header->str = $xtitle;

echo $header->show();

$doclink = new link($this->uri(array("action" => "editdocument")));
$doclink->link = "Document";

$overviewlink = new link($this->uri(array("action" => "addoverview")));
$overviewlink->link = "Overview";

$rulesandsyllabustwolink = new link($this->uri(array("action" => "addrulesandsyllabustwo")));
$rulesandsyllabustwolink->link = "Rules and Syllabus - Page Two";

$subsidyrequirementslink = new link($this->uri(array("action" => "addsubsidyrequirements")));
$subsidyrequirementslink->link = "Subsidy Requirements";

$outcomesandassessmentonelink = new link($this->uri(array("action" => "addoutcomesandassessmentone")));
$outcomesandassessmentonelink->link = "Outcomes and Assessment - Page One";

$outcomesandassessmenttwolink = new link($this->uri(array("action" => "addoutcomesandassessmenttwo")));
$outcomesandassessmenttwolink->link = "Outcomes and Assessment - Page Two";

$outcomesandassessmentthreelink = new link($this->uri(array("action" => "addoutcomesandassessmentthree")));
$outcomesandassessmentthreelink->link = "Outcomes and Assessment - Page Three";

$resourceslink = new link($this->uri(array("action" => "addresources")));
$resourceslink->link = "Resources";

$collaborationandcontractslink = new link($this->uri(array("action" => "addcollaborationandcontracts")));
$collaborationandcontractslink->link = "Collaboration and Contracts";

$reviewlink = new link($this->uri(array("action" => "addreview")));
$reviewlink->link = "Review";

$contactdetailslink = new link($this->uri(array("action" => "addcontactdetails")));
$contactdetailslink->link = "Contact Details";

$links = $doclink->show() . '&nbsp;|&nbsp;' . $overviewlink->show() . '&nbsp;|&nbsp;' .
        "<b>Rules and Syllabus - Page One</b>" . '&nbsp;|&nbsp;' . $rulesandsyllabustwolink->show() . '&nbsp;|&nbsp;' .
        $subsidyrequirementslink->show() . '&nbsp;|&nbsp;' . $outcomesandassessmentonelink->show() . '&nbsp;|&nbsp;' .
        $outcomesandassessmenttwolink->show() . '&nbsp;|&nbsp;' . $outcomesandassessmentthreelink->show() . '&nbsp;|&nbsp;' .
        $resourceslink->show() . '&nbsp;|&nbsp;' . $collaborationandcontractslink->show() . '&nbsp;|&nbsp;' .
        $reviewlink->show() . '&nbsp;|&nbsp;' . $contactdetailslink->show() . '<br/>';

$fs = new fieldset();
$fs->setLegend('<b>Navigation</b>');
$fs->addContent($links);

echo $fs->show() . '<br/>';

$table = $this->newObject('htmltable', 'htmlelements');

$textarea = new textarea('b1');
$textarea->height = '70px';
$textarea->width = '500px';
if ($mode == "fixup") {
    $textarea->value = $b1;
}
$table->startRow();
$table->addCell("B.1. How does this course/unit change the rules for the curriculum?");
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('b2');
$textarea->height = '70px';
$textarea->width = '500px';
if ($mode == "fixup") {
    $textarea->value = $b2;
}
$table->startRow();
$table->addCell("B.2. Describe the course/unit syllabus:");
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('b3a');
$textarea->height = '70px';
$textarea->width = '500px';
if ($mode == "fixup") {
    $textarea->value = $b3a;
}
$table->startRow();
$table->addCell("B.3. a. What are the pre-requisites for the course/unit if any?");
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('b3b');
$textarea->height = '70px';
$textarea->width = '500px';
if ($mode == "fixup") {
    $textarea->value = $b3b;
}
$table->startRow();
$table->addCell("B.3.b. What are the co-requisites for the course/unit if any?");
$table->addCell($textarea->show());
$table->endRow();

$radio = new radio ('b4a');
$radio->addOption('1',"a compulsory course/unit");
$radio->addOption('2',"an optional course/unit");
$radio->addOption('3',"both compulsory and optional as the course/unit is offered toward qualifications/ programmes with differing curriculum structures ");
$radio->setSelected('1');
$radio->setBreakSpace('</p><p>');
if ($mode == "fixup") {
    $radio->setSelected($b4a);
}
$table->startRow();
$table->addCell("B.4.a. This is:");
$table->addCell($radio->show());
$table->endRow();

$textarea = new textarea('b4b');
$textarea->height = '70px';
$textarea->width = '500px';
if ($mode == "fixup") {
    $textarea->value = $b4b;
}
$table->startRow();
$table->addCell("B.4.b. If it is a compulsory course/unit, which course/unit is it replacing, or is the course/unit to be taken by students in addition to the current workload of courses/unit?");
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('b4c');
$textarea->height = '70px';
$textarea->width = '500px';
if ($mode == "fixup") {
    $textarea->value = $b4c;
}
$table->startRow();
$table->addCell("B.4.c. If it is both a compulsory and optional course/unit, provide details explaining for which qualifications/ programmes the course/unit would be optional and for which it would be compulsory:");
$table->addCell($textarea->show());
$table->endRow();

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

$legend = "<b>Section B: Rules and Syllabus - Page One</b>";
$fs = new fieldset();
$fs->setLegend($legend);
$fs->addContent($table->show());
$form->addToForm($fs->show());



$button = new button('next', $this->objLanguage->languageText('word_next'));
$button->setToSubmit();
$form->addToForm('<br/>'.$button->show());

$button = new button('back', $this->objLanguage->languageText('word_back'));
$uri = $this->uri(array('action' => 'addoverview'));
$button->setToSubmit('javascript: window.location=\'' . $uri . '\'');
$form->addToForm($button->show());

$button = new button('cancel', $this->objLanguage->languageText('word_cancel'));
$uri = $this->uri(array('action' => 'home'));;
$button->setToSubmit('javascript: window.location=\'' . $uri . '\'');
$form->addToForm($button->show());

echo $form->show();
?>
