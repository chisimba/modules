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

$form = new form('commentsform', $this->uri(array('action' => $action, 'id' => $id, 'formname' => 'comments')));

$xtitle = $this->objLanguage->languageText('mod_wicid_document', 'wicid', 'Comment Page for the Academic Planning Office');

$header = new htmlheading();
$header->type = 2;
$header->str = $xtitle;

echo $header->show();

$doclink = new link($this->uri(array("action" => "editdocument", 'id' => $id, 'formname' => 'comments')));
$doclink->link = "Document";

$overviewlink = new link($this->uri(array("action" => "showoverview")));
$overviewlink->link = "Overview";

$rulesandsyllabusonelink = new link($this->uri(array("action" => "showrulesandsyllabusone", 'id' => $id, 'formname' => 'comments')));
$rulesandsyllabusonelink->link = "Rules and Syllabus - Page One";

$rulesandsyllabustwolink = new link($this->uri(array("action" => "showrulesandsyllabustwo", 'id' => $id, 'formname' => 'comments')));
$rulesandsyllabustwolink->link = "Rules and Syllabus - Page Two";

$subsidyrequirementslink = new link($this->uri(array("action" => "showsubsidyrequirements", 'id' => $id, 'formname' => 'comments')));
$subsidyrequirementslink->link = "Subsidy Requirements";

$outcomesandassessmentonelink = new link($this->uri(array("action" => "showoutcomesandassessmentone", 'id' => $id, 'formname' => 'comments')));
$outcomesandassessmentonelink->link = "Outcomes and Assessment - Page One";

$outcomesandassessmenttwolink = new link($this->uri(array("action" => "showoutcomesandassessmenttwo", 'id' => $id, 'formname' => 'comments')));
$outcomesandassessmenttwolink->link = "Outcomes and Assessment - Page Two";

$outcomesandassessmentthreelink = new link($this->uri(array("action" => "showoutcomesandassessmentthree", 'id' => $id, 'formname' => 'comments')));
$outcomesandassessmentthreelink->link = "Outcomes and Assessment - Page Three";

$resourceslink = new link($this->uri(array("action" => "showresources", 'id' => $id, 'formname' => 'comments')));
$resourceslink->link = "Resources";

$collaborationandcontractslink = new link($this->uri(array("action" => "showcollaborationandcontracts", 'id' => $id, 'formname' => 'comments')));
$collaborationandcontractslink->link = "Collaboration and Contracts";

$reviewlink = new link($this->uri(array("action" => "showreview", 'id' => $id, 'formname' => 'comments')));
$reviewlink->link = "Review";

$contactdetailslink = new link($this->uri(array("action" => "showcontactdetails", 'id' => $id, 'formname' => 'comments')));
$contactdetailslink->link = "Contact Details";

$links = $doclink->show() . '&nbsp;|&nbsp;' . $overviewlink->show() . '&nbsp;|&nbsp;' .
        $rulesandsyllabusonelink->show() . '&nbsp;|&nbsp;' . $rulesandsyllabustwolink->show() . '&nbsp;|&nbsp;' .
        $subsidyrequirementslink->show() . '&nbsp;|&nbsp;' . $outcomesandassessmentonelink->show() . '&nbsp;|&nbsp;' .
        $outcomesandassessmenttwolink->show() . '&nbsp;|&nbsp;' . $outcomesandassessmentthreelink->show() . '&nbsp;|&nbsp;' .
        $resourceslink->show() . '&nbsp;|&nbsp;' . $collaborationandcontractslink->show() . '&nbsp;|&nbsp;' .
        $reviewlink->show() . '&nbsp;|&nbsp;' . $contactdetailslink->show() . "<b>Comments</b>" . '<br/>';

$fs = new fieldset();
$fs->setLegend('<b>Navigation</b>');
$fs->addContent($links);

echo $fs->show() . '<br/>';

$table = $this->newObject('htmltable', 'htmlelements');
$table->border = 2;
$table->cellpadding = '2';
$table->cellspacing = '3';

