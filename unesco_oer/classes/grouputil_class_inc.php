<?php
$this->loadClass('link', 'htmlelements');

/*
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the
 * Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

class grouputil extends object {

    public function init() {
        $this->ObjDbUserGroups = $this->getObject("dbusergroups", "unesco_oer");
        $this->objDbGroups = $this->getObject("dbgroups", "unesco_oer");
        $this->objUser = $this->getObject('user', 'security');
        $this->objUseExtra = $this->getObject("dbuserextra", "unesco_oer");
        $this->objLanguagecode = $this->getObject('languagecode', 'language');
        $this->objTranslatedDate = $this->getObject('translatedatedifference', 'utilities');
        $this->objPost = $this->getObject('dbpost','forum');
       $this->objDateTime =  $this->getObject('dateandtime', 'utilities');
    }

    public function groupPerPage() {
        $dropdown = new dropdown('group_per_page');
        $groups = $this->objDbGroups->getAllGroups();

        if (count($groups) >= 9) {
            for ($i = 1; $i < 10; $i++) {
                $dropdown->addOption($i);
            }
        } else {
            for ($i = 1; $i <= count($groups); $i++) {
                $dropdown->addOption($i);
            }
        }
        $content.=' <div class="moduleHeader darkBlueText"><img src="skins/unesco_oer/images/icon-filter-items-per-page.png" alt="Items per page" class="modulesImages">Groups per page</div>
                <div class="blueBackground">
                	
                    	<option value="">' . $dropdown->show() . '</option>
                    </select>
                </div>';


        return $content;
    }

    public function content($group) {

        $thumbLink = new link($this->uri(array("action" => '11a', 'id' => $group['id'], "page" => '10a_tpl.php')));

        $thumbLink->link = '<img src="' . $group['thumbnail'] . '" alt="Adaptation placeholder" width="45" height="49" class="smallAdaptationImageGrid">';
        $userid = $this->objUser->userId();
        if ($this->ObjDbUserGroups->ismemberOfgroup($userid, $group['id'])) {

            $joinGroupLink = new link($this->uri(array('action' => "10")));
            $joinGroupLink->link = 'Join';
            $joinGroupLink->cssId = 'memberofgroup';
        } else {

            $joinGroupLink = new link($this->uri(array('action' => "joingroup", 'groupid' => $group['id'], 'userid' => $this->objUser->userId(), "page" => '10a_tpl.php')));
            $joinGroupLink->link = 'Join';
            $joinGroupLink->cssId = 'joingroup';
        }

        $joinGroupLink->cssClass = 'greenTextBoldLink';


        $content.='

<div class="whiteBackgroundBox">
                            ' . $thumbLink->show() . '
                            <div class="groupGridViewHeading greenText">
                             ' . $group['name'] . '
                            </div>
                            <div class="groupMemberAndJoinLinkDiv">
                            	<span class="greenText">Members :</span>' . $this->ObjDbUserGroups->groupMembers($group['id']) . '<br><br>
                                <a href="#"><img src="skins/unesco_oer/images/icon-join-group.png" alt="Join Group" width="18" height="18" class="smallLisitngIcons"></a>
               				 	<div class="linkTextNextToJoinGroupIcons">' . $joinGroupLink->show() . '</div>
                            </div>

                            </div>';

        return $content;
    }

    public function populateListView() {
        $objTable = $this->getObject('htmltable', 'htmlelements');
        $objTable->cssClass = "gridListingTable";
        $objTable->width = NULL;
        $groups = $this->objDbGroups->getAllGroups();
        foreach ($groups as $group) {
            $objTable->startRow();
            $objTable->addCell($this->content($group));
            $objTable->endRow();
        }

        echo $objTable->show();
    }

    public function popoulateGridView() {
        $objTable = $this->getObject('htmltable', 'htmlelements');
        $objTable->cssClass = "gridListingTable";
        $objTable->width = NULL;
        $groups = $this->objDbGroups->getAllGroups(); // need to specify
        $newRow = true;
        $count = 0;
        foreach ($groups as $group) {
            $count++;
            if ($newRow) {
                $objTable->startRow();
                $objTable->addCell($this->content($group));
                $newRow = false;
            } else {
                $objTable->addCell($this->content($group));
            }
            if ($count == 3) {
                $newRow = true;
                $objTable->endRow();
                $count = 0;
            }
        }

        echo $objTable->show();
    }

    //return group members

    public function groupMembers($groupid) {

        $groupOwnerId = $this->objDbGroups->getGroupOwner($groupid);

        $arrays = $this->ObjDbUserGroups->getGroupUser($groupid);
        //(<span class="greenText fontBold">Group Administrator</span>)
        foreach ($arrays as $array) {
            if (strcmp($groupOwnerId, $array[0]['userid'])) {
                $firstname = $this->objUseExtra->getUserfirstname($groupOwnerId);
                $surname = $this->objUseExtra->getUserSurname($groupOwnerId);

                $content.=' 
                    <div class="memberList">
                    <div class="communityRelatedInfoIcon"><img src="skins/unesco_oer/images/icon-member.png" width="18" height="18"></div>
                    <div class="memberIconText">' . $firstname . " " . " " . " " . $surname . '(<span class="greenText fontBold">Group Administrator</span>) </div>
                     </div>';
            } else {
                if (strcmp($this->objUser->userId(), $groupOwnerId) && $this->ObjDbUserGroups->notApproved($array[0]['userid'])) {
                    $Link = new link($this->uri(array("action" => 'ViewProduct', 'id' => $productID, "page" => '10a_tpl.php')));
                    $Link->link = '<img src="skins/unesco_oer/images/icon-join-group.png" width="18" height="18"></div>';

                    $firstname = $this->objUseExtra->getUserSurnameByID($array[0]['id']);
                    $surname = $this->objUseExtra->getUserfirstnameByID($array[0]['id']);
                    $content.='<div class="memberList">
                            <div class="communityRelatedInfoIcon"><img src="skins/unesco_oer/images/icon-member.png" width="18" height="18"></div>
                            <div class="memberIconText">' . $firstname . " " . " " . " " . $surname . ' </div>
                            <div class="communityRelatedInfoIcon"><img src="skins/unesco_oer/images/icon-join-group.png" width="18" height="18"></div>
                           </div>';
                } else {
                    $firstname = $this->objUseExtra->getUserSurnameByID($array[0]['id']);
                    $surname = $this->objUseExtra->getUserfirstnameByID($array[0]['id']);
                    $content.='<div class="memberList">
                            <div class="communityRelatedInfoIcon"><img src="skins/unesco_oer/images/icon-member.png" width="18" height="18"></div>
                            <div class="memberIconText">' . $firstname . " " . " " . " " . $surname . ' </div>
                           </div>';
                }
            }
        }
        echo $content;
    }

    public function groupAdaptation($groupid) {

        $content = '';
        $arrays = $this->objDbGroups->getGroupProductadaptation($groupid);
        if (count($arrays) > 0) {
            foreach ($arrays as $array) {
                $productID = $array['product_id'];

                $product = $this->newObject('product', 'unesco_oer');
                $product->loadProduct($productID);
                $language = $product->getLanguageName();
                $institution = $product->getInstitutionName();
                $institutionId = $product->getInstitutionID();
                $Thumbnail = $product->getThumbnailPath();

                $Link = new link($this->uri(array("action" => 'ViewProduct', 'id' => $productID, "page" => '10a_tpl.php')));
                $Link->link = '<img src="' . $Thumbnail . ' "alt="Adaptation placeholder" width="45" height="49" class="smallAdaptationImageGrid">';

                $Title = $this->objDbGroups->getAdaptedProductTitle($productID);
                $TittleLink = new link($this->uri(array("action" => 'ViewProduct', 'id' => $productID, "page" => '10a_tpl.php')));
                $TittleLink->link = $Title;

                $InstitutionLink = new link($this->uri(array("action" => '4', 'institutionId' => $institutionId, "page" => '10a_tpl.php')));
                $InstitutionLink->link = $institution;


                $content.='              <div class="discussionList">
                            ' . $Link->show() . '
                            <div class="textNextToGroupIcon">
                             <h2>' . $TittleLink->show() . '</h2>
                             Institution : <a href="#" class="greyTextLink">' . $InstitutionLink->show() . '</a><br>
                             Adapted in : <a href="#" class="bookmarkLinks">' . $language . '</a>
                            </div>
                        </div>';
            }
            echo $content;
        }
    }

    public function groupInstitution($groupid) {
        $content = '';
        $arrayInstitutionId = $this->objDbGroups->getInstitutions($groupid);

        foreach ($arrayInstitutionId as $institutionid) {
            //if(strcmp($institutionid,"Null")!=0){
            $institutionThumbnail = $this->objDbGroups->getInstitutionThumbnail($institutionid);
            $institutionname = $this->objDbGroups->getInstitutionName($institutionid);

            $content.='  <div class="discussionList"><img src="' . $institutionThumbnail . ' "alt="Adaptation placeholder" class="smallAdaptationImageGrid" height="49" width="45">
                            <div class="textNextToGroupIcon">
                                <h2>
                                ' . $institutionname . '</h2>
                                   <a href="#" class="bookmarkLinks">English</a> | <a href="#" class="bookmarkLinks">German</a>
                            </div>
                             </div>
                   ';
        //}
        }
        echo $content;
    }

    public function topcontent($groupid) {
        $Link = new link($this->uri(array("action" => '8a', 'id' => $groupid, "page" => '10a_tpl.php')));
        $Link->link = $this->objDbGroups->getGroupName($groupid);


        $content.='
     <img src="' . $this->objDbGroups->getThumbnail($groupid) . '" alt="Adaptation placeholder" class="smallAdaptationImageGrid" height="49" width="45">
        <div class="textNextToGroupIcon">
        <h2 class="greenText">' . $Link->show() . '</h2>
            ' . $this->objDbGroups->getGroupDescription($groupid) . '
                       </div>
        ';
        return $content;
    }

    public function getThumbnail($groupid) {

        return $this->objDbgroups->getThumbnail($groupid);
    }

    public function leaveGroup($id, $groupid) {

        if ($this->ObjDbUserGroups->ismemberOfgroup($id, $groupid)) {
            $LeavegroupLink = new link($this->uri(array('action' => "leaveGroup", 'id' => $id, 'groupid' => $groupid, "page" => '10a_tpl.php')));
            $LeavegroupLink->link = 'Leave group';
            $LeavegroupLink->cssId = 'leavegroup';
        } else {
            $LeavegroupLink = new link($this->uri(array('action' => "8a", "page" => '10a_tpl.php')));
            $LeavegroupLink->link = 'Leave group';
            $LeavegroupLink->cssId = 'cantleavegroup';
        }

        $LeavegroupLink->cssClass = "greenTextBoldLink";
        $content.='<img src="skins/unesco_oer/images/icon-group-leave-group.png" alt="Leaave Group" width="18" height="18" class="smallLisitngIcons">
                           <div class="linksTextNextToSubIcons">' . $LeavegroupLink->show() . '</div>';
        return $content;
    }

    public function groupOwner($groupid) {

        $ownerId = $this->objDbGroups->getGroupOwner($groupid);
        $owner_Details = $this->objUseExtra->getUserbyUserID($ownerId);
        $owner_surname = $owner_Details[0]['surname'];
        $owner_name = $owner_Details[0]['firstname'];
        $ownerExtraInfo = $this->objUseExtra->getUserExtraByID($ownerId);
        $group_thumbnail = $this->objDbGroups->getThumbnail($groupid);
        $groupMembers = $this->ObjDbUserGroups->groupMembers($groupid);




        $content.='
         <div class="groupOwnerImage">
        <img src="' . $group_thumbnail . '" width="79" height="101">
                            <br><span class="greenText fontBold">Owner:</span> <br>
                            ' . $owner_name . '' . '&nbsp;' . '' . $owner_surname . '
                                <br><br>
                            <span class="greenText fontBold">Administrators: <br></span>2<br><br>

                            <span class="greenText fontBold">Group members: <br></span>' . $groupMembers . '
                         </div>';
        return $content;
    }

    public function groupDescription($groupid) {
        $content = '';

        $content.='<li class="noPaddingList">' . $this->objDbGroups->getDescription_Line_one($groupid) . '</li>';
        if ($this->objDbGroups->getDescription_Line_two($groupid) != '') {
            $content.=' <li class="noPaddingList">' . $this->objDbGroups->getDescription_Line_one($groupid) . '</li>';
        }
        if ($this->objDbGroups->getDescription_Line_three($groupid) != '') {
            $content.=' <li class="noPaddingList">' . $this->objDbGroups->getDescription_Line_one($groupid) . '</li>';
        }
        if ($this->objDbGroups->getDescription_Line_four($groupid) != '') {
            $content.=' <li class="noPaddingList">' . $this->objDbGroups->getDescription_Line_one($groupid) . '</li>';
        }

        return $content;
    }

    function latestDiscussion($groupid) {

        $content;
        $content.='

        <div class="discusionPostDiv">
        <img src="skins/unesco_oer/images/user.jpg" width="40" height="40" class="discussionImage">
        <a href="" class="greenTextBoldLink">Discussion post title</a>
        <br>
        Posts: 3
       </div>';
    }

    function discussion($topics, $forumId) {
        $content;

        foreach ($topics as $topic) {
            $link = new link($this->uri(array('action' => 'viewtopic', 'id' => $topic['topic_id'], 'type' => 'context'), 'forum'));
            $link->link = stripslashes($topic['post_title']);
            $link->show();
            //$datefield=$this->objTranslatedDate->getDifference($topic['lastdate']);
           $post=$this->objPost->getLastPost($forumId);
           $datefield = $this->objDateTime->formatDateOnly($post['datelastupdated']).' - '.$this->objDateTime->formatTime($post['datelastupdated']);


            $content.='
                <div class="tenPixelPaddingLeft">
                <div class="topGroupDiv">
                	<div class="paddingContentTopLeftRightBottom">
                        <div class="discussionList">
                         <h3 class="fontBold">' . $link->show() . '</h3>
                             <div class="textNextToRightFloatedImage">
                              </div>
                            Posts:12<br>
                            Last posted: '.$datefield.'
                        </div>


                    </div>
                </div>
                <br><br><br>
                </div>
';
        }
        return $content;
    }

}
?>



<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript">

    jQuery(document).ready(function(){

        jQuery("a[id=joingroup]").click(function(){

            var r=confirm( "Are you sure you want to join this group?\nClick Ok a request will be sent to the group admin");
            if(r== true){
                window.location=this.href;
            }
            return false;
        }
    );

    }
);

    jQuery(document).ready(function(){

        jQuery("a[id=memberofgroup]").click(function(){

            var r=confirm( "Your are a member of this group\n you can not join again....!!!");
            if(r== true){
                window.location=this.href;
            }
            return false;
        }


    );
    }
);


    jQuery(document).ready(function(){

        jQuery("a[id=leavegroup]").click(function(){

            var r=confirm( "Are you sure you want to leave the group?");
            if(r== true){
                window.location=this.href;
            }
            return false;
        }
    );

    }
);

    jQuery(document).ready(function(){

        jQuery("a[id=cantleavegroup]").click(function(){

            var r=confirm( "You are not a member of this group\n Action Leave group failed....!!!!");
            if(r== true){
                window.location=this.href;
            }
            return false;
        }
    );

    }
);


</script>

