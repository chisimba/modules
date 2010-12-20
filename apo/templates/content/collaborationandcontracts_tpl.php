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

$xtitle = $this->objLanguage->languageText('mod_wicid_document', 'wicid', 'Section F: Collaboration and Contacts');

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
$fs->setLegend('<b>Forms</b>');
$fs->addContent($links);

echo $fs->show() . '<br/>';

$legend = "<b>Collaboration and Contracts</b>";

$form = new form('collaborationandcontactsform');

$table = $this->newObject('htmltable', 'htmlelements');

$radio = new radio ('f1a');
$radio->addOption('1',"Yes");
$radio->addOption('2',"No");
$radio->setSelected('1');
$table->startRow();
$table->addCell("F.1.a Is approval for the course/unit required from a professional body?:");
$table->addCell($radio->show());
$table->endRow();

$textarea = new textarea('f1b');
$table->startRow();
$table->addCell('F.1.b If yes, state the name of the professional body and provide details of the bodys prerequisites and/or contacts.:');
$table->addCell($textarea->show());
$table->endRow();

$radio = new radio ('f2a');
$radio->addOption('1',"Yes");
$radio->addOption('2',"No");
$radio->setSelected('1');
$table->startRow();
$table->addCell("F.2.a Are other Schools or Faculties involved in and/or have interest in the course?:");
$table->addCell($radio->show());
$table->endRow();

$textarea = new textarea('f2b');
$table->startRow();
$table->addCell('F.2.b If yes, provide the details of the other Schools or Fucalties involvement/interest, including support and provision for the course/unit.:');
$table->addCell($textarea->show());
$table->endRow();

$radio = new radio ('f3a');
$radio->addOption('1',"Yes");
$radio->addOption('2',"No");
$radio->setSelected('1');
$table->startRow();
$table->addCell("F.3.a Does the course/unit provide service learning?:");
$table->addCell($radio->show());
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
$uri = $this->uri(array('action' => 'addreview'));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
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