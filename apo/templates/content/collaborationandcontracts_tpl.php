<?php
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

$xtitle = $this->objLanguage->languageText('mod_wicid_newdocument', 'wicid', 'Section F: Collaboration and Contacts');

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

$resourceslink = new link($this->uri(array("action" => "addresources")));
$resourceslink->link = "Resources";

$reviewlink = new link($this->uri(array("action" => "addreview")));
$reviewlink->link = "Review";

$contactdetailslink = new link($this->uri(array("action" => "addcontactdetails")));
$contactdetailslink->link = "Contact Details";

$links = $doclink->show() . '&nbsp;|&nbsp;' . $overviewlink->show() . '&nbsp;|&nbsp;' .
        $rulesandsyllabusonelink->show() . '&nbsp;|&nbsp;' . $rulesandsyllabustwolink->show() . '&nbsp;|&nbsp;' .
        $subsidyrequirementslink->show() . '&nbsp;|&nbsp;' . $outcomesandassessmentonelink->show() . '&nbsp;|&nbsp;' .
        $outcomesandassessmenttwolink->show() . '&nbsp;|&nbsp;' . $outcomesandassessmentthreelink->show() . '&nbsp;|&nbsp;' .
        $resourceslink->show() . '&nbsp;|&nbsp;' . "<b>Collaboration and Contracts</b>" . '&nbsp;|&nbsp;' .
        $reviewlink->show() . '&nbsp;|&nbsp;' . $contactdetailslink->show() . '<br/>';

$fs = new fieldset();
$fs->setLegend('<b>Navigation</b>');
$fs->addContent($links);

echo $fs->show() . '<br/>';

$legend = "<b>Section F: Collaboration and Contacts</b>";
$action = 'addreview';
$form = new form('collaborationandcontactsform', $this->uri(array('action' => $action, 'id' => $id, 'formname'=>'collaborationandcontracts')));

$table = $this->newObject('htmltable', 'htmlelements');

$F1a = new dropdown('f1a');
$F1a->addOption("Yes");
$F1a->addOption("No");

if ($mode == 'fixup') {
    $documentNumber->setSelected($f2a);
}
if ($mode == 'edit') {
    $documentNumber->setSelected(substr($document['refno'], 0, 1));
}
$table->startRow();
$table->addCell("F.1.a Is approval for the course/unit required from a professional body?:");
if ($mode == 'edit') {
    $table->addCell($document['refno'] . '-' . $document['version']);
} else {
    $table->addCell($F1a->show());
}
$table->endRow();

$textarea = new textarea('f1b');

$table->startRow();
$table->addCell('F.1.b If yes, state the name of the professional body and provide details of the bodys prerequisites and/or contacts.:');
$table->addCell($textarea->show());
$table->endRow();

$F2a = new dropdown('f2a');
$F2a->addOption("Yes");
$F2a->addOption("No");

if ($mode == 'fixup') {
    $documentNumber->setSelected($f2a);
}
if ($mode == 'edit') {
    $documentNumber->setSelected(substr($document['refno'], 0, 1));
}
$table->startRow();
$table->addCell("F.2.a Are other Schools or Faculties involved in and/or have interest in the course?:");
if ($mode == 'edit') {
    $table->addCell($document['refno'] . '-' . $document['version']);
} else {
    $table->addCell($F2a->show());
}
$table->endRow();

$textarea = new textarea('f2b');

$table->startRow();
$table->addCell('F.2.b If yes, provide the details of the other Schools or Fucalties involvement/interest, including support and provision for the course/unit.:');
$table->addCell($textarea->show());
$table->endRow();

$F3a = new dropdown('f3a');
$F3a->addOption("Yes");
$F3a->addOption("No");

if ($mode == 'fixup') {
    $documentNumber->setSelected($f2a);
}
if ($mode == 'edit') {
    $documentNumber->setSelected(substr($document['refno'], 0, 1));
}
$table->startRow();
$table->addCell("F.3.a Does the course/unit provide service learning?:");
if ($mode == 'edit') {
    $table->addCell($document['refno'] . '-' . $document['version']);
} else {
    $table->addCell($F3a->show());
}
$table->endRow();

$textarea = new textarea('f3b');

$table->startRow();
$table->addCell('F.3.b If yes, provide the details on the nature as well as the provisioning for the service learning component and methodology.:');
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('f4');

$table->startRow();
$table->addCell('F.4 Specify whether collaboration, contacts or other cooperation agreements have been, or will need to be, entered into with entities outside of the university?:');
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

$fs = new fieldset();
$fs->setLegend($legend);
$fs->addContent($table->show());
$form->addToForm($fs->show());

$button = new button('next', $this->objLanguage->languageText('word_next'));
$button->setToSubmit();
$form->addToForm('<br/>' .$button->show());

$button = new button('back', $this->objLanguage->languageText('word_back'));
$uri = $this->uri(array('action' => 'addresources'));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$form->addToForm($button->show());

$button = new button('cancel', $this->objLanguage->languageText('word_cancel'));
$uri = $this->uri(array('action' => 'home'));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$form->addToForm($button->show());


echo $form->show();
?>