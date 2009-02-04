<?php
// security check - must be included in all scripts
if (! /**
 * Description for $GLOBALS
 * @global unknown $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS ['kewl_entry_point_run']) {
    die ( "You cannot view this page directly" );
}
/**
 *
 */
/**
 * @access     public
 */
class dbtwits extends dbtable {

    /**
     *
     * @var langauge an object reference.
     */
    public $objLanguage;

    /**
     * Method that initializes the objects
     *
     * @access private
     * @return nothing
     */
    public function init() {
        parent::init('tbl_twitsocial');
    }


    public function saveRecords($arr) {
        if(!is_array($arr)) {
            return $this->insert($rec);
        }
        foreach ($arr as $rec) {
            $this->insert($rec);
        }
        return;
    }


    public function getUnchecked() {
        $ret = $this->getAll("WHERE checked = 0");
        return $ret;
    }

    public function setCheck($id) {
        echo "updated $id <br />";
        $this->update('id', $id, array('checked' => 1), 'tbl_twitsocial');
    }
} //end of class
?>