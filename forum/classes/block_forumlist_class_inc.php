<?php

/**
 * @package forum
 */
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
        die("You cannot view this page directly");
}

// end security check

class block_forumlist extends object {

        var $objLanguage;
        var $objPost;
        var $objUser;
        var $forumContext;
        var $trimStrObj;
        var $objSysConfig;
        var $objDateTime;
        var $objForum;
        var $objUserContext;
        var $contextObject;

        public function init() {
                $this->loadClass('link', 'htmlelements');
                $this->loadClass('form', 'htmlelements');
                $this->loadClass('dropdown', 'htmlelements');
                $this->loadClass('textinput', 'htmlelements');
                $this->loadClass('button', 'htmlelements');
                $this->loadClass('label', 'htmlelements');
                $this->loadClass('htmlheading', 'htmlelements');
                $this->loadClass('hiddeninput', 'htmlelements');
                $this->loadClass('user', 'security');
                $this->objUserContext = $this->getObject('usercontext', 'context');
                $this->objLanguage = $this->getObject('language', 'language');
                $this->title = str_replace('{Context}', "", $this->objLanguage->languageText('mod_forum_forumsincontext', 'forum'));
                $this->objPost = $this->getObject('dbpost', 'forum');
                $this->objUser = $this->getObject('user', 'security');
                $this->objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
                $this->objForum = $this->getObject('dbforum', 'forum');
                // Get Context Code Settings
                $this->contextObject = & $this->getObject('dbcontext', 'context');
                $this->contextCode = $this->contextObject->getContextCode();
                // Trim String Functions
                $this->trimstrObj = & $this->getObject('trimstr', 'strings');
                $this->objDateTime = & $this->getObject('dateandtime', 'utilities');
        }

