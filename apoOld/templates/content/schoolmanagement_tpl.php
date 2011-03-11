<?php
$this->loadClass('htmlheading', 'htmlelements');
$objIcon = $this->newObject('geticon', 'htmlelements');
$header = new htmlheading();
$header->type = 2;
$header->str = $this->objLanguage->languageText('mod_apo_schoolmanagement', 'apo');

echo $header->show();


$newschoollink = new link($this->uri(array("action" => "newschool", "selected" => $selected)));
$newschoollink->link = "Add New School";

echo $newschoollink->show();

$table = $this->getObject("htmltable", "htmlelements");
$table->startHeaderRow();
$table->addHeaderCell("Faculty");
$table->addHeaderCell("School");
$table->addHeaderCell("Administrator");
$table->addHeaderCell("Telephone");
$table->addHeaderCell("Actions");
$table->endHeaderRow();

$objIcon->setIcon('edit');
$objIcon->alt = $this->objLanguage->languageText('mod_apo_editschool', 'apo', 'Edit School');
$objIcon->title = $this->objLanguage->languageText('mod_apo_editschool', 'apo', 'Edit School');
$editIcon = $objIcon->show();

$objIcon->setIcon('delete');
$objIcon->alt = $this->objLanguage->languageText('mod_apo_deleteschool', 'apo', 'Delete School');
$objIcon->title = $this->objLanguage->languageText('mod_apo_deleteschool', 'apo', 'Delete School');
$deleteIcon = $objIcon->show();

if (count($schools) > 0) {
    foreach ($schools as $school) {
        $table->startRow();
        $table->addCell($school['faculty']);
        $table->addCell($school['school']);
        $table->addCell($school['contact_person']);
        $table->addCell($school['telephone']);

        $editOption = new link ($this->uri(array('action'=>'editschool', "selected" => $selected, 'id'=>$school['id'])));
        $editOption->link = $editIcon;
        $edit = $editOption->show();

        $deleteLink = new link ($this->uri(array('action'=>'deleteschool', "selected" => $selected, 'id'=>$school['id'])));
        $deleteLink->link = $deleteIcon;
        $delete = $deleteLink->show();

        $table->addCell($edit.' &nbsp; '.$delete, 100);
        $table->endRow();
    }
}
echo $table->show();
?>