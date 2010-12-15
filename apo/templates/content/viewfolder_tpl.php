<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php
$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$header = new htmlheading();
$header->type = 2;
$this->baseDir = $this->objSysConfig->getValue('FILES_DIR', 'wicid');

if ($selected == '') {
    $folders = $this->getDefaultFolder($this->baseDir);
    $selected = $folders[0];
}
$cfile = substr($selected, strlen($this->baseDir));
$header->str = $cfile;

echo $header->show();

$createFolder = "";
if ($this->objUser->isAdmin()) {
    $createFolder = $this->objUtils->showCreateFolderForm("/", $selected);
}
echo $createFolder;

$newdoclink = new link($this->uri(array("action" => "newdocument", "selected" => $selected)));
$newdoclink->link = "Register New Course Proposal";

$unapproveddocs = new link($this->uri(array("action" => "unapproveddocs")));
$unapproveddocs->link = "Unapproved/New documents";


$rejecteddocuments = new link($this->uri(array("action" => "rejecteddocuments")));
$rejecteddocuments->link = "Rejected documents";

echo $newdoclink->show() . '&nbsp;|&nbsp;' . $unapproveddocs->show() . '&nbsp;|&nbsp;' . $rejecteddocuments->show() . '<br/>';
$table = $this->getObject("htmltable", "htmlelements");

$table->startHeaderRow();
$table->addHeaderCell("Type");
$table->addHeaderCell("Title");
$table->addHeaderCell("Ref No");
$table->addHeaderCell("Owner");
$table->endHeaderRow();
if (count($files) > 0) {
    foreach ($files as $file) {
        $dlink1=new link($this->uri(array("action"=>"downloadfile","filepath"=>$file['id'],"filename"=>$file['actualfilename'])));
        $dlink1->link=$file['thumbnailpath'];

        $dlink2=new link($this->uri(array("action"=>"downloadfile","filepath"=>$file['id'],"filename"=>$file['actualfilename'])));
        $dlink2->link=$file['actualfilename'];

        $table->startRow();
        $table->addCell($dlink1->show());
        $table->addCell($dlink2->show());
        $table->addCell($file['refno']);
        $table->addCell($file['owner'].'('.$file['telephone'].')');
        $table->endRow();
    }
}
echo $table->show();
?>
