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
 * Class for providing access to the table tbl_test_question_calculated in the database
 * @author Nguni Phakela
 *
 * @copyright (c) 2004 UWC
 * @package mcqtests
 * @version 1.2
 */
class dbquestion_calculated extends dbtable
{
    /**
     * Method to construct the class and initialise the table
     *
     * @access public
     * @return
     */
    public function init()
    {
        parent::init('tbl_test_question_calculated');
        $this->table = 'tbl_test_question_calculated';
    }
}
?>