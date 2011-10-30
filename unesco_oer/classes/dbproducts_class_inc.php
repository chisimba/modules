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

    private $adaptationTable;

    private $objUser;

    function init()
    {
        parent::init("tbl_unesco_oer_products");
        $this->adaptationTable = "tbl_unesco_oer_product_adaptation_data";
        $this->objUser = $this->getObject('user', 'security');
    }

    function getProductTitle($title)
    {
        $sql = "select * from $this->_tableName where title = '$title'";

        return $this->getArray($sql);
    }
    
     function getProductlanguages()
    {
        $sql = "select distinct country_code from $this->adaptationTable ";

        return $this->getArray($sql);
    }
    
     function getProductidbycountry($country)
    {
        $sql = "select product_id from $this->adaptationTable where country_code = '$country' ";

        return $this->getArray($sql);
    }
    
    function getadapted($id)
    {
        $sql = "select * from $this->_tableName where parent_id = '$id' and deleted = 0";
        

        return $this->getArray($sql);
    }

    function getTotalEntries($filter)
    {
        $sql = "SELECT * FROM $this->_tableName where $filter";
        $count = $this->getArray($sql);

        return count($count);
    }

    function getFilteredProducts($filter)
    {
        $sql = "select * from $this->_tableName where $filter";

        return $this->getArray($sql);
    }

     function getAdaptedProducts($filter)
    {
        $sql = "select * from tbl_unesco_oer_groups $filter";

        return $this->getArray($sql);
    }

    function getProducts($start, $end)
    {
        $sql = "select * from $this->_tableName limit $start,$end";

        return $this->getArray($sql);
    }
    
     function getAllProducts()
    {
        $sql = "select * from $this->_tableName ";

        return $this->getArray($sql);
    }

    function addProduct($productArray, $adaptationData = NULL)
    {
        $tags = $productArray['lucene_tags'];
        unset($productArray['lucene_tags']);
        $id = $this->insert($productArray);

        if (is_array($adaptationData))
        { 
            $adaptationData['product_id'] = $id;
            $this->insert($adaptationData, $this->adaptationTable);
        }

        $result = $id;

        if ($result != FALSE) {
            $this->addLuceneIndex($id,$productArray, $tags);
        }
        return $result;
    }

    function addLuceneIndex($id, $productArray, $tags = array()) {

            //$this->objKeywords->addStoryKeywords($id, $keyTags);

            //$objTags = $this->getObject('dbnewstags');
            //$objTags->addStoryTags($id, $tags);

            // Call Object
            $objIndexData = $this->getObject('indexdata', 'search');

            // Prep Data
            $docId = 'unesco_oer_products_'.$id;
            $docDate = $productArray['date'];
            $url = $this->uri(array('action'=>'ViewProduct', 'id'=>$id), 'unesco_oer');
            $title = stripslashes($productArray['title']);

            // Remember to add all info you want to be indexed to this field
            $contents = stripslashes($productArray['title']).' '. stripcslashes($productArray['description']).' '.stripslashes($productArray['abstract']);

            // A short overview that gets returned with the search results
            $objTrim = $this->getObject('trimstr', 'strings');
            $teaser = $objTrim->strTrim(strip_tags(stripslashes($productArray['description'])), 300);

            $module = 'unesco_oer';

            $additionalSearchIndex = array(
                    'alternativetitle' => $productArray['alternative_title'],
                    'resourcetype' => $productArray['resource_type']
                );

            $userId = $this->objUser->userId();

//            if (is_array($tags)) $tags = 'array';
//            else $tags = 'noarray';

            // Add to Index
            $objIndexData->luceneIndex($docId, $docDate, $url, $title, $contents,
            $teaser, $module, $userId, $tags, NULL, NULL, NULL, NULL, NULL, NULL, $additionalSearchIndex);
    }

    function updateProduct($productID, $productArray, $adaptationData = NULL)
    {
        $tags = $productArray['lucene_tags'];
        unset($productArray['lucene_tags']);

        $product = $this->getProductByID($productID);

        $this->update('puid', $product['puid'], $productArray   );

        if (is_array($adaptationData))
        {
            $data = $this->getAdaptationDataByProductID($productID);
            $this->update('puid', $data['puid'], $adaptationData, $this->adaptationTable);
        }
        if ($productArray['deleted'] == 0){
            $this->addLuceneIndex($productID, $productArray, $tags);
        } else {
            $objIndexData = $this->getObject('indexdata', 'search');
            $docId = "unesco_oer_products_$productID";
            $objIndexData->removeIndex($docId);
        }
    }

    //TODO Ntsako check the hierichal storage of data to make this more efficient
    function getNoOfAdaptations($parentId)
    {
        $sql = "SELECT * FROM $this->_tableName WHERE parent_id = '$parentId' AND deleted=0";
        $child = $this->getArray($sql);

        return count($child);
    }

    function getMostAdaptedProducts($displayAllMostAdaptedProducts) {
        //If the more link has been clicked, retrieve all adapted products
        if($displayAllMostAdaptedProducts == true){
            $sql = "SELECT parent_id, creator, count(*) AS total FROM $this->_tableName WHERE parent_id IS NOT NULL GROUP BY parent_id ORDER BY total DESC";
        }else{//By default, display only the three most adapted products
            $sql = "SELECT parent_id, creator, count(*) AS total FROM $this->_tableName WHERE parent_id IS NOT NULL GROUP BY parent_id ORDER BY total DESC LIMIT 3";
        }
        
        return $this->getArray($sql);
    }

    function getProductByID($id){
        //TODO change function so it can identify if the $id is a puid or a normal id
        //TODO this function currently fails when you have more than 99 products
        //If searching by id
        $sql = '';
        if (strlen($id)>2){
            $sql = "select * from $this->_tableName where id = '$id'";
        }  else {
            //If searching by puid
            $sql = "select * from $this->_tableName where id = '$id'";
        }
        //$sql = "select * from $this->_tableName where id = '$id'";
        $products = $this->getArray($sql);
        return $products[0]; //TODO add error handler for non unique ID.
    }

    function getAdaptationDataByProductID($id) {
        $sql = "select * from $this->adaptationTable where product_id = '$id'";

        $data = $this->getArray($sql);
        return $data[0];
    }
    
   
    
    /*
     * function takes a creator name and if it has an adaptation
     * @param $creatorName
     * return boolean
     */

    function hasAnAdaptation($creatorName) {
        $sql = "SELECT * FROM $this->_tableName WHERE creator ='$creatorName'";
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

    /**This function returns an array of OER's that are not translations of other
     * originals.
     *
     * @return array
     */
    function getTranslatableOriginals() {
        $filter = 'WHERE parent_id IS NULL AND translation_of IS NULL AND deleted=0';
        return $this->getAll($filter);
    }

    function getAllTranslationsByID($productID){

        $product = $this->getRow('id', $productID);
        $translationID = NULL;

        if (empty ($product['translation_of'])) {
            $translationID = $productID;
        } else {
            $translationID = $product['translation_of'];
        }

        $translationsOfProduct = $this->getAll("WHERE translation_of='$translationID'");
        $translatedProduct = $this->getAll("WHERE id='$translationID'");
        return array_merge($translatedProduct, $translationsOfProduct);
    }

    public function getAllAssocAdaptations($id, $groupid) {
        
    }
}
?>