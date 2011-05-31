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

//TODO remove as much html from this class
//    private $_institution;
    private $_institutionmanager;
    private $_institution;

    function init() {
        $this->_institutionmanager = $this->getObject('institutionmanager', 'unesco_oer');
        $this->_institution = $this->getObject('institution', 'unesco_oer');
        $this->loadClass('link', 'htmlelements');
    }

    function getInstitution($id) {
        $this->_institution = $this->_institutionmanager->getInstitution($id);
    }

    function showInstitutionName() {
        return $this->_institution->getName();
    }

    function showInstitutionDescription() {
        return $this->_institution->getDescription();
    }

    function showInstitutionType() {
        return $this->_institution->getType();
    }

    function showInstitutionCountry() {
        return $this->_institution->getCountry();
    }

    function showInstitutionKeywords() {
        return $this->_institution->getKeyword1();

        $keyword2 = $this->_institution->getKeyword2();
        if (isset($keyword2)) {
            return ' | ' . $this->_institution->getKeyword2();
        }
    }

    function showInstitutionAddress() {
        $address = '';
        $address .= $this->_institution->getAddress1();

        $address2 = $this->_institution->getAddress2();
        if (isset($address2)) {
            $address .= ', ' . $this->_institution->getAddress2();
        }

        $address3 = $this->_institution->getAddress3();
        if (isset($address3)) {
            $address .= ', ' . $this->_institution->getAddress3();
        }

        return $address;
    }

    function showInstitutionWebsiteLink() {
        return $this->_institution->getWebsiteLink();
    }

    function showInstitutionLinkedGroups() {
        return $this->_institution->getAllLinkedGroups();
    }

    function showInstitutionThumbnail() {
        return $this->_institution->getThumbnail();
    }

    function showNewInstitutionLink() {
        $acLink = new link($this->uri(array("action" => "institutionEditor")));
        $acLink->cssClass = 'greyTextLink';
        $acLink->link = '<a href="#"><img src="skins/unesco_oer/images/new-institution.png" width="18" height="18" class="Farright"></a> <a href="#" class="greyTextLink">Create new institution</a>';
        
        return $acLink->show();
    }

}
?>
