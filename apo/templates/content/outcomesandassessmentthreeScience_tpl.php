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
$this->loadClass('textarea', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$this->objformdata = $this->getObject('dbformdata');
$this->setVar('pageSuppressXML', TRUE);
$this->baseDir = $this->objSysConfig->getValue('FILES_DIR', 'wicid');

$id = $this->getParam('id');

$formdata = $this->objformdata->getFormData("outcomesandassessmentthree", $id);
if ($formdata != null) {
    $mode = "edit";
}

$calculate = $this->uri(array("action" => "editdocument"));

$action = 'showresources';
$form = new form('outcomesandassessmentthreeScienceform', $this->uri(array('action' => $action, 'id' => $id, 'formname' => 'outcomesandassessmentthree')));

$xtitle = $this->objLanguage->languageText('mod_wicid_document', 'wicid', 'Section D: Outcomes and Assessment');

$header = new htmlheading();
$header->type = 2;
$header->str = $xtitle;

echo $header->show();

$doclink = new link($this->uri(array("action" => "editdocument")));
$doclink->link = "Document";

$overviewlink = new link($this->uri(array("action" => "showoverview")));
$overviewlink->link = "Overview";

$rulesandsyllabusonelink = new link($this->uri(array("action" => "showrulesandsyllabusone")));
$rulesandsyllabusonelink->link = "Rules and Syllabus - Page One";

$rulesandsyllabustwolink = new link($this->uri(array("action" => "showrulesandsyllabustwo")));
$rulesandsyllabustwolink->link = "Rules and Syllabus - Page Two";

$subsidyrequirementslink = new link($this->uri(array("action" => "showsubsidyrequirements")));
$subsidyrequirementslink->link = "Subsidy Requirements";

$outcomesandassessmentonelink = new link($this->uri(array("action" => "showoutcomesandassessmentone")));
$outcomesandassessmentonelink->link = "Outcomes and Assessment - Page One";

$outcomesandassessmenttwolink = new link($this->uri(array("action" => "showoutcomesandassessmenttwo")));
$outcomesandassessmenttwolink->link = "Outcomes and Assessment - Page Two";

$resourceslink = new link($this->uri(array("action" => "showresources")));
$resourceslink->link = "Resources";

$collaborationandcontractslink = new link($this->uri(array("action" => "showcollaborationandcontracts")));
$collaborationandcontractslink->link = "Collaboration and Contracts";

$reviewlink = new link($this->uri(array("action" => "showreview")));
$reviewlink->link = "Review";

$contactdetailslink = new link($this->uri(array("action" => "showcontactdetails")));
$contactdetailslink->link = "Contact Details";

$links = $doclink->show() . '&nbsp;|&nbsp;' . $overviewlink->show() . '&nbsp;|&nbsp;' .
        $rulesandsyllabusonelink->show() . '&nbsp;|&nbsp;' . $rulesandsyllabustwolink->show() . '&nbsp;|&nbsp;' .
        $subsidyrequirementslink->show() . '&nbsp;|&nbsp;' . $outcomesandassessmentonelink->show() . '&nbsp;|&nbsp;' .
        $outcomesandassessmenttwolink->show() . '&nbsp;|&nbsp;' . "<b>Outcomes and Assessment - Page Three</b>" . '&nbsp;|&nbsp;' .
        $resourceslink->show() . '&nbsp;|&nbsp;' . $collaborationandcontractslink->show() . '&nbsp;|&nbsp;' .
        $reviewlink->show() . '&nbsp;|&nbsp;' . $contactdetailslink->show() . '<br/>';

$fs = new fieldset();
$fs->setLegend('<b>Navigation</b>');
$fs->addContent($links);

echo $fs->show() . '<br/>';

$label = new label();
$label->labelValue = "<b><i>D.5. Specify the notional study hours expected for the duration of the course/unit using the spreadsheet provided.</b></i>";

$table = $this->newObject('htmltable', 'htmlelements');
/*$table->attributes = "bgcolor=#7BC618";
/*$this->css_class = null;
  $this->attributes = null;
  $this->tr_start = "<tr>\n";;
  $this->tdClasses = null;
  $this->trClasses = null;
  $this->attrs = null; */
//$table->bgcolor = "#7BC618";//"green";
$table->width = '50%';
$table->border = 2;
//$table->cellpadding = '2';
$table->cellspacing = '3';
$table->attributes = "black"; //alternate_row_colors = TRUE;

$table->startHeaderRow();
$table->addHeaderCell("Teaching events");
$table->addHeaderCell("No. of teaching events");
$table->addHeaderCell("Level of course (1st yr=1, 2nd yr=2, or 3rd yr=3)");
$table->addHeaderCell("Contact time (hours) per teaching event");
$table->addHeaderCell("No. of contact hours");
$table->addHeaderCell("Faculty average ratio of self study to contact (1st year)");
$table->addHeaderCell("Faculty average ratio of self study to contact (2nd year)");
$table->addHeaderCell("Faculty average ratio of self study to contact (3rd year)");
$table->addHeaderCell("Self study hours");
$table->addHeaderCell("Test hours");
$table->addHeaderCell("Test self study");
$table->addHeaderCell("Exam hours");
$table->addHeaderCell("Exam self study");
$table->addHeaderCell("Notional Hours (rounded)");
$table->endHeaderRow();

//textinput($name=null, $value=null, $type=null, $size=null)
$textinputa1 = new textinput('a1', "", 'text', 3);
/*$textinputa1->size = 3;
$textinputa1->fldType = 'text';
$textinputa1->cssClass = 'text';*/
$textinputa2 = new textinput('a2', "", 'text', 3);
$textinputa3 = new textinput('a3', "", 'text', 3);
$textinputa4 = new textinput('a4', "", 'text', 3);
$textinputa5 = new textinput('a5', "", 'text', 3);
$textinputa6 = new textinput('a6', "", 'text', 3);
$textinputa7 = new textinput('a7', "", 'text', 3);
$textinputa8 = new textinput('a8', "", 'text', 3);
$textinputa13 = new textinput('a13', "", 'text', 4.5);

//addCell($str, $width=null, $valign="top", $align=null, $class=null, $attrib=Null,$border = '0')
$table->startRow();
$table->addCell("Lectures (excl. tests)");
$table->addCell($textinputa1->show(), null, "center", "center", null, null, '2');
$table->addCell($textinputa2->show(), null, "center", "center", null, null, '1');
$table->addCell($textinputa3->show(), null, "center", "center", null, null, '1');
$table->addCell($textinputa4->show(), null, "center", "center", null, null, '1');
$table->addCell($textinputa5->show(), null, "center", "center", null, null, '1');
$table->addCell($textinputa6->show(), null, "center", "center", null, null, '1');
$table->addCell($textinputa7->show(), null, "center", "center", null, null, '1');
$table->addCell($textinputa8->show(), null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell($textinputa13->show(), null, "center", "center", null, null, '1');
$table->endRow();

$textinputb1 = new textinput('b1', "", 'text', 3);
$textinputb2 = new textinput('b2', "", 'text', 3);
$textinputb3 = new textinput('b3', "", 'text', 3);
$textinputb4 = new textinput('b4', "", 'text', 3);
$textinputb5 = new textinput('b5', "", 'text', 3);
$textinputb6 = new textinput('b6', "", 'text', 3);
$textinputb7 = new textinput('b7', "", 'text', 3);
$textinputb8 = new textinput('b8', "", 'text', 3);
$textinputb13 = new textinput('b13', "", 'text', 4.5);

$table->startRow();
$table->addCell("Tuts");
$table->addCell($textinputb1->show(), null, "center", "center", null, null, '1');
$table->addCell($textinputb2->show(), null, "center", "center", null, null, '1');
$table->addCell($textinputb3->show(), null, "center", "center", null, null, '1');
$table->addCell($textinputb4->show(), null, "center", "center", null, null, '1');
$table->addCell($textinputb5->show(), null, "center", "center", null, null, '1');
$table->addCell($textinputb6->show(), null, "center", "center", null, null, '1');
$table->addCell($textinputb7->show(), null, "center", "center", null, null, '1');
$table->addCell($textinputb8->show(), null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell($textinputb13->show(), null, "center", "center", null, null, '1');
$table->endRow();

$textinputc1 = new textinput('c1', "", 'text', 3);
$textinputc2 = new textinput('c2', "", 'text', 3);
$textinputc3 = new textinput('c3', "", 'text', 3);
$textinputc4 = new textinput('c4', "", 'text', 3);
$textinputc5 = new textinput('c5', "", 'text', 3);
$textinputc6 = new textinput('c6', "", 'text', 3);
$textinputc7 = new textinput('c7', "", 'text', 3);
$textinputc8 = new textinput('c8', "", 'text', 3);
$textinputc13 = new textinput('c13', "", 'text', 4.5);

$table->startRow();
$table->addCell("Labs");
$table->addCell($textinputc1->show(), null, "center", "center", null, null, '1');
$table->addCell($textinputc2->show(), null, "center", "center", null, null, '1');
$table->addCell($textinputc3->show(), null, "center", "center", null, null, '1');
$table->addCell($textinputc4->show(), null, "center", "center", null, null, '1');
$table->addCell($textinputc5->show(), null, "center", "center", null, null, '1');
$table->addCell($textinputc6->show(), null, "center", "center", null, null, '1');
$table->addCell($textinputc7->show(), null, "center", "center", null, null, '1');
$table->addCell($textinputc8->show(), null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell($textinputc13->show(), null, "center", "center", null, null, '1');
$table->endRow();

$textinputd1 = new textinput('d1', "", 'text', 3);
$textinputd3 = new textinput('d3', "", 'text', 3);
$textinputd4 = new textinput('d4', "", 'text', 3);
$textinputd8 = new textinput('d8', "", 'text', 3);
$textinputd13 = new textinput('d13', "", 'text', 4.5);

$table->startRow();
$table->addCell("Field trips (days)");
$table->addCell($textinputd1->show(), null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell($textinputd3->show(), null, "center", "center", null, null, '1');
$table->addCell($textinputd4->show(), null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell($textinputd8->show(), null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell($textinputd13->show(), null, "center", "center", null, null, '1');
$table->endRow();

$textinpute1 = new textinput('e1', "", 'text', 3);
$textinpute8 = new textinput('e8', "", 'text', 3);
$textinpute13 = new textinput('e13', "", 'text', 4.5);

$table->startRow();
$table->addCell("Assignments/essays");
$table->addCell($textinpute1->show(), null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell($textinpute8->show(), null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell($textinpute13->show(), null, "center", "center", null, null, '1');
$table->endRow();

$textinput = new textinput('other', "", 'text', 10);
$textinputf1 = new textinput('f1', "", 'text', 3);
$textinputf2 = new textinput('f2', "", 'text', 3);
$textinputf3 = new textinput('f3', "", 'text', 3);
$textinputf4 = new textinput('f4', "", 'text', 3);
$textinputf5 = new textinput('f5', "", 'text', 3);
$textinputf6 = new textinput('f6', "", 'text', 3);
$textinputf7 = new textinput('f7', "", 'text', 3);
$textinputf8 = new textinput('f8', "", 'text', 3);
$textinputf13 = new textinput('f13', "", 'text', 4.5);

$table->startRow();
$table->addCell("Other (please specify)" . $textinput->show(), null, "center", "center", null, null, '1');
$table->addCell($textinputf1->show(), null, "center", "center", null, null, '1');
$table->addCell($textinputf2->show(), null, "center", "center", null, null, '1');
$table->addCell($textinputf3->show(), null, "center", "center", null, null, '1');
$table->addCell($textinputf4->show(), null, "center", "center", null, null, '1');
$table->addCell($textinputf5->show(), null, "center", "center", null, null, '1');
$table->addCell($textinputf6->show(), null, "center", "center", null, null, '1');
$table->addCell($textinputf7->show(), null, "center", "center", null, null, '1');
$table->addCell($textinputf8->show(), null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell($textinputf13->show(), null, "center", "center", null, null, '1');
$table->endRow();

$textinputg9 = new textinput('g9', "", 'text', 3);
$textinputg10 = new textinput('g10', "", 'text', 3);
$textinputg13 = new textinput('g13', "", 'text', 4.5);

$table->startRow();
$table->addCell("Tests");
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell($textinputg9->show(), null, "center", "center", null, null, '1');
$table->addCell($textinputg10->show(), null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell($textinputg13->show(), null, "center", "center", null, null, '1');
$table->endRow();

$textinputh11 = new textinput('h11', "", 'text', 3);
$textinputh12 = new textinput('h12', "", 'text', 3);
$textinputh13 = new textinput('h13', "", 'text', 4.5);

$table->startRow();
$table->addCell("Exams");
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell($textinputh11->show(), null, "center", "center", null, null, '1');
$table->addCell($textinputh12->show(), null, "center", "center", null, null, '1');
;
$table->addCell($textinputh13->show(), null, "center", "center", null, null, '1');
$table->endRow();

$textinputi13 = new textinput('i13', "", 'text', 4.5);

$table->startRow();
$table->addCell("Total Notional Study Hours");
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell($textinputi13->show(), null, "center", "center", null, null, '1');
$table->endRow();
/*
  if ($mode == "fixup") {
  $textinput->value = $a;
  }
  if ($mode == "edit") {
  $textinput->value = $formdata['a'];
  } */

$button = new button('calculate', "Calculate");
$uri = $this->uri(array('action' => 'calculatespreedsheetScience', 'id' => $id, 'formname' => 'outcomesandassessmentthree'));
$action = 'javascript: window.location=\'' . $uri . '\'';
$button->setToSubmit();
$table->startRow();
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell(" ", null, "center", "center", null, null, '1');
$table->addCell($button->show(), null, "center", "right", null, null, '1');
$table->endRow();

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

$legend = "<b>Section D: Outcomes and Assessment - Page Three</b>";
$fs = new fieldset();
$fs->setLegend($legend);
$fs->addContent($label->show());
$fs->addContent($table->show());
$form->addToForm($fs->show());

$button = new button('next', $this->objLanguage->languageText('word_next'));
$button->setToSubmit();
$form->addToForm('<br/>' . $button->show());

$button = new button('back', $this->objLanguage->languageText('word_back'));
$uri = $this->uri(array('action' => 'showoutcomesandassessmenttwo'));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$form->addToForm($button->show());

$button = new button('cancel', $this->objLanguage->languageText('word_cancel'));
$uri = $this->uri(array('action' => 'home'));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$form->addToForm($button->show());

echo $form->show();
?>

