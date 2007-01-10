<script language="JavaScript">
parent.document.getElementById('usersdiv').innerHTML=
'<?php


$content = $this->getContent();
//$content = preg_replace("/'/","\'",$content);
echo $content;
?>';
</script>
<script language="JavaScript">
toggleBan=0;
function ToggleBan()
{
	if (toggleBan==0) {
		document.getElementById("bandiv").style.visibility="visible";
		toggleBan=1;
	}
	else {
		document.getElementById("bandiv").style.visibility="hidden";
		toggleBan=0;
	}
}
</script>
<?php
/*
echo "[".(mktime()-30*60)."]";
echo('<pre>');
print_r($users[0]);
echo('</pre>');
*/
//Put the main content
//echo $this->getContent();
//Use to check for admin user:
$isAdmin = $this->objUser->isAdmin();
//Use to check for lecturer in context:
$isLecturer = false;
$objDbContext = &$this->getObject('dbcontext','context');
$contextCode = $objDbContext->getContextCode();
if($contextCode != NULL){
    $userPKId=$this->objUser->PKId($this->objUser->userId());
    $objGroups=$this->getObject('groupAdminModel','groupadmin');
    $groupid=$objGroups->getLeafId(array($contextCode,'Lecturers'));
    if($objGroups->isGroupMember( $userPKId, $groupid )){
        $isLecturer = true;
    }
}
if (($contextType == 'private' && $contextUsername == $this->objUser->userName()) || $isAdmin || $isLecturer || $this->isValid('ban')) {
?>
	<table>
	<tr>
	<td>
	<a href="javascript:;" onclick="ToggleBan();">
<?php
    // Display the ban user button.
	$icon = $this->getObject('geticon','htmlelements');
	$icon->setIcon('chat/banuser');
	$icon->align=false;
	$icon->alt = $objLanguage->languageText('mod_chat_banuser','chat');
	echo $icon->show();
?></a></td><td><?php
    // Display the ban user layer.
	echo '<div id="bandiv" name="bandiv" class="odd" style="visibility:hidden; position:absolute; ">';
	$form = new form("banForm", 
		$this->uri(array(
	    	'module'=>'chat',
			'action'=>'ban',
			'context'=>$context,
		))	
	);
	$form->setDisplayType(1);
	$form->addToForm($objLanguage->languageText("chat_ban",'chat'));
    // Display a list of users to ban.
	$dropdown = new dropdown("username");
	foreach ($users as $user) {
		$dropdown->addOption($user["username"],$user["firstname"] . " " . $user["surname"]);
	}
	$form->addToForm($dropdown);
	$form->addToForm($objLanguage->languageText("word_for"));
    // Display a list of times to ban the user for.
	$dropdown = new dropdown("expire");
	$dropdown->addOption("1","1 min");
	$dropdown->addOption("5","5 mins");
	$dropdown->addOption("10","10 mins");
	$form->addToForm($dropdown);
    // Display the submit button.
	$button = new button("submit", $objLanguage->languageText("word_submit") . "!");
	$button->setToSubmit();
	$form->addToForm($button);
	echo $form->show();
	echo "</div>";
?>
	</td>
	</tr>
	</table>
<?php
}
?>