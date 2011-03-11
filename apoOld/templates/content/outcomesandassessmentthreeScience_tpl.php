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

$a3 = 0.75;
$a5 = 0.8;
$a6 = 1.2;
$a7 = 1.6;
$b3 = 0.75;
$b5 = 1.5;
$b6 = 1.4;
$b7 = 1.6;
$c3 = 3;
$c5 = 0.6;
$c6 = 1.2;
$c7 = 1.6;
$d3 = 8;
$f5 = 0.6;
$f6 = 1.2;
$f7 = 1.6;

if ($mode == "new") {
    $a4 = 0;
    $a8 = 0;
    $a13 = 12;
    $b4 = 0;
    $b8 = 0;
    $b13 = 13;
    $c4 = 0;
    $c8 = 0;
    $c13 = 14;
    $d4 = 0;
    $d13 = 15;
    $e13 = 0;
    $f4 = 0;
    $f8 = 0;
    $f13 = 17;
    $g13 = 0;
    $h13 = 0;
    $i13 = $a13 + $b13 + $c13 + $d13 + $e13 + $f13 + $g13 + $h13;
}

$formdata = $this->objformdata->getFormData("outcomesandassessmentthreeScience", $id);
if ($formdata != null) {
    $mode = "edit";
    $a4 = $formdata['a1'] * $a3;
        switch ($formdata['a2']) {
            case 1:
                $a8 = $a4 * $a5;
                break;
            case 2:
                $a8 = $a4 * $a6;
                break;
            case 3:
                $a8 = $a4 * $a7;
                break;
            default:
                $errormessages[] = "Level of course must be 1, 2 or 3";
        }
        $a13 = $a4 + $a8;
        $a13 = round($a13);

        $b4 = $formdata['b1'] * $b3;
        switch ($formdata['b2']) {
            case 1:
                $b8 = $b4 * $b5;
                break;
            case 2:
                $b8 = $b4 * $b6;
                break;
            case 3:
                $b8 = $b4 * $b7;
                break;
            default:
                $errormessages[] = "Level of course must be 1, 2 or 3";
        }
        $b13 = $b4 + $b8;
        $b13 = round($b13);

        $c4 = $formdata['c1'] * $c3;
        switch ($formdata['c2']) {
            case 1:
                $c8 = $c4 * $c5;
                break;
            case 2:
                $c8 = $c4 * $c6;
                break;
            case 3:
                $c8 = $c4 * $c7;
                break;
            default:
                $errormessages[] = "Level of course must be 1, 2 or 3";
        }
        $c13 = $c4 + $c8;
        $c13 = round($c13);

        $d4 = $formdata['d1'] * $d3;
        $d13 = $d4 + $formdata['d8'];
        $d13 = round($d13);

        $e13 = $formdata['e8'];

        $f4 = $formdata['f1'] * $f3;
        switch ($formdata['f2']) {
            case 1:
                $f8 = $f4 * $f5;
                break;
            case 2:
                $f8 = $f4 * $f6;
                break;
            case 3:
                $f8 = $f4 * $f7;
                break;
            default:
                $errormessages[] = "Level of course must be 1, 2 or 3";
        }
        $f13 = $f4 + $f8;
        $f13 = round($f13);

        $g13 = $formdata['g9'] + $formdata['g10'];
        $g13 = round($g13);

        $h13 = $formdata['h11'] + $formdata['h12'];
        $h13 = round($h13);

        $i13 = $a13 + $b13 + $c13 + $d13 + $e13 + $f13 + $g13 + $h13;
    
}

$calculate = $this->uri(array("action" => "editdocument"));

$action = 'calculatespreedsheetScience';
$form = new form('outcomesandassessmentthreeScienceform', $this->uri(array('action' => $action, 'id' => $id, 'formname' => 'outcomesandassessmentthreeScience')));
$form->setOnSubmit($this->uri(array('action' => $action, 'id' => $id, 'formname' => 'outcomesandassessmentthreeScience')));

$xtitle = $this->objLanguage->languageText('mod_wicid_document', 'wicid', 'Section D: Outcomes and Assessment');

$header = new htmlheading();
$header->type = 2;
$header->str = $xtitle;

echo $header->show();

$doclink = new link($this->uri(array("action" => "editdocument","id"=>$id)));
$doclink->link = "Document";

$overviewlink = new link($this->uri(array("action" => "showoverview","id"=>$id)));
$overviewlink->link = "Overview";

$rulesandsyllabusonelink = new link($this->uri(array("action" => "showrulesandsyllabusone","id"=>$id)));
$rulesandsyllabusonelink->link = "Rules and Syllabus - Page One";

