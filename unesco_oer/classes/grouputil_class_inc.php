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

    }



  public function groupPerPage(){
      $dropdown=new dropdown('group_per_page');
      for($i=1;$i<16;$i++){
          $dropdown->addOption($i);
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



      $content.='
          <div class="whiteBackgroundBox">
         '.$thumbLink->show().'
                            <div class="groupGridViewHeading greenText">
                            '.$group['name'] .' </div>
                            <div class="groupMemberAndJoinLinkDiv">
                            	<span class="greenText">Members :</span>'. $this->ObjDbUserGroups->groupMembers($group['id']) .'<br><br>
                                <a href="#"><img src="skins/unesco_oer/images/icon-join-group.png" alt="Join Group" width="18" height="18" class="smallLisitngIcons"></a>
               				 	<div class="linkTextNextToJoinGroupIcons"><a href="#" class="greenTextBoldLink">Join</a></div>
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






}

?>
