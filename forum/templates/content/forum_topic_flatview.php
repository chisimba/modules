<?php
$this->setVar('pageSuppressXML',true);
/**
* This template displays a topic in a flat view format
*/

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

if ($this->isValid('moderatetopic') && !$forumlocked && $forumtype != 'workgroup' && $this->isLoggedIn) {
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
    echo '<strong>'.$this->objLanguage->languageText('mod_forum_topiclockedby', 'forum').' '.$this->objUser->fullname($post['lockuser']).' on '.formatdate($post['lockdate']).'</strong>';
    echo '<p>'.$post['lockReason'].'</p>';
    echo '</div>';
}

$ratingsForm = new form('savepostratings', $this->uri(array('action'=>'savepostratings')));

$ratingsForm->addToForm($thread);

// Determine whether to show the submit form for the ratings form
// Without this button, form is a waste, but need to make efficient
if ($showRatingsForm) {
    $objButton = new button('submitForm');
    $objButton->setValue($this->objLanguage->languageText('mod_forum_sendratings', 'forum'));
    $objButton->setToSubmit();
    
    if ($post['status'] != 'CLOSE' && !$forumlocked) {
        $ratingsForm->addToForm('<p align="right">'.$objButton->show().'</p>');
    }
    
    // These elements are need for the redirect
    $hiddenTopicId = new textinput('topic', $post['topic_id']);
    $hiddenTopicId->fldType = 'hidden';
    $ratingsForm->addToForm($hiddenTopicId->show());
}

echo $ratingsForm->show();


///----------------------------------------------------------

if ($post['topic_tangent_parent'] == '0' && count($tangents) > 0) {

    $header = new htmlheading();
    $header->type=3;
    $header->str = 'Tangents';
    
    echo $header->show();
    
    $table = $this->getObject('htmltable', 'htmlelements');
    $table->cellpadding = 5;
    $table->cellspacing = 1;
    $table->startHeaderRow();
    $table->addHeaderCell($this->objLanguage->languageText('mod_forum_topicconversation', 'forum'));
    $table->addHeaderCell($this->objLanguage->languageText('word_author', 'forum'), NULL, NULL, 'center');
    $table->addHeaderCell($this->objLanguage->languageText('word_replies', 'forum'), NULL, NULL, 'center');
    $table->addHeaderCell($this->objLanguage->languageText('word_views', 'forum'), NULL, NULL, 'center');
    $table->addHeaderCell($this->objLanguage->languageText('mod_forum_lastpost', 'forum'), NULL, NULL, 'center');
    $table->endHeaderRow();
    
    $row = 'odd';
    foreach ($tangents AS $tangent)
    {
        $table->startRow();
        
        $titleLink = new link($this->uri(array('action'=>'viewtopic', 'id'=>$tangent['id'], 'type'=>$forumtype)));
        $titleLink->link = $tangent['post_title'];
        
        $table->addCell($titleLink->show(), NULL, NULL, NULL, $row);
        $table->addCell($tangent['firstName'].' '.$tangent['surname'], NULL, NULL, 'center', $row);
        $table->addCell($tangent['replies'], NULL, NULL, 'center', $row);
        $table->addCell($tangent['views'], NULL, NULL, 'center', $row);
        
        $objIcon = $this->getObject('geticon', 'htmlelements');
        $objIcon->setIcon('gotopost', NULL, 'icons/forum/');
        
        $lastPostLink = new link ($this->uri(array('action'=>'viewtopic', 'id'=>$tangent['id'], 'post'=>$tangent['last_post'], 'type'=>$forumtype)));
        $lastPostLink->link = $objIcon->show();
        
        if (formatDate($tangent['lastdate']) == date('j F Y')) {
            $datefield = $this->objLanguage->languageText('mod_forum_todayat', 'forum').' '.formatTime($tangent['lastdate']);
        } else {
            $datefield = formatDate($tangent['lastdate']).' - '.formatTime($tangent['lastdate']);
        }
        
        $table->addCell($datefield.'<br />'.$tangent['lastFirstName'].' '.$tangent['lastSurname'].$lastPostLink->show(), Null, 'center', 'right', $row.' smallText');
        
        $table->endRow();
        
        $row = $row=='odd' ? 'even' : 'odd';
    }
    
    echo $table->show();
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

$changetopicstatus = new link($this->uri(array('action'=>'topicstatus', 'id'=>$post['topic_id'], 'type'=>$forumtype)));
$changetopicstatus->link = $this->objLanguage->languageText('mod_forum_changetopicstatus', 'forum');

$returntoforum = new link($this->uri(array('action'=>'forum', 'id'=>$post['forum_id'], 'type'=>$forumtype)));
$returntoforum->link = $this->objLanguage->languageText('mod_forum_returntoforum', 'forum');

echo '<p align="center">';






// if ($post['status'] != 'CLOSE' && !$forumlocked) {
    // echo $replylink->show().' / ';
// }

if ((!$forumlocked && $this->isValid('newtopic2')) || $forumtype == 'workgroup') {
    echo $newtopiclink->show().' / ';
}

if ($this->isValid('moderatetopic') && !$forumlocked && $forumtype != 'workgroup' && $this->isLoggedIn) {
    echo $moderateTopicLink->show().' / ';
}

echo $returntoforum->show().'</p>';

echo $this->showForumFooter($post['forum_id'], FALSE);

?>