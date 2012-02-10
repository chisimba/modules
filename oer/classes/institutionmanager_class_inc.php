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
    private $_objCountry;
    private $_validation;

    /**
     * @var object $objLanguage Language Object
     */
    private $_objLanguage;

    function init() {
        $this->_objDbInstitution = $this->getObject('dbinstitution');
        $this->_objDbInstitutionType = $this->getObject('dbinstitutiontypes');
        $this->_objCountry = $this->getObject('languagecode', 'language');
        $this->_objLanguage = $this->getObject('language', 'language');
        $this->_institution = $this->getObject('institution');
        $this->_institutionList = array();
        $this->_validation['valid'] = TRUE;
    }

    public function institutionNameExists($name) {
        $checkName = $this->_objDbInstitution->getInstitutionName($name);

        if (strlen($checkName) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function addInstitution($name, $description, $type, $country, $address1, $address2, $address3, $zip, $city, $websiteLink, $keyword1, $keyword2, $thumbnail) {
        //Check if institution exists
        return $this->_objDbInstitution->addInstitution($name, $description, $type, $country, $address1, $address2, $address3, $zip, $city, $websiteLink, $keyword1, $keyword2, $thumbnail);
    }

    public function editInstitution($id, $name, $description, $type, $country, $address1, $address2, $address3, $zip, $city, $websiteLink, $keyword1, $keyword2, $thumbnail) {
        //First check if an institution with a similar name exists        
        $this->_objDbInstitution->editInstitution($id, $name, $description, $type, $country, $address1, $address2, $address3, $zip, $city, $websiteLink, $keyword1, $keyword2, $thumbnail);
    }

    public function removeInstitution($id) {
        $this->_objDbInstitution->deleteInstitution($id);
    }

    public function getInstitution($id) {
        $this->_institution = $this->constructInstitution($id);

        return $this->_institution;
    }

    public function getAllInstitutions($filter = NULL) {
        $objIcon = $this->newObject('geticon', 'htmlelements');
        $objUser = $this->getObject("user", "security");
        $this->_institutionList = $this->_objDbInstitution->getAllInstitutions($filter);
        $table = $this->getObject("htmltable", "htmlelements");
        $table->startHeaderRow();
        $table->addHeaderCell($this->_objLanguage->languageText('mod_oer_institution_name', 'oer'));
        $table->addHeaderCell($this->_objLanguage->languageText('mod_oer_institution_country', 'oer'));
        $table->addHeaderCell($this->_objLanguage->languageText('mod_oer_institution_type', 'oer'));
        $table->endHeaderRow();
        $dbInstitutionType = $this->getObject("dbinstitutiontypes", "oer");
        $canEdit = $objUser->isLoggedIn();
        foreach ($this->_institutionList as $institution) {
            $table->startRow();
            $objIcon->setIcon("edit");
            $editLink=new link($this->uri(array("action"=>"institutionedit","mode"=>"edit", "id"=>$institution['id'])));
            $editLink->link=$objIcon->show();
            
            $table->addCell($institution['name'].$editLink->show());
            $table->addCell($institution['country']);
            $table->addCell($dbInstitutionType->getType($institution['type']));
            $table->endRow();
        }
        $header = '<div id="institutionlisting_header">';
        $addLink = new link($this->uri(array("action" => 'institutionedit')));
        $addLink->link = $this->_objLanguage->languageText('mod_oer_institution_heading_new', 'oer');
        $header.=$addLink->show();
        $header.='</div>';
        return $header . $table->show();
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

    //Get the country name by using the country ID stored in the database
    function getInstitutionCountry() {
        $countryId = $this->_institution->getCountry();

        return $this->_objCountry->getName($countryId);
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
        $institutionData['country'] = $this->getInstitutionCountryId();
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
        $this->_validation['valid'] = TRUE;
        //Check if a name has been provided
        if (empty($name)) {
            $this->_validation['valid'] = FALSE;
            $nameErrMsg = $this->_objLanguage->languageText('mod_unesco_oer_institution_name_error', 'unesco_oer');
            $this->_validation['name'] = $nameErrMsg;
        }

        //Ensure that a description has been provided
        if (empty($description)) {
            $this->_validation['valid'] = FALSE;
            $descriptionErrMsg = $this->_objLanguage->languageText('mod_unesco_oer_institution_type_error', 'unesco_oer');
            $this->_validation['description'] = $descriptionErrMsg;
        }

        //Ensure that a type has been selected
        if (empty($type)) {
            $this->_validation['valid'] = FALSE;
            $typeErrMsg = $this->_objLanguage->languageText('mod_unesco_oer_institution_description_error', 'unesco_oer');
            $this->_validation['type'] = $typeErrMsg;
        }

        //Ensure that a country has been selected
        if (empty($country)) {
            $this->_validation['valid'] = FALSE;
            $countryErrMsg = $this->_objLanguage->languageText('mod_unesco_oer_institution_country_error', 'unesco_oer');
            $this->_validation['country'] = $countryErrMsg;
        }
        //Ensure that an address1 has been provided
        if (empty($address1)) {
            $this->_validation['valid'] = FALSE;
            $addressErrMsg = $this->_objLanguage->languageText('mod_unesco_oer_institution_address_error', 'unesco_oer');
            $this->_validation['address1'] = $addressErrMsg;
        }

        //Ensure that a city has been provided
        if (empty($city)) {
            $this->_validation['valid'] = FALSE;
            $cityErrMsg = $this->_objLanguage->languageText('mod_unesco_oer_institution_city_error', 'unesco_oer');
            $this->_validation['city'] = $cityErrMsg;
        }

        //Ensure that a zip has been provided
        if (empty($zip)) {
            $this->_validation['valid'] = FALSE;
            $zipErrMsg = $this->_objLanguage->languageText('mod_unesco_oer_institution_zip_error', 'unesco_oer');
            $this->_validation['zip'] = $zipErrMsg;
        }

        //Ensure that a websitelink has been provided
        if (empty($websiteLink)) {
            $this->_validation['valid'] = FALSE;
            $urlErrMsg = $this->_objLanguage->languageText('mod_unesco_oer_institution_websitelink_error', 'unesco_oer');
            $this->_validation['websiteLink'] = $urlErrMsg;
        }

        //Ensure that at least 1 keyword has been provided
        if (empty($keyword1)) {
            $this->_validation['valid'] = FALSE;
            $keywordErrMsg = $this->_objLanguage->languageText('mod_unesco_oer_institution_keyword_error', 'unesco_oer');
            $this->_validation['keyword1'] = $keywordErrMsg;
        }

        //Ensure that thumbnail is provided
        if (empty($thumbnail)) {
            $this->_validation['valid'] = FALSE;
            $thumbnailErrMsg = $this->_objLanguage->languageText('mod_unesco_oer_institution_thumbnail_error', 'unesco_oer');
            $this->_validation['thumbnail'] = $thumbnailErrMsg;
        }

        return $this->_validation;
    }

}

?>