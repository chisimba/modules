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
$this->loadClass('checkbox', 'htmlelements');
$this->loadClass('radio', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');

$this->setVar('pageSuppressXML', TRUE);
$this->baseDir = $this->objSysConfig->getValue('FILES_DIR', 'wicid');
$action = 'finishdocument';

$form = new form('contactdetailsform', $this->uri(array('action' => $action, 'id'=>$id, 'formname'=>'contactdetails')));

$xtitle = $this->objLanguage->languageText('mod_wicid_document', 'wicid', 'Section H: Contact Details');

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

$outcomesandassessmentthreelink = new link($this->uri(array("action" => "addoutcomesandassessmentthree")));
$outcomesandassessmentthreelink->link = "Outcomes and Assessment - Page Three";

$resourceslink = new link($this->uri(array("action" => "addresources")));
$resourceslink->link = "Resources";

$collaborationandcontractslink = new link($this->uri(array("action" => "addcollaborationandcontracts")));
$collaborationandcontractslink->link = "Collaboration and Contracts";

$reviewlink = new link($this->uri(array("action" => "addreview")));
$reviewlink->link = "Review";

$links = $doclink->show() . '&nbsp;|&nbsp;' . $overviewlink->show() . '&nbsp;|&nbsp;' .
        $rulesandsyllabusonelink->show() . '&nbsp;|&nbsp;' . $rulesandsyllabustwolink->show() . '&nbsp;|&nbsp;' .
        $subsidyrequirementslink->show() . '&nbsp;|&nbsp;' . $outcomesandassessmentonelink->show() . '&nbsp;|&nbsp;' .
        $outcomesandassessmenttwolink->show() . '&nbsp;|&nbsp;' . $outcomesandassessmentthreelink->show() . '&nbsp;|&nbsp;' .
        $resourceslink->show() . '&nbsp;|&nbsp;' . $collaborationandcontractslink->show() . '&nbsp;|&nbsp;' .
        $reviewlink->show() . '&nbsp;|&nbsp;' . "<b>Contact Details</b>" . '<br/>';

$fs = new fieldset();
$fs->setLegend('<b>Navigation</b>');
$fs->addContent($links);

echo $fs->show() . '<br/>';

$table = $this->newObject('htmltable', 'htmlelements');

$textarea = new textarea('h1');
$textarea->height = '70px';
$textarea->width = '500px';
$textarea->cols = 100;
if ($mode == "fixup") {
    $textarea->value = $h1;
}
$table->startRow();
$table->addCell("H.1. Name of academic proposing the course/unit:");
$table->endRow();

$table->startRow();
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('h2a');
$textarea->height = '70px';
$textarea->width = '500px';
$textarea->cols = 100;
if ($mode == "fixup") {
    $textarea->value = $h2a;
}
$table->startRow();
$table->addCell("H.2.a. Name of the School which will be the home for the course/unit:");
$table->endRow();

$table->startRow();
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('h2b');
$textarea->height = '70px';
$textarea->width = '500px';
$textarea->cols = 100;
if ($mode == "fixup") {
    $textarea->value = $h2b;
}
$table->startRow();
$table->addCell("H.2.b. School approval signature (Head of School or appropriate School committee chair) and date:");
$table->endRow();

$table->startRow();
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('h3a');
$textarea->height = '70px';
$textarea->width = '500px';
$textarea->cols = 100;
if ($mode == "fixup") {
    $textarea->value = $h3a;
}
$table->startRow();
$table->addCell("H.3.a. Telephone contact numbers:");
$table->endRow();

$table->startRow();
$table->addCell($textarea->show());
$table->endRow();

$textarea = new textarea('h3b');
$textarea->height = '70px';
$textarea->width = '500px';
$textarea->cols = 100;
if ($mode == "fixup") {
    $textarea->value = $h3b;
}
$table->startRow();
$table->addCell("H.3.b. Email addresses:");
$table->endRow();

$table->startRow();
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

$legend = "<b>Section H: Contact Details</b>";
$fs = new fieldset();
$fs->setLegend($legend);
$fs->addContent($table->show());
$form->addToForm($fs->show());

$button = new button('finish', "Finish");
$button->setToSubmit();
$form->addToForm('<br/>'.$button->show());

$button = new button('back', $this->objLanguage->languageText('word_back'));
$uri = $this->uri(array('action' => 'addreview'));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$form->addToForm($button->show());

$button = new button('cancel', $this->objLanguage->languageText('word_cancel'));
$uri = $this->uri(array('action' => 'home'));
$button->setToSubmit('javascript: window.location=\'' . $uri . '\'');
$form->addToForm($button->show());
echo $form->show();
?>

