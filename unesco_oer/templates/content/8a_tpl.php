<?php
$this->loadClass('link', 'htmlelements');
$this->loadClass('dropdown', 'htmlelements');
$this->loadClass('button', 'htmlelements');
$this->loadClass('checkbox', 'htmlelements');
$this->loadClass('textinput', 'htmlelements');
$this->objLanguagecode = $this->getObject('languagecode', 'language');
?>


<div class="subNavigation"></div>
<div class="breadCrumb tenPixelLeftPadding">
    <a href="#" class="groupsBreadCrumbColor">Groups</a> |
    <span class="groupsBreadCrumbColor noUnderline"><?php
//  / $groupname=$this->objDbGroups->getGroupName($groupid);
//   $group_Country=$this->objDbGroups->getGroupCountry($groupid);/
   echo $this->objDbGroups->getGroupName($this->getParam('id')) . ", " .$this->objLanguagecode->getName($this->objDbGroups->getGroupCountry($this->getParam('id')));
   
    ?>   </span>

</div>
<div class="greenBackgroundColor">
    <div class="wideLeftFloatDiv">
        <!-- Left Colum -->
        <div class="groupsleftColumnDiv  tenPixelTopPadding">
            <div class="tenPixelPaddingLeft tenPixelPaddingRight">
                <img src="<?php echo $this->objDbGroups->getThumbnail($this->getParam('id'))?>" alt="Adaptation placeholder" width="40" height="40" class="smallAdaptationImageGrid"><h5 class="greenText">
                    <?php
                    echo $this->objDbGroups->getGroupName($this->getParam('id')) . ", " .$this->objLanguagecode->getName($this->objDbGroups->getGroupCountry($this->getParam('id')));
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
$id = $this->objUseExtra->getUserbyUserIdbyUserID($currLoggedInID);
$groupid = $this->getParam('id');
echo $this->objGroupUtil->leaveGroup($id, $groupid);
?>
<!--                           <img src="skins/unesco_oer/images/icon-group-leave-group.png" alt="Leaave Group" width="18" height="18" class="smallLisitngIcons">
                       <div class="linksTextNextToSubIcons"><a href="#" class="greenTextBoldLink">Leave group</a></div>-->
                    </div>
                    <div class="groupSubLinksList">

                        <img src="skins/unesco_oer/images/icon-group-discussion.png" alt="Group discussion" width="18" height="18" class="smallLisitngIcons">
                        <div class="linksTextNextToSubIcons"><a href="?module=forum" class="greenTextBoldLink">Group discussion</a></div>
                    </div>
                    <div class="groupSubLinksList">
                        <img src="skins/unesco_oer/images/icon-group-subgroups.png" alt="Sub Groups" width="18" height="18" class="smallLisitngIcons">
                        <div class="linksTextNextToSubIcons"><a href="#" class="greenTextBoldLink">Subgroups</a></div>
                    </div>
                    <div class="groupSubLinksList">

                        <img src="skins/unesco_oer/images/icon-group-new-sub-group.png" alt="New Group" width="18" height="18" class="smallLisitngIcons">
                        <div class="linksTextNextToSubIcons"><a href="#" class="greenTextBoldLink">Create a new subgroup</a></div>
                    </div>
                    <div class="groupSubLinksList">
                        <img src="skins/unesco_oer/images/icon-group-email-alerts.png" alt="Email Alerts" width="18" height="18" class="smallLisitngIcons">
                        <div class="linksTextNextToSubIcons"><a href="#" class="greenTextBoldLink">Email alerts</a></div>
                    </div>
                    <div class="groupSubLinksList">

                        <img src="skins/unesco_oer/images/icon-group-calendar.png" alt="Group Calendar" width="18" height="18" class="smallLisitngIcons">
                        <div class="linksTextNextToSubIcons"><a href="?module=calendar" class="greenTextBoldLink">Group calendar</a></div>
                    </div>
                    <div class="groupSubLinksList">
                        <img src="skins/unesco_oer/images/icon-group-files.png" alt="Group Files" width="18" height="18" class="smallLisitngIcons">
                        <div class="linksTextNextToSubIcons"><a href="#" class="greenTextBoldLink">Group files</a></div>
                    </div>
                    <div class="greyDivider"></div>

                    <div class="groupSubLinksList">
                        <a href="#" class="greenTextBoldLink">Manage OER Resources</a><br>
                    </div>
                    <div class="groupSubLinksList">
                        <a href="#" class="greenTextBoldLink">Add OER Resources</a>
                    </div>
                </div>
            </div>

        </div>

        <div class="groupsRightColumn tenPixelTopPadding">
