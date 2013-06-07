<?php

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
        die("You cannot view this page directly");
}

//require_once('attachmentreader_class_inc.php');

define('EMAIL_HOST', 'imap.gmail.com');
define('EMAIL_POST', '993');

// See: http://www.php.net/manual/en/function.imap-open.php -> Optional flags for names
define('EMAIL_OPTIONS', 'imap/ssl');


define('EMAIL_LOGIN', 'forum@thumbzup.com');
define('EMAIL_PASSWORD', '4RuMfOr7hUm8zUp');

//This will be the [domain] part of the @ in [catchall]@[domain]
// forum_topic_2@chisimba.tohir.co.za
define('CATCH_ALL_BASE', 'chisimba.tohir.co.za');

/**
 * Forum Email Class
 *
 * This class controls all functionality for sending emails from forum posts
 *
 * NB! At the moment, it only works for context forums, not lobby
 *
 * @author Tohir Solomons
 * @copyright (c) 2004 University of the Western Cape
 * @package forum
 * @version 1
 */
class forumemail extends object {

        /**
         * @var Object $eMailReciever object to get emails from the server
         */
        var $eMailReciever;

        /**
         * @var Array $emailList List of Email Addresses
         */
        var $emailList;

        /**
         * @var string $contextCode Context Code
         */
        var $contextCode;
        var $emailBox;
        var $objUser;
        var $objPostText;
        var $dbForum;
        var $objUserContext;

        /**
         * Constructor
         */
        function init() {
                $this->dbPost = $this->getObject('dbpost', 'forum');
                $this->dbTopic = $this->getObject('dbtopic', 'forum');
                $this->objUser = $this->getObject('user', 'security');
                $this->objPostText = & $this->getObject('dbposttext');
                $this->dbForum = & $this->getObject('dbforum');
                $this->objUserContext = $this->getObject('usercontext','context');
                $this->contextGroups = & $this->getObject('managegroups', 'contextgroups');

                /*
                 * TESTING
                 */

//                $this->loadClass('attachmentreader', 'mail');
//                $this->emailBox = new AttachmentReader(EMAIL_HOST, EMAIL_POST, EMAIL_OPTIONS, EMAIL_LOGIN, EMAIL_PASSWORD, CATCH_ALL_BASE);
////
//                $numMessages = $this->emailBox->getNumMessages();
//
//                if ($numMessages > 0) {
//                        for ($emailNum = 1; $emailNum <= $numMessages; $emailNum++) {
//
//                                // Retrieve Basic Details of Email From the Headers
//                                $emailDetails = $this->emailBox->getEmailDetails($emailNum);
//
//                                $split = explode('-', $emailDetails['subject']);
//                                $topic_id = 'gen' . $split[1];
//                                $topicDetails = $this->dbTopic->getTopicDetails($topic_id);
//                                $postDetails = $this->dbPost->getPostWithText($topicDetails['first_post']);
//                                if ($topicDetails == TRUE) {
//                                        $post_parent = $topicDetails['first_post'];
//                                        $forum_id = $topicDetails['forum_id'];
//                                        //get the forum object
//                                        $objForum = $this->getObject('dbforum','forum');
//                                        $forumDetails = $objForum->getForum($forum_id);
//                                        $post_title = $topicDetails['post_title'];
//                                        //
//                                        $partialMessage = explode('>', $emailDetails['messageBody']);
//                                        print_r($partialMessage);
//                                        $post_text = $emailDetails['messageBody'];
//                                        $language = $postDetails['language'];
//                                        $userDetails = $this->objUser->getRow('emailaddress', $emailDetails['sender']);
//                                        //check if the user is a member of the site
//                                        if ($userDetails) {
//                                                $userID = $userDetails['userid'];
//                                                $level = $postDetails['level'];
//                                                echo "<pre>";
////                                                print_r($emailDetails);
//                                                echo "</pre>";
////                                                if ($this->objUserContext->isContextMember($this->objUser->userId(), $forumDetails['contextcode'])) {
////                                                        $post_id = $this->dbPost->insertSingle($post_parent, 0, $forum_id, $topic_id, $userID, $level);
////                                                        $this->objPostText->insertSingle($post_id, $post_title, $post_text, $language, $post_parent, $userID);
////                                                        $this->dbTopic->updateLastPost($topic_id, $post_id);
////                                                        $this->dbForum->updateLastPost($forum_id, $post_id);
////                                                        $this->emailBox->deleteEmail($emailNum);
////                                                }
//                                        }
//                                } else {
//                                        echo "<h1>The topic does not exists</h1>";
//                                }
//
//                                // Mark Email for deletion
////                                $this->emailBox->deleteEmail($emailNum);
//                        }
//                        // Expunge Deleted Mail
//                        unset($this->emailBox);
//                } else {
//                        echo "<h1>Inbox is empty</h1>";
//                }
                /*
                 * END
                 */
//                $this->objUser = & $this->getObject('user', 'security');
                $this->objLanguage = & $this->getObject('language', 'language');
//                $this->objTopic = $this->getObject('dbtopic', 'forum');
        }

