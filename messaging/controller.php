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
    * @access private
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
    * @var object $dbMessaging: The dbmessaging database class in the messaging module
    * @access private
    */
    public $dbMessaging;

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
        $this->isAdmin = $this->objUser->inAdminGroup($this->userId, 'Site Admin');
        $this->contextCode = $this->objContext->getContextCode();
        
        // messaging objects
        $this->objDisplay = $this->getObject('display', 'messaging');
        $this->dbRooms = $this->getObject('dbrooms', 'messaging');
        $this->dbUsers = $this->getObject('dbusers', 'messaging');
        $this->dbUserlog = $this->getObject('dbuserlog', 'messaging');
        $this->dbBanned = $this->getObject('dbbannedusers', 'messaging');
        $this->dbMessaging = $this->getObject('dbmessaging', 'messaging');
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
            case 'roomlist':
                $rooms = $this->dbRooms->listRooms($this->contextCode);
                $userRooms = $this->dbUsers->listUserRooms($this->userId);
                $templateContent = $this->objDisplay->roomList($rooms, $userRooms);
                $this->setVarByRef('templateContent', $templateContent);
                $this->setVar('mode', 'normal');
                return 'template_tpl.php';
                break;
                
            case 'addroom':
                $templateContent = $this->objDisplay->addRoom();
                $this->setVarByRef('templateContent', $templateContent);
                $this->setVar('mode', 'normal');
                return 'template_tpl.php';
                break;
                
            case 'editroom':
                $roomId = $this->getParam('roomId');
                $roomData = $this->dbRooms->getRoom($roomId);
                $templateContent = $this->objDisplay->addRoom('edit', $roomData);
                $this->setVarByRef('templateContent', $templateContent);
                $this->setVar('mode', 'normal');
                return 'template_tpl.php';
                break;
                
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
                
            case 'deleteroom':
                $roomId = $this->getParam('roomId');
                $this->dbRooms->deleteRoom($roomId);
                $this->dbUsers->deleteUsers($roomId);
                $this->dbUserlog->deleteUsers($roomId);
                $this->dbBanned->deleteUsers($roomId);
                return $this->nextAction('');
                break;
                
            case 'readmore':
                $roomId = $this->getParam('room_id');
                $roomData = $this->dbRooms->getRoom($roomId);
                $templateContent = $this->objDisplay->readMore($roomData);
                $this->setVarByRef('templateContent', $templateContent);
                $this->setVar('mode', 'popup');
                return 'template_tpl.php';
                break;
                
            case 'enterroom':
                $roomId = $this->getParam('roomId');
                $this->setSession('chat_room_id', $roomId);
                $this->dbUserlog->addUser($roomId, $this->userId);
                $roomData = $this->dbRooms->getRoom($roomId);
                $counter = $this->dbMessaging->getMessageCount($roomId);
                $this->setSession('message_counter', $counter);
                $array = array(
                    'name' => $this->name,
                );
                $message = $this->objLanguage->code2Txt('mod_messaging_userenter', 'messaging', $array);
                $this->dbMessaging->addChatMessage($message);
                $templateContent = $this->objDisplay->chatRoom($roomData);
                $this->setVarByRef('templateContent', $templateContent);
                if($roomData['text_only'] != 1){
                    $this->setVar('mode', 'room');
                    $this->setVar('script', TRUE);
                }else{
                    $this->setVar('mode', 'textroom');
                }
                return 'template_tpl.php';
                break;
                
            case 'leaveroom':
                $roomId = $this->getSession('chat_room_id');
                $roomData = $this->dbRooms->getRoom($roomId);
                $array = array(
                    'name' => $this->name,
                );
                $message = $this->objLanguage->code2Txt('mod_messaging_userexit', 'messaging', $array);
                $this->dbMessaging->addChatMessage($message);
                return $this->nextAction('');
                break;
                
            case 'getusers':
                $roomId = $this->getSession('chat_room_id');
                $bannedUsers = $this->dbBanned->listUsers($roomId);
                $users = $this->dbUserlog->listUsers($roomId);
                return $this->objDisplay->getUsers($users, $bannedUsers);
                break;
                
            case 'moresmileys':
                $templateContent = $this->objDisplay->showSmileys();
                $this->setVarByRef('templateContent', $templateContent);
                $this->setVar('script', TRUE);
                $this->setVar('mode', 'popup');
                return 'template_tpl.php';
                break;
                
            case 'sendchat':
                $message = $this->getParam('chat');
                $messageId = $this->dbMessaging->addChatMessage($message);
                return '';
                break;
                
            case 'getchat':
                $roomId = $this->getSession('chat_room_id');
                $counter = $this->getSession('message_counter');
                $messages = $this->dbMessaging->getChatMessages($roomId, $counter);
                if($messages){
                    $count = count($messages);
                    $counter = $counter + $count;
                    $this->setSession('message_counter', $counter);
                    return $this->objDisplay->showChat($messages);
                }else{
                    echo '';
                }
                break;
                                                                
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