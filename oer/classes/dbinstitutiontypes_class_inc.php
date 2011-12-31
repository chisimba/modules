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

class dbinstitutiontypes extends dbtable {

    function init() {
        parent::init("tbl_oer_institution_types");
    }

    function getInstitutionTypes() {
        $sql = "select * from tbl_oer_institution_types";
        return $this->getArray($sql);
    }

    function addType($type) {
        $data = array(
            'type' => $type,
        );

        $this->insert($data);
    }

   /*
    * This function takes a type Id an returns the type type
    * @param $typeId
    * return type
    */
    function getType($typeID){
        $sql = "SELECT * FROM tbl_oer_institution_types WHERE id='$typeID'";
        $type=$this->getArray($sql);
        return $type[0]['type'];
    }

    /*
    * This function takes a type type an returns the first type ID if found
    * @param $typetype
    * return type
    */
    function findTypeID($type){
        $sql = "SELECT * FROM tbl_oer_institution_types WHERE type='$type'";
        $typeID=$this->getArray($sql);
        return $typeID[0]['id'];
    }

}

?>