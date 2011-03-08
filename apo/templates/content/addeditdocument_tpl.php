<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php
$documentjs = '<script type="text/javascript" src="' . $this->getResourceURI('js/document.js') . '"></script>';
$jqueryUICore = '<script type="text/javascript" src="' . $this->getResourceURI('js/jquery-ui/jquery.ui.core.js') . '"></script>';
$jqueryUI = '<script type="text/javascript" src="' . $this->getResourceURI('js/jquery-ui/jquery-ui.min.js') . '"></script>';
$jqueryUICSS = '<link rel="stylesheet" type="text/css" href="' . $this->getResourceURI('css/jquery-ui.css') . '">';

$this->appendArrayVar("headerParams", $jqueryUICSS);
$this->appendArrayVar("headerParams", $jqueryUICore);
$this->appendArrayVar("headerParams", $jqueryUI);
$this->appendArrayVar("headerParams", $documentjs);

$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('fieldset', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->documents = $this->getObject('dbdocuments');

$this->setVar('pageSuppressXML', TRUE);
$this->setVar('JQUERY_VERSION', '1.4.2');

$this->loadClass('iframe', 'htmlelements');
$this->loadClass('button', 'htmlelements');



if ($mode == 'new') {
    $action = 'registerdocument';
}
$this->baseDir = $this->objSysConfig->getValue('FILES_DIR', 'wicid');

if ($mode == 'edit') {
    $action = "updatedocument";
    $selected = $this->baseDir . $document['topic'];
}

if ($selected == '') {

    $folders = $this->__getdefaultfolder($this->baseDir);
    $selected = $folders[0];
}

$this->loadClass('dropdown', 'htmlelements');

$cfile = substr($selected, strlen($this->baseDir));

$xtitle = $this->objLanguage->languageText('mod_wicid_newdocument', 'wicid', 'New Course Proposal');
if ($mode == 'edit') {
    $xtitle = $this->objLanguage->languageText('mod_wicid_newdocument', 'wicid', 'Edit Course Proposal');
}
$header = new htmlheading();
$header->type = 2;
$header->str = $xtitle;

echo $header->show();

if ($mode == 'edit') {
    $overviewlink = new link($this->uri(array("action" => "showoverview", "id" => $document['id'], 'formname'=>'editdocument')));
    $overviewlink->link = "Overview";

    $rulesandsyllabusonelink = new link($this->uri(array("action" => "showrulesandsyllabusone", "id" => $document['id'], 'formname'=>'editdocument')));
    $rulesandsyllabusonelink->link = "Rules and Syllabus (page one)";

    $rulesandsyllabustwolink = new link($this->uri(array("action" => "showrulesandsyllabustwo", "id" => $document['id'], 'formname'=>'editdocument')));
    $rulesandsyllabustwolink->link = "Rules and Syllabus (page two)";

    $subsidyrequirementslink = new link($this->uri(array("action" => "showsubsidyrequirements", "id" => $document['id'], 'formname'=>'editdocument')));
    $subsidyrequirementslink->link = "Subsidy Requirements";

    $outcomesandassessmentonelink = new link($this->uri(array("action" => "showoutcomesandassessmentone", "id" => $document['id'], 'formname'=>'editdocument')));
    $outcomesandassessmentonelink->link = "Outcomes and Assessment (page one)";

    $outcomesandassessmenttwolink = new link($this->uri(array("action" => "showoutcomesandassessmenttwo", "id" => $document['id'], 'formname'=>'editdocument')));
    $outcomesandassessmenttwolink->link = "Outcomes and Assessment (page two)";

    $outcomesandassessmentthreelink = new link($this->uri(array("action" => "showoutcomesandassessmentthree", "id" => $document['id'], 'formname'=>'editdocument')));
    $outcomesandassessmentthreelink->link = "Outcomes and Assessment (page three)";

    $resourceslink = new link($this->uri(array("action" => "showresources", "id" => $document['id'], 'formname'=>'editdocument')));
    $resourceslink->link = "Resources";

    $collaborationandcontractslink = new link($this->uri(array("action" => "showcollaborationandcontracts", "id" => $document['id'], 'formname'=>'editdocument')));
    $collaborationandcontractslink->link = "Collaboration and Contracts";

    $reviewlink = new link($this->uri(array("action" => "showreview", "id" => $document['id'], 'formname'=>'editdocument')));
    $reviewlink->link = "Review";

    $contactdetailslink = new link($this->uri(array("action" => "showcontactdetails", "id" => $document['id'], 'formname'=>'editdocument')));
    $contactdetailslink->link = "Contact Details";

    $commentslink = new link($this->uri(array("action" => "showcomments", "id" => $document['id'], 'formname'=>'editdocument')));
    $commentslink->link = "Comments";

    $feedbacklink = new link($this->uri(array("action" => "showfeedback", "id" => $document['id'], 'formname'=>'editdocument')));
    $feedbacklink->link = "Feedback";

    $links = "<b>Document</b>" . '&nbsp;|&nbsp;' . $overviewlink->show() . '&nbsp;|&nbsp;' .
            $rulesandsyllabusonelink->show() . '&nbsp;|&nbsp;' . $rulesandsyllabustwolink->show() . '&nbsp;|&nbsp;' .
            $subsidyrequirementslink->show() . '&nbsp;|&nbsp;' . $outcomesandassessmentonelink->show() . '&nbsp;|&nbsp;' .
            $outcomesandassessmenttwolink->show() . '&nbsp;|&nbsp;' . $outcomesandassessmentthreelink->show() . '&nbsp;|&nbsp;' .
            $resourceslink->show() . '&nbsp;|&nbsp;' . $collaborationandcontractslink->show() . '&nbsp;|&nbsp;' .
            $reviewlink->show() . '&nbsp;|&nbsp;' . $contactdetailslink->show() . '&nbsp;|&nbsp;' . $commentslink->show() . '&nbsp;|&nbsp;' .
            $feedbacklink->show() . '<br/>';

    $fs = new fieldset();
    $fs->setLegend('<b>Navigation</b>');
    $fs->addContent($links);

    echo $fs->show() . '<br/>';
}

// Opening date
$table = $this->newObject('htmltable', 'htmlelements');
$table->startRow();
$textinput = new textinput('date_created');
$textinput->size = 60;
$textinput->cssId = "datepicker1";

if ($mode == 'edit') {
    $textinput->value = $document['date_created'];
}



$table->addCell('<b>Entry Date</b>');
$table->addCell($textinput->show());
$table->endRow();

$number = 'S';
$textinput = new dropdown('department');
$textinput->addOption("", "Please select department...");
$textinput->addFromDB($departments, 'name', 'name', $document['department']);
//$textinput->size = 60;
if ($mode == 'edit') {
    $textinput->setSelected($document['department']);
}
if ($mode == "fixup") {
    $textinput->setSelected($department);
}
$table->startRow();
$table->addCell("<b>Originating Department</b>");
$table->addCell($textinput->show());
$table->endRow();

$textinput = new textinput('contact');
$textinput->size = 60;
if ($mode == 'edit') {
    $textinput->value = $document['contact_person'];
} else if ($mode == "fixup") {
    $textinput->value = $contact;
} else {
    $textinput->value = $this->objUser->fullname();
}
$table->startRow();
$table->addCell("<b>Contact person</b>");
$table->addCell($textinput->show());
$table->endRow();



$textinput = new textinput('telephone');
$textinput->size = 40;
if ($mode == 'edit') {
    $textinput->value = $document['telephone'];
}
$table->startRow();
$table->addCell("<b>Telephone number</b>");
$table->addCell($textinput->show());
$table->endRow();



$textinput = new textinput('title');
$textinput->size = 60;
if ($mode == 'edit') {
    $textinput->value = $document['docname'];
}if ($mode == 'fixup') {
    $textinput->value = $title;
}
$table->startRow();
$table->addCell("<b>Course Title</b>");
$table->addCell($textinput->show());
$table->endRow();


$legend = "New Course Proposal";
if ($mode == 'edit') {
    $legend = "Edit document";
}
$fs = new fieldset();
$fs->setLegend($legend);
$fs->addContent($table->show());

// Form

$form = new form('registerdocumentform', $this->uri(array('action' => $action, 'id' => $id)));
$numberfield = new hiddeninput('number', $number);
$form->addToForm($numberfield->show());


$hiddenSelected = new hiddeninput('selected', $cfile);
$form->addToForm($hiddenSelected->show());

//$form
if ($mode == 'edit') {
    $hiddenId = new hiddeninput('id', $document['id']);
    $form->addToForm($hiddenId->show());
}

$errormessages = array();
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

$button = new button('save', $this->objLanguage->languageText('mod_wicid_save', 'wicid', 'Create Document'));
$button->setToSubmit();

if ($mode == 'edit') {
    $button = new button('next', $this->objLanguage->languageText('word_next'));
    $uri = $this->uri(array('action' => 'showoverview', 'id' => $document['id']));
    $button->setOnClick('javascript: window.location=\'' . $uri . '\'');
}

$form->addToForm('<br/>' . $button->show());

/* if ($this->objUser->isAdmin()) {
  if ($mode == 'edit') {
  $button = new button('approve', "Approve");
  $uri = $this->uri(array('action' => 'approvedocument', 'id' => $document['id']));
  $button->setOnClick('javascript: window.location=\'' . $uri . '\'');
  $form->addToForm($button->show());

  $button = new button('reject', "Reject");
  $uri = $this->uri(array('action' => 'rejectdocument', 'id' => $document['id']));
  $button->setOnClick('javascript: window.location=\'' . $uri . '\'');
  $form->addToForm($button->show());
  }
  } */
$button = new button('cancel', $this->objLanguage->languageText('word_cancel'));
$uri = $this->uri(array('action' => 'home'));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$form->addToForm($button->show());

echo $form->show();
?>
