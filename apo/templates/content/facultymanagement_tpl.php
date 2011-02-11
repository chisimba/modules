<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php
$this->loadClass('htmlheading', 'htmlelements');
$header = new htmlheading();
$header->type = 2;
$header->str = $this->objLanguage->languageText('mod_apo_facultymanagement', 'apo');

echo $header->show();


$newfacultylink = new link($this->uri(array("action" => "newfaculty", "selected" => $selected)));
$newfacultylink->link = "Add New Faculty";

$editfacultylink = new link($this->uri(array("action" => "editfaculty", "selected" => $selected)));
$editfacultylink->link = "Edit Faculty";


$deletefacultylink = new link($this->uri(array("action" => "deletefaculty", "selected" => $selected)));
$deletefacultylink->link = "deletefaculty";

echo $newfacultylink->show() . '&nbsp;|&nbsp;' . $editfacultylink->show() . '&nbsp;|&nbsp;' . $deletefacultylink->show() . '<br/>';

$table = $this->getObject("htmltable", "htmlelements");
$table->startHeaderRow();
$table->addHeaderCell("Faculty");
$table->addHeaderCell("Administrator");
$table->addHeaderCell("Title");
$table->addHeaderCell("Telephone");
$table->endHeaderRow();

if (count($faculties) > 0) {
    foreach ($faculties as $faculty) {
        $table->startRow();
        $table->addCell($faculty['faculty']);
        $table->addCell($faculty['contact_person']);
        $table->addCell($faculty['refno']);
        $table->addCell($faculty['topic']);
        $table->addCell($faculty['telephone']);
        $table->endRow();
    }
}
echo $table->show();
?>
