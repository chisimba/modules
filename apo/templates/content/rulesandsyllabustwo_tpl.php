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

$form = new form('rulesandsyllabustwoform');

$xtitle = $this->objLanguage->languageText('mod_wicid_document', 'wicid', 'Section B: Rules and Syllabus');

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

$links = $doclink->show() . '&nbsp;|&nbsp;' . $overviewlink->show() . '&nbsp;|&nbsp;' .
        $rulesandsyllabusonelink->show() . '&nbsp;|&nbsp;' . "<b>Rules and Syllabus (page two)</b>" . '&nbsp;|&nbsp;' .
        $subsidyrequirementslink->show() . '&nbsp;|&nbsp;' . $outcomesandassessmentonelink->show() . '&nbsp;|&nbsp;' .
        $outcomesandassessmenttwolink->show() . '&nbsp;|&nbsp;' . $outcomesandassessmentthreelink->show() . '&nbsp;|&nbsp;' .
        $resourceslink->show() . '&nbsp;|&nbsp;' . $collaborationandcontractslink->show() . '&nbsp;|&nbsp;' .
        $reviewlink->show() . '&nbsp;|&nbsp;' . $contactdetailslink->show() . '<br/>';
$fs = new fieldset();
$fs->setLegend('<b>Forms</b>');
$fs->addContent($links);

echo $fs->show() . '<br/>';

$table = $this->newObject('htmltable', 'htmlelements');

$radio = new radio ('b5a');
$radio->addOption('1'," a 1st year unit");
$radio->addOption('2',"a 2nd year unit");
$radio->addOption('3',"a 3rd year unit");
$radio->addOption('4',"a 4th year unit");
$radio->addOption('5',"a 5th year unit");
$radio->addOption('6',"a 6th year unit");
$radio->addOption('7',"an honours unit");
$radio->addOption('8',"a postgraduate diploma unit");
$radio->addOption('9',"a masters unit");
$radio->setSelected('1');
$radio->setBreakSpace('</p><p>');
$table->startRow();
$table->addCell("B.5.a. At what level is the course/unit taught?");
$table->addCell($radio->show());
$table->endRow();

$textarea = new textarea('b5b');
$textarea->height = '70px';
$textarea->width = '500px';
$table->startRow();
$table->addCell("B.5.b. In which year/s of study is the course/unit to be taught? ");
$table->addCell($textarea->show());
$table->endRow();

$radio = new radio ('b6a');
$radio->addOption('1',"full year unit offered in semester 1 and 2");
$radio->addOption('2',"half year unit offered in semester 1");
$radio->addOption('3',"half year unit offered in semester 2");
$radio->addOption('4',"half year unit offered in semester 1 and 2");
$radio->addOption('5',"block unit offered in block 1");
$radio->addOption('6',"block unit offered in block 2");
$radio->addOption('7',"block unit offered in block 3");
$radio->addOption('8',"block unit offered in block 4");
$radio->addOption('9',"attendance course/unit");
$radio->addOption('9',"other");
$radio->setSelected('1');
$radio->setBreakSpace('</p><p>');
$table->startRow();
$table->addCell("B.6.a. This is a:");
$table->addCell($radio->show());
$table->endRow();

$textarea = new textarea('b6b');
$textarea->height = '70px';
$textarea->width = '500px';
$table->startRow();
$table->addCell("B.6.b. If ‘other’, provide details of the course/unit duration and/or the number of lectures which comprise the course/unit:");
$table->addCell($textarea->show());
$table->endRow();

$radio = new radio ('b6c');
$radio->addOption('y',"yes");
$radio->addOption('n',"no");
$radio->setSelected('1');
$radio->setBreakSpace('</p><p>');
$table->startRow();
$table->addCell("B.6.c.Is the unit assessed:");
$table->addCell($radio->show());
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

$legend = "<b>B: Rules and Syllabus (page 1)</b>";
$fs = new fieldset();
$fs->setLegend($legend);
$fs->addContent($table->show());
$form->addToForm($fs->show());

$button = new button('next', $this->objLanguage->languageText('word_next'));
$uri = $this->uri(array('action' => 'addoutcomesandassessmentthree'));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$form->addToForm($button->show());

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

