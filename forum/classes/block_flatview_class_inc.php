<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of bllock_flatview_class_inc
 *
 * @author monwabisi
 */
class block_flatview extends object {

    var $objUser;
    var $objLanguage;
    var $objPost;
    var $objForum;
    var $js;
    var $icon;
    var $contextObject;
    var $contextCode;
    var $objTopic;
    var $objPostRatings;
    var $objForumRatings;

    //put your code here
    function init() {
        $this->loadClass('htmlheading', 'htmlelements');
        $this->loadClass('link', 'htmlelements');
        $this->loadClass('form', 'htmlelements');
        $this->loadClass('dropdown', 'htmlelements');
        $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('button', 'htmlelements');
        // Get Context Code Settings
        $this->contextObject = & $this->getObject('dbcontext', 'context');
        $this->contextCode = $this->contextObject->getContextCode();
        $this->objLanguage = $this->getObject('language', 'language');
        $this->title = $this->objLanguage->languageText('mod_forum_replytotopic', 'forum');
        $this->objUser = $this->getObject('user', 'security');
        $this->objPost = $this->getObject('dbpost', 'forum');
        $this->objForum = $this->getObject('dbforum', 'forum');
        // Forum Ratings
        $this->objForumRatings = & $this->getObject('dbforum_ratings');
        $this->objPostRatings = & $this->getObject('dbpost_ratings');
        $this->objIcon = $this->newObject('geticon', 'htmlelements');
        $this->objTopic = $this->getObject('dbtopic', 'forum');
        $this->objDateTime = & $this->getObject('dateandtime', 'utilities');
        $this->js = '
<script type="text/javascript">
    //<![CDATA[

    function SubmitForm()
    {
        document.forms["postReplyForm"].submit();
    }

    //]]>
</script>
';
    }

