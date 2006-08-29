<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}
// end security check

/**
* The chat block class.
* @author Jeremy O'Connor
*/
class block_chat extends object
{
    public $title;
    private $objLanguage;

    public function init()
    {
        $this->objLanguage =& $this->getObject('language','language');
        $this->title = $this->objLanguage->languageText('mod_chat_lastpostinchat','chat');
        $this->loadClass('link', 'htmlelements');
    }

    public function show()
    {
        // Link to the chat.
        $url = $this->uri('', 'chat');
        $icon = $this->newObject('geticon', 'htmlelements');
        $icon->setModuleIcon('chat');
        $icon->alt = $this->objLanguage->languageText('mod_chat_join','chat');
        $objLinkIcon = new link($url);
        $objLinkIcon->link = $icon->show();
        $objLinkText = new link($url);
        $objLinkText->link = $this->objLanguage->languageText('mod_chat_name','chat');
        $chatLink = '<p>'.$objLinkIcon->show().'&nbsp;'.$objLinkText->show().'</p>';

        // Build up output string.
        $str = "";
        // Get user object.
        $objUser =& $this->getObject('user', 'security'); //$objUser->userName()
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
        // Get dbChatContexts object.
        $objDbChatContexts =& $this->getObject('dbchatcontexts');
        // Get context ID.
        $list = $objDbChatContexts->listSingle($context);
        if (empty($list)) {
            return $this->objLanguage->languageText('mod_chat_noposts','chat').$chatLink;
        }
        $contextId = $list[0]["id"];
        // Get dbChat object.
        $objDbChat =& $this->getObject('dbchat');
        // Get the last post.
        $content = $objDbChat->listLast($contextId, $objUser->userName());
        if (empty($content)) {
            $str .= $this->objLanguage->languageText('mod_chat_noposts','chat').$chatLink;
        }
        else {
            $entry = $content[0];
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
                "<b>[" . $this->_parseUsername($entry["username"]) . "]</b>"
                . " "
                . $this->_parseContent($entry["content"])
                . " "
                . "<span class=\"minute\"><i>"
                . strftime("(%a %H:%M)",$entry["stamp"])
                . "</i></span>";
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
        // Show number of users online.
        $objDbChatUsers =& $this->getObject('dbchatusers');
        //$list = $objDbChatUsers->listCount($contextId);
        //$count = $list[0]["count(*)"];
		$count = $objDbChatUsers->listCount($contextId);
        $str .= "<br/>".
            $this->objLanguage->code2Txt(
                'mod_chat_usersonline','chat',
                array(
                    'COUNT'=>$count,
                    'USERS'=>($count==1
                        ?$this->objLanguage->languageText('word_user','chat')
                        :$this->objLanguage->languageText('word_users','chat')
                    )
                )
             );
        $str .= '<br/>'.$chatLink;
        // Return block output string.
        return $str;
    }
	/**
	* Function to parse the username
	* @param string The username
	* @return string The parsed username
	*/
	private function _parseUsername($username)
	{
	    // Replace single quotes with the HTML single quote
		$username = preg_replace("/'/","&#39;",$username);
		return $username;
	}

    /**
    * Function to parse the content
    * @param string The content
    * @return string The parsed content
    */
    private function _parseContent($content)
    {
        // Find the end of the <span> tag.
        $pos1 = strpos($content, ">");
        // Find the begin of the </span> tag.
        $pos2 = strrpos($content, "<");
        // Break up the string.
        $str1 = substr($content, 0, $pos1+1);
        $str2 = substr($content, $pos1+1, $pos2-$pos1-1);
        $str3 = substr($content, $pos2);
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
                    $str .= "<br/>";
                }
                $str .= $element[$i];
            }
            $array2[] = $str;
        }
        $result = $pre . implode(" ", $array2) . $post;
        $content = $result;
	    // Remove newlines
		$content = preg_replace("/\n/"," ",$content);
	    // Remove carriage returns
		//$content = preg_replace("/\r/"," ",$content);
	    // Replace single quotes with the HTML single quote
		$content = preg_replace("/'/","&#39;",$content);
	    $content = preg_replace("/\[(.*)\]/",'<img src="skins/_common/icons/smileys/\\1.gif" border="0"/>',$content);
	    return $content;
        /*
        // Build result string.
        $i = 0;
        $result = "";
        while($i < strlen($content)){
            // Remove newlines
            if ($content[$i] == "\n") {
                $result .= " ";
                $i++;
            }
            // Remove carriage returns
            else if ($content[$i] == "\r") {
                $result .= " ";
                $i++;
            }
            // Replace single quotes with the HTML single quote
            else if ($content[$i] == "'") {
                //$str .= "\\'";
                $result .= "&#39;";
                $i++;
            }
            // Convert [smilexx] to an icon
            else if ($content[$i] == "[") {
                $i++;
                $smiley = "";
                while($i < strlen($content) && $content[$i]!="]"){
                    $smiley .= $content[$i];
                    $i++;
                } // while
                if ($i >= strlen($content)) {
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
                $result .= $content[$i];
                $i++;
            }
        } // while
        return $result;
        */
    }
}
?>