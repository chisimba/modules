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


    function addUserInfo($title, $surname,$username, $password, $email, $firstname, $sex, $country,$cellnumber) {
        $this->objAdmin->addUser($userid = "$password", $username, $password, $title, $firstname, $surname, $email, $sex, $country, $cellnumber, $staffnumber = '', $accountType = 'useradmin', $accountstatus = '1');
    }

    function addUserInfoExtra($USERID=NULL,$username, $birthdate, $address, $city, $state, $postaladdress, $organisation, $jobtittle, $TypeOccapation, $WorkingPhone, $DescriptionText, $WebsiteLink, $GroupMembership) {
        $data = array(
            'userid' => $USERID,
            'birthday' => $birthdate,
            'address' => $address,
            'city' => $city,
            'state' => $state,
            'postaladdress' => $postaladdress,
            'organisation/company' => $organisation,
            'jobtittle' => $jobtittle,
            'typeoccapation' => $TypeOccapation,
            'workingphone' => $WorkingPhone,
            'description' => $DescriptionText,
            'websitelink' => $WebsiteLink,
            'groupmembership' => $GroupMembership
        );
        $this->insert($data);
       
    }


    function editUserInfo($id,$staffnumber,$surname,$title,$userId,$username,$password,$email,$firstname,$sex,$country,$birthdate, $address, $city, $state, $postaladdress, $organisation, $jobtittle,$WorkingPhone, $cellnumber, $DescriptionText, $WebsiteLink, $GroupMembership) {
        $this->objAdmin->updateUserDetails($id, $username, $firstname, $surname, $title, $email, $sex, $country, $cellnumber, $staffnumber, $password, $accountType='useradmin', $accountstatus='1');
        $data = array(
            'birthday' => $birthdate,
            'address' => $address,
            'city' => $city,
            'state' => $state,
            'postaladdress' => $postaladdress,
            'organisation/company' => $organisation,
            'jobtittle' => $jobtittle,
            'typeoccapation' => $TypeOccapation,
            'workingphone' => $WorkingPhone,
            'description' => $DescriptionText,
            'websitelink' => $WebsiteLink,
            'groupmembership' => $GroupMembership
        );
        $this->update($data);
        //$this->objAdmin->updateUserDetails($id, $username, $firstname, $surname, $title, $email, $sex, $country, $cellnumber, $staffnumber, $password, $accountType='useradmin', $accountstatus='1');
    }

    function getAllUser(){
         $sql = "select * from tbl_users";
         return $this->getArray($sql);
    }
}

?>


    
    
   

