<?php
/*
 * Created on 15 Mar 2007
 * @author Mohamed Yusuf
 */
 
 class dbrealtime extends dbTable
 {
 	
 	
 	/**
 	 * initializing
 	 */
 	public function init()
 	{
		parent::init('tbl_realtime_conversation');
		define("SUCCESS", "SUCCESS");
		define("FAILURE", "FAILURE");
 	}
 	
 	/**
 	 * request toke
 	 * return true/false
 	 */
 	public function requestToken($userId, $userLevel, $contextCode)
 	{
		$query = "select * from tbl_realtime_conversation";
		$res = $this->getArray($query);
		/**
		 *  check if th
		 */
		if(empty($res)){
			/**
			 * no open conversation and student can't start one
			 */
			if(eregi("student",$userLevel)){
				return FAILURE;
			}elseif(eregi("lecturer", $userLevel)){
				/**
				 * no open session yet
				 */
				
				 return SUCCESS;
			}else{
				return FAILURE;
			}
		}else{
			 
			 $ssql = "select * from tbl_realtime_voicequeue";
			 $ress = $this->getArray($ssql);
			 
			if(empty($ress)){
				$sql = "select id from tbl_realtime_conversation";
				$res = $this->getArray($sql);
				foreach($res as $result){
					$lastId = $result['id'];
				}
				$assignToToken = array();
				$assignToToken['conversationid'] = $lastId;
				$assignToToken['userid'] = $userId;
				$assignToToken['hastoken'] = 1;
				$this->insert($assignToToken, 'tbl_realtime_voicequeue');				
				return SUCCESS;
			}else{
				/**
				 * check who is requesting token.
				 * if the requester is lecturer,
				 * give the token. otherwise return token not available
				 */
				 if(eregi("lecturer", $userLevel)){
				 	//delete details of who ever has the token.
				 	
				 	$sql = "delete from tbl_realtime_voicequeue";
				 	$this->query($sql);
				 	
				 	//insert lecturer details//insert lecturer details
					$sql = "select id from tbl_realtime_conversation";
					$res = $this->getArray($sql);
					foreach($res as $result){
						$lastId = $result['id'];
					}				 	
				 	
				 	$lastId = $this->getLastInsertId();
					$assignToken = array();
					$assignToken['conversationid'] = $lastId;
					$assignToken['userid'] = $userId;
					$assignToken['hastoken'] = 1;
					$this->insert($assignToken, 'tbl_realtime_voicequeue');				 	
				 	return SUCCESS;
				 }else{
				 	return FAILURE;
				 }
			}
		}
 	}
 	
 	/**
 	 * release token
 	 * return true, token available.
 	 */
 	public function releaseToken()
 	{
 		$sql = "delete from tbl_realtime_voicequeue";
		$this->query($sql);
		return SUCCESS;
 	}
 	
 	/**
 	 *  end conversation.
 	 *	return false
 	 */
 	public function stopConversation($contextcode){
		$this->removeConversation($contextcode);
 		return SUCCESS;
 	}
 	
 	public function startConversation($userid, $userlevel, $contextcode)
 	{
 		$sql = "select contextcode from tbl_realtime_conversation where contextcode=\"$contextcode\"";
 		$result = $this->getArray($sql);
 		
 		if(empty($result)){
			$this->addConversation($userid, $userlevel, $contextcode);
			return SUCCESS;	
 		}else{
 			foreach ($result as $context){
 				$contextC = $context['contextcode'];
 				$this->removeConversation($contextC);
 			}
 			$this->addConversation($userid, $userlevel, $contextcode);
 			return SUCCESS;
 		}		
 	}
 	
 	private function removeConversation($contextCode)
 	{
 		$sql = "select conversationid from tbl_realtime_conversation where contextcode=\"$contextCode\"";
 		$result = $this->getArray($sql);
 		if(empty($result)){
 			//return FAILURE;
 		}else{
 			foreach ($result as $id){
 				$convId = $id['conversationid'];
 			}
 			//delete entries from voicequeue table
 			$sql = "delete from tbl_realtime_voicequeue where conversationid=\"$convId\"";
 			$this->getArray($sql);
 			
 			//delete entries from conversation table
 			$sql = "delete from tbl_realtime_conversation where id=\"$convId\"";
 			$this->getArray($sql); 	
 			//return SUCCESS;		
 		}
 	}
 	
 	private function addConversation($userid, $userlevel, $contextcode)
 	{
 		$addRow = array();
		$starttime = strftime('%Y-%m-%d %H:%M:%S', mktime());
		$addRow['userid'] = $userid;
		$addRow['contextcode'] = $contextcode;
		$addRow['starttime'] = $starttime;
		$this->insert($addRow, 'tbl_realtime_conversation');			
		 
	 	//lecturer starts one.
		$sql = "select id from tbl_realtime_conversation";
		$res = $this->getArray($sql);
		foreach($res as $result){
			$lastId = $result['id'];
		}
		
		$queue = array();
		$queue['conversationid'] = $lastId;
		$queue['userid'] = $userid;
		$queue['hastoken'] = 1;
		$this->insert($queue, 'tbl_realtime_voicequeue'); 		
 	}
 }
?>
