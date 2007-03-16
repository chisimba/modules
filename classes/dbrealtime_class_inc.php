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
			if($userLevel=="Student"){
				return FAILURE;
			}elseif($userLevel == "Lecturer"){
				/**
				 * no open session yet
				 */
				 $addRow = array();
				 $starttime = strftime('%Y-%m-%d %H:%M:%S', mktime());
				 $addRow['contextcode'] = $contextCode;
				 $addRow['starttime'] = $starttime;
				 $this->insert($addRow, 'tbl_realtime_conversation');
				
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
				 if($userLevel == "Lecturer"){
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
 	public function endConversation($userLevel){
 		$sql = "delete from tbl_realtime_conversation";
 		$this->query($sql);
 		$sql = "delete from tbl_realtime_voicequeue";
 		$this->query($sql);
 		return FAILURE;
 	}
 	
 	public function startConversation($userid, $userlevel, $contextcode)
 	{
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
		return SUCCESS;		
 	}
 }
?>
