<?php
	// security check - must be included in all scripts
	if (!$GLOBALS['kewl_entry_point_run'])
	{
		die("You cannot view this page directly");
	}
	// end security check
	
	/**
	* Manages all db trans actions for ajax chat
	* 
	* @author Shafeek Naidoo
	* @version v1.0
	* @copyright 2006, University of the Western Cape & AVOIR Project
	* @license GNU GPL
	* @package ajaxchat
	**/
	
	class dbajaxchat extends dbTable
	{
		
		
		/**
		* @var object $objUser
		*/
		var $objUser; 
		
		public function init()
		{
			parent::init("tbl_ajaxchat_message");
			$this->objUser =& $this->getObject('user','security');
		}
		
		
		/**
		* Function to return a list of user created rooms
		* @param userid
		*/
		
		public function getRooms($userId)
		{
			$sql = "SELECT * FROM tbl_ajaxchat_rooms WHERE created_by = '$userId'";
			return $this->execute($sql);
		}
		
		
		/**
		* Function to return the lobby
		* @param userid
		*/
		
		public function getLobby()
		{
			$sql = "SELECT * FROM tbl_ajaxchat_rooms WHERE id = 101";
			return $this->execute($sql);
		}
		
		/**
		* This method to execute an arbtrary query 
		* @param $sql query to be excecuted
		* @return associative array with column name value pairs 
		*/
		public function execute($sql)
		{
			
			return $this->getArray($sql);
			
		}
		
		/**
		* This method inserts values into the database 
		* 
		* @return associative array with column name value pairs 
		*/
		public function insertChatMessage($message,$room)
		{
			
			//$message = $this->getParam('message');
			$name = $this->objUser->fullName();
			
			$userId = $this->objUser->userId();
			
			$chat_room = $room;
			
			//$this->setSessionVariable('id',$id);
			
			$time = date('Y-m-d,H:i');
			$seconds = date('U');
			
			$values = array('chat_id'=>$chat_room,'message'=>$message,'user_name'=>$name,'post_time'=>$time,'post_seconds'=>$seconds,'user_id'=>$userId);
			$tablename = 'tbl_ajaxchat_message';
			return @$this->add($values,$tablename);
			
		}
		
		/**
		* This method is used to insert data into the table
		*
		*/
		
		public function add($fields,$tablename = '')
		{
			return $this->insert($fields,$tablename);
		}
		
		/**
		* Function to retrieve the message from the database
		* 
		*/
		public function getMessage($room,$id = null)
		{
			$sql = "SELECT * FROM tbl_ajaxchat_message WHERE chat_id = '$room' AND id = '$id'";
			
			
			
			return $this->execute($sql);
		}
		
		/**
		* function to retrieve the last entered text for the database
		* 
		*
		*/
		public function getMessages($room)
		{
			
			$id_first = $this->getSessionVariable('id_first');
			
			$id_last = $this->lastChat($room);
			
			
			if(!isset($id_first))
			{
				$id_first = $id_last[0]['id'];
			}
			
			
			
			//update old id session
			$this->setSessionVariable('id_first',$id_last[0]['id']);
			
			if($id_last[0]['id'] > $id_first)
			{
				$sql = "SELECT * from tbl_ajaxchat_message WHERE chat_id = '$room' AND id > '$id_first' ORDER BY post_seconds ASC ";
			}
					
			if(isset($sql))
			{
				return $this->execute($sql);
			}
			
		}
		
		
		/**
		* function to retrieve the last chat_id of the table
		* @param room
		*/
		public function lastChat($room)
		{
			$sql = "SELECT *  FROM tbl_ajaxchat_message where chat_id = '$room' ORDER BY id DESC";
			$records = $this->execute($sql);
			
			/*
			$count = count($records);
			$count--;
			*/
			
			$temp = array();
			
			
			$temp[0]['id'] = $records[0]['id'];
			
			return $temp;
			
			
		}
		
		public function setSessionVariable($name, $value)
		{
			$this->setSession($name,$value);
		}
	
		/**
		* Function to get session variable
		*/
		public function getSessionVariable($name,$date = null)
		{
			return $this->getSession($name,$date);
		}
		
		
		/**
		* function to enter a new user into the chat room
		*
		*/
		
		public function enter_room()
		{
			$room = $this->getSessionVariable('room');
			
			$name = $this->objUser->fullName();
			
			$userId = $this->objUser->userId();
			
			$in_time = date('Y-m-d');
			
			$values = array('chat_id'=>$room,'user_id'=>$userId,'user_name'=>$name,'enter_time'=>$in_time);
			$table = 'tbl_ajaxchat_users';
			
			//check if user is already in the room
			return @$this->add($values,$table);
			
		}
		
		
		/**
		* function to check if a person is already in a room
		* @param userid
		* @return true on success
		*/
		public function getLoggedOn()
		{
			
			$startLogon = $this->getSessionVariable('startLogon');
			
			$room = $this->getSession('room');
			
			$lastLogon = $this->lastEntered($room);
			
			 
			
			//make sure only see the message from when user starts chatting
			if(!isset($startLogon))
			{
				$startLogon = $lastLogon[0]['id'];
			} 
			 
			//updating
			$this->setSessionVariable('startLogon',$lastLogon[0]['id']);
			
			if( $lastLogon[0]['id'] > $startLogon)
			{
				$sql = "SELECT * FROM tbl_ajaxchat_users WHERE chat_id = '$room' AND id > '$startLogon'";
			}
			
			
			
			if(isset($sql))
			{
				return $this->execute($sql);
			}
		}
		
		
		/**
		* function to get an update of users that entered the room
		* @param userid
		* @return true on success
		*/
		public function getLoggedOnList()
		{
			
			$startLogonList = $this->getSessionVariable('startLogonList');
			
			$room = $this->getSession('room');
			
			$lastLogon = $this->lastEntered($room);
			
			 
			
			//make sure only see the message from when user starts chatting
			/* if(!isset($startLogon))
			{
				$startLogon = $lastLogon[0]['id'];
			} */ 
			 
			//updating
			//$this->setSessionVariable('startLogonList',$lastLogon[0]['id']);
			
			if( $lastLogon[0]['id'] > $startLogonList)
			{
				$sql = "SELECT * FROM tbl_ajaxchat_users WHERE chat_id = '$room' AND id > '$startLogonList'";
			}
			
			
			
			if(isset($sql))
			{
				return $this->execute($sql);
			}
			
			
		}
		
		
		/**
		* function to track when a new user has entered a room
		* 
		*/
		public function lastEntered($room)
		{
			$sql = "SELECT *  FROM tbl_ajaxchat_users where chat_id = '$room' ORDER BY id DESC";
			$records = $this->execute($sql);
			
			$temp = array();
			 
			
			$temp[0]['id'] = $records[0]['id'];
			
			return $temp;
		}
		
		
		/**
		* function to log the user out of the room
		*
		*/
		public function leaveRoom()
		{
			$userId = $this->objUser->userId();
			
			//$sql =  "SELECT id FROM tbl_ajaxchat_users WHERE user_id = '$userId'";
			
			//$user = $this->execute($sql);
			
			
			//delete all messages of user
			@$this->delete('user_id',$userId,'tbl_ajaxchat_message');
			
			@$this->delete('user_id',$userId,'tbl_ajaxchat_users');
			
		}
		
		
		/**
		* function to update the users table when chatter leaves
		*
		*/
		public function updateUsers()
		{
			$fields = array('inRoom'=>'0');
			$userId = $this->objUser->userId();
			@$this->update('user_id',$id,$fields,'tbl_ajaxchat_users');
		}
		
		/**
		* function to get the logged out user
		*/
		public function loggedOut($room)
		{
			$firstLogout = $this->getSessionVariable('first_logout');
			
			$lastLogout = $this->lastLogout($room);
			
			if(!isset($firstLogout))
			{
				$firstLogout = $lastLogoutast[0]['id'];
			}
			
			$this->getSessionVariable('first_logout',$lastLogout[0]['id']);
			
			
			if($lastLogoutast[0]['id'] > $firstLogout)
			{
				$sql = "SELECT * from tbl_ajaxchat_users WHERE chat_id = '$room' AND id > '$firstLogout' ORDER BY post_seconds ASC ";
			}
					
			if(isset($sql))
			{
				return $this->execute($sql);
			}
			
			
			
		}
		
		
		/**
		* function to get the last user that logged out
		*/
		
		public function lastLogout($room)
		{
			$sql = "SELECT * FROM tbl_ajaxchat_users where chat_id = '$room' AND inRoom = 0";
			
			$records = $this->execute($sql);
			
			$temp = array();
			
			
			$temp[0]['id'] = $records[0]['id'];
			
			return $temp;
			
		}
		
			
		
		
		
		
	}
	
?>
