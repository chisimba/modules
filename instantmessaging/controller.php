<?php
/* -------------------- helloworld class extends controller ----------------*/
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	die("You cannot view this page directly");
}
// end security check

/**
* Controller for Instant Messaging module
* @author Jeremy O'Connor
* @copyright 2004 University of the Western Cape
* $Id$
*/

// Code to implement ICQ functionality.

define("ICQ_DOMAIN","pager.icq.com"); // leave this alone; this is Mirabilis' ICQ Pager server
define("REPLY_TO","joconnor@uwc.ac.za"); // put your master reply-to address here

class ICQPager {
	var $icq_no = 0;
	var $from_addr = "";
	var $subj = "ICQPager Message";

	function ICQPager($icq_no,$from_addr) {
		if (!$icq_no) { echo "Must specify ICQ number"; die(""); }
		$this->icq_no=$icq_no;
		if (!$from_addr) { echo "Must specify sender address"; die(""); }
		$this->from_addr=$from_addr;
	}
	
	function subject($s) {
		$this->subject = $s;
	}
	
	function send($s) {
		$msg = str_replace("\r","",$s);
		
		mail($this->icq_no."@".ICQ_DOMAIN,
				 $this->subj, $msg,
     "From: $this->from_addr\n"
    ."Reply-To: ".REPLY_TO."\n"
    ."X-Mailer: PHP/ICQ Gateway");
	}
}

/**
* The controller for the instantmessaging module.
*/
class instantmessaging extends controller
{
    var $objUser;
	//var $objHelp;
	var $objLanguage;
	var $objDbLoggedInUsers;
	var $objDbEntries;
	var $objIpToCountry;

    /**
    * The Init function
    */
    function init()
    {
        $this->objUser =& $this->getObject('user', 'security');
        //$this->objHelp=& $this->getObject('helplink','help');
        //$this->objHelp->rootModule="";
        $this->objLanguage =& $this->getObject('language','language');
        $this->objDbLoggedInUsers =& $this->getObject('dbloggedinusers'); 
        $this->objDbEntries =& $this->getObject('dbentries'); 
		$this->objDbOptions = $this->getObject('dboptions');
		//--$this->objIpToCountry=& $this->getObject('iptocountry', 'iptocountry');
        //Get the activity logger class
        //$this->objLog=$this->newObject('logactivity', 'logger');
        //Set it to log once per session
        //$this->objLog->logOncePerSession = TRUE;
        //Log this module call
        //$this->objLog->log();
    }
    
