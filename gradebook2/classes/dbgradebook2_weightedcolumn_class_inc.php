<?php
/* ----------- data class extends dbTable for tbl_gradebook2_weightedcolumn------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
/**
 * Model class for the table tbl_gradebook2_weightedcolumn
 * @author Paul Mungai
 * @copyright 2010 University of the Western Cape
 */
class dbgradebook2_weightedcolumn extends dbTable
{
    /**
     * Constructor method to define the table
     */
    function init() 
    {
        parent::init('tbl_gradebook2_weightedcolumn');
        $this->objUser = &$this->getObject('user', 'security');
    }
    /**
     * Return all records
     * @param string $userId The User ID
     * @return array The entries
     */
    function listAll($userId) 
    {
        return $this->getAll("WHERE userid='" . $userId . "'");
    }
    /**
     * Return a single record
     * @param string $id ID
     * @return array The values
     */
    function listSingle($id) 
    {
        return $this->getAll("WHERE id='" . $id . "'");
    }
    /**
     * Return all records
     * @return array The values
     */
    function getAllRecords() 
    {
        $sql = "SELECT * FROM tbl_gradebook2_weightedcolumn";
        $data = $this->getArray($sql);
        if (!empty($data)) {
            return $data;
        } else {
            return FALSE;
        }
    }
    /**
     * Insert a record
     * @param string $learnerId The learner ID
     * @param string $totalGrade Total grade
     */
    function insertSingle($learnerId,$totalGrade) 
    {
        $id = $this->insert(array(
            'learnerid' => $learnerId,
            'totalgrade' => $totalGrade
        ));
        return $id;
    }
    /**
     * Update a record
     * @param string $id ID
     * @param string $totalgrade totalgrade
     */
    function updateSingle($id, $totalGrade) 
    {
        $this->update("id", $id, array(
            'totalgrade' => $totalGrade
        ));
    }
    /**
     * Delete a record
     * @param string $id ID
     */
    function deleteSingle($id) 
    {
        $this->delete("id", $id);
    }
}
?>
