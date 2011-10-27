<?php

if (!$GLOBALS['kewl_entry_point_run'])
    die("you cannot view directly");

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

/**
 * Used to generate the data needed for reporting.
 * Sql queries are executed generating the data.
 * @author jcsepc002
 */
class dbreporting extends dbtable {

    function init() {
        parent::init("tbl_unesco_oer_products");
    }

    /**
     * Field/s: Product_Count
     * Product_Count -> count of UNESCO Original Products
     */
    function getProdOriginal() {

        $sql = 'SELECT COUNT(id) AS Product_Count FROM tbl_unesco_oer_products
                WHERE parent_id IS NULL AND deleted = 0';
        $ProdOriginalArray = $this->getArray($sql);
        $Count = $ProdOriginalArray[0]["product_count"];

        return $Count;
    }

    /**
     * Field/s: Product_Count
     * Product_Count -> count of UNESCO Adapted Products
     */
    function getProdAdapted() {

        $sql = 'SELECT COUNT(id) AS Product_Count FROM tbl_unesco_oer_products
               WHERE parent_id IS NOT NULL AND deleted = 0';
        $ProdAdaptedArray = $this->getArray($sql);
        $Count = $ProdAdaptedArray[0]["product_count"];

        return $Count;
    }

    /**
     * Field/s: Language_Count
     * Language_Count -> number of distinct languages in original
     */
    function getNoLanguagesOriginals() {

        $sql = 'SELECT COUNT( DISTINCT language) AS Language_Count FROM
               tbl_unesco_oer_products WHERE parent_id IS NULL AND deleted = 0';
        $NoLangOri = $this->getArray($sql);
        $Count = $NoLangOri[0]["language_count"];

        return $Count;
    }

    /**
     * Field/s: Language_Count
     * Language_Count -> number of distinct languages in adapted
     */
    function getNoLanguagesAdaptations() {

        $sql = 'SELECT COUNT( DISTINCT language) AS Language_Count FROM
               tbl_unesco_oer_products WHERE parent_id IS NOT NULL 
               AND tbl_unesco_oer_products.deleted = 0';
        $NoLangApt = $this->getArray($sql);
        $Count = $NoLangApt[0]["language_count"];

        return $Count;
    }

    function getLanguageBreakdownOriginals() {

        $sql = 'SELECT tbl_langs_avail.name AS Language,
               Count(tbl_langs_avail.name) AS count
               FROM tbl_unesco_oer_products JOIN tbl_langs_avail
               ON tbl_unesco_oer_products.language = tbl_langs_avail.id
               WHERE tbl_unesco_oer_products.parent_id IS NULL 
               AND tbl_unesco_oer_products.deleted = 0
               GROUP BY tbl_langs_avail.name
               ORDER BY count DESC';
        $LanguageOriginal = $this->getArray($sql);
        $Count = $LanguageOriginal;

        return $Count;
    }

    function getLanguageBreakdownAdaptations() {

        $sql = 'SELECT tbl_langs_avail.name AS Language,
               Count(tbl_langs_avail.name) AS count
               FROM tbl_unesco_oer_products JOIN tbl_langs_avail
               ON tbl_unesco_oer_products.language = tbl_langs_avail.id
               WHERE tbl_unesco_oer_products.parent_id IS NOT NULL 
               AND tbl_unesco_oer_products.deleted = 0
               GROUP BY tbl_langs_avail.name
               ORDER BY count DESC';
        $LanguageOriginal = $this->getArray($sql);
        $Count = $LanguageOriginal;

        return $Count;
    }

    function getBreakdownCountryAdaptations() {

        $sql = 'SELECT tbl_unesco_oer_product_adaptation_data.country_code AS Country,
               COUNT(tbl_unesco_oer_product_adaptation_data.country_code) AS Count
               FROM tbl_unesco_oer_product_adaptation_data
               JOIN tbl_unesco_oer_products
               ON tbl_unesco_oer_products.id = tbl_unesco_oer_product_adaptation_data.product_id
               WHERE tbl_unesco_oer_products.deleted = 0
               GROUP BY tbl_unesco_oer_product_adaptation_data.country_code
               ORDER BY Count DESC';
        $Country = $this->getArray($sql);
        $Count = $Country;

        return $Count;
    }

