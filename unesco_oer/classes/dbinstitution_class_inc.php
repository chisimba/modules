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

class dbinstitution extends dbtable
{

    function init()
    {
        parent::init("tbl_unesco_oer_institution");
    }

    function addInstitution($name, $loclat, $loclong, $thumbnailPath)
    {
        $data = array(
            'name' => $name,
            'loclat' => $loclat,
            'loclong' => $loclong,
            'thumbnail' => $thumbnailPath
        );

        $this->insert($data);
    }

    public function getInstitutionThumbnail($name)
    {
        //TODO Ntsako Handle the situation where the institution is not in the table
        $sql = "SELECT thumbnail FROM tbl_unesco_oer_institution WHERE name = '$name'";
        $thumbnail = $this->getArray($sql);
        return $thumbnail[0];
    }

    public function isInstitution($name)
    {
        //$sql = "IF EXISTS(SELECT * FROM tbl_unesco_oer_institution WHERE name = '$name')";

        $sql = "SELECT thumbnail FROM tbl_unesco_oer_institution WHERE name = '$name'";
        if (count($this->getArray($sql)) != 0) {
            return true;
        } else {
            return false;
        }
    }

}
?>
