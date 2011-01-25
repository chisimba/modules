<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php
if (isset($refno)) {
    echo '<div class="warning"><strong>The ref number is ' . $refno . '</strong></div>';
}

$this->baseDir = $this->objSysConfig->getValue('FILES_DIR', 'wicid');
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('fieldset', 'htmlelements');
$this->loadClass('link', 'htmlelements');
$header = new htmlheading();
$header->type = 2;
$header->str = $this->objLanguage->languageText('mod_wicid_unapproved', 'wicid', 'Unapproved Documents') . ' (' . count($documents) . ')';

echo $header->show();


$newdoclink = new link($this->uri(array("action" => "newdocument", "selected" => $selected)));
$newdoclink->link = "New Course Proposal";

$unapproveddocs = new link($this->uri(array("action" => "unapproveddocs")));
$unapproveddocs->link = "Unapproved/New documents";


$rejecteddocuments = new link($this->uri(array("action" => "rejecteddocuments")));
$rejecteddocuments->link = "Rejected documents";


$links = $newdoclink->show() . '&nbsp;|&nbsp;' . $unapproveddocs->show() . '&nbsp;|&nbsp;' . $rejecteddocuments->show() . '<br/>';
$fs = new fieldset();
$fs->setLegend('Navigation');
$fs->addContent($links);

echo $fs->show() . '<br/>';


$table = $this->getObject("htmltable", "htmlelements");
$table->startHeaderRow();
$table->addHeaderCell("Title");
$table->addHeaderCell("Department");
$table->addHeaderCell("Owner");
$table->addHeaderCell("Telephone");
$table->addHeaderCell("Date");

$table->endHeaderRow();
$objIcon = $this->newObject('geticon', 'htmlelements');
$objIcon->setIcon('edit');
if (count($documents) > 0) {
    foreach ($documents as $document) {
        //$topic=  substr($document['topic'], strlen($this->baseDir));
        $link = new link($this->uri(array("action" => "showeditdocument", "id" => $document['id'])));
        $link->link = $document['filename'];
        $table->startRow();

        $table->addCell($link->show());
        $table->addCell($document['department']);
        $table->addCell($document['owner']);
       
        $table->addCell($document['telephone']);
   
        $table->addCell($document['date']);
        $table->endRow();
    }
}
echo $table->show();
?>
