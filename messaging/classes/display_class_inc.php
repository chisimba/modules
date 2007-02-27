<?php
/* ----------- templates class extends object ----------*/

// security check - must be included in all scripts
if(!$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}

/**
* Templates class for messaging module
* @author Kevin Cyster
*/

class display extends object
{
    /**
    * @var object $objIcon: The geticon class of the htmlelements module
    * @access private
    */
    private $objIcon;
     
    /**
    * @var object $objFeaturebox: The featurebox class in the navigation module
    * @access private
    */
    private $objFeaturebox;

    /**
    * @var object $objLanguage: The language class of the language module
    * @access private
    */
    private $objLanguage;
     
    /**
    * @var object $objUser: The user class of the security module
    * @access private
    */
    private $objUser;
     
    /**
    * @var object $objDatetime: The datetime class of the utilities module
    * @access private
    */
    private $objDatetime;
     
    /**
    * @var object $objContext: The dbcontexr class in the context module
    * @access private
    */
    private $objContext;

    /**
    * @var object $objWorkgroup: The dbworkgroup class in the workgroup module
    * @access public
    */
//    public $objWorkgroup;

    /**
    * @var string $userId: The user id of the current logged in user
    * @access private
    */
    private $userId;

    /**
    * @var boolean $isLecturer: TRUE if the user is a lecturer in the current context
    * @access private
    */
    private $isLecturer;

    /**
    * @var string $contextCode: The context code if the user is in a context
    * @access public
    */
    public $contextCode;

    /**
    * @var object $dbRooms: The dbrooms class in the messaging module
    * @access private
    */
    private $dbRooms;

    /**
    * @var object $dbUsers: The dbusers class in the messaging module
    * @access private
    */
    private $dbUsers;

    /**
    * @var object $dbUserlog: The dbuserlog class in the messaging module
    * @access private
    */
    private $dbUserlog;

    /**
    * @var object $dbMessaging: The dbmessaging class in the messaging module
    * @access private
    */
    private $dbMessaging;

    /**
    * @var object $dbBanned: The dbbannedusers class in the messaging module
    * @access private
    */
    private $dbBanned;

    /**
    * Method to construct the class
    *
    * @access public
    * @return
    */
    public function init()
    {   
        // load html element classes
        $this->loadClass('htmlheading', 'htmlelements');
        $this->loadClass('htmltable', 'htmlelements');
        $this->loadClass('link', 'htmlelements');
        $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('textarea', 'htmlelements');
        $this->loadClass('radio', 'htmlelements');
        $this->loadClass('dropdown', 'htmlelements');
        $this->loadClass('form', 'htmlelements');
        $this->loadClass('button', 'htmlelements');
        $this->loadClass('windowpop','htmlelements');
        $this->loadClass('layer','htmlelements');
        $this->loadClass('tabbedbox', 'htmlelements');
        $this->objIcon = $this->newObject('geticon', 'htmlelements');
        $this->objFeaturebox = $this->getObject('featurebox', 'navigation');

        // syatem classes
        $this->objLanguage = $this->getObject('language','language');
        $this->objUser = $this->getObject('user', 'security');
        $this->objDatetime = $this->getObject('datetime', 'utilities');
        $this->objContext = $this->getObject('dbcontext', 'context');
        $this->userId = $this->objUser->userId(); 
        $this->isLecturer = $this->objUser->isContextLecturer();  
        $this->contextCode = $this->objContext->getContextCode();
        
        // messaging classes     
        $this->dbRooms = $this->getObject('dbrooms', 'messaging');
        $this->dbUsers = $this->getObject('dbusers', 'messaging');
        $this->dbUserlog = $this->getObject('dbuserlog', 'messaging');
        $this->dbMessaging = $this->getObject('dbmessaging', 'messaging');
        $this->dbBanned = $this->getObject('dbbannedusers', 'messaging');
    }

