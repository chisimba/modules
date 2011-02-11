<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php
$this->loadClass('htmlheading', 'htmlelements');
$objIcon = $this->newObject('geticon', 'htmlelements');
$header = new htmlheading();
$header->type = 2;
$header->str = $this->objLanguage->languageText('mod_apo_facultymanagement', 'apo');

echo $header->show();


$newfacultylink = new link($this->uri(array("action" => "newfaculty", "selected" => $selected)));
$newfacultylink->link = "Add New Faculty";

/*$editfacultylink = new link($this->uri(array("action" => "editfaculty", "selected" => $selected)));
$editfacultylink->link = "Edit Faculty";


$deletefacultylink = new link($this->uri(array("action" => "deletefaculty", "selected" => $selected)));
$deletefacultylink->link = "deletefaculty";*/

echo $newfacultylink->show();// . '&nbsp;|&nbsp;' . $editfacultylink->show() . '&nbsp;|&nbsp;' . $deletefacultylink->show() . '<br/>';

$table = $this->getObject("htmltable", "htmlelements");
$table->startHeaderRow();
$table->addHeaderCell("Faculty");
$table->addHeaderCell("Administrator");
$table->addHeaderCell("Telephone");
$table->addHeaderCell("Actions");
$table->endHeaderRow();

$objIcon->setIcon('edit');
//$objIcon->alt = $this->objLanguage->languageText('mod_news_editstory', 'news', 'Edit Story');
//$objIcon->title = $this->objLanguage->languageText('mod_news_editstory', 'news', 'Edit Story');
$editIcon = $objIcon->show();

$objIcon->setIcon('delete');
//$objIcon->alt = $this->objLanguage->languageText('mod_news_deletestory', 'news', 'Delete Story');
//$objIcon->title = $this->objLanguage->languageText('mod_news_deletestory', 'news', 'Delete Story');
$deleteIcon = $objIcon->show();


if (count($faculties) > 0) {
    foreach ($faculties as $faculty) {
        $table->startRow();
        $table->addCell($faculty['faculty']);
        $table->addCell($faculty['contact_person']);
        $table->addCell($faculty['telephone']);

        $editOption = new link ($this->uri(array('action'=>'editfaculty', "selected" => $selected, 'id'=>$faculty['id'])));
        $editOption->link = $editIcon;
        $edit = $editOption->show();

        $deleteLink = new link ($this->uri(array('action'=>'deletefaculty', "selected" => $selected, 'id'=>$faculty['id'])));
        $deleteLink->link = $deleteIcon;
        $delete = $deleteLink->show();

        $table->addCell($edit.' &nbsp; '.$delete, 100);
        $table->endRow();
    }
}
echo $table->show();
?>
