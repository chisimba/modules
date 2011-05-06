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

class dbavailableproductlanguages extends dbtable
{

    function init()
    {
        parent::init("tbl_unesco_oer_available_product_languages");
    }

    /**
     *
     * Get the languages that the product is available in
     *
     * @access public
     * @param string $product_id The identity of the product
     * @return Array containing the languages that the product is available in
     *
     */
    function getProductLanguage($product_id)
    {
        $sql = "select language from tbl_unesco_oer_available_product_languages where product_id = '$product_id'";
        $languages = $this->getArray($sql);
        return $languages;
    }
 
     /**
     *
     *Add a new language that a product is available in
     *
     * @access public
     * @param string $product_id, $language The identity of the product and the language
     * @return Boolean, if the addition is successful, true, if not false.
     *
     */
    function addProductLanguage($product_id, $language)
    {
        $data = array(
            'product_id' => $product_id,
            'language' => $language
        );

        $this->insert($data);
    }

    function getAll()
    {
        $sql = "select * from tbl_unesco_oer_available_product_languages";

        return $this->getArray($sql);
    }

}
?>
