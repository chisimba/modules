<?php
$js = $this->getJavascriptFile('radioselect.js', 'forum');
$this->appendArrayVar('headerParams', $js);

$this->setVar('bodyParams', 'onLoad="changeLabel();"');

$this->loadClass('form', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('iframe', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('link', 'htmlelements');
$this->loadClass('radio', 'htmlelements');
$this->loadClass('hiddeninput', 'htmlelements');



$header = new htmlheading();
$header->type=1;

$forumLink = new link($this->uri(array('action'=>'forum', 'id'=>$forumid)));
$forumLink->link = $forum['forum_name'];
$forumLink->title = $this->objLanguage->languageText('mod_forum_returntoforum');

$header->str=$forumLink->show().' - '.$this->objLanguage->languageText('mod_forum_postnewmessage');
echo $header->show();

if ($mode == 'fix') {
    echo '<span class="noRecordsMessage error"><strong>'.$this->objLanguage->languageText('mod_forum_messageisblank').'</strong><br />&nbsp;</span>';
}


$newTopicForm = new form('newTopicForm', $this->uri( array('module'=>'forum', 'action'=>'savenewtopic', 'type'=>$forumtype)));
$newTopicForm->displayType = 3;
$newTopicForm->addRule('title', $this->objLanguage->languageText('mod_forum_addtitle'), 'required');


$addTable = $this->getObject('htmltable', 'htmlelements');
$addTable->width='99%';
$addTable->cellpadding = 10;

// Title
$addTable->startRow();
    $subjectLabel = new label($this->objLanguage->languageText('word_subject').':', 'input_title');
    $addTable->addCell($subjectLabel->show(), 120);
    
    $titleInput = new textinput('title');
    $titleInput->size = 50;
    
    if ($mode == 'fix') {
        $titleInput->value = $details['title'];
    }
    
    $addTable->addCell($titleInput->show());

$addTable->endRow();

// Type of Topic
$addTable->startRow();

    $discussionTypeLabel = new label('<nobr>'.$this->objLanguage->languageText('mod_forum_typeoftopic').':</nobr>', 'input_discussionType');
    $addTable->addCell($discussionTypeLabel->show(), 120);
    
    $discussionType = new dropdown('discussionType');
    
    
    foreach ($discussionTypes as $element) 
    {
        $discussionType->addOption($element['id'], $element['type_name']);
    }
    
    $typeOfTopicTable = $this->getObject('htmltable', 'htmlelements');
    $typeOfTopicTable->startRow();
    $typeOfTopicTable->cellpadding  = 1;
    
    $counter = 0;
    $objIcon =& $this->getObject('geticon', 'htmlelements');
    
    $objRadioButton = new radio('discussionType');
    $objRadioButton->setTableColumns(3);
    $objRadioButton->setBreakSpace('table');
    
    
    foreach ($discussionTypes as $element) 
    {
        $objIcon->setIcon($element['type_icon'], NULL, 'icons/forum/');
        
        $cellContent = $objIcon->show().' '.$element['type_name'];
        
        $objRadioButton->addOption($element['id'], $objIcon->show().' '.$element['type_name']);
        
        $objRadioButton->extra = 'onclick="changeLabel();"';
    }
    
    // TODO: Set to NULL and add client side validation
    if ($mode == 'fix') {
        $objRadioButton->setSelected($details['type']);
    } else {
        $objRadioButton->setSelected($discussionTypes[0]['id']);
    }
    
    $addTable->addCell($objRadioButton->show());
    
    


$addTable->endRow();

// Show Sticky Topic
if ($this->isValid('moderatetopic') || $this->isValid('moderatetopic')) {
    $addTable->startRow();
    $addTable->addCell($this->objLanguage->languageText('mod_forum_stickytopic', 'Sticky Topic').':');
    
    $sticky = new radio ('stickytopic');
    $sticky->addOption('1', $this->objLanguage->languageText('word_yes'));
    $sticky->addOption('0', $this->objLanguage->languageText('word_no'));
    $sticky->setSelected('0');
    $sticky->setBreakSpace(' &nbsp; ');
    $addTable->addCell($sticky->show());
    $addTable->endRow();
} else {
    $sticky = new hiddeninput ('stickytopic', 'no');
    $newTopicForm->addToForm($sticky->show());
}

// Language
$addTable->startRow();

    $languageLabel = new label($this->objLanguage->languageText('word_language').':', 'input_language');
    $addTable->addCell($languageLabel->show(), 120);
    
    $language =& $this->newObject('language','language');
    $languageList = $this->newObject('dropdown', 'htmlelements');
    $languageList->name = 'language';
    $languageCodes = & $this->newObject('languagecode','language');
    
    // Sort Associative Array by Language, not ISO Code
    asort($languageCodes->iso_639_2_tags); 
    
    foreach ($languageCodes->iso_639_2_tags as $key => $value) {
        $languageList->addOption($key, $value);
    }
    
    if ($mode == 'fix') {
        $languageList->setSelected($details['language']);
    } else {
        $languageList->setSelected($languageCodes->getISO($language->currentLanguage()));
    }
    
    $addTable->addCell($languageList->show());

$addTable->endRow();

$addTable->startRow();

$htmlareaLabel = new label($this->objLanguage->languageText('word_message').':', 'message');

if ($mode == 'fix') {
    $messageCSS = 'error';
} else {
    $messageCSS = NULL;
}
$addTable->addCell($htmlareaLabel->show(), 120, 'top', NULL, $messageCSS);

$editor=&$this->newObject('htmlarea','htmlelements');
$editor->setName('message');
$editor->setContent(NULL);
$editor->setRows(15);
$editor->setColumns('100');
$editor->setDefaultToolBarSetWithoutSave();

$objContextCondition = &$this->getObject('contextcondition','contextpermissions');
$this->isContextLecturer = $objContextCondition->isContextMember('Lecturers');

if ($this->contextCode == 'root') {
    $editor->context = FALSE;
} else if ($this->isContextLecturer || $objContextCondition->isAdmin()) {
    $editor->context = TRUE;
} else {
    $editor->context = FALSE;
}
		
$addTable->addCell($editor->show());

$addTable->endRow();
// ------------------------------

// Only show if forum attachments are allowed

if ($forum['attachments'] == 'Y') {
    $addTable->startRow();
    
    $attachmentsLabel = new label($this->objLanguage->languageText('mod_forum_attachments').':', 'attachments');
    $addTable->addCell($attachmentsLabel->show(), 120);
    
    $attachmentIframe = new iframe();
    $attachmentIframe->width='100%';
    $attachmentIframe->height='100';
    $attachmentIframe->frameborder='0';
    $attachmentIframe->src= $this->uri(array('action' => 'attachments', 'id'=>$temporaryId, 'forum' => $forumid, 'type'=>$forumtype)); 
    
    $addTable->addCell($attachmentIframe->show());
    
    $addTable->endRow();
}
// ------------------------------


// Show Forum Subscriptions if enabled

if ($forum['subscriptions'] == 'Y') {
	$addTable->startRow();
	$addTable->addCell($this->objLanguage->languageText('mod_forum_emailnotification', 'Email Notification').':');
	$subscriptionsRadio = new radio ('subscriptions');
	$subscriptionsRadio->addOption('nosubscriptions', $this->objLanguage->languageText('mod_forum_donotsubscribetothread', 'Do not subscribe to this thread'));
	$subscriptionsRadio->addOption('topicsubscribe', $this->objLanguage->languageText('mod_forum_notifytopic', 'Notify me via email when someone replies to this thread'));
	$subscriptionsRadio->addOption('forumsubscribe', $this->objLanguage->languageText('mod_forum_notifyforum', 'Notify me of ALL new topics and replies in this forum.'));
	$subscriptionsRadio->setBreakSpace('<br />');
	
	if ($forumSubscription) {
		$subscriptionsRadio->setSelected('forumsubscribe');
		$subscribeMessage = $this->objLanguage->languageText('mod_forum_youaresubscribedtoforum', 'You are currently subscribed to the forum, receiving notification of all new posts and replies.');
	} else {
		$subscriptionsRadio->setSelected('nosubscriptions');
		$subscribeMessage = $this->objLanguage->languageText('mod_forum_youaresubscribedtonumbertopic', 'You are currently subscribed to [NUM] topics.');
        $subscribeMessage = str_replace('[NUM]', $numTopicSubscriptions, $subscribeMessage);
	}
	
	$div = '
	<div class="forumTangentIndent">'.$subscribeMessage.'</div>';
	
	$addTable->addCell($subscriptionsRadio->show().$div);
	$addTable->endRow();
}

$addTable->startRow();

$addTable->addCell(' ');

$submitButton = new button('submit', $this->objLanguage->languageText('word_submit'));
$submitButton->setToSubmit();

$cancelButton = new button('cancel', $this->objLanguage->languageText('word_cancel'));
$returnUrl = $this->uri(array('action'=>'forum', 'id'=>$forumid, 'type'=>$forumtype));
$cancelButton->setOnClick("window.location='$returnUrl'");

$addTable->addCell($submitButton->show().' / '.$cancelButton->show());

$newTopicForm->addToForm($addTable->show());


$hiddenForumInput = new hiddeninput('forum', $forumid);
$newTopicForm->addToForm($hiddenForumInput->show());

$hiddenTemporaryId = new hiddeninput('temporaryId', $temporaryId);
$newTopicForm->addToForm($hiddenTemporaryId->show());


echo $newTopicForm->show();



?>