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
$table->addHeaderCell('Logon time');
$table->addHeaderCell('Last active time');
$table->addHeaderCell('Idle time');
$table->endHeaderRow();
foreach ($users as $user) {

    $table->startRow();
    $table->addCell(++$count);
    $table->addCell($user['username']);
    $table->addCell($user['firstname']);
    $table->addCell($user['surname']);
    $logonTime = $this->objLoggedInUsers->getLogonTime($user['userid']);
    $lastActiveTime = $this->objLoggedInUsers->getLastActiveTime($user['userid']);
    $idleTime = $this->objLoggedInUsers->getInactiveTime($user['userid']);
    $table->addCell($logonTime);
    $table->addCell($lastActiveTime);
    $table->addCell($idleTime);
    $table->endRow();
}

echo $table->show();
?>
