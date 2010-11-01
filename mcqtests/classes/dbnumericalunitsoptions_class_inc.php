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
 * Class for providing access to the table tbl_test_question_numericaloptions in the database
 * @author Nguni Phakela
 *
 * @copyright (c) 2010 University of the Witwatersrand
 * @package mcqtests
 * @version 1.202
 */
class dbnumericalunitsoptions extends dbtable {
    /**
     * Method to construct the class and initialise the table.
     *
     * @access public
     * @return
     */
    public $table;

    public function init() {
        parent::init('tbl_test_question_numericaloptions');
        $this->table = 'tbl_test_question_numericaloptions';
    }

    public function addNumericalOptions($data) {
        //insert into this table
        return $this->insert($data);
    }

    public function updateNumericalQuestions($id, $data) {
        $this->update('questionid', $id, $data);
    }

    public function deleteNumericalOptions($id) {
        $this->delete('questionid', $id);
    }
}
?>