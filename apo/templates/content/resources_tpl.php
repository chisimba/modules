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
$this->loadClass('textarea', 'htmlelements');

$action='addcollaborationandcontracts';
$form = new form('resourcesform', $this->uri(array('action' => $action, 'id' => $id, 'formname'=>'resources')));

$xtitle = $this->objLanguage->languageText('mod_wicid_document', 'wicid', 'Section E: Resources');

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

$outcomesandassessmentonelink = new link($this->uri(array("action" => "addoutcomesandassessmentone")));
$outcomesandassessmentonelink->link = "Outcomes and Assessment (page one)";

$outcomesandassessmenttwolink = new link($this->uri(array("action" => "addoutcomesandassessmenttwo")));
$outcomesandassessmenttwolink->link = "Outcomes and Assessment (page two)";

$outcomesandassessmentthreelink = new link($this->uri(array("action" => "addoutcomesandassessmentthree")));
$outcomesandassessmentthreelink->link = "Outcomes and Assessment (page three)";

$collaborationandcontractslink = new link($this->uri(array("action" => "addcollaborationandcontracts")));
$collaborationandcontractslink->link = "Collaboration and Contracts";

$reviewlink = new link($this->uri(array("action" => "addreview")));
$reviewlink->link = "Review";

$contactdetailslink = new link($this->uri(array("action" => "addcontactdetails")));
$contactdetailslink->link = "Contact Details";

$links = $doclink->show() . '&nbsp;|&nbsp;' . $overviewlink->show() . '&nbsp;|&nbsp;' .
        $rulesandsyllabusonelink->show() . '&nbsp;|&nbsp;' . $rulesandsyllabustwolink->show() . '&nbsp;|&nbsp;' .
        $subsidyrequirementslink->show() . '&nbsp;|&nbsp;' . $outcomesandassessmentonelink->show() . '&nbsp;|&nbsp;' .
        $outcomesandassessmenttwolink->show() . '&nbsp;|&nbsp;' . $outcomesandassessmentthreelink->show() . '&nbsp;|&nbsp;' .
        "<b>Resources</b>" . '&nbsp;|&nbsp;' . $collaborationandcontractslink->show() . '&nbsp;|&nbsp;' .
        $reviewlink->show() . '&nbsp;|&nbsp;' . $contactdetailslink->show() . '<br/>';

$fs = new fieldset();
$fs->setLegend('<b>Navigation</b>');
$fs->addContent($links);

echo $fs->show() . '<br/>';

$table = $this->newObject('htmltable', 'htmlelements');

$textarea = new textarea('e1a');
$textarea->height = '70px';
$textarea->width = '500px';
if ($mode == "fixup") {
    $textarea->value = $he1a;
}
$table->startRow();
$table->addCell("E.1.a. Is there currently adequate teaching capacity with regard to the introduction of the course/unit? ");
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('e1b');
$textarea->height = '70px';
$textarea->width = '500px';
if ($mode == "fixup") {
    $textarea->value = $e1b;
}
$table->startRow();
$table->addCell("E.1.b. Who will teach the course/unit?");
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('e2a');
$textarea->height = '70px';
$textarea->width = '500px';
if ($mode == "fixup") {
    $textarea->value = $e2a;
}
$table->startRow();
$table->addCell("E.2.a. How many students will the course/unit attract?");
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('e2b');
$textarea->height = '70px';
$textarea->width = '500px';
if ($mode == "fixup") {
    $textarea->value = $e2b;
}
$table->startRow();
$table->addCell("E.2.b. How has this been factored into the enrolment planning in your Faculty?");
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('e2c');
$textarea->height = '70px';
$textarea->width = '500px';
if ($mode == "fixup") {
    $textarea->value = $e2c;
}
$table->startRow();
$table->addCell("E.2.c. How has it been determined if the course/unit is sustainable in the long term, or short term if of topical interest?");
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('e3a');
$textarea->height = '70px';
$textarea->width = '500px';
if ($mode == "fixup") {
    $textarea->value = $e3a;
}
$table->startRow();
$table->addCell("E.3.a. Specify the space requirements for the course/unit:");
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('e3b');
$textarea->height = '70px';
$textarea->width = '500px';
if ($mode == "fixup") {
    $textarea->value = $e3b;
}
$table->startRow();
$table->addCell("E.3.b. Specify the IT teaching resources required for the course/unit:");
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('e3c');
$textarea->height = '70px';
$textarea->width = '500px';
if ($mode == "fixup") {
    $textarea->value = $e3c;
}
$table->startRow();
$table->addCell("E.3.c. Specify the library resources required to teach the course/unit:");
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('e4');
$textarea->height = '70px';
$textarea->width = '500px';
if ($mode == "fixup") {
    $textarea->value = $e4;
}
$table->startRow();
$table->addCell("E.4. Does the School intend to offer the course/unit in addition to its current course/unit offerings, or is the intention to eliminate an existing course/unit?");
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('e5a');
$textarea->height = '70px';
$textarea->width = '500px';
if ($mode == "fixup") {
    $textarea->value = $e5a;
}
$table->startRow();
$table->addCell("E.5.a. Specify the name of the course/unit co-ordinator:");
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('e5b');
$textarea->height = '70px';
$textarea->width = '500px';
if ($mode == "fixup") {
    $textarea->value = $e5b;
}
$table->startRow();
$table->addCell("E.5.b. State the Staff number of the course/unit coordinator (consult your Faculty Registrar):");
$table->addCell($textarea->show());
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

$legend = "<b>E: Resources</b>";
$fs = new fieldset();
$fs->setLegend($legend);
$fs->addContent($table->show());
$form->addToForm($fs->show());

$button = new button('next', $this->objLanguage->languageText('word_next'));
$button->setToSubmit();
$form->addToForm('<br/>'.$button->show());

$button = new button('back', $this->objLanguage->languageText('word_back'));
$uri = $this->uri(array('action' => 'addoutcomesandassessmentthree'));
$button->setToSubmit('javascript: window.location=\'' . $uri . '\'');
$form->addToForm($button->show());

$button = new button('cancel', $this->objLanguage->languageText('word_cancel'));
$uri = $this->uri(array('action' => 'home'));
$button->setToSubmit('javascript: window.location=\'' . $uri . '\'');
$form->addToForm($button->show());

echo $form->show();
?>
