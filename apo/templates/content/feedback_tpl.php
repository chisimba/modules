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
$this->loadClass('radio', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');
$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$this->objformdata = $this->getObject('dbformdata');
$this->setVar('pageSuppressXML', TRUE);
$this->baseDir = $this->objSysConfig->getValue('FILES_DIR', 'wicid');

$action = 'finishdocument';

$form = new form('feedbackform', $this->uri(array('action' => $action, 'id' => $id, 'formname'=>'feedback')));

$xtitle = $this->objLanguage->languageText('mod_wicid_document', 'wicid', 'Feedback');

$header = new htmlheading();
$header->type = 2;
$header->str = $xtitle;

echo $header->show();

$doclink = new link($this->uri(array("action" => "editdocument", 'id' => $id, 'formname'=>'feedback')));
$doclink->link = "Document";

$overviewlink = new link($this->uri(array("action" => "showoverview")));
$overviewlink->link = "Overview";

$rulesandsyllabusonelink = new link($this->uri(array("action" => "showrulesandsyllabusone", 'id' => $id, 'formname'=>'feedback')));
$rulesandsyllabusonelink->link = "Rules and Syllabus - Page One";

$rulesandsyllabustwolink = new link($this->uri(array("action" => "showrulesandsyllabustwo", 'id' => $id, 'formname'=>'feedback')));
$rulesandsyllabustwolink->link = "Rules and Syllabus - Page Two";

$subsidyrequirementslink = new link($this->uri(array("action" => "showsubsidyrequirements", 'id' => $id, 'formname'=>'feedback')));
$subsidyrequirementslink->link = "Subsidy Requirements";

$outcomesandassessmentonelink = new link($this->uri(array("action" => "showoutcomesandassessmentone", 'id' => $id, 'formname'=>'feedback')));
$outcomesandassessmentonelink->link = "Outcomes and Assessment - Page One";

$outcomesandassessmenttwolink = new link($this->uri(array("action" => "showoutcomesandassessmenttwo", 'id' => $id, 'formname'=>'feedback')));
$outcomesandassessmenttwolink->link = "Outcomes and Assessment - Page Two";

$outcomesandassessmentthreelink = new link($this->uri(array("action" => "showoutcomesandassessmentthree", 'id' => $id, 'formname'=>'feedback')));
$outcomesandassessmentthreelink->link = "Outcomes and Assessment - Page Three";

$resourceslink = new link($this->uri(array("action" => "showresources", 'id' => $id, 'formname'=>'feedback')));
$resourceslink->link = "Resources";

$collaborationandcontractslink = new link($this->uri(array("action" => "showcollaborationandcontracts", 'id' => $id, 'formname'=>'feedback')));
$collaborationandcontractslink->link = "Collaboration and Contracts";

$reviewlink = new link($this->uri(array("action" => "showreview", 'id' => $id, 'formname'=>'feedback')));
$reviewlink->link = "Review";

$contactdetailslink = new link($this->uri(array("action" => "showcontactdetails", 'id' => $id, 'formname'=>'feedback')));
$contactdetailslink->link = "Contact Details";

$commentslink = new link($this->uri(array("action" => "showcomments", 'id' => $id, 'formname'=>'feedback')));
$commentslink->link = "Comments";

$links = $doclink->show() . '&nbsp;|&nbsp;' . $overviewlink->show() . '&nbsp;|&nbsp;' .
        $rulesandsyllabusonelink->show() . '&nbsp;|&nbsp;' . $rulesandsyllabustwolink->show() . '&nbsp;|&nbsp;' .
        $subsidyrequirementslink->show() . '&nbsp;|&nbsp;' . $outcomesandassessmentonelink->show() . '&nbsp;|&nbsp;' .
        $outcomesandassessmenttwolink->show() . '&nbsp;|&nbsp;' . $outcomesandassessmentthreelink->show() . '&nbsp;|&nbsp;' .
        $resourceslink->show() . '&nbsp;|&nbsp;' . $collaborationandcontractslink->show() . '&nbsp;|&nbsp;' .
        $reviewlink->show() . '&nbsp;|&nbsp;' . $contactdetailslink->show() . '&nbsp;|&nbsp;' . $commentslink->show() . "<b>Feedback</b>".'<br/>';

$fs = new fieldset();
$fs->setLegend('<b>Navigation</b>');
$fs->addContent($links);

echo $fs->show() . '<br/>';

$table = $this->newObject('htmltable', 'htmlelements');

$efs = new fieldset();
$efs->setLegend('Errors');
if (count($errormessages) > 0) {

    $errorstr = '<ul>';

    foreach ($errormessages as $errormessage) {
        $errorstr.='<li class="error">' . $errormessage; //. '<li/>';
    }
    $errorstr.='</li>';
    $efs->addContent($errorstr);
    $form->addToForm($efs);
}

$legend = "<b>Feedback</b>";
$fs = new fieldset();
$fs->setLegend($legend);
$fs->addContent($table->show());
$form->addToForm($fs->show());

$button = new button('next', $this->objLanguage->languageText('word_next'));
$button->setToSubmit();
//$uri = $this->uri(array('action' => $action, 'id' => $id, 'formname'=>'overview', 'toform'=>'rulesandsyllabusone'));
//$button->setOnClick('javascript: window.location=\'' . $uri . '\'');

$form->addToForm('<br/>' . $button->show());


$button = new button('back', $this->objLanguage->languageText('word_back'));
$uri = $this->uri(array('action' => 'showcomments', 'id' => $id, 'formname'=>'overview', 'toform'=>'addeditdocument'));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
//$button->setToSubmit();
$form->addToForm($button->show());

$button = new button('cancel', $this->objLanguage->languageText('word_cancel'));
$uri = $this->uri(array('action' => 'home'));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$form->addToForm($button->show());

echo $form->show();

?>
