<?php


// Check if Title has Re: attached to it   
if (substr($post['post_title'], 0, 3) == 'Re:') {
    // If it does, simply strip slashes
    $defaultTitle = stripslashes($post['post_title']);
    $originalTitle = stripslashes($post['post_title']);
} else {
    // Else strip slashes AND append Re: to the title
    $defaultTitle = 'Re: '.stripslashes($post['post_title']);
    $originalTitle = 'Re: '.stripslashes($post['post_title']);
}
    
// If result of server-side validation, change default title to posted one
if ($mode == 'fix') {
    // Select Posted Title
    $defaultTitle = $details['title'];
} 


?>
<script type="text/javascript">
function clearForTangent()
{
    postTitle = "<?php echo (addslashes($defaultTitle)); ?>";

    
    if (document.postReplyForm.replytype[1].checked)
    {
        
        if (document.postReplyForm.title.value == "<?php echo addslashes($originalTitle); ?>".split("'").join("\'"))
        {
            alert ('<?php echo $this->objLanguage->languageText('mod_forum_tangentsowntitles'); ?> "<?php echo addslashes($originalTitle); ?>"\n<?php echo $this->objLanguage->languageText('mod_forum_changetitle'); ?>.');
            document.postReplyForm.title.value = '';
            document.postReplyForm.title.focus();
            
            
        }
    }
    
    if (document.postReplyForm.replytype[0].checked)
    {
        if (document.postReplyForm.title.value == '')
        {
            document.postReplyForm.title.value = postTitle.split("'").join("\'");
            //"<?php echo (stripslashes($originalTitle)); ?>";
            document.postReplyForm.title.focus();
        }
    }


}
</script>
<?php

$this->loadClass('form', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('textarea', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('radio', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('iframe', 'htmlelements');


$header = new htmlheading();
$header->type=3;
$header->str=$this->objLanguage->languageText('mod_forum_postreply').': '.stripslashes($post['post_title']);
echo $header->show();

if ($mode == 'fix') {
    echo '<span class="noRecordsMessage error"><strong>'.$this->objLanguage->languageText('mod_forum_messageisblank').'</strong><br />&nbsp;</span>';
}

echo $postDisplay;

echo '<br/>';
$postReplyForm = new form('postReplyForm', $this->uri( array('action'=>'savepostreply', 'type'=>$forumtype)));
$postReplyForm->displayType = 3;
$postReplyForm->addRule('title', $this->objLanguage->languageText('mod_forum_addtitle'), 'required');


$addTable = $this->getObject('htmltable', 'htmlelements');
$addTable->width='99%';
$addTable->align='center';
$addTable->cellpadding = 10;


$addTable->startRow();
$subjectLabel = new label($this->objLanguage->languageText('word_subject').':', 'input_title');
$addTable->addCell($subjectLabel->show(), 100);

$titleInput = new textinput('title');
$titleInput->size = 50;

$titleInput->value = htmlspecialchars($defaultTitle);

$addTable->addCell($titleInput->show());

$addTable->endRow();

// type of post
$addTable->startRow();

$addTable->addCell('<nobr>'.$this->objLanguage->languageText('mod_forum_typeofreply').':</nobr>', 100);

$objElement = new radio('replytype');
$objElement->addOption('reply',$this->objLanguage->languageText('mod_forum_postasreply'));
$objElement->addOption('tangent', $this->objLanguage->languageText('mod_forum_postastangent'));
//$objElement->addOption('moderate','Post Reply as Moderator');

if ($mode == 'fix') {
    $objElement->setSelected($details['replytype']);
} else {
    $objElement->setSelected('reply');
}
$objElement->setBreakSpace('<br />');

$objElement->extra = ' onClick="clearForTangent()"';
    

$addTable->addCell($objElement->show());

$addTable->endRow();

$addTable->startRow();

    $languageLabel = new label($this->objLanguage->languageText('word_language').':', 'input_language');
    $addTable->addCell($languageLabel->show(), 100);
    
    $language =& $this->newObject('language','language');
    $languageList = new dropdown('language');
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

$addTable->addCell($this->objLanguage->languageText('word_message').':', 140);

$editor=&$this->newObject('htmlarea','htmlelements');
$editor->setName('message');
$editor->setContent('');
$editor->setRows(20);
$editor->setColumns('100');

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

// ------------------

if ($forum['attachments'] == 'Y') {
    $addTable->startRow();
    
    $attachmentsLabel = new label($this->objLanguage->languageText('mod_forum_attachments').':', 'attachments');
    $addTable->addCell($attachmentsLabel->show(), 100);
    
    $attachmentIframe = new iframe();
    $attachmentIframe->width='100%';
    $attachmentIframe->height='100';
    $attachmentIframe->frameborder='0';
    $attachmentIframe->src= $this->uri(array('module' => 'forum', 'action' => 'attachments', 'id'=>$temporaryId, 'forum' => $forum['id'], 'type'=>$forumtype)); 
    
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
	} else if ($topicSubscription) { 
        $subscriptionsRadio->setSelected('topicsubscribe');
		$subscribeMessage = $this->objLanguage->languageText('mod_forum_youaresubscribedtotopic', 'You are already subscribed to this topic.');
    } else {
		$subscriptionsRadio->setSelected('nosubscriptions');
        $subscribeMessage = $this->objLanguage->languageText('mod_forum_youaresubscribedtonumbertopic', 'You are currently subscribed to [NUM] topics.');
        $subscribeMessage = str_replace('[NUM]', $numTopicSubscriptions, $subscribeMessage);
	}
	
	$div = '<div class="forumTangentIndent">'.$subscribeMessage.'</div>';
	
	$addTable->addCell($subscriptionsRadio->show().$div);
	$addTable->endRow();
}

// ------------------------------

$addTable->startRow();

$addTable->addCell(' ');

$submitButton = new button('submit', $this->objLanguage->languageText('word_submit'));
$submitButton->setToSubmit();

$cancelButton = new button('cancel', $this->objLanguage->languageText('word_cancel'));
$returnUrl = $this->uri(array('action'=>'thread', 'id'=>$post['topic_id'], 'type'=>$forumtype));
$cancelButton->setOnClick("window.location='$returnUrl'");

$addTable->addCell($submitButton->show().' / '.$cancelButton->show());

$postReplyForm->addToForm($addTable);

$hiddenTypeInput = new textinput('discussionType');
$hiddenTypeInput->fldType = 'hidden';
$hiddenTypeInput->value = $post['type_id'];
$postReplyForm->addToForm($hiddenTypeInput->show());


$hiddenTangentInput = new textinput('parent');
$hiddenTangentInput->fldType = 'hidden';
$hiddenTangentInput->value = $post['post_id'];
$postReplyForm->addToForm($hiddenTangentInput->show());

$topicHiddenInput = new textinput('topic');
$topicHiddenInput->fldType = 'hidden';
$topicHiddenInput->value = $post['topic_id'];
$postReplyForm->addToForm($topicHiddenInput->show());

$hiddenForumInput = new textinput('forum');
$hiddenForumInput->fldType = 'hidden';
$hiddenForumInput->value = $forum['id'];
$postReplyForm->addToForm($hiddenForumInput->show());

$hiddenTemporaryId = new textinput('temporaryId');
$hiddenTemporaryId->fldType = 'hidden';
$hiddenTemporaryId->value = $temporaryId;
$postReplyForm->addToForm($hiddenTemporaryId->show());

echo $postReplyForm->show();

?>