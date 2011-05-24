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

class institution extends object {

    /**
     * This is the id of the isntitution
     * @var <String>
     */
    private $_id;
    /**
     * This is the name of the isntitution
     * @var <String>
     */
    private $_name;
    /**
     * The description of the institution
     * @var <String>
     */
    private $_description;
    /**
     * The type of institution i.e. School, NGO, IGO or Private Sector
     * @var <PDInstitutionType>
     */
    private $_type;
    /**
     * The name of the country that the institution is in
     * @var <PDCountry>
     */
    private $_country;
    /**
     * Address of the institution
     * @var <String>
     */
    private $_address;
    /**
     * Zip or postal code of the institution
     * @var <int>
     */
    private $_zip;
    /**
     * The city where the institution is located
     * @var <String>
     */
    private $_city;
    /**
     * Website link of the institution
     * @var <String>
     */
    private $_websiteLink;
    /**
     * Comma separated list of keywords associated with the institution
     * @var <String>
     */
    private $_keywords;
    /**
     * List of references of groups that are linked with the institution
     * @var <Group>
     */
    private $_linkedGroups;
    /**
     * Latitude coordinates
     * @var <String>
     */
    private $_latitude;
    /**
     * Longitude coordinates
     * @var <String>
     */
    private $_longitude;
    /**
     * This is the thumbnail of the institution
     * @var <String>
     */
    private $_thumbnail;

    /**
     *
     * @param <String> $name
     * @param <String> $description
     * @param <PDInstitutionType> $type
     * @param <PDCOuntry> $country
     * @param <String> $address
     * @param <Integer> $zip
     * @param <String> $city
     * @param <String> $websiteLink
     * @param <String> $keywords
     * @param <Group> $linkedGroups
     * @param <String> $thumbnail
     */
//    function __construct(
//    $name, $description, $type, $country, $address, $zip, $city, $websiteLink, $keywords, $linkedGroups, $thumbnail) {
//        $this->_id = $id;
//        $this->_name = $name;
//        $this->_description = $description;
//        $this->_type = $type;
//        $this->_country = $country;
//        $this->_address = $address;
//        $this->_zip = $zip;
//        $this->_city = $city;
//        $this->_websiteLink = $websiteLink;
//        $this->_keywords = $keywords;
//        $this->_linkedGroups = $linkedGroups;
//        $this->_thumbnail = $thumbnail;
//        $this->_latitude = NULL;
//        $this->_longitude = NULL;
//    }

    /**
     * Standard getter for the id of the institution
     * @param <type>
     * @return <String> $this->_id
     */
    public function getId() {
        return $this->_id;
    }

    /**
     * Standard setter for the id of the institution
     * @param <String> $id
     * @return <Boolean> Returns the result of setting the id
     */
    public function setId($id) {
        $this->_id = $id;
    }

    /**
     * Standard getter for the name of the institution
     * @param <type>
     * @return <String> $this->_name
     */
    public function getName() {
        return $this->_name;
    }

    /**
     * Standard setter for the name of the institution
     * @param <String> $name
     * @return <Boolean> Returns the result of setting the name
     */
    public function setName($name) {
        $this->_name = $name;
    }

    public function getDesciption() {
        return $this->_description;
    }

    public function setDescription($description) {
        $this->_description = $description;
    }

    public function getType() {
        return $this->_type;
    }

    public function setType($type) {
        $this->_type = $type;
    }

    public function getCountry() {
        return $this->_country;
    }

    public function setCountry($country) {
        $this->_country = $country;
    }

    public function getAddress() {
        return $this->_address;
    }

    public function setAddress($adress) {
        $this->_address = $adress;
    }

    public function getZipCode() {
        return $this->_zip;
    }

    public function setZipCode($zipCode) {
        $this->_zip = $zipCode;
    }

    public function setCity($city) {
        $this->_city = $city;
    }

    public function getCity() {
        return $this->_city;
    }

    public function setWebsiteLink($websiteLink) {
        $this->_websiteLink = $websiteLink;
    }

    public function getWebsiteLink() {
        return $this->_websiteLink;
    }

    public function getKeywords() {
        return $this->_keywords;
    }

    public function setKeywords($keywords) {
        $this->_keywords = $keywords;
    }

    public function getLatitude() {
        $this->_latitude;
    }

    public function setLatitude($latitude) {
        $this->_latitude = $latitude;
    }

    public function getLongitude() {
        $this->_longitude;
    }

    public function setLongitude($longitude) {
        $this->_longitude = $longitude;
    }

    public function setThumbnail($thumbnail) {
        $this->_thumbnail = $thumbnail;
    }

    public function getTgumbnail($thumbnail) {
        return $this->_thumbnail;
    }

    public function getLinkedGroup() {
        return $this->_linkedGroups;
    }

    public function getAllLinkedGroups() {
        return $this->_linkedGroups;
    }

    public function addLinkedGroup($groupId) {
        $this->_linkedGroups = $group;
    }

    public function removeLinkedGroup($groupId) {

    }

}
?>
