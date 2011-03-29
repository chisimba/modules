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
 * Module for demoing mvc arch in chisimba
 *
 * @author jcse4
 */

class dbfeaturedproduct extends dbtable {

    function init(){
        parent::init("tbl_unesco_oer_featured_products");
    }


/*This function checks that the database is empty,and insert a current featured unesco product in the database
 *if the database already has a featured product on it,an update with the current unesco featured product done
 * @param<type> $featuredProduct
 */

    function overRightCurrentFeaturedProduct($featuredProduct) {
        if ($this->getRecordCount() == 0) {
            //$this->insert($featuredProduct);
            $data = array('product_id' => $featuredProduct);
            return $this->insert($data);
        } else {
            $currentFeaturedProductArray = $this->getAll();
            //TODO add error handling for case of more than one featured product 
            //in array
            $currentFeaturedProduct = $currentFeaturedProductArray[0];
            return $this->update(
                    'puid',
                    $currentFeaturedProduct['puid'],
                    array('product_id' => $featuredProduct)
            );
        }
    }

    /*This function get the current featured product ID
     * return int
     */

    function getCurrentFeaturedProductID(){
        $sql = 'select * from tbl_unesco_oer_featured_products ';
        $featuredProductArray = $this->getArray($sql);
        if (sizeof($featuredProductArray) > 1){
            //TODO error handling (should never be greater than 1
        }
        $featuredProduct = $featuredProductArray[0];
        return $featuredProduct['product_id'];
        
        
    }

}
                ?>

