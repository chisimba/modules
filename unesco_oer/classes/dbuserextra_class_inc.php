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



class dbuserextra extends dbtable{
  
    private $objAdmin;
    private $objUser;

      function init() {
         parent::init("tbl_unesco_oer_userextra");
         $this->objAdmin=$this->getObject('useradmin_model2','security');
         $this->objUser=$this->getObject('user','security');

    }

   
   function SaveNewUser($id, $userId, $birthdate, $address, $city, $state, $postaladdress, $organisation, $jobtittle, $TypeOccapation, $WorkingPhone, $DescriptionText, $WebsiteLink, $GroupMembership) {
        $data = array(
            'id' => $id,
            'userid' => $userId,
            'birthday' => $birthdate,
            'address' => $address,
            'city' => $city,
            'state' => $state,
            'postaladdress' => $postaladdress,
            'organisation' => $organisation,
            'jobtittle' => $jobtittle,
            'typeoccapation' => $TypeOccapation,
            'workingphone' => $WorkingPhone,
            'description' => $DescriptionText,
            'websitelink' => $WebsiteLink,
            'groupmembership' => $GroupMembership
        );
        $this->insert($data);
    }

    function updateUserInfo($id,$userId, $birthdate, $address, $city, $state, $postaladdress, $organisation, $jobtittle, $TypeOccapation, $WorkingPhone, $DescriptionText, $WebsiteLink, $GroupMembership) {
        if ($birthdate !='') {
            $data = array(
                'id' => $id,
                'userid' => $userId,
                'birthday' => $birthdate);
            $this->update('id', $id,$data);
        }
        if ($city !='') {
            $data = array(
                'id' => $id,
                'userid' => $userId,
                'city' => $city);
            $this->update('id', $id, $data);
        }
        if ($address !='') {
            $data = array(
                'id' => $id,
                'userid' => $userId,
                'address' => $address);
            $this->update('id', $id, $data);
        }
        if ($state !='') {
            $data = array(
                'id' => $id,
                'userid' => $userId,
                'state' => $state);
            $this->update('id', $id, $data);
        }
        if ($postaladdress != '') {
            $data = array(
                'id' => $id,
                'userid' => $userId,
                'postaladdress' => $postaladdress);
            $this->update('id', $id, $data);
        }
        if ($organisation != '') {
            $data = array(
                'id' => $id,
                'userid' => $userId,
                'organisation' => $organisation);
            $this->update('id', $id, $data);
        }
        if ($birthdate != '') {
            $data = array(
                'id' => $id,
                'userid' => $userId,
                'birthday' => $birthdate);
            $this->update('id', $id, $data);
        }
        if ($WorkingPhone != '') {
            $data = array(
                'id' => $id,
                'userid' => $userId,
                'workingphone' => $WorkingPhone);
            $this->update('id', $id, $data);
        }
        if ($DescriptionText != '') {
            $data = array(
                'id' => $id,
                'userid' => $userId,
                'description' => $DescriptionText);
            $this->update('id', $id, $data);
            }
        if ($WebsiteLink != '') {
            $data = array(
                'id' => $id,
                'userid' => $userId,
                'websitelink' => $WebsiteLink);
            $this->update('id', $id, $data);
        }
        if ($GroupMembership != '') {
            $data = array(
                'id' => $id,
                'userid' => $userId,
                'groupmembership' => $GroupMembership);
            $this->update('id', $id, $data);
        }
        
           if ($TypeOccapation != '') {
            $data = array(
                'id' => $id,
                'userid' => $userId,
                'typeoccapation' => $TypeOccapation);
            $this->update('id', $id, $data);
        }
        
           if ($jobtittle != '') {
            $data = array(
                'id' => $id,
                'userid' => $userId,
                'jobtittle' => $jobtittle);
            $this->update('id', $id, $data);
        }
    }

    function getUserbyUserIdbyUserID($userid){
        $sql="SELECT * from tbl_users WHERE userid='$userid'";
        $id=$this->getArray($sql);
        return $id[0]['id'];
    }

    function getAllUser(){
         $sql = "select * from tbl_users";
         return $this->getArray($sql);
    }


     function deleteUser($id,$userid){
         $sql="DELETE FROM tbl_unesco_oer_userextra WHERE id='$id' AND userid='$userid'";
         return $this->getArray($sql);
       }
     
     function getUserDetails($id,$userid){
         $sql="SELECT * FROM tbl_unesco_oer_userextra WHERE id='$id' AND userid='$userid'";
         return $this->getArray($sql);
     }

     function searchUserByName($name){
         $sql="SELECT * FROM tbl_users WHERE name='$name'";
         return $this->getArray($sql);
     }

     function searchUserByUsername($username){
         $sql="SELECT * FROM tbl_users WHERE username='$username'";
         return $this->getArray($sql);
    }




    function getLastInsertedId($userId, $username, $password, $title, $firstname, $surname, $email, $sex){
       $sql="SELECT * FROM tbl_users WHERE userid='$userId' AND username='$username'AND title='$title' AND surname='$surname' AND emailaddress='$email' AND  sex='$sex'";
       $id= $this->getArray($sql);
       return $id[0]['id'];
    }

}

?>


    
    
   

