<?php
/* -------------------- messaging extends controller ----------------*/

// security check-must be included in all scripts
if(!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}
// end security check

/**
* Module class to create and reply to online surveys
* @copyright (c) 2004 KEWL.NextGen
* @version 1.0
* @package survey
* @author Kevin Cyster
*
* $Id: controller.php
*/

class messaging extends controller
{
    /**
    * @var object $objUser: The user class in the security module
    * @access public
    */
    public $objUser;

    /**
    * @var string $userId: The userId of the current user
    * @access public
    */
    public $userId;

    /**
    * @var string $name: The full name of the current user
    * @access public
    */
    public $name;

    /**
    * @var boolean $isAdmin: TRUE if the user is in the site admin group, FALSE if not
    * @access public
    */
    public $isAdmin;

    /**
    * @var object $objContext: The dbcontext class in the context module
    * @access public
    */
    public $objContext;

    /**
    * @var string $contextCode: The context code if the user is in a context
    * @access public
    */
    public $contextCode;

    /**
    * @var object $objWorkgroup: The dbworkgroup class in the workgroup module
    * @access public
    */
//    public $objWorkgroup;

    /**
    * @var object $objDisplay: The display class in the messaging module
    * @access protected
    */
    protected $objDisplay;

    /**
    * @var object $dbRooms: The dbrooms database class in the messaging module
    * @access private
    */
    private $dbRooms;

    /**
    * @var object $dbUsers: The dbusers database class in the messaging module
    * @access private
    */
    private $dbUsers;

    /**
    * @var object $dbUserlog: The dbuserlog database class in the messaging module
    * @access private
    */
    private $dbUserlog;

    /**
    * @var object $dbBanned: The dbbannedusers database class in the messaging module
    * @access private
    */
    private $dbBanned;

    /**
    * @var object $dbMessages: The dbmessages database class in the messaging module
    * @access private
    */
    public $dbMessages;

    /**
    * @var object $objDatetime: The datetime class in the utilities module
    * @access private
    */
    private $objDatetime;

    /**
    * @var object $objLanguage: The language class in the language module
    * @access private
    */
    private $objLanguage;

    /**
    * Method to construct the class
    *
    * @access public
    * @return
    */
    public function init()
    {
        // system objects
        $this->objUser = $this->getObject('user', 'security');
        $this->objContext = $this->getObject('dbcontext', 'context');
        $this->objDatetime = $this->getObject('datetime', 'utilities');
        $this->objLanguage = $this->getObject('language', 'language');
//        $this->objWorkgroup = $this->getObject('dbworkgroup', 'workgroup');

        // system variables
        $this->userId = $this->objUser->userId();
        $this->name = $this->objUser->fullname($this->userId);
        $this->isAdmin = $this->objUser->inAdminGroup($this->userId);
        $this->contextCode = $this->objContext->getContextCode();
        
        // messaging objects
        $this->objDisplay = $this->getObject('display', 'messaging');
        $this->dbRooms = $this->getObject('dbrooms', 'messaging');
        $this->dbUsers = $this->getObject('dbusers', 'messaging');
        $this->dbUserlog = $this->getObject('dbuserlog', 'messaging');
        $this->dbBanned = $this->getObject('dbbannedusers', 'messaging');
        $this->dbMessages = $this->getObject('dbmessages', 'messaging');
    }

