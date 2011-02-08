<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php
//Load Classes
$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');

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

$createFolder = "";
if ($this->objUser->isAdmin()) {
    $createFolder = $this->objUtils->showCreateFolderForm("/", $selected);
    $deleteFolder = $this->objUtils->showDeleteFolderForm("/", $message);
}
echo $createFolder;
echo $deleteFolder;

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

$links = $newdoclink->show() . '&nbsp;|&nbsp;' . $unapproveddocs->show() . '&nbsp;|&nbsp;' . $rejecteddocuments->show() . '<br/>';

//Add navigation to fieldset
$fs = new fieldset();
$fs->setLegend('Navigation');
$fs->addContent($links);

echo $fs->show();

//Add Form
$form = new form('registerdocumentform', $this->uri(array('action' => 'batchexecute', 'mode' => $mode, 'active' => 'Y')));

$table = &$this->newObject("htmltable", "htmlelements");
//Store file count
$filecount = count($files);
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

    $table->startRow();
    $table->addCell($selectall->show() .$textinput->show(). "<b>" . $this->objLanguage->languageText('mod_wicid_select', 'wicid', "Select") . "</b>");
    $table->addCell("<b>" . $this->objLanguage->languageText('mod_wicid_type', 'wicid', "Type") . "</b>");
    $table->addCell("<b>" . $this->objLanguage->languageText('mod_wicid_title', 'wicid', "Title") . "</b>");
    $table->addCell("<b>" . $this->objLanguage->languageText('mod_wicid_refno', 'wicid', "Ref No") . "</b>");
    $table->addCell("<b>" . $this->objLanguage->languageText('mod_wicid_owner', 'wicid', "Owner") . "</b>");
    $table->endRow();
    foreach ($files as $file) {
        $dlink1 = new link($this->uri(array("action" => "downloadfile", "filepath" => $file['id'], "filename" => $file['actualfilename'])));
        $dlink1->link = $file['thumbnailpath'];

        $dlink2 = new link($this->uri(array("action" => "downloadfile", "filepath" => $file['id'], "filename" => $file['actualfilename'])));
        $dlink2->link = $file['actualfilename'];
        //Get the document Id
        $docId = $this->documents->getIdWithRefNo($file['refno']);

        //Create checkbox to help select record for batch execution
        $approve = &new checkBox($docId . '_app', Null, Null);
        $approve->setValue('execute');
        $approve->setId('set4batch_'.$count);

        $table->startRow();
        $table->addCell($approve->show());
        $table->addCell($dlink1->show());
        $table->addCell($dlink2->show());
        $table->addCell($file['refno']);
        $table->addCell($file['owner'] . '(' . $file['telephone'] . ')');
        $table->endRow();
        $count++;
    }
} else {
    $table->startRow();
    $table->addCell($this->objLanguage->languageText('mod_wicid_norecords', 'wicid', 'There are no records found'));
    $table->endRow();
}

//add table to form
$form->addToForm($table->show());

$button = new button('submit', $this->objLanguage->languageText('mod_wicid_deleteselected', 'wicid', 'Delete Selected'));
$button->setToSubmit();

$form->addToForm(" </br> " . $button->show());

//Add rejected documents table to fieldset
$fs = new fieldset();
$fs->setLegend($this->objLanguage->languageText('mod_wicid_topics', 'wicid', 'Topics'));
$fs->addContent($form->show());
echo $fs->show();
?>