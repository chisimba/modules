<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of block_forumview_class_inc
 *
 * @author monwabisi
 */
class block_forumview extends object {

        var $objGroups;
        var $objTranslatedDate;
        var $objLanguage;
        var $objForum;
        var $objTopic;
        var $objUser;
        var $domDoc;
        var $showFullName;

        //put your code here
        public function init() {
                $this->loadClass('link', 'htmlelements');
                $this->loadClass('multitabbedbox', 'htmlelements');
                $this->loadClass('form', 'htmlelements');
                $this->loadClass('label', 'htmlelements');
                $this->loadClass('dropdown', 'htmlelements');
                $this->loadClass('button', 'htmlelements');
                $this->loadClass('textinput', 'htmlelements');
                $this->loadClass('htmlheading', 'htmlelements');
                $this->loadClass('dbtopic', 'forum');
                $this->domDoc = new DOMDocument('utf-8');
                $this->title = "Forum view";
                $this->objLanguage = $this->getObject('language', 'language');
                $this->objTranslatedDate = $this->getObject('translatedatedifference', 'utilities');
//                $this->objGroups = $this->getObject('groupadmin_model', 'groupadmin');
                $this->objForum = $this->getObject('dbforum', 'forum');
                $this->objTopic = $this->getObject('dbtopic', 'forum');
                $this->objUser = $this->getObject('user', 'security');
                $this->objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
                $this->showFullName = $this->objSysConfig->getValue('SHOWFULLNAME', 'forum');
        }

