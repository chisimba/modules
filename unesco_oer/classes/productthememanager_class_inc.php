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
        //$this->_umbrellaTheme->getAllSubThemes();

        return $this->_umbrellaTheme;
    }

    //This returns an array of subthemes based on the provided id
    function getUmbrellaThemeSubThemes($id) {
        $this->_umbrellaTheme->setId($id);
        
        return $this->_umbrellaTheme->getAllSubThemes();
    }

    //This function updates a subtheme given a theme id, theme, and umbrella theme id
    function updateSubTheme($themeId, $theme, $umbrellaId) {
        return $this->_objDbProductThemes->updateTheme($themeId, $theme, $umbrellaId);
    }

    //This function updates a subtheme given a theme id, theme, and umbrella theme id
    function updateUmbrellaTheme($umbrellaId, $theme) {
        return $this->_objDbProductThemes->updateUmbrellaTheme($umbrellaId, $theme);
    }

    /**
     * These function deletes a subtheme from the tbl_unesco_oer_product_themes table
     * @param <type> $id
     * @return <type>
     * Returns Array() if the theme is deleted or when the theme was not found in the table
     * Returns FALSE
     */
    function deleteSubTheme($id) {
        //Check if theme is in use
        //Could also check if the theme exists
        $isInUse = $this->subThemeInUse($id);

        if (!($isInUse)) {
            return $this->_objDbProductThemes->deleteTheme($id);
        } else {//If the subTheme is being used by a product
            $themeInUse = $this->getSubTheme($id);
            //Check for the product that is using the theme
//            return "Theme is in use";
            return FALSE;
        }
    }

    function subThemeInUse($id) {
        $isInUse = $this->_objDbProductThemes->getproductIDBythemeID($id);
        
        if (!(empty($isInUse))) {
            return TRUE;
        } else {//If the subTheme is being used by a product
//            $themeInUse = $this->getSubTheme($id);
            //Check for the product that is using the theme
//            return "Theme is in use";
            return FALSE;
        }

    }

    function deleteUmbrellaTheme($id) {
        //Check if the one of it's subthemes is in use
        $this->_productthemelist = $this->getUmbrellaThemeSubThemes($id);

        //Check if theme has subthemes
        if(count($this->_productthemelist)>0){//Means Umbrella theme has sub themes
            return FALSE;
        }

        $subThemeInUse = FALSE;
        //Check if any of the subthemes that belong to the umbrella theme are in use
        //I think this is redundant since an umbrella theme with subthemes can not be deleted
        foreach ($this->_productthemelist as $subTheme) {
            $subThemeId = $subTheme->getId();   //Get the current sub themes Id
            $subThemeInUse = $this->subThemeInUse($subThemeId);
            if($subThemeInUse){
                return FALSE;
            }
        }//Else if non of the subthemes are in use

        $this->_objDbProductThemes->deleteUmbrellaTheme($id);
        return TRUE;
    }

}
?>