<!--           <div class="groupOwnerImage">
                <img src="skins/unesco_oer/images/product-grid-product-cover-placeholder.jpg" width="79" height="101">
                <br>
                <span class="greenText fontBold">Owner:</span> <br>
              
                <br><br>
                <span class="greenText fontBold">Administrators: <br></span>2<br><br>

                <span class="greenText fontBold">Group members: <br></span>
             </div>-->
<?php
//echo $this->objDbGroups->getGroupName($this->getParam('id'));
//echo $this->objDbGroups->getGroupOwner($this->getParam('id'));
echo $this->objGroupUtil->groupOwner($this->getParam('id'));
?>
            <div class="groupDescription">
                <div class="tenPixelPaddingLeft">
                    <h2 class="greenText"><?php
                    echo $this->objDbGroups->getGroupName($this->getParam('id')) . ", " .$this->objLanguagecode->getName($this->objDbGroups->getGroupCountry($this->getParam('id')))
                    ?></h2><br>
                    <span class="greenText fontBold">Description</span>

                    <?php
                    echo  $this->objGroupUtil->groupDescription($this->getParam('id'));

                    ?>
<!--                    <li class="noPaddingList">Advertising</li>
                    <li class="noPaddingList">Online Journalism</li>
                    <li class="noPaddingList">Photjournalism</li>
                    <li class="noPaddingList">Print writing and Design</li>-->
                    <br>
                    <span class="greenText fontBold">Brief description: </span>
                    <?php
                     echo $this->objDbGroups->getGroupDescription($this->getParam('id'));
                    ?>
                    
                    <br><br>
                    <span class="greenText fontBold">Interest: </span>

                    <br><br>
                    <span class="greenText fontBold">Website: </span>
                    <?php
                    echo $this->objDbGroups->getWebsite($this->getParam('id'));
                    ?>
                    <br><br>
                    <span class="greenText fontBold">Region: </span>Africa
                    <br><br>
                    <span class="greenText fontBold">Country: </span>
                    <?php
                    echo $this->objLanguagecode->getName($this->objDbGroups->getGroupCountry($this->getParam('id')))
                    ?>
                    <br><br>
                    <span class="greenText fontBold">School database revcord URL: </span>http://www.unesco-ci.org
                </div>

            </div>

            <div class="discussionListingDiv">
                <div class="discussionListHeadingDiv">Latest discussion</div>
                <div class="discussionListDiv">
                    <div class="discusionPostDiv">
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
                    </div>

                    <div class="showAlldiscussions">
                        <a href="" class="greenTextBoldLink">All discussions</a> |
                       
                        <?php
                        $forumId = $this->objDbGroups->getGroupForumId($groupid);
                        $addDiscussionLink = new link($this->uri(array("action"=>"newTopicForm", "groupid" => $groupid, "forumid" => $forumid)));
                        $addDiscussionLink->link='add a discussion';
                        $addDiscussionLink->cssClass='greenTextBoldLink';
                        echo $addDiscussionLink->show();
                       ?>
                    </div>
                </div>


            </div>


            <!--- OER Resources -->
            <div class="resourcesListingDiv">
                <div class="resourcesListHeadingDiv">OER Resources</div>
                <div class="resourcesListDiv">
                    <div class="resourcesPostDiv">
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
                    </div>
                    <div class="showAlldiscussions">
                        <a href="" class="greyTextBoldLink">All OER resources</a> |
                        <a href="" class="greyTextBoldLink">add resource</a>
                    </div>
                </div>


            </div>

        </div>

    </div>

</div>

<!-- Footer-->