        /**
         * Method to set the Context Code to use
         * @param String $context Context Code
         */
        function setContextCode($context) {
                $this->contextCode = $context;
                $this->objMailer = &$this->getObject('mailer', 'mail');
        }

        /**
         * Method to prepare the list of email addresses to use
         * At the moment, it takes the list of all users in a context.
         */
        function prepareListEmail($topic_id) {

                // Create an empty array for the email addresses
                $this->emailList = array();

                // Get Users Subscribed to Topic
                $objTopicSubscribers = & $this->getObject('dbtopicsubscriptions');
                $topicSubscribers = $objTopicSubscribers->getUsersSubscribedTopic($topic_id);

                // Add the Email to the array
                foreach ($topicSubscribers as $user) {
                        array_push($this->emailList, $user['emailaddress']);
                }

                $objTopic = & $this->getObject('dbtopic');
                $topicDetails = $objTopic->listSingle($topic_id);

                // Get Users Subscribed to Forum
                $objForumSubscribers = & $this->getObject('dbforumsubscriptions');
                $forumSubscribers = $objForumSubscribers->getUsersSubscribedForum($topicDetails['forum_id']);

                // Add the Email to the array
                foreach ($forumSubscribers as $user) {
                        array_push($this->emailList, $user['emailaddress']);
                }

                // Remove duplicate emails
                $this->emailList = array_unique($this->emailList);
        }

        /**
         * Method to send an email to the users
         * @param String $topic_id Record Id of the Topic
         * @param String $title Title of the Post
         * @param String $text Text of the Post
         * @param String $forum Name of the Forum
         * @param String $senderId Record Id of the Sender
         * @param String $replyUrl Url for the User to Reply to
         */
        function sendEmail($topic_id, $title, $text, $forum, $senderId, $replyUrl) {
                $this->prepareListEmail($topic_id);
//                $this->emailList = array(
//                    0 => 'wsifumba@gmail.com'
//                );
                // Only bother to send emails if there are more than one user.
                if (count($this->emailList) > 0) {
                        $this->loadClass('link', 'htmlelements');

                        //set eMail subject to topic ID/ without (gen)
                        $subject = '[' . $forum . ']-' . str_replace('gen', '', $topic_id);
                        $name = 'Not Needed';

                        $line1 = $this->objLanguage->languageText('mod_forum_emailtextline1', 'forum', '{NAME} has posted the following message to the {FORUM} discussion forum') . ':';
                        $line1 = str_replace('{NAME}', $this->objUser->fullname($senderId), $line1);
                        $line1 = str_replace('{FORUM}', $forum, $line1);

                        //Convert '&' to '&amp;'
                        $replyUrl = str_replace('&', '&amp;', $replyUrl);

                        // Create a link
                        $replyLink = new link($replyUrl);
                        $replyLink->link = $replyUrl;

                        $line2 = $this->objLanguage->languageText('mod_forum_emailtextline2', 'forum', 'To reply to this message, go to: {URL}');
                        $line2 = str_replace('{URL}', $replyLink->show(), $line2);

                        $message = '------------------------------------------------<br />' . "\r\n";
                        $message .= $title . "<br />\r\n";
                        $message .= ucfirst($this->objLanguage->languageText('word_by', 'forum', 'By')) . ' ' . $this->objUser->fullname($senderId) . "<br />\r\n";
                        $message .= '------------------------------------------------<br />' . "\r\n";
                        //$message .= '<p>'.$line1.'</p>'."\r\n\r\n";
                        $message .= str_replace('&nbsp;', ' ', $text) . "\r\n\r\n";
                        $message .= '<hr />' . "\r\n\r\n";
                        $message .= '<p>' . $line2 . '</p>' . "\r\n\r\n";

                        $body = '<html><head></head><body>' . $message . '</body></html>';

                        $from = $topic_id . '@' . CATCH_ALL_BASE;
                        $fromName = $this->objUser->fullname($senderId);

                        // Setup Alternate Message - Convert '&amp;' back to '&'
                        $altMessage = str_replace('&amp;', '&', $message);

                        // Add alternative message - same version minus html tags
                        $messagePlain = strip_tags($altMessage);
                        $this->objMailer->setValue('to', $from);
                        $this->objMailer->setValue('bcc', $this->emailList);
                        $this->objMailer->setValue('from', $from);
                        $this->objMailer->setValue('fromName', $fromName);
                        $this->objMailer->setValue('subject', $subject);
                        $this->objMailer->setValue('useHTMLMail', TRUE);
                        $this->objMailer->setValue('body', $messagePlain);
                        $this->objMailer->setValue('htmlbody', $message);
                        return $this->objMailer->send();
                } else {
                        return NULL;
                }
        }

}

?>