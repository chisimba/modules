<?php

/**
 * @package mynotes
 */
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
 * Class for providing access to the table tbl_tag in the database
 * @author Nguni
 *
 * @copyright (c) 2010 University of the Witwatersrand
 * @package mynotes
 * @version 0.1
 */
class dbtag extends dbtable {

    /**
     * Method to construct the class and initialise the table.
     *
     * @access public
     * @return
     */
    public $table;
    public $objUser;
    public $userId;

    public function init() {
        parent::init('tbl_mynotes_tags');
        $this->table = 'tbl_mynotes_tags';
        $this->objUser = &$this->getObject('user', 'security');
        $this->userId = $this->objUser->userId();
    }

    /**
     * Method to insert or update a tag in the database.
     *
     * @access public
     * @param array $fields The table fields to be added/updated.
     * @param string $id The id of the tag to be edited. Default=NULL.
     * @return array $id The id of the inserted or updated tag.
     * @return array $qnId The qnId of the inserted or updated question.
     */
    public function addTag($data, $id = Null, $qnId = Null) {
        $othertags = explode(",", $data["tags"]);

        if(!empty($othertags)) {
            $idArr = array();
            
            if (!empty($id)) {
                $idArr[$count] = $id;
            }

            foreach ($othertags as $othertag) {
                if(!empty($othertag)) {
                    $fields['name'] = $othertag;
                    $fields['userid'] = $this->userId;
                    $fields['datecreated'] = date('Y-m-d H:i:s');
                    
                    if($id) {
                        $fields['datemodified'] = date('Y-m-d H:i:s');
                        $fields['modifiedby'] = $this->userId;
                        $this->update('id', $id, $fields);
                    } else {
                        $this->insert($fields);
                    }
                }
            }
        }
        
        return $id;
    }

    /**
     * Method to get all tags.
     *
     * @access public
     * @param string $filter An additional filter on the select statement.
     * @return array $data The list of tags.
     */
    public function getTags($filter = NULL) {
        $sql = 'SELECT * FROM ' . $this->table;
        if ($filter != NULL) {
            $sql.= " WHERE " . $filter;
        } else {
            $sql .= "";
        }
        $data = $this->getArray($sql);
        if (!empty($data)) {
            return $data;
        }
        return FALSE;
    }

    /**
     * Method to get a specific tag.
     *
     * @access public
     * @param string $id The id of the tag.
     * @return array $data The details of the tag.
     */
    public function getTag($id) {
        $sql = 'SELECT * FROM ' . $this->table;
        $sql.= " WHERE id='$id'";
        $data = $this->getArray($sql);
        if (!empty($data)) {
            return $data;
        }
        return FALSE;
    }

    /**
     * Method to delete a tag.
     * The sort order of the following tags is decreased by one.
     *
     * @access public
     * @param string $id The id of the tag.
     * @return
     */
    public function deleteTag($id) {
        $this->delete('id', $id);
    }

}

// end of class
?>