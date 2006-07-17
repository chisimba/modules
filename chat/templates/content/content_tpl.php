<?php
/**
* Function to parse the username
* @param string The username
* @return string The parsed username
*/
function parseUsername($username)
{
    // Replace single quotes with the HTML single quote
	$username = preg_replace("/'/","&#39;",$username);
	return $username;
}
    
/**
* Function to parse the content
* @param string Thec ontent
* @param object The $this reference
* @return string The parsed entry
*/
function parseContent($content)
{
    // Remove newlines
	$content = preg_replace("/\n/"," ",$content);
    // Remove carriage returns
	//$content = preg_replace("/\r/"," ",$content);
    // Replace single quotes with the HTML single quote
	$content = preg_replace("/'/","&#39;",$content);        
    $content = preg_replace("/\[(.*)\]/",'<img src="skins/_common/icons/smileys/\\1.gif" border="0"/>',$content);
    return $content;
}

$ObjIcon = $this->getObject('geticon','htmlelements');
// Display each entry.
foreach ($content as $entry) {
    // Is this a system post?
	if ($entry["username"]=="") {
		echo "<span class=\"chat-meta\">"
			. parseContent($entry["content"],$this) . "<br/>"
			. "</span>";
	}
    // Is this a public post?
	else if ($entry["recipient"]=="All") {
		echo "<b>[" . parseUsername($entry["username"]) . "]</b>"
			. "&nbsp;" . parseContent($entry["content"],$this) 
			. "&nbsp;<span class=\"minute\"><i>" . strftime("(%a %H:%M)",$entry["stamp"]) . "</i></span>" 
			."<br/>";
	}
    // This is a private post
	else {
		echo "<span class=\"chat-private\">"
			. "<b>*PRIVATE*[" 
			. parseUsername($entry["username"]) 
			. ":" 
			. parseUsername($entry["recipient"]) 
			. "]</b>"
			. "&nbsp;" . parseContent($entry["content"],$this) 
			. "&nbsp;<span class=\"minute\"><i>" . strftime("(%a %H:%M)",$entry["stamp"]) . "</i></span>" 
			. "<br/>"
			. "</span>";
	}
}
?>