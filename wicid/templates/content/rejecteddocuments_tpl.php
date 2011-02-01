<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php
$this->loadClass('htmlheading', 'htmlelements');
$header = new htmlheading();
$header->type = 2;
$header->str = $this->objLanguage->languageText('mod_wicid_rejected', 'wicid', 'Rejected Documents');

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

$links = $newdoclink->show() . '&nbsp;|&nbsp;' . $unapproveddocs->show() . '&nbsp;|&nbsp;' . $rejecteddocuments->show() . '<br/>';

//Add navigation to fieldset
$fs = new fieldset();
$fs->setLegend('Navigation');
$fs->addContent($links);

echo $fs->show();

echo "<br />";
$table = $this->getObject("htmltable", "htmlelements");

if (count($documents) > 0) {
    $table->startHeaderRow();
    $table->addHeaderCell("Owner");
    $table->addHeaderCell("Ref No");
    $table->addHeaderCell("Title");
    $table->addHeaderCell("Topic");
    $table->addHeaderCell("Telephone");
    $table->addHeaderCell("Attachment");
    $table->endHeaderRow();
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
} else {
    $table->startRow();
    $table->addCell("There are no rejected documents");
    $table->endRow();
}

//Add rejected documents table to fieldset
$fs = new fieldset();
$fs->setLegend('Rejected Documents');
$fs->addContent($table->show());
echo $fs->show();
?>
