<!--<script language="JavaScript">
    window.setInterval('xajax_updateChatUsers()', 1000);
</script>-->
<?php
	//echo $mode;
    //$temporary = true;
    // Load classes
    $this->loadClass("iframe","htmlelements");
    $this->loadClass("form","htmlelements");
    $this->loadClass("textinput","htmlelements");
    $this->loadClass("dropdown","htmlelements");
    $this->loadClass("button","htmlelements");

?>
<!--<div style="background-color: #008080;">-->
<?php
    //
    echo '<table width="100%">';
    echo "<tr>";
    if ($mode == 'compact') {
        echo "<td valign=\"top\">";
    }
    else {
        echo "<td width=\"80%\" valign=\"top\">";
    }
?>
<!--<div style="background-color: #008080; padding:5px;">-->
<!--<div style="background-color: #000080; padding:5px;">-->
<!--<p style="color:#ffffff">-->
<?php
    echo "<h3>" . $objLanguage->languageText("chat_room",'chat') . " : <b>" . $context . "</b></h3>";
?>
<!--</p>-->
    <div style="background-color: #FFFFFF;"><!-- padding:5px;-->
<?php
    // Layer for displaying chat
    echo "<div id='contentdiv'
        style='
			border: 1px solid #c0c0c0;
            background-color: #FFFFFF;
            height: 200px;
            overflow: auto;
        '>Text</div>";
?>
    </div>
<!--</div>-->
<!--</div>-->
<!--<div style="background-color: #008080; padding:5px;">-->
<!--<div style="background-color: #000080; padding:5px;">-->
    <div style="background-color: #FFFFFF;"><!-- padding:5px;-->
<?php
    // Input iframe for user input.
    $iframe = new iframe();
    $iframe->id = "input";
    $iframe->name = "input";
    $iframe->width = "100%";
    $iframe->height = "100";
    $iframe->frameborder = "0";
    //$iframe->scrolling="yes";
    $iframe->src =
        $this->uri(array(
            'module'=>'chat',
            'action'=>'input',
            'context'=>$context
        ));
    echo $iframe->show();
?>
    </div>