$rulesandsyllabustwolink = new link($this->uri(array("action" => "showrulesandsyllabustwo","id"=>$id)));
$rulesandsyllabustwolink->link = "Rules and Syllabus - Page Two";

$subsidyrequirementslink = new link($this->uri(array("action" => "showsubsidyrequirements","id"=>$id)));
$subsidyrequirementslink->link = "Subsidy Requirements";

$outcomesandassessmentonelink = new link($this->uri(array("action" => "showoutcomesandassessmentone","id"=>$id)));
$outcomesandassessmentonelink->link = "Outcomes and Assessment - Page One";

$outcomesandassessmenttwolink = new link($this->uri(array("action" => "showoutcomesandassessmenttwo","id"=>$id)));
$outcomesandassessmenttwolink->link = "Outcomes and Assessment - Page Two";

$resourceslink = new link($this->uri(array("action" => "showresources","id"=>$id)));
$resourceslink->link = "Resources";

$collaborationandcontractslink = new link($this->uri(array("action" => "showcollaborationandcontracts","id"=>$id)));
$collaborationandcontractslink->link = "Collaboration and Contracts";

$reviewlink = new link($this->uri(array("action" => "showreview","id"=>$id)));
$reviewlink->link = "Review";

$contactdetailslink = new link($this->uri(array("action" => "showcontactdetails","id"=>$id)));
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
$label->labelValue = "<b>D.5. Specify the notional study hours expected for the duration of the course/unit using the spreadsheet provided.</b><br></br>";

$label1 = new label();
$label1->labelValue = "<br><i>Check that the total notional study hours (last number in right hand column) cover the Faculty requirements (equal to or exceeding, but as close as possible to, 10 hours for every point the unit is worth.  Full year units at first year level are worth 36 points; 48 points at second year level and 72 points at third year level).</i></br>";

$table = $this->newObject('htmltable', 'htmlelements');
$table->width = '50%';
$table->border = 2;
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

$textinputa1 = new textinput('a1', "", 'text', 3);
$textinputa2 = new textinput('a2', "", 'text', 3);
if ($mode == "fixup") {
    $textinputa1->value = $a1;
    $textinputa2->value = $a2;
}
if ($mode == "edit") {
    $textinputa1->value = $formdata['a1'];
    $textinputa2->value = $formdata['a2'];
}

