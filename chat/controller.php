<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
    die("You cannot view this page directly");
}
// end security check

/**
* Controller for Chat module
* @author Jeremy O'Connor
* @copyright 2004 University of the Western Cape
* $Id$
*/
class chat extends controller
{
    var $objUser;
    var $objConfig;
    var $objLanguage;
    var $objDbChat;
    var $objDbChatContexts;
    var $objDbChatContextMembers;
    var $objDbChatUsers;
    var $objDbChatBannedUsers;

    /**
    * The Init function
    */
    function init()
    {
        $this->objUser =& $this->getObject('user', 'security');
        $this->objHelp =& $this->getObject('helplink','help');
        //$this->objHelp->rootModule="chat";
        $this->objConfig =& $this->getObject('config','config');
        $this->objLanguage =& $this->getObject('language','language');
        $this->objDbChat =& $this->getObject('dbchat');
        $this->objDbChatContexts =& $this->getObject('dbchatcontexts');
        $this->objDbChatContextMembers =& $this->getObject('dbchatcontextmembers');
        $this->objDbChatUsers =& $this->getObject('dbchatusers');
        $this->objDbChatBannedUsers =& $this->getObject('dbchatbannedusers');
        //Get the activity logger class
        $this->objLog=$this->newObject('logactivity', 'logger');
        //Set it to log once per session
        $this->objLog->logOncePerSession = TRUE;
        //Log this module call
        $this->objLog->log();
        /*
		//XAJAX		
        $this->loadClass('xajax', 'htmlelements');
        $this->loadClass('xajaxresponse', 'htmlelements');
        */
     }

    /*
    * Gets a parameter from the URL
    * @param string $name The name of the parameter
    * @param string $default The default value
    */
    function getParamFromURL($name, $default = NULL)
    {
        return isset($_GET[$name])
            ? is_string($_GET[$name])
                ? trim($_GET[$name])
                : $_GET[$name]
            : $default;
    }

