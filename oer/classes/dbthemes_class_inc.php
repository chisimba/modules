<?php

/**
 * This is a DB layer that manages umbrella themes
 *
 * @author davidwaf
 */
class dbthemes extends dbtable {

    private $tableName = 'tbl_oer_themes';

    function init() {
        parent::init($this->tableName);
    }

    /**
     * this selects original products
     */
    function getThemes() {
        $sql = "select th.theme as theme,uth.theme as umbrellatheme from tbl_oer_themes th, tbl_oer_umbrella_themes uth where th.umbrellatheme = uth.id";
        return $this->getArray($sql);
    }
    

    /**
     * inserts a new theme
     * @param type $title
     * @return type 
     */
    function addTheme($title,$umbrellaTheme) {
        $data = array("theme" => $title,'umbrellatheme'=>$umbrellaTheme);
        return $this->insert($data);
    }

}

?>
