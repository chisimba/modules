<?php
//Sending display to 1 column layout
ob_start();

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
/**
* This template displays a topic in a freemind view format
*/

$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('link', 'htmlelements');
$this->loadClass('form', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->loadClass('button', 'htmlelements');

$objIcon = $this->getObject('geticon', 'htmlelements');

$header = new htmlheading();
$header->type=1;

$link = new link($this->uri(array('action'=>'discussion', 'id'=>$post['discussion_id'], 'type'=>$discussiontype)));
$link->link = $post['discussion_name'];
$headerString = $link->show().' &gt; '.stripslashes($post['post_title']);

$header->str=$headerString;

if ($this->objUser->isCourseAdmin() && !$discussionlocked && $discussiontype != 'workgroup' && $this->isLoggedIn) {
    $objIcon->setIcon('moderate');
    $objIcon->title = $this->objLanguage->languageText('mod_discussion_moderatetopic', 'discussion');
    $objIcon->alt = $this->objLanguage->languageText('mod_discussion_moderatetopic', 'discussion');

    $moderateTopicLink = new link($this->uri(array('action'=>'moderatetopic', 'id'=>$post['topic_id'], 'type'=>$discussiontype)));
    $moderateTopicLink->link = $objIcon->show();

    $header->str .= ' '.$moderateTopicLink->show();
}

echo $header->show();

//Confirmation messages
if ($this->getParam('message') == 'save') {
    $timeoutMessage = $this->getObject('timeoutmessage', 'htmlelements');
    $timeoutMessage->setMessage($this->objLanguage->languageText('mod_discussion_postsaved', 'discussion'));
    $timeoutMessage->setTimeout(20000);
    echo ('<p>'.$timeoutMessage->show().'</p>');
}
if ($this->getParam('message') == 'postupdated') {
    $timeoutMessage = $this->getObject('timeoutmessage', 'htmlelements');
    $timeoutMessage->setMessage($this->objLanguage->languageText('mod_discussion_postupdated', 'discussion'));
    $timeoutMessage->setTimeout(10000);
    echo ('<p>'.$timeoutMessage->show().'</p>');
}
if ($this->getParam('message') == 'replysaved') {
    $timeoutMessage = $this->getObject('timeoutmessage', 'htmlelements');
    $timeoutMessage->setMessage($this->objLanguage->languageText('mod_discussion_replysaved', 'discussion'));
    $timeoutMessage->setTimeout(10000);
    echo ('<p>'.$timeoutMessage->show().'</p>');
}

// Error Messages
if ($this->getParam('message') == 'cantreplydiscussionlocked') {
    $this->setErrorMessage('This Discussion has been Locked. You cannot post a reply to this Topic'); // LTE
}
if ($this->getParam('message') == 'cantreplytopiclocked') {
    $this->setErrorMessage('This Topic has been Locked. You cannot post a reply to this Topic'); // LTE
}

echo $changeDisplayForm;

if ($post['status'] =='CLOSE') {
    echo '<div class="discussionTangentIndent">';
    echo '<strong>'.$this->objLanguage->languageText('mod_discussion_topiclockedby', 'discussion').' '.$this->objUser->fullname($post['lockuser']).' on '.$this->objDateTime->formatdate($post['lockdate']).'</strong>';
    echo '<p>'.$post['lockreason'].'</p>';
    echo '</div>';
}

// Show Mind Map

$objFreeMindMap = $this->getObject('flashfreemind', 'freemind');
$objFreeMindMap->setMindMap($map.'&ext=.mm');
$objFreeMindMap->mainNodeShape = 'rectangle';
$objFreeMindMap->defaultWordWrap = 300;


echo $objFreeMindMap->show();

//

$moderateTopicLink = new link($this->uri(array('action'=>'moderatetopic', 'id'=>$post['topic_id'], 'type'=>$discussiontype)));
$moderateTopicLink->link = $this->objLanguage->languageText('mod_discussion_moderatetopic', 'discussion');

$newtopiclink = new link($this->uri(array('action'=>'newtopic', 'id'=>$post['discussion_id'], 'type'=>$discussiontype)));
$newtopiclink->link = $this->objLanguage->languageText('mod_discussion_startnewtopic', 'discussion');

$returntodiscussion = new link($this->uri(array('action'=>'discussion', 'id'=>$post['discussion_id'], 'type'=>$discussiontype)));
$returntodiscussion->link = $this->objLanguage->languageText('mod_discussion_returntodiscussion', 'discussion');

echo '<p align="center">';






// if ($post['status'] != 'CLOSE' && !$discussionlocked) {
    // echo $replylink->show().' / ';
// }

if ((!$discussionlocked && $this->objUser->isCourseAdmin()) || $discussiontype == 'workgroup') {
    echo $newtopiclink->show().' / ';
}

if ($this->objUser->isCourseAdmin() && !$discussionlocked && $discussiontype != 'workgroup' && $this->isLoggedIn) {
    echo $moderateTopicLink->show().' / ';
}

echo $returntodiscussion->show().'</p>';


$display = ob_get_contents();
ob_end_clean();

$this->setVar('middleColumn', $display);
?>