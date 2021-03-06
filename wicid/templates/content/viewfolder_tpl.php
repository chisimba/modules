<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php
//Load Classes
$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');

//Append javascript to check all fields
$this->appendArrayVar('headerParams', "
<script type=\"text/javascript\">
        // Action to be taken once page has loaded
        jQuery(document).ready(function(){
            jQuery(\"#input_selectall\").bind('click', function() {
                //if checked, check the other checkboxes, otherwise uncheck all
                var act = jQuery('#input_selectall').attr('checked');
                //Get no of checkboxes from hidden input that stores the count
                var count = jQuery('#input_doc_count').attr('value');
            if(act) {
                var todo = 'checked';
            } else {
                var todo = '';
            }
            for (var i = 0; i < count; i++) {
                jQuery('#set4batch_'+i).attr('checked', todo);
            }
            });
        });
</script>
");


$header = new htmlheading();
$header->type = 2;
$this->baseDir = $this->objSysConfig->getValue('FILES_DIR', 'wicid');

if ($selected == '') {
    $folders = $this->getDefaultFolder($this->baseDir);
    $selected = $folders[0];
}
if ($selected != "unknown0") {
    $cfile = substr($selected, strlen($this->baseDir));
    $header->str = $cfile;

    echo $header->show();
}


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
$fs->setLegend('Navigation');
$fs->addContent($links);

echo $fs->show();

//Add Form
$form = new form('registerdocumentform', $this->uri(array('action' => 'batchexecute', 'sourceaction' => 'viewfolder', 'mode' => $mode, 'active' => 'Y', 'rcount' => $rows, 'rowcount' => $files['count'], 'start' => $start, 'folder' => $dir)));
if (!empty($message)) {
    $form->addToForm("<br />" . '<strong id="confirm">' . $message . '</strong>');
}
$table = &$this->newObject("htmltable", "htmlelements");
//Store file count
$filecount = $files['count'];
if ($filecount > 0) {
    $count = 0;
    //Create a check all checkbox
    $selectall = &new checkBox('selectall', Null, Null);
    $selectall->setValue('clicked');
    //Store count
    $textinput = new textinput('doc_count');
    $textinput->size = 1;
    $textinput->value = $filecount;
    $textinput->setType('hidden');



    $table->startHeaderRow();
    $table->addHeaderCell($selectall->show() . $textinput->show() . "<b>" . $this->objLanguage->languageText('mod_wicid_select', 'wicid', "Select") . "</b>");
    $table->addHeaderCell("<b>" . $this->objLanguage->languageText('mod_wicid_type', 'wicid', "Type") . "</b>");
    $table->addHeaderCell("<b>" . $this->objLanguage->languageText('mod_wicid_title', 'wicid', "Title") . "</b>");
    $table->addHeaderCell("<b>" . $this->objLanguage->languageText('mod_wicid_refno', 'wicid', "Ref No") . "</b>");
    $table->addHeaderCell("<b>" . $this->objLanguage->languageText('mod_wicid_owner', 'wicid', "Owner") . "</b>");
    $table->endHeaderRow();
    foreach ($files as $file) {
        if (count($file) > 1) {
            $dlink1 = new link($this->uri(array("action" => "downloadfile", "filepath" => $file['id'], "filename" => $file['actualfilename'])));
            $dlink1->link = $file['thumbnailpath'];

            $dlink2 = new link($this->uri(array("action" => "downloadfile", "filepath" => $file['id'], "filename" => $file['actualfilename'])));
            $dlink2->link = $file['actualfilename'];
            //Get the document Id
            $docId = $this->documents->getIdWithRefNo($file['refno']);

            //Create checkbox to help select record for batch execution
            $approve = &new checkBox($docId . '_app', Null, Null);
            $approve->setValue('execute');
            $approve->setId('set4batch_' . $count);
            //Check if even
            if (($count % 2) == 0) {
                $table->startRow("even");
            } else {
                $table->startRow("odd");
            }
            $table->addCell($approve->show());
            $table->addCell($dlink1->show());
            $table->addCell($dlink2->show());
            $table->addCell($file['refno']);
            $table->addCell($file['owner'] . '(' . $file['telephone'] . ')');
            $table->endRow();
            $count++;
        }
    }
} else {
    $table->startRow();
    $table->addCell('<strong class="confirm">' . $this->objLanguage->languageText('mod_wicid_norecords', 'wicid', 'There are no records found')) . '</strong>';
    $table->endRow();
}

