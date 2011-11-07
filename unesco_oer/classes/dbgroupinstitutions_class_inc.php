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

class dbgroupinstitutions extends dbtable {

    function init() {
        parent::init('tbl_unesco_oer_group_institutions');
    }

    function add_group_institutions($groupid, $institutionid) {
        $data = array(
            'group_id' => $groupid,
            'institution_id' => $institutionid
        );
        $this->insert($data);
    }
    
    function removegrouplink($intid, $groupid) {
        $sql = "DELETE  FROM  tbl_unesco_oer_group_institutions WHERE institution_id='$intid' AND group_id='$groupid'";
        return $this->getArray($sql);
    }

    function add_group($groupid) {
        $data = array(
            'group_id' => $groupid,
            'institution_id' => ''
        );
        $this->insert($data);
    }

//To get all group linked institutions
    function getLinkedInstitutions($groupid) {
        $sql = "SELECT * FROM tbl_unesco_oer_group_institutions WHERE group_id='$groupid'";
        return $this->getArray($sql);
    }

    function check_availableGroupInstitution($groupid, $institutionid) {
        $sql = "SELECT * FROM tbl_unesco_oer_group_institutions WHERE group_id='$groupid' and institution_id='$institutionid'";
        $array = $this->getArray($sql);
        if (count($array) > 0) {
            return TRUE;
        }
    }

}
?>