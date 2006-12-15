<?php
/* ----------- data class extends dbTable for tbl_email_attachments ----------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
/**
* Model class for the table tbl_email_new
* @author Kevin Cyster
*/
class dbattachments extends dbTable
{
    /**
    * Method to construct the class.
    *
    * @access public
    * @return
    */
    public function init()
    {
        parent::init('tbl_email_attachments');
        $this->table = 'tbl_email_attachments';
        $this->objUser = &$this->getObject('user', 'security');
        $this->userId = $this->objUser->userId();
    }

    /**
    * Method for adding attachments to the database.
    *
    * @access public
    * @param string $emailId The id of the email the attachments are for
    * @param array $attachment The file data
    * @return string $attachmentId The id of the attachment
    */
    public function addAttachments($emailId, $attachment)
    {
        $fields = array();
        $fields['email_id'] = $emailId;
        $fields['file_name'] = $attachment['filename'];
        $fields['file_type'] = $attachment['mimetype'];
        $fields['file_size'] = $attachment['filesize'];
        $fields['extension'] = $attachment['extension'];
        $attachmentId = $this->insert($fields);

        $updateArray['stored_name'] = $attachmentId;
        $this->update('id', $attachmentId, $updateArray);
        return $attachmentId;
    }

    /**
    * Method to retrieve an attachment from the data base
    *
    * @access public
    * @param string $attachmentId The id of the attachments
    * @return array $data The attachment data
    */
    public function getAttachment($attachmentId)
    {
        $sql = "SELECT * FROM ".$this->table;
        $sql.= " WHERE id='".$attachmentId."'";
        $data = $this->getArray($sql);
        if (!empty($data)) {
            return $data;
        }
        return FALSE;
    }

    /**
    * Method to retrieve attachments from the data base
    *
    * @access public
    * @param string $emailId The id of the email the attachments are for
    * @return array $data The attachment data
    */
    public function getAttachments($emailId)
    {
        $sql = "SELECT * FROM ".$this->table;
        $sql.= " WHERE email_id='".$emailId."'";
        $sql.= " ORDER BY file_name";
        $data = $this->getArray($sql);
        if (!empty($data)) {
            return $data;
        }
        return FALSE;
    }

    /**
    *  Method to resend an attachment
    *
    * @access public
    * @param string $emailId The id of the original email
    * @param string $newEmailId The id of the resent email
    * @return
    */
    public function resendAttachments($emailId, $newEmailId)
    {
        $arrAttachments = $this->getAttachments($emailId);
        foreach($arrAttachments as $attachment) {
            $fields = array();
            $fields['email_id'] = $newEmailId;
            $fields['file_name'] = $attachment['file_name'];
            $fields['file_type'] = $attachment['file_type'];
            $fields['file_size'] = $attachment['file_size'];
            $fields['file_data'] = $attachment['file_data'];
            $fileId = $this->insert($fields);
        }
    }
}
?>