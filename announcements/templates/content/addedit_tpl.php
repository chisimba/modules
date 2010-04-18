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
$outerLayer = "";
$this->appendArrayVar('headerParams', $this->getJavaScriptFile('jquery/jquery.livequery.js', 'jquery'));

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
    $header->str = $this->objLanguage->languageText('mod_announcements_update', 'announcements', 'Edit Announcement');
    $formAction = 'update';
} else {
    $header->str = $this->objLanguage->languageText('mod_announcements_addnewannouncement', 'announcements', 'Add New Announcement');
    $formAction = 'save';
}

$outerLayer = $header->show();

$form = new form ('announcement', $this->uri(array('action'=>$formAction)));

if ($mode == 'edit') {
    $hiddenInput = new hiddeninput('id', $announcement['id']);
    $form->addToForm($hiddenInput->show());
}

$table = $this->newObject('htmltable', 'htmlelements');
$table->startRow();

$label = new label ($this->objLanguage->languageText('word_title', 'system', 'Title'), 'input_title');
$title = new textinput('title');
$title->size = 60;
if(!empty($enteredtitle)){
    $title->value = $enteredtitle;
}elseif ($mode == 'edit') {
    $title->value = $announcement['title'];
}
//Check if title is empty
$form->addRule('title', $this->objLanguage->languageText('mod_announcements_titlerequired','announcements'), 'required');
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
        
        if ($mode == 'add' && $context == $this->objContext->getContextCode()) {
            $checkbox->ischecked = TRUE;
        }
        
        if ($mode == 'edit') {
            if (in_array($context, $contextAnnouncementList)) {
                $checkbox->ischecked = TRUE;
            }
        }
        if ($mode == 'fixup') {
            if (in_array($context, $enteredcontexts)) {
                $checkbox->ischecked = TRUE;
            }
        }
        $contextRow=$this->objContext->getContext($context);
        $label = new label(' '.$contextRow['title'], 'context_'.$context);
        
        $contextsList .= '<br />&nbsp; &nbsp; '.$checkbox->show().$label->show();
    }
}


if ($mode == 'add') {
    if ($isAdmin && count($lecturerContext) > 0) {
        $table->startRow();
        
        $table->addCell($this->objLanguage->languageText('mod_announcements_sendto', 'announcements', 'Send to').':');
        
        $recipientTarget = new radio ('recipienttarget');
        $recipientTarget->setBreakSpace('<br />');
        $recipientTarget->addOption('site', $this->objLanguage->languageText('mod_announcements_allusers', 'announcements', 'Site - All Users'));
        $recipientTarget->addOption('context', $this->objLanguage->code2Txt('mod_announcements_onlytofollowing', 'announcements', NULL, 'Only to the following [-contexts-]'));
        
        $recipientTarget->setSelected('site');
        
        $str = $recipientTarget->show();
        
        $table->addCell($str.$contextsList);
        
        $table->endRow();
    } else if ($isAdmin) {
        $recipientTarget = new hiddeninput('recipienttarget', 'site');
        $form->addToForm($recipientTarget->show());
    } else {
        $table->startRow();
        $table->addCell($this->objLanguage->languageText('mod_announcements_sendto', 'announcements', 'Send to'));
        
        $str = $this->objLanguage->code2Txt('mod_announcements_followingcontexts', 'announcements', NULL, 'the following [-contexts-]').':';
        
        $table->addCell($str.$contextsList);
        
        $table->endRow();
        
        $recipientTarget = new hiddeninput('recipienttarget', 'context');
        $form->addToForm($recipientTarget->show());
    }
} else {
echo "I am the else";
    $recipientTarget = new hiddeninput('recipienttarget', $announcement['contextid']);
    $form->addToForm($recipientTarget->show());
    
    if ($announcement['contextid'] == 'site') {
        $table->startRow();
        $table->addCell($this->objLanguage->languageText('word_type', 'system', 'Type').':');
        $table->addCell($this->objLanguage->languageText('mod_announcements_siteannouncement', 'announcements', 'Site Announcement'));
        $table->endRow();
    } else {
        $table->startRow();
        
        $label = new label ($this->objLanguage->languageText('word_title', 'system', 'Title'), 'input_title');
        $title = new textinput('title');
	if(!empty($enteredtitle)){
	    $title->value = $enteredtitle;
	}elseif ($mode == 'edit') {
	    $title->value = $announcement['title'];
	}
        $form->addRule('title', $this->objLanguage->languageText('mod_announcements_titlerequired','announcements'), 'required');
        $table->addCell($this->objLanguage->languageText('mod_announcements_sendto', 'announcements', 'Send to').':');
        
        $str = $this->objLanguage->code2Txt('mod_announcements_followingcontexts', 'announcements', NULL, 'the following [-contexts-]').':';
        $recipientTarget = new radio ('recipienttarget');
        $recipientTarget->setBreakSpace('<br />');
        $recipientTarget->addOption('site', $this->objLanguage->languageText('mod_announcements_allusers', 'announcements', 'Site - All Users'));
        $recipientTarget->addOption('context', $this->objLanguage->code2Txt('mod_announcements_onlytofollowing', 'announcements', NULL, 'Only to the following [-contexts-]'));
        if(!empty($enteredrecipienttarget)){
         $recipientTarget->setSelected($enteredrecipienttarget);
        }else{
         $recipientTarget->setSelected('site');
        }
        
        $str2 = $recipientTarget->show();

        //$table->addCell($str.$contextsList);
        $table->addCell($str2.$contextsList);
        $table->endRow();
    }
}


$table->startRow();

$htmlArea = $this->newObject('htmlarea', 'htmlelements');
$htmlArea->name = 'message';
if(!empty($enteredmessage)){
    $title->value = $enteredmessage;
}elseif ($mode == 'edit') {
    $htmlArea->value = $announcement['message'];
}

$table->addCell($this->objLanguage->languageText('word_message', 'system', 'Message'));
$table->addCell($htmlArea->show());
$table->endRow();

$table->startRow();

$email2Users = new radio ('email');
$email2Users->addOption('N', $this->objLanguage->languageText('word_no', 'system', 'No'));
$email2Users->addOption('Y', $this->objLanguage->languageText('word_yes', 'system', 'Yes'));


$email2Users->setSelected('N');

$email2Users->setBreakSpace(' &nbsp; ');

$table->addCell($this->objLanguage->languageText('mod_announcements_emailtousers', 'announcements', 'Email to Users'));
$table->addCell($email2Users->show());
$table->endRow();

$table->startRow();

$table->addCell('&nbsp;');
$table->addCell('&nbsp;');
$table->endRow();

$table->startRow();

$button = new button ('send', $this->objLanguage->languageText('mod_announcements_postannouncement', 'announcements', 'Post Announcement'));
$button->setToSubmit();

$table->addCell('&nbsp;');
$table->addCell($button->show());
$table->endRow();


$form->addToForm($table->show());

$modeInput = new hiddeninput('mode', $mode);
$form->addToForm($modeInput->show());

$outerLayer .= $form->show();

$outerLayer = '<div class="outerwrapper">' . $outerLayer . '</div>';
echo $outerLayer;



$backLink = new link ($this->uri(NULL));
$backLink->link = $this->objLanguage->languageText('mod_announcements_back', 'announcements', 'Back to Announcements');

echo "<div class='modulehome'></div><div class='modulehomelink'>"
  . $backLink->show() . '</div>';

?>