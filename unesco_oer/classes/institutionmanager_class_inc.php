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
include_once 'institution_class_inc.php';
include_once 'dbinstitution_class_inc.php';

class institutionmanager extends object {

    private $_institutionList;
    private $_institution;
    private $_objDbInstitution;
    private $_groupList;
    private $_group;

    function init() {
        $this->_objDbInstitution = $this->getObject('dbinstitution');
        $this->_institution = $this->getObject('institution');
        $this->_institutionList = array();
        $this->_group = NULL;
        $this->_groupList = NULL;
    }

    public function addLinkedGroup($institutionId, $groupId) {
        
    }

    public function removeLinkedGroup($institutionId, $groupId) {

    }

    public function addInstitution(&$institution) {
        //$myInstitution = $this->loadObject('institution', 'unesco_oer');
        //TODO Ntsako Add this institution to the database
    }

    public function editInstitution($id) {

    }

    public function removeInstitution($id) {

    }

    public function getInstitution($id) {
        $this->_institution = $this->constructInstitution($id);

        return $this->_institution;
    }

    public function getAllInstitutions() {
        //$this->_institutionList = $this->_objDbInstitution->getAllInstitutions();
        return $this->_objDbInstitution->getAllInstitutions();
    }

    private function constructInstitution($id) {
        $parameters = $this->_objDbInstitution->getInstitutionById($id);

        $myInstitution = $this->getObject('institution', 'unesco_oer');
        $myInstitution->setId($parameters[0]['id']);
        $myInstitution->setName($parameters[0]['name']);
        $myInstitution->setDescription($parameters[0]['description']);
        $myInstitution->setType($parameters[0]['type']);
        $myInstitution->setCountry($parameters[0]['country']);
        $myInstitution->setAddress($parameters[0]['address']);
        $myInstitution->setZip($parameters[0]['zip']);
        $myInstitution->setCity($parameters[0]['city']);
        $myInstitution->setWebsiteLink($parameters[0]['websitelink']);
        $myInstitution->setKeywords($parameters[0]['keywords']);
        $myInstitution->addLinkedGroup($parameters[0]['linkedgroups']);
        $myInstitution->setThumbnail($parameters[0]['thumbnail']);

        return $myInstitution;

    }

}
?>