    function biuldform() {
        // Get details on the topic
        $topic_id = $this->getParam('id');
        $post = $this->objPost->getRootPost($topic_id);
        $forumlocked = FALSE;
        $forumtype = $this->getParam('type');
        // Get details of the Forum
        $forum = $this->objForum->getForum($post['forum_id']);
        $header = new htmlheading();
        $header->type = 1;
        $link = new link($this->uri(array('action' => 'forum', 'id' => $post['forum_id'], 'type' => $forumtype)));
        $link->link = $post['forum_name'];
        //the heading
        $headerString = $link->show() . ' &gt; ' . stripslashes($post['post_title']);
        $header->str = $headerString;
        $hardHTML = '';
        // Check if forum is locked - if true - disable / editing replies
        if ($this->objForum->checkIfForumLocked($post['forum_id'])) {
            $this->objPost->repliesAllowed = FALSE;
            $this->objPost->editingPostsAllowed = FALSE;
            $this->objPost->forumLocked = TRUE;
            $forumlocked = TRUE;
        } else {
            if ($this->objUser->isCourseAdmin($this->contextCode)) {
                $this->objPost->showModeration = TRUE;
            }
        }
        if ($this->objUser->isCourseAdmin($this->contextCode) && !$forumlocked && $forumtype != 'workgroup' && $this->objUser->isLoggedIn()) {
            $this->objIcon->setIcon('moderate');
            $this->objIcon->title = $this->objLanguage->languageText('mod_forum_moderatetopic', 'forum');
            $this->objIcon->alt = $this->objLanguage->languageText('mod_forum_moderatetopic', 'forum');

            $moderateTopicLink = new link($this->uri(array('action' => 'moderatetopic', 'id' => $post['topic_id'], 'type' => $forumtype)));
            $moderateTopicLink->link = $this->objIcon->show();
        }
        ////Confirmation messages
        if ($this->getParam('message') == 'save') {
            $timeoutMessage = $this->getObject('timeoutmessage', 'htmlelements');
            $timeoutMessage->setMessage($this->objLanguage->languageText('mod_forum_postsaved', 'forum'));
            $timeoutMessage->setTimeout(20000);
            echo ('<p>' . $timeoutMessage->show() . '</p>');
        }
        if ($this->getParam('message') == 'postupdated') {
            $timeoutMessage = $this->getObject('timeoutmessage', 'htmlelements');
            $timeoutMessage->setMessage($this->objLanguage->languageText('mod_forum_postupdated', 'forum'));
            $timeoutMessage->setTimeout(10000);
            echo ('<p>' . $timeoutMessage->show() . '</p>');
        }
        if ($this->getParam('message') == 'replysaved') {
            $timeoutMessage = $this->getObject('timeoutmessage', 'htmlelements');
            $timeoutMessage->setMessage($this->objLanguage->languageText('mod_forum_replysaved', 'forum'));
            $timeoutMessage->setTimeout(10000);
            echo ('<p>' . $timeoutMessage->show() . '</p>');
        }

// Error Messages
        if ($this->getParam('message') == 'cantreplyforumlocked') {
            $this->setErrorMessage('This Forum has been Locked. You cannot post a reply to this Topic'); // LTE
        }
        if ($this->getParam('message') == 'cantreplytopiclocked') {
            $this->setErrorMessage('This Topic has been Locked. You cannot post a reply to this Topic'); // LTE
        }

        if ($post['status'] == 'CLOSE') {
            $hardHTML = '<div class="forumTangentIndent">';
            $hardHTML.= '<strong>' . $this->objLanguage->languageText('mod_forum_topiclockedby', 'forum') . ' ' . $this->objUser->fullname($post['lockuser']) . ' on ' . $this->objDateTime->formatdate($post['lockdate']) . '</strong>';
            $hardHTML .= '<p>' . $post['lockreason'] . '</p>';
            $hardHTML .= '</div>';
        }
        //ratings form
        $ratingsForm = new form('savepostratings', $this->uri(array('action' => 'savepostratings')));
        // Create the indented thread
        $thread = $this->objPost->displayFlatThread($topic_id);

        $ratingsForm->addToForm($thread);
        //hidden input
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
        //show ratings variable, set to false by default
        $showRatingsForm = FALSE;
        // Check if ratings allowed in Forum
        if ($forum['ratingsenabled'] == 'Y') {
            $this->objPost->forumRatingsArray = $this->objForumRatings->getForumRatings($post['forum_id']);
            $this->objPost->showRatings = TRUE;
            $showRatingsForm = TRUE;
        } else {
            $this->objPost->showRatings = FALSE;
        }
        // Determine whether to show the submit form for the ratings form
// Without this button, form is a waste, but need to make efficient
        if ($showRatingsForm) {
            $objButton = new button('submitForm');
            $objButton->cssClass = 'save';
            $objButton->setValue($this->objLanguage->languageText('mod_forum_sendratings', 'forum'));
            $objButton->setToSubmit();

            if ($post['status'] != 'CLOSE' && !$forumlocked) {
                $ratingsForm->addToForm('<p align="right">' . $objButton->show() . '</p>');
            }
        }

        $details = $this->getSession($this->getParam('tempid'));
        $temporaryId = $details['temporaryId'];
        $hiddenTemporaryId = new textinput('temporaryId');
        $hiddenTemporaryId->fldType = 'hidden';
        if (!isset($temporaryId)) {
            $temporaryId = "";
        }

        if ($post['topic_tangent_parent'] == '0') {
            $tangentsTable = $this->objTopic->showTangentsTable($post['topic_id']);
        }

        if (isset($tangentsTable)) {
            echo $tangentsTable;
        }
        if ($this->objUser->isCourseAdmin($this->contextCode) && !$forumlocked && $forumtype != 'workgroup' && $this->objUser->isLoggedIn()) {
            $elements = $moderateTopicLink->show() . '&nbsp;&nbsp;Moderate topic';
        }

//        $elements .= $this->objTopic->showChangeDisplayTypeForm($topic_id, 'flatview');
        $elements .= $hardHTML . $ratingsForm->show();
        return $elements;
    }

    function show() {
        return $this->biuldform();
    }

}

?>