    /**
    * The dispatch funtion
    * @param string $action The action
    * @return string The content template file
    */
    function dispatch($action=Null)
    {
        $temporary = true;
        $this->setVar('temporary',$temporary);
        // 1. ignore action at moment as we only do one thing - say hello
        // 2. load the data object (calls the magical getObject which finds the
        //    appropriate file, includes it, and either instantiates the object,
        //    or returns the existing instance if there is one. In this case we
        //    are not actually getting a data object, just a helper to the 
        //    controller.
        // 3. Pass variables to the template
        $this->setVarByRef('objUser', $this->objUser);
        //$this->setVarByRef('objHelp', $this->objHelp);
		$this->setVarByRef('objLanguage', $this->objLanguage);
        // return the name of the template to use  because it is a page content template
        // the file must live in the templates/content subdir of the module directory
		// View all received messages
		if ($action == "view") {
			$entries = $this->objDbEntries->listAll($this->objUser->userId());
			// Mark the messages as read
			foreach ($entries as $entry) {
				$this->objDbEntries->updateSingle($entry['id']);
			}
			$this->setVarByRef('entries', $entries);
			// Output
			//$this->setPageTemplate("View_page_tpl.php");
			$this->setVar('pageSuppressXML', TRUE);
			$this->setVar('pageSimpleSkin', TRUE);
            $this->setVar('pageSuppressContainer', TRUE);
            $this->setVar('pageSuppressBanner', TRUE);
            $this->setVar('pageSuppressToolbar', TRUE);
            $this->setVar('suppressFooter', TRUE);
            $this->setVar('pageSuppressIM', TRUE);
		    return "View_tpl.php";
		}
		// Show a message
		if ($action=="showmessage") {
			// Get the messageId
			$messageId = $this->getParam('messageId', '');
			$this->setVarByRef('messageId', $messageId);
			// Get the message
		    $entries = $this->objDbEntries->ListSingle($messageId);
			$entry = $entries[0];
			// Get Sender
			$senderId = $entry['sender'];
			$this->setVarByRef('senderId', $senderId);
			if (is_null($senderId)) {
			    $sender = $this->objLanguage->languageText('mod_instantmessaging_systemnotification');
			}
			else {
				$sender = $this->objUser->fullname($senderId);
			}
			$this->setVarByRef('sender', $sender);
			// Get the text of the message
			$text = $entry['content'];
			$this->setVarByRef('text', $text);
			// Output
			//$this->setPageTemplate("NoBanner_page_tpl.php");
			$this->setVar('pageSimpleSkin', TRUE);
            $this->setVar('pageSuppressContainer', TRUE);
            $this->setVar('pageSuppressBanner', TRUE);
            $this->setVar('pageSuppressToolbar', TRUE);
            $this->setVar('suppressFooter', TRUE);
            $this->setVar('pageSuppressIM', TRUE);
			return "ShowMessage_tpl.php";
		}
		// Send a message
		if ($action=="sendmessage") {
			$closeWindow = $this->getParam('closeWindow', 'no');
			$this->setVarByRef('closeWindow', $closeWindow);
			// Message being replied to			
			$messageId = $this->getParam('messageId', '');
			// Is this a reply ?
			if ($messageId == '') {
				$reply = 'no';
			    $text = "";
			}
			else {
				$reply = 'yes';
			    $entries = $this->objDbEntries->ListSingle($messageId);
				$entry = $entries[0];
				$text = $entry['content'];
				$closeWindow = 'yes';
			}
			$this->setVarByRef('reply', $reply);
			$this->setVarByRef('text', $text);
			// Get Recipient
			$recipientId = $this->getParam('recipientId', '');
			$this->setVarByRef('recipientId', $recipientId);
            $recipientType = $this->getParam('recipientType','user');
			$this->setVarByRef('recipientType', $recipientType);
            switch($recipientType){
            	case 'user': 
        			$recipient = $this->objUser->fullname($recipientId);
            		break;
            	case 'context': 
                    $objDbContext =& $this->getObject('dbcontext','context');
        			$contextDetails = $objDbContext->getContextDetails($recipientId);
                    $recipient = $contextDetails['title'];
            		break;
                case 'workgroup':
                    $objDbWorkgroup =& $this->getObject('dbworkgroup','workgroup'); 
                    $recipient = $objDbWorkgroup->getDescription($recipientId);
                    break;
                case 'buddies':
                    $recipient = "buddies";
                    break;
            	default:
            		;
            } // switch
			$this->setVarByRef('recipient', $recipient);			
			// Output
			//$this->setPageTemplate("NoBanner_page_tpl.php");
			$this->setVar('pageSimpleSkin', TRUE);
            $this->setVar('pageSuppressContainer', TRUE);
            $this->setVar('pageSuppressBanner', TRUE);
            $this->setVar('pageSuppressToolbar', TRUE);
            $this->setVar('suppressFooter', TRUE);
            $this->setVar('pageSuppressIM', TRUE);
			return "SendMessage_tpl.php";
		}
		// Confirm the send message form submit
		if ($action=="sendmessageconfirm") {
            // Get the recipient id
			$recipientId = $this->getParam('recipientId', '');
            // Get the recipient type
			$recipientType = $this->getParam('recipientType', 'user');
			// Insert the message(s) into the database
            switch($recipientType){
            	case 'user': 
        			$this->objDbEntries->insertSingle($recipientId, $this->objUser->userId(), $_POST['text']);
            		break;
            	case 'context':               
                    $contextCode = $recipientId;
                    // Get the groupAdminModel object.
    				$groups =& $this->getObject("groupAdminModel", "groupadmin");
    
                    $dolecturers = $this->getParam('lecturers',NULL);
                    $dostudents = $this->getParam('students',NULL);
                    $doguests = $this->getParam('guests',NULL);
                    
                    if ($dolecturers) {
                        // Get a list of lecturers
        				$gid=$groups->getLeafId(array($contextCode,'Lecturers'));
        				$lecturers = $groups->getGroupUsers($gid, array('userId'));
                        foreach ($lecturers as $user) {
                            $this->objDbEntries->insertSingle($user['userid'], $this->objUser->userId(), $_POST['text']);
                        }
                    }
    
                    if ($dostudents) {
                        // Get a list of students
        				$gid=$groups->getLeafId(array($contextCode,'Students'));
        				$students = $groups->getGroupUsers($gid, array('userId'));
                        foreach ($students as $user) {
                            $this->objDbEntries->insertSingle($user['userid'], $this->objUser->userId(), $_POST['text']);
                        }
                    }

                    if ($doguests) {
                        // Get a list of guests
        				$gid=$groups->getLeafId(array($contextCode,'Guest'));
        				$guests = $groups->getGroupUsers($gid, array('userId','surname','firstName'));
                        foreach ($guests as $user) {
                            $this->objDbEntries->insertSingle($user['userid'], $this->objUser->userId(), $_POST['text']);
                        }
                    }    
            		break;
                case 'workgroup':
                    $objDbWorkgroupUsers =& $this->getObject('dbworkgroupusers','workgroup'); 
                    $members = $objDbWorkgroupUsers->listAll($recipientId);
                    foreach ($members as $user) {
                        $this->objDbEntries->insertSingle($user['userid'], $this->objUser->userId(), $_POST['text']);
                    }
                    break;
                case 'buddies':
                    $objDbBuddies =& $this->getObject('dbbuddies','buddies'); 
                    $members = $objDbBuddies->getBuddies($this->objUser->userId());
                    foreach ($members as $user) {
                        $this->objDbEntries->insertSingle($user['buddyid'], $this->objUser->userId(), $_POST['text']);
                    }
                    break;
            	default:
            		;
            } // switch
			// Must we close the window?
			$closeWindow = $this->getParam('closeWindow', 'no');
			if ($closeWindow == 'yes') {
				//$this->setPageTemplate("NoBanner_page_tpl.php");
				$this->setVar('pageSimpleSkin', TRUE);
                $this->setVar('pageSuppressContainer', TRUE);
                $this->setVar('pageSuppressBanner', TRUE);
                $this->setVar('pageSuppressToolbar', TRUE);
                $this->setVar('suppressFooter', TRUE);
                $this->setVar('pageSuppressIM', TRUE);
			    return "CloseWindow_tpl.php";
			}
			//$this->setPageTemplate("NoBanner_page_tpl.php");
			$this->setVar('pageSimpleSkin', TRUE);
            $this->setVar('pageSuppressContainer', TRUE);
            $this->setVar('pageSuppressBanner', TRUE);
            $this->setVar('pageSuppressToolbar', TRUE);
            $this->setVar('suppressFooter', TRUE);
            $this->setVar('pageSuppressIM', TRUE);
			return "SendMessageConfirm_tpl.php";
		}
        // Enable IM systemwide
		if ($action=="enable") {
		    unset($_SESSION['pageSuppressIM']);
		}
        // Disable IM systemwide
		if ($action=="disable") {
		    $_SESSION['pageSuppressIM']=1;
		}
		// Show all logged in users
		if ($action=="showusers" 
			|| $action=="enable"
			|| $action=="disable") {
			$users = $this->objDbLoggedInUsers->listAll();
			$this->setVarByRef('users', $users);
			$this->setPageTemplate("Refresh_page_tpl.php");
			$this->setVar('pageSimpleSkin', TRUE);
	        return "ShowUsers_tpl.php";
		}
		// Handle ICQ messages
		if ($action=="icq") {
			//$this->setPageTemplate("NoBanner_page_tpl.php");
            $this->setVar('pageSuppressContainer', TRUE);
            $this->setVar('pageSuppressBanner', TRUE);
            $this->setVar('pageSuppressToolbar', TRUE);
            $this->setVar('suppressFooter', TRUE);
            $this->setVar('pageSuppressIM', TRUE);
			return "ICQ_tpl.php";
		}
		// Handle sending of ICQ messages
		if ($action=="icqconfirm") {
			//$icq = new ICQPager($_POST["icq"],"joconnor@uwc.ac.za");
			//$icq->send($_POST["text"]);
			//$this->setPageTemplate("NoBanner_page_tpl.php");
            $this->setVar('pageSuppressContainer', TRUE);
            $this->setVar('pageSuppressBanner', TRUE);
            $this->setVar('pageSuppressToolbar', TRUE);
            $this->setVar('suppressFooter', TRUE);
            $this->setVar('pageSuppressIM', TRUE);
		    //return "CloseWindow_tpl.php";
			return "ICQConfirm_tpl.php";
		}
		if ($action=='options') {
			$this->setVar('notifyLogin',$this->objDbOptions->get('notifyLogin'));
			$this->setVar('notifyReceive',$this->objDbOptions->get('notifyReceive'));
			$this->setVar('pageSimpleSkin', TRUE);
            $this->setVar('pageSuppressContainer', TRUE);
            $this->setVar('pageSuppressBanner', TRUE);
            $this->setVar('pageSuppressToolbar', TRUE);
            $this->setVar('suppressFooter', TRUE);
            $this->setVar('pageSuppressIM', TRUE);
		    return "options_tpl.php";
		}
		if ($action=='optionsconfirm') {
			$this->objDbOptions->set('notifylogin',$this->getParam('notifylogin','off')=='on');
			$this->objDbOptions->set('notifyreceive',$this->getParam('notifyreceive','off')=='on');
			$this->setVar('pageSimpleSkin', TRUE);
            $this->setVar('pageSuppressContainer', TRUE);
            $this->setVar('pageSuppressBanner', TRUE);
            $this->setVar('pageSuppressToolbar', TRUE);
            $this->setVar('suppressFooter', TRUE);
            $this->setVar('pageSuppressIM', TRUE);
		    return "options_confirm_tpl.php";
		}
	    return "IM_tpl.php";
    }
}    
?>