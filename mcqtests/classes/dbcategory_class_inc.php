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
 * Class for providing access to the table tbl_test_category in the database
 * @author Paul Mungai
 *
 * @copyright (c) 2010 University of the Witwatersrand
 * @package mcqtests
 * @version 1.2
 */
class dbcategory extends dbtable {

    /**
     * Method to construct the class and initialise the table.
     *
     * @access public
     * @return
     */
    public $table;
    /*
     * @var object to hold user db class
     */
    public $objUser;
    /*
     * @var string to hold the user Id
     */
    public $userId;
    /*
     * @var object to hold context db class
     */
    public $objContext;
    /*
     * @var string to hold the context Code
     */
    public $contextCode;

    public function init() {
        parent::init('tbl_test_category');
        $this->table = 'tbl_test_category';
        $this->objUser = &$this->getObject('user', 'security');
        $this->userId = $this->objUser->userId();
        $this->objContext = $this->newObject('dbcontext', 'context');
        $this->contextCode = $this->objContext->getContextCode();
    }

    /**
     * Method to insert or update a category in the database.
     *
     * @access public
     * @param array $fields The table fields to be added/updated.
     * @param string $id The id of the category to be edited. Default=NULL.
     * @return string $id The id of the inserted or updated category.
     */
    public function addCategory($fields, $id = NULL) {
        $fields['timemodified'] = date('Y-m-d H:i:s');
        if ($id) {
            $fields['timemodified'] = date('Y-m-d H:i:s');
            $fields['modifiedby'] = $this->userId;
            $this->update('id', $id, $fields);
        } else {
            $fields['timecreated'] = date('Y-m-d H:i:s');
            $fields['createdby'] = $this->userId;
            $fields['contextcode'] = $this->contextCode;
            $id = $this->insert($fields);
        }
        return $id;
    }

    /**
     * Method to get a set of categories for a particular context.
     *
     * @access public
     * @param string $contextCode The contextcode of the context being used.
     * @param string $filter An additional filter on the select statement.
     * @return array $data The list of questions in the test.
     */
    public function getCategories($contextCode, $filter = NULL) {
        $sql = 'SELECT * FROM ' . $this->table;
        if ($filter) {
            $sql.= " WHERE contextcode='$contextCode' AND $filter";
        } else {
            $sql.= " WHERE contextcode='$contextCode' ORDER BY sortorder";
        }
        $data = $this->getArray($sql);
        if (!empty($data)) {
            $count = $this->countCategories($data);
            $data[0]['count'] = $count;
            return $data;
        }
        return FALSE;
    }

    /**
     * Method to get a specific category.
     *
     * @access public
     * @param string $id The id of the category.
     * @return array $data The details of the category.
     */
    public function getCategory($id) {
        $sql = 'SELECT * FROM ' . $this->table;
        $sql.= " WHERE id='$id'";
        $data = $this->getArray($sql);
        if (!empty($data)) {
            return $data;
        }
        return FALSE;
    }

    /**
     * Method to delete a category.
     * The sort order of the following categories is decreased by one.
     *
     * @access public
     * @param string $id The id of the category.
     * @return
     */
    public function deleteCategory($id) {
        $category = $this->getCategory($id);
        if (!empty($category)) {
            $filter = 'sortorder > ' . $category[0]['sortorder'] . ' ORDER BY sortorder';
            $data = $this->getCategories($question[0]['contextcode'], $filter);
            if (!empty($data)) {
                foreach ($data as $line) {
                    $fields = array();
                    $fields['sortorder'] = $line['sortorder'] - 1;
                    $this->addCategory($fields, $line['id']);
                }
            }
        }
        $this->delete('id', $id);
    }

    /**
     * Method to count the number of categories in a specified context.
     *
     * @access public
     * @param string $contextCode The context of the specified context.
     * @return int $catnum The number of categories in the context.
     */
    public function countCategories($contextCode) {
        $sql = "SELECT count(id) AS qnum FROM " . $this->table . " WHERE contextcode='$contextCode'";
        $data = $this->getArray($sql);
        if (!empty($data)) {
            $qnum = $data[0]['qnum'];
            return $qnum;
        }
        return FALSE;
    }

    /**
     * Change the order of categories in the context
     *
     * @access public
     * @param string $id The id of the category to be moved
     * @param bool $order If order is true move category up else move category down 1
     * @return bool TRUE if the order has been changed, FALSE if it hasn't.
     */
    public function changeOrder($id, $order) {
        $sql = 'SELECT contextcode, sortorder FROM ' . $this->table . " WHERE id='$id'";
        $data = $this->getArray($sql);
        if (!empty($data)) {
            $pos = $data[0]['sortorder'];
            $contextCode = $data[0]['contextcode'];
            // if move category up, check its not the first category
            if ($order && $pos > 1) {
                $newpos = $pos - 1;
                // if move category down, check its not the last category
            } else if (!$order) {
                $num = $this->countCategories($contextCode);
                if ($pos < $num) {
                    $newpos = $pos + 1;
                } else {
                    return FALSE;
                }
            } else {
                return FALSE;
            }
            // swap order of categories
            $sql = 'SELECT id FROM ' . $this->table . " WHERE contextcode='$contextCode' and sortorder='$newpos'";
            $result = $this->getArray($sql);
            if (!empty($result)) {
                $this->update('id', $result[0]['id'], array(
                    'sortorder' => $pos
                ));
                $this->update('id', $id, array(
                    'sortorder' => $newpos
                ));
                return TRUE;
            }
        }
        return FALSE;
    }

}

// end of class
?>