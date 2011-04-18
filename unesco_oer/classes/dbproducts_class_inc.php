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
 * @author jcse1
 */
class dbproducts extends dbtable
{

    function init()
    {
        parent::init("tbl_unesco_oer_products");
    }

    function getProductTitle($title)
    {
        $sql = "select * from tbl_unesco_oer_products where title = '$title'";

        return $this->getArray($sql);
    }

    function getTotalEntries($filter)
    {
        $sql = "SELECT * FROM tbl_unesco_oer_products where $filter";
        $count = $this->getArray($sql);

        return count($count);
    }

    function getFilteredProducts($filter)
    {
        $sql = "select * from tbl_unesco_oer_products where $filter";

        return $this->getArray($sql);
    }

    function getProducts($start, $end)
    {
        $sql = "select * from tbl_unesco_oer_products limit $start,$end";

        return $this->getArray($sql);
    }

    function addProduct($productArray)
    {
        $this->insert($productArray);
    }

    //TODO Ntsako check the hierichal storage of data to make this more efficient
    function getNoOfAdaptations($parentId)
    {
        $sql = "SELECT * FROM tbl_unesco_oer_products WHERE parent_id = '$parentId'";
        $child = $this->getArray($sql);

        return count($child);
    }

    function getMostAdaptedProducts($displayAllMostAdaptedProducts) {
        //If the more link has been clicked, retrieve all adapted products
        if($displayAllMostAdaptedProducts == true){
            $sql = "SELECT parent_id, creator, count(*) AS total FROM tbl_unesco_oer_products GROUP BY parent_id ORDER BY total DESC";
        }else{//By default, display only the three most adapted products
            $sql = "SELECT parent_id, creator, count(*) AS total FROM tbl_unesco_oer_products GROUP BY parent_id ORDER BY total DESC LIMIT 3";
        }
        
        return $this->getArray($sql);
    }

    function getProductByID($id){
        //TODO change function so it can identify if the $id is a puid or a normal id
        //TODO this function currently fails when you have more than 99 products
        //If searching by id
        $sql = '';
        if (strlen($id)>2){
            $sql = "select * from tbl_unesco_oer_products where id = '$id'";
        }  else {
            //If searching by puid
            $sql = "select * from tbl_unesco_oer_products where puid = '$id'";
        }
        //$sql = "select * from tbl_unesco_oer_products where id = '$id'";
        $products = $this->getArray($sql);
        return $products[0]; //TODO add error handler for non unique ID.
    }
    
    /*
     * function takes a creator name and if it has an adaptation
     * @param $creatorName
     * return boolean
     */

    function hasAnAdaptation($creatorName) {
        $sql = "SELECT * FROM tbl_unesco_oer_products WHERE creator ='$creatorName'";
        $Array = $this->getArray($sql);
        for ($i = 0; $i < count($Array); $i++) {
            if ($Array[$i]['id'] == $Array[$i]['parent_id']) {
                $Adaptation = 1; // True;
                return $Adaptation;
            } else {
                $Adaptation = 0; // False
                return $Adaptation;
            }
        }       
    }

    function isAdaptation($id){
        $product = $this->getProductByID($id);
        if ($product['parent_id'] == null){
            return FALSE;
        }else{
            return TRUE;
        }
    }

}
?>
