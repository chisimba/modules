<?php
$this->loadClass('link', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->objLanguagecode = $this->getObject('languagecode', 'language');
$this->objTopic = & $this->getObject('dbtopic', 'forum');
$forum = $this->objDbGroups->getForum($this->getParam('id'));
$limit =  " limit 30";
$order = 'date';
$topics = $this->objTopic->showTopicsInForum($forum['id'], $this->objUser->userId(), $forum['archivedate'], $order, 'asc', NULL, $limit);
$topicsNum = count($topics);

?>


<div class="subNavigation"></div>
<div class="breadCrumb tenPixelLeftPadding">
    <a href="#" class="groupsBreadCrumbColor">Groups</a> |
    <span class="groupsBreadCrumbColor noUnderline"><?php
echo $this->objDbGroups->getGroupName($this->getParam('id')) . ", " . $this->objLanguagecode->getName($this->objDbGroups->getGroupCountry($this->getParam('id')));
?>   </span>

</div>
<div class="greenBackgroundColor">
    <div class="wideLeftFloatDiv">
        <!-- Left Colum -->
        <div class="groupsleftColumnDiv  tenPixelTopPadding">
            <div class="tenPixelPaddingLeft tenPixelPaddingRight">
                <img src="<?php echo $this->objDbGroups->getThumbnail($this->getParam('id')); ?>" alt="Adaptation placeholder" width="40" height="40" class="smallAdaptationImageGrid"><h5 class="greenText">
                    <?php
                    echo $this->objDbGroups->getGroupName($this->getParam('id')) . ", " . $this->objLanguagecode->getName($this->objDbGroups->getGroupCountry($this->getParam('id')));
                    ?></h5>
                <div class="groupSubLinks">

                    <div class="greyDivider"></div>
                    <div class="groupSubLinksList">
                        <img src="skins/unesco_oer/images/small-icon-rss-feed.png" alt="Feed" width="18" height="18" class="smallLisitngIcons">
                        <div class="linksTextNextToSubIcons"><a href="#" class="greyTextBoldLink">Subscribe to feed</a></div>
                    </div>
                    <div class="groupSubLinksList">
                        <img src="skins/unesco_oer/images/small-icon-bookmark.png" alt="Bookmark" width="18" height="18" class="smallLisitngIcons">
                        <div class="linksTextNextToSubIcons"><a href="#" class="greyTextBoldLink">Bookmark this</a></div>

                    </div>
                    <div class="greyDivider"></div>
                    <br>
                    <div class="groupSubLinksList">
                        <?php
                        $currLoggedInID = $this->objUser->userId();
                   //     $id = $this->objUseExtra->getUserbyUserIdbyUserID($currLoggedInID);
                        $groupid = $this->getParam('id');
//                           if ($this->hasMemberPermissions()){
                        echo $this->objGroupUtil->leaveGroup($currLoggedInID, $groupid);
                         //  }
                        ?>
<!--                           <img src="skins/unesco_oer/images/icon-group-leave-group.png" alt="Leaave Group" width="18" height="18" class="smallLisitngIcons">
                       <div class="linksTextNextToSubIcons"><a href="#" class="greenTextBoldLink">Leave group</a></div>-->
                    </div>
                    <div class="groupSubLinksList">

                        <img src="skins/unesco_oer/images/icon-group-discussion.png" alt="Group discussion" width="18" height="18" class="smallLisitngIcons">
                        <div class="linksTextNextToSubIcons"><a href="?module=forum&action=forum&id=<?php
                        $groupid = $this->getParam('id');
                        echo $this->objDbGroups->getGroup_pkid_in_forum($groupid);
                        ?>&page=10a_tpl.php   " class="greenTextBoldLink">Group discussion</a></div>
                    </div>
                    <div class="groupSubLinksList">
                        <img src="skins/unesco_oer/images/icon-group-subgroups.png" alt="Sub Groups" width="18" height="18" class="smallLisitngIcons">
                        <div class="linksTextNextToSubIcons"><a href="#" class="greenTextBoldLink">
                                 <?php
                                $groupid = $this->getParam('id');
                                $addSubgroupLink = new link($this->uri(array("action" =>"subgroupListingForm", "parent_id" => $groupid,"page" => "10a_tpl.php")));
                                $addSubgroupLink->link = $this->objLanguage->languageText('mod_unesco_oer_sub_group', 'unesco_oer');
                                 $addSubgroupLink->cssClass = 'greenTextBoldLink';
                                echo $addSubgroupLink->show();
                                ?>
                                </a></div>
                    </div>
                    <div class="groupSubLinksList">

                        <img src="skins/unesco_oer/images/icon-group-new-sub-group.png" alt="New Group" width="18" height="18" class="smallLisitngIcons">
                        <div class="linksTextNextToSubIcons"><a href="#" class="greenTextBoldLink">
                                <?php
                                $groupid = $this->getParam('id');
                                $addSubgroupLink = new link($this->uri(array("action" =>"subgroupForm", "parent_id" =>$groupid, "page" => "10a_tpl.php")));
                                $addSubgroupLink->link = $this->objLanguage->languageText('mod_unesco_oer_sub_group_create', 'unesco_oer');
                                 $addSubgroupLink->cssClass = 'greenTextBoldLink';
                                    if ($this->hasMemberPermissions()){
                                          echo $addSubgroupLink->show();
                                    }
                                ?></a></div>
                    </div>
                    <div class="groupSubLinksList">
                        <img src="skins/unesco_oer/images/icon-group-email-alerts.png" alt="Email Alerts" width="18" height="18" class="smallLisitngIcons">
                        <div class="linksTextNextToSubIcons"><a href="#" class="greenTextBoldLink">Email alerts</a></div>
                    </div>
                    <div class="groupSubLinksList">
<?php
   if ($this->hasMemberPermissions()){
       
       echo ' <img src="skins/unesco_oer/images/icon-group-calendar.png" alt="Group Calendar" width="18" height="18" class="smallLisitngIcons">
                        
                        <div class="linksTextNextToSubIcons"><a href="?module=calendar&groupid='.$groupid.' &page=10a_tpl.php" class="greenTextBoldLink">Group calendar</a></div>';
       
   }

?>
                       
                    </div>
                    <div class="groupSubLinksList">
                        <?php
   if ($this->hasMemberPermissions()){
       
      echo '   <img src="skins/unesco_oer/images/icon-group-files.png" alt="Group Files" width="18" height="18" class="smallLisitngIcons">
                        <div class="linksTextNextToSubIcons"><a href="?module=filemanager&groupid='. $groupid.'&page=10a_tpl.php" class="greenTextBoldLink">Group files</a></div>';
       
   }

?>
                    
                    </div>
                    <div class="greyDivider"></div>

                    <div class="groupSubLinksList">
                        <a href="#" class="greenTextBoldLink">
                            <?php
                            $groupid = $this->getParam('id');
                            $addDiscussionLink = new link($this->uri(array("action" => "manageOERresource", "groupid" => $groupid,"page" => "10a_tpl.php")));
                            $addDiscussionLink->link = $this->objLanguage->languageText('mod_unesco_oer_manage_resource', 'unesco_oer');
                            $addDiscussionLink->cssClass = 'greenTextBoldLink';
                               if ($this->hasEditorPermissions()){
                            echo $addDiscussionLink->show();
                               }
                            ?>

                        </a><br>
                    </div>
                    <div class="groupSubLinksList">
                        <a href="#" class="greenTextBoldLink">

                            <?php
                            $groupid = $this->getParam('id');
                            $addDiscussionLink = new link($this->uri(array("action" => "addOERform", "groupid" => $groupid, "page" => "10a_tpl.php")));
                            $addDiscussionLink->link = $this->objLanguage->languageText('mod_unesco_oer_add_resource', 'unesco_oer');
                            $addDiscussionLink->cssClass = 'greenTextBoldLink';
                               if ($this->hasMemberPermissions()){
                            echo $addDiscussionLink->show();
                               }
                            ?>
                        </a>
                    </div>
                </div>
            </div>

        </div>

        <div class="groupsRightColumn tenPixelTopPadding">

            <?php
                            echo $this->objGroupUtil->groupOwner($this->getParam('id'));
            ?>
                            <div class="groupDescription">
                                <div class="tenPixelPaddingLeft">
                                    <h2 class="greenText"><?php
                            echo $this->objDbGroups->getGroupName($this->getParam('id')) . ", " . $this->objLanguagecode->getName($this->objDbGroups->getGroupCountry($this->getParam('id')))
            ?></h2><br>
                        <span class="greenText fontBold"><?php   echo $this->objLanguage->languageText('mod_unesco_oer_description', 'unesco_oer') ?>: </span>

                    <?php
                            echo $this->objGroupUtil->groupDescription($this->getParam('id'));
                    ?>
                            <!--                    <li class="noPaddingList">Advertising</li>
                                                <li class="noPaddingList">Online Journalism</li>
                                                <li class="noPaddingList">Photjournalism</li>
                                                <li class="noPaddingList">Print writing and Design</li>-->
                            <br>
                            <span class="greenText fontBold"><?php   echo $this->objLanguage->languageText('mod_unesco_oer_group_description', 'unesco_oer') ?> :  </span>
                    <?php
                            echo $this->objDbGroups->getGroupDescription($this->getParam('id'));
                    ?>

                            <br><br>
                            <span class="greenText fontBold"><?php   echo $this->objLanguage->languageText('mod_unesco_oer_group_interest', 'unesco_oer') ?> : </span>

                            <br><br>
                            <span class="greenText fontBold"><?php   echo $this->objLanguage->languageText('mod_unesco_oer_users_website_link', 'unesco_oer') ?> : </span>
                    <?php
                            echo $this->objDbGroups->getWebsite($this->getParam('id'));
                    ?>
                            <br><br>
                            <span class="greenText fontBold"><?php   echo $this->objLanguage->languageText('mod_unesco_oer_region', 'unesco_oer') ?> : </span>Africa
                            <br><br>
                            <span class="greenText fontBold"><?php   echo $this->objLanguage->languageText('mod_unesco_oer_Country', 'unesco_oer') ?> : </span>
                    <?php
                            echo $this->objLanguagecode->getName($this->objDbGroups->getGroupCountry($this->getParam('id')))
                    ?>
                            <br><br>
                            </div>
                    </div>

                   <div class="discussionListingDiv">
                        <div class="discussionListHeadingDiv"><?php   echo $this->objLanguage->languageText('mod_unesco_oer_latest_discussion', 'unesco_oer') ?> </div>

                        <div class="discussionListDiv">



            <?php
              echo $this->objGroupUtil->latestDiscussion($topics);
            ?>
<!--                            <div class="discusionPostDiv">
                                <img src="skins/unesco_oer/images/user.jpg" width="40" height="40" class="discussionImage">
                                <a href="" class="greenTextBoldLink">Discussion post title</a>
                                <br>

                                Posts: 3
                            </div>

                            <div class="discusionPostDiv">
                                <img src="skins/unesco_oer/images/user.jpg" width="40" height="40" class="discussionImage">
                                <a href="" class="greenTextBoldLink">Discussion post title</a>
                                <br>
                                Posts: 8
                                <div class="discusionReplyDiv">
                                    <img src="skins/unesco_oer/images/user.jpg" width="40" height="40" class="discussionImage">

                                    <a href="" class="greenTextBoldLink">Re:Discussion post title</a>
                                    <br>
                                    Posts: 1
                                </div>
                            </div>

                            <div class="discusionPostDiv">
                                <img src="skins/unesco_oer/images/user.jpg" width="40" height="40" class="discussionImage">
                                <a href="" class="greenTextBoldLink">Discussion post title</a>

                                <br>
                                Posts: 23
                            </div>-->

                            <div class="showAlldiscussions">

                                <a href="?module=forum&action=forum&id=<?php
                            $groupid = $this->getParam('id');
                            echo $this->objDbGroups->getGroup_pkid_in_forum($groupid);
                        ?>&page=10a_tpl.php   " class="greenTextBoldLink"><?php   echo $this->objLanguage->languageText('mod_unesco_oer_all_discussion', 'unesco_oer') ?> </a> |

                        <?php
                            $forumId = $this->objDbGroups->getGroupForumId($groupid);
                            $addDiscussionLink = new link('/unesco_oer/index.php?module=forum&action=newtopic&id='.$forumId.'&type=context&page=10a_tpl.php');
                            $addDiscussionLink->link = 'add a discussion';
                            $addDiscussionLink->cssClass = 'greenTextBoldLink';
                               if ($this->hasMemberPermissions()){
                            echo $addDiscussionLink->show();
                               }
                        ?>
<!--                        http://localhost/unesco_oer/index.php?module=forum&action=newtopic&id=gen14Srv26Nme38_77856_1311232246&type=context-->
                        
                        </div>
                    </div>


                </div>


                <!--- OER Resources -->
                <div class="resourcesListingDiv">
                    <div class="resourcesListHeadingDiv"><?php   echo $this->objLanguage->languageText('mod_unesco_oer_resource', 'unesco_oer') ?></div>
                    <div class="resourcesListDiv">
                        <?php
                            $groupid = $this->getParam('id');
                           echo  $this->objGroupUtil->OerResource($groupid);
                        ?>
<!--                        <div class="resourcesPostDiv">
                            <img src="skins/unesco_oer/images/adapted-product-grid-group-logo-placeholder.jpg" width="45" height="49" class="resourcesImage">
                            <h2>Model Cirrcula for Journalism Education</h2>

                            <br>
                            Adapted in : <a href="" class="greyTextLink">English</a>
                        </div>

                        <div class="resourcesPostDiv">
                            <img src="skins/unesco_oer/images/adapted-product-grid-group-logo-placeholder.jpg" width="45" height="49" class="resourcesImage">
                            <h2>Manual for Investigative Journalism</h2>
                            <br>

                            Adapted in : <a href="" class="greyTextLink">English</a>
                        </div>-->
                        <div class="showAlldiscussions">
                            <a href="" class="greyTextBoldLink"><?php   echo $this->objLanguage->languageText('mod_unesco_oer_all_resource', 'unesco_oer') ?></a> |

                        <?php
                            $groupid = $this->getParam('id');
                            $addresourceLink = new link($this->uri(array("action" => "addOERform", "groupid" => $groupid, "page" => "10a_tpl.php")));
                            $addresourceLink->link = 'add resource';
                            $addresourceLink->cssClass = 'greyTextBoldLink';
                               if ($this->hasMemberPermissions()){
                           echo $addresourceLink->show();
                               }
                        ?>
                       </div>
                </div>


            </div>

        </div>

    </div>

</div>

<!-- Footer-->