    /**
    * This is the main method of the class
    * It calls other functions depending on the value of $action
    *
    * @access public
    * @param string $action
    * @return
    */
    public function dispatch($action)
    {
        // Now the main switch statement to pass values for $action
        switch($action){
            // display a list of chat rooms
            case 'roomlist':
                $templateContent = $this->objDisplay->roomList();
                $this->setVarByRef('templateContent', $templateContent);
                $this->setVar('mode', 'normal');
                return 'template_tpl.php';
                break;
                
            // create a chat room
            case 'addroom':
                $templateContent = $this->objDisplay->addRoom();
                $this->setVarByRef('templateContent', $templateContent);
                $this->setVar('mode', 'normal');
                return 'template_tpl.php';
                break;
                
            // edit a chat room
            case 'editroom':
                $roomId = $this->getParam('roomId');
                $templateContent = $this->objDisplay->addRoom('edit', $roomId);
                $this->setVarByRef('templateContent', $templateContent);
                $this->setVar('mode', 'normal');
                return 'template_tpl.php';
                break;
                
            // submit chat room data
            case 'submitroom':
                $button = $this->getParam('button');
                if($button == 'cancel'){
                    return $this->nextAction('');
                }else{
                    $mode = $this->getParam('mode');
                    $roomName = $this->getParam('room_name');
                    $roomDesc = $this->getParam('room_desc');
                    $textOnly = $this->getParam('text_only');
                    $disabled = $this->getParam('disabled');
                    if($mode == 'add'){
                        $roomType = $this->getParam('room_type');
                        if($roomType == 1){
                            $ownerId = $this->userId;;
                        }elseif($roomType == 2){
                            $ownerId = $this->objContext->getContextCode();
                        }else{
//                            $ownerId = $this->objWorkgroup->getWorkgroupId();                            
                        }
                        $roomData = array(
                            'room_type' => $roomType,
                            'room_name' => $roomName,
                            'room_desc' => $roomDesc,
                            'text_only' => $textOnly,
                            'disabled' => $disabled,
                            'owner_id' => $ownerId,
                        );
                        $roomId = $this->dbRooms->addRoom($roomData);
                        if($roomType == 1){
                            $chatUserId = $this->dbUsers->addUser($roomId, $this->userId);
                        }
                    }else{
                        $roomId = $this->getParam('roomId');
                        $roomData = array(
                            'room_name' => $roomName,
                            'room_desc' => $roomDesc,
                            'text_only' => $textOnly,
                            'disabled' => $disabled,
                        );
                        $this->dbRooms->editRoom($roomId, $roomData);
                    }
                }
                return $this->nextAction('');
                break;
                
            // delete a chat room
            case 'deleteroom':
                $roomId = $this->getParam('roomId');
                $this->dbRooms->deleteRoom($roomId);
                $this->dbUsers->deleteUsers($roomId);
                $this->dbUserlog->deleteUsers($roomId);
                $this->dbBanned->deleteUsers($roomId);
                return $this->nextAction('');
                break;
                
            // display the chat room description if the description is truncated
            case 'readmore':
                $roomId = $this->getParam('room_id');
                $templateContent = $this->objDisplay->readMore($roomId);
                $this->setVarByRef('templateContent', $templateContent);
                $this->setVar('mode', 'popup');
                return 'template_tpl.php';
                break;
                
            // display the chat room page when entering the chat room
            case 'enterroom':
                $roomId = $this->getParam('roomId');
                $this->setSession('chat_room_id', $roomId);
                $this->dbUserlog->addUser($roomId, $this->userId);
                $roomData = $this->dbRooms->getRoom($roomId);
                $counter = $this->dbMessages->getMessageCount($roomId);
                $this->setSession('message_counter', $counter);
                $array = array(
                    'name' => $this->name,
                );
                $message = $this->objLanguage->code2Txt('mod_messaging_userenter', 'messaging', $array);
                $this->dbMessages->addChatMessage($message, TRUE);
                $templateContent = $this->objDisplay->chatRoom($roomData);
                $this->setVarByRef('templateContent', $templateContent);
                if($roomData['text_only'] != 1){
                    $this->setVar('mode', 'room');
                }else{
                    $this->setVar('mode', 'textroom');
                }
                $this->setVar('scriptaculous', TRUE);
                return 'template_tpl.php';
                break;
                
            // exit the chat room page and display the chat room list
            case 'leaveroom':
                $roomId = $this->getSession('chat_room_id');
                $roomData = $this->dbRooms->getRoom($roomId);
                $array = array(
                    'name' => $this->name,
                );
                $message = $this->objLanguage->code2Txt('mod_messaging_userexit', 'messaging', $array);
                $this->dbMessages->addChatMessage($message, TRUE);
                $this->unsetSession('chat_room_id');
                return $this->nextAction('');
                break;
                
            // display a list of users in the chat room
            case 'getusers':
                return $this->objDisplay->getUsers();
                break;
                
            // display the popup page to ban users
            case 'banpopup':
                $templateContent = $this->objDisplay->banPopup();
                $this->setVarByRef('templateContent', $templateContent);
                $this->setVar('scriptaculous', TRUE);   
                $this->setVar('mode', 'popup');
                return 'template_tpl.php';
                break;
                
            // display a list of users meeting the name criteria
            case 'listusers':
                $option = $this->getParam('option');
                $value = $this->getparam('username');
                return $this->objDisplay->listUsers($option, $value);
                break;

            // submit the banned user data
            case 'banuser':
                $roomId = $this->getSession('chat_room_id');
                $userId = $this->getParam('userId');
                $banType = $this->getParam('type');
                $banLength = $this->getParam('length');
                $banData = array(
                    'room_id' => $roomId,
                    'user_id' => $userId,
                    'ban_type' => $banType,
                    'ban_length' => $banLength,
                );
                $this->dbBanned->addUser($banData);
                if($banType == 1){
                    $array = array(
                        'mod' => $this->name,
                        'user' => $this->objUser->fullname($userId),                    
                    );
                    $message = $this->objLanguage->code2Txt('mod_messaging_banindefmsg', 'messaging', $array);
                }else{
                    $array = array(
                        'mod' => $this->name,
                        'user' => $this->objUser->fullname($userId),
                        'time' => $banLength,                    
                    );
                    $message = $this->objLanguage->code2Txt('mod_messaging_bantempmsg', 'messaging', $array);                    
                }
                $this->dbMessages->addChatMessage($message, TRUE);
                return $this->nextAction('confirmban', array(
                    'banType' => $banType,
                    'userId' => $userId,
                ));
                break;
                
            // display the popup page to confirm the banning of a user
            case 'confirmban':
                $userId = $this->getParam('userId');
                $banType = $this->getParam('banType');
                $templateContent = $this->objDisplay->confirmBan($banType, $userId);
                $this->setVarByRef('templateContent', $templateContent);
                $this->setVar('mode', 'popup');
                return 'template_tpl.php';
                break;
                
            // display a message to the user if banned
            case 'getbanmsg':
                $userId = $this->getParam('userId');
                return $this->objDisplay->getBanMsg($userId);
                break;

            // display the popup page to unban users
            case 'unbanpopup':
                $templateContent = $this->objDisplay->unbanPopup();
                $this->setVarByRef('templateContent', $templateContent);
                $this->setVar('mode', 'popup');
                return 'template_tpl.php';
                break;
                
            // submit the unbanned user data
            case 'unbanusers':
                $roomId = $this->getSession('chat_room_id');
                $userList = $this->getParam('userId');
                foreach($userList as $user){
                    $bannedUser = $this->dbBanned->isBanned($user, $roomId);
                    $bannedId = $bannedUser['id'];
                    $this->dbBanned->deleteUser($bannedId);
                    $array = array(
                        'mod' => $this->name,
                        'user' => $this->objUser->fullname($user),
                    );
                    $message = $this->objLanguage->code2Txt('mod_messaging_unbanindefmsg', 'messaging', $array);
                    $this->dbMessages->addChatMessage($message, TRUE);
                }
                $users = implode('|', $userList);
                return $this->nextAction('confirmunban', array(
                    'users' => $users,
                ));
                break;

            // display the popup page to confirm the unbanning of a user
            case 'confirmunban':
                $users = $this->getParam('users');
                $templateContent = $this->objDisplay->confirmUnban($users);
                $this->setVarByRef('templateContent', $templateContent);
                $this->setVar('mode', 'popup');
                return 'template_tpl.php';
                break;
                
            // display the popup page to show more smiley icons
            case 'moresmileys':
                $templateContent = $this->objDisplay->moreSmileys();
                $this->setVarByRef('templateContent', $templateContent);
                $this->setVar('mode', 'popup');
                return 'template_tpl.php';
                break;
                
            // submit the chat message data
            case 'sendchat':
                $message = $this->getParam('chat');
                $messageId = $this->dbMessages->addChatMessage($message);
                return $messageId;
                break;
                
            // get the chat messages posted to a chat room
            case 'getchat':
                return $this->objDisplay->getChat();
                break;
                                                                
            // display the chat room list as default
            default:
                $this->unsetSession('chat_room_id');
                $this->unsetSession('message_counter');
                $this->dbUserlog->deleteUser($this->userId);
                return $this->nextAction('roomlist', array(), 'messaging');
                break;
        }
    }
}
?>