    function getBreakdownTypeOriginal() {
        $sql = 'SELECT tbl_unesco_oer_resource_types.description AS Description,
               COUNT(tbl_unesco_oer_resource_types.description) AS Count
               FROM tbl_unesco_oer_products
               JOIN tbl_unesco_oer_resource_types
               ON tbl_unesco_oer_products.resource_type =  tbl_unesco_oer_resource_types.id
               WHERE tbl_unesco_oer_products.parent_id IS NULL 
               AND tbl_unesco_oer_products.deleted = 0
               GROUP BY tbl_unesco_oer_resource_types.description
               ORDER BY Count DESC';
        $Type = $this->getArray($sql);
        $Count = $Type;

        return $Count;
    }

    function getBreakdownTypeAdaptation() {
        $sql = 'SELECT tbl_unesco_oer_resource_types.description AS Description,
               COUNT(tbl_unesco_oer_resource_types.description) AS Count
               FROM tbl_unesco_oer_products
               JOIN tbl_unesco_oer_resource_types
               ON tbl_unesco_oer_products.resource_type =  tbl_unesco_oer_resource_types.id
               WHERE tbl_unesco_oer_products.parent_id IS NOT NULL 
               AND tbl_unesco_oer_products.deleted = 0
               GROUP BY tbl_unesco_oer_resource_types.description
               ORDER BY Count DESC';
        $Type = $this->getArray($sql);
        $Count = $Type;

        return $Count;
    }

    function getCountryName($countryCode) {

        $country = $this->getObject('languagecode', 'language');
        $countryName = $country->getName($countryCode);
        return $countryName;
    }

    function getRegionBreakdownAdaptation() {
        $sql = 'SELECT tbl_unesco_oer_regions.region AS Region,
               Count(tbl_unesco_oer_regions.region) AS Count
               FROM tbl_unesco_oer_product_adaptation_data
               JOIN tbl_unesco_oer_regions
               ON tbl_unesco_oer_product_adaptation_data.region =  tbl_unesco_oer_regions.id
               JOIN tbl_unesco_oer_products
               ON tbl_unesco_oer_products.id = tbl_unesco_oer_product_adaptation_data.product_id
               WHERE tbl_unesco_oer_product_adaptation_data.region IS NOT NULL
               AND tbl_unesco_oer_products.deleted = 0
               GROUP BY tbl_unesco_oer_regions.region
               ORDER BY Count DESC';
        $Regions = $this->getArray($sql);
        $Count = $Regions;
        return $Count;
    }

    function getInstitutionTypeBreakdownAdaptation() {
        $sql = 'SELECT tbl_unesco_oer_institution_types.type AS Type,
               Count(tbl_unesco_oer_group_institutions.group_id) AS Count
               FROM tbl_unesco_oer_group_institutions
               JOIN tbl_unesco_oer_institutions
               ON tbl_unesco_oer_group_institutions.institution_id = tbl_unesco_oer_institutions.id
               JOIN tbl_unesco_oer_institution_types
               ON tbl_unesco_oer_institutions.type = tbl_unesco_oer_institution_types.id
               WHERE tbl_unesco_oer_group_institutions.institution_id IS NOT NULL
               GROUP BY tbl_unesco_oer_institution_types.type
               ORDER BY Count DESC';

        $type = $this->getArray($sql);
        $Count = $type;
        return $Count;
    }

    function getEvolutionByOriginal() {
        $sql = 'SELECT EXTRACT(MONTH FROM tbl_unesco_oer_products.date) AS Month,
               COUNT(EXTRACT(MONTH FROM tbl_unesco_oer_products.date)) AS Count
               FROM tbl_unesco_oer_products
               WHERE tbl_unesco_oer_products.parent_id IS NULL 
               AND tbl_unesco_oer_products.deleted = 0
               GROUP BY Month
               ORDER BY Count DESC';
        $evol = $this->getArray($sql);
        $Count = $evol;

        return $Count;
    }

