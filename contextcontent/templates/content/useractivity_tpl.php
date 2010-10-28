<?php
$this->loadClass('link', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$header = new htmlHeading();
$header->type = "1";
$header->cssClass = "useractivitytitle";
$header->str = $this->objLanguage->languageText('mod_contextcontent_useractivity', 'contextcontent', 'User activity').'&nbsp;-&nbsp;'.$modulename;

echo $header->show();

$table = $this->getObject('htmltable', 'htmlelements');
$table->startHeaderRow();
$table->addHeaderCell('No');
$table->addHeaderCell('Names');
$table->addHeaderCell('Access Count');
$table->addHeaderCell('Last Access');

$table->endHeaderRow();
$count = 1;
foreach ($data as $row) {
    $link = new link($this->uri(array("action" => "viewuseractivitybyid", "userid" => $row['userid'])));
    $link->link = $this->objUser->fullname($row['userid']);
    $table->startRow();
    $table->addCell($count + ".");
    $table->addCell($row['userid']);
    $table->addCell($row['accesscount']);
    $table->addCell($row['lastaccess']);
    $table->endRow();
    $count++;
}
$toolbar = $this->getObject('contextsidebar','context');
$cssLayout = $this->newObject('csslayout', 'htmlelements');
$cssLayout->setNumColumns(2);
$cssLayout->setLeftColumnContent($toolbar->show());
$cssLayout->setMiddleColumnContent($table->show());

echo $cssLayout->show();
//echo $table->show();
?>
