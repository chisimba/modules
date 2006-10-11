<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}


/**
* Model class for chat
* @author Jeremy O'Connor
* @copyright 2004 University of the Western Cape
*/

class model extends object {
    private $objUser;
    private $objLanguage;
	private $objDbChat;
	private $objDbChatContexts;
	private $objDbChatUsers;
    public function init()
    {
        $this->objUser =& $this->getObject('user', 'security');
        $this->objLanguage =& $this->getObject('language','language');
        $this->objDbChat =& $this->getObject('dbchat');
 	    $this->objDbChatContexts =& $this->getObject('dbchatcontexts');
 	    $this->objDbChatUsers =& $this->getObject('dbchatusers');
    }
    /** Sends a string to the chat room.
    * @param string The chat room
    * @param string The string to send.
    * @return
    */
    public function sendToChat($chatroom, $string)
    {
        // Get the context id.
		$list = $this->objDbChatContexts->listSingle($chatroom);
		$contextId = $list[0]["id"];
        // Update last active timestamp
		$this->objDbChatUsers->updateSingle(
			$contextId,
			$this->objUser->userName(),
			mktime()
		);
        // Add the <span></span> tags.
        $string = "<span style=\"
    		font-family: Verdana,verdana,arial,helvetica;
    		font-size: 8pt;
            line-height: 200%;
    		color: Blue;
    		\">"
    		. $string
    		. "</span>";
        // Insert post into database
		$this->objDbChat->insertSingle(
			$contextId,
			$this->objUser->userName(),
			$string,
			'All',
			mktime()
		);
    }
    /** Checks if a user is in the chat room.
    * @param string The chat room.
    * @param string The user id.
    * @return boolean True if the user is in the chat room, false otherwise.
    */
    public function isInChatroom($chatroom, $userId)
    {
         // Get the context id.
		$list = $this->objDbChatContexts->listSingle($chatroom);
		$contextId = $list[0]["id"];
        // Get the username.
        $username = $this->objUser->userName($userId);
        // Check if user in room.
        $list = $this->objDbChatUsers->listsingle($contextId, $username);
        return !empty($list);
    }
    /** Returns a list of rooms the user is in.
    * @param string The user id.
    * @return array A list of rooms the user is in.
    */
    public function getRoomsUserIsIn($userId)
    {
        // Get the username.
        $username = $this->objUser->userName($userId);
        // Get list of rooms.
        $array = $this->objDbChatUsers->getRoomsUserIsIn($username);
        $result = array();
        foreach ($array as $element) {
            $result[] = $element['context'];
        }
        return $result;

    }
	/**
	* Get the last 10 entries.
	* @param string The context
	* @param string The workgroup ID
	* @return string The results
	*/
    public function getLast10($context=null, $workgroupId=null)
	{
        // Build up output string.
        $str = "";
        // Get user object.
        $objUser =& $this->objUser;//$this->getObject('user', 'security'); //$objUser->userName()
		if (is_null($context)) {
	        // Get context object.
	        $objDbContext =& $this->getObject('dbcontext','context');
	        $contextCode = $objDbContext->getContextCode();
	        // Are we in a context ...
	        if ($contextCode == Null) {
	            $context = "Lobby";
	        }
	        // ... else we are in a context.
	        else {
	            $contextRecord = $objDbContext->getContextDetails($contextCode);
	            $context = $contextRecord['title'];
	        }
		}
		if (!is_null($workgroupId)) {
		    $objDbWorkgroup =& $this->getObject('dbworkgroup','workgroup');
			$workgroup = $objDbWorkgroup->getDescription($workgroupId);
			$context .= ' ('.$workgroup.')';
		}
        // Get dbChatContexts object.
 	    $objDbChatContexts =& $this->getObject('dbchatcontexts');
        // Get context ID.
		$list = $objDbChatContexts->listSingle($context);
        if (empty($list)) {
            return '<div class="noRecordsMessage">'.$this->objLanguage->languageText('mod_chat_nopostsinroom').'</div>';
        }
		$contextId = $list[0]["id"];
        // Get dbChat object.
        $objDbChat =& $this->getObject('dbchat');
        // Get the last post.
		$content = $objDbChat->listLast($contextId, $objUser->userName(), '10');
        if (empty($content)) {
            return '<div class="noRecordsMessage">'.$this->objLanguage->languageText('mod_chat_nopostsinroom').'</div>';
        }
        else {
			foreach ($content as $entry) {
	            // Is this a meta entry?
	            /*
	    		if ($entry["username"]=="") {
	    			$str .= "<span class=\"chat-meta\">";
	    			$str .= $entry["text"];
	    			$str .= "</span>";
	    		}
	            */
	            // Is this a public post?
	    		//if ($entry["recipient"]=="All") {
				$str .=
	                "<b>[" . $entry["username"] . "]</b>"
	                . " "
	                . $this->_parseEntry($entry["content"])
	                . " "
	                . "<span class=\"minute\"><i>"
	                . strftime("(%a %H:%M)",$entry["stamp"])
	                . "</i></span><br />";
	    		//}
	            // This is a private post
	            /*
	    		else {
	    			$str .=  "<span class=\"chat-private\">";
	    			$str .=  "<b>*PRIVATE*[" . $entry["username"] . ":" . $entry["recipient"] . "]</b>&nbsp;" . $this->_parseEntry($entry["text"],$icon) . "&nbsp;" . "<span class=\"minute\"><i>" . strftime("(%a %H:%M)",$entry["timestamp"]) . "</i></span>";
	    			$str .=  "</span>";
	    		}
	            */
			}
        }
		/*
        // Show number of users online.
        $objDbChatUsers =& $this->getObject('dbchatusers');
        $list = $objDbChatUsers->listCount($contextId);
        $count = $list[0]["COUNT(*)"];
        $str .= "<br />".
            $this->objLanguage->code2Txt(
                'mod_chat_usersonline',
                array(
                    'COUNT'=>$count,
                    'USERS'=>($count==1
                        ?$this->objLanguage->languageText('word_user')
                        :$this->objLanguage->languageText('word_users')
                    )
                )
             );
        // Link to the chat.
        $icon = $this->newObject('geticon','htmlelements');
        $icon->setIcon('modules/chat');
        $icon->alt = $this->objLanguage->languageText('mod_chat_join');
        $icon->align=false;
		$str .= "<br /><a href=\"" . $this->uri(array(),'chat') . "\">".$icon->show()."</a>";
		*/
        // Return block output string.
		return $str;
    }
    /**
    * Function to parse the entry.
	* @access private
    * @param string The text for the entry
    * @param object The icon object
    * @return string The parsed entry
    */
    private function _parseEntry($entry)
    {
        // Find the end of the <span> tag.
        $pos1 = strpos($entry, ">");
        // Find the begin of the </span> tag.
        $pos2 = strrpos($entry, "<");
        // Break up the string.
        $str1 = substr($entry, 0, $pos1+1);
        $str2 = substr($entry, $pos1+1, $pos2-$pos1-1);
        $str3 = substr($entry, $pos2);
        // Save these for later.
        $pre = $str1;
        $str = $str2;
        $post = $str3;
        // Strip slashes.
        $str = stripslashes($str);
        // Break up long words.
        $array = explode(" ", $str);
        //print_r($array);
        $array2 = array();
        foreach ($array as $element) {
            $str = "";
            $len = strlen($element);
            for ($i = 0; $i < $len; $i++) {
                if ($i > 0 && ($i % 22) == 0) {
                    $str .= "<br />";
                }
                $str .= $element[$i];
            }
            $array2[] = $str;
        }
        $result = $pre . implode(" ", $array2) . $post;
        $entry = $result;
        // Build result string.
        $i = 0;
        $result = "";
        while($i < strlen($entry)){
            // Remove newlines
            if ($entry[$i] == "\n") {
                $result .= " ";
                $i++;
            }
            // Remove carriage returns
            else if ($entry[$i] == "\r") {
                $result .= " ";
                $i++;
            }
            // Replace single quotes with the HTML single quote
            else if ($entry[$i] == "'") {
                //$str .= "\\'";
                $result .= "&#39;";
                $i++;
            }
            // Convert [smilexx] to an icon
            else if ($entry[$i] == "[") {
                $i++;
                $smiley = "";
                while($i < strlen($entry) && $entry[$i]!="]"){
                	$smiley .= $entry[$i];
                	$i++;
                } // while
                if ($i >= strlen($entry)) {
                    break;
                }
                $i++;
                $icon =& $this->getObject('geticon','htmlelements');
                $icon->setIcon("smileys/" . $smiley);
                $icon->align=false;
                $result .= $icon->show();
            }
            // Default : just copy character
            else {
                $result .= $entry[$i];
                $i++;
            }
        } // while
        return $result;
    }
}
?>