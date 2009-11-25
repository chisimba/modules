<?php
echo "Essay Table Updated Succesfully";
$this->loadClass('htmltable','htmlelements');
    
$table = new htmltable();
$table->addCell('Essayid');
$table->addCell('Submitdate');
$essays=$this->objStudentEssays->getstudentEssays($essayid);
foreach($essays as $essay) {
    $table->width='30%';
    $table->border='2';
    $table->cellspacing='1';
    $table->startRow();
    $table->addCell($essay['essayid']);
    $table->addCell($essay['submitdate']);
    $table->endRow();
}
echo $table->show();
?>
