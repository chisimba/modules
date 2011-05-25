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

class institutiongui extends object {

//    private $_institution;
    private $_institutionmanager;
    private $_institution;

    function init() {
        $this->_institutionmanager = $this->getObject('institutionmanager', 'unesco_oer');
        $this->_institution = $this->getObject('institution', 'unesco_oer');
    }

    function getInstitution($id) {
        $this->_institution = $this->_institutionmanager->getInstitution($id);
    }

    function showInstitutionName() {
        echo $this->_institution->getName();
    }

    function showInstitutionDescription() {
        echo $this->_institution->getDescription();
    }

    function showInstitutionType() {
        echo $this->_institution->getType();
    }

    function showInstitutionCountry() {
        echo $this->_institution->getCountry();
    }

    function showInstitutionKeywords() {
        echo $this->_institution->getKeywords();
    }

    function showInstitutionAddress() {
        echo $this->_institution->getAddress();
    }

    function showInstitutionWebsiteLink() {
        echo $this->_institution->getWebsiteLink();
    }

    function showInstitutionLinkedGroups() {
        echo $this->_institution->getAllLinkedGroups();
    }

    

//    //Get the object and build it
//    function showInstitutionName($id) {
//        $myInstitution = $this->_institutionmanager->getInstitution($id);
//        echo $myInstitution->getName();
//    }
//
//    function showInstitutionDescription($id) {
//        $myInstitution = $this->_institutionmanager->getInstitution($id);
//        echo $myInstitution->getDescription();
//    }

    function displayInstitution($institution) {
        
    }

}
?>
