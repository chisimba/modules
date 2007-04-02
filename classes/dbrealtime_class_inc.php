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
		$this->objUser = & $this->getObject('user', 'security');
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
		log_debug("convid". $convId);
		$sql = "select hastoken, puid from tbl_realtime_voicequeue where conversationid=\"$convId\"";
		$result = $this->getArray($sql);

		if (eregi("student", $userLevel) || eregi("guest", $userLevel) || eregi("administrator", $userLevel))
		{
			if (empty ($result))
			{
				$this->addQueue($userId, $contextCode);
				return SUCCESS;
			} else
			{
				foreach ($result as $res)
				{
					$hasToken = $res['hastoken'];
					$puid = $res['puid'];
				}

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
					$this->addQueue($userId, $contextCode);
					return SUCCESS;
				}
			}
		}
		elseif (eregi("lecturer", $userLevel))
		{
			//explicitly realease token
			$sql = "delete from tbl_realtime_voicequeue where hastoken=1";
			$this->getArray($sql);
			$this->addQueue($userId, $contextCode);
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
	public function releaseToken($userId, $contextCode)
	{
		log_debug("userid" . $userId);
		$convId = $this->getConversationId($contextCode);
		$sql = "delete from tbl_realtime_voicequeue where conversationid=\"$convId\" && userid=\"$userId\"";
		log_debug("convid =" . $convId . " userid " . $userId);
		$this->getArray($sql);

		$sql = "select puid from tbl_realtime_voicequeue where conversationid=\"$convId\" order by puid limit 1";
		$result = $this->getArray($sql);
		if (empty ($result))
		{
			log_debug("nothing happened");
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
	public function stopConversation($userId, $contextCode)
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
	public function startConversation($userId, $contextCode)
	{
		$sql = "select contextcode from tbl_realtime_conversation where contextcode=\"$contextCode\"";
		$result = $this->getArray($sql);

		if (empty ($result))
		{
			$this->createConversation($userId, $contextCode);
			$this->addQueue($userId, $contextCode);
			return SUCCESS;
		} else
		{
			$this->removeConversation($contextCode);
			$this->createConversation($userId, $contextCode);
			$this->addQueue($userId, $contextCode);
			return SUCCESS;
		}
	}

	/**
	 * @param string userId user identification number
	 * @param string userLevel group that user belongs to
	 * @param string contextCode Course code
	 * @return String SUCCESS/FAILURE
	 */
	public function joinConversation($userId, $contextCode)
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
	public function leaveConversation($userId, $contextCode)
	{
		return SUCCESS;
	}

	/**
	 * @param string userId user identification number
	 * @param string userLevel group that user belongs to
	 * @param string contextCode Course code
	 * @return String SUCCESS/FAILURE
	 */
	public function checkToken($userId, $contextCode)
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

	public function makeQueue($userId, $userLevel, $contextCode)
	{
		$convId = $this->getConversationId($contextCode);
		$sql = "select userid, hastoken from tbl_realtime_voicequeue where conversationId=\"$convId\" && userid!=\"$userId\" && hastoken=0 order by puid asc";
		$result = $this->getArray($sql);
		$commaDelimitedText = "";
		if (empty ($result))
		{
			return $commaDelimitedText;
		} else
		{
			foreach ($result as $results)
			{
				$commaDelimitedText .= $this->objUser->getFirstname($results['userid']) .
				"," . $results['userid'] . "," . $results['hastoken'] . "," . $userLevel . ",-";
			}
			return $commaDelimitedText;
		}
	}

	public function assignToken($userId, $contextCode)
	{
		$convId = $this->getConversationId($contextCode);
		log_debug("id " . $userId . " context " . $contextCode);
		$sql = "delete from tbl_realtime_voicequeue where conversationid=\"$convId\" && hastoken=1";
		$this->getArray($sql);

		$sql = "select puid from tbl_realtime_voicequeue where conversationid=\"$convId\" && userid=$userId";
		$result = $this->getArray($sql);
		foreach ($result as $res)
		{
			$puid = $res['puid'];
		}
		log_debug("puid" . $puid);
		$update = array ();
		$update['hastoken'] = 1;

		if ($this->update("puid", $puid, $update, "tbl_realtime_voicequeue"))
		{
			log_debug("excuted");
			return SUCCESS;
		} else
		{
			log_debug("failure excuted");
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
	private function addQueue($userId, $contextCode)
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
	private function createConversation($userId, $contextCode)
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
