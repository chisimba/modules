<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php
$this->loadclass('link','htmlelements');

// to create a link
$studentImportUrl = new link();
$studentImportUrl->link($this->uri(array('action'=>'addstudent')));
$studentImportUrl->link = "Add Students";
echo $studentImportUrl->show();

?>
