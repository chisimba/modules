<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php
/* ------------icon request template---------------- */
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check
if (isset($refno)) {
    echo '<div class="warning"><strong>' . $this->objLanguage->languageText('mod_wicid_refnois', 'wicid', 'The ref number is') . ' ' . $refno . '</strong></div>';
}

$this->baseDir = $this->objSysConfig->getValue('FILES_DIR', 'wicid');
//load classes
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('fieldset', 'htmlelements');
$this->loadClass('link', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
//Create object for geticon
$objIcon = $this->newObject('geticon', 'htmlelements');
//Load Icon loader
$objIcon->setIcon('loader');

//Append javascript to check all fields
$this->appendArrayVar('headerParams', "
<script type=\"text/javascript\">
        // Action to be taken once page has loaded
        jQuery(document).ready(function(){
            jQuery(\"#input_selectall\").bind('click', function() {
                //if checked, check the other checkboxes, otherwise uncheck all
                var act = jQuery('#input_selectall').attr('checked');
                //Get no of checkboxes
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

//Append JS to check if folder exists and avoid creation of duplicates
$this->appendArrayVar('headerParams', '
    <script type="text/javascript">
        // Flag Variable - Update message or not
        var doUpdateMessage = false;

        // Var Current Entered Folder
        var currentFolder;

        // Action to be taken once page has loaded
        jQuery(document).ready(function(){
            jQuery("#input_foldername").bind(\'keyup\', function() {
                checkFolder(jQuery("#input_foldername").attr(\'value\'), jQuery("#input_parentfolder").attr(\'value\'));
            });
            jQuery("#input_parentfolder").change(function() {
                checkFolder(jQuery("#input_foldername").attr(\'value\'), jQuery("#input_parentfolder").attr(\'value\'));
            });
        });

        // Function to check whether folder exists in the chosen directory
        function checkFolder(folder, parent)
        {
            // Messages can be updated
            doUpdateMessage = true;

            // If folder is null
            if (folder == null) {
                // Remove existing stuff on the span
                jQuery("#spanfoldermessage").html("");
                jQuery("#spanfoldermessage").removeClass("error");
                jQuery("#input_foldername").removeClass("inputerror");
                jQuery("#spanfoldermessage").removeClass("success");
                doUpdateMessage = false;

            // If folder name is root - Reserved. Saves Ajax Call
            } else if (folder.toLowerCase() == "root") {

                currentFolder = folder;

                jQuery("#spanfoldermessage").html("' . $this->objLanguage->languageText('mod_wicid_thename', 'wicid', 'The name') . ' "+folder+" ' . $this->objLanguage->languageText('mod_wicid_isreservedselectother', 'wicid', 'is reserved. Kindly type in another one') . '");
                jQuery("#spanfoldermessage").addClass("error");
                jQuery("#input_foldername").addClass("inputerror");
                jQuery("#spanfoldermessage").removeClass("success");
                doUpdateMessage = false;

            // Else Need to do Ajax Call
            } else {
                // Check that existing folder name is not in use
                if (currentFolder != folder) {

                    // Set message to checking
                    jQuery("#spanfoldermessage").removeClass("success");
                    jQuery("#spanfoldermessage").html("<span id=\"folderexistscheck\">' . addslashes($objIcon->show()) . ' ' . $this->objLanguage->languageText('mod_wicid_checking', 'wicid', 'Checking') . ' ...</span>");

                    // Set current Folder
                    currentFolder = folder;

                    // DO Ajax
                    jQuery.ajax({
                        type: "GET",
                        url: "index.php",
                        data: "module=wicid&action=folderExistsCheck&foldername="+folder+"&parentname="+parent,
                        success: function(msg){

                            // Check if messages can be updated and folder remains the same
                            if (doUpdateMessage == true && currentFolder == folder) {

                                // IF folder exists
                                if (msg == "exists") {
                                    jQuery("#spanfoldermessage").html("<strong class="confirm">' . $this->objLanguage->languageText('mod_wicid_afolderwithname', 'wicid', 'A folder with the name') . ' "+folder+" ' . $this->objLanguage->languageText('mod_wicid_alreadyexists', 'wicid', 'already exists') . '</strong>");
                                    jQuery("#spanfoldermessage").addClass("error");
                                    jQuery("#input_foldername").addClass("inputerror");
                                    jQuery("#spanfoldermessage").removeClass("success");
                                    jQuery("#savebutton").attr("disabled", "disabled");

                                // Else
                                } else {
                                    jQuery("#spanfoldermessage").html("' . $this->objLanguage->languageText('mod_wicid_canusename', 'wicid', 'You can use the name') . ': "+folder);
                                    jQuery("#spanfoldermessage").addClass("success");
                                    jQuery("#spanfoldermessage").removeClass("error");
                                    jQuery("#input_foldername").removeClass("inputerror");
                                    jQuery("#savebutton").removeAttr("disabled");
                                }

                            }
                        }
                    });
                }
            }
        }
    </script>');

$header = new htmlheading();
$header->type = 2;
$header->str = $this->objLanguage->languageText('mod_wicid_unapproved', 'wicid', 'Unapproved Documents') . ' (' . $documents['count'] . ')';

echo $header->show();

// Create a Register New Document Button
//$button = new button("button1", $this->objLanguage->languageText('mod_wicid_registernewdoc', 'wicid', "Register New Document"));
//$button->issubmitbutton = false;
$newdoclink = new link($this->uri(array("action" => "newdocument", "selected" => $selected)));
//$newdoclink->link = $button->showDefault();
$newdoclink->link = $this->objLanguage->languageText('mod_wicid_registernewdoc', 'wicid', "Register New Document");

// Create a  Button
//$button = new button("button3", $this->objLanguage->languageText('mod_wicid_registeredocs', 'wicid', "Rejected documents"));
$rejecteddocuments = new link($this->uri(array("action" => "rejecteddocuments")));
//$button->issubmitbutton = false;
//$rejecteddocuments->link = $button->showDefault();
$rejecteddocuments->link = $this->objLanguage->languageText('mod_wicid_registeredocs', 'wicid', "Rejected documents");

$links = $newdoclink->show() . '&nbsp;|&nbsp;' . $rejecteddocuments->show() . '<br/>';
$fs = new fieldset();
$fs->setLegend($this->objLanguage->languageText('mod_wicid_navigation', 'wicid', 'Navigation'));
$fs->addContent($links);

echo $fs->show() . '<br/>';

$table = $this->getObject("htmltable", "htmlelements");

$doccount = $documents['count'];

$objIcon = $this->newObject('geticon', 'htmlelements');
$objIcon->setIcon('edit');

if ($doccount > 0) {
    $count = 0;
    $table->startHeaderRow();

    //Add checkbox if there are docs to show
    if ($doccount > 0) {
        //Create a check all checkbox
        $selectall = &new checkBox('selectall', Null, Null);
        $selectall->setValue('clicked');
        //Store count
        $textinput = new textinput('doc_count');
        $textinput->size = 1;
        $textinput->value = $doccount;
        $textinput->setType('hidden');
        //$this->objLanguage->languageText('mod_wicid_select', 'wicid', "Select")
        $table->addHeaderCell("<b>" . $selectall->show() . $textinput->show() . "</b>");
    } else {
        $table->addHeaderCell(" ");
    }

    $table->addHeaderCell("<b>" . $this->objLanguage->languageText('mod_wicid_title', 'wicid', "Title") . "</b>");
    $table->addHeaderCell("<b>" . $this->objLanguage->languageText('mod_wicid_refno', 'wicid', "Ref No") . "</b>");
    $table->addHeaderCell("<b>" . $this->objLanguage->languageText('mod_wicid_owner', 'wicid', "Owner") . "</b>");
    $table->addHeaderCell("<b>" . $this->objLanguage->languageText('mod_wicid_topic', 'wicid', "Topic") . "</b>");
    $table->addHeaderCell("<b>" . $this->objLanguage->languageText('mod_wicid_telephone', 'wicid', "Telephone") . "</b>");
    $table->addHeaderCell("<b>" . $this->objLanguage->languageText('mod_wicid_attachment', 'wicid', "Attachment") . "</b>");
    $table->addHeaderCell("<b>" . $this->objLanguage->languageText('mod_wicid_date', 'wicid', "Date") . "</b>");

    $table->endHeaderRow();

    foreach ($documents as $document) {
        if (count($document) > 1) {
            $astatus = $document['attachmentstatus'];            
            //$topic=  substr($document['topic'], strlen($this->baseDir));
            $link = new link($this->uri(array("action" => "editdocument", "id" => $document['id'], 'mode' => $mode, 'active' => 'N', 'rcount' => $rows, 'rowcount' => $documents['count'], 'start' => $start, 'astatus' => $astatus)));
            $link->link = $document['filename'];

            //Dont show checkbox if there is no attachment
            /*
              if ($document['attachmentstatus'] == 'No') {
              $approve = new hiddeninput($document['id'] . '_app', "");
              } else {
              //Create checkbox to help select record for batch approval
              $approve = &new checkBox($document['id'] . '_app', Null, Null);
              $approve->setValue('approve');
              } */

            //Show checkbox even without attachment
            //Create checkbox to help select record for batch execution
            $approve = &new checkBox($document['id'] . '_app', Null, Null);
            $approve->setValue('execute');
            $approve->setId('set4batch_' . $count);

            //Add row to render the record data
            if (($count % 2) == 0) {
                $table->startRow("even");
            } else {
                $table->startRow("odd");
            }
            $table->addCell($approve->show());
            $table->addCell($link->show());
            $table->addCell($document['refno']);
            $table->addCell($document['owner']);
            $table->addCell($document['topic']);
            $table->addCell($document['telephone']);
            // w.setUrl(GWT.getHostPageBaseURL() + Constants.MAIN_URL_PATTERN + "?module=wicid&action=uploadfile&docname=" + document.getTitle()
            //         + "&docid=" + document.getId() + "&topic=" + document.getTopic());


            $uplink = new link($this->uri(array("action" => "uploadfile", "docname" => $document['filename'], "docid" => $document['id'], "topic" => $document['topic'])));
            $uplink->link = $objIcon->show();

            $table->addCell($document['attachmentstatus'] . $uplink->show());
            $table->addCell($document['date']);
            $table->endRow();
            //Increment count
            $count++;
        }
    }
} else {
    if ($attonly == "onlyattached") {
        //Loads if no records with attachments were found
        $table->startRow();
        $table->addCell('<strong id="confirm">' . $this->objLanguage->languageText('mod_wicid_norecordswithattachments', 'wicid', 'No records with attachments were found')) . '</strong>';
        $table->endRow();
    } else {
        //Loads if no records were found
        $table->startRow();
        $table->addCell('<strong id="confirm">' . $this->objLanguage->languageText('mod_wicid_norecords', 'wicid', 'There are no records found')) . '</strong>';
        $table->endRow();
    }
}

// Form
$form = new form('registerdocumentform', $this->uri(array('action' => 'batchexecute', 'sourceaction' => 'unapproveddocs', 'mode' => $mode, 'active' => 'N', 'rcount' => $rows, 'rowcount' => $documents['count'], 'start' => $start)));
if ($doccount > 0) {
    if (!empty($message)) {
        $form->addToForm("<br />" . '<strong id="confirm">' . $message . '</strong>');
    } else {
        $form->addToForm("<br />" . '<strong id="confirm">' . $this->objLanguage->languageText('mod_wicid_approvenote', 'wicid', 'Note: Only records with attachments will be approved') . '</strong>');
    }
}
$form->addToForm($table->show());
if ($doccount > 0) {
    $button = new button('submit', $this->objLanguage->languageText('mod_wicid_approveselected', 'wicid', 'Approve Selected'));
    $button->setToSubmit();
    $form->addToForm('<br/>' . $button->show());

    $button = new button('submit', $this->objLanguage->languageText('mod_wicid_deleteselected', 'wicid', 'Delete Selected'));
    $button->setToSubmit();

    $form->addToForm("  " . $button->show());
    if (!empty($message)) {
        $form->addToForm("<br />" . '<strong id="confirm">' . $message . '</strong>');
    } else {
        $form->addToForm("<br />" . '<strong id="confirm">' . $this->objLanguage->languageText('mod_wicid_approvenote', 'wicid', 'Note: Only records with attachments will be approved') . '</strong>');
    }
}

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
        $prevform = new form('prevform', $this->uri(array('action' => 'unapproveddocs', 'mode' => $mode, 'active' => 'N', 'start' => $newprev, 'onlyattached' => $attonly, 'rowcount' => $totalrowcount)));

        $prevform->addToForm("</ br> " . $button->show() . $textinput2->show() . " </ br>");

        $table->addCell($prevform->show(), "50%", 'top', 'right');
    }
    //Add Next button
    if ($newstart < $totalrowcount && $start != $totalrowcount && $totalrowcount > $rows) {

        $button = new button('submit', $this->objLanguage->languageText('mod_wicid_wordnext', 'wicid', 'Next'));
        $button->setToSubmit();
        //Add Form
        $nextform = new form('nextform', $this->uri(array('action' => 'unapproveddocs', 'mode' => $mode, 'active' => 'N', 'start' => $newstart, 'onlyattached' => $attonly, 'rowcount' => $totalrowcount)));

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
        $table->addCell(" ", "50%", 'top', 'left');
    }
    $table->endRow();
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

//Check box for select only documents with attachments
if ($attonly == "onlyattached") {
    $onlyattached = &new checkBox('onlyattached', Null, true);
} else {
    $onlyattached = &new checkBox('onlyattached', Null, Null);
}
$onlyattached->setValue('onlyattached');

//Select no of records to display
$rcountform = new form('totalrowcount', $this->uri(array('action' => 'unapproveddocs', 'mode' => $mode, 'active' => 'Y', 'start' => 0)));
$button = new button('submit', $this->objLanguage->languageText('mod_wicid_wordgo', 'wicid', 'List'));
$button->setToSubmit();

$rcountform->addToForm("</ br> " . $button->show() . " " . $dd->show() . " records. Select only records with attachments?" . $onlyattached->show() . " </ br>");

//Create table to hold the rowcount
$table = &$this->newObject("htmltable", "htmlelements");
$table->width = '100%';
$table->startRow();
$table->endRow();
$table->startRow();
$table->addCell($rcountform->show(), "50%", 'top', 'left', Null, 'colspan="2"');
$table->endRow();
$rcounttable = $table->show();

//Create legend for the unnapproved docs
$fs = new fieldset();
$fs->setLegend($this->objLanguage->languageText('mod_wicid_unapproved', 'wicid', 'Unapproved documents'));

//Check if str is empty
if (!empty($str)) {
    $fs->addContent($rcounttable . "<br/>" . $form->show() . "<br/>" . $navtable);
} else {
    $fs->addContent($rcounttable . "<br/>" . $form->show());
}
echo $fs->show();
?>