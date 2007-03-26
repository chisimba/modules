<?php

/*
 * Created on 15 Mar 2007
 * @author Mohamed Yusuf
 */
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	die("You cannot view this page directly");
}

class dbrealtime extends dbTable
{

	public function init()
	{
		parent :: init('tbl_realtime_conversation');
		define("SUCCESS", "SUCCESS");
		define("FAILURE", "FAILURE");
	}

	/**
	 * @author Mohamed Yusuf
	 * @param string userId user identification number
	 * @param string userLevel group that user belongs to
	 * @param string contextCode Course code
	 * @return String SUCCESS/FAILURE
	 */
	public function requestToken($userId, $userLevel, $contextCode)
	{
		
		$userLevel = strtolower($userLevel);
		$convId = $this->getConversationId($contextCode);
		$sql = "select hastoken from tbl_realtime_voicequeue where conversationid=\"$convId\"";
		$result = $this->getArray($sql);
		if (eregi("student",$userLevel))
		{
			if(empty($result)){
				$this->addQueue($userId, $userLevel, $contextCode);
				return SUCCESS;				
			} else { 
				foreach ($result as $res)
				{
					$hasToken = $res['hastoken'];
				}
				if ("1" == $hasToken)
				{
					return FAILURE;
				} else {
					//assumed that token had been released
					$this->addQueue($userId, $userLevel, $contextCode);
					return SUCCESS;
				}
			}
		}
		elseif (eregi("lecturer", $userLevel))
		{
			//explicitly realease token
			$this->releaseToken($userId, $userLevel, $contextCode);
			$this->addQueue($userId, $userLevel, $contextCode);
			return SUCCESS;
		} else
		{
			//what to do with administrators and guests.
			return FAILURE;
		}
	}

	/**
	* @author Mohamed Yusuf
	* @param string contextCode Course code
	* @return String SUCCESS/FAILURE
	*/
	public function releaseToken($userId, $userLevel, $contextCode)
	{
		$convId = $this->getConversationId($contextCode);
		$sql = "delete from tbl_realtime_voicequeue where conversationid=\"$convId\"";
		$this->getArray($sql);
		return SUCCESS;
	}

	/**
	 * @author Mohamed Yusuf
	 * @param string contextCode Course code
	 * @return String SUCCESS/FAILURE
	 */
	public function stopConversation($userId, $userLevel, $contextCode)
	{
		$sql = "select userid from tbl_realtime_conversation where userid=\"$userId\"";
		$result = $this->getArray($sql);
		if (!empty ($result))
		{
			$this->removeConversation($contextCode);
			return SUCCESS;
		} else
		{
			return FAILURE;
		}
	}

	/**
	* @author Mohamed Yusuf
	* @param string userId user identification number
	* @param string userLevel group that user belongs to
	* @param string contextCode Course code
	* @return String SUCCESS/FAILURE
	*/
	public function startConversation($userId, $userLevel, $contextCode)
	{
		$sql = "select contextcode from tbl_realtime_conversation where contextcode=\"$contextCode\"";
		$result = $this->getArray($sql);

		if (empty($result))
		{
			$this->createConversation($userId, $userLevel, $contextCode);
			$this->addQueue($userId, $userLevel, $contextCode);
			return SUCCESS;
		} else
		{
			$this->removeConversation($contextCode);
			$this->createConversation($userId, $userLevel, $contextCode);
			$this->addQueue($userId, $userLevel, $contextCode);
			return SUCCESS;
		}
	}

	/**
	* @author Mohamed Yusuf
	* @param string contextCode Course code
	* @return String SUCCESS/FAILURE
	*/
	private function removeConversation($contextCode)
	{
		$convId = $this->getConversationId($contextCode);
		//delete entries from voicequeue table
		$sql = "delete from tbl_realtime_voicequeue where conversationid=\"$convId\"";
		$this->getArray($sql);

		//delete entries from conversation table
		$sql = "delete from tbl_realtime_conversation where id=\"$convId\"";
		$this->getArray($sql);
		//return SUCCESS;		
	}

	/**
	* @author Mohamed Yusuf
	* @param string userId user identification number
	* @param string userLevel group that user belongs to
	* @param string contextCode Course code
	* @return String SUCCESS/FAILURE
	*/
	private function addQueue($userId, $userLevel, $contextCode)
	{
		$lastId = $this->getConversationId($contextCode);
		$queue = array ();
		$queue['conversationid'] = $lastId;
		$queue['userid'] = $userId;
		$queue['hastoken'] = 1;
		$this->insert($queue, 'tbl_realtime_voicequeue');
	}

	/**
	* @author Mohamed Yusuf
	* @param string contextCode Course code
	* @return String id
	*/
	private function getConversationId($contextCode)
	{
		$sql = "select id  from tbl_realtime_conversation where contextcode=\"$contextCode\"";
		$result = $this->getArray($sql);
		foreach ($result as $res)
		{
			$conversationId = $res['id'];
		}
		return $conversationId;
	}

	/**
	* @author Mohamed Yusuf
	* @param string userId user identification number
	* @param string userLevel group that user belongs to
	* @param string contextCode Course code
	* @return String SUCCESS/FAILURE
	*/
	private function createConversation($userId, $userLevel, $contextCode)
	{
		$addRow = array ();
		$starttime = strftime('%Y-%m-%d %H:%M:%S', mktime());
		$addRow['userid'] = $userId;
		$addRow['contextcode'] = $contextCode;
		$addRow['starttime'] = $starttime;
		$this->insert($addRow, 'tbl_realtime_conversation');
	}

	/**
	* @author Mohamed Yusuf
	* @param string userId user identification number
	* @param string userLevel group that user belongs to
	* @param string contextCode Course code
	* @return String SUCCESS/FAILURE
	*/
	public function joinConversation($userId, $userLevel, $contextCode)
	{
		$convId = $this->getConversationId($contextCode);
		if (empty ($convId))
		{
			return FAILURE;
		} else
		{
			return SUCCESS;
		}
	}

	/**
	* @author Mohamed Yusuf
	* @param string userId user identification number
	* @param string userLevel group that user belongs to
	* @param string contextCode Course code
	* @return String SUCCESS/FAILURE
	*/
	public function leaveConversation($userId, $userLevel, $contextCode)
	{
		return SUCCESS;
	}

	/**
	* @author Mohamed Yusuf
	* @param string userId user identification number
	* @param string userLevel group that user belongs to
	* @param string contextCode Course code
	* @return String SUCCESS/FAILURE
	*/	
	public function checkToken($userId, $userLevel, $contextCode)
	{
		$sql = "select hastoken from tbl_realtime_voicequeue where userid=\"$userId\"";
		$result = $this->getArray($sql);
		if(empty($result)){
			//log_debug("empty array, statement did not fetch rows");
			return FAILURE;
		}else{
			//log_debug("statement did fetch rows");
			return SUCCESS;
		}
		
	}
}
?>
