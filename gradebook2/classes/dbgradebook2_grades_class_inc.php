<?php
/* ----------- data class extends dbTable for tbl_gradebook2_grades------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
/**
 * Model class for the table tbl_gradebook2_grades
 * @author Paul Mungai
 * @copyright 2010 University of the Western Cape
 */
class dbgradebook2_grades extends dbTable
{
    /**
     * Constructor method to define the table
     */
    function init() 
    {
        parent::init('tbl_gradebook2_grades');
        $this->objUser = &$this->getObject('user', 'security');
    }
    /**
     * Return all records
     * @param string $learnerid The Learner ID
     * @return array The entries
     */
    function listAll($learnerid) 
    {
        return $this->getAll("WHERE learnerid='" . $learnerid . "'");
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
        $sql = "SELECT * FROM tbl_gradebook2_grades";
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
     * @param string $columnId The Column Id
     * @param string $totalGrade Total grade
     */
    function insertSingle($learnerId,$columnId,$totalGrade) 
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
