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

class dbproductthemes extends dbtable {

    private $umbrella_theme_table;
    private $product_theme_jxn_table;
    public $objUser;

    function init() {
        parent::init("tbl_unesco_oer_product_themes");
        $this->umbrella_theme_table = "tbl_unesco_oer_umbrella_themes";
        $this->product_theme_jxn_table = "tbl_unesco_oer_product_theme_junction";
        $objUser = $this->getObject('user', 'security');
    }

    function getProductThemes() {
        $sql = "select * from $this->_tableName";
        return $this->getArray($sql);
    }

    function getUmbrellaThemes() {
        $sql = "select * from $this->umbrella_theme_table";
        return $this->getArray($sql);
    }

    function deleteTheme($id) {
        $sql = "DELETE FROM $this->_tableName WHERE id = '$id'";
        $objIndexData = $this->getObject('indexdata', 'search');
        $objIndexData->removeIndex($id);
        $this->getArray($sql);
//If the theme is not an umbrella theme then it must be a subtheme
        
    }

    function deleteUmbrellaTheme($id) {
        $sql = "DELETE FROM $this->umbrella_theme_table WHERE id = '$id'";
        $this->getArray($sql);
        $objIndexData = $this->getObject('indexdata', 'search');
        $objIndexData->removeIndex($id);
    }

    function getThemesByProductID($productID) {
        $sqlJxnTable = "select * from $this->product_theme_jxn_table where product_id = '$productID'";
        $sql = "SELECT $this->_tableName.id, $this->_tableName.umbrella_theme_id, $this->_tableName.theme
                FROM $this->_tableName
                INNER JOIN ($sqlJxnTable) As t2
                On $this->_tableName.id=t2.theme_id";
        return $this->getArray($sql);
    }

    function getproductIDBythemeID($id) {
        $sql = "select * from $this->product_theme_jxn_table where theme_id = '$id'";

        return $this->getArray($sql);
    }

    function getThemesByUmbrellaID($umbrellaID) {
        $sql = "select * from $this->_tableName where umbrella_theme_id = '$umbrellaID'";
        return $this->getArray($sql);
    }

    function getThemeByID($id) {
        $row = $this->getRow('id', $id);
        return $row;
    }

    function getUmbrellaThemeByID($id) {
        $sql = "SELECT * FROM $this->umbrella_theme_table where id = '$id'";
        $row = $this->getArray($sql);
        return $row[0];
    }

    function addTheme($themeName, $umbrellID = NULL) {
        $data = array(
            'theme' => $themeName,
            'umbrella_theme_id' => $umbrellID
        );

        $this->insert($data);

        // Prepare to add context to search index
        $objIndexData = $this->getObject('indexdata', 'search');

        $saveDate = date('Y-m-d H:M:S');
        $url = $this->uri(array('action' => 'viewThemes', 'themeId' => ''), 'contextcontent');

        $objTrimStr = $this->getObject('trimstr', 'strings');
        $teaser = $objTrimStr->strTrim(strip_tags($themeName), 500);

        $userId = 4;
        //$this->objUser->userId();
        $module = 'unesco_oer';

        // Todo - Set permissions on entering course, e.g. iscontextmember.
        $permissions = NULL;

        $objIndexData->luceneIndex(NULL, $saveDate, $url, $themeName, NULL, $teaser, $module, $userId, NULL, NULL, NULL);
    }

    function addUmbrellaTheme($umbrella) {
        $data = array(
            'theme' => $umbrella
        );

        $this->insert($data, $this->umbrella_theme_table);

        // Prepare to add context to search index
        $objIndexData = $this->getObject('indexdata', 'search');

        $saveDate = date('Y-m-d H:M:S');
        $url = $this->uri(array('action' => 'viewThemes', 'themeId' => ''), 'contextcontent');

        $objTrimStr = $this->getObject('trimstr', 'strings');
        $teaser = $objTrimStr->strTrim(strip_tags($umbrella), 500);

        $userId = 45;
        //// $this->objUser->userId();
        $module = 'unesco_oer';

        // Todo - Set permissions on entering course, e.g. iscontextmember.
        $permissions = NULL;

        $objIndexData->luceneIndex(NULL, $saveDate, $url, $umbrella, NULL, $teaser, $module, $userId, NULL, NULL, NULL);

    }

    function addProductThemeJxn($productID, $themeID) {
        $data = array(
            'product_id' => $productID,
            'theme_id' => $themeID
        );

        $this->insert($data, $this->product_theme_jxn_table);
    }

    function deleteProductThemesJxnByProductID($productID) {
        $sql = "DELETE FROM $this->product_theme_jxn_table WHERE product_id = '$productID'";
        //$this->_execute($sql);
        $this->delete('product_id', $productID, $this->product_theme_jxn_table);
    }

    function updateTheme($id, $theme, $umbrellaId) {
        return $this->update('id', $id,
                array('id' => $id,
                    'theme' => $theme,
                    'umbrella_theme_id' => $umbrellaId));
    }

    function updateUmbrellaTheme($id, $theme) {
        $data = array(
            'id' => $id,
            'theme' => $theme
        );

        $this->update('id', $id, $data, $this->umbrella_theme_table);
    }

}
?>