    /**
    * The dispatch funtion
    * @param string $action The action
    * @return string The content template file
    */
    function dispatch($action=Null)
    {
		$this->setVar('pageSuppressXML',true);
    	if ($this->getParam('passthroughlogin') == 'true')
    		return $this->nextAction(null);
        $temporary = true;
        $this->setVar('temporary',$temporary);
        $mode = $this->getParam('mode', NULL);
        $this->setVar('mode',$mode);
        // 1. ignore action at moment as we only do one thing - say hello
        // 2. load the data object (calls the magical getObject which finds the
        //    appropriate file, includes it, and either instantiates the object,
        //    or returns the existing instance if there is one. In this case we
        //    are not actually getting a data object, just a helper to the
        //    controller.
        // 3. Pass variables to the template
        $this->setVarByRef('objUser', $this->objUser);
        $this->setVarByRef('objHelp', $this->objHelp);
        $this->setVarByRef('objConfig', $this->objConfig);
        $this->setVarByRef('objLanguage', $this->objLanguage);
        // return the name of the template to use  because it is a page content template
        // the file must live in the templates/content subdir of the module directory
        $error = "";
        $this->setVarByRef("error", $error);
        // Default action is join chat
        if ($action == NULL) {
        	/*
	        // Prepare AJAX - used to determine which records have changed
	        $xajaxClass = new xajax($this->uri(null));
	        $xajaxClass->registerFunction(array($this, 'updateChatUsers')); // Register another function in this controller
	        $xajaxClass->processRequests(); // XAJAX method to be called
	        $this->appendArrayVar('headerParams', $xajaxClass->getJavascript()); // Send JS to header
	        */
            // Get the context
            $this->objDbContext = &$this->getObject('dbcontext','context');
            $this->contextCode = $this->objDbContext->getContextCode();
            // Are we in a context ?
            if ($this->contextCode == NULL) {
                $context = "Lobby";
            }
            else {
            	$context = $this->objDbContext->getTitle();
            }
            // Set up font session vartiables
            $this->setSession('family', 'Verdana');
            $this->setSession('size', '8');
            $this->setSession('color', 'Blue');
            // Set up persistentlogging session variable
            //if (!isset($_SESSION['chat~persistentLogging'])) {
            $this->setSession('persistentLogging', 'on');
            //}
            // Join the context
            $this->join($context);
            // Set action to chat
            $action="chat";
        }
        else if ($action == 'viewlog' || $action == 'searchlog') {
            // Get the context
            $this->objDbContext = &$this->getObject('dbcontext','context');
            $this->contextCode = $this->objDbContext->getContextCode();
            // Are we in a context ?
            if ($this->contextCode == NULL) {
                $context = "Lobby";
            }
            else {
            	$context = $this->objDbContext->getTitle();
            }
        }
        // Leave the chat room
        if ($action == "leave") {
            $context = $this->getParam('context', '');
            // Leave the context
            $this->leave($context);
            // Set action to main
            $action="main";
        }
        // Create a new chat room
        if ($action == "createcontext") {
            $_context = $_POST["newcontext"];
            $list = $this->objDbChatContexts->listSingle($_context);
            // Check if chat room already exists
            if (!empty($list)) {
                $context = $_context;
            }
            else {
                $context = $_context;
                // Insert context into database
                $_contextId = $this->objDbChatContexts->insertSingle(
                    $context,
                    $this->objUser->userName(),
                    'private'
                );
                // Add this user as member of chat room
                $this->objDbChatContextMembers->insertSingle(
                    $_contextId,
                    $this->objUser->userId()
                );
            }
            if ($temporary) {
                // Set action to chat
                $action="chat";
            }
            else {
                // Set action to main
                $action="main";
            }
        }
        // Add a user to a context
        if ($action == 'adduser') {
            $username = $_POST["username"];
            $_userId = $this->objUser->getUserId($username);
            if ($_userId != false) {
                $context = $this->getParam('context', '');
                $list = $this->objDbChatContexts->listSingle($context);
                $_contextId = $list[0]["id"];
                // Add this user as member of chat room
                $this->objDbChatContextMembers->insertSingle(
                    $_contextId,
                    $_userId
                );
            }
            $action="chat";
        }
        // Main page
        if ($action=="main") {
            // List all contexts
            $contexts = $this->objDbChatContexts->listAll();
            // Create array for display
            $list = array();
            $this->setVarByRef("list", $list);
            foreach ($contexts as $context) {
                // Get number of users in chat room
                $list2 = $this->objDbChatUsers->listCount($context["id"]);
                $count = $list2[0]["COUNT(*)"];
                $element = array();
                // Set $element[0] to display string
                $element[0] = $context["context"]
                . " "
                . $this->objLanguage->code2Txt(
                    'mod_chat_usersonline',
                    array(
                        'COUNT'=>$count,
                        'USERS'=>($count==1
                            ?$this->objLanguage->languageText('word_user','chat')
                            :$this->objLanguage->languageText('word_users','chat')
                        )
                    )
                 );
                // Set $element[1] to context
                $element[1] = $context["context"];
                // Add $element to $list
                $list[] = $element;
            }
            return "main_tpl.php";
        }
        // Input iframe
        if ($action == "input") {
            // Get context
            $context = $this->getParam('context', '');
            $this->setVarByRef('context', $context);
            // Get contextId
            $list = $this->objDbChatContexts->listSingle($context);
            $contextId = $list[0]["id"];
            // Get list of users
            $users = $this->objDbChatUsers->listAll($contextId);
            $this->setVarByRef("users", $users);
            // Check if user is banned
            $banned = false;
            $list = $this->objDbChatBannedUsers->listSingle($this->objUser->userName());
            // Is user banned?
            if (!empty($list)) {
                // Get expiry timestamp
                $expire = $list[0]['expire'];
                // Has the banning expired?
                if (mktime()<$expire) {
                    $banned = true;
                }
            }
            // User has posted
            if (isset($_POST["text"])) {
                // Is there any text entered?
                if (!empty($_POST["text"])) {
                    // Is user banned?
                    if ($banned) {
                        $this->objDbChat->insertSingle(
                            $contextId,
                            "",
                            "<b>" . $this->objUser->userName() . "</b> " . $this->objLanguage->languageText("chat_you_are_banned",'chat'),
                            "All",
                            mktime()
                        );
                    }
                    else {
                        $post = $_POST["text"];

						// $document should contain an HTML document.
						// This will remove HTML tags, javascript sections
						// and white space. It will also convert some
						// common HTML entities to their text equivalent.

						$search = array ("'<script[^>]*?>.*?</script>'si",  // Strip out javascript
						                 "'<[\/\!]*?[^<>]*?>'si",           // Strip out html tags
						                 "'([\r\n])[\s]+'",                 // Strip out white space
						                 "'&(quot|#34);'i",                 // Replace html entities
						                 "'&(amp|#38);'i",
						                 "'&(lt|#60);'i",
						                 "'&(gt|#62);'i",
						                 "'&(nbsp|#160);'i",
						                 "'&(iexcl|#161);'i",
						                 "'&(cent|#162);'i",
						                 "'&(pound|#163);'i",
						                 "'&(copy|#169);'i",
						                 "'&#(\d+);'e");                    // evaluate as php

						$replace = array ("",
						                  "",
						                  "\\1",
						                  "\"",
						                  "&",
						                  "<",
						                  ">",
						                  " ",
						                  chr(161),
						                  chr(162),
						                  chr(163),
						                  chr(169),
						                  "chr(\\1)");

						$post = preg_replace ($search, $replace, $post);

                        $objParse =& $this->getObject('parser');
                        $post = $objParse->Parse($post);
                        // Update last active timestamp
                        $this->objDbChatUsers->updateSingle(
                            $contextId,
                            $this->objUser->userName(),
                            mktime()
                        );
                        // Update font characteristics
                        $family = $_POST["family"];
                        $size = $_POST["size"];
                        $color = $_POST["color"];
                        $this->setSession('family', $family);
                        $this->setSession('size', $size);
                        $this->setSession('color', $color);
                        // Create output for chat window
                        $result = "<span style=\""
                        ."font-family: " . $this->getSession('family') . ",verdana,arial,helvetica;"
                        ."font-size: " . $this->getSession('size') . "pt;"
                        ."line-height: 200%;"
                        ."color: " . $this->getSession('color') . ";"
                        . "\">"
                        . $post
                        . "</span>";
                        // Insert post into database
                        $this->objDbChat->insertSingle(
                            $contextId,
                            $this->objUser->userName(),
                            $result,
                            $_POST["recipient"],
                            mktime()
                        );
                    }
                }
            }
            //$this->setPageTemplate("input_page_tpl.php");
            $this->setVar('pageSuppressIM', TRUE);
            $this->setVar('pageSuppressBanner', TRUE);
            $this->setVar('pageSuppressToolbar', TRUE);
            $this->setVar('pageSuppressContainer', TRUE);
            $this->setVar('suppressFooter', TRUE);
			$this->setVar('pageSuppressTrailingDiv', TRUE);
            $script = '
			<script type="text/javascript" language="JavaScript">
			function submitenter(myfield,e)
			{
			var keycode;
			if (window.event) keycode = window.event.keyCode;
			else if (e) keycode = e.which;
			else return true;

			if (keycode == 13)
			   {
			   myfield.form.submit();
			   return false;
			   }
			else
			   return true;
			}
			</script>
            ';
            $this->appendArrayVar('headerParams', $script);
            //$bodyParams=' onLoad="document.getElementById(\'input_text\').focus()" ';
            //$this->setVarByRef('bodyParams',$bodyParams);
            return "input_tpl.php";
        }
        // Turn on persistent logging
        if ($action=="persistentloggingon") {
            $this->setSession('persistentLogging', 'on');
            $action = "chat";
        }
        // Turn off persistent logging
        if ($action=="persistentloggingoff") {
            $this->setSession('persistentLogging', 'off');
            $action = "chat";
        }
        // Get the context from the URL
        if (!isset($context)) {
            $context = $this->getParam('context', '');
        }
        $this->setVarByRef('context', $context);
        // Join chat room
        if ($action == "join") {
            // Set up font session variables
            $this->setSession('family', 'Verdana');
            $this->setSession('size', '8');
            $this->setSession('color', 'Blue');
            // Join the context
            $this->join($context,$this->getParam('type','context'));
            $action = "chat";
        }
        // Join chat room
        if ($action == "joincontext") {
            // Set up font session variables
            $this->setSession('family', 'Verdana');
            $this->setSession('size', '8');
            $this->setSession('color', 'Blue');
            // Join the context
            $this->join($_POST['context'],$this->getParam('type','context'));
            $action = "chat";
        }
        // Get ContextId, ContextUsername and ContextType.
        $list = $this->objDbChatContexts->listSingle($context);
        $contextId = $list[0]["id"];
        $contextUsername = $list[0]["username"];
        $contextType = $list[0]["type"];
        $this->setVarByRef("contextUsername", $contextUsername);
        $this->setVarByRef("contextType", $contextType);
        // Get content of chat
        if ($action == "content") {
            $list = $this->objDbChatUsers->listSingle($contextId, $this->objUser->userName());
            // Get start timestamp for user
            $start = $list[0]["start"];
            // Get content of chat, starting from $start
            $content = $this->objDbChat->listAll($contextId, $this->objUser->userName(), $start, true);
            $this->setVarByRef("content", $content);
            $this->setVar('pageSuppressIM', TRUE);
            $this->setVar('pageSuppressBanner', TRUE);
            $this->setVar('pageSuppressToolbar', TRUE);
            $this->setVar('pageSuppressContainer', TRUE);
            $this->setVar('suppressFooter', TRUE);
			$this->setVar('pageSuppressTrailingDiv', TRUE);
			$headerParams = '
			<script type="text/javascript" language="JavaScript">
			function StartTimer()
			{
				window.setInterval(\'UpdateTimer()\', 10000);
			}
			function UpdateTimer()
			{
				//alert(\'OK\');
				//window.location.href=\'index.php\';
			    window.location.reload();
			}
			</script>
			';	
            $this->appendArrayVar('headerParams',$headerParams);
			$bodyParams = 'onload="StartTimer()"';
            $this->setVarByRef('bodyParams',$bodyParams);
            //$this->setPageTemplate('content_page_tpl.php');
            $this->setLayoutTemplate("content_layout_tpl.php");
            return "content_tpl.php";
        }
        // Ban a user
        if ($action=="ban") {
            // Check for proper submit
            if (isset($_POST['expire'])) {
                // Calculate expiry timestamp
                switch($_POST['expire']){
                    case '1':
                        $expire = mktime()+60*1;
                        break;
                    case '5':
                        $expire = mktime()+60*5;
                        break;
                    case '10':
                        $expire = mktime()+60*10;
                        break;
                    default:
                        ;
                } // switch
                // First flush out banned users
                $this->objDbChatBannedUsers->deleteSingle(
                    $_POST['username']
                );
                // Now add a record to implement a ban on the user
                $this->objDbChatBannedUsers->insertSingle(
                    $_POST['username'],
                    $expire
                );
            }
            //$action = "users";
            return $this->nextAction('users',array('context'=>$context),'chat');
        }
        // Display the users in the chat context
        if ($action == "users") {
            //$this->setVar('pageSuppressContainer', TRUE);
            //$this->setVar('pageSuppressBanner', TRUE);
            //$this->setVar('pageSuppressToolbar', TRUE);
            //$this->setVar('suppressFooter', TRUE);
            //$this->setVar('pageSuppressIM', TRUE);
            //$this->appendArrayVar('headerParams',$headerParams);
            // Get all the users in the chat context
            $users = $this->objDbChatUsers->listAll($contextId);
            foreach ($users as $user) {
                // If the user has not been active for the last 30 minutes...
                if ($user['lastactive'] < (mktime()-30*60)) {
                    $this->objDbChatUsers->deleteSingle($contextId, $user['username']);
                }
            }
            $users = $this->objDbChatUsers->listAll($contextId);
            $this->setVarByRef("users", $users);
            $this->setVar('pageSuppressIM', TRUE);
            $this->setVar('pageSuppressBanner', TRUE);
            $this->setVar('pageSuppressToolbar', TRUE);
            $this->setVar('pageSuppressContainer', TRUE);
            $this->setVar('suppressFooter', TRUE);
			$this->setVar('pageSuppressTrailingDiv', TRUE);
			$headerParams = '
			<script type="text/javascript" language="JavaScript">
			function StartTimer()
			{
				window.setInterval(\'UpdateTimer()\', 30000);
			}
			function UpdateTimer()
			{
				//alert(\'OK\');
				//window.location.href=\'index.php\';
			    window.location.reload();
			}
			</script>
			';
            $this->appendArrayVar('headerParams',$headerParams);
			$bodyParams = 'onload="StartTimer()"';
            $this->setVarByRef('bodyParams',$bodyParams);
            //$this->setPageTemplate('users_page_tpl.php');
            $this->setLayoutTemplate("users_layout_tpl.php");
            return "users_tpl.php";
        }
        // View the log
        if ($action == "viewlog") {
			//$this->setVar('context',$context);
            // Get all entries
            $content = $this->objDbChat->listAll($contextId, $this->objUser->userName(), 0, false);
            $this->setVarByRef("content", $content);
            return "viewlog_tpl.php";
        }
        // View the log
        if ($action == "searchlog") {
        	//echo $_POST['searchterm'];
			//$this->setVar('context',$context);
            // Get all entries // listAll
            $content = $this->objDbChat->search($contextId, $this->objUser->userName(), 0, false, $_POST['searchterm']);
            $this->setVarByRef("content", $content);
            return "viewlog_tpl.php";
        }
        // Clear the log
        if ($action == "clearlog") {
            // Delete all entries
            $this->objDbChat->deleteAll($contextId);
            $action = "chat";
        }
        // Handle main chat page
        if ($action=="chat") {
            //$users = $this->objDbChatUsers->listAll($contextId);
            //$this->setVarByRef("users", $users);
            /*
	        $script = '
			<script type="text/javascript" language="javascript">
			function scrolltoend()
			{
				document.getElementById("contentdiv").scrollTop=document.getElementById("contentdiv").scrollHeight;
			}
			</script>        
		    ';
		    $this->appendArrayVar('headerParams', $script);
		    */
            if ($mode == 'compact') {
                $this->setVar('pageSuppressBanner', TRUE);
                $this->setVar('pageSuppressToolbar', TRUE);
                $this->setVar('suppressFooter', TRUE);
            }
			else {
			    if ($contextType == 'workgroup') {
					// Add workgroup description to breadcrumbs
			        // Get the workgroup object
			        $objDbWorkgroup=$this->getobject('dbworkgroup', 'workgroup');
					// Get workgroup ID
			        $workgroupId = $objDbWorkgroup->getWorkgroupId();
					// Get workgroup description
			        $workgroupDescription = $objDbWorkgroup->getDescription($workgroupId);
					// Load the link class
					$this->loadClass('link', 'htmlelements');
					// Create the link
					$workgroupLink = new link ($this->uri(array(), 'workgroup'));
					// Set the link
					$workgroupLink->link = $workgroupDescription;
					// Get the breadcrumbs object
					$objBreadcrumbs =& $this->getObject('tools','toolbar');
					// Insert the breadcrumb
					$objBreadcrumbs->insertBreadCrumb(array($workgroupLink->show()));
				}
			}
            return "chat_tpl.php";
        }
    }
    /**
    * Function to handle joining a chat room
    * @param string The context to join
    */
    function join($context, $type = 'context')
    {
        // Lookup the context in the database
        $list = $this->objDbChatContexts->listSingle($context);
        // Add context to context table if not there
        if (empty($list)) {
            $this->objDbChatContexts->insertSingle(
                $context,
                'admin', //$this->objUser->userName()
                $type
            );
            $list = $this->objDbChatContexts->listSingle($context);
        }
        $contextId = $list[0]["id"];
        // Handle persistent logging.
        if ($this->getSession('persistentLogging')=='on') {
            $content = $this->objDbChat->listAll($contextId, $this->objUser->userName(), 0, true);
            // Count number of posts
            $lines=0;
            foreach ($content as $entry) {
                $lines++;
            }
            // If no entries are found then atart now
            if ($lines==0) {
                $start = mktime();
            }
            // otherwise start at first entry
            else {
                $line = 0;
                $entry = $content[$line];
                $start = $entry['stamp'];
            }
        }
        else {
            $start = mktime();
        }
        //
        // Get rid of records left behind if user never left the room previously
        $this->objDbChatUsers->deleteSingle($contextId, $this->objUser->userName());
        // Add the user to the context
        $this->objDbChatUsers->insertSingle(
            $contextId,
            $this->objUser->userName(),
            $start,
            mktime()
        );
        // Insert notification into chat
        $this->objDbChat->insertSingle(
            $contextId,
            "",
            "<b>" . $this->objUser->userName() . "</b> " . $this->objLanguage->languageText("chat_has_entered",'chat') . ".",
            "All",
            mktime()
        );
    }
    /**
    * Function to handle leaving a chat room
    * @param string The context to leave
    */
    function leave($context)
    {
        // Get the context ID
        $list = $this->objDbChatContexts->listSingle($context);
        $contextId = $list[0]["id"];
        // Remove the user from the context
        $this->objDbChatUsers->deleteSingle($contextId, $this->objUser->userName());
        // Insert notification into chat
        $this->objDbChat->insertSingle(
            $contextId,
            "",
            "<b>" . $this->objUser->userName() . "</b> " . $this->objLanguage->languageText("chat_has_left",'chat') . ".",
            "All",
            mktime()
        );
    }
    /*
    function updateChatUsers()
    {
        $objResponse = new xajaxResponse();
        $objResponse->addAppend('usersdiv', 'innerHTML', ','.date('Y-m-d').'<br />');
        return $objResponse->getXML();    	
    }
    */
}
?>