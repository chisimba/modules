<?
/**
 * File dbassignment extends dbtable
 * @author Megan Watson
 * @copyright (c) 2004 UWC
 * @package assignment
 * @version 0.1
 * @filesource
 */

// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
} // end security check

/**
 * Class to access table tbl_assignment
 * @author Megan Watson
 * @copyright (c) 2004 UWC
 * @version 0.1
 */

class dbassignment extends dbtable {
    /**
     * Constructor
     */
    public function init() {
        parent::init('tbl_assignment');
        $this->table = 'tbl_assignment';
    }

    /**
     * Method to search assignments and return the results.
     * @param string $field The table field in which to search.
     * @param string $value The value to search for.
     * @param string $context The current context.
     * @return array $data The results of the search.
     */
    public function search($field, $value, $context,$workgroupid) {


        $sql = "SELECT * FROM ".$this->table;
        $sql .= " WHERE $field LIKE '$value%'";
        if($workgroupid) {
            $sql .= " AND workgroup_id='$workgroupid'";
        }
        $sql .= " AND context='$context'";
        $sql .= ' ORDER BY closing_date, name';

        $data = $this->getArray($sql);

        if($data) {
            return $data;
        }
        return FALSE;
    }

    /**
     * Method to insert a new assignment into the database or update an existing one.
     * @param array $fields The table fields and values to be inserted in the database
     * @param string $id The id of the assignment to be updated, default=NULL on insert
     * @return
     */
    public function addAssignment($fields, $id=NULL) {
        if(!empty($id)) {
            $this->update('id',$id,$fields);
        }else {

            $id = $this->insert($fields);
        }
        return $id;
    }

    /**
     * Method to get an assignment from the database.
     * @param string $context The current context.
     * @param string $filter
     * @return array $data List of assignments
     */
    public function getAssignment($workgroupid,$filter=NULL) {   // pass workgroup instead of $context

        $sql = 'SELECT * FROM '.$this->table;
        if($workgroupid=='') {
            $sql .= " WHERE workgroup_id IS NULL";} //TO SELECT NULL/EMPTY WORKGROUPID
            else { $sql .= " WHERE workgroup_id ='$workgroupid'"; 
            }
         if($filter) {
            $sql .= ' AND '.$filter;
            }
            $sql .= ' ORDER BY closing_date';
//echo $sql;
//die();
        $data = $this->getArray($sql);
        if($data) {
            return $data;
            }
            return FALSE;
            }
            
    /**
     * Method to delete an assignment.
     * @param string $id The id of the assignment to be deleted
     * @return
     */
    public function deleteAssignment($id) {
        $this->delete('id',$id);
    }
}// end of class
?>