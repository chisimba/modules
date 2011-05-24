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

class institutiongui extends object{
//    private $_institution;
    private $_institutionmanager;

    function init() {
        $this->_institutionmanager = $this->getObject('institutionmanager', 'unesco_oer');
    }

    //Get the object and build it
    function showInstitutionName($id){
        $this->_institutionmanager = $this->getObject('institutionmanager', 'unesco_oer');
        //$myInstitution = $this->_institutionmanager->getInstitution($id);
//        echo is_object($this->_institutionmanager->getInstitution('gen15Srv41Nme47_69623_1306229613'));
       // echo $myInstitution->getName();
    }
    function displayInstitution($institution) {

    }
}
?>
