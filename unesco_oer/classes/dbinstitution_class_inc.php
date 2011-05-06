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
        parent::init("tbl_unesco_oer_institution");
    }

    function getInstitutions() {
        $sql = "select * from tbl_unesco_oer_institution";
        return $this->getArray($sql);
    }

    function addInstitution($name, $loclat, $loclong, $thumbnailPath, $type = NULL, $country = NULL) {
        $data = array(
            'name' => $name,
            'loclat' => $loclat,
            'loclong' => $loclong,
            'country'=> $country,
            'type' => $type,
            'thumbnail' => $thumbnailPath
        );

        $this->insert($data);
    }

    //to get an institution latitude
    function  getInstitutionLatitude($InstitutionNameID){
        $sql = "SELECT * FROM tbl_unesco_oer_institution WHERE id='$InstitutionNameID'";
        $Institution=$this->getArray($sql);
        return $Institution[0]['loclat'];
    }

// to get an institution longitude

    function getInstitutionLongitude($name){
        $sql = "SELECT * FROM tbl_unesco_oer_institution WHERE name='$name'";
        $Institution=$this->getArray($sql);
        return $Institution[0]['loclong'];
    }
   
   function getAllInstitution(){
        $sql=" select * from tbl_unesco_oer_institution";
        $InstitutionNames=$this->getArray($sql);
        return $InstitutionNames;
    }

    function getInstitutionName($name){
        $sql = "SELECT * FROM tbl_unesco_oer_institution WHERE name='$name'";
        $InstitutionName=$this->getArray($sql);
        return $InstitutionName[0]['name'];
    }

 

    //To handle the latitude and longitude to feet on the map
    function getlocationcoords($lat, $lon, $width, $height) {
        $x = (($lon + 180) * ($width / 360));
        $y = ((($lat * -1) + 90) * ($height / 180));
        return array("x" => round($x), "y" => round($y));
    }
      
    // function is responsible to dispaly the map and its images
    function MapHandler($im,$lat,$long) {
       if (empty($long))$long = 28.0316;
        if (empty($lat))$lat = -26.19284;
        $red = imagecolorallocate($im, 255, 0,0);
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

    public function getInstitutionThumbnail($name) {
        //TODO Ntsako Handle the situation where the institution is not in the table
        $sql = "SELECT * FROM tbl_unesco_oer_institution WHERE name = '$name'";
        $thumbnail = $this->getArray($sql);
        return $thumbnail[0];
    }

    public function isInstitution($name) {
        //$sql = "IF EXISTS(SELECT * FROM tbl_unesco_oer_institution WHERE name = '$name')";

        $sql = "SELECT * FROM tbl_unesco_oer_institution WHERE name = '$name'";
        if (count($this->getArray($sql)) != 0) {
            //return true;
             return count($this->getArray($sql));
        } else {
            return false;
        }
        //return count($this->getArray($sql));
    }


    //this function delete  a record

    function deleteInstitution($puid,$name){
        $sql="DELETE FROM tbl_unesco_oer_institution WHERE puid='$puid' AND name='$name'";
        $this->getArray($sql);
        }

     //this function edit the instituin name
        //TODO MUST ALSO EDIT THUMBNAIL
    function editInstitution($id,$puid,$loclat,$loclong,$name){
       return $this->update(
                    'puid',
                     $puid,
                     array('loclat' => $loclat,'loclong'=>$loclong,'name'=>$name,'id'=>$id)
            );
        }

    function getInstitutionByID($id){
        //TODO change function so it can identify if the $id is a puid or a normal id
        //TODO this function currently fails when you have more than 99 intitutions
        //If searching by id
        $sql = '';
        if (strlen($id)>2){
            $sql = "select * from $this->_tableName where id = '$id'";
        }  else {
            //If searching by puid
            $sql = "select * from $this->_tableName where puid = '$id'";
        }
        $products = $this->getArray($sql);
        return $products[0]; //TODO add error handler for non unique ID.
    }

    /*
    * This function takes a institution name and returns the first type ID if found
    * @param $Name
    * return typeID
    */
    function findInstitutionTypeID($Name){
        $sql = "SELECT * FROM $this->_tableName WHERE name='$Name'";
        $institution=$this->getArray($sql);
        return $institution[0]['type'];
    }

    /*
    * This function takes a institution name and returns the country of the first
    * institution found
    * @param $Name
    * return typeID
    */
    function getInstitutionCountry($name){
        $sql = "SELECT * FROM $this->_tableName WHERE name='$name'";
        $institution=$this->getArray($sql);
        return $institution[0]['country'];
    }
}

?>
