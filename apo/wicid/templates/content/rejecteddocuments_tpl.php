<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php
$this->loadClass('htmlheading', 'htmlelements');
$header = new htmlheading();
$header->type = 2;
$header->str = $this->objLanguage->languageText('mod_wicid_rejected', 'wicid', 'Rejected Documents');

echo $header->show();


$newdoclink = new link($this->uri(array("action" => "newdocument", "selected" => $selected)));
$newdoclink->link = "Register New Document";

$unapproveddocs = new link($this->uri(array("action" => "unapproveddocs")));
$unapproveddocs->link = "Unapproved/New documents";


$rejecteddocuments = new link($this->uri(array("action" => "rejecteddocuments")));
$rejecteddocuments->link = "Rejected documents";

echo $newdoclink->show() . '&nbsp;|&nbsp;' . $unapproveddocs->show() . '&nbsp;|&nbsp;' . $rejecteddocuments->show() . '<br/>';

$table = $this->getObject("htmltable", "htmlelements");
$table->startHeaderRow();
$table->addHeaderCell("Owner");
$table->addHeaderCell("Ref No");
$table->addHeaderCell("Title");
$table->addHeaderCell("Topic");
$table->addHeaderCell("Telephone");
$table->addHeaderCell("Attachment");
$table->endHeaderRow();

if (count($documents) > 0) {
    foreach ($documents as $document) {
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
echo $table->show();
?>
