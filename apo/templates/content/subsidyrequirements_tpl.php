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
$this->loadClass('radio','htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('iframe', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');

$this->setVar('pageSuppressXML', TRUE);

$xtitle = $this->objLanguage->languageText('mod_wicid_document', 'wicid', 'Section C: Subsidy Requirements');

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
        $rulesandsyllabusonelink->show() . '&nbsp;|&nbsp;' . $rulesandsyllabustwolink->show() . '&nbsp;|&nbsp;' .
        "<b>Subsidy Requirements</b>" . '&nbsp;|&nbsp;' . $outcomesandassessmentonelink->show() . '&nbsp;|&nbsp;' .
        $outcomesandassessmenttwolink->show() . '&nbsp;|&nbsp;' . $outcomesandassessmentthreelink->show() . '&nbsp;|&nbsp;' .
        $resourceslink->show() . '&nbsp;|&nbsp;' . $collaborationandcontractslink->show() . '&nbsp;|&nbsp;' .
        $reviewlink->show() . '&nbsp;|&nbsp;' . $contactdetailslink->show() . '<br/>';

$fs = new fieldset();
$fs->setLegend('<b>Navigation</b>');
$fs->addContent($links);

echo $fs->show() . '<br/>';

/* if ($mode == 'edit') {
  $legend = "Edit document";
  } */
$table = $this->newObject('htmltable', 'htmlelements');

$textarea = new textarea('c1');
$textarea->cols=100;
$table->startRow();
$table->addCell('C.1. The mode of instruction is understood to be contact/face-to-face lecturing. Provide details if any other mode of delivery is to be used:');
$table->endRow();

$table->startRow();
$table->addCell($textarea->show());
$table->endRow();

$radio = new radio ('c2a');
$radio->addOption('1',"off-campus");
$radio->addOption('2',"on-campus");
$radio->setSelected('1');
$table->startRow();
$table->addCell("C.2.a. The course/unit is taught:");
$table->endRow();

$table->startRow();
$table->addCell($radio->show());
$table->endRow();


$textarea = new textarea('c2b');
$textarea->cols=100;
$table->startRow();
$table->addCell('C.2.b. If the course/unit is taught off-campus provide details:');
$table->endRow();

$table->startRow();
$table->addCell($textarea->show());
$table->endRow();

//Section C.3.

$textinput = new textinput('c3');
$textinput->size = 60;
$textinput->value = $c3;
$table->startRow();
$table->addCell('C.3. What is the third order CESM (Classification of Education Subject Matter) category for the course/unit? (The CESM manual can be downloaded from http://intranet.wits.ac.za/Academic/APO/CESMs.htm):');
$table->endRow();

$table->startRow();
$table->addCell($textinput->show());
$table->endRow();

//Section C.4.
$radio = new radio ('c4a');
$radio->addOption('1',"Yes");
$radio->addOption('2',"No");
$radio->setSelected('1');
$table->startRow();
$table->addCell("C.4.a. Is any other School/Entity involved in teaching this unit?:");
$table->endRow();

$table->startRow();
$table->addCell($radio->show());
$table->endRow();


$textarea = new textarea('c4b');
$textarea->size = 60;
$textarea->value = $c4b;
$textarea->cols=100;
$table->startRow();
$table->addCell('C.4.b. If yes, state the name of the School/Entity:');
$table->endRow();

$table->startRow();
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('c4c');
$textarea->size = 60;
$textarea->value = $c4c;
$textarea->cols=100;
$table->startRow();
$table->addCell('Percentage each teaches.:');
$table->endRow();

$table->startRow();
$table->addCell($textarea->show());
$table->endRow();

$legend = "<b>Section C: Subsidy Requirements</b>";

$fs = new fieldset();
$fs->setLegend($legend);
$fs->addContent($table->show());
//echo $fs->show();
$action = 'addoutcomesandassessmentone';
$form = new form('subsidyrequirementsform', $this->uri(array('action' => $action, 'id' => $id, 'formname'=>'subsidyrequirements')));

$hiddenSelected = new hiddeninput('selected', $cfile);
$form->addToForm($hiddenSelected->show());

if ($mode == 'edit') {
    $hiddenId = new hiddeninput('docid', $document['id']);
    $form->addToForm($hiddenId->show());
}

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
$form->addToForm('<br/>'.$button->show());

$button = new button('back', $this->objLanguage->languageText('word_back'));
$uri = $this->uri(array('action' => 'addrulesandsyllabustwo'));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$form->addToForm($button->show());

$button = new button('cancel', $this->objLanguage->languageText('word_cancel'));
$uri = $this->uri(array('action' => 'home'));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$form->addToForm($button->show());


echo $form->show();
?>
