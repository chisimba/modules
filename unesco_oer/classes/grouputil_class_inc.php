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


  public function populategridview($group){
      $content.=' <img src="'.$group['thumbnail'] .'" alt="Adaptation placeholder" width="45" height="49" class="smallAdaptationImageGrid">
                            <div class="groupGridViewHeading greenText">
                            '.$group['name'] .'
                          </div>
                            <div class="groupMemberAndJoinLinkDiv">
                            	<span class="greenText">Members :</span>

                                <br><br>
                                <a href="#"><img src="skins/unesco_oer/images/icon-join-group.png" alt="Join Group" width="18" height="18" class="smallLisitngIcons"></a>
               				 	<div class="linkTextNextToJoinGroupIcons"><a href="#" class="greenTextBoldLink">Join</a></div>
                            </div>

                            ';
      return $content;
  }

  public function populateListView(){

  }








}

?>