    /**
    * Method to create the chat room list template
    *
    * @access public
    * @return string $str The template output string
    **/
    public function roomList()
    {
        // language elements
        $chatHeading = $this->objLanguage->languageText('mod_messaging_chatheading', 'messaging');
        $roomHeading  = $this->objLanguage->languageText('mod_messaging_chatrooms', 'messaging');
        $nameLabel = $this->objLanguage->languageText('mod_messaging_wordname', 'messaging');
        $descLabel = $this->objLanguage->languageText('mod_messaging_worddesscription', 'messaging');
        $ownerLabel = $this->objLanguage->languageText('mod_messaging_wordowner', 'messaging');
        $createdLabel = $this->objLanguage->languageText('mod_messaging_wordcreated', 'messaging');
        $usersLabel = $this->objLanguage->languageText('mod_messaging_activeusers', 'messaging');
        $noRecordsLabel = $this->objLanguage->languageText('mod_messaging_norecords', 'messaging');
        $systemLabel = $this->objLanguage->languageText('mod_messaging_wordsystem', 'messaging');
        $addLabel = $this->objLanguage->languageText('mod_messaging_addroom', 'messaging');
        $editLabel = $this->objLanguage->languageText('mod_messaging_editroom', 'messaging');
        $deleteLabel = $this->objLanguage->languageText('mod_messaging_deleteroom', 'messaging');
        $exitLabel = $this->objLanguage->languageText('mod_messaging_exit', 'messaging');
        $addTitleLabel = $this->objLanguage->languageText('mod_messaging_addtitle', 'messaging');
        $editTitleLabel = $this->objLanguage->languageText('mod_messaging_edittitle', 'messaging');
        $deleteTitleLabel = $this->objLanguage->languageText('mod_messaging_deletetitle', 'messaging');
        $exitTitleLabel = $this->objLanguage->languageText('mod_messaging_exittitle', 'messaging');
        $moreLabel = $this->objLanguage->languageText('mod_messaging_wordmore', 'messaging');
        $moreTitleLabel = $this->objLanguage->languageText('mod_messaging_moretitle', 'messaging');
        $confirmLabel = $this->objLanguage->languageText('mod_messaging_confirm', 'messaging');
        $disabledLabel = $this->objLanguage->languageText('mod_messaging_worddisabled', 'messaging');
        
        // get data
        $rooms = $this->dbRooms->listRooms($this->contextCode);
        $userRooms = $this->dbUsers->listUserRooms($this->userId);
                
        // headings
        $objHeader = new htmlHeading();
        $objHeader->str = $chatHeading;
        $objHeader->type = 1;
        $header = $objHeader->show();        
        $str = $header;
        
        $objHeader = new htmlHeading();
        $objHeader->str = $roomHeading;
        $objHeader->type = 3;
        $header = $objHeader->show();
        $str .= $header;
        
        $objLink = new link($this->uri(array(
            'action' => 'addroom'
        ), 'messaging'));
        $objLink->link = $addLabel;
        $objLink->title = $addTitleLabel;
        $addLink = $objLink->show(); 
        $str .= $addLink;
        
        $objTable = new htmlTable();
        $objTable->cellpadding = '2';
        $objTable->cellspacing = '2';
        $objTable->startHeaderRow();
        $objTable->addHeaderCell('<b>'.$nameLabel.'</b>', '', '', '', '', '');
        $objTable->addHeaderCell('<b>'.$descLabel.'</b>', '', '', '', '', '');
        $objTable->addHeaderCell('<b>'.$ownerLabel.'</b>', '', '', '', '', '');
        $objTable->addHeaderCell('<b>'.$createdLabel.'</b>', '', '', '', '', '');
        $objTable->addHeaderCell('<b>'.$usersLabel.'</b>', '', '', '', '', '');
        $objTable->addHeaderCell('', '', '', '', '', '');
        $objTable->endHeaderRow();
        
        if($rooms != FALSE){            
            $roomList = array();
            if($userRooms == FALSE){
                foreach($rooms as $key => $room){
                    if($room['id'] == 'init_1'){
                        $roomList[] = $rooms[$key];
                    }elseif($room['room_type'] == 2){
                        $roomList[] = $rooms[$key];
                    }
                }
            }else{
                foreach($rooms as $key => $room){
                    if($room['id'] == 'init_1'){
                        $roomList[] = $rooms[$key];
                    }elseif($room['room_type'] == 2){
                        $roomList[] = $rooms[$key];
                    }else{
                        foreach($userRooms as $userRoom){
                            if($userRoom['room_id'] == $room['id']){
                                $roomList[] = $rooms[$key];
                            }
                        }
                    }
                }
            }
        }else{
            $roomList = FALSE;
        }
        
        if($roomList == FALSE){
            $objTable->startRow();
            $objTable->addCell($noRecordsLabel, '', '', '', 'noRecordsMessage', 'colspan="6"');
            $objTable->endRow();       
        }else{
            $i = 0;
            foreach($roomList as $room){
                $class = (($i++%2) == 0) ? 'even' : 'odd';
                
                $roomName = $room['room_name'];
                $array = array(
                    'room' => $roomName
                );
                $name = $this->objLanguage->code2Txt('mod_messaging_enter', 'messaging', $array);
                $objLink = new link($this->uri(array(
                    'action' => 'enterroom',
                    'roomId' => $room['id'],
                )));
                $objLink->title = $name;
                $objLink->link = $roomName;
                $nameLink = $objLink->show();
                
                if($room['disabled'] == 1){
                    $nameLink = '<strong>'.$roomName.'</strong><br />('.$disabledLabel.')';   
                }
                
                $roomDesc = $room['room_desc'];
                if(strlen($roomDesc) > 255){
                    $objPopup = new windowpop();
                    $objPopup->title = $moreTitleLabel;
                    $objPopup->set('location',$this->uri(array(
                        'action' => 'readmore',
                        'room_id' => $room['id']
                    )));
                    $objPopup->set('linktext', '[...'.$moreLabel.'...]');
                    $objPopup->set('width', '600');
                    $objPopup->set('height', '500');
                    $objPopup->set('left', '100');
                    $objPopup->set('top', '100');
                    $objPopup->set('scrollbars', 'yes');
                    $objPopup->putJs(); // you only need to do this once per page
                    $morePopup = $objPopup->show();
                    
                    $roomDesc = substr($roomDesc, 0, 255).'&nbsp;&nbsp;'.$morePopup;
                }
                $roomType = $room['room_type'];
                $ownerId = $room['owner_id'];
                $date = $this->objDatetime->formatDateOnly($room['date_created']);
                
                if($roomType == 0){
                    $owner = $systemLabel;
                    $date = '';    
                }elseif($roomType == 1){
                    $owner = $this->objUser->fullname($ownerId);
                }elseif($roomType == 2){
                    $owner = $this->objContext->getField('title', $room['owner_id']);
                }else{
                    $owner = 'to do - workgroup name';
                }
                       
                $objLink = new link($this->uri(array(
                    'action' => 'editroom',
                    'roomId' => $room['id'],
                ), 'messaging'));
                $objLink->link = $editLabel;
                $objLink->title = $editTitleLabel;
                $editLink = '<nobr>'.$objLink->show().'</nobr>';

                $objLink = new link($this->uri(array(
                    'action' => 'deleteroom',
                    'roomId' => $room['id'],
                ), 'messaging'));
                $objLink->link = $deleteLabel;
                $objLink->title = $deleteTitleLabel;
                $objLink->extra = ' onclick="javascript:
                    return confirm(\''.$confirmLabel.'\');"';
                $deleteLink = '<nobr>'.$objLink->show().'</nobr>';
                
                $activeUsers = $this->dbUserlog->listUsers($room['id']);
                $userCount = $activeUsers ? count($activeUsers) : 0;
                
                if($roomType == 1 && $ownerId == $this->userId){
                    if($userCount == 0){
                        $links = $editLink.'<br />'.$deleteLink;
                    }else{
                        $links = $editLink;
                    }
                }elseif($roomType == 2 && $this->isLecturer){
                    if($userCount == 0){
                        $links = $editLink.'<br />'.$deleteLink;
                    }else{
                        $links = $editLink;
                    }
                }else{
                    $links = '';
                }

                $this->objIcon = $objTable->startRow();
                $objTable->addCell($nameLink, '15%', '', '', $class, '');
                $objTable->addCell($roomDesc, '', '', '', $class, '');
                $objTable->addCell($owner, '15%', '', '', $class, '');
                $objTable->addCell('<nobr>'.$date.'</nobr>', '15%', '', 'center', $class, '');
                $objTable->addCell($userCount, '5%', '', 'center', $class, '');
                $objTable->addCell($links, '10%', '', '', $class, '');
                $objTable->endRow(); 
            } 
        }     
        $listTable = $objTable->show();        
        $str .= $listTable;

        $objLink = new link($this->uri(array(), '_default'));
        $objLink->link = $exitLabel;
        $objLink->title = $exitTitleLabel;
        $exitLink = $objLink->show(); 
        $str .= '<br />'.$exitLink;
        
        return $str;
    }

    /**
    * Method to create the add and edit chat room template
    *
    * @access public
    * @param string $mode The mode of the template 'add' or 'edit'
    * @param array $roomId The id of the chat room to edit
    * @return string $str The template output string
    */
    public function addRoom($mode = 'add', $roomId = NULL)
    {
        // language items
        $addLabel = $this->objLanguage->languageText('mod_messaging_addroom', 'messaging');
        $editLabel = $this->objLanguage->languageText('mod_messaging_editroom', 'messaging');
        $nameLabel = $this->objLanguage->languageText('mod_messaging_wordname', 'messaging');
        $descLabel = $this->objLanguage->languageText('mod_messaging_worddesscription', 'messaging');
        $entitiesLabel = $this->objLanguage->languageText('mod_messaging_entities', 'messaging');
        $textLabel = $this->objLanguage->languageText('mod_messaging_textonly', 'messaging');
        $submitLabel = $this->objLanguage->languageText('mod_messaging_wordsubmit', 'messaging');
        $cancelLabel = $this->objLanguage->languageText('mod_messaging_wordcancel', 'messaging');
        $errNameLabel = $this->objLanguage->languageText('mod_messaging_errname', 'messaging');
        $errDescLabel = $this->objLanguage->languageText('mod_messaging_errdesc', 'messaging');
        $inputLabel = $this->objLanguage->languageText('mod_messaging_inputsetting', 'messaging');
        $typeLabel = $this->objLanguage->languageText('mod_messaging_wordtype', 'messaging');
        $contextLabel = $this->objLanguage->code2Txt('mod_messaging_contextroom', 'messaging');
        $workgroupLabel = $this->objLanguage->code2Txt('mod_messaging_workgrouproom', 'messaging');
        $privateLabel = $this->objLanguage->languageText('mod_messaging_privateroom', 'messaging');
        $statusLabel = $this->objLanguage->code2Txt('mod_messaging_wordstatus', 'messaging');
        $enabledLabel = $this->objLanguage->languageText('mod_messaging_wordenabled', 'messaging');
        $disabledLabel = $this->objLanguage->languageText('mod_messaging_worddisabled', 'messaging');
        
        //heading
        $objHeader = new htmlHeading();
        if($mode == 'add'){
            $objHeader->str = $addLabel;
        }else{
            $objHeader->str = $editLabel;
        }
        $objHeader->type = 1;
        $header = $objHeader->show();        
        $str = $header;
        
        // get data 
        if($mode == 'add'){
            $roomId = '';
            $roomType = 1;
            $roomName = '';
            $roomDesc = '';
            $textOnly = 0;
            $disabled = 0;
        }else{
            $roomData = $this->dbRooms->getRoom($roomId);
            $roomType = $roomData['room_type'];
            $roomName = $roomData['room_name'];
            $roomDesc = $roomData['room_desc'];
            $textOnly = $roomData['text_only'];
            $disabled = $roomData['disabled'];
        }
        
        $objInput = new textinput('roomId', $roomId, 'hidden');
        $roomIdInput = $objInput->show();
        
        $objRadio = new radio('room_type');
        $objRadio->addOption(1, '&nbsp;'.$privateLabel);
        if($this->isLecturer){
            $objRadio->addOption(2, '&nbsp;'.ucfirst($contextLabel));
        }
//        $objRadio->addOption(3, ucfirst($workgroupLabel));
        $objRadio->setSelected($roomType);
        $objRadio->setBreakSpace('<br />');
        $typeRadio = $objRadio->show();
        
        $objInput = new textinput('room_name', $roomName);
        $nameInput = $objInput->show();
        
        $objText = new textarea('room_desc', $roomDesc);
        $descText = $objText->show();

        $objRadio = new radio('text_only');
        $objRadio->addOption(0, '&nbsp;'.$entitiesLabel);
        $objRadio->addOption(1, '&nbsp;'.$textLabel);
        $objRadio->setSelected($textOnly);
        $objRadio->setBreakSpace('<br />');
        $displayRadio = $objRadio->show();
        
        $objRadio = new radio('disabled');
        $objRadio->addOption(0, '&nbsp;'.$enabledLabel);
        $objRadio->addOption(1, '&nbsp;'.$disabledLabel);
        $objRadio->setSelected($disabled);
        $objRadio->setBreakSpace('<br />');
        $statusRadio = $objRadio->show();
        
        $objButton = new button('submitbutton', $submitLabel);
        $objButton->setToSubmit();
        $objButton->extra = ' onclick="javascript:
            var form = document.getElementById(\'form_submitform\');
            var element = document.createElement(\'input\');

            element.setAttribute(\'id\', \'input_button\');
            element.setAttribute(\'name\', \'button\');
            element.setAttribute(\'value\', \'submit\');
            element.setAttribute(\'type\', \'hidden\');
            
            form.appendChild(element);
            //form.submit();"';
        $submitButton = $objButton->show();

        $objButton = new button('cancelbutton', $cancelLabel);
        $objButton->extra = ' onclick="javascript:
            var form = document.getElementById(\'form_cancelform\');
            var element = document.createElement(\'input\');

            element.setAttribute(\'id\', \'input_button\');
            element.setAttribute(\'name\', \'button\');
            element.setAttribute(\'value\', \'cancel\');
            element.setAttribute(\'type\', \'hidden\');
            
            form.appendChild(element);
            form.submit();"';
        $cancelButton = $objButton->show();

        $typeBox = $this->objFeaturebox->show($typeLabel, $typeRadio);
        $nameBox = $this->objFeaturebox->show($nameLabel, $nameInput);
        $descBox = $this->objFeaturebox->show($descLabel, $descText);
        $inputBox = $this->objFeaturebox->show($inputLabel, $displayRadio);
        $statusBox = $this->objFeaturebox->show($statusLabel, $statusRadio);
        
        $objForm = new form('submitform', $this->uri(array(
            'action' => 'submitroom',
            'mode' => $mode,
        ), 'messaging'));
        $objForm->addToForm($roomIdInput);
        if($mode == 'add'){
            $objForm->addToForm($typeBox);
        }
        $objForm->addToForm($nameBox);
        $objForm->addToForm($descBox);
        $objForm->addToForm($inputBox);
        $objForm->addToForm($statusBox);
        $objForm->addToForm($submitButton.'&nbsp;'.$cancelButton);
        $objForm->addRule('room_name', $errNameLabel, 'required');
        $objForm->addRule('room_desc', $errDescLabel, 'required');
        $submitForm = $objForm->show();
        $str .= $submitForm;
        
        $objForm = new form('cancelform', $this->uri(array('
            action' => 'submitroom'
        ), 'messaging'));
        $cancelForm = $objForm->show();
        $str .= $cancelForm;
        
        return $str;        
    }
    
    /**
    * Method to create the read more template
    *
    * @access public
    * @param array $roomId The id of the chat room to display
    * @return string $str The template output string
    */
    public function readMore($roomId)
    {
        // language items
        $nameLabel = $this->objLanguage->languageText('mod_messaging_wordname', 'messaging');
        $descLabel = $this->objLanguage->languageText('mod_messaging_worddesscription', 'messaging');
        $closeLabel = $this->objLanguage->languageText('mod_messaging_wordclose', 'messaging');
        $closeTitleLabel = $this->objLanguage->languageText('mod_messaging_closetitle', 'messaging');
        
        // get data
        $roomData = $this->dbRooms->getRoom($roomId);
        
        $string = $this->objFeaturebox->show($nameLabel, $roomData['room_name']);
        $string .= $this->objFeaturebox->show($descLabel, $roomData['room_desc']);
        
        $objLink = new link('javascript:
            this.close();');
        $objLink->link = $closeLabel;
        $objLink->title = $closeTitleLabel;
        $closeLink = $objLink->show();

        $objTable = new htmltable();
        $objTable->cellspacing = '2';
        $objTable->cellpadding = '2';
        $objTable->startRow();
        $objTable->addCell($closeLink, '', '', 'center', '', '');
        $objTable->endRow();
        $string .= $objTable->show();
        
        $objLayer = new layer();
        $objLayer->padding = '10px';
        $objLayer->addToStr($string);
        $str = $objLayer->show();
        
        return $str;
    }
    
    /**
    * Method to create the chat room template
    *
    * @access public
    * @param array $roomData An array containing the chat room data
    * @return string $str The template output string
    */
    public function chatRoom($roomData)
    {
        // javascript
        $script = '<script type="text/javaScript">
            Event.observe(window, "load", init_chat, false);
    
            function init_chat(){
                Event.observe("input_send", "click", send_chat, false);
                get_chat();
            }
            
            function get_chat(){
                var url = "index.php";
                var target = "chatroom";
                var pars = "module=messaging&action=getchat";
                var myAjax = new Ajax.Updater(target, url, {method: "get", parameters: pars, onComplete: chat_timer});
            }

            function chat_timer(){
                setTimeout("get_chat()", 1000);
            }
            
            function send_chat(){
                var msg = document.getElementById("input_message");
                
                if(msg.value != ""){
                    var url = "index.php";
                    var target = "input_message";
                    var pars = "module=messaging&action=sendchat&chat=" + msg.value;
                    var myAjax = new Ajax.Updater(target, url, {method: "get", parameters: pars});                
                    msg.value = "";
                }                
                msg.focus();
            }
        </script>';
        $str = $script;
        
        // language items
        $array = array(
            'room' => $roomData['room_name'],
        );
        $heading = $this->objLanguage->code2Txt('mod_messaging_room', 'messaging', $array);
        $sendLabel = $this->objLanguage->languageText('mod_messaging_wordsend', 'messaging');
        $clearLabel = $this->objLanguage->languageText('mod_messaging_wordclear', 'messaging');
        
        // heading
        $objHeader = new htmlHeading();
        $objHeader->str = ucfirst($heading);
        $objHeader->type = 1;
        $header = $objHeader->show();        
        $str .= $header;
        
        $objLayer = new layer();
        $objLayer->id = 'chatroom';
        $objLayer->height = '300px';
        $objLayer->border = '1px solid black';
        $objLayer->overflow = 'auto';
        $chatLayer = $objLayer->show();
        $str .= $chatLayer;
        
        $objText = new textarea('message');
        $chatText = $objText->show();
        
        $objButton = new button('send', $sendLabel);
        $sendButton = $objButton->show();
        
        $objButton = new button('clear', $clearLabel);
        $objButton->extra = 'onclick="javascript: 
            var element = document.getElementById(\'input_message\');
            element.value = \'\';
            element.focus();
        "';
        $clearButton = $objButton->show();
        
        $objTable = new htmltable();
        $objTable->cellspacing = '2';
        $objTable->cellpadding = '2';
        $objTable->startRow();
        $objTable->addCell($chatText, '', '', '', '' ,'');
        $objTable->addCell($sendButton.'<br />'.$clearButton, '', 'center', '', '' ,'');
        $objTable->endRow();
        $chatTable = $objTable->show();
        $string = $chatTable;
        
        $objForm = new form('chat', $this->uri(array(
            'action' => 'sendchat'
        )));
        $objForm->addToForm($string);
        $chatForm = $objForm->show();
        $str .= $chatForm;
        
        return $str;
    }
    
    /**
    * Method to create the active users list
    * 
    * @access public
    * @param boolean $isModerator TRUE if the current user is a moderator FALSE if not
    * @return string $str The template output string
    */
    public function getUsers($isModerator)
    {
        // language items
        $activeLabel = $this->objLanguage->languageText('mod_messaging_active', 'messaging');
        $banLabel = $this->objLanguage->languageText('mod_messaging_ban', 'messaging');
        $unbanLabel = $this->objLanguage->languageText('mod_messaging_unban', 'messaging');
        $bannedLabel = $this->objLanguage->languageText('mod_messaging_banned', 'messaging');
        $indefLabel = $this->objLanguage->languageText('mod_messaging_banindef', 'messaging');
        
        // get data
        $roomId = $this->getSession('chat_room_id');
        $roomData = $this->dbRooms->getRoom($roomId);
        $users = $this->dbUserlog->listUsers($roomId);
        $bannedUsers = $this->dbBanned->listUsers($roomId);

        $objTable = new htmltable();
        $objTable->cellspacing = '2';
        $objTable->cellpadding = '2';
        foreach($users as $user){
            $objTable->startRow();
            $objTable->addCell($this->objUser->fullname($user['user_id']), '', '', '', '', '');
            $isBanned = FALSE;
            $tempBan = FALSE;
            if($bannedUsers != FALSE){
                foreach($bannedUsers as $bannedUser){
                    if($user['user_id'] == $bannedUser['user_id']){
                        $isBanned = TRUE;
                        if($bannedUser['ban_type'] != 1){
                            $tempBan = TRUE;
                        }   
                    }
                }
            }
            if($isBanned){
                if($tempBan){
                    $date = $this->objDatetime->formatDate($bannedUser['ban_stop']);
                    $array = array(
                        'date' => $date,
                    );
                    $bannedLabel = $this->objLanguage->code2Txt(
                    'mod_messaging_bantemp', 'messaging', $array);

                    $this->objIcon->setIcon('failed');
                    $this->objIcon->title = $bannedLabel;
                    $this->objIcon->extra = ' onclick="javascript:
                        alert()"';
                    $icon = $this->objIcon->show();
               }else{
                    if($isModerator == 1){
                        $this->objIcon->title = $unbanLabel;
                        $this->objIcon->extra = '';
                        $icon = $this->objIcon->getLinkedIcon($this->uri(array('action' => 'unban')),'failed');
                    }else{
                        $this->objIcon->title = $indefLabel;
                        $this->objIcon->setIcon('failed');
                        $this->objIcon->extra = '';
                        $icon = $this->objIcon->show();                        
                    }
                }
            }else{
                if($isModerator == 1 && $user['user_id'] != $this->userId){
                    $this->objIcon->title = $banLabel;
                    $this->objIcon->setIcon('ok');
                    $this->objIcon->extra = '';
                    $okIcon = $this->objIcon->show();

                    $objPopup = new windowpop();
                    $objPopup->title = $banLabel;
                    $objPopup->set('location',$this->uri(array(
                        'action' => 'banpopup',
                        'userId' => $user['user_id'],
                    )));
                    $objPopup->set('linktext', $okIcon);
                    $objPopup->set('width', '500');
                    $objPopup->set('height', '350');
                    $objPopup->set('left', '100');
                    $objPopup->set('top', '100');
                    $objPopup->set('scrollbars', 'no');
                    $objPopup->putJs(); // you only need to do this once per page
                    $icon = $objPopup->show();
                }else{
                    $this->objIcon->title = $activeLabel;
                    $this->objIcon->setIcon('ok');
                    $this->objIcon->extra = '';
                    $icon = $this->objIcon->show();                    
                }
            }
            $objTable->addCell($icon, '', '', '', '', '');
            $objTable->endRow();
        }
        $str = $objTable->show();
        echo $str;
    }
    
    /**
    * Method to create the show more smileys template
    *
    * @access public
    * @return string $str The template output string
    */
    public function moreSmileys()
    {
        $script = '<script type="text/javaScript">
            var namelist = new Array("alien", "angel", "angry", "anticipation", "approve", "big_grin", "big_smile", "blush", "boom", "clown", "confused", "cool", "crying", "dead", "evil", "evil_eye", "exclamation", "flower", "geek", "kiss", "martian", "moon", "ogle", "ouch", "peace", "question", "rainbow", "raise_eyebrows", "raspberry", "roll_eyes", "sad", "shy", "sleepy", "smile", "stern", "surprise", "thoughtful", "thumbs_down", "thumbs_up", "unsure", "up_yours", "very_angry", "wink", "worried");
            
            var codelist = new Array("[A51]", "[0:-)]", "[>:-(]", "[8-)]", "[^-)]", "[:-D]", "[:-]]", "[:-I]", "[boom]", "[:o)]", "[<:-/]", "[B-)]", "[!-(]", "[xx-P]", "[}:-)]", "[};-)]", "[!]", "[F]", "[G]", "[:-x]", "[M]", "[moon]", "[8-P]", "[P-(]", "[V]", "[?]", "[((]", "[E-)]", "[:-P]", "[8-]]", "[:-(]", "[8-/]", "[zzz]", "[:-)]", "[>:-.]", "[8-o]", "[>8-/]", "[-]", "[+]", "[:-/]", "[@#$%]", "[>:-0]", "[;-)]", "[8-(]");
            
            function addSmiley(elementId)
            {
                var msg = opener.document.getElementById("input_message");
                for(i = 0; i <= namelist.length-1; i++){
                        if(namelist[i] == elementId){
                        if(msg.value == ""){
                            msg.value = codelist[i];
                        }else{
                            msg.value = msg.value + " " + codelist[i];
                        }
                    }
                }
                msg.focus();
            }
        </script>';
        $content = $script;

        $title = $this->objLanguage->languageText('mod_message_wordsmileys', 'messaging');
        $smileyLabel = $this->objLanguage->languageText('mod_message_smileys', 'messaging');  
        $closeLabel = $this->objLanguage->languageText('mod_messaging_wordclose', 'messaging');
        $closeTitleLabel = $this->objLanguage->languageText('mod_messaging_closetitle', 'messaging');

        $list = array(
            'alien' => '[ A51 ]',
            'angel' => '[ 0:-) ]',
            'angry' => '[ >:-( ]',
            'anticipation' => '[ 8-) ]',
            'approve' => '[ ^-) ]',
            'big_grin' => '[ :-D ]',
            'big_smile' => '[ :-] ]',
            'blush' => '[ :-I ]',
            'boom' => '[ boom ]',
            'clown' => '[ :o) ]',
            'confused' => '[ <:-/ ]',
            'cool' => '[ B-) ]',
            'crying' => '[ !-( ]',
            'dead' => '[ xx-P ]',
            'evil' => '[ }:-) ]',
            'evil_eye' => '[ };-) ]',
            'exclamation' => '[ ! ]',
            'flower' => '[ F ]',
            'geek' => '[ G ]',
            'kiss' => '[ :-x ]',
            'martian' => '[ M ]',
            'moon' => '[ moon ]',
            'ogle' => '[ 8-P ]',
            'ouch' => '[ P-( ]',
            'peace' => '[ V ]',
            'question' => '[ ? ]',
            'rainbow' => '[ (( ]',
            'raise_eyebrows' =>'[ E-) ]',
            'raspberry' => '[ :-P ]',
            'roll_eyes' => '[ 8-] ]',
            'sad' => '[ :-( ]',
            'shy' => '[ 8-/ ]',
            'sleepy' => '[ zzz ]',
            'smile' => '[ :-) ]',
            'stern' => '[ >:-. ]',
            'surprise' => '[ 8-o ]',
            'thoughtful' => '[ >8-/ ]',
            'thumbs_down' => '[ - ]',
            'thumbs_up' => '[ + ]',
            'unsure' => '[ :-/ ]',
            'up_yours' => '[ @#%& ]',
            'very_angry' => '[ >:-0 ]',
            'wink' => '[ ;-) ]',
            'worried' => '[ 8-( ]',
        );
        
        $objLink = new link('javascript:this.close();');
        $objLink->link = $closeLabel;
        $objLink->title = $closeTitleLabel;
        $closeLink = $objLink->show();
        
        $objTable = new htmltable();
        $objTable->cellspacing = '2';
        $objTable->cellpadding = '2';
        $objTable->startRow();
        $objTable->addCell('<strong>'.$smileyLabel.'</strong>', '', '', '', '', 'colspan="8"');
        $objTable->endRow();
        $array = array_chunk($list, '4', TRUE);
        foreach($array as $line){
            $objTable->startRow();
            foreach($line as $smiley => $code){
                $this->objIcon->setIcon($smiley, 'gif', 'icons/smileys/');
                $this->objIcon->title = '';
                $this->objIcon->extra = '';
                $icon = $this->objIcon->show();
                
                $objTable->addCell('<div id="'.$smiley.'" style="cursor: pointer;" onclick="addSmiley(this.id)">'.$icon.'</div>', '12.5%', '', 'center', '', '');
                $objTable->addCell('<nobr><font class="warning"><b>'.htmlentities($code).'</b></font></nobr>', '', '', '', '', '');
            }
            $objTable->endRow();
        }
        $objTable->startRow();
        $objTable->addCell('<br />'.$closeLink, '', '', 'center', '', 'colspan="8"');
        $objTable->endRow();
        $smileyTable = $objTable->show();
        $string = $smileyTable;

        $content .= $this->objFeaturebox->show($title, $string);

        $objLayer = new layer();
        $objLayer->padding = '10px';
        $objLayer->addToStr($content);
        $str = $objLayer->show();
        return $str;        
    }
    
    /**
    * Method to create the content for the chat message div
    * 
    * @access public
    * @return string The template output string
    */
    public function getChat()
    {
        $roomId = $this->getSession('chat_room_id');
        $counter = $this->getSession('message_counter');
        $messages = $this->dbMessaging->getChatMessages($roomId, $counter);
        
        $str = '';
        if($messages != FALSE){
            $str = '<ul>';
            foreach($messages as $message){
                $str .= '<li>';
                $userId = $message['sender_id'];
                $name = $this->objUser->fullname($userId);
                $date = $this->objDatetime->formatDate($message['date_created']);
                $str .= '<strong>['.$name.',&nbsp;';
                $str .= $date.']:</strong><br />';
                $str .= nl2br($message['message']);
//                $str .= $message['message'];
                $str .= '</li>';
            }
            $str .= '</ul>';
        }
        echo $str;
    }    

    /**
    * Method to create the content for the banned user opopup
    * 
    * @access public
    * @param string $userId The userId of the user to be banned
    * @return string The template output string
    */
    public function banPopup($userId)
    {
        $typeLabel = $this->objLanguage->languageText('mod_messaging_bantype', 'messaging');
        $tempLabel = $this->objLanguage->languageText('mod_messaging_temp', 'messaging');
        $indefLabel = $this->objLanguage->languageText('mod_messaging_indefinitely', 'messaging');
        $lengthLabel = $this->objLanguage->languageText('mod_messaging_banlength', 'messaging');
        $submitLabel = $this->objLanguage->languageText('mod_messaging_wordsubmit', 'messaging');
        $cancelLabel = $this->objLanguage->languageText('mod_messaging_wordcancel', 'messaging');
                
        $roomId = $this->getSession('chat_room_id');
        
        $objRadio = new radio('type');
        $objRadio->addOption(0, '&nbsp;'.$tempLabel);
        $objRadio->addOption(1, '&nbsp;'.$indefLabel);
        $objRadio->setSelected(0);
        $objRadio->setBreakSpace('<br />');
        $objRadio->extra = ' onclick="javascript:
            var myDiv = document.getElementById(\'lengthDiv\');
            if(this.value == 0){
                myDiv.style.visibility = \'visible\'; 
                myDiv.style.height = \'\';         
            }else{
                myDiv.style.visibility = \'hidden\';
                myDiv.style.height = \'0px\';
            }"';
        $typeRadio = $objRadio->show();
        
        $typeFeature = $this->objFeaturebox->show($typeLabel, $typeRadio);
        
        $objRadio = new radio('type');
        $objRadio->addOption(0, '&nbsp;'.$tempLabel);
        $objRadio->addOption(1, '&nbsp;'.$indefLabel);
        $objRadio->setSelected(1);
        $objRadio->setBreakSpace('<br />');
        $typeRadio = $objRadio->show();
        
        $objDrop = new dropdown('length');
        $objDrop->addOption(5, '&nbsp;5 min');
        $objDrop->addOption(10, '&nbsp;10 min');
        $objDrop->addOption(15, '&nbsp;15 min');
        $objDrop->addOption(30, '&nbsp;30 min');
        $objDrop->addOption(45, '&nbsp;45 min');
        $objDrop->addOption(60, '&nbsp;60 min');
        $objDrop->extra = ' style="width: 60px;"';
        $lengthDrop = $objDrop->show();
        
        $lengthFeature = $this->objFeaturebox->show($lengthLabel, $lengthDrop);

        $objLayer = new layer();
        $objLayer->id = 'lengthDiv';
        $objLayer->addToStr($lengthFeature);
        $lengthDiv = $objLayer->show();
        
        $objButton = new button('send', $submitLabel);
        $objButton->extra = ' onclick="javascript:
            var myForm = document.getElementById(\'form_ban\');
            myForm.submit();
            window.close();"';
        $sendButton = $objButton->show();
        
        $objButton = new button('cancel', $cancelLabel);
        $objButton->extra = ' onclick="javascript:window.close()"';
        $cancelButton = $objButton->show();
        
        $objForm = new form('ban', $this->uri(array(
            'action' => 'banuser',
            'roomId' => $roomId,
            'userId' => $userId,
            )));
        $objForm->addToForm($typeFeature);
        $objForm->addToForm($lengthDiv);
        $objForm->addToForm($sendButton.'&nbsp;'.$cancelButton);
        $banForm = $objForm->show();
        
        $objLayer = new layer();
        $objLayer->id = 'formDiv';
        $objLayer->padding = '10px';
        $objLayer->addToStr($banForm);
        $formDiv = $objLayer->show();
        $str = $formDiv;
        
        return $str;
    }    
}
?>