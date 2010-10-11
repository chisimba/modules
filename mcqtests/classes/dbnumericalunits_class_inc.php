<?php
/**
 * @package mcqtests
 * @filesource
 */
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
 * Class for providing access to the table tbl_test_numericalunits in the database
 * @author Nguni Phakela
 *
 * @copyright (c) 2010 University of the Witwatersrand
 * @package mcqtests
 * @version 1.2
 */
class dbnumericalunits extends dbtable {
    /**
     * Method to construct the class and initialise the table.
     *
     * @access public
     * @return
     */
    public $table;

    public function init() {
        parent::init('tbl_test_question_numericalunits');
        $this->table = 'tbl_test_question_numericalunits';
    }

    public function addNumericalUnits($data) {
        $this->insert($data);
    }

    public function updateNumericalUnits($data, $id) {
        $this->update("questionid", $id, $data);
    }

    public function deleteNumericalUnit($id) {
        $this->delete('id', $id);
    }

    public function getNumericalUnits($id) {
        $units = $this->getAll("WHERE questionid = '$id'");
        return $units[0];
    }
}

?>