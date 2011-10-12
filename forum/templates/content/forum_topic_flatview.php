<?php
//Sending display to 1 column layout
ob_start();

//$this->setVar('pageSuppressXML',true);
/**
* This template displays a topic in a flat view format
*/

$js='
<script type="text/javascript">
    //<![CDATA[

    function SubmitForm()
    {
        document.forms["postReplyForm"].submit();
    }

    //]]>
</script>
';

echo $js;


$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('link', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('button', 'htmlelements');

$objIcon = $this->newObject('geticon', 'htmlelements');

$header = new htmlheading();
$header->type=1;

$link = new link($this->uri(array('action'=>'forum', 'id'=>$post['forum_id'], 'type'=>$forumtype)));
$link->link = $post['forum_name'];
$headerString = $link->show().' &gt; '.stripslashes($post['post_title']);


$header->str=$headerString;

if ($this->objUser->isCourseAdmin($this->contextCode) && !$forumlocked && $forumtype != 'workgroup' && $this->isLoggedIn) {
    $objIcon->setIcon('moderate');
    $objIcon->title = $this->objLanguage->languageText('mod_forum_moderatetopic', 'forum');
    $objIcon->alt = $this->objLanguage->languageText('mod_forum_moderatetopic', 'forum');

    $moderateTopicLink = new link($this->uri(array('action'=>'moderatetopic', 'id'=>$post['topic_id'], 'type'=>$forumtype)));
    $moderateTopicLink->link = $objIcon->show();

    $header->str .= ' '.$moderateTopicLink->show();
}

echo $header->show();



//Confirmation messages
if ($this->getParam('message') == 'save') {
    $timeoutMessage = $this->getObject('timeoutmessage', 'htmlelements');
    $timeoutMessage->setMessage($this->objLanguage->languageText('mod_forum_postsaved', 'forum'));
    $timeoutMessage->setTimeout(20000);
    echo ('<p>'.$timeoutMessage->show().'</p>');
    
}
if ($this->getParam('message') == 'postupdated') {
    $timeoutMessage = $this->getObject('timeoutmessage', 'htmlelements');
    $timeoutMessage->setMessage($this->objLanguage->languageText('mod_forum_postupdated', 'forum'));
    $timeoutMessage->setTimeout(10000);
    echo ('<p>'.$timeoutMessage->show().'</p>');
}
if ($this->getParam('message') == 'replysaved') {
    $timeoutMessage = $this->getObject('timeoutmessage', 'htmlelements');
    $timeoutMessage->setMessage($this->objLanguage->languageText('mod_forum_replysaved', 'forum'));
    $timeoutMessage->setTimeout(10000);
    echo ('<p>'.$timeoutMessage->show().'</p>');
}

// Error Messages
if ($this->getParam('message') == 'cantreplyforumlocked') {
    $this->setErrorMessage('This Forum has been Locked. You cannot post a reply to this Topic'); // LTE
}
if ($this->getParam('message') == 'cantreplytopiclocked') {
    $this->setErrorMessage('This Topic has been Locked. You cannot post a reply to this Topic'); // LTE
}

echo $changeDisplayForm;

if ($post['status'] =='CLOSE') {
    echo '<div class="forumTangentIndent">';
    echo '<strong>'.$this->objLanguage->languageText('mod_forum_topiclockedby', 'forum').' '.$this->objUser->fullname($post['lockuser']).' on '.$this->objDateTime->formatdate($post['lockdate']).'</strong>';
    echo '<p>'.$post['lockreason'].'</p>';
    echo '</div>';
}

$ratingsForm = new form('savepostratings', $this->uri(array('action'=>'savepostratings')));

$ratingsForm->addToForm($thread);

$hiddenTypeInput = new textinput('discussionType');
$hiddenTypeInput->fldType = 'hidden';
$hiddenTypeInput->value = $post['type_id'];
$ratingsForm->addToForm($hiddenTypeInput->show());


$hiddenTangentInput = new textinput('parent');
$hiddenTangentInput->fldType = 'hidden';
$hiddenTangentInput->value = $post['post_id'];
$ratingsForm->addToForm($hiddenTangentInput->show());

$topicHiddenInput = new textinput('topic');
$topicHiddenInput->fldType = 'hidden';
$topicHiddenInput->value = $post['topic_id'];
$ratingsForm->addToForm($topicHiddenInput->show());

$hiddenForumInput = new textinput('forum');
$hiddenForumInput->fldType = 'hidden';
if (isset($forum)) {
    $hiddenForumInput->value = $forum['id'];
}

$ratingsForm->addToForm($hiddenForumInput->show());

$hiddenTemporaryId = new textinput('temporaryId');
$hiddenTemporaryId->fldType = 'hidden';
if (!isset($temporaryId)) {
    $temporaryId="";
}
$hiddenTemporaryId->value = $temporaryId;
$ratingsForm->addToForm($hiddenTemporaryId->show());

// Determine whether to show the submit form for the ratings form
// Without this button, form is a waste, but need to make efficient
if ($showRatingsForm) {
    $objButton = new button('submitForm');
    $objButton->cssClass = 'save';
    $objButton->setValue($this->objLanguage->languageText('mod_forum_sendratings', 'forum'));
    $objButton->setToSubmit();

    if ($post['status'] != 'CLOSE' && !$forumlocked) {
        $ratingsForm->addToForm('<p align="right">'.$objButton->show().'</p>');
    }


}

echo $ratingsForm->show();


///----------------------------------------------------------

if (isset($tangentsTable)) {
    echo $tangentsTable;
}

/*
Do not show reply form if:
1) Topic is Locked
2) Forum is Locked
3) User has just replied or posted message
*/
if ($post['status'] != 'CLOSE' && !$forumlocked && $this->getParam('message') == '' && $this->isLoggedIn) {
    $header = new htmlheading();
    $header->type=3;
    $header->str = $this->objLanguage->languageText('mod_forum_replytotopic', 'forum');

    echo $header->show();

    echo $this->objPost->showPostReplyForm($post['post_id'], FALSE);
    
}

// $replylink = new link($this->uri(array('action'=>'postreply', 'id'=>$post['post_id'], 'type'=>$forumtype)));
// $replylink->link = $this->objLanguage->languageText('mod_forum_replytotopic');

$moderateTopicLink = new link($this->uri(array('action'=>'moderatetopic', 'id'=>$post['topic_id'], 'type'=>$forumtype)));
$moderateTopicLink->link = $this->objLanguage->languageText('mod_forum_moderatetopic', 'forum');

$newtopiclink = new link($this->uri(array('action'=>'newtopic', 'id'=>$post['forum_id'], 'type'=>$forumtype)));
$newtopiclink->link = $this->objLanguage->languageText('mod_forum_startnewtopic', 'forum');

$returntoforum = new link($this->uri(array('action'=>'forum', 'id'=>$post['forum_id'], 'type'=>$forumtype)));
$returntoforum->link = $this->objLanguage->languageText('mod_forum_returntoforum', 'forum');

echo '<p align="center">';






// if ($post['status'] != 'CLOSE' && !$forumlocked) {
// echo $replylink->show().' / ';
// }

if ((!$forumlocked && $this->objUser->isCourseAdmin($this->contextCode)) || $forumtype == 'workgroup') {
    echo $newtopiclink->show().' / ';
}

if ($this->objUser->isCourseAdmin($this->contextCode) && !$forumlocked && $forumtype != 'workgroup' && $this->isLoggedIn) {
    echo $moderateTopicLink->show().' / ';
}

echo $returntoforum->show().'</p>';

echo $this->showForumFooter($post['forum_id'], FALSE);

$display = ob_get_contents();
ob_end_clean();

$this->setVar('middleColumn', $display);
?>