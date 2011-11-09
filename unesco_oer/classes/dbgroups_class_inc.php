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

    function getgroups($start, $limit) {
        $sql = "select * from tbl_unesco_oer_groups limit $start,$limit";
        return $this->getArray($sql);
    }

    function getidbylocation($loclat, $loclong) {
        $sql = "select * from tbl_unesco_oer_groups where loclat = '$loclat' and loclong = '$loclong";

        return $this->getArray($sql);
    }

    function getAllGroups() {
        $sql = "select * from tbl_unesco_oer_groups where parent_id is  NULL";
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

    function getGroupForumId($groupid) {
        $sql =
                "select id from tbl_forum where forum_workgroup = '$groupid'";
        $result = $this->getArray($sql);
        return $result[0]['id'];
    }

    function editgroup($id, $name, $email, $address, $city, $state, $country, $postalcode, $website, $institution, $loclat, $loclong, $description, $thumbnail, $description_one, $description_two, $description_three, $description_four) {
        if ($name != '') {
            $data = array(
                'id' => $id,
                'name' => $name);
            $this->update('id', $id, $data);
        }

        if ($email != '') {
            $data = array(
                'id' => $id,
                'email' => $email);
            $this->update(
                    'id', $id, $data
            );
        }

        if ($website != '') {


            $data = array(
                'id' => $id,
                'website' => $website);
            $this->update(
                    'id', $id, $data
            );
        }


        if ($address != '') {

            $data = array(
                'id' => $id,
                'address' => $address);
            $this->update(
                    'id', $id, $data
            );
        }

        if ($city != '') {

            $data = array(
                'id' => $id,
                'city' => $city);
            $this->update(
                    'id', $id, $data
            );
        }

        if ($state != '') {

            $data = array(
                'id' => $id,
                'state' => $state);
            $this->update(
                    'id', $id, $data
            );
        }

        if ($country != '') {

            $data = array(
                'id' => $id,
                'country' => $country);
            $this->update(
                    'id', $id, $data
            );
        }


        if ($postalcode != '') {

            $data = array(
                'id' => $id,
                'postalcode' => $postalcode);
            $this->update(
                    'id', $id, $data
            );
        }



        if ($institution != '') {

            $data = array(
                'id' => $id,
                'linkedInstitution' => $institution);
            $this->update(
                    'id', $id, $data);
        }

        if ($loclat != '') {

            $data = array(
                'id' => $id,
                'loclat' => $loclat);
            $this->update(
                    'id', $id, $data
            );
        }

        if ($loclong != '') {
            $data = array(
                'id' => $id,
                'loclong' => $loclong);
            $this->update(
                    'id', $id, $data
            );
        }

        if ($description != '') {

            $data = array(
                'id' => $id,
                'description' => $description);
            $this->update(
                    'id', $id, $data
            );
        }
        if ($thumbnail != '') {
            $data = array(
                'id' => $id,
                'thumbnail' => $thumbnail);
            $this->update(
                    'id', $id, $data
            );
        }


        if ($description_one != '') {
            $data = array(
                'id' => $id,
                'description_one' => $description_one);
            $this->update(
                    'id', $id, $data
            );
        }



        if ($description_two != '') {
            $data = array(
                'id' => $id,
                'description_two' => $description_two);
            $this->update(
                    'id', $id, $data
            );
        }



        if ($description_three != '') {
            $data = array(
                'id' => $id,
                'description_three' => $description_three);
            $this->update(
                    'id', $id, $data
            );
        }


        if ($description_four != '') {
            $data = array(
                'id' => $id,
                'description_four' => $description_four);
            $this->update(
                    'id', $id, $data
            );
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
    function saveNewGroup($name, $email, $address, $city, $state, $country, $postalcode, $website, $institution, $loclat, $loclong, $description, $admin, $thumbnail, $description_one, $description_two, $description_three, $description_four) {
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
            'admin' => $admin,
            'thumbnail' => $thumbnail,
            'description_one' => $description_one,
            'description_two' => $description_two,
            'description_three' => $description_three,
            'description_four' => $description_four
        );
        return $this->insert($data);
    }

    /*
     * This function take a groupId and return its latitude
     * @param $GroupID
     * return int
     */

    function getGroupLatitude($groupid) {
        $sql = "SELECT * FROM tbl_unesco_oer_groups WHERE id='$groupid'";
        $Group = $this->getArray($sql);
        return $Group[0]['loclat'];
    }

    /*
     * This function takes a groupId and return its longitude
     * @param $GroupID
     * return int
     */

    function getGroupLongitude($groupid) {
        $sql = "SELECT * FROM tbl_unesco_oer_groups WHERE id='$groupid'";
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

    function getGroupDescription($groupid) {
        $sql = "SELECT * FROM tbl_unesco_oer_groups WHERE id='$groupid'";
        $GroupDescription = $this->getArray($sql);
        return $GroupDescription[0]['description'];
    }

    function getGroupCountry($groupid) {
        $sql = "SELECT * FROM tbl_unesco_oer_groups WHERE id='$groupid'";
        $GroupCountry = $this->getArray($sql);
        return $GroupCountry[0]['country'];
    }

    function getGroupID($groupname) {
        $sql = "SELECT * FROM tbl_unesco_oer_groups WHERE name='$groupname'";
        $groupID = $this->getArray($sql);
        return $groupID[0]['id'];
    }

    function getLinkedInstitution($groupid) {
        $sql = "SELECT * FROM tbl_unesco_oer_groups WHERE id='$groupid'";
        $linkedInstitution = $this->getArray($sql);
        return $linkedInstitution[0]['linkedinstitution'];
    }

    function getThumbnail($groupid) {
        $sql = "select * from tbl_unesco_oer_groups where id='$groupid'";
        $thumbnail = $this->getArray($sql);
        return $thumbnail[0]['thumbnail'];
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
        )
            $long = 28.0316;
        if (empty($lat)
        )
            $lat = -26.19284;
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

    function searchGroupByName($groupname) {
        $sql = "SELECT * FROM tbl_unesco_oer_groups WHERE name = '$groupname'";
        return $this->getArray($sql);
    }

//To get the adaptation of group by groupid

    function getGroupProductadaptation($groupid) {
        $sql = "SELECT a.product_id,p.id from tbl_unesco_oer_product_adaptation_data a, tbl_unesco_oer_products p WHERE a.group_id = '$groupid' and p.id = a.product_id and p.deleted=0 ";
        return $this->getArray($sql);
    }

// To get adapted group adapted product thumbnail
    function getAdaptedProductThumbnail($productid) {
        $sql = "SELECT * FROM tbl_unesco_oer_products WHERE id='$productid'";
        $array = $this->getArray($sql);
        return $array[0]['thumbnail'];
    }

    //To get group adapted product title
    function getAdaptedProductTitle($productid) {
        $sql = "SELECT * FROM tbl_unesco_oer_products WHERE id='$productid'";
        $array = $this->getArray($sql);
        return $array[0]['title'];
    }

    function getGroupUsers($groupname) {
        $sql = "SELECT * FROM tbl_unesco_oer_userextra WHERE groupmembership = '$groupname'";
        return $this->getArray($sql);
    }

    //To get adapted product co ordinates
    function getAdaptedProductLat($productid) {
        $sql = "SELECT * FROM tbl_unesco_oer_product_adaptation_data  WHERE product_id='$productid'";
        $array = $this->getArray($sql);
        $groupid = $array[0]['group_id'];
        return $this->getGroupLatitude($groupid);
    }

    function getAdaptedProductLon($productid) {
        $sql = "SELECT * FROM tbl_unesco_oer_product_adaptation_data  WHERE product_id='$productid'";
        $array = $this->getArray($sql);
        $groupid = $array[0]['group_id'];
        return $this->getGroupLongitude($groupid);
    }

    function getLastInsertId() {
        $array = $this->getLastEntry(Null, $orderField = 'id');
        return $array[0]['id'];
    }

    function getGroupInstitutions($id) {
        $sql = "SELECT * FROM tbl_unesco_oer_group_institutions WHERE group_id='$id'";
        return $this->getArray($sql);
    }

    function getInstitutions($id) {
        $arrayInstitutions = array();
        $sql = "SELECT * FROM tbl_unesco_oer_group_institutions WHERE group_id='$id' AND institution_id IS NOT NULL";
        $institutions = $this->getArray($sql);
        foreach ($institutions as $institution) {

            array_push($arrayInstitutions, $institution['institution_id']);
        }
        return $arrayInstitutions;
    }

    function getInstitution($id) {
        $sql = "SELECT * FROM tbl_unesco_oer_institutions WHERE id ='$id'";
        return $this->getArray($sql);
    }
    
     function getLastEntry() {
        $sql = "SELECT id FROM tbl_unesco_oer_institutions order by id desc limit 1";
        return $this->getArray($sql);
    }

    function getInstitutionThumbnail($institutionid) {
        $sql = "SELECT * FROM tbl_unesco_oer_institutions WHERE id ='$institutionid'";
        $institutionarray = $this->getArray($sql);
        return $institutionarray[0]['thumbnail'];
    }

    function getInstitutionName($institutionid) {
        $sql = "SELECT * FROM tbl_unesco_oer_institutions WHERE id ='$institutionid'";
        $institutionarray = $this->getArray($sql);
        return $institutionarray[0]['name'];
    }

    function getNoOfInstitutions($id) {
        $sql = "SELECT * FROM tbl_unesco_oer_group_institutions WHERE group_id='$id' AND institution_id IS NOT NULL";
         $institutions = $this->getArray($sql);
        return count($institutions);
    }

    function getGroupOwner($groupid) {
        $sql = "SELECT * FROM tbl_unesco_oer_groups WHERE id='$groupid'";
        $array = $this->getArray($sql);
        return $array[0]['admin'];
    }




//    function getGroupOwnerID($groupid) {
//      $sql = "SELECT * FROM tbl_unesco_oer_groups WHERE id = '$groupid'";
//      $owner= $this->getArray($sql);
//      return $owner[0]['admin'];
//
//    }

    function storegroupinstitution($groupid, $institutionid) {
        $data = array(
            'group_id' => $groupid,
            'institution_id' => $institutionid
        );
        $this->insert($data);
    }

    function getWebsite($groupid) {
        $sql = "SELECT * FROM tbl_unesco_oer_groups where id = '$groupid'";
        $website = $this->getArray($sql);
        return $website[0]['website'];
    }

    function getDescription_Line_one($groupid) {
        $sql = "SELECT * FROM tbl_unesco_oer_groups where id = '$groupid'";
        $array = $this->getArray($sql);
        return $array[0]['description_one'];
    }

    function getDescription_Line_two($groupid) {
        $sql = "SELECT * FROM tbl_unesco_oer_groups where id = '$groupid'";
        $array = $this->getArray($sql);
        return $array[0]['description_two'];
    }

    function getDescription_Line_three($groupid) {
        $sql = "SELECT * FROM tbl_unesco_oer_groups where id = '$groupid'";
        $array = $this->getArray($sql);
        return $array[0]['description_three'];
    }

    function getDescription_Line_four($groupid) {
        $sql = "SELECT * FROM tbl_unesco_oer_groups where id = '$groupid'";
        $array = $this->getArray($sql);
        return $array[0]['description_four'];
    }

    function getForum($groupid) {
        $sql =
                "select * from tbl_forum where forum_workgroup = '$groupid'";

        $array = $this->getArray($sql);
        return $array[0];
    }

    //boolean check if a group has a forum
    function forumExist($groupid) {
        $sql = "select * from tbl_forum where forum_workgroup = '$groupid'";
        $array = $this->getArray($sql);
        if (empty($array)) {
            return TRUE;
        } else {
            return False;
        }
    }


    function getGroup_Pkid_in_forum($groupid){
        $sql="select * from tbl_forum where forum_workgroup = '$groupid'";
        $array = $this->getArray($sql);
        return $array[0]['id'];
    }
    function  getNumberPost($topicid){
        $sql="select * from tbl_forum_topic where id ='$topicid'";
        $array = $this->getArray($sql);
        return $array[0]['replies'];
    }
    function saveSubGroup($name,$website,$description,$brief_descriptiont,$interest,$parent_id) {
        $data = array(
            'name' => $name,
            'email' => '',
            'address' =>'',
            'city' => '',
            'state' => '',
            'country' =>'',
            'postalcode' => '',
            'website' => $website,
            'LinkedInstitution' =>'',
            'loclat' => '',
            'loclong' => '',
            'description' => $description,
            'admin' => $admin,
            'thumbnail' => '',
            'description_one' => $brief_description,
            'description_two' => '',
            'description_three' =>'',
            'description_four' => '',
            'interests'=>$interest,
             'parent_id'=>$parent_id
        );
        return $this->insert($data);
    }


    function getGroupSubgroup($groupid){
        $sql="select * from tbl_unesco_oer_groups where parent_id='$groupid'";
        $array = $this->getArray($sql);
        return $array;
    }





}
?>