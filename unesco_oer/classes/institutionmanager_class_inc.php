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

class institutionmanager extends object {

    private $_institutionList;
    private $_institution;
    private $_objDbInstitution;
    private $_objDbInstitutionType;
    private $_objDbCountries;
    private $_validation;

    function init() {
        $this->_objDbInstitution = $this->getObject('dbinstitution');
        $this->_objDbInstitutionType = $this->getObject('dbinstitutiontypes');
        $this->_objDbCountries = $this->getObject('dbcountries');
        $this->_institution = $this->getObject('institution');
        $this->_institutionList = array();
        $this->_validation['valid'] = TRUE;
    }

    public function addInstitution($name, $description, $type, $country, $address1, $address2, $address3, $zip, $city, $websiteLink, $keyword1, $keyword2, $thumbnail) {
        $this->_objDbInstitution->addInstitution($name, $description, $type,
                $country, $address1, $address2, $address3, $zip,
                $city, $websiteLink, $keyword1, $keyword2, $thumbnail);
    }

    public function editInstitution($id, $name, $description, $type, $country, $address1, $address2, $address3, $zip, $city, $websiteLink, $keyword1, $keyword2, $thumbnail) {

      $this->_objDbInstitution->editInstitution($id, $name, $description, $type,
                $country, $address1, $address2, $address3, $zip,
                $city, $websiteLink, $keyword1, $keyword2,
                $thumbnail);
    }

    public function removeInstitution($id) {
        $this->_objDbInstitution->deleteInstitution($id);
    }

    public function getInstitution($id) {
        $this->_institution = $this->constructInstitution($id);

        return $this->_institution;
    }

    public function getAllInstitutions() {
        $this->_institutionList = $this->_objDbInstitution->getAllInstitutions();
        return $this->_institutionList;
    }

    private function constructInstitution($id) {
        $parameters = $this->_objDbInstitution->getInstitutionById($id);

        $myInstitution = $this->getObject('institution', 'unesco_oer');
        $myInstitution->setId($parameters[0]['id']);
        $myInstitution->setName($parameters[0]['name']);
        $myInstitution->setDescription($parameters[0]['description']);
        $myInstitution->setType($parameters[0]['type']);
        $myInstitution->setCountry($parameters[0]['country']);
        $myInstitution->setAddress1($parameters[0]['address1']);
        $myInstitution->setAddress2($parameters[0]['address2']);
        $myInstitution->setAddress3($parameters[0]['address3']);
        $myInstitution->setZip($parameters[0]['zip']);
        $myInstitution->setCity($parameters[0]['city']);
        $myInstitution->setWebsiteLink($parameters[0]['websitelink']);
        $myInstitution->setKeyword1($parameters[0]['keyword1']);
        $myInstitution->setKeyword2($parameters[0]['keyword2']);
        $myInstitution->setThumbnail($parameters[0]['thumbnail']);

        return $myInstitution;
    }

    public function getIdOfAddedInstitution() {
        $id = $this->_objDbInstitution->getLastInstitutionId();

        return $id[0]['id'];
    }

    function getInstitutionId() {
        return $this->_institution->getId();
    }

    function getInstitutionName() {
        return $this->_institution->getName();
    }

    function getInstitutionDescription() {
        return $this->_institution->getDescription();
    }

    function getInstitutionType() {
        $typeId = $this->_institution->getType();

        return $this->_objDbInstitutionType->getType($typeId);
    }

    function getInstitutionTypeID() {
        return $this->_institution->getType();
    }

    function getInstitutionZip() {
        return $this->_institution->getZip();
    }

    function getInstitutionCity() {
        return $this->_institution->getCity();
    }

    function getInstitutionWebsiteLink() {
        return $this->_institution->getWebsiteLink();
    }

    function getInstitutionCountry() {
        $countryId = $this->_institution->getCountry();

        return $this->_objDbCountries->getCountryName($countryId);
    }

