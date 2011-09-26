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

class umbrellaproducttheme extends producttheme {

    /**
     * This is an array containing the subthemes
     * @var <Array>
     */
    private $_subThemesList;
    private $_subTheme;
    private $_objDbProductThemes;

    function init() {
        //Initialise all parameters
        parent::setName(NULL);
        $this->_subThemesList = array();
        parent::setId(NULL);
        $this->_objDbProductThemes = $this->getObject('dbproductthemes');
        $this->_subTheme = $this->getObject('producttheme');
    }

    /**
     *
     * @return <productThemes> Returns all the themes that belong to this umbrella theme
     */
    function getAllSubThemes() {
        $umbrellaId = parent::getId();
        $subThemes = $this->_objDbProductThemes->getThemesByUmbrellaID($umbrellaId);
        foreach ($subThemes as $subTheme) {
            //Create themes
            $this->_subTheme->setId($subTheme['id']);
            $this->_subTheme->setName($subTheme['theme']);
            //Create list of subthemes
            array_push($this->_subThemesList, $this->_subTheme);
        }
        return $this->_subThemesList;
    }

    function getSubTheme($id) {
        return $this->_objDbProductThemes->getThemeByID($id);
    }

    function addSubTheme($themeName) {
        if (!empty($themeName)) {
            $umbrellaId = parent::getId();
            return $this->_objDbProductThemes->addTheme($themeName, $umbrellId);
        } else {
            return FALSE;
        }
    }

    function removeSubTheme($id) {
        $this->_objDbProductThemes->deleteTheme($id);
    }

    function updateSubTheme($id, $theme, $umbrellaId) {
        $this->_objDbProductThemes->updateTheme($id, $theme, $umbrellaId);
    }

}
?>