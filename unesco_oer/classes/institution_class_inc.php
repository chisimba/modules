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
     * The country and code of the institution
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

    function __construct(
    $name, $description, $type, $country, $address, $zip, $city, $websiteLink, $keywords, $linkedGroups) {
        $this->_name = $name;
        $this->_description = $description;
        $this->_type = $type;
        $this->_country = $country;
        $this->_address = $address;
        $this->_zip = $zip;
        $this->_city = $city;
        $this->_websiteLink = $websiteLink;
        $this->_keyWords = $keywords;
        $this->_linkedGroups = $linkedGroups;
    }

//    /**
//     * Standard Dispatch Function for Controller
//     * @param <type> $action
//     * @return <type>
//     */
//    private function stringValidation($parameter, $argument) {
//
//    }
//
//    private function arrayValidation($parameter, $argument) {
//        
//    }

}
?>
