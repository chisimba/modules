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
        $this->objDBGrades = &$this->getObject('dbgradebook2_grades', 'gradebook2');
        // Load Context Object
        $this->objContext = $this->getObject('dbcontext', 'context');

        // Store Context Code
        $this->contextCode = $this->objContext->getContextCode();
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
     * Return all context records
     * @return array The values
     */
    function getContextGrades($contextcode=Null, $where = Null)
    {
        if (empty($contextcode))
            $contextcode = $this->contextCode;
        $sql = "SELECT * FROM tbl_gradebook2_weightedcolumn where contextcode='".$contextcode."'".$where;
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
     * @param array $colArr Contains data for every column
     */
    function insertSingle($colArr) 
    {
        $userId = $this->objUser->userId();
        $id = $this->insert(array(
            'userid' => $userId,
            'column_name' => $colArr['column_name'],
            'contextcode' => $colArr['contextcode'],
            'display_name' => $colArr['display_name'],
            'description' => $colArr['description'],
            'primary_display' => $colArr['primary_display'],
            'secondary_display' => $colArr['secondary_display'],
            'grading_period' => $colArr['grading_period'],
            'creationdate' => $this->now(),
            'include_weighted_grade' => $colArr['include_weighted_grade'],
            'running_total' => $colArr['running_total'],
            'show_grade_center_calc' => $colArr['show_grade_center_calc'],
            'show_in_mygrades' => $colArr['show_in_mygrades'],
            'show_statistics' => $colArr['show_statistics']
        ));
        return $id;
    }
    /**
     * Update a record
     * @param string $id ID
     * @param array $colArr Contains data for every column
     */
    function updateSingle($id, $colArr) 
    {
        $this->update("id", $id, array(
            'column_name' => $colArr['column_name'],
            'contextcode' => $colArr['contextcode'],
            'display_name' => $colArr['display_name'],
            'description' => $colArr['description'],
            'primary_display' => $colArr['primary_display'],
            'secondary_display' => $colArr['secondary_display'],
            'grading_period' => $colArr['grading_period'],
            'include_weighted_grade' => $colArr['include_weighted_grade'],
            'running_total' => $colArr['running_total'],
            'show_grade_center_calc' => $colArr['show_grade_center_calc'],
            'show_in_mygrades' => $colArr['show_in_mygrades'],
            'show_statistics' => $colArr['show_statistics']
        ));
        return $id;
    }
    /**
     * Delete a record
     * @param string $id ID
     */
    function deleteSingle($id) 
    {
        $this->delete("id", $id);
    }
    /**
     * Return json for context logs
     * @param string $contextcode Context Code
     * @return json The Context Grades
     */
    function jsonContextGrades( $contextcode = Null, $start, $limit ) 
    {
        if (empty($contextcode))
            $contextcode = $this->contextCode;
        if ( !empty($start) && !empty($limit) ) 
         $where = " LIMIT " . $start . " , " . $limit;
        else
         $where = "";
        $contextGrades = $this->getContextGrades( $contextcode, $where );

        $gradeCount = (count($contextGrades));
        $gradeArray = array();
        foreach ( $contextGrades as $contextGrade ) {
        }
    }
}
?>
