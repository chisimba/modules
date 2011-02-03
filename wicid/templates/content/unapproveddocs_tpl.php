<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php
/* ------------icon request template---------------- */
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check
if (isset($refno)) {
    echo '<div class="warning"><strong>The ref number is ' . $refno . '</strong></div>';
}

$this->baseDir = $this->objSysConfig->getValue('FILES_DIR', 'wicid');
//load classes
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('fieldset', 'htmlelements');
$this->loadClass('link', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
//Create object for geticon
$objIcon = $this->newObject('geticon', 'htmlelements');
//Load Icon loader
$objIcon->setIcon('loader');


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
            alert("Was selected");
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

                jQuery("#spanfoldermessage").html("The name "+folder+" is reserved. Kindly type in another one");
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
                    jQuery("#spanfoldermessage").html("<span id=\"folderexistscheck\">' . addslashes($objIcon->show()) . ' Checking ...</span>");

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
                                    jQuery("#spanfoldermessage").html("A folder with the name "+folder+" already exists");
                                    jQuery("#spanfoldermessage").addClass("error");
                                    jQuery("#input_foldername").addClass("inputerror");
                                    jQuery("#spanfoldermessage").removeClass("success");
                                    jQuery("#savebutton").attr("disabled", "disabled");

                                // Else
                                } else {
                                    jQuery("#spanfoldermessage").html("You can use the name: "+folder);
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
$header->str = $this->objLanguage->languageText('mod_wicid_unapproved', 'wicid', 'Unapproved Documents') . ' (' . count($documents) . ')';

echo $header->show();

// Create a Register New Document Button
$button = new button("submit", "Register New Document");

$newdoclink = new link($this->uri(array("action" => "newdocument", "selected" => $selected)));
$newdoclink->link = $button->show();

// Create a Unapproved/New documents Button
$button = new button("submit", "Unapproved/New documents");
$unapproveddocs = new link($this->uri(array("action" => "unapproveddocs")));
$unapproveddocs->link = $button->show();

// Create a  Button
$button = new button("submit", "Rejected documents");
$rejecteddocuments = new link($this->uri(array("action" => "rejecteddocuments")));
$rejecteddocuments->link = $button->show();

if ($this->objUser->isAdmin()) {
    echo $this->objUtils->showCreateFolderForm($tobeeditedfoldername);
}


$links = $newdoclink->show() . '&nbsp;|&nbsp;' . $unapproveddocs->show() . '&nbsp;|&nbsp;' . $rejecteddocuments->show() . '<br/>';
$fs = new fieldset();
$fs->setLegend('Navigation');
$fs->addContent($links);



echo $fs->show() . '<br/>';


$table = $this->getObject("htmltable", "htmlelements");
$table->startHeaderRow();
$table->addHeaderCell("Select");
$table->addHeaderCell("Title");
$table->addHeaderCell("Ref No");
$table->addHeaderCell("Owner");
$table->addHeaderCell("Topic");
$table->addHeaderCell("Telephone");
$table->addHeaderCell("Attachment");
$table->addHeaderCell("Date");

$table->endHeaderRow();
$objIcon = $this->newObject('geticon', 'htmlelements');
$objIcon->setIcon('edit');

if (count($documents) > 0) {
    foreach ($documents as $document) {
        //$topic=  substr($document['topic'], strlen($this->baseDir));
        $link = new link($this->uri(array("action" => "editdocument", "id" => $document['id'])));
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
        
        //Add row to render the record data
        $table->startRow();
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
    }
}

// Form
$form = new form('registerdocumentform', $this->uri(array('action' => 'batchexecute', 'mode' => $mode, 'active'=>'N')));
$form->addToForm($table->show());

$button = new button('submit', $this->objLanguage->languageText('mod_wicid_approveselected', 'wicid', 'Approve Selected'));
$button->setToSubmit();
$form->addToForm('<br/>' . $button->show());

$button = new button('submit', $this->objLanguage->languageText('mod_wicid_deleteselected', 'wicid', 'Delete Selected'));
$button->setToSubmit();

$form->addToForm(" | ".$button->show());

//Create legend for the unnapproved docs
$fs = new fieldset();
$fs->setLegend('Unapproved documents');
$fs->addContent($form->show());

echo $fs->show();
?>