        public function buildForumView() {
                $forumid = $this->getParam('id');
                //get the forum ID
                $forum = $this->objForum->getForum($forumid);
                $newTopicIcon = $this->getObject('geticon', 'htmlelements');
                $newTopicIcon->setIcon('notes');
                $newTopicIcon->alt = $this->objLanguage->languageText('mod_forum_startnewtopic', 'forum');
                $newTopicIcon->title = $this->objLanguage->languageText('mod_forum_startnewtopic', 'forum');
//        $forumtype = & $this->getVar('forumtype');
                $tblTopic = $this->newObject('htmltable', 'htmlelements');
                $objTranslatedDate = $this->getObject('translatedatedifference', 'utilities');
                // Link to start new topic
                $newTopicLink = new link($this->uri(array('action' => 'newtopic', 'id' => $forumid, 'type' => $forum['forum_type'])));
                $newTopicLink->link = $newTopicIcon->show();

// Start of First Row

                $tblTopic->startHeaderRow();


                $tblTopic->addHeaderCell($this->objForum->forumSortLink($forumid, 'status', $this->objLanguage->languageText('word_status', 'forum', 'Status')), '30', 'center');

                // --------------


                $tblTopic->addHeaderCell($this->objForum->forumSortLink($forumid, 'read', $this->objLanguage->languageText('word_noun_read', 'forum')), '30', 'center');

                // --------------


                $tblTopic->addHeaderCell($this->objForum->forumSortLink($forumid, 'type', $this->objLanguage->languageText('word_type', 'forum', 'Type')), '30', 'center');

                // --------------


                $tblTopic->addHeaderCell($this->objForum->forumSortLink($forumid, 'topic', $this->objLanguage->languageText('mod_forum_topicconversation', 'forum')), '30%', 'center');

                // --------------


                $tblTopic->addHeaderCell($this->objForum->forumSortLink($forumid, 'author', $this->objLanguage->languageText('word_author')), Null, 'center', 'center');

                // --------------

                $tblTopic->addHeaderCell($this->objForum->forumSortLink($forumid, 'replies', $this->objLanguage->languageText('word_replies', 'system', 'Replies')), Null, 'center', 'center');

                // --------------

                $tblTopic->addHeaderCell($this->objForum->forumSortLink($forumid, 'views', $this->objLanguage->languageText('word_views', 'system', 'Views')), Null, 'center', 'center');

                // --------------

                $tblTopic->addHeaderCell($this->objForum->forumSortLink($forumid, 'lastpost', $this->objLanguage->languageText('mod_forum_lastpost', 'forum')), Null, 'center', 'center');

                $tblTopic->endHeaderRow();

                $header = new htmlheading();
                $header->type = 1;
                $header->str = $forum['forum_name'];
                //admimn functions table
                $tblAdmin = new htmlTable();
                $tblAdmin->startRow();
//                $tblTopic->startRow();
//// Start checking whether to show the link
                if ($forum['forumlocked'] != 'Y') {
                        // Check if students can start topic
                        if ($forum['studentstarttopic'] == 'Y') {
//                                $header->str .= ' ' . $newTopicLink->show();
                                $tblAdmin->addCell($newTopicLink->show(), NULL, NULL, 'center');
                                // Else check if user is lecturer or admin
                        } else if ($this->objUser->isCourseAdmin($this->contextCode)) {
//                                $header->str .= ' ' . $newTopicLink->show();
                                $tblAdmin->addCell($newTopicLink->show());
                        }
                }
                $tblTopic->endRow();
                /**
                 * @todo Return object containing the start new topic icon/string
                 */
                //forum search object
                $objForumSearch = $this->getObject('forumsearch');
                $objForumSearch->defaultForum = $forumid;
                /**
                 * @todo Return the forum search object
                 */
                // Get Order and Sorting Values
                $order = $this->getSession('sortorder', $this->getSession('sortorder', 'date'));
                $this->objTopic = $this->getObject('dbtopic', 'forum');
                $direction = $this->getParam('direction', $this->getSession('sortdirection', 'asc'));
                $page = $this->getParam('page', 1);
                $limitPerPage = 30;        // Prevent Users from adding alphabetical items to page
                if (!is_numeric($page)) {
                        $page = 1;
                }        // Prevent URL by hacking
                // If page limit is too high, set to 1
                if ($page > $this->objTopic->getNumForumPages($forumid, $limitPerPage, FALSE)) {
                        $page = 1;
                }
                $limit = ' LIMIT ' . ($page - 1) * $limitPerPage . ', ' . $limitPerPage;
                $paging = $this->objTopic->prepareTopicPagingLinks($forumid, $page, $limitPerPage);
                $allTopics = $this->objTopic->showTopicsInForum($forumid, $this->objUser->userId($this->objUser->userName()), $forum['archivedate'], $order, $direction, NULL, NULL);
//        echo $order;
                $topicsNum = count($allTopics);
                if ($topicsNum > 0) {
                        /**
                         * @todo Append the number of topics to object
                         */
//                        echo $paging;
                }
                $tblTopic->attributes = ' align="center" border="0"';
                $tblTopic->cellspacing = '1';
                $tblTopic->cellpadding = '4';
                $tblTopic->border = '0';
                $tblTopic->width = '99%';

                if ($topicsNum > 0) {
                        $tblTopic->css_class = 'forumtopics';
                }

                $wrapperDiv = "";
                if ($topicsNum > 0) {
                        foreach ($allTopics as $topic) {
                                $altRowCSS = NULL;

                                //
                                $divClass = "";
                                $divID = "";
                                $divContent = "";
                                $divHeader = "";
                                $divStatus = "";

                                $objIcon = $this->getObject('geticon', 'htmlelements');

                                if ($topic['topicstatus'] == 'OPEN') {
                                        $objIcon->setIcon('unlock', NULL, 'icons/forum/');
                                        $objIcon->title = $this->objLanguage->languageText('mod_forum_topicisopen', 'forum');
                                        $rowCSS = $altRowCSS;
                                        //
//                                        $divContent = "locked";
                                } else {
                                        $objIcon->setIcon('lock', NULL, 'icons/forum/');
                                        $objIcon->title = $this->objLanguage->languageText('mod_forum_topicislocked', 'forum');
                                        $rowCSS = 'closedTopic';
                                        //
                                        $divClass = "unlocked";
                                }

                                if ($topic['sticky'] == '1') {
                                        $rowCSS = 'stickyTopic';
                                        //
//                                        $divContent .= "<br/>Sticky";
                                }

                                $tblTopic->startRow($rowCSS);

                                if ($this->showFullName) {
                                        $tblTopic->addCell($this->objUser->getUserImage($topic['userid']) . "<br/>" . $topic['firstname'] . ' ' . $topic['surname'], Null, 'center', 'center');
                                        //
//                                        $divContent .= '<br/>' . $this->objUser->getUserImage($topic['userid']) . '<br/>' . $topic['firstname'] . ' ' . $topic['surname'];
                                } else {
                                        $tblTopic->addCell($topic['username'], Null, 'center', 'center');
                                        //
//                                        $divContent .= '<br/>' . $topic['username'];
                                }

                                $tblTopic->addCell($objIcon->show(), Null, 'center');

//                if ($topic['readtopic'] == '') {
//                    $objIcon->setIcon('unreadletter');
//                    $objIcon->title = $this->objLanguage->languageText('mod_forum_newunreadtopic', 'forum');
//                    //
//                    $divContent .= "unread";
//                } else if ($topic['lastreadpost'] == $topic['last_post']) {
//                    $objIcon->setIcon('readletter');
//                    $objIcon->title = $this->objLanguage->languageText('mod_forum_readtopic', 'forum');
//                    //
//                    $divContent .= "<br/>read";
//                } else {
//                    $objIcon->setIcon('readnewposts');
//                    $objIcon->title = $this->objLanguage->languageText('mod_forum_hasnewposts', 'forum');
//                    //
//                    $divContent .= "<br/>readnewpost";
//                }
//                $tblTopic->addCell($objIcon->show(), Null, 'center');

                                $objIcon->setIcon($topic['type_icon'], NULL, 'icons/forum/');
                                $objIcon->title = $topic['type_name'];
                                //
//                $divContent .= '<br/>'.$topic['type_icon'];

                                $tblTopic->addCell($objIcon->show(), Null, 'center');

                                $link = new link($this->uri(array('action' => 'viewtopic', 'id' => $topic['topic_id'], 'type' => $forum['forum_type'])));

                                $link->link = "<span class='forumname' >" . stripslashes($topic['post_title']) . "</span>";

                                if ($topic['sticky'] == '1') {
                                        //
                                        $objIcon->setIcon('sticky_yes');
//                    $divContent .= '<br/>'.$objIcon->show();
                                        $objIcon->title = $this->objLanguage->languageText('mod_forum_stickytopic', 'forum', 'Sticky Topic');
                                        $sticky = $objIcon->show() . ' ';
                                        $tblTopic->addCell($link->show(), '30%', 'center', NULL, NULL, 'class=sticky', NULL);
                                } else {
                                        $tblTopic->addCell($link->show(), '30%', 'center', NULL, NULL, NULL, NULL);
                                        $sticky = '';
                                }


                                //
//                                $divContent .= '<br/>' . $topic['replies'];
                                $tblTopic->addCell("<span class='numberindicator' >{$topic['replies']}</span><br/><label class='menu' >Replies</label>", Null, 'center', 'center');

                                //
//                                $divContent .= '<br/>' . $topic['views'];
                                $tblTopic->addCell("<span class='numberindicator' >{$topic['views']}</span><br/><label class='menu'>Views</label>", Null, 'center', 'center');

                                // if (formatDate($topic['lastdate']) == date('j F Y')) {
                                // $datefield = 'Today at '.formatTime($topic['lastdate']);
                                // } else {
                                // $datefield = formatDate($topic['lastdate']).' - '.formatTime($topic['lastdate']);
                                // }
                                //
//                $divContent .= '<br/>' . $topic['lastdate'];
                                $datefield = $objTranslatedDate->getDifference($topic['lastdate']);

                                $objIcon->setIcon('gotopost', NULL, 'icons/forum/');
                                $objIcon->title = $this->objLanguage->languageText('mod_forum_gotopost', 'forum');

                                $lastPostLink = new link($this->uri(array('action' => 'viewtopic', 'id' => $topic['topic_id'], 'post' => $topic['last_post'], 'type' => $forum['forum_type'])));
                                $lastPostLink->link = $objIcon->show();

                                if ($this->showFullName) {
                                        //
                                        $divContent .= '<br/>' . $datefield . '<br />' . $topic['lastfirstname'] . ' ' . $topic['lastsurname'] . $lastPostLink->show() . '<br/><br/>';
                                        $tblTopic->addCell($datefield . '<br />' . $topic['lastfirstname'] . ' ' . $topic['lastsurname'] . $lastPostLink->show(), Null, 'center', 'right', 'smallText');
                                } else {
                                        //
                                        $divContent .= '<br/>' . $datefield . '<br />' . $topic['lastfirstname'] . ' ' . $topic['lastsurname'] . $lastPostLink->show();
                                        $tblTopic->addCell($datefield . '<br />' . $topic['lastusername'] . $lastPostLink->show(), Null, 'center', 'right', 'smallText');
                                }

                                $objIcon->align = 'absmiddle';

                                $tblTopic->endRow();

                                if ($topic['tangentcheck'] != '') {
                                        $tangents = $this->objTopic->getTangents($topic['topic_id']);
                                        foreach ($tangents as $tangent) {
                                                $tblTopic->startRow();
                                                $tblTopic->addCell('&nbsp;', Null, 'center');
                                                $tblTopic->addCell('&nbsp;', Null, 'center');
                                                $tblTopic->addCell('&nbsp;', Null, 'center');

                                                $link = new link($this->uri(array('action' => 'viewtopic', 'id' => $tangent['id'], 'type' => $forum['forum_type'])));
                                                $link->link = $tangent['post_title'];

                                                $objIcon->setIcon('tangent', NULL, 'icons/forum/');
                                                $objIcon->title = $this->objLanguage->languageText('word_tangent');

                                                //
                                                $divContent .= '<br/>' . $objIcon->sho() . ' ' . $link->show();
                                                $tblTopic->addCell($objIcon->show() . ' ' . $link->show(), Null, 'center');

                                                if ($this->showFullName) {
                                                        //
                                                        $divContent .= '<br/>' . $tangent['firstname'] . ' ' . $tangent['surname'];
                                                        $tblTopic->addCell($tangent['firstname'] . ' ' . $tangent['surname'], Null, 'center', 'center');
                                                } else {
                                                        $tblTopic->addCell($tangent['username'], Null, 'center', 'center');
                                                        //
                                                        $divContent .= '<br/>' . $tangent['username'];
                                                }
                                                //
//                        $divContent .= '<br/>'.$tangent['replies'].'<br/>';
//                        $divContent .= '<br/>'.$tangent['views'].'<br/>';

                                                $tblTopic->addCell($tangent['replies'], Null, 'center', 'center');
                                                $tblTopic->addCell($tangent['views'], Null, 'center', 'center');

                                                // if (formatDate($tangent['lastdate']) == date('j F Y')) {
                                                // $datefield = $this->objLanguage->languageText('mod_forum_todayat').' '.formatTime($tangent['lastdate']);
                                                // } else {
                                                // $datefield = formatDate($tangent['lastdate']).' - '.formatTime($tangent['lastdate']);
                                                // }

                                                $datefield = $objTranslatedDate->getDifference($tangent['lastdate']);

                                                $objIcon->setIcon('gotopost', NULL, 'icons/forum/');
                                                $objIcon->title = $this->objLanguage->languageText('mod_forum_gotopost');

                                                //$tblTopic->addCell('<strong>'.$tangent['lastFirstName'].' '.$tangent['lastSurname'].'</strong> <br />'.$objIcon->show().$datefield, Null, 'center', 'center', 'smallText');

                                                $lastPostLink = new link($this->uri(array('action' => 'viewtopic', 'id' => $tangent['id'], 'post' => $tangent['last_post'], 'type' => $forum['forum_type'])));
                                                $lastPostLink->link = $objIcon->show();

                                                $objIcon->setIcon('gotopost', NULL, 'icons/forum/');

                                                if ($this->showFullName) {
                                                        //
//                            $divContent .= '<br/>'.$datefield . '<br />' . $tangent['lastfirstname'] . ' ' . $tangent['lastsurname'] . $lastPostLink->show();
                                                        $tblTopic->addCell($datefield . '<br />' . $tangent['lastfirstname'] . ' ' . $tangent['lastsurname'] . $lastPostLink->show(), Null, 'center', 'right', 'smallText');
                                                } else {
                                                        //
//                            $divContent .= $datefield . '<br />' . $tangent['lastusername'] . $lastPostLink->show();
                                                        $tblTopic->addCell($datefield . '<br />' . $tangent['lastusername'] . $lastPostLink->show(), Null, 'center', 'right', 'smallText');
                                                }

                                                $tblTopic->endRow();
                                        }
                                }
//                $wrapperDiv .= $divContent;
                        }
                } else {

                        $noposts = '<div class="noRecordsMessage">';
                        $noposts .= $this->objLanguage->languageText('mod_forum_nopostsinforum', 'forum') . '.<br /><br />' . $this->objLanguage->languageText('mod_forum_clicklinkstarttopic', 'forum') . '.';
                        $noposts .= '</div>';

                        $tblTopic->startRow();

                        $tblTopic->addCell($noposts, null, null, null, null, ' colspan="8"');
                        $tblTopic->endRow();
                }
                return $tblTopic->show();
        }

        public function show() {
                return $this->buildForumView();
        }

}

?>
