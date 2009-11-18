<?php
echo "Essay Table Updated Succesfully";
$this->loadClass('htmltable','htmlelements');
$table = new htmltable();
$essays=$this->objStudentEssays->getstudentEssays($essayid);

foreach($essays as $essay) {
    $table->startRow();
    $table->addCell($essay['content']);
    $table->endRow();
}
echo $table->show();
?>
