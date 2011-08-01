<?php

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

$this->loadClass('dbgroups','unesco_oer');
// load user db class




class grouplist extends object {

    public $groups;
    public $user;

    public function init()
     {
        parent::init();
        $this->objDbgroups = new dbgroups();
        //neee db user
        }


    public function getGroups(){
        return $this->objDbgroups->getGroups();
    }


    public function deleteGroup($groupid){
        return $this->objDbgroups->deleteGroup($groupid);
        }

    public function addGroup($name, $loclat, $loclong, $thumbnailPath, $country = NULL){
        return  $this->objDbgroups->addGroup($name, $loclat, $loclong, $thumbnailPath, $country = NULL);
    }

    //must include thumbnail
    public function editgroup($id,$puid,$loclat,$loclong,$name){
        return  $this->objDbgroups->editgroup($id, $puid, $loclat, $loclong, $name);
    }
   
    public function search(){

    }
    
     

    public function show(){

    }

    public function getUsers(){

    }

    public function getUsers(){
        
    }

    public function getDescription(){
        return $this->objGroups;

    }

    public function getLinkedList(){

    }

    public function getLinkedDiscussion(){

    }

    public function leaveGroup($userid){

    }

    public function viewGroupCalender(){

    }

}

?>
