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

class dbusergroups extends dbtable {

    function init() {
        parent::init("tbl_unesco_oer_user_groups");
    }

    function joingroup($id, $groupid) {
        $data = array(
            'id' => $id,
            'groupid' => $groupid
        );
        $this->insert($data);
    }

    function adduser($id) {
        $data = array(
            'id' => $id,
            'groupid' => ''
        );
        $this->insert($data);
    }

//to get all users in  a particular group
    function getGroupUser($groupid) {
        $sql = "SELECT * FROM tbl_unesco_oer_user_groups WHERE groupid='$groupid'";
        return $this->getArray($sql);
    }

    //To get the number of group users
    function groupMembers($groupid) {
        $sql = "SELECT * FROM tbl_unesco_oer_user_groups WHERE groupid='$groupid'";
        $array = $this->getArray($sql);
        return count($array);
    }

    // get a user list of groups that he belong
    function getUserGroups($id) {
        $sql = "SELECT * FROM tbl_unesco_oer_user_groups WHERE id='$id'";
        return $this->getArray($sql);
    }

    // when a user what to leave a particular group
    function leavegroup($id, $groupid) {
        $sql = "DELETE  FROM  tbl_unesco_oer_user_groups WHERE id='$id' AND groupid='$groupid'";
        return $this->getArray($sql);
    }

    //TO get user that who don't belong to any group
    function getNonGroupUsers() {
        $sql = "SELECT * FROM tbl_unesco_oer_user_groups WHERE groupid='NULL'";
        return $this->getArray($sql);
    }

    //Check that a user belongs to a  group

    function ismemberOfgroup($id, $groupid) {
        $sql = "SELECT * FROM tbl_unesco_oer_user_groups WHERE groupid='$groupid' AND id='$id'";
        $array = $this->getArray($sql);
        if (count($array) > 0) {
            return 1; //TRUE
        } else {
            return 0;
        }
    }

    function check_availableUserGroup($userId, $groupid) {
        $sql = "SELECT * FROM tbl_unesco_oer_user_groups' WHERE id='$userId' and groupid='$groupid'";
        $array = $this->getArray($sql);
        if (count($array) > 0) {
            return TRUE;
        }
    }

}
?>






