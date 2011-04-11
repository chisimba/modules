<?php

$this->loadClass('label', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('fieldset', 'htmlelements');
////$this->loadClass('textinput', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');


$this->setVar('pageSuppressXML', TRUE);

//$this->loadClass('iframe', 'htmlelements');
//$this->loadClass('button', 'htmlelements');

$table = $this->newObject('htmltable', 'htmlelements');
$table->startRow();
$table->addCell($document['department']);
$table->endRow();

$table->startRow();
$table->addCell('Current editor:&nbsp;' . $this->objUser->fullname($document['currentuserid']));
$table->endRow();

$objUsers = $this->getObject('dbapousers');
$allUsers = $objUsers->getAllUsers();

$userlist = "";
foreach ($allUsers as $user) {
    $checkbox = new checkbox('selectedusers[]', $user['userid']);
    $checkbox->value = $user['userid'];
    $checkbox->cssId = 'user_' . $user['id'];
    $checkbox->cssClass = 'user_option';

    $label = new label(' ' . $user['firstname'] . ',' . $user['surname'], 'user_' . $user['userid']);

    $userlist .= ' ' . $checkbox->show() . $label->show() . '<br />';
}

$table->startRow();
$table->addCell($userlist);
$table->endRow();

$legend = "Faculty";

$fs = new fieldset();
$fs->setLegend($legend);
$fs->addContent('<b>' . $table->show() . '</b>');

echo $fs->show() . '<br/>';

$button = new button('cancel', $this->objLanguage->languageText('word_cancel'));
$uri = $this->uri(array('action' => 'showeditdocument', 'id' => $id));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
//$form->addToForm($button->show());

$forwardbutton = new button('forward', 'Forward');
$uri = $this->uri(array('action' => 'fowarddocument', 'id' => $id));
$forwardbutton->setOnClick('javascript: window.location=\'' . $uri . '\'');


$fs = new fieldset();
$fs->setLegend('Forward');
$fs->addContent($button->show().'&nbsp;'.$forwardbutton->show());


echo $fs->show() . '<br/>';
?>
