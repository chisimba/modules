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

class productthememanager extends object {

    private $_productTheme;
    private $_umbrellaTheme;
    private $_productthemelist;
    private $_objDbProductThemes;

    function init() {
        $this->_productTheme = $this->getObject('producttheme');
        $this->_umbrellaTheme = $this->getObject('umbrellaproducttheme');
        $this->_productthemelist = array();
        $this->_objDbProductThemes = $this->getObject('dbproductthemes');
    }

    function createUmbrellaTheme($theme) {
//        $this->_objDbProductThemes->addUmbrellaTheme($theme);
//        $this->_umbrellaThemes = $this->_objDbProductThemes->getUmbrellaThemes();
        return $this->_objDbProductThemes->addUmbrellaTheme($theme);
    }

    function createsubTheme($theme, $umbrellaId) {
        $this->_umbrellaTheme = $this->_objDbProductThemes->getUmbrellaThemeByID($umbrellaId);
        return $this->_umbrellaTheme->addSubTheme($theme);
    }

    
    //This returns a subtheme based on the provided id
    function getSubTheme($id) {
        $themeData = $this->_objDbProductThemes->getThemeByID($id);
        $this->_productTheme->setName($themeData['theme']);
        $this->_productTheme->setId($themeData['id']);
        
        return $this->_productTheme;
    }

    //This returns an umbrella based on the provided id
    function getUmbrellaTheme($id) {
        $themeData = $this->_objDbProductThemes->getUmbrellaThemeByID($id);
        $this->_umbrellaTheme->setName($themeData['theme']);
        $this->_umbrellaTheme->setId($themeData['id']);

        //Add subthemes to the theme
       $this->_umbrellaTheme->getAllSubThemes();

        return $this->_umbrellaTheme;
    }

    //This returns a list of subthemes based on the provided id
    function getUmbrellaThemeSubThemes($id) {
        $themeData = $this->_objDbProductThemes->getUmbrellaThemeByID($id);
       
        return $this->_umbrellaTheme->getAllSubThemes();
    }

    //This function updates a subtheme given a theme id, theme, and umbrella theme id
    function updateSubtheme($themeId, $theme, $umbrellaId) {
        return $this->_objDbProductThemes->updateTheme($id, $theme, $umbrellaId);
    }
    
    //This function updates a subtheme given a theme id, theme, and umbrella theme id
    function updateUmbrellatheme($umbrellaId, $theme) {
        return $this->_objDbProductThemes->updateUmbrellaTheme($umbrellaId, $theme);
    }

    function deleteSubTheme($id) {
        //Check if theme is in use
        $isInUse = $this->objDbProductThemes->getproductIDBythemeID($id);

        if(empty($isInUse)){
//            $this->objDbProductThemes->deleteTheme($id);
            return $this->objDbProductThemes->deleteTheme($id);
        }else{
            //Get list of products using the theme
//            $usedbyList = $this->
        }

    }

    function deleteUmbrellaTheme($id){
        //Check if the theme has subthemes
    }

}
?>
