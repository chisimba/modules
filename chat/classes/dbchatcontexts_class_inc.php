<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}


/**
* Model class for the table tbl_faq
* @author Jeremy O'Connor
* @copyright 2004 University of the Western Cape
*/
class dbChatContexts extends dbTable
{
    /**
    * Constructor method to define the table
    */
    public function init()
    {
        parent::init('tbl_chat_contexts');
        //$this->USE_PREPARED_STATEMENTS=True;
    }

    /**
    * Return all records
    * @return array The contexts
    */
    public function listAll($type = NULL)
    {
        $sql = "SELECT
            id,
            context
        FROM tbl_chat_contexts";
        if (!is_null($type)) {
            $sql .= " WHERE type='$type'";
        }
        else {
            $sql .= " WHERE (type='context') OR (type='private')";
        }
        $sql.=" ORDER BY context";
        return $this->getArray($sql);
    }

    /**
    * Return all records
    * @param string $context The context
    * @return array The context
    */
    public function listsingle($context)
    {
        $sql = "SELECT
            *
        FROM tbl_chat_contexts
        WHERE context='" . addslashes($context) . "'";
        return $this->getArray($sql);
    }

    /**
    * Insert a record
    * @param string $context The context
    * @param string $username The username
    * @return string PK ID
    */
    public function insertSingle($context, $username, $type = 'context')
    {
        return $this->insert(array(
            'context' => $context,
            'username' => $username,
            'type' => $type
        ));
        //return;
    }

    /**
    * Delete a record
    * @author Megan Watson
    * @param string $id The id of the chat room.
    */
    public function deleteRecord($id)
    {
       $this->delete('id', $id);
    }
}
?>