//addHeaderCell($str, $width=null, $valign="top", $align='left', $class=null, $attrib=Null)
$table->startHeaderRow();
$table->addHeaderCell("INFORMATION", 165, "top", "center", null, 'colspan = "2"'); //, null, "center", "center", null, null, '2');
$table->addHeaderCell("DETAILS", 100, "top", "center", null, 'colspan = "2"'); //, null, "center", "center", null, null, '2');
$table->endHeaderRow();

$textarea = new textarea('title', null, 2, 80);
$table->startRow();
$table->addCell("Title", 190, "top", null, null, 'colspan = "2"', 1);
$table->addCell($textarea->show(), 190, "top", null, null, 'colspan = "2"', 1);
$table->endRow();

$textarea = new textarea('ad', null, 2, 80);
$table->startRow();
$table->addCell("Academic Development", 190, "top", null, null, 'colspan = "2"', 1);
$table->addCell($textarea->show(), 190, "top", null, null, 'colspan = "2"', 1);
$table->endRow();

$textarea = new textarea('faculty', null, 2, 80);
$table->startRow();
$table->addCell("Faculty", 190, "top", null, null, 'colspan = "2"', 1);
$table->addCell($textarea->show(), 190, "top", null, null, 'colspan = "2"', 1);
$table->endRow();

/* $table = $this->newObject('htmltable', 'htmlelements');
  $table->border = 2;
  $table->cellpadding = '2';
  $table->cellspacing = '3'; */

//addCell($str, $width=null, $valign="top", $align=null,
//$class=null, $attrib=Null,$border = '0')
$textarea = new textarea('doc', null, 2, 65);
$table->startRow();
$table->addCell("Document reference no. & Title e.g. S2003/?", 190, "top", null, null, 'colspan = "2"', 1);
$table->addCell($textarea->show());
$table->addCell("<b>DATE</b>", 80, "center", "center", null, 'bgcolor="#E8E8E8"', 1);
$table->endRow();

//textinput($name=null, $value=null, $type=null, $size=null)
$textarea = new textarea('recieved', null, 2, 65);
$textinput = new textinput('date1', 'dd-mm-yyyy', 'text', 9);
$table->startRow();
$table->addCell("Received by", 190, "top", null, null, 'colspan = "2"', 1);
$table->addCell($textarea->show());
$table->addCell($textinput->show());
$table->endRow();

$textarea = new textarea('apocomments', null, 2, 65);
$textinput = new textinput('date2', 'dd-mm-yyyy', 'text', 9);
$table->startRow();
$table->addCell("APO", 100, "center", null, null, 'rowspan = "2"', 1);
$table->addCell("Comments");
$table->addCell($textarea->show());
$table->addCell($textinput->show());
$table->endRow();

$textarea = new textarea('aporeply', null, 2, 65);
$textinput = new textinput('date3', 'dd-mm-yyyy', 'text', 9);
$table->startRow();
$table->addCell("Received Reply");
$table->addCell($textarea->show());
$table->addCell($textinput->show());
$table->endRow();

$textarea = new textarea('referrals', null, 2, 65);
$textinput = new textinput('date4', 'dd-mm-yyyy', 'text', 9);
$table->startRow();
$table->addCell("Referrals to: Other Faculties, Prof Bodies, Service Learning, ect.");
$table->addCell("Comments");
$table->addCell($textarea->show());
$table->addCell($textinput->show());
$table->endRow();


$textarea = new textarea('subsidy', null, 2, 65);
$textinput = new textinput('date5', 'dd-mm-yyyy', 'text', 9);
$table->startRow();
$table->addCell("Subsidy", 190, "top", null, null, 'colspan = "2"', 1);
$table->addCell($textarea->show());
$table->addCell($textinput->show());
$table->endRow();

$textarea = new textarea('librarycomments', null, 2, 65);
$textinput = new textinput('date6', 'dd-mm-yyyy', 'text', 9);
$table->startRow();
$table->addCell("Library (for library use only)", 100, "center", null, null, 'rowspan = "2"', 1);
$table->addCell("Comments");
$table->addCell($textarea->show());
$table->addCell($textinput->show());
$table->endRow();

