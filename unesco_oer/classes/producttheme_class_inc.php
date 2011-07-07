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

class producttheme extends object {
    /**
     *This is the name of the theme
     * @var <String>
     */
    private $_name;

    /**
     *This is the ID of the theme
     * @var <String>
     */
    private $_id;
    
    /**
     *This is the type of theme ["a" => "maintheme", "b" => "theme", "c" => "subtheme"]
     * @var <themeType>
     */
    private $_themeType;

    /**
     *This is an array containing the subthemes
     * @var <Array>
     */
    private $_subThemes;

    function init() {
        $this->_name = NULL;
        $this->_id = NULL;
        $this->_themeType = NULL;
        $this->_subThemes = array();
    }

    function getName() {
        return $this->_name;
    }

    function getID() {
        return $this->_id;
    }

    function setID($id) {
        if (!empty($id)) {
            $this->_id = $id;
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function setName($name) {
        if (!empty($id)) {
            $this->_name = $name;
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function geThemeType() {
        return $this->_themeType;
    }

    function setThemeType($themeType) {
        if (!empty($themeType)) {
            $this->_type = $themeType;
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function getSubThemes() {
        return $this->_subThemes;
    }

    function addSubThemes($subThemes) {
        if (($this->_type != 'subtheme') && (!empty($subThemes))) {
            return array_push($this->_subThemes, $subThemes);
        } else {
            return FALSE;
        }
    }

}
?>
