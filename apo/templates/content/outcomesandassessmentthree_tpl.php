<?php
 /*
 * @category  Chisimba
 * @package   apo (Academic Planning Office)
 * @author    Jacqueline Gil
 */
$this->loadClass('fieldset', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('iframe', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');

$this->setVar('pageSuppressXML', TRUE);
$this->baseDir = $this->objSysConfig->getValue('FILES_DIR', 'wicid');
$id=$this->getParam('id');

$action = 'addresources';
$form = new form('outcomesandassessmentthreeform', $this->uri(array('action' => $action, 'id' => $id, 'formname'=>'outcomesandassessmentthree')));

$xtitle = $this->objLanguage->languageText('mod_wicid_document', 'wicid', 'Section D: Outcomes and Assessment');

$header = new htmlheading();
$header->type = 2;
$header->str = $xtitle;

echo $header->show();

$doclink = new link($this->uri(array("action" => "editdocument")));
$doclink->link = "Document";

$overviewlink = new link($this->uri(array("action" => "addoverview")));
$overviewlink->link = "Overview";

$rulesandsyllabusonelink = new link($this->uri(array("action" => "addrulesandsyllabusone")));
$rulesandsyllabusonelink->link = "Rules and Syllabus - Page One";

$rulesandsyllabustwolink = new link($this->uri(array("action" => "addrulesandsyllabustwo")));
$rulesandsyllabustwolink->link = "Rules and Syllabus - Page Two";

$subsidyrequirementslink = new link($this->uri(array("action" => "addsubsidyrequirements")));
$subsidyrequirementslink->link = "Subsidy Requirements";

$outcomesandassessmentonelink = new link($this->uri(array("action" => "addoutcomesandassessmentone")));
$outcomesandassessmentonelink->link = "Outcomes and Assessment - Page One";

$outcomesandassessmenttwolink = new link($this->uri(array("action" => "addoutcomesandassessmenttwo")));
$outcomesandassessmenttwolink->link = "Outcomes and Assessment - Page Two";

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
        $subsidyrequirementslink->show() . '&nbsp;|&nbsp;' . $outcomesandassessmentonelink->show() . '&nbsp;|&nbsp;' .
        $outcomesandassessmenttwolink->show() . '&nbsp;|&nbsp;' . "<b>Outcomes and Assessment - Page Three</b>" . '&nbsp;|&nbsp;' .
        $resourceslink->show() . '&nbsp;|&nbsp;' . $collaborationandcontractslink->show() . '&nbsp;|&nbsp;' .
        $reviewlink->show() . '&nbsp;|&nbsp;' . $contactdetailslink->show() . '<br/>';

$fs = new fieldset();
$fs->setLegend('<b>Navigation</b>');
$fs->addContent($links);

echo $fs->show() . '<br/>';

$label = new label();
$label->labelValue = "<b><i>D.5. Specify the notional study hours expected for the duration of the course/unit using the spreadsheet provided.</b></i>";

$table = $this->newObject('htmltable', 'htmlelements');
$table->border = 2;
$table->cellpadding = '2';
$table->cellspacing='3';

$textinput = new textinput('a');
$textinput->size = 10;
if ($mode == "fixup") {
    $textinput->value = $a;
}
$table->startRow();
$table->addCell("a. Over how many weeks will this course run?");
$table->addCell($textinput->show());
$table->endRow();

$textinput = new textinput('b');
$textinput->size = 10;
if ($mode == "fixup") {
    $textinput->value = $b;
}
$table->startRow();
$table->addCell("b. How many hours of teaching will a particular student experience for this specific course in a single week?");
$table->addCell($textinput->show());
$table->endRow();

$textinput = new textinput('c');
$textinput->size = 10;
if ($mode == "fixup") {
    $textinput->value = $c;
}
$table->startRow();
$table->addCell("c. How many hours of tutorials will a particular student experience for this specific course in a single week?");
$table->addCell($textinput->show());
$table->endRow();

$textinput = new textinput('d');
$textinput->size = 10;
if ($mode == "fixup") {
    $textinput->value = $d;
}
$table->startRow();
$table->addCell("d. How many lab hours will a particular student experience for this specific course in a single week? (Note: the assumption is that there is only one staff contact hour per lab, the remaining lab time is student self-study)");
$table->addCell($textinput->show());
$table->endRow();

$textinput = new textinput('e');
$textinput->size = 10;
if ($mode == "fixup") {
    $textinput->value = $e;
}
$table->startRow();
$table->addCell("e. How many other contact sessions are there each week including periods used for testd or other assessments which have not been included in the number of lecture, tutorial or laboratory sessions.");
$table->addCell($textinput->show());
$table->endRow();

$label = new label();
$label->forId = 'totalcontacttime';
if ($mode == "fixup") {
    $label->labelValue = $totalContactTime;
}
$table->startRow();
$table->addCell("<b>Total contact time</b>");
$table->addCell("<b>".$label->labelValue."</b>");
$table->endRow();

$textinput = new textinput('f');
$textinput->size = 10;
if ($mode == "fixup") {
    $textinput->value = $f;
}
$table->startRow();
$table->addCell("f. For every hour of lectures or contact with a staff member, how many hours should the student spend studying by her/himself?");
$table->addCell($textinput->show());
$table->endRow();

$label = new label();
$label->forId = 'studyhoursnoexam';
if ($mode == "fixup") {
    $label->labelValue = $totalstudyhoursNoexam;
}
$table->startRow();
$table->addCell("<b>Total notional study hours (excluding the exams)</b>");
$table->addCell("<b>".$label->labelValue."</b>");
$table->endRow();

$textinput = new textinput('g');
$textinput->size = 10;
if ($mode == "fixup") {
    $textinput->value = $g;
}
$table->startRow();
$table->addCell("g. How many exams are there per year?");
$table->addCell($textinput->show());
$table->endRow();

$textinput = new textinput('h');
$textinput->size = 10;
if ($mode == "fixup") {
    $textinput->value = $h;
}
$table->startRow();
$table->addCell("h. How long is each exam?");
$table->addCell($textinput->show());
$table->endRow();

$label = new label();
$label->forId = 'totalexamtime';
if ($mode == "fixup") {
    $label->labelValue = $totalExamTime;
}
$table->startRow();
$table->addCell("<b>Total exam time per year</b>");
$table->addCell("<b>".$label->labelValue."</b>");
$table->endRow();

$textinput = new textinput('i');
$textinput->size = 10;
if ($mode == "fixup") {
    $textinput->value = $i;
}
$table->startRow();
$table->addCell("i. How many hours of preparation for the exams is the student expected to undertake?");
$table->addCell($textinput->show());
$table->endRow();

$label = new label();
$label->forId = 'totalstudyhours';
if ($mode == "fixup") {
    $label->labelValue = $totalstudyhoursExam;
}
$table->startRow();
$table->addCell("<b>Total notional study hours</b>");
$table->addCell("<b>".$label->labelValue."</b>");
$table->endRow();

$label = new label();
$label->forId = 'saqa';
if ($mode == "fixup") {
    $label->labelValue = $totalSAQAcredits;
}
$table->startRow();
$table->addCell("<b>Total SAQA Credits</b>");
$table->addCell("<b>".$label->labelValue."</b>");
$table->endRow();

$button = new button('calculate', "Calculate");
$uri = $this->uri(array('action' => 'calculatespreedsheet'));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$table->startRow();
$table->addCell(" ");
$table->addCell($button->show());
$table->endRow();

$efs = new fieldset();
$efs->setLegend('Errors');
if (count($errormessages) > 0) {

    $errorstr = '<ul>';

    foreach ($errormessages as $errormessage) {
        $errorstr.='<li class="error">' . $errormessage ;//. '<li/>';
    }
    $errorstr.='</li>';
    $efs->addContent($errorstr);
    $form->addToForm($efs);
}

$legend = "<b>Section D: Outcomes and Assessment - Page Three</b>";
$fs = new fieldset();
$fs->setLegend($legend);
$fs->addContent($label->show());
$fs->addContent($table->show());
$form->addToForm($fs->show());

$button = new button('next', $this->objLanguage->languageText('word_next'));
$button->setToSubmit();
$form->addToForm('<br/>'.$button->show());

$button = new button('back', $this->objLanguage->languageText('word_back'));
$uri = $this->uri(array('action' => 'addoutcomesandassessmenttwo'));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$form->addToForm($button->show());

$button = new button('cancel', $this->objLanguage->languageText('word_cancel'));
$uri = $this->uri(array('action' => 'home'));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$form->addToForm($button->show());

echo $form->show();
?>