$textarea = new textarea('libraryreply', null, 2, 65);
$textinput = new textinput('date7', 'dd-mm-yyyy', 'text', 9);
$table->startRow();
$table->addCell("Received Reply");
$table->addCell($textarea->show());
$table->addCell($textinput->show());
$table->endRow();

$textarea = new textarea('facultyboard', null, 2, 65);
$textinput = new textinput('date8', 'dd-mm-yyyy', 'text', 9);
$table->startRow();
$table->addCell("Faculty Board", 190, "top", null, null, 'colspan = "2"', 1);
$table->addCell($textarea->show());
$table->addCell($textinput->show());
$table->endRow();

$textarea = new textarea('approval', null, 2, 65);
$textinput = new textinput('date9', 'dd-mm-yyyy', 'text', 9);
$table->startRow();
$table->addCell("Professional Body Approval", 190, "top", null, null, 'colspan = "2"', 1);
$table->addCell($textarea->show());
$table->addCell($textinput->show());
$table->endRow();

$textarea = new textarea('apdc', null, 2, 65);
$textinput = new textinput('date10', 'dd-mm-yyyy', 'text', 9);
$table->startRow();
$table->addCell("APDC", 190, "top", null, null, 'colspan = "2"', 1);
$table->addCell($textarea->show());
$table->addCell($textinput->show());
$table->endRow();

$textarea = new textarea('legal', null, 2, 65);
$textinput = new textinput('date11', 'dd-mm-yyyy', 'text', 9);
$table->startRow();
$table->addCell("Legal Office (if necessary)", 190, "top", null, null, 'colspan = "2"', 1);
$table->addCell($textarea->show());
$table->addCell($textinput->show());
$table->endRow();

$textarea = new textarea('codes', null, 2, 65);
$textinput = new textinput('date12', 'dd-mm-yyyy', 'text', 9);
$table->startRow();
$table->addCell("Create Codes", 190, "top", null, null, 'colspan = "2"', 1);
$table->addCell($textarea->show());
$table->addCell($textinput->show());
$table->endRow();

$textarea = new textarea('review', null, 2, 65);
$textinput = new textinput('date13', 'dd-mm-yyyy', 'text', 9);
$table->startRow();
$table->addCell("Review", 190, "top", null, null, 'colspan = "2"', 1);
$table->addCell($textarea->show());
$table->addCell($textinput->show());
$table->endRow();



/* $efs = new fieldset();
  $efs->setLegend('Errors');
  if (count($errormessages) > 0) {

  $errorstr = '<ul>';

  foreach ($errormessages as $errormessage) {
  $errorstr.='<li class="error">' . $errormessage; //. '<li/>';
  }
  $errorstr.='</li>';
  $efs->addContent($errorstr);
  $form->addToForm($efs);
  } */

$legend = "<b>Comments</b>";
$fs = new fieldset();
$fs->width = 800;
$fs->setLegend($legend);
$fs->addContent($table->show());
$form->addToForm($fs->show());

$button = new button('next', $this->objLanguage->languageText('word_next'));
$button->setToSubmit();
//$uri = $this->uri(array('action' => $action, 'id' => $id, 'formname'=>'overview', 'toform'=>'rulesandsyllabusone'));
//$button->setOnClick('javascript: window.location=\'' . $uri . '\'');

$form->addToForm('<br/>' . $button->show());


$button = new button('back', $this->objLanguage->languageText('word_back'));
$uri = $this->uri(array('action' => 'showcontactdetails', 'id' => $id, 'formname' => 'overview', 'toform' => 'addeditdocument'));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
//$button->setToSubmit();
$form->addToForm($button->show());

$button = new button('cancel', $this->objLanguage->languageText('word_cancel'));
$uri = $this->uri(array('action' => 'home'));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$form->addToForm($button->show());

echo $form->show();
?>
