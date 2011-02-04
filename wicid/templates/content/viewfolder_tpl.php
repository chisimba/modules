<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php
//Load Classes
$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');

$header = new htmlheading();
$header->type = 2;
$this->baseDir = $this->objSysConfig->getValue('FILES_DIR', 'wicid');

if ($selected == '') {
    $folders = $this->getDefaultFolder($this->baseDir);
    $selected = $folders[0];
}
if($selected !="unknown0"){
$cfile = substr($selected, strlen($this->baseDir));
$header->str = $cfile;

echo $header->show();
}

$createFolder = "";
if ($this->objUser->isAdmin()) {
    $createFolder = $this->objUtils->showCreateFolderForm("/", $selected);
}
echo $createFolder;

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

$links = $newdoclink->show() . '&nbsp;|&nbsp;' . $unapproveddocs->show() . '&nbsp;|&nbsp;' . $rejecteddocuments->show() . '<br/>';

//Add navigation to fieldset
$fs = new fieldset();
$fs->setLegend('Navigation');
$fs->addContent($links);

echo $fs->show();

$table = $this->getObject("htmltable", "htmlelements");

if (count($files) > 0) {
    $table->startHeaderRow();
    $table->addHeaderCell("Select");
    $table->addHeaderCell("Type");
    $table->addHeaderCell("Title");
    $table->addHeaderCell("Ref No");
    $table->addHeaderCell("Owner");
    $table->endHeaderRow();
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

        $table->startRow();
        $table->addCell($approve->show());
        $table->addCell($dlink1->show());
        $table->addCell($dlink2->show());
        $table->addCell($file['refno']);
        $table->addCell($file['owner'] . '(' . $file['telephone'] . ')');
        $table->endRow();
    }
} else {
    $table->startRow();
    $table->addCell("There are no topics to display");
    $table->endRow();
}
//Add Form
$form = new form('registerdocumentform', $this->uri(array('action' => 'batchexecute', 'mode' => $mode, 'active'=>'Y')));
$form->addToForm($table->show());

$button = new button('submit', $this->objLanguage->languageText('mod_wicid_deleteselected', 'wicid', 'Delete Selected'));
$button->setToSubmit();

$form->addToForm(" </br> " . $button->show());

//Add rejected documents table to fieldset
$fs = new fieldset();
$fs->setLegend('Topics');
$fs->addContent($form->show());
echo $fs->show();
?>
