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

class dbresourcetypes extends dbtable {
    public $objUser;

    function init() {
        parent::init("tbl_unesco_oer_resource_types");
        $objUser = $this->getObject('user', 'security');
    }

    function getResourceTypes() {
        $sql = "select * from tbl_unesco_oer_resource_types";
        return $this->getArray($sql);
    }

    function addType($description, $table) {
        $data = array(
            'description' => $description,
            'table_name' => $table
        );

        $this->insert($data);
        // Prepare to add context to search index
        $objIndexData = $this->getObject('indexdata', 'search');

        $saveDate = date('Y-m-d H:M:S');
        $url = $this->uri(array('action' => '4', 'institutionId' => ''), 'contextcontent');

        $objTrimStr = $this->getObject('trimstr', 'strings');
        $teaser = $objTrimStr->strTrim(strip_tags($description), 500);

        $userId = 2;
//        $this->objUser->userId();
        $module = 'unesco_oer';

        // Todo - Set permissions on entering course, e.g. iscontextmember.
        $permissions = NULL;

        $objIndexData->luceneIndex(NULL, $saveDate, $url, $name, NULL, $teaser, $module, $userId, NULL, NULL, NULL);
    }

    function getResourceTypeDescription($id) {
        $row = $this->getRow('id', $id);
        return $row['description'];
    }

    function getResourceTypeTable($id) {
        $row = $this->getRow('id', $id);
        return $row['table_name'];
    }

    function getResourceTypeById($id) {
        $sql = "SELECT * FROM tbl_unesco_oer_resource_types WHERE id = '$id'";
        $type = $this->getArray($sql);
        return $type[0];
    }

    function updateType($id, $description, $table_name) {
        $data = array(
            'id' => $id,
            'description' => $description,
            'table_name' => $table_name
        );

        $this->update('id', $id, $data, 'tbl_unesco_oer_resource_types');
    }

    function deleteType($id) {
        $sql = "DELETE FROM tbl_unesco_oer_resource_types WHERE id='$id'";
        $this->getArray($sql);
    }

}
?>