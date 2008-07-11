<script language="JavaScript" type="text/javascript" >
    jQuery('input[name=recipienttarget]:radio').livequery(function() {
        if (jQuery('input[name=recipienttarget]:radio:checked').val() == 'site') {
            jQuery('.context_option').attr('disabled', 'disabled');
        } else {
            jQuery('.context_option').removeAttr('disabled');
        }
    });
    
    jQuery('input[name=recipienttarget]:radio').livequery('click', function() {
        if (jQuery('input[name=recipienttarget]:radio:checked').val() == 'site') {
            jQuery('.context_option').attr('disabled', 'disabled');
        } else {
            jQuery('.context_option').removeAttr('disabled');
        }
    });
    
</script>
<?php

$this->appendArrayVar('headerParams', $this->getJavaScriptFile('jquery/jquery.livequery.js', 'htmlelements'));

$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('link', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('radio', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');
$this->loadClass('label', 'htmlelements');

$header = new htmlHeading();
$header->type = 1;

if ($mode == 'edit') {
    $header->str = 'Edit Announcement';
} else {
    $header->str = 'Add New Announcement';
}

echo $header->show();

$form = new form ('announcement', $this->uri(array('action'=>'save')));

$table = $this->newObject('htmltable', 'htmlelements');
$table->startRow();

$label = new label ('Title', 'input_title');
$title = new textinput('title');
$title->size = 60;

$table->addCell($label->show(), 120);
$table->addCell($title->show());
$table->endRow();

$contextsList = '';

if (count($lecturerContext) > 0) {
    foreach ($lecturerContext as $context)
    {
        $checkbox = new checkbox ('contexts[]', $context);
        $checkbox->value = $context;
        $checkbox->cssId = 'context_'.$context;
        $checkbox->cssClass = 'context_option';
        
        $label = new label(' '.$this->objContext->getTitle($context), 'context_'.$context);
        
        $contextsList .= '<br />&nbsp; &nbsp; '.$checkbox->show().$label->show();
    }
}

if ($isAdmin && count($lecturerContext) > 0) {
    $table->startRow();
    
    $label = new label ('Title', 'input_title');
    $title = new textinput('title');
    
    $table->addCell('Send to:');
    
    $recipientTarget = new radio ('recipienttarget');
    $recipientTarget->setBreakSpace('<br />');
    $recipientTarget->addOption('site', 'Site - All Users');
    $recipientTarget->addOption('context', 'Only to the following courses');
    
    $recipientTarget->setSelected('site');
    
    $str = $recipientTarget->show();
    
    $table->addCell($str.$contextsList);
    
    $table->endRow();
} else if ($isAdmin) {
    $recipientTarget = new hiddeninput('recipient', 'site');
    $form->addToForm($recipientTarget->show());
} else {
    $table->startRow();
    
    $label = new label ('Title', 'input_title');
    $title = new textinput('title');
    
    $table->addCell('Send to:');
    
    
    $str = 'the following courses:';
    
    $table->addCell($str.$contextsList);
    
    $table->endRow();
    
    $recipientTarget = new hiddeninput('recipient', 'context');
    $form->addToForm($recipientTarget->show());
}


$table->startRow();

$htmlArea = $this->newObject('htmlarea', 'htmlelements');
$htmlArea->name = 'message';

$table->addCell('Message');
$table->addCell($htmlArea->show());
$table->endRow();


$table->startRow();

$email2Users = new radio ('email');
$email2Users->addOption('N', 'No');
$email2Users->addOption('Y', 'Yes');

$email2Users->setSelected('N');
$email2Users->setBreakSpace(' &nbsp; ');

$table->addCell('Email to Users');
$table->addCell($email2Users->show());
$table->endRow();

$table->startRow();

$table->addCell('&nbsp;');
$table->addCell('&nbsp;');
$table->endRow();

$table->startRow();

$button = new button ('send', 'Post Announcement');
$button->setToSubmit();

$table->addCell('&nbsp;');
$table->addCell($button->show());
$table->endRow();


$form->addToForm($table->show());

$modeInput = new hiddeninput('mode', $mode);
$form->addToForm($modeInput->show());

echo $form->show();



$addLink = new link ($this->uri(array('action'=>'add')));
$addLink->link = 'Post New Announcement';

echo $addLink->show();



?>