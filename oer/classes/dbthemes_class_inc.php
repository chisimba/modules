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
     * this selects themes
     */
    function getThemes() {
        $sql = "select th.theme as theme,uth.theme as umbrellatheme from tbl_oer_themes th, tbl_oer_umbrella_themes uth where th.umbrellatheme = uth.id";
        return $this->getArray($sql);
    }

    /**
     * selects and formats themes
     * @return string 
     */
    function getThemesFormatted() {
        $sql = "select th.id,th.theme as theme,uth.theme as umbrellatheme from tbl_oer_themes th, tbl_oer_umbrella_themes uth where th.umbrellatheme = uth.id";
        $data = $this->getArray($sql);
        $themes = array();
        foreach ($data as $row) {
            $themes[] = array(
                "id" => $row['id'],
                "theme" => $row['theme'] . ' (' . $row['umbrellatheme'] . ')'
            );
        }
        return $themes;
    }

    /**
     * inserts a new theme
     * @param type $title
     * @return type 
     */
    function addTheme($title, $umbrellaTheme) {
        $data = array("theme" => $title, 'umbrellatheme' => $umbrellaTheme);
        return $this->insert($data);
    }
    
    /**
     * Returns theme data for a supplied id
     * @param type $id
     * @return type 
     */
    function getTheme($id){
        $sql="select * from tbl_oer_themes where id = '".$id."'";
        $data= $this->getArray($sql);
        if(count($data) > 0){
            return $data[0];
        }else{
            return null;
        }
    }

}

?>
