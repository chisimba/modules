<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php
$this->loadClass('htmlheading', 'htmlelements');
$header = new htmlheading();
$header->type = 2;
$header->str = $this->objLanguage->languageText('mod_wicid_rejected', 'wicid', 'Rejected Documents');

echo $header->show();


// Create a Register New Document Button
$button = new button("submit", $this->objLanguage->languageText('mod_wicid_registernewdoc', 'wicid', "Register New Document"));

$newdoclink = new link($this->uri(array("action" => "newdocument", "selected" => $selected)));
$newdoclink->link = $button->show();

// Create a Unapproved/New documents Button
$button = new button("submit", $this->objLanguage->languageText('mod_wicid_newunapproved', 'wicid', "Unapproved/New documents"));
$unapproveddocs = new link($this->uri(array("action" => "unapproveddocs")));
$unapproveddocs->link = $button->show();

// Create a  Button
$button = new button("submit", $this->objLanguage->languageText('mod_wicid_registeredocs', 'wicid', "Rejected documents"));
$rejecteddocuments = new link($this->uri(array("action" => "rejecteddocuments")));
$rejecteddocuments->link = $button->show();

$links = $newdoclink->show() . '&nbsp;&nbsp;' . $unapproveddocs->show() . '&nbsp;&nbsp;' . $rejecteddocuments->show() . '<br/>';

//Add navigation to fieldset
$fs = new fieldset();
$fs->setLegend($this->objLanguage->languageText('mod_wicid_navigation', 'wicid', 'Navigation'));
$fs->addContent($links);

echo $fs->show();

echo "<br />";
$table = $this->getObject("htmltable", "htmlelements");
//Get no of rows
$doccount = $documents['count'];
if ($doccount > 0) {
    $table->startHeaderRow();
    $table->addHeaderCell($this->objLanguage->languageText('mod_wicid_owner', 'wicid', "Owner"));
    $table->addHeaderCell($this->objLanguage->languageText('mod_wicid_refno', 'wicid', "Ref No"));
    $table->addHeaderCell($this->objLanguage->languageText('mod_wicid_title', 'wicid', "Title"));
    $table->addHeaderCell($this->objLanguage->languageText('mod_wicid_topic', 'wicid', "Topic"));
    $table->addHeaderCell($this->objLanguage->languageText('mod_wicid_telephone', 'wicid', "Telephone"));
    $table->addHeaderCell($this->objLanguage->languageText('mod_wicid_attachment', 'wicid', "Attachment"));
    $table->endHeaderRow();
    foreach ($documents as $document) {
        if (count($document) > 1) {
            $table->startRow();
            $table->addCell($document['owner']);
            $table->addCell($document['refno']);
            $table->addCell($document['filename']);
            $table->addCell($document['topic']);
            $table->addCell($document['telephone']);
            $table->addCell($document['attachmentstatus']);
            $table->endRow();
        }
    }
} else {
    $table->startRow();
    $table->addCell($this->objLanguage->languageText('mod_wicid_norecords', 'wicid', "There are no records found"));
    $table->endRow();
}

$contenttable = $table->show();

//Add Navigations
if ($doccount > 0) {
    //Compute new start val
    $newstart = $start + $rows;
    $newprev = $start - $rows;
    //Navigation Flag
    $str = "";
    //total row count
    $totalrowcount = $documents['count'];
    //Create table to hold buttons(forms)
    $table = &$this->newObject("htmltable", "htmlelements");
    $table->width = '100%';
    $table->startRow();
    $nextflag = "nonext";
    //Add prev button
    if ($newprev >= 0) {
        $str .= "prev";
        $button = new button('submit', $this->objLanguage->languageText('mod_wicid_wordprevious', 'wicid', 'Previous'));
        $button->setToSubmit();
        //Add Form
        $prevform = new form('prevform', $this->uri(array('action' => 'rejecteddocuments', 'mode' => $mode, 'start' => $newprev, 'rowcount' => $totalrowcount)));

        $prevform->addToForm("</ br> " . $button->show() . " </ br>");

        $table->addCell($prevform->show(), "50%", 'top', 'right');
    }
    //Add Next button
    if ($newstart < $totalrowcount && $start != $totalrowcount && $totalrowcount > $rows) {

        $button = new button('submit', $this->objLanguage->languageText('mod_wicid_wordnext', 'wicid', 'Next'));
        $button->setToSubmit();
        //Add Form
        $nextform = new form('nextform', $this->uri(array('action' => 'rejecteddocuments', 'mode' => $mode, 'start' => $newstart, 'rowcount' => $totalrowcount)));

        $nextform->addToForm("</ br> " . $button->show() . " </ br>");
        if (!empty($str)) {
            $table->addCell($nextform->show(), "50%", 'top', 'left');
        } else {
            $table->addCell(" ", "50%", 'top', 'left');
            $table->addCell($nextform->show(), "50%", 'top', 'left');
        }
        $str .= "next";
        $nextflag = "next";
    }
    if ($nextflag == "nonext") {
        $table->addCell(" ", "50%", 'top', 'left');
    }
    $table->endRow();
    $navtable = $table->show();
}

//Add rejected documents table to fieldset
$fs = new fieldset();
$fs->setLegend($this->objLanguage->languageText('mod_wicid_registeredocs', 'wicid', "Rejected documents"));
//Check if str is empty
if (!empty($str)) {
    $fs->addContent($contenttable . "<br/>" . $navtable);
} else {
    $fs->addContent($contenttable);
}
echo $fs->show();
?>
