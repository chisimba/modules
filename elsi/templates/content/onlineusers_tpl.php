<?php

$this->loadClass("link", "htmlelements");
$this->loadClass("htmltable", "htmlelements");

$table = $this->getObject('htmltable', 'htmlelements');
$count = 0;
echo '<br/><br/>';
foreach ($users as $user) {

    $table->startRow();
    $table->addCell(++$count);
    $table->addCell($user['username']);
    $table->addCell($user['firstname']);
    $table->addCell($user['surname']);
    $table->endRow();
}

echo $table->show();
?>