        public function buildHome() {
                $tblclass = $this->newObject('htmltable', 'htmlelements');
                $tblclass->width = '';
                $tblclass->attributes = " align='center'";
                $tblclass->cellspacing = '0';
                $tblclass->cellpadding = '5';
                $tblclass->border = '0';
                $tblclass->width = '100%';
                $tblclass->startHeaderRow();
                $tblclass->addHeaderCell('&nbsp;', 10, 'center');
                $tblclass->addHeaderCell('<strong>' . $this->objLanguage->languageText('mod_forum', 'forum') . '</strong>', '40%');
                $tblclass->addHeaderCell('<strong><nobr>' . $this->objLanguage->languageText('word_topics') . '</nobr></strong>', 100, NULL, 'center');
                $tblclass->addHeaderCell('<strong><nobr>' . $this->objLanguage->languageText('word_posts') . '</nobr></strong>', 100, NULL, 'center');
                $tblclass->addHeaderCell('<strong><nobr>' . $this->objLanguage->languageText('mod_forum_lastpost', 'forum') . '</nobr></strong>', 100);
                $tblclass->endHeaderRow();
                $dropdown = new dropdown('forum');
                $dropdown->addOption('all', 'All Forums');
                $homeForm = $this->getObject('form', 'htmlelements');
                $rowcount = 0;
//user object to be used in determining if user is admin
//                $objUser = $this->getObject('user', 'security');
//                $objDB = &$this->getObject('dbforum', 'forum');
//        $forums = $objDB->getAll();
                $forums = $this->objForum->showAllForums($this->contextCode);

                foreach ($forums as $forum) {
                        if ($this->objUserContext->isContextMember($this->objUser->userId(), $forum['forum_context']) || $forum['forum_context'] == 'root') {
//                                
//                        }
                        if ($this->contextObject->isInContext() || $forum['forum_context'] == 'root') {
//                                echo $forum['forum_context'].'<br/>';
//                                echo "<br/>In context mode {$this->contextObject->getContextCode()}<br/>";
//                                if ($this->objUserContext->isContextMember($this->objUser->userId($this->objUser->email()), $this->contextObject->getContextCode()) || $forum['forum_context'] == 'root') {
//                                        echo "<br/>member of context<br/>";
//                                } else {
//                                        continue;
//                                }
//                        }
                        $oddOrEven = ($rowcount == 0) ? "odd" : "even";
                        $dropdown->addOption($forum['id'], $forum['forum_name']);
                        $forumLink = new link($this->uri(array('module' => 'forum', 'action' => 'forum', 'id' => $forum['forum_id'])));
                        $forumLink->link = $forum['forum_name'];
                        $forumName = $forumLink->show();
                        $this->contextCode = $forum['forum_context'];
                        if ($forum['defaultforum'] == 'Y') {
                                $forumName .= '<em> - ' . $this->objLanguage->languageText('mod_forum_defaultForum', 'forum', 'Default Forum') . '</em>';
                        }
                        $objIcon = $this->getObject('geticon', 'htmlelements');
                        if ($forum['forumlocked'] == 'Y') {
                                $objIcon->setIcon('lock', NULL, 'icons/forum/');
                                $objIcon->title = $this->objLanguage->languageText('mod_forum_forumislocked', 'forum');
                        } else {
                                $objIcon->setIcon('unlock', NULL, 'icons/forum/');
                                $objIcon->title = $this->objLanguage->languageText('mod_forum_forumisopen', 'forum');
                        }
                        $tblclass->startRow($oddOrEven);
                        $tblclass->addCell($objIcon->show(), 20, NULL, 'center');
                        $tblclass->addCell($forumName . '<br />' . $this->objLanguage->abstractText($forum['forum_description']), '40%', 'center');
                        $tblclass->addCell($forum['topics'] . '<br/>Topics', NULL, NULL, 'center');
                        $tblclass->addCell($forum['post'] . '<br/>Posts', 100, NULL, 'center');
                        $post = $this->objPost->getLastPost($forum['id']);
                        if ($post == FALSE) {
                                $postDetails = '<em>' . $this->objLanguage->languageText('mod_forum_nopostsyet', 'forum') . '</em>';
                                $cssClass = NULL;
                        } else {
                                $cssClass = 'smallText';
                                $postLink = new link($this->uri(array('module' => 'forum', 'action' => 'viewtopic', 'id' => $post['topic_id'], 'post' => $post['post_id'])));
                                $postLink->link = stripslashes($post['post_title']);
                                $postDetails = '<strong>' . $postLink->show() . '</strong>';
                                $postDetails .= '<br />' . $this->trimstrObj->strTrim(stripslashes(str_replace("\r\n", ' ', strip_tags($post['post_text']))), 80);

                                $this->showFullName = $this->objSysConfig->getValue('SHOWFULLNAME', 'forum');
                                if ($post['firstname'] != '') {
                                        if ($this->showFullName) {
                                                $user = 'By: ' . $post['firstname'] . ' ' . $post['surname'] . ' - ';
                                        } else {
                                                $user = 'By: ' . $post['username'] . ' - ';
                                        }
                                } else {
                                        $user = '';
                                }
                                if ($this->objDateTime->formatDateOnly($post['datelastupdated']) == date('j F Y')) {
                                        $datefield = $this->objLanguage->languageText('mod_forum_todayat', 'forum') . ' ' . $this->objDateTime->formatTime($post['datelastupdated']);
                                } else {
                                        $datefield = $this->objDateTime->formatDateOnly($post['datelastupdated']) . ' - ' . $this->objDateTime->formatTime($post['datelastupdated']);
                                }

                                $postDetails .= '<br /><strong>' . $user . $datefield . '</strong>';
                        }
                        $tblclass->addCell($postDetails, '40%', 'center', NULL, $cssClass);
                        $tblclass->endRow();
                        // Set rowcount for bitwise determination of odd or even
                        $rowcount = ($rowcount == 0) ? 1 : 0;
                        }
                }
        }
                $homeForm->addToForm($tblclass->show());
                $objSearch = $this->getObject('forumsearch');
//        $homeForm->addToForm($editLink->show());

                if ($this->objUser->isCourseAdmin($this->contextCode) && $this->objUser->isLoggedIn()) {
                        $administrationLink = new link($this->uri(array('module' => 'forum', 'action' => 'administration')));
                        $administrationLink->link = $this->objLanguage->languageText('mod_forum_forumadministration', 'forum');
                        $administrationLink->cssClass = "sexybutton";
                        $homeForm->addToForm('<br/>' . $administrationLink->show());
                }
                $homeForm->addToForm($objSearch->show());
                return '<div class="forum_main" >' . $homeForm->show() . '</div>';
        }

        public function show() {
                return $this->buildHome();
        }

}

?>