<!--</div>-->
<!--</div>-->
<?php
    if ($mode != 'compact') {
?>
    <br />
    <div>
    <table>
    <tr>
    <td align="left">
<?php
    // Leave the chat room. Return to the default page or a predefined page.
    if ($contextType == 'workgroup') {
        $uri = $this->uri(array(),'workgroup');
    }
    else {
        if ($temporary) {
            $uri = $this->uri(array(),'_default');
        }
        else {
            $uri = $this->uri(array(
                'module'=>'chat',
                'action'=>'leave',
                'context'=>$context
            ));
        }
    }
    $icon = $this->getObject('geticon','htmlelements');
    $icon->setIcon('chat/leaveroom');
    $icon->align=false;
    $icon->alt = $objLanguage->languageText("mod_chat_leaveroom",'chat');
    echo "<a href=\"".$uri."\">".$icon->show()."</a>";
?>
    </td>
    <td align="left">
<?php
    if ($contextType != 'workgroup' && $temporary) {
        // Join another chat room.
        $frmContext=& $this->newObject('form','htmlelements');
        $frmContext->name='joincontext';
        $frmContext->setAction(
            $this->uri(array(
                'module'=>'chat',
                'action'=>'joincontext'
            ))
        );
        $frmContext->setDisplayType(3);
        $dropdown = new dropdown('context');
        // Add the current context to the dropdown and select it.
        $objDbContext =& $this->getObject('dbcontext','context');
        $contextCode = $objDbContext->getContextCode();
        if ($contextCode == Null) {
            $contextTitle = "Lobby";
        }
        else {
            $contextTitle = $objDbContext->getTitle($contextCode);
        }
        $dropdown->addOption($contextTitle,$contextTitle);
        $dropdown->setSelected($contextTitle);
        // Add all the private rooms.
        $dropdown->addFromDB(
            $this->objDbChatContextMembers->getContextsForUser($this->objUser->userId()),//$this->objDbChatContexts->listAll('private'),
            'context',
            'context',
            NULL
        );
        $frmContext->addToForm($dropdown);
        $button=new button();
        $button->setToSubmit();
        $button->setValue($this->objLanguage->languageText('word_go'));
        $frmContext->addToForm($button);
        echo $frmContext->show();
    }
?>
    </td>
    <td align="left">
<?php
    if ($contextType != 'workgroup' && $temporary) {
?>
    <table>
    <tr>
    <script language="JavaScript">
    toggleCreate=0;
    </script>
    <td>
    <a href="javascript:;" onclick="
    if (toggleCreate==0) {
        document.getElementById('creatediv').style.visibility='visible';
        toggleCreate=1;
    }
    else {
        document.getElementById('creatediv').style.visibility='hidden';
        toggleCreate=0;
    }
    ">
<?php
    // Display the add button.
    $icon = $this->getObject('geticon','htmlelements');
    $icon->setIcon('add');
    $icon->align=false;
    $icon->alt = $objLanguage->languageText("mod_chat_createroom",'chat');
    echo $icon->show();
?>
    </a>
    </td>
    <td>
<?php
    // Display the create room layer.
    echo "<div id=\"creatediv\" name=\"creatediv\" class=\"odd\"
    style=\"
        visibility:hidden;
        position:absolute;
    \">";
    // Create a chat room.
    $form = new form("createForm",
        $this->uri(array(
            'module'=>'chat',
            'action'=>'createContext'
        ))
    );
    $form->setDisplayType(3);
    //$form->addToForm($objLanguage->languageText('chat_new_room').":");
    $form->addToForm(new textinput("newcontext",""));
    $button = new button("submit", $objLanguage->languageText("chat_create",'chat'));
    $button->setToSubmit();
    $form->addToForm($button);
    echo $form->show();
    echo "</div>";
?>
    </td>
    </tr>
    </table>
<?
    }
?>
    </td>
    <td align="left">
<?
    if ($contextType == 'private' && $contextUsername == $this->objUser->userName()) {
?>
    <table>
    <tr>
    <script language="JavaScript">
    toggleAddUser=0;
    </script>
    <td>
    <a href="javascript:;" onclick="
    if (toggleAddUser==0) {
        document.getElementById('adduserdiv').style.visibility='visible';
        toggleAddUser=1;
    }
    else {
        document.getElementById('adduserdiv').style.visibility='hidden';
        toggleAddUser=0;
    }
    ">
<?php
    // Display the add button.
    $icon = $this->getObject('geticon','htmlelements');
    $icon->setIcon('user_user');
    $icon->align=false;
    $icon->alt = $objLanguage->languageText("mod_chat_adduser",'chat');
    echo $icon->show();
?>
    </a>
    </td>
    <td>
<?php
    // Display the add user layer.
    echo "<div id=\"adduserdiv\" name=\"adduserdiv\" class=\"odd\"
    style=\"
        visibility:hidden;
        position:absolute;
    \">";
    // Add a user.
    $form = new form("addUserForm",
        $this->uri(array(
            'module'=>'chat',
            'action'=>'addUser',
            'context'=>$context
        ))
    );
    $form->setDisplayType(3);
    //$form->addToForm($objLanguage->languageText('chat_new_room').":");
    $form->addToForm(new textinput("username",""));
    $button = new button("submit", $objLanguage->languageText("chat_add",'chat'));
    $button->setToSubmit();
    $form->addToForm($button);
    echo $form->show();
    echo "</div>";
?>
    </td>
    </tr>
    </table>
<?
    }
?>
    </td>
    <td align="left">
<?php
    // View the chat log.
    echo "<a href=\"" .
        $this->uri(array(
            'module'=>'chat',
            'action'=>'viewLog',
            'context'=>$context
        )) . "\" target=\"_blank\">";
    $icon = $this->getObject('geticon','htmlelements');
    $icon->setIcon('chat/viewlog');
    $icon->align=false;
    $icon->alt = $objLanguage->languageText("mod_chat_viewlog",'chat');
    echo $icon->show();
    echo "</a>";
    // Show the clear log icon.
    if ($contextUsername == $objUser->userName()) {
        echo "&nbsp;";
        echo "<a href=\"" .
            $this->uri(array(
                'module'=>'chat',
                'action'=>'clearLog',
                'context'=>$context,
            )) . "\">";
        $icon = $this->getObject('geticon','htmlelements');
        $icon->setIcon('chat/clearlog');
        $icon->align=false;
        $icon->alt = $objLanguage->languageText("chat_clear_log",'chat');
        echo $icon->show();
        echo "</a>";
    }
    // Show the persistent logging icon.
    if ($this->getSession('persistentLogging')=='on') {
        echo "<a href=\"".
        $this->uri(array(
            'module'=>'chat',
            'action'=>'persistentLoggingOff',
            'context'=>$context,
        ))
        ."\">";
        $icon = $this->getObject('geticon','htmlelements');
        $icon->setIcon('chat/toggleloggingon');
        $icon->align=false;
        $icon->alt = $objLanguage->languageText("mod_chat_persistentloggingon",'chat');
        echo $icon->show();
        echo "</a>";
    }
    else {
        echo "<a href=\"".
        $this->uri(array(
            'module'=>'chat',
            'action'=>'persistentLoggingOn',
            'context'=>$context,
        ))
        ."\">";
        $icon = $this->getObject('geticon','htmlelements');
        $icon->setIcon('chat/toggleloggingoff');
        $icon->align=false;
        $icon->alt = $objLanguage->languageText("mod_chat_persistentloggingoff",'chat');
        echo $icon->show();
        echo "</a>";
    }
?>
    </td>
    </tr>
    </table>
    </div>
<?php
    echo "</td>";
    echo "<td width=\"20%\" valign=\"top\">";
?>
<!--<div style="background-color: #000080; padding:5px;">-->
<!--<p style="color:#ffffff">--><h3>Logged in users</h3><!--</p>-->
    <!--<div style="
		border: 5px outset #c0c0c0;
		background-color: #FFFFFF;
		padding:5px;
	">-->
<?php
    // Layer for displaying users
    echo "<div id='usersdiv'
        style='
			border: 1px solid #c0c0c0;
            background-color: #FFFFFF;
            height: 200px;
            overflow: auto;
        '></div>";
	// Self-refreshing hidden iframe for list of users
    $iframe = new iframe();
    $iframe->name = "users";
    $iframe->width = "100%";
    $iframe->height = "150px";
    $iframe->frameborder = "0";
    $iframe->src =
        $this->uri(array(
            'module'=>'chat',
            'action'=>'users',
            'context'=>$context
        ));
    echo $iframe->show();
?>
    <!--</div>-->
<?php
    } // if ($mode != 'compact') {
?>
<!--</div>-->
<?php
    echo "</td>";
    echo "</tr>";
    echo "</table>";
    // Self-refreshing hidden iframe for chat content
    $iframe = new iframe();
    $iframe->name = "content";
    $iframe->width = "0";
    $iframe->height = "0";
    $iframe->src =
        $this->uri(array(
            'module'=>'chat',
            'action'=>'content',
            'context'=>$context
        ));
    echo $iframe->show();
?>
<!--</div>-->