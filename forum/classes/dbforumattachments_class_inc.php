<?php
/* ----------- data class extends dbTable for tbl_forum_attachments------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }

/**
* Forum Attachments Table
* This class controls all functionality relating to the tbl_forum_attachments table
* @author Tohir Solomons
* @copyright (c) 2004 University of the Western Cape
* @package forum
* @version 1
*/
class dbforumattachments extends dbTable
{

    /**
    * Constructor method to define the table
    */
    function init() {
        parent::init('tbl_forum_attachments');
    }


    /**
    * Insert an attachment into into the database
    *
    * @param string $fileId:                     File Id of the file in tbl_filestore
    * @param string $forum_id:                 Forum Record ID
    * @param string $description:              Description of the File
    * @param string $userId:                   User Id of the person giving the rating
    * @param string $dateLastUpdated:      Date Rating is added
    *
    * @return string Last Insert Id
    */
    function insertSingle($fileId, $forum_id, $description, $userId, $dateLastUpdated)
    {
        $this->insert(array(
                'fileId' => $fileId,
                'forum_id' => $forum_id,
                'description' => $description,
                'userId' => $userId,
                'dateLastUpdated' => strftime('%Y-%m-%d %H:%M:%S', $dateLastUpdated)));
                
        return $this->getLastInsertId();
    }
    
    /**
    * Gets the list of attachments for a post
    *
    * @param string $forum: Forum Record ID
    * @param string $tempPost: Id of temporary post - post that is being written but has been sent yet
    *
    * @return array List of Attachments
    */
    function getListForumAttachments($forum, $tempPost)
    {
        $sql = 'SELECT *, tbl_forum_attachments.id AS attachment_id, 
        tbl_forum_temp_attachment.id AS used
        FROM tbl_forum_attachments 
        INNER JOIN tbl_filestore ON (tbl_forum_attachments.fileId = tbl_filestore.fileId) 
        LEFT JOIN tbl_forum_temp_attachment ON 
            (tbl_forum_temp_attachment.attachment_id = tbl_forum_attachments.id 
            AND temp_id = "'.$tempPost.'")
        WHERE tbl_forum_attachments.forum_id = "'.$forum.'"';
        
        return $this->getArray($sql);    
    }
    
    /**
    * Method to delete all attachments related to a forum
    * This function will also clean up data stored as blobs
    * @param string $forum Record Id of the Forum
    */
    function deleteForumAttachments($forum)
    {
        $attachments = $this->getAll('WHERE forum_id="'.$forum.'"');
        
        //$objFileUpload = $this->getObject('fileupload', 'filestore');
        
        foreach ($attachments as $attachment)
        {
            $objFileUpload->eraseFile($attachment['fileId']);
            $this->delete('id', $attachment['id']);
        }
        
        return TRUE;
    }
    



} #end of class
?>