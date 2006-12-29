<?php
/* ----------- data class extends dbTable for tbl_email ----------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
/**
 * Model class for the table tbl_email_new
 * @author Kevin Cyster
 */
class dbemail extends dbTable
{
    /**
     * @var string $userId The userId of the current user
     * @access private
     */
    private $userId;

    /**
     * Method to construct the class.
     *
     * @access public
     * @return
     */
    public function init()
    {
        parent::init('tbl_email');
        $this->table = 'tbl_email';
        $this->objUser = &$this->newObject('user', 'security');
        $this->userId = $this->objUser->userId();
        $this->objModules = &$this->newObject('modules', 'modulecatalogue');
        $this->objLanguage = &$this->newObject('language', 'language');
        $this->dbRouting = &$this->newObject('dbrouting');
        $this->dbAttachments = &$this->newObject('dbattachments');
        $this->emailFiles = &$this->newObject('emailfiles');
    }

    /**
     * Method for adding a row to the database.
     *
     * @access public
     * @param string $recipientList The list of recipientIds
     * @param string $subject The email subject
     * @param string $message The email message
     * @param string $attachments An indicator if the email has attachments 1=YES 0=NO
     * @return string $emailId The id of the email
     */
    public function sendMail($recipientList, $subject, $message, $attachment)
    {
        $fields = array();
        $fields['sender_id'] = $this->userId;
        $fields['recipient_list'] = $recipientList;
        $fields['subject'] = $subject;
        $fields['message'] = $message;
        $fields['attachments'] = $attachment;
        $fields['date_sent'] = date("Y-m-d H:i:s");
        $fields['updated'] = date("Y-m-d H:i:s");
        $emailId = $this->insert($fields);
        $this->dbRouting->sendMail($emailId, $this->userId, $this->userId, 'init_3', 1, 0);
        $arrRecipientList = explode("|", $recipientList);
        foreach($arrRecipientList as $recipient) {
            if ($recipient != '') {
                $this->dbRouting->sendMail($emailId, $this->userId, $recipient, 'init_1', 0, 0);
                //$this->instantMessage($recipient);

            }
        }
        $attachCount = $this->emailFiles->saveAttachments($emailId);
        $this->update('id', $emailId, $attachCount);
        return $emailId;
    }

    /**
     * Method for getting a row from the database.
     *
     * @access public
     * @param string $emailId The id of the email to retrieve
     * @return array $data The email data
     */
    public function getMail($emailId)
    {
        $sql = "SELECT * FROM ".$this->table;
        $sql.= " WHERE id='".$emailId."'";
        $data = $this->getArray($sql);
        if (!empty($data)) {
            $data = $data[0];
            return $data;
        }
        return FALSE;
    }

    /**
     * Method to resend and email
     *
     * @access public
     * @param string $emailId The id of the email to resend
     * @return string $newEmailId The id of the 'copied' email
     */
    public function resendEmail($emailId)
    {
        $emailData = $this->getMail($emailId);
        $recipientList = $emailData['recipient_list'];
        $subject = $emailData['subject'];
        $message = $emailData['message'];
        $attachment = $emailData['attachments'];
        $this->emailFiles->createAttachments($emailId);
        $newEmailId = $this->sendMail($recipientList, $subject, $message, $attachment);
        return $newEmailId;
    }

    /**
     * Method to notify an internal email recipient via instant messaging
     *
     * @access public
     * @param string $recipient - the userId of the recipient
     * @return
     */
    public function instantMessage($recipient)
    {
        $message = $this->objLanguage->languageText('mod_email_email');
        if ($this->objModules->checkIfRegistered('instantmessaging')) {
            $objIMDbOptions = $this->newObject('dboptions', 'instantmessaging');
            // Fail if table does not exist
            if ($objIMDbOptions->get('notifyreceive', $recipient)) {
                $objIM = &$this->newObject('dbentries', 'instantmessaging');
                //System notification
                $objIM->sendInstantMessage($recipient, null, $message);
            }
        }
    }
}
?>