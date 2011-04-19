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

$action = 'fowarddocument';

$form = new form('forwardform', $this->uri(array('action' => $action, 'id' => $id, 'formname'=>'forward',"from"=>$from)));

$table = $this->newObject('htmltable', 'htmlelements');

$legend = "Faculty";

$fs = new fieldset();
$fs->setLegend($legend);
$fs->addContent( $document['department']);

echo $fs->show() . '<br/>';

$table->startRow();
$table->boarder='1';
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


$fs = new fieldset();
$fs->setLegend('Forward');
$fs->addContent($table->show());
$form->addToForm($fs->show().'</br>');

$button = new button('cancel', $this->objLanguage->languageText('word_cancel'));
$uri = $this->uri(array('action' => 'showSection','from'=>$from, 'id' => $id));
$button->setOnClick('javascript: window.location=\'' . $uri . '\'');
$form->addToForm($button->show().'&nbsp');

$forwardbutton = new button('forward', $this->objLanguage->languageText('mod_apo_forward', 'apo', 'Forward'));
//$uri = $this->uri(array('action' => 'fowarddocument', 'id' => $id));
$forwardbutton->setToSubmit();

$form->addToForm($forwardbutton->show());


echo $form->show()
?>