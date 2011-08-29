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

    function getProductKeywordByID($id)
    {
        $row = $this->getRow('id', $id);
        return $row;
    }

    function getKeywordsByProductID($productID)
    {
        $sqlJxnTable = "select product_id, keyword_id from $this->product_keyword_jxn where product_id = '$productID'";
        $sql = "SELECT *
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
        
        $this->insert($data,  $this->product_keyword_jxn);
    }

    function deleteProductKeywordJxnByProductID($productID)
    {
        $sql = "DELETE FROM $this->product_keyword_jxn WHERE product_id = '$productID'";
        //$this->_execute($sql);
        $this->delete('product_id', $productID, $this->product_keyword_jxn);
    }

    function deleteKeyword($id) {
        $this->delete('id', $id);
        $this->delete('keyword_id', $id, $this->product_keyword_jxn);
    }

    function updateKeyword($id, $newKeyword){
        $this->update('id', $id, array('keyword' => $newKeyword));
    }
}
?>
