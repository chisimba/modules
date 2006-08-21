<?php

$this->loadClass('form', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');

$form = new form('saveattachment', $this->uri(array('action'=>'saveattachment')));

// Todo Implement WorkGroup
// Todo Implement Context
$objSelectFile = $this->newObject('selectfile', 'filemanager');
$objSelectFile->name = 'attachment';
$form->addToForm($objSelectFile->show());

$button = new button('save', 'Save');
$button->setToSubmit();
$form->addToForm(' &nbsp; &nbsp; '.$button->show());

$hiddeninput = new hiddeninput('id', $id);
$form->addToForm($hiddeninput->show());

echo $form->show();

if (count($files) > 0) {

    echo '<ul>';
    
    foreach ($files AS $file)
    {
        echo ('<li>'.$file['filename'].'</li>');
    
    }
    
    echo '</ul>';
} 


?>