//add table to form
$form->addToForm($table->show());
if ($filecount > 0) {
    $button = new button('submit', $this->objLanguage->languageText('mod_wicid_deleteselected', 'wicid', 'Delete Selected'));
    $button->setToSubmit();

    $form->addToForm(" </br> " . $button->show());
    
    $unapprovebutton = new button('submit', $this->objLanguage->languageText('mod_wicid_unapproveselected', 'wicid', 'Unapprove Selected'));
    $unapprovebutton->setToSubmit();

    $form->addToForm(" " . $unapprovebutton->show());
}

//Add Navigations
if ($filecount > 0) {


    //Compute new start val
    $newstart = $start + $rows;
    $newprev = $start - $rows;

    //Navigation Flag
    $str = "";
    //Create table to hold buttons(forms)
    $table = &$this->newObject("htmltable", "htmlelements");
    $table->width = '100%';
    $table->startRow();
    $nextflag = "nonext";

    //Store count
    $textinput2 = new textinput('rcount');
    $textinput2->size = 1;
    $textinput2->value = $rows;
    $textinput2->setType('hidden');

    //Add prev button
    if ($newprev >= 0) {
        $str .= "prev";
        $button = new button('submit', $this->objLanguage->languageText('mod_wicid_wordprevious', 'wicid', 'Previous'));
        $button->setToSubmit();
        //Add Form
        $prevform = new form('prevform', $this->uri(array('action' => 'viewfolder', 'mode' => $mode, 'active' => 'Y', 'start' => $newprev, 'rowcount' => $files['count'], 'folder' => $dir)));

        $prevform->addToForm("</ br> " . $button->show() . $textinput2->show() . " </ br>");

        $table->addCell($prevform->show(), "50%", 'top', 'right');
    }
    //Add Next button
    if ($newstart < $files['count'] && $start != $files['count'] && $files['count'] > $rows) {

        $button = new button('submit', $this->objLanguage->languageText('mod_wicid_wordnext', 'wicid', 'Next'));
        $button->setToSubmit();
        //Add Form
        $nextform = new form('nextform', $this->uri(array('action' => 'viewfolder', 'mode' => $mode, 'active' => 'Y', 'start' => $newstart, 'rowcount' => $files['count'], 'folder' => $dir)));

        $nextform->addToForm("</ br> " . $button->show() . $textinput2->show() . " </ br>");
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
        $table->addCell("", "50%", 'top', 'left');
    }
    $navtable = $table->show();
}

$dd = &new dropdown('rcount');
$dd->addOption('50', '50');
$dd->addOption('100', '100');
$dd->addOption('150', '150');
$dd->addOption('200', '200');
$dd->addOption('250', '250');
$dd->addOption('300', '300');
$dd->addOption('350', '350');
$dd->addOption('400', '400');
$dd->addOption('450', '450');
$dd->addOption('500', '500');
$dd->selected = $rows;
$dd->onchangeScript = 'onchange="document.forms[\'totalrowcount\'].submit();"';

//Select no of records to display
$rcountform = new form('totalrowcount', $this->uri(array('action' => 'viewfolder', 'mode' => $mode, 'active' => 'Y', 'start' => 0, 'rowcount' => $files['count'], 'folder' => $dir)));
$button = new button('submit', $this->objLanguage->languageText('mod_wicid_wordgo', 'wicid', 'List'));
$button->setToSubmit();
$rcountform->addToForm("</ br> " . $button->show() . " " . $dd->show() . " records. </ br>");

//Create table to hold the rowcount
$table = &$this->newObject("htmltable", "htmlelements");
$table->width = '100%';
$table->startRow();
$table->endRow();
$table->startRow();
$table->addCell($rcountform->show(), "50%", 'top', 'left', Null, 'colspan="2"');
$table->endRow();
$rcounttable = $table->show();

//Add documents table to fieldset
$fs = new fieldset();
$fs->setLegend($this->objLanguage->languageText('mod_wicid_topics', 'wicid', 'Topics'));
//Check if str is empty
if (!empty($str)) {
    $fs->addContent($rcounttable . "<br/>" . $form->show() . "<br/>" . $navtable);
} else {
    $fs->addContent($rcounttable . "<br/>" . $form->show());
}
echo $fs->show();
?>