    function getEvolutionByAdaptation() {
        $sql = 'SELECT EXTRACT(MONTH FROM tbl_unesco_oer_products.date) AS Month,
               COUNT(EXTRACT(MONTH FROM tbl_unesco_oer_products.date)) AS Count
               FROM tbl_unesco_oer_products
               WHERE tbl_unesco_oer_products.parent_id IS NOT NULL 
               AND tbl_unesco_oer_products.deleted = 0
               GROUP BY Month
               ORDER BY Count DESC';
        $evol = $this->getArray($sql);
        $Count = $evol;
        return $Count;
    }

    function getProductThemes() {
        $sql = 'SELECT theme FROM tbl_unesco_oer_product_themes';
        $themes = $this->getArray($sql);
        return $themes;
    }

    //functions used to populate downloadable report - all adaptations
    function getRegionID($regions) {

        $arrayCount = sizeof($regions);
        $regionID = array();

        for ($i = 0; $i < $arrayCount; $i++) {

            switch ($regions[$i]) {

                case 'Africa' :
                    $regionID[] = "'region_1'";
                    break;
                case 'Arab States' :
                    $regionID[] = "'region_2'";
                    break;
                case 'Asia and the Pacific' :
                    $regionID[] = "'region_3'";
                    break;
                case 'Europe and North America':
                    $regionID[] = "'region_4'";
                    break;
                default:
                    $regionID[] = "'region_1'";
            }
        }

        return $regionID;
    }

    function getProductsByRegion($regions) {

        $arrayCount = sizeof($regions);
        $allSql = array();
        for ($x = 0; $x < $arrayCount; $x++) {
            $sql = "SELECT tbl_unesco_oer_products.title, tbl_unesco_oer_products.creator
                FROM tbl_unesco_oer_products
                JOIN tbl_unesco_oer_product_adaptation_data
                ON tbl_unesco_oer_product_adaptation_data.product_id = tbl_unesco_oer_products.id
                WHERE tbl_unesco_oer_product_adaptation_data.region = $regions[$x] AND tbl_unesco_oer_products.parent_id IS NOT NULL
                AND tbl_unesco_oer_products.deleted = 0";

            $allSql[] = $this->getArray($sql);
        }

        return $allSql;
    }

    function getThemeID($themeID) {

        $arrayCount = sizeof($themeID);
        $allSql = array();
        for ($x = 0; $x < $arrayCount; $x++) {
            $query = "'$themeID[$x]'";
            $sql = "SELECT tbl_unesco_oer_product_themes.id
                FROM tbl_unesco_oer_product_themes
                WHERE tbl_unesco_oer_product_themes.theme = $query";
            $allSql[] = $this->getArray($sql);
        }

        return $allSql;
    }

    function getProductsByTheme($theme) {

        $arrayCount = sizeof($theme);
        $allSql = array();

        for ($x = 0; $x < $arrayCount; $x++) {
            $themeName = "'" . $theme[$x][0]["id"] . "'";
            $sql = "SELECT tbl_unesco_oer_products.title, tbl_unesco_oer_products.creator
                FROM tbl_unesco_oer_products
                JOIN tbl_unesco_oer_product_theme_junction
                ON tbl_unesco_oer_product_theme_junction.product_id = tbl_unesco_oer_products.id
                WHERE tbl_unesco_oer_product_theme_junction.theme_id = $themeName AND tbl_unesco_oer_products.parent_id IS NOT NULL
                AND tbl_unesco_oer_products.deleted = 0 ";
            $allSql[] = $this->getArray($sql);
        }

        return $allSql;
    }

    function getAdaptationTypeID($adaptationTypes) {

        $arrayCount = sizeof($adaptationTypes);
        $allSql = array();
        for ($x = 0; $x < $arrayCount; $x++) {
            $query = "'$adaptationTypes[$x]'";
            $sql = "SELECT tbl_unesco_oer_resource_types.id
                FROM tbl_unesco_oer_resource_types
                WHERE tbl_unesco_oer_resource_types.description = $query";
            $allSql[] = $this->getArray($sql);
        }        

        return $allSql;
    }

    function getProductsByType($type) {

        $arrayCount = sizeof($type);
        $allSql = array();

        for ($x = 0; $x < $arrayCount; $x++) {
            $typeName = "'" . $type[$x][0]["id"] . "'";
            $sql = "SELECT tbl_unesco_oer_products.title, tbl_unesco_oer_products.creator 
                FROM tbl_unesco_oer_products
                WHERE tbl_unesco_oer_products.resource_type = $typeName AND tbl_unesco_oer_products.parent_id IS NOT NULL
                AND tbl_unesco_oer_products.deleted = 0";
            $allSql[] = $this->getArray($sql);
        }
        
        return $allSql;
    }

