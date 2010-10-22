<?php

$this->loadClass("link", "htmlelements");
$this->loadClass("htmltable", "htmlelements");

$table = $this->getObject('htmltable', 'htmlelements');
$count = 0;
echo '<br/><br/>';
$table->startHeaderRow();
$table->addHeaderCell('Count');
$table->addHeaderCell('Username');
$table->addHeaderCell('Firstname');
$table->addHeaderCell('Surname');
$table->addHeaderCell('Duration');
$table->endHeaderRow();
foreach ($users as $user) {

    $table->startRow();
    $table->addCell(++$count);
    $table->addCell($user['username']);
    $table->addCell($user['firstname']);
    $table->addCell($user['surname']);
    $duration = $this->objLoggedInUsers->getMyTimeOn($user['userid']);
    $table->addCell($duration);
    $table->endRow();
}

echo $table->show();
?>
