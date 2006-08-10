<?php
/* ----------- data class extends dbTable for tbl_forum_temp_attachment------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }

/**
* Temporary Post Attachments Table
* This class stores records of attachments for posts whilst the post is being compiled
* @author Tohir Solomons
* @copyright (c) 2004 University of the Western Cape
* @package forum
* @version 1
*/
class dbtempattachments extends dbTable
{

    /**
    * Constructor method to define the table
    */
    function init() {
        parent::init('tbl_forum_temp_attachment');
    }
    
    /**
    * Method to get a list of attachment with all details
    *
    * @param string $id Temporary Post Id
    * @return array List of Attachments
    */
    function getList($id)
    {
        $sql = 'SELECT tbl_forum_temp_attachment.id AS attachment_id,
        tbl_forum_attachments.description, tbl_filestore.* FROM tbl_forum_temp_attachment 
        INNER JOIN tbl_forum_attachments ON (tbl_forum_temp_attachment.attachment_id = tbl_forum_attachments.id)
        INNER JOIN tbl_filestore ON (tbl_forum_attachments.fileId = tbl_filestore.fileId)
        WHERE tbl_forum_temp_attachment.temp_id = "'.$id.'"';
        
        return $this->getArray($sql);
    
    }
    
    /**
    * Method to get a list of attachment record by providing the temporary post id
    *
    * @param string $id Temporary Post Id
    * @return array List of Attachments
    */
    function getQuickList($id)
    {
        $sql = ' WHERE temp_id="'.$id.'"';
        
        return $this->getAll($sql);
    }
    
    /**
    * Method to subscribe a user to the forum
    *
    * @param string $temp_id: Temporary Id of the post before it is being posted
    * @param string $attachment_id: File Id of attachment
    * @param string $forum_id: Forum the attachment is related
    * @param string $userId: User Id of the person 
    * @param string $dateLastUpdated: Date attachment is added
    */
    function insertSingle($temp_id, $attachment_id, $forum_id, $userId, $dateLastUpdated)
    {
        $this->insert(array(
                'temp_id' => $temp_id,
                'attachment_id' => $attachment_id,
                'forum_id' => $forum_id,
                'userId' => $userId,
                'dateLastUpdated' => strftime('%Y-%m-%d %H:%M:%S', $dateLastUpdated)));
    }
    
    /**
    * Method to delete the temporary records for attachments
    *
    * @param string $id Temporary Post Id
    */
    function deleteTemps($id)
    {
        return $this->delete('temp_id', $id);
    
    }


} #end of class
?>
