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
          $this->objDbInstitution = $this->getObject("dbinstitution", "unesco_oer");
            $this->objLanguage = $this->getObject("language", "language");
             $this->objGroups = $this->getObject('groupadminmodel', 'groupadmin');
               $this->objGroupAdminModel = $this->getObject("groupadminmodel", "groupadmin");
        $this->objUser = $this->getObject('user', 'security');
        $this->objUseExtra = $this->getObject("dbuserextra", "unesco_oer");
        $this->objLanguagecode = $this->getObject('languagecode', 'language');
        $this->objTranslatedDate = $this->getObject('translatedatedifference', 'utilities');
        $this->objPost = $this->getObject('dbpost','forum');
        $this->objDateTime =  $this->getObject('dateandtime', 'utilities');
        $js = '<script language="JavaScript" src="' . $this->getResourceUri('grouputil.js') . '" type="text/javascript"></script>';
        $this->appendArrayVar('headerParams', $js);
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

    public function content($group,$onestep) {

        $thumbLink = new link($this->uri(array("action" => '11a', 'id' => $group['id'], "page" => '10a_tpl.php')));
        $thumbLink->link = '<img src="' . $group['thumbnail'] . '" alt="Adaptation placeholder" width="45" height="49" class="smallAdaptationImageGrid">';
        $groupnameLink= new link($this->uri(array("action" => '11a', 'id' => $group['id'], "page" => '10a_tpl.php')));
        $groupnameLink->link=$group['name'];
        $groupnameLink->cssClass="groupGridViewHeading greenText";
  //echo $onestep;
  
        $userid = $this->objUser->userId();
        if ($this->ObjDbUserGroups->ismemberOfgroup($userid, $group['id'])) {
      

            
        $joinGroupLink = new link($this->uri(array('action' => "10")));
        

            $joinGroupLink->link = $this->objLanguage->languageText('mod_unesco_oer_join', 'unesco_oer');
            $joinGroupLink->cssId = 'memberofgroup';
        } else {
            
            if ($onestep != null){
            
             //  $joinGroupLink = new link($this->uri(array('action' => "onestepjoingroup", 'groupid' => $group['id'], 'userid' => $this->objUser->userId(), "page" => '10a_tpl.php','productID' => $onestep)));
                   $joinGroupLink = new link('#');
                    $joinGroupLink->link = $this->objLanguage->languageText('mod_unesco_oer_join', 'unesco_oer');;
                  $joinGroupLink->cssId = $group['id'];
                  
        } else  {   $joinGroupLink = new link($this->uri(array('action' => "joingroup", 'groupid' => $group['id'], 'userid' => $this->objUser->userId(), "page" => '10a_tpl.php')));

         
            $joinGroupLink->link = $this->objLanguage->languageText('mod_unesco_oer_join', 'unesco_oer');
            $joinGroupLink->cssId = 'joingroup';
        }
        }

        $joinGroupLink->cssClass = 'greenTextBoldLink';
   
//        if ($this->hasMemberPermissions()){
        $showgrouplink = $joinGroupLink->show();
        
        
//             } else $showgrouplink = null;


        $content.='

<div class="whiteBackgroundBox">
                            ' . $thumbLink->show() . '
                            <div class="groupGridViewHeading greenText">
                             ' .$groupnameLink->show(). '
                            </div>
                            <div class="groupMemberAndJoinLinkDiv">
                            	<span class="greenText">Members :</span>' . $this->ObjDbUserGroups->groupMembers($group['id']) . '<br><br>
                                <a href="#"><img src="skins/unesco_oer/images/icon-join-group.png" alt="Join Group" width="18" height="18" class="smallLisitngIcons"></a>
               				 	<div class="linkTextNextToJoinGroupIcons"> '. $showgrouplink .'</div>
                            </div>

                            </div> 
                          
';

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
        $adminInfo=$this->objUseExtra->getUserbyUserID($arrays[0]['userid']);

   //CHECK IF IS A GROUP OWNER
        if(!$this->objUseExtra->isGroupOwner($this->objUser->isLoggedIn(),$groupid)){
            
            foreach ($arrays as $array) {
                $userInfo=$this->objUseExtra->getUserbyUserID($array['userid']);

                if(strcmp($array['userid'],$groupOwnerId)==0){
                    $firstname =  $userInfo[0]['firstname'];
                    $surname =  $userInfo[0]['surname'];
                    $content.='
                             <div class="memberList">
                             <div class="communityRelatedInfoIcon"><img src="skins/unesco_oer/images/icon-member.png" width="18" height="18"></div>
                             <div class="memberIconText">' . $firstname . " " . " " . " " . $surname . '(<span class="greenText fontBold">Group Administrator</span>) </div>
                              </div>';
           }

           if(!(strcmp($array['userid'],$groupOwnerId)==0) && $this->ObjDbUserGroups->notApproved($array['userid'])){
                    $firstname =$userInfo[0]['firstname'];
                    $surname = $userInfo[0]['surname'];
                    $content.='<div class="memberList">
                              <div class="communityRelatedInfoIcon"><img src="skins/unesco_oer/images/icon-member.png" width="18" height="18"></div>
                              <div class="memberIconText">' . $firstname . " " . " " . " " . $surname . ' </div>
                              </div>';
                    }

                    if(!$this->ObjDbUserGroups->notApproved($array['userid'])){
                        $Link = new link($this->uri(array("action" => 'ViewProduct', 'id' => $productID, "page" => '10a_tpl.php')));
                        $Link->link = '<img src="skins/unesco_oer/images/icon-join-group.png" width="18" height="18"></div>';
                        $firstname =$userInfo[0]['firstname'];
                        $surname = $userInfo[0]['surname'];
                        $content.='<div class="memberList">
                                   <div class="communityRelatedInfoIcon"><img src="skins/unesco_oer/images/icon-member.png" width="18" height="18"></div>
                                   <div class="memberIconText">' . $firstname . " " . " " . " " . $surname . ' </div>
                                   <div class="communityRelatedInfoIcon"><img src="skins/unesco_oer/images/icon-join-group.png" width="18" height="18"></div>
                               </div>';
                        }

             
           }
           echo $content;
        }else{
            foreach ($arrays as $array) {
                $userInfo=$this->objUseExtra->getUserbyUserID($array['userid']);
                if(strcmp($array['userid'],$groupOwnerId)==0){
                      $firstname =  $userInfo[0]['firstname'];
                      $surname =  $userInfo[0]['surname'];
                       $content.='
                    <div class="memberList">
                    <div class="communityRelatedInfoIcon"><img src="skins/unesco_oer/images/icon-member.png" width="18" height="18"></div>
                    <div class="memberIconText">' . $firstname . " " . " " . " " . $surname . '(<span class="greenText fontBold">Group Administrator</span>) </div>
                     </div>';
           }

                   if($this->ObjDbUserGroups->notApproved($array['userid']) && !(strcmp($array['userid'],$groupOwnerId)==0)){
                  $firstname =$userInfo[0]['firstname'];
                  $surname = $userInfo[0]['surname'];
                  $content.='<div class="memberList">
                            <div class="communityRelatedInfoIcon"><img src="skins/unesco_oer/images/icon-member.png" width="18" height="18"></div>
                            <div class="memberIconText">' . $firstname . " " . " " . " " . $surname . ' </div>
                                                      </div>';

           }
           }

           echo $content;
        }

}



    


    public function groupAdaptation($groupid, $customURIarray = NULL, $customApaptationList = NULL) {

        $content = '';
        $arrays = array();
        if (empty($customApaptationList)){
            $arrays = $this->objDbGroups->getGroupProductadaptation($groupid);
        } else {
            $arrays = $customApaptationList;
        }
        if (count($arrays) > 0) {
            foreach ($arrays as $array) {
                if (empty($customApaptationList)){
                $productID = $array['product_id'];
                } else {
                    $productID = $array['id'];
                }

                $product = $this->newObject('product', 'unesco_oer');
                $product->loadProduct($productID);
                $language = $product->getLanguageName();
                $institution = $product->getInstitutionName();
                $institutionId = $product->getInstitutionID();
                $Thumbnail = $product->getThumbnailPath();

                $uri = $this->uri(array("action" => 'ViewProduct', 'id' => $productID, "page" => '10a_tpl.php'));

                if (!empty($customURIarray)) {
                    $customURIarray['productID'] = $productID;
                    $uri = $this->uri($customURIarray);
                }

                $Link = new link($uri);
                $Link->link = '<img src="' . $Thumbnail . ' "alt="Adaptation placeholder" width="45" height="49" class="smallAdaptationImageGrid">';

                $Title = $this->objDbGroups->getAdaptedProductTitle($productID);
                $TittleLink = new link($uri);
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
              $groupnameLink= new link($this->uri(array("action" => 'DelinkGroup', 'id' => $groupid, 'instid' => $institutionid, "page" => '10a_tpl.php')));
              $groupnameLink->link= $this->objLanguage->languageText('mod_unesco_oer_remove_institution', 'unesco_oer');
                 $groupnameLink->cssClass="groupGridViewHeading greenText";  
                 
                 $userid = $this->objUser->userId();      
        if ($this->ObjDbUserGroups->ismemberOfgroup($userid, $groupid)) {
            

            $content.='  <div class="discussionList"><img src="' . $institutionThumbnail . ' "alt="Adaptation placeholder" class="smallAdaptationImageGrid" height="49" width="45">
                            <div class="textNextToGroupIcon">
                                <h2>
                                ' . $institutionname . '</h2>
                                   <a href="#" class="bookmarkLinks">English</a> | <a href="#" class="bookmarkLinks">German</a> 
                                   
                            <div class= "institutionLink">' . $groupnameLink->show().'
                          
                </div>
                            </div>
                            
                             </div>
                   ';} else {
                        $content.='  <div class="discussionList"><img src="' . $institutionThumbnail . ' "alt="Adaptation placeholder" class="smallAdaptationImageGrid" height="49" width="45">
                            <div class="textNextToGroupIcon">
                                <h2>
                                ' . $institutionname . '</h2>
                                   <a href="#" class="bookmarkLinks">English</a> | <a href="#" class="bookmarkLinks">German</a> 
                                   
                    
                            </div>
                            
                             </div>
                   ';
                       
                       
                       
                       
                       
                   }
        //}
        }
        echo $content;
    }

    public function topcontent($groupid , $uri = NULL) {
        $groupName = $this->objDbGroups->getGroupName($groupid);
        if (empty($groupid) || empty($groupName)) {
            return false;
        }

        if (empty($uri)) $uri = $this->uri(array("action" => '8a', 'id' => $groupid, "page" => '10a_tpl.php'));
        $Link = new link($uri);
        $Link->link = $groupName;
        $Link->cssClass="greenTextBoldLink";


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
            $LeavegroupLink->link = $this->objLanguage->languageText('mod_unesco_oer_group_leave', 'unesco_oer');
            $LeavegroupLink->cssId = 'leavegroup';
              $LeavegroupLink->cssClass = "greenTextBoldLink";
        $content.='<img src="skins/unesco_oer/images/icon-group-leave-group.png" alt="Leaave Group" width="18" height="18" class="smallLisitngIcons">
                           <div class="linksTextNextToSubIcons">' . $LeavegroupLink->show() . '</div>';
        } else {
            $content = null;
        }

      
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

    function latestDiscussion($topics) {

        $content;
       

        foreach ($topics as $topic) {
            $link = new link($this->uri(array('action' => 'viewtopic', 'id' => $topic['topic_id'], 'type' => 'context',"page" => "10a_tpl.php"), 'forum'));
            $link->link = stripslashes($topic['post_title']);
            $link->cssClass = "greenTextBoldLink";
       
            $numberReply=$this->objDbGroups->getNumberPost($topic['topic_id']);
          
            if($numberReply!=0){
               $reply='<div class="discusionReplyDiv">
                     <img src="skins/unesco_oer/images/user.jpg" width="40" height="40" class="discussionImage">
                      <a href="" class="greenTextBoldLink">Re:'.$link->show().'</a>
                      <br>
                      Posts: '.$numberReply.'
                      </div>';
               }else{
                    $reply='';

               }

            

            //make an if statement to showe a reply

        $content.='
            <div class="discusionPostDiv">
            <img src="skins/unesco_oer/images/user.jpg" width="40" height="40" class="discussionImage">
            <a href="" class="greenTextBoldLink">'.$link->show().'</a>
            <br>
            Posts: 3
            '.$reply.'
              </div>';
        }
        return $content;
        
    }

    function discussion($topics, $forumId) {
        $content;

        foreach ($topics as $topic) {
            $link = new link($this->uri(array('action' => 'viewtopic', 'id' => $topic['topic_id'], 'type' => 'context',"page" =>"10a_tpl.php"), 'forum'));
            $link->link = stripslashes($topic['post_title']);
            $link->show();
            //$datefield=$this->objTranslatedDate->getDifference($topic['lastdate']);
            $post = $this->objPost->getLastPost($forumId);
            $datefield = $this->objDateTime->formatDateOnly($post['datelastupdated']) . ' - ' . $this->objDateTime->formatTime($post['datelastupdated']);

            $content.='
                <div class="tenPixelPaddingLeft">
                <div class="topGroupDiv">
                <div class="paddingContentTopLeftRightBottom">
                <div class="discussionList">
                <h3 class="fontBold">' . $link->show() . '</h3>
                <div class="textNextToRightFloatedImage">
                </div>
                Posts:12<br>
                Last posted: ' . $datefield . '
                </div>

                    </div>
                </div>
                <br><br><br>
                </div>
';
        }
        return $content;
    }



    function OerResource($groupid){
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
                $Link->link = '<img src="' . $Thumbnail. ' "alt="Adaptation placeholder" width="45" height="49" class="resourcesImage">';

                $Title = $this->objDbGroups->getAdaptedProductTitle($productID);
                $TittleLink = new link($this->uri(array("action" => 'ViewProduct', 'id' => $productID, "page" => '10a_tpl.php')));
                $TittleLink->link = $Title;


                $content.='<div class="resourcesPostDiv">
                           '.$Link->show().'
                           <h2>'.$TittleLink->show().'</h2>
                           <br>
                           Adapted in : <a href="" class="greyTextLink">'.$language.'</a>
                          </div>';


            }
            echo $content;
        }
    }


    function communitiesGroupList(){
        $product = $this->newObject('product', 'unesco_oer');
        $product->loadProduct($productID);
        $language = $product->getLanguageName();
        $institution = $product->getInstitutionName();
        $institutionId = $product->getInstitutionID();
        $Thumbnail = $product->getThumbnailPath();

        $AdaptProductLink= $Link = new link($this->uri(array("action" =>'adaptProduct', 'productID' => $productID, "page" => '10a_tpl.php')));
        $AdaptProductLink= $this->objLanguage->languageText('mod_unesco_oer_product_make_adaptation', 'unesco_oer');
        $AdaptProductLink->cssClass = "adaptationLinks";

        $content;
        $content.='<div class="listAdaptations tenPixelTopPadding">
                        <div class="floaLeftDiv">

                            <img src="'.$Thumbnail.' alt="Adaptation placeholder">
                        </div>
                    <div class="rightColumInnerDiv">
                      <div class="blueListingHeading">Model Curricula for Journalism Education</div>
                            Adapted in <a href="#" class="productAdaptationGridViewLinks">'.$language.'</a>
                            <br>
                            <div class="listingAdaptationsLinkAndIcon">

                               <img src="skins/unesco_oer/images/small-icon-make-adaptation.png" alt="New mode" width="18" height="18" class="smallLisitngIcons">
                               <div class="textNextToTheListingIconDiv"><a href="" class="adaptationLinks">'.$AdaptProductLink->show().'</a></div>
                   	  		</div>

               		  <div class="listingAdaptationsLinkAndIcon">
                             <img src="skins/unesco_oer/images/small-icon-bookmark.png" alt="Bookmark" width="18" height="18" class="smallLisitngIcons">
                             <div class="textNextToTheListingIconDiv"><a href="#" class="bookmarkLinks">bookmark</a></div>
                      </div>
                        </div>

                    </div>';
    }
    
    
    
    public function Linkinstitution($id,$onestepid){
        

        
$this->loadClass('form', 'htmlelements');
$this->loadClass('htmlheading', 'htmlelements');
$this->loadClass('htmltable','htmlelements');
$this->loadClass('textinput','htmlelements');
$this->loadClass('label', 'htmlelements');
$this->loadClass('fieldset','htmlelements');


$user_current_membership = $this->objDbGroups->getGroupInstitutions($id);
$currentMembership = array();
$availablegroups = array();
$availablebox = array();
$memberbox = array();


$groups = $this->objDbInstitution->getAllInstitutions();


foreach ($groups as $group) {
    array_push($availablegroups, $group['id']);
}

foreach ($user_current_membership as $membership) {
     array_push($currentMembership, $membership['institution_id']);
    
}

//var_dump($availablegroups);

  $results = array_diff($availablegroups,$currentMembership);
  //var_dump($results);
  
  foreach ($results as $result){ 
      foreach($groups as $group){
                                
          if ($group['id'] == $result){ 
              array_push($availablebox, $group);
          }
      }
  }
  
  foreach ($user_current_membership as $membership){ 
      foreach($groups as $group){
                                
          if ($group['id'] == $membership['institution_id']){ 
              array_push($memberbox, $group);
          }
      }
  }
  






// setup and show heading
$header = new htmlheading();
$header->type = 1;
$header->str = $this->objLanguage->languageText('mod_unesco_oer_group_link_institution', 'unesco_oer');
echo '<div style="padding:10px;">'.$header->show();



$uri=$this->uri(array('action'=>'linkInstitution' , 'id' => $id, 'productID' => $onestepid ));
$form = new form ('register', $uri);
$table = $this->newObject('htmltable', 'htmlelements');
$table->width = '100%';
$table->border = '0';
$tableable->cellspacing = '0';
$table->cellpadding = '2';

$table = $this->newObject('htmltable', 'htmlelements');




//foreach ($groups as $group) {
//    if (count($user_current_membership) > 0) {
//        foreach ($user_current_membership as $membership) {
//            if($membership['institution_id'] !=NULL){echo ' TTTTTTTTTTTTTTTTTTTTT';
//            if (strcmp($group['id'], $membership['institution_id']) == 0 ) {
//                array_push($currentMembership, $group);
//            }else {
//                array_push($availablegroups, $group);
//            }} 
//        } 
//    } else {
//        array_push($availablegroups, $group); 
//    }
//}










$objSelectBox = $this->newObject('selectbox','htmlelements');
$objSelectBox->create( $form, 'leftList[]', 'Available Institutionss', 'rightList[]', 'Chosen Institutions' );
$objSelectBox->insertLeftOptions(
                        $availablebox,
                        'id',
                        'name' );
$objSelectBox->insertRightOptions(
                               $memberbox,
                               'id',
                               'name');

$tblLeft = $this->newObject( 'htmltable','htmlelements');
$objSelectBox->selectBoxTable( $tblLeft, $objSelectBox->objLeftList);
//Construct tables for right selectboxes
$tblRight = $this->newObject( 'htmltable', 'htmlelements');
$objSelectBox->selectBoxTable( $tblRight, $objSelectBox->objRightList);
//Construct tables for selectboxes and headings
$tblSelectBox = $this->newObject( 'htmltable', 'htmlelements' );
$tblSelectBox->width = '90%';
$tblSelectBox->startRow();
    $tblSelectBox->addCell( $objSelectBox->arrHeaders['hdrLeft'], '100pt' );
    $tblSelectBox->addCell( $objSelectBox->arrHeaders['hdrRight'], '100pt' );
$tblSelectBox->endRow();
$tblSelectBox->startRow();
    $tblSelectBox->addCell( $tblLeft->show(), '100pt' );
    $tblSelectBox->addCell( $tblRight->show(), '100pt' );
$tblSelectBox->endRow();

$table->startRow();
$table->addCell($this->objLanguage->languageText('mod_unesco_oer_group_institution', 'unesco_oer'));
$table->addCell($tblSelectBox->show());
$table->endRow();


$fieldset = $this->newObject('fieldset', 'htmlelements');
$fieldset->legend = $this->objLanguage->languageText('mod_unesco_oer_group_fieldset4', 'unesco_oer');
$fieldset->contents = $table->show();

$form->addToForm($fieldset->show());
$form->addToForm('<br />');

$button = new button ('submitform',$this->objLanguage->languageText('mod_unesco_oer_group_save_button', 'unesco_oer'));
$action = $objSelectBox->selectAllOptions($objSelectBox->objRightList )." SubmitProduct();";
$button->setOnClick('javascript: ' . $action);

$Cancelbutton = new button ('cancelform',$this->objLanguage->languageText('mod_unesco_oer_group_cancel_button', 'unesco_oer'));

$form->extra = 'enctype="multipart/form-data"';
$form->addToForm('<p align="right">'.$button->show().$Cancelbutton->show().'</p>');

echo $form->show();
echo '</div> ';

    }
    
    
 
    function hasMemberPermissions() {
        $userId = $this->objUser->userid();
        $groupId = $this->objGroups->getId('Members');
        return $this->objGroupAdminModel->isGroupMember($userId, $groupId) || $this->objUser->isAdmin() || $this->hasEditorPermissions();
    } 

   
    
    
    
    



}
?>