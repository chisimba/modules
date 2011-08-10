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

    public function init(){
         $this->ObjDbUserGroups= $this->getObject("dbusergroups", "unesco_oer");
         $this->objDbGroups= $this->getObject("dbgroups", "unesco_oer");
         $this->objUser=$this->getObject('user', 'security');
         $this->objUseExtra = $this->getObject("dbuserextra","unesco_oer");
         $this->objLanguagecode=$this->getObject('languagecode', 'language');



    }



  public function groupPerPage(){
      $dropdown=new dropdown('group_per_page');
      $groups=$this->objDbGroups->getAllGroups();

      if(count($groups)>=9){
          for($i=1;$i<10;$i++){
          $dropdown->addOption($i);}
     }else{
         for($i=1;$i<=count($groups);$i++){
            $dropdown->addOption($i);}
      }
    $content.=' <div class="moduleHeader darkBlueText"><img src="skins/unesco_oer/images/icon-filter-items-per-page.png" alt="Items per page" class="modulesImages">Groups per page</div>
                <div class="blueBackground">
                	
                    	<option value="">'.$dropdown->show().'</option>
                    </select>
                </div>';


    return $content;

  }




  public function content($group){

      $thumbLink= new link($this->uri(array("action" => '11a','id'=>$group['id'])));
  
      $thumbLink->link='<img src="'.$group['thumbnail'] .'" alt="Adaptation placeholder" width="45" height="49" class="smallAdaptationImageGrid">';
      //$joinLink;
       //$objUser = $this->getObject('user', 'security');
        //$imageBottomFlag = $this->objUser->isLoggedIn() ? $adaptLink->show() : '';
     
  
              $joinGroupLink = new link($this->uri(array('action' =>"joingroup", 'id' => $group['id'])));
              $joinGroupLink->link='Join';
              $joinGroupLink->cssClass = 'joingroup';
            


      $content.='
          <div class="whiteBackgroundBox">
         '.$thumbLink->show().'
                            <div class="groupGridViewHeading greenText">
                            '.$group['name'] .' </div>
                            <div class="groupMemberAndJoinLinkDiv">
                            	   <span class="greenText">Members :</span>'. $this->ObjDbUserGroups->groupMembers($group['id']) .'<br><br>
                             <!--    <a href="#"><img src="skins/unesco_oer/images/icon-join-group.png" alt="Join Group" width="18" height="18" class="smallLisitngIcons"></a>
               				 	<div class="linkTextNextToJoinGroupIcons"><a href="#" class="greenTextBoldLink">-->

   <br/>
               <a class="greyListingHeading">'.$this->objLanguagecode->getName($group['country']).'</a> |
                             <a class="greyListingHeading">'.$group['city'].'</a> |
                                           <a class="greyListingHeading">'.$group['state'].'</a> |
                                                         <a class="greyListingHeading">'.$group['email'].'</a>
                                                             <br/>
           </div>
'.//$joinGroupLink->show().'</a></div>
              '</a></div>
                            </div>
                            </div>
                            

                            ';
      return $content;
  }




  public function populateListView(){
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

public function groupMembers($groupid){
    //$NoofMembers=count($this->ObjDbUserGroups->getGroupUser($groupid));
    $arrays=$this->ObjDbUserGroups->getGroupUser($groupid);
    foreach( $arrays as $array){
        $firstname=$this->objUseExtra->getUserSurname($array['id']);
        $surname=$this->objUseExtra->getUserfirstname($array['id']);
        $content.='         <div class="memberList">
                            <div class="communityRelatedInfoIcon"><img src="skins/unesco_oer/images/icon-member.png" width="18" height="18"></div>
                            <div class="memberIconText">'.$firstname." ".$surname.' (<span class="greenText fontBold">Group Administrator</span>)</div>

                        </div>';
         echo $content;
         }
}

public function groupAdaptation($groupid){
    $arrays=$this->objDbGroups->getGroupProductadaptation($groupid);
    foreach($arrays as $array){
        $productID=$array['product_id'];
        $Thumbnail=$this->objDbGroups->getAdaptedProductThumbnail($productID);
        $Title=$this->objDbGroups->getAdaptedProductTitle($productID);


    $content.='              <div class="discussionList">
                            <img src='.$Thumbnail.' alt="Adaptation placeholder" width="45" height="49" class="smallAdaptationImageGrid">
                            <div class="textNextToGroupIcon">
                             <h2>'.$Title.'</h2>
                             Institution : <a href="#" class="greyTextLink"></a><br>
                             Adapted in : <a href="#" class="bookmarkLinks"></a>
                            </div>
                        </div>';
    echo $content;
        }
}






}

?>



<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript">

jQuery(document).ready(function(){

    jQuery("a[class=joingroup]").click(function(){

        var r=confirm( "Are you sure you want to join this group?");
        if(r== true){
            window.location=this.href;
        }
        return false;
    }
);

}
);

jQuery(document).ready(function(){

    jQuery("a[class=memberofgroup]").click(function(){

        var r=confirm( "Your are a member of this group\n you can not join again!!!");
        if(r== true){
            window.location=this.href;
        }
        return false;
    }


);
}
);


</script>

