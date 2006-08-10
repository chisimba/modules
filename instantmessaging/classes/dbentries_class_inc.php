<?php
/* ----------- data class extends dbTable for tbl_blog------------*/// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
    {
        die("You cannot view this page directly");
    }


/**
* Model class for the table tbl_faq
* @author Jeremy O'Connor
* @copyright 2004 University of the Western Cape
*/
class dbentries extends dbTable
{
    /**
    * Constructor method to define the table
    */
    function init() 
    {
        parent::init('tbl_im_entries');
        //$this->USE_PREPARED_STATEMENTS=True;
    }

	/**
	* Send an instant message.
	* @param string $recipient Recipient of message
	* @param string $sender Sender of message
	* @param string $text Text of message
	* Note: If $sender is null, this is a 'system notification' type message,
	* to which you cannot reply.
	*/
	function SendInstantMessage($recipient, $sender, $text)
    {
        $this->insertSingle($recipient, $sender, $text);
    }

    /**
    * Return all records
	* @param string $recipient Recipient of message
	* @return array The messages
    */
	function listAll($recipient)
	{
		$sql = "SELECT id, sender, content FROM tbl_im_entries 
		WHERE (recipient='$recipient') 
		AND ((isread='0') OR (isread IS NULL))";
		return $this->getArray($sql);
		//return $this->getAll();
	}

	/**
	* Return a single record
	* @param string $Id ID
	* @return array The message
	*/	
	function listSingle($Id)
	{
		$sql = "SELECT sender, content FROM tbl_im_entries
		WHERE id='$Id'";
		return $this->getArray($sql);
		//return $this->getRow("id", $id);
	}

	/**
	* Insert a record
	* @param string $recipient Recipient of message
	* @param string $sender Sender of message
	* @param string $text Text of message
	*/
	function insertSingle($recipient, $sender, $text)
	{
		$this->insert(array(
        	'recipient' => $recipient,
        	'sender' => $sender,
			'content' => $text
		));
		return;	
	}

	/**
	* Mark message as read.
	* @param string $Id ID
	*/
	function updateSingle($Id)
	{
		$this->update("id", $Id, 
			array(
        		'isread' => "1"
			)
		);
	}
}
?>