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

class dbinstitution extends dbtable {

    function init() {
        parent::init("tbl_unesco_oer_institutions");
    }

    /**
     * Returns an array with all institution objects
     * @return <Array<Institution>>
     */
    function getAllInstitutions() {
        $sql = "SELECT * FROM tbl_unesco_oer_institutions";
        return $this->getArray($sql);
    }

    function getInstitutionById($id) {
        $sql = "SELECT * FROM tbl_unesco_oer_institutions WHERE id = '$id'";

        return $this->getArray($sql);
    }

    function addInstitution($name, $description, $type, $country, $address1, $address2, $address3, $zip, $city, $websiteLink, $keyword1, $keyword2, $thumbnail) {
        $data = array(
            'name' => $name,
            'description' => $description,
            'country' => $country,
            'type' => $type,
            'address1' => $address1,
            'address2' => $address2,
            'address3' => $address3,
            'city' => $city,
            'websiteLink' => $websiteLink,
            'keyword1' => $keyword1,
            'keyword2' => $keyword2,
            'zip' => $zip,
            'thumbnail' => $thumbnail
        );

        $this->insert($data);
    }

    //to get an institution latitude
    function getInstitutionLatitude($InstitutionNameID) {
        $sql = "SELECT * FROM tbl_unesco_oer_institutions WHERE id='$InstitutionNameID'";
        $Institution = $this->getArray($sql);
        return $Institution[0]['loclat'];
    }

// to get an institution longitude

    function getInstitutionLongitude($name) {
        $sql = "SELECT * FROM tbl_unesco_oer_institutions WHERE name='$name'";
        $Institution = $this->getArray($sql);
        return $Institution[0]['loclong'];
    }

    function getAllInstitution() {
        $sql = " select * from tbl_unesco_oer_institutions";
        $InstitutionNames = $this->getArray($sql);
        return $InstitutionNames;
    }

    function getInstitutionName($name) {
        $sql = "SELECT * FROM tbl_unesco_oer_institutions WHERE name='$name'";
        $InstitutionName = $this->getArray($sql);
        return $InstitutionName[0]['name'];
    }

    //To handle the latitude and longitude to feet on the map
    function getlocationcoords($lat, $lon, $width, $height) {
        $x = (($lon + 180) * ($width / 360));
        $y = ((($lat * -1) + 90) * ($height / 180));
        return array("x" => round($x), "y" => round($y));
    }

    // function is responsible to dispaly the map and its images
    function MapHandler($im, $lat, $long) {
        if (empty($long)
            )$long = 28.0316;
        if (empty($lat)
            )$lat = -26.19284;
        $red = imagecolorallocate($im, 255, 0, 0);
        $scale_x = imagesx($im);
        $scale_y = imagesy($im);
        $pt = $this->getlocationcoords($lat, $long, $scale_x, $scale_y);
//        if(imagefilledrectangle($im, $pt["x"] - 2, $pt["y"] - 2, $pt["x"] + 2, $pt["y"] + 2, $red)){
//            $abLink = new link($this->uri(array("action" => "3a")));
//            $abLink->link = '';
//            echo $abLink->show();
//        }
        imagefilledrectangle($im, $pt["x"] - 2, $pt["y"] - 2, $pt["x"] + 2, $pt["y"] + 2, $red);
        header("Content-Type: image/png");
    }

    public function getInstitutionThumbnail($thumbnail) {
        //TODO Ntsako Handle the situation where the institution is not in the table
        $sql = "SELECT * FROM tbl_unesco_oer_institutions WHERE thumbnail = '$thumbnail'";
        $thumbnail = $this->getArray($sql);
        return $thumbnail[0];
    }

    public function isInstitution($name) {
        //$sql = "IF EXISTS(SELECT * FROM tbl_unesco_oer_institution WHERE name = '$name')";

        $sql = "SELECT * FROM tbl_unesco_oer_institutions WHERE name = '$name'";
        if (count($this->getArray($sql)) != 0) {
            //return true;
            return count($this->getArray($sql));
        } else {
            return false;
        }
        //return count($this->getArray($sql));
    }

    //this function delete  a record

    function deleteInstitution($puid, $name) {
        $sql = "DELETE FROM tbl_unesco_oer_institutions WHERE puid='$puid' AND name='$name'";
        $this->getArray($sql);
    }

    //this function edit the instituin name
    //TODO MUST ALSO EDIT THUMBNAIL
    function editInstitution($id, $puid, $loclat, $loclong, $name) {
        return $this->update(
                'puid',
                $puid,
                array('loclat' => $loclat, 'loclong' => $loclong, 'name' => $name, 'id' => $id)
        );
    }

    public function getInstitutionCountry($country) {
        //TODO Ntsako Handle the situation where the institution is not in the table
        $sql = "SELECT * FROM tbl_unesco_oer_institutions WHERE country = '$country'";
        $country = $this->getArray($sql);
        return $country[0];
    }

    function findInstitutionTypeID($type) {
        $sql = "SELECT * FROM tbl_unesco_oer_institutions WHERE type = '$type'";
        $type = $this->getArray($sql);
        return $type[0];
    }

}
?>
