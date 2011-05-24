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

    function init(){
        $this->_objDbInstitution = $this->getObject('dbinstitution');
        $this->_institution = NULL;
        $this->_institutionList = NULL;
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
        return $this->_objDbInstitution->getInstitutionById($id);
    }

    public function getAllInstitutions() {
        //$this->_institutionList = $this->_objDbInstitution->getAllInstitutions();
        return $this->_objDbInstitution->getAllInstitutions();
    }

}
?>
