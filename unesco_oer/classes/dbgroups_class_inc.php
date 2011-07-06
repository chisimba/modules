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

class dbgroups extends dbtable {

    function init() {
        parent::init("tbl_unesco_oer_groups");
    }

    function getAllGroups() {
        $sql = "select * from tbl_unesco_oer_groups";
        return $this->getArray($sql);
    }

    function getGroupInfo($groupid) {
        $sql = "SELECT * FROM tbl_unesco_oer_groups WHERE id='$groupid'";
        return $this->getArray($sql);
    }

    function deleteGroup($groupid) {
        $sql = "DELETE FROM tbl_unesco_oer_groups WHERE id='$groupid'";
        $this->getArray($sql);
    }

    function editgroup($id, $name, $email, $address, $city, $state, $country, $postalcode, $website, $institution, $loclat, $loclong, $description, $thumbnail) {
        if ($name != '') {
            return $this->update(
                    'id',
                    $id,
                    $data = array(
                'id' => $id,
                'name' => $name)
            );
        }

        if ($email != '') {
            return $this->update(
                    'id',
                    $id,
                    $data = array(
                'id' => $id,
                'email' => $email)
            );
        }


        if ($address != '') {
            return $this->update(
                    'id',
                    $id,
                    $data = array(
                'id' => $id,
                'address' => $address)
            );
        }

        if ($city != '') {
            return $this->update(
                    'id',
                    $id,
                    $data = array(
                'id' => $id,
                'city' => $city)
            );
        }

        if ($state != '') {
            return $this->update(
                    'id',
                    $id,
                    $data = array(
                'id' => $id,
                'state' => $state)
            );
        }

        if ($country != '') {
            return $this->update(
                    'id',
                    $id,
                    $data = array(
                'id' => $id,
                'country' => $country)
            );
        }


        if ($postalcode != '') {
            return $this->update(
                    'id',
                    $id,
                    $data = array(
                'id' => $id,
                'postalcode' => $postalcode)
            );
        }

        if ($website != '') {
            return $this->update(
                    'id',
                    $id,
                    $data = array(
                'id' => $id,
                'website' => $website)
            );
        }

        if ($institution != '') {
            return $this->update(
                    'id',
                    $id,
                    $data = array(
                'id' => $id,
                'LinkedInstitution' => $institution)
            );
        }

        if ($loclat != '') {
            return $this->update(
                    'id',
                    $id,
                    $data = array(
                'id' => $id,
                'loclat' => $loclat)
            );
        }

        if ($loclong != '') {
            return $this->update(
                    'id',
                    $id,
                    $data = array(
                'id' => $id,
                'loclong' => $loclong)
            );
        }

        if ($description != '') {
            return $this->update(
                    'id',
                    $id,
                    $data = array(
                'id' => $id,
                'description' => $description)
            );
            if ($thumbnail != '') {
                return $this->update(
                        'id',
                        $id,
                        $data = array(
                    'id' => $id,
                    'thumbnail' => $thumbnail)
                );
            }
        }
    }

//
//    function addGroup($name, $loclat, $loclong, $thumbnailPath, $country = NULL) {
//        $data = array(
//            'name' => $name,
//            'loclat' => $loclat,
//            'loclong' => $loclong,
//            'country' => $country,
//            'thumbnail' => $thumbnailPath
//        );
//
//        $this->insert($data);
//    }
    // PBROBLEM PROBLEM PROBLEM   #############################################
    // Does a User need necessarily to know the Latitude and Longitude of the group Location?
    //
    function saveNewGroup($name, $email, $address, $city, $state, $country, $postalcode, $website, $institution, $loclat, $loclong, $description, $thumbnail) {
        $data = array(
            'name' => $name,
            'email' => $email,
            'address' => $address,
            'city' => $city,
            'state' => $state,
            'country' => $country,
            'postalcode' => $postalcode,
            'website' => $website,
            'LinkedInstitution' => $institution,
            'loclat' => $loclat,
            'loclong' => $loclong,
            'description' => $description,
            'thumbnail' => $thumbnail
        );
        $this->insert($data);
    }

    /*
     * This function take a groupId and return its latitude
     * @param $GroupID
     * return int
     */

    function getGroupLatitude($name) {
        $sql = "SELECT * FROM tbl_unesco_oer_groups WHERE name='$name'";
        $Group = $this->getArray($sql);
        return $Group[0]['loclat'];
    }

    /*
     * This function takes a groupId and return its longitude
     * @param $GroupID
     * return int
     */

    function getGroupLongitude($name) {
        $sql = "SELECT * FROM tbl_unesco_oer_groups WHERE name='$name'";
        $Group = $this->getArray($sql);
        return $Group[0]['loclong'];
    }

    /*
     * This function take a groupId an return the group name
     * @param $GroupId
     * return name
     */

    function getGroupName($GroupID) {
        $sql = "SELECT * FROM tbl_unesco_oer_groups WHERE id='$GroupID'";
        $GroupName = $this->getArray($sql);
        return $GroupName[0]['name'];
   
    }

    function getLinkedInstitution($groupid){
        $sql = "SELECT * FROM tbl_unesco_oer_groups WHERE id='$groupid'";
        $linkedInstitution = $this->getArray($sql);
        return $linkedInstitution[0]['linkedinstitution'];
     }



    /*
     * This function convert the latitude and longitude and map it on a map
     * @lat
     * @lon
     * @param $width
     * @param $height
     * return array
     */

    function getlocationcoords($lat, $lon, $width, $height) {
        $x = (($lon + 180) * ($width / 360));
        $y = ((($lat * -1) + 90) * ($height / 180));
        return array("x" => round($x), "y" => round($y));
    }

    /*
     * This function is  responsuble to draw the image
     * @im
     * @lat
     * @lat
     */

    function MapHandler($im, $lat, $long) {
        if (empty($long)
            )$long = 28.0316;
        if (empty($lat)
            )$lat = -26.19284;
        $red = imagecolorallocate($im, 255, 0, 0);
        $scale_x = imagesx($im);
        $scale_y = imagesy($im);
        $pt = $this->getlocationcoords($lat, $long, $scale_x, $scale_y);
        imagefilledrectangle($im, $pt["x"] - 2, $pt["y"] - 2, $pt["x"] + 2, $pt["y"] + 2, $red);
        header("Content-Type: image/png");
    }

    public function getGroupThumbnail($name) {
        $sql = "SELECT * FROM tbl_unesco_oer_groups WHERE name = '$name'";
        $thumbnail = $this->getArray($sql);
        return $thumbnail[0];
    }

    public function isGroup($name) {
        //$sql = "IF EXISTS(SELECT * FROM tbl_unesco_oer_institution WHERE name = '$name')";

        $sql = "SELECT * FROM tbl_unesco_oer_groups WHERE name = '$name'";
        if (count($this->getArray($sql)) != 0) {
            //return true;
            return count($this->getArray($sql));
        } else {
            return false;
        }
    }

        
    }
?>

