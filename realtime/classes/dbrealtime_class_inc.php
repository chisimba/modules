<?php


/**
 * Created on 15 Mar 2007
 * @author Mohamed Yusuf
 */
/**
 * security check - must be included in all scripts
 */
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
	 * @param string userId user identification number
	 * @param string userLevel group that user belongs to
	 * @param string contextCode Course code
	 * @return String SUCCESS/FAILURE
	 */
	public function requestToken($userId, $userLevel, $contextCode)
	{
		//log_debug("userid: " . $userId . " userlevel: " . $userLevel . " contextcode: " . $contextCode);
		$userLevel = strtolower($userLevel);
		$convId = $this->getConversationId($contextCode);
		$sql = "select hastoken, puid from tbl_realtime_voicequeue where conversationid=\"$convId\"";
		$result = $this->getArray($sql);

		foreach ($result as $res)
		{
			$hasToken = $res['hastoken'];
			$puid = $res['puid'];
		}
		if (eregi("student", $userLevel) || eregi("guest", $userLevel) || eregi("administrator", $userLevel))
		{
			if (empty ($result))
			{
				$this->addQueue($userId, $userLevel, $contextCode);
				return SUCCESS;
			} else
			{
				if ("1" == $hasToken)
				{
					$inQueue = array ();
					$inQueue['conversationid'] = $this->getConversationId($contextCode);
					$inQueue['userid'] = $userId;
					$inQueue['hastoken'] = 0;
					$this->insert($inQueue, 'tbl_realtime_voicequeue');
					return FAILURE;
				} else
				{
					//assumed that token have been released
					$this->addQueue($userId, $userLevel, $contextCode);
					return SUCCESS;
				}
			}
		}
		elseif (eregi("lecturer", $userLevel))
		{
			//explicitly realease token
			$sql = "delete from tbl_realtime_voicequeue where hastoken=1";
			$this->getArray($sql);
			$this->addQueue($userId, $userLevel, $contextCode);
			return SUCCESS;
		} else
		{
			//what to do with administrators and guests.
			return FAILURE;
		}
	}

	/**
	 * @param string contextCode Course code
	 * @return String SUCCESS/FAILURE
	 */
	public function releaseToken($userId, $userLevel, $contextCode)
	{
		$convId = $this->getConversationId($contextCode);
		$sql = "delete from tbl_realtime_voicequeue where conversationid=\"$convId\" && userid=\"$userId\"";
		$this->getArray($sql);

		$sql = "select puid from tbl_realtime_voicequeue where conversationid=\"$convId\" order by puid limit 1";
		$result = $this->getArray($sql);
		if (empty ($result))
		{
			//do nothing
		} else
		{
			foreach ($result as $res)
			{
				$puid = $res['puid'];
			}
			$update = array ();
			$update['hastoken'] = 1;
			$this->update("puid", $puid, $update, "tbl_realtime_voicequeue");
		}
		return SUCCESS;
	}

	/**
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
	 * @param string userId user identification number
	 * @param string userLevel group that user belongs to
	 * @param string contextCode Course code
	 * @return String SUCCESS/FAILURE
	 */
	public function startConversation($userId, $userLevel, $contextCode)
	{
		$sql = "select contextcode from tbl_realtime_conversation where contextcode=\"$contextCode\"";
		$result = $this->getArray($sql);

		if (empty ($result))
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
	 * @param string userId user identification number
	 * @param string userLevel group that user belongs to
	 * @param string contextCode Course code
	 * @return String SUCCESS/FAILURE
	 */
	public function checkToken($userId, $userLevel, $contextCode)
	{
		$sql = "select hastoken from tbl_realtime_voicequeue where userid=\"$userId\"";
		$result = $this->getArray($sql);
		if (empty ($result))
		{
			//log_debug("empty array, statement did not fetch rows");
			return "false";
		} else
		{
			foreach ($result as $res)
			{
				$hasToken = $res['hastoken'];
			}
			if (1 == $hasToken)
			{
				return "true";
			} else
			{
				return "false";
			}
		}
	}

	/**
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
}
?>
