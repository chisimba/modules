<?php

/**
 * This is a DB layer that manages keywords
 *
 * @author davidwaf
 */
class dbkeywords extends dbtable {

    private $tableName = 'tbl_oer_product_keywords';

    function init() {
        parent::init($this->tableName);
    }

    /**
     * this returns the key words
     */
    function getKeyWords() {
        $sql = "select * from $this->tableName";
        return $this->getArray($sql);
    }

    

    /**
     * inserts new key word into database
     * @param type $keyword
     * @return type 
     */
    function addKeyWord($keyword) {
        $data = array("keyword"=>$keyword);
        return $this->insert($data);
    }

}

?>
