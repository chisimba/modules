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

    function getGroups() {
        $sql = "select name from tbl_unesco_oer_groups";
        return $this->getArray($sql);
    }

    function addGroup($name, $loclat, $loclong, $thumbnailPath) {
        $data = array(
            'name' => $name,
            'loclat' => $loclat,
            'loclong' => $loclong,
            'thumbnail' => $thumbnailPath
        );

        $this->insert($data);
    }

     /*
      * This function take a groupId and return its latitude
      * @param $GroupID
      * return int
      */

     function  getGroupLatitude($name){
        $sql = "SELECT * FROM tbl_unesco_oer_groups WHERE name='$name'";
        $Group=$this->getArray($sql);
        return $Group[0]['loclat'];
    }

    /*
     * This function takes a groupId and return its longitude
     * @param $GroupID
     * return int
     */
    function getGroupLongitude($name){
        $sql = "SELECT * FROM tbl_unesco_oer_groups WHERE name='$name'";
        $Group=$this->getArray($sql);
        return $Group[0]['loclong'];
    }


    /*
     * This function get the Id's of all the group
     * return array
     */
   function getAllGroups(){
        $sql=" select id from tbl_unesco_oer_groups";
        $GroupNames=$this->getArray($sql);
        return $GroupNames;
    }

   /*
    * This function take a groupId an return the group name
    * @param $GroupId
    * return name
    */
    function getGroupName($GroupID){
        $sql = "SELECT * FROM tbl_unesco_oer_groups WHERE id='$GroupID'";
        $GroupName=$this->getArray($sql);
        return $GroupName[0]['name'];
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
        if (empty($long))$long =28.0316;
        if (empty($lat))$lat =-26.19284;
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
            return true;
        } else {
            return false;
        }
    }





}

?>

