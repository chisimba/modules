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



/**
 * Description of dbproductkeywords_class_inc
 *
 * @author manie
 */
class dbproductkeywords extends dbtable
{
    private $product_keyword_jxn;

    function init()
    {
        parent::init('tbl_unesco_oer_product_keywords');
        $this->product_keyword_jxn = 'tbl_unesco_oer_product_keyword_junction';
    }

    function getProductKeywords()
    {
        $sql = "SELECT * FROM $this->_tableName";
        return $this->getArray($sql);
    }

    function getKeywordsByProductID($productID)
    {
        $sqlJxnTable = "select * from $this->product_keyword_jxn where product_id = '$productID'";
        $sql = "SELECT $this->_tableName.id, $this->_tableName.keyword
                FROM $this->_tableName
                INNER JOIN ($sqlJxnTable) As t2
                On $this->_tableName.id=t2.keyword_id";
        return $this->getArray($sql);
    }

    function addKeyword($keyword)
    {
        $data = array(
          'keyword'  => $keyword
        );
        $this->insert($data);
    }

    function addProductKeywordJxn($productID, $keywordID)
    {
        $data = array(
          'product_id' => $productID,
          'keyword_id' => $keywordID
        );
        
        $this->insert($data);
    }
}
?>
