<?php
/* ------------icon request template----------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	die("You cannot view this page directly");
}
// end security check
if ($objUser->isLoggedIn()) {
    $userName = $this->objUser->userName();
} else {
    $userName = "Guest";
}
$applet = '
<applet 
codebase="modules/ircchat/resources"
code="IRCApplet.class"
archive="
	irc.jar,
	pixx.jar
" 
width="640" 
height="400"
>
<param name="CABINETS" 
value="
	irc.cab,
	securedirc.cab,
	pixx.cab
"
/>
<param name="nick" value="'.$userName.'"/>
<param name="alternatenick" value="Guest"/>
<param name="name" value="Java User"/>
<param name="host" value="irc.uwc.ac.za"/>
<param name="gui" value="pixx"/>
<param name="command1" value="join #t"/>
</applet>
';
$content = $applet;
echo $content;
?>