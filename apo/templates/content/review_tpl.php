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

$xtitle = $this->objLanguage->languageText('mod_wicid_newdocument', 'wicid', 'Section G: Review');

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
$collaborationandcontractslink = new link($this->uri(array("action" => "addcollaborationandcontracts")));
$collaborationandcontractslink->link = "Collaboration and Contracts";
$contactdetailslink = new link($this->uri(array("action" => "addcontactdetails")));
$contactdetailslink->link = "Contact Details";

$links = $doclink->show() . '&nbsp;|&nbsp;' . $overviewlink->show() . '&nbsp;|&nbsp;' .
        $rulesandsyllabusonelink->show() . $rulesandsyllabustwolink->show() . '&nbsp;|&nbsp;' .
        $subsidyrequirementslink->show() . '&nbsp;|&nbsp;' . $outcomesandassessmentonelink->show() . '&nbsp;|&nbsp;' .
        $outcomesandassessmenttwolink->show() . '&nbsp;|&nbsp;' . $outcomesandassessmentthreelink->show() . '&nbsp;|&nbsp;' .
        $resourceslink->show() . '&nbsp;|&nbsp;' . $collaborationandcontractslink->show() . '&nbsp;|&nbsp;' .
        "<b>Review</b>" . '&nbsp;|&nbsp;' . $contactdetailslink->show() . '<br/>';
$fs = new fieldset();
$fs->setLegend('Forms');
$fs->addContent($links);

echo $fs->show() . '<br/>';

$legend = "Review";

$form = new form('reviewform');

$table = $this->newObject('htmltable', 'htmlelements');

$textarea = new textarea('g1a');

$table->startRow();
$table->addCell('<b>G.1.a How will the course/unit syllabus be reviewed?:</b>');
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('g1b');

$table->startRow();
$table->addCell('<b>G.1.b How often will the course/unit syllabus be reviewed?:</b>');
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('g2a');

$table->startRow();
$table->addCell('<b>G.2.a How will integration of course/unit outcome, syllabus, teaching methods and assessment methods be evaluated?:</b>');
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('g2b');

$table->startRow();
$table->addCell('<b>G.2.b How often will the above integration be reviewed?:</b>');
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('g3a');

$table->startRow();
$table->addCell('<b>G.3.a How will the course/unit through-put rate be evaluated?:</b>');
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('g3b');

$table->startRow();
$table->addCell('<b>G.3.b How often will the course/unit through-put be reviewed?:</b>');
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('g4a');

$table->startRow();
$table->addCell('<b>G.4.a How will theteaching on the course/unit be evaluated from a students perspective and from a lectures perspective?:</b>');
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('g4b');

$table->startRow();
$table->addCell('<b>G.4.b How often will the teaching on the course/unit be evaluated from these two perspectives?:</b>');
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
