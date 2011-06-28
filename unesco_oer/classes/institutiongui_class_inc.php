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
    private $_institutionList;

    function init() {
        $this->_institutionmanager = $this->getObject('institutionmanager', 'unesco_oer');
        $this->loadClass('link', 'htmlelements');
    }

    function getInstitution($id) {
        $this->_institutionmanager->getInstitution($id);
    }

    function showInstitutionName() {
        return $this->_institutionmanager->getInstitutionName();
    }

    function showInstitutionDescription() {
        return $this->_institutionmanager->getInstitutionDescription();
    }

    function showInstitutionType() {
        return $this->_institutionmanager->getInstitutionType();
    }

    function showInstitutionTypeId() {
        return $this->_institutionmanager->getInstitutionTypeID();
    }

    function showInstitutionCountry() {
        return $this->_institutionmanager->getInstitutionCountry();
    }

    function showInstitutionCountryId() {
        return $this->_institutionmanager->getInstitutionCountryId();
    }

    function showInstitutionKeywords() {
        $keywords = $this->_institutionmanager->getInstitutionKeywords();

        return $keywords;
    }

    function showInstitutionAddress() {
        $address = $this->_institutionmanager->getInstitutionAddress();

        return $address;
    }

    function showInstitutionZip() {
        $zip = $this->_institutionmanager->getInstitutionZip();

        return $zip;
    }

    function showInstitutionCity() {
        $city = $this->_institutionmanager->getInstitutionCity();

        return $city;
    }

    function showInstitutionWebsiteLink() {
        $acLink = new link('http://' . $this->_institutionmanager->getInstitutionWebsiteLink());
        $acLink->cssClass = 'greyTextLink';
        $acLink->link = $this->_institutionmanager->getInstitutionWebsiteLink();

        return $acLink->show();
    }

    function showInstitutionThumbnail() {
        return $this->_institutionmanager->getInstitutionThumbnail();
    }

    function showAllInstitutionData() {
        return $this->_institutionmanager->getInstitutionData();
    }

    function showNewInstitutionLink() {
        $acLink = new link($this->uri(array("action" => "institutionEditor", 'institutionId' => NULL)));
        $acLink->cssClass = 'adaptationListingLink';
        $acLink->link = 'Create new institution';

        return $acLink->show();
    }

    function showEditInstitutionLink($institutionId) {
        $acLink = new link($this->uri(array("action" => "institutionEditor", 'institutionId' => $institutionId)));
        $acLink->cssClass = 'adaptationListingLink';
        $acLink->link = 'Update';

        return $acLink->show();
    }

    function showDeleteInstitutionLink($institutionId) {
        $acLink = new link($this->uri(array("action" => "deleteInstitution", 'institutionId' => $institutionId)));
        $acLink->cssClass = 'deleteinstitution';
        $acLink->link = 'Delete';

        return $acLink->show();
    }

    function showNewInstitutionLinkThumbnail() {
        $acLink = new link($this->uri(array("action" => "institutionEditor", 'institutionId' => NULL)));
        $acLink->cssClass = 'Farright';
        $acLink->link = '<img src="skins/unesco_oer/images/icon-filter-institution-type.png" width="18" height="18">';

        return $acLink->show();
    }

    function showEditInstitutionLinkThumbnail($institutionId) {
        $acLink = new link($this->uri(array("action" => "institutionEditor", 'institutionId' => $institutionId)));
        $acLink->cssClass = 'Farright';
        $acLink->link = '<img src="skins/unesco_oer/images/icon-edit-section.png">';

        return $acLink->show();
    }

    function showDeleteInstitutionLinkThumbnail($institutionId) {
        $acLink = new link($this->uri(array("action" => "deleteInstitution", 'institutionId' => $institutionId)));
        $acLink->cssClass = 'deleteinstitution';
        $acLink->link = '<img src="skins/unesco_oer/images/icon-delete.png">';

        return $acLink->show();
    }

    function showAllInstitutions() {
        return $this->_institutionmanager->getAllInstitutions();
    }

}
?>

<script type="text/javascript">
    jQuery(document).ready(function(){

    jQuery("a[class=deleteInstitution]").click(function(){

    var r=confirm("Are you sure you want to delete this institution?");
    if(r== true){
    window.location=this.href;
    }
    return false;
    }
    }
</script>