    function getInstitutionByTypes($institutionTypes) {

        $arrayCount = sizeof($institutionTypes);
        $allSql = array();
        for ($x = 0; $x < $arrayCount; $x++) {
            $query = "'$institutionTypes[$x]'";
            $sql = "SELECT tbl_unesco_oer_institution_types.id
                FROM tbl_unesco_oer_institution_types
                WHERE tbl_unesco_oer_institution_types.type = $query";
            $allSql[] = $this->getArray($sql);
        }

        $arrayCount = sizeof($allSql);
        $allSql1 = array();

        for ($x = 0; $x < $arrayCount; $x++) {
            $query = "'" . $allSql[$x][0]["id"] . "'";
            $sql = "SELECT tbl_unesco_oer_products.title, tbl_unesco_oer_products.creator
                FROM tbl_unesco_oer_product_adaptation_data
                JOIN tbl_unesco_oer_institutions
                ON tbl_unesco_oer_institutions.id = tbl_unesco_oer_product_adaptation_data.institution_id
                JOIN tbl_unesco_oer_products
                ON tbl_unesco_oer_products.id = tbl_unesco_oer_product_adaptation_data.product_id
                WHERE tbl_unesco_oer_institutions.type = $query ";
            $allSql1[] = $this->getArray($sql);
        }
        
        return $allSql1;
    }
    
    function getLangID($langNames){
                
        $arrayCount = sizeof($langNames);
        $allSql = array();
        for ($x = 0; $x < $arrayCount; $x++) {
            $query = "'$langNames[$x]'";
            $sql = "SELECT tbl_langs_avail.id
                FROM tbl_langs_avail
                WHERE tbl_langs_avail.name = $query";
            $allSql[] = $this->getArray($sql);
        }

        return $allSql;
        
    }
    
    function getProductsByLanguage($langID){
        
        $arrayCount = sizeof($langID);
        $allSql = array();

        for ($x = 0; $x < $arrayCount; $x++) {
            $lang = "'" . $langID[$x][0]["id"] . "'";
            $sql = "SELECT tbl_unesco_oer_products.title, tbl_unesco_oer_products.creator
                FROM tbl_unesco_oer_products
                WHERE tbl_unesco_oer_products.language = $lang AND tbl_unesco_oer_products.parent_id IS NOT NULL
                AND tbl_unesco_oer_products.deleted = 0";
            $allSql[] = $this->getArray($sql);
        }

        return $allSql;
        
    }

    function createReportQuery($adaptationTypes, $institutionTypes, $countryNames, $themeNames, $langNames, $regions) {
        
        $arrayQuery = array();
        
        $regionID = $this->getRegionID($regions);
        $regionSQL = $this->getProductsByRegion($regionID);
        $arrayQuery[] = $regionSQL;
        
        $themeID = $this->getThemeID($themeNames);
        $themeSQL = $this->getProductsByTheme($themeID);
        $arrayQuery[] = $themeSQL;
        
        $typeID = $this->getAdaptationTypeID($adaptationTypes);
        $typeSQL = $this->getProductsByType($typeID);
        $arrayQuery[] = $typeSQL;
        
        $intsitutionSQL = $this->getInstitutionByTypes($institutionTypes);        
        $arrayQuery[] = $intsitutionSQL;
        
        $langs = $this->getLangID($langNames);
        $langSQL = $this->getProductsByLanguage($langs);
        $arrayQuery[] = $langSQL;
        $allProductsArr = array();
        for ($x=0; $x <5; $x++){            
            
            $tempArray = $arrayQuery[$x];
            $allProducts = "";
            foreach ($tempArray as $Products){
                foreach($Products as $Prod ){
                    
                    foreach($Prod as $Prodd){
                      $allProducts .= $Prodd." ";  
                    }
                    
                }
                
            }
            $allProductsArr[] = $allProducts;
        }

        return $allProductsArr;
    }

}

?>