    function getInstitutionCountryId() {
        return $this->_institution->getCountry();
    }

    function getInstitutionThumbnail() {
        return $this->_institution->getThumbnail();
    }

    function getInstitutionKeywords() {
        $keywords = array(
            "keyword1" => $this->_institution->getKeyword1(),
            "keyword2" => $this->_institution->getKeyword2());

        return $keywords;
    }

    function getInstitutionAddress() {
        $address = array(
            "address1" => $this->_institution->getAddress1(),
            "address2" => $this->_institution->getAddress2(),
            "address3" => $this->_institution->getAddress3());

        return $address;
    }

    //Get the values of all the current institution in an array
    function getInstitutionData() {
        $institutionData['name'] = $this->getInstitutionName();
        $institutionData['id'] = $this->getInstitutionId();
        $institutionData['description'] = $this->getInstitutionDescription();
        $institutionData['type'] = $this->getInstitutionType();
        $institutionData['country'] = $this->getInstitutionCountry();
        $address = $this->getInstitutionAddress();
        $institutionData['address1'] = $address['address1'];
        $institutionData['address2'] = $address['address2'];
        $institutionData['address3'] = $address['address3'];
        $institutionData['zip'] = $this->getInstitutionZip();
        $institutionData['city'] = $this->getInstitutionCity();
        $institutionData['websiteLink'] = $this->getInstitutionWebsiteLink();
        $keywords = $this->getInstitutionKeywords();
        $institutionData['keyword1'] = $keywords['keyword1'];
        $institutionData['keyword2'] = $keywords['keyword2'];
        $institutionData['thumbnail'] = $this->getInstitutionThumbnail();

        return $institutionData;
    }

    function validate($name, $description, $type, $country, $address1, $address2, $address3, $zip, $city, $websiteLink, $keyword1, $keyword2, $thumbnail) {
        $this->_validation['valid']= TRUE;
        //Check if a name has been provided
        if (isempty($name)) {
            $this->_validation['valid'] = FALSE;
            $this->_validation['name'] = "Please enter a name for the institution.";
        }

        //Ensure that a description has been provided
        if (isempty($description)) {
            $this->_validation['valid'] = FALSE;
            $this->_validation['description'] = "Please provide a description for the institution.";
        }

        //Ensure that a type has been selected
        if (isempty($type)) {
            $this->_validation['valid'] = FALSE;
            $this->_validation['type'] = "Please select a type for the institution.";
        }

        //Ensure that a country has been selected
        if (isempty($type)) {
            $this->_validation['valid'] = FALSE;
            $this->_validation['country'] = "Please select a country for the institution.";
        }
        //Ensure that an address1 has been provided
        if (isempty($address1)) {
            $this->_validation['valid'] = FALSE;
            $this->_validation['address1'] = "Please provide a valid address for the institution.";
        }

        //Ensure that a city has been provided
        if (isempty($city)) {
            $this->_validation['valid'] = FALSE;
            $this->_validation['city'] = "Please provide a city for the institution.";
        }

        //Ensure that a zip has been provided
        if (isempty($zip)) {
            $this->_validation['valid'] = FALSE;
            $this->_validation['zip'] = "Please provide a valid zip/postal code.";
        }

        //Ensure that a websitelink has been provided
        if (isempty($websiteLink)) {
            $this->_validation['valid'] = FALSE;
            $this->_validation['websiteLink'] = "Please provide a valid website link.";
        }

        //Ensure that at least 1 keyword has been provided
        if (isempty($keyword1)) {
            $this->_validation['valid'] = FALSE;
            $this->_validation['keyword1'] = "Please provide at least one keyword.";
        }
        
        //Ensure that thumbnail is provided
        if (isempty($thumbnail)) {
            $this->_validation['valid'] = FALSE;
            $this->_validation['thumbnail'] = "Please provide a thumbnail.";
        }

         return $this->_validation;
    }

}
?>