$table->startRow();
$table->addCell("Lectures (excl. tests)");
$table->addCell($textinputa1->show(), null, "center", "center", null, null /*'bgcolor="#00FF33"'*/, '2');
$table->addCell($textinputa2->show(), null, "center", "center", null, null /*'bgcolor="#00FF33"'*/, '1');
$table->addCell($a3, null, "center", "center", null, null /*''bgcolor="#FFFF00"'*/ , '1');
$table->addCell($a4, null, "center", "center", null, null /*''bgcolor="#FFFF00"'*/, '1');
$table->addCell($a5, null, "center", "center", null, null /*''bgcolor="#FFFF00"'*/, '1');
$table->addCell($a6, null, "center", "center", null, null /*''bgcolor="#FFFF00"'*/, '1');
$table->addCell($a7, null, "center", "center", null, null /*''bgcolor="#FFFF00"'*/, '1');
$table->addCell($a8, null, "center", "center", null, null /*''bgcolor="#FFFF00"'*/, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell($a13, null, "center", "center", null, null /*''bgcolor="#FFFF00"'*/, '1');
$table->endRow();

$textinputb1 = new textinput('b1', "", 'text', 3);
$textinputb2 = new textinput('b2', "", 'text', 3);
if ($mode == "fixup") {
    $textinputb1->value = $b1;
    $textinputb2->value = $b2;
}
if ($mode == "edit") {
    $textinputb1->value = $formdata['b1'];
    $textinputb2->value = $formdata['b2'];
}

$table->startRow();
$table->addCell("Tuts");
$table->addCell($textinputb1->show(), null, "center", "center", null, null /*'bgcolor="#00FF33"'*/, '1');
$table->addCell($textinputb2->show(), null, "center", "center", null, null /*'bgcolor="#00FF33"'*/, '1');
$table->addCell($b3, null, "center", "center", null, null /*''bgcolor="#FFFF00"'*/, '1');
$table->addCell($b4, null, "center", "center", null, null /*''bgcolor="#FFFF00"'*/, '1');
$table->addCell($b5, null, "center", "center", null, null /*''bgcolor="#FFFF00"'*/, '1');
$table->addCell($b6, null, "center", "center", null, null /*''bgcolor="#FFFF00"'*/, '1');
$table->addCell($b7, null, "center", "center", null, null /*''bgcolor="#FFFF00"'*/, '1');
$table->addCell($b8, null, "center", "center", null, null /*''bgcolor="#FFFF00"'*/, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell($b13, null, "center", "center", null, null /*''bgcolor="#FFFF00"'*/, '1');
$table->endRow();

$textinputc1 = new textinput('c1', "", 'text', 3);
$textinputc2 = new textinput('c2', "", 'text', 3);
if ($mode == "fixup") {
    $textinputc1->value = $c1;
    $textinputc2->value = $c2;
}
if ($mode == "edit") {
    $textinputc1->value = $formdata['c1'];
    $textinputc2->value = $formdata['c2'];
}

$table->startRow();
$table->addCell("Labs");
$table->addCell($textinputc1->show(), null, "center", "center", null, null /*'bgcolor="#00FF33"'*/, '1');
$table->addCell($textinputc2->show(), null, "center", "center", null, null /*'bgcolor="#00FF33"'*/, '1');
$table->addCell($c3, null, "center", "center", null, null /*''bgcolor="#FFFF00"'*/, '1');
$table->addCell($c4, null, "center", "center", null, null /*''bgcolor="#FFFF00"'*/, '1');
$table->addCell($c5, null, "center", "center", null, null /*''bgcolor="#FFFF00"'*/, '1');
$table->addCell($c6, null, "center", "center", null, null /*''bgcolor="#FFFF00"'*/, '1');
$table->addCell($c7, null, "center", "center", null, null /*''bgcolor="#FFFF00"'*/, '1');
$table->addCell($c8, null, "center", "center", null, null /*''bgcolor="#FFFF00"'*/, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell($c13, null, "center", "center", null, null /*''bgcolor="#FFFF00"'*/, '1');
$table->endRow();

$textinputd1 = new textinput('d1', "", 'text', 3);
$textinputd8 = new textinput('d8', "", 'text', 3);
if ($mode == "fixup") {
    $textinputd1->value = $d1;
    $textinputd8->value = $d8;
}
if ($mode == "edit") {
    $textinputd1->value = $formdata['d1'];
    $textinputd8->value = $formdata['d8'];
}

$table->startRow();
$table->addCell("Field trips (days)");
$table->addCell($textinputd1->show(), null, "center", "center", null, null /*'bgcolor="#00FF33"'*/, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell($d3, null, "center", "center", null, null /*''bgcolor="#FFFF00"'*/, '1');
$table->addCell($d4, null, "center", "center", null, null /*''bgcolor="#FFFF00"'*/, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell($textinputd8->show(), null, "center", "center", null, null /*'bgcolor="#00FF33"'*/, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell($d13, null, "center", "center", null, null /*''bgcolor="#FFFF00"'*/, '1');
$table->endRow();

$textinpute1 = new textinput('e1', "", 'text', 3);
$textinpute8 = new textinput('e8', "", 'text', 3);
if ($mode == "fixup") {
    $textinpute1->value = $e1;
    $textinpute8->value = $e8;
}
if ($mode == "edit") {
    $textinpute1->value = $formdata['e1'];
    $textinpute8->value = $formdata['e8'];
}

$table->startRow();
$table->addCell("Assignments/essays");
$table->addCell($textinpute1->show(), null, "center", "center", null, null /*'bgcolor="#00FF33"'*/, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell($textinpute8->show(), null, "center", "center", null, null /*'bgcolor="#00FF33"'*/, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell($e13, null, "center", "center", null, null /*''bgcolor="#FFFF00"'*/, '1');
$table->endRow();

$textinput = new textinput('other', "", 'text', 10);
$textinputf1 = new textinput('f1', "", 'text', 3);
$textinputf2 = new textinput('f2', "", 'text', 3);
$textinputf3 = new textinput('f3', "", 'text', 3);
if ($mode == "fixup") {
    $textinput->value = $other;
    $textinputf1->value = $f1;
    $textinputf2->value = $f2;
    $textinputf3->value = $f3;
}
if ($mode == "edit") {
    $textinput->value = $formdata['other'];
    $textinputf1->value = $formdata['f1'];
    $textinputf2->value = $formdata['f2'];
    $textinputf3->value = $formdata['f3'];
}

$table->startRow();
$table->addCell("Other (please specify)" . $textinput->show(), null, "center", "center", null, null, '1');
$table->addCell($textinputf1->show(), null, "center", "center", null, null /*'bgcolor="#00FF33"'*/, '1');
$table->addCell($textinputf2->show(), null, "center", "center", null, null /*'bgcolor="#00FF33"'*/, '1');
$table->addCell($textinputf3->show(), null, "center", "center", null, null /*'bgcolor="#00FF33"'*/, '1');
$table->addCell($f4, null, "center", "center", null, null /*''bgcolor="#FFFF00"'*/, '1');
$table->addCell($f5, null, "center", "center", null, null /*''bgcolor="#FFFF00"'*/, '1');
$table->addCell($f6, null, "center", "center", null, null /*''bgcolor="#FFFF00"'*/, '1');
$table->addCell($f7, null, "center", "center", null, null /*''bgcolor="#FFFF00"'*/, '1');
$table->addCell($f8, null, "center", "center", null, null /*''bgcolor="#FFFF00"'*/, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell($f13, null, "center", "center", null, null /*''bgcolor="#FFFF00"'*/, '1');
$table->endRow();

$textinputg9 = new textinput('g9', "", 'text', 3);
$textinputg10 = new textinput('g10', "", 'text', 3);
if ($mode == "fixup") {
    $textinputg9->value = $g9;
    $textinputg10->value = $g10;
}
if ($mode == "edit") {
    $textinputg9->value = $formdata['g9'];
    $textinputg10->value = $formdata['g10'];
}

$table->startRow();
$table->addCell("Tests");
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell($textinputg9->show(), null, "center", "center", null, null /*'bgcolor="#00FF33"'*/, '1');
$table->addCell($textinputg10->show(), null, "center", "center", null, null /*'bgcolor="#00FF33"'*/, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell($g13, null, "center", "center", null, null /*''bgcolor="#FFFF00"'*/, '1');
$table->endRow();

$textinputh11 = new textinput('h11', "", 'text', 3);
$textinputh12 = new textinput('h12', "", 'text', 3);
if ($mode == "fixup") {
    $textinputh11->value = $h11;
    $textinputh12->value = $h12;
}
if ($mode == "edit") {
    $textinputh11->value = $formdata['h11'];
    $textinputh12->value = $formdata['h12'];
}

$table->startRow();
$table->addCell("Exams");
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell($textinputh11->show(), null, "center", "center", null, null /*'bgcolor="#00FF33"'*/, '1');
$table->addCell($textinputh12->show(), null, "center", "center", null, null /*'bgcolor="#00FF33"'*/, '1');
$table->addCell($h13, null, "center", "center", null, null /*''bgcolor="#FFFF00"'*/, '1');
$table->endRow();

$table->startRow();
$table->addCell("Total Notional Study Hours");
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("-", null, "center", "center", null, null, '1');
$table->addCell("<u><b>".$i13."</b></u>", null, "center", "center", null, null /*''bgcolor="#FFFF00"'*/, '1');
$table->endRow();

$button = new button('calculate', "Calculate");
$uri = $this->uri(array('action' => 'calculatespreedsheetScience', 'id' => $id, 'formname' => 'outcomesandassessmentthreeScience'));
$action = 'javascript: window.location=\'' . $uri . '\'';
//$button->setOnClick($action);
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

/* D.6. State the university credits for the course/unit using as a guide the University Credit Points System tables included in Appendix E.
 * D.7. Timetable slots/diagonals (applicable to the Faculties of Science, Commerce Law and Management, Humanities)
 */

$table2 = $this->newObject('htmltable', 'htmlelements');

$textarea = new textarea('d6');
 $textarea->height = '70px';
  $textarea->width = '1000px';
  $textarea->cols = 100;
if ($mode == "fixup") {
    $textarea->value = $d6;
}
if ($mode == "edit") {
    $textarea->value = $formdata['d6'];
}
$table2->startRow();
$table2->addCell("D.6. State the university credits for the course/unit using as a guide the University Credit Points System tables included in Appendix E.");
$table2->endRow();
$table2->startRow();
$table2->addCell($textarea->show());
$table2->endRow();

$textarea = new textarea('d7');
 $textarea->height = '70px';
  $textarea->width = '1000px';
  $textarea->cols = 100;
if ($mode == "fixup") {
    $textarea->value = $d6;
}
if ($mode == "edit") {
    $textarea->value = $formdata['d6'];
}
$table2->startRow();
$table2->addCell("D.7. Timetable slots/diagonals (applicable to the Faculties of Science, Commerce Law and Management, Humanities).");
$table2->endRow();
$table2->startRow();
$table2->addCell($textarea->show());
$table2->endRow();

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
$fs->addContent($label1->show());
$fs->addContent($table->show());
$fs->addContent("<br></br>");
$fs->addContent($table2->show());
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

