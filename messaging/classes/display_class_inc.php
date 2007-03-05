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
    * @var object $dbMessages: The dbmessages class in the messaging module
    * @access private
    */
    private $dbMessages;

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
        $this->loadClass('checkbox', 'htmlelements');
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
        $this->dbMessages = $this->getObject('dbmessages', 'messaging');
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
            Event.observe(window, "load", get_chat, false);
    
            function get_chat(){
                var url = "index.php";
                var target = "chatDiv";
                var pars = "module=messaging&action=getchat";
                var myAjax = new Ajax.Updater(target, url, {method: "post", parameters: pars, onComplete: chat_timer});
            }

            function chat_timer(){
                var myDiv = document.getElementById("chatDiv");
                myDiv.scrollTop = myDiv.scrollHeight
                setTimeout("get_chat()", 3000);
            }
            
            function send_chat(){
                var msg = document.getElementById("input_message");
                
                if(msg.value != ""){
                    var url = "index.php";
                    var target = "input_message";
                    var pars = "module=messaging&action=sendchat&chat=" + msg.value;
                    var myAjax = new Ajax.Updater(target, url, {method: "post", parameters: pars});                
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
                
        $objLayer = new layer();
        $objLayer->id = 'bannedDiv';
        $objLayer->padding = '10px';
        $objLayer->border = '1px solid red';
        $bannedLayer = $objLayer->show();
        $str .= $bannedLayer;
        
        // heading
        $objHeader = new htmlHeading();
        $objHeader->str = ucfirst($heading);
        $objHeader->type = 1;
        $header = $objHeader->show();        
        $str .= $header;
        
        $objLayer = new layer();
        $objLayer->id = 'chatDiv';
        $objLayer->height = '300px';
        $objLayer->border = '1px solid black';
        $objLayer->overflow = 'auto';
        $chatLayer = $objLayer->show();
        $str .= $chatLayer;
        
        $objText = new textarea('message');
        $chatText = $objText->show();
        
        $objButton = new button('send', $sendLabel);
        $objButton->extra = ' onclick="javascript:
            send_chat();"';
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

        $objLayer = new layer();
        $objLayer->id = 'formDiv';
        $objLayer->addToStr($chatForm);
        $formLayer = $objLayer->show();
        $str .= $formLayer;
        
        return $str;
    }
    
    /**
    * Method to create the active users list
    * 
    * @access public
    * @return string $str The template output string
    */
    public function getUsers()
    {
        // language items
        $activeLabel = $this->objLanguage->languageText('mod_messaging_active', 'messaging');
        $banLabel = $this->objLanguage->languageText('mod_messaging_ban', 'messaging');
        $unbanLabel = $this->objLanguage->languageText('mod_messaging_unban', 'messaging');
        $indefLabel = $this->objLanguage->languageText('mod_messaging_banindef', 'messaging');
        
        // get data
        $roomId = $this->getSession('chat_room_id');
        $roomData = $this->dbRooms->getRoom($roomId);
        $users = $this->dbUserlog->listUsers($roomId);
        $bannedUsers = $this->dbBanned->listUsers($roomId);

        $banned = 'N';
        $userId = '';
        
        $objTable = new htmltable();
        $objTable->cellspacing = '2';
        $objTable->cellpadding = '2';
        if($users != FALSE){
            foreach($users as $user){
                $name = $this->objUser->fullname($user['user_id']);
                $isBanned = FALSE;
                $tempBan = FALSE;
                if($bannedUsers != FALSE){
                    foreach($bannedUsers as $key=>$bannedUser){
                        if($user['user_id'] == $bannedUser['user_id']){
                            if($bannedUser['user_id'] == $this->userId){
                                $banned = 'Y';
                                $userId = $this->userId;
                            }
                            $isBanned = TRUE;
                            $bannedId = $bannedUser['id'];
                            if($bannedUser['ban_type'] != 1){
                                $tempBan = TRUE;
                            }   
                        }
                    }
                }
                if($isBanned){
                    if($tempBan){
                        $dateNow = strtotime(date('Y-m-d H:i:s'));
                        $banStop = strtotime($bannedUser['ban_stop']);
                        if($dateNow >= $banStop){
                            $this->dbBanned->deleteUser($bannedUser['id']);
                            $array = array(
                                'user' => $this->objUser->fullname($bannedUser['user_id'])
                          );
                            $message = $this->objLanguage->code2Txt('mod_messaging_unbantempmsg', 'messaging', $array);                    
                            $this->dbMessages->addChatMessage($message, TRUE);             
                        }
                        $date = $this->objDatetime->formatDate($bannedUser['ban_stop']);    
                       $array = array(
                            'date' => $date,
                        );
                        $bannedLabel = $this->objLanguage->code2Txt('mod_messaging_bantemp', 'messaging', $array);

                        $this->objIcon->setIcon('failed');
                        $this->objIcon->title = $bannedLabel;
                        $icon = $this->objIcon->show();
                   }else{
                        $this->objIcon->title = $indefLabel;
                        $this->objIcon->setIcon('failed');
                        $this->objIcon->extra = '';
                        $icon = $this->objIcon->show();
                    }
                }else{
                    $this->objIcon->title = $activeLabel;
                    $this->objIcon->setIcon('ok');
                    $this->objIcon->extra = '';
                    $icon = $this->objIcon->show();
                }
                $objTable->startRow();
                $objTable->addCell($name, '', '', '', '', '');
                $objTable->addCell($icon, '', '', '', '', '');
                $objTable->endRow();
            }
            $objInput = new textinput('banned', $banned, 'hidden');
            $bannedInput = $objInput->show();

            $objInput = new textinput('userId', $userId, 'hidden');
            $userIdInput = $objInput->show();

            $objTable->startRow();
            $objTable->addCell($bannedInput.$userIdInput, '', '', '', '', '');
            $objTable->endRow();
            
            $str = $objTable->show();
            echo $str;
        }else{
            echo '';
        }
    }
    
    /**
    * Method to create the show more smileys template
    *
    * @access public
    * @return string $str The template output string
    */
    public function moreSmileys()
    {
        // javascript
        $this->setVar('pageSuppressXML', TRUE);
        $script = '<script type="text/javaScript">
            var namelist = new Array("alien", "angel", "angry", "anticipation", "approve", "big_grin", "big_smile", "blush", "boom", "clown", "confused", "cool", "crying", "dead", "evil", "evil_eye", "exclamation", "flower", "geek", "kiss", "martian", "moon", "ogle", "ouch", "peace", "question", "rainbow", "raise_eyebrows", "raspberry", "roll_eyes", "sad", "shy", "sleepy", "smile", "stern", "surprise", "thoughtful", "thumbs_down", "thumbs_up", "unsure", "up_yours", "very_angry", "wink", "worried");
            
            var codelist = new Array("[A51]", "[0:-)]", "[>:-(]", "[8-)]", "[^-)]", "[:-D]", "[:-]]", "[:-I]", "[<@>]", "[:o)]", "[<:-/]", "[B-)]", "[!-(]", "[xx-P]", "[}:-)]", "[};-)]", "[!]", "[F]", "[G]", "[:-x]", "[M]", "[B]", "[8-P]", "[P-(]", "[V]", "[?]", "[((]", "[E-)]", "[:-P]", "[8-]]", "[:-(]", "[8-/]", "[zzz]", "[:-)]", "[>:-.]", "[8-o]", "[>8-/]", "[-]", "[+]", "[:-/]", "[@#$%]", "[>:-0]", "[;-)]", "[8-(]");
            
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
                window.close();
                msg.focus();
            }
        </script>';
        $content = $script;

        // language items
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
        
        $objLink = new link('javascript:
            this.close();');
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
        // language items
        $systemLabel = $this->objLanguage->languageText('mod_messaging_wordsystem', 'messaging');
        
        // get data
        $roomId = $this->getSession('chat_room_id');
        $counter = $this->getSession('message_counter');
        $messages = $this->dbMessages->getChatMessages($roomId, $counter);
        
        $str = '';
        if($messages != FALSE){
            $str = '<ul>';
            foreach($messages as $message){
                $str .= '<li>';
                $userId = $message['sender_id'];
                if($userId == 'system'){
                    $name = $systemLabel;   
                }else{
                    $name = $this->objUser->fullname($userId);
                }
                $date = $this->objDatetime->formatDate($message['date_created']);
                $str .= '<strong>['.$name.'&nbsp;-&nbsp;';
                $str .= $date.']:</strong><br />';
                $str .= nl2br($message['message']);
                $str .= '</li>';
            }
            $str .= '</ul>';
        }
        echo $str;
    }    

    /**
    * Method to create the content for the banned user popup
    * 
    * @access public
    * @return string The template output string
    */
    public function banPopup()
    {
         $style = '<style type="text/css">
            div.autocomplete {
                position:absolute;
                background-color:white;
            }    
            div.autocomplete ul {
                list-style-type:none;
                margin:0px;
                padding:0px;
            }    
            div.autocomplete ul li.selected {
                border:1px solid #888;
                background-color: #ffb;
            }
            div.autocomplete ul li {
                border:1px solid #888;
                list-style-type:none;
                display:block;
                margin:0;
                cursor:pointer;
            }
        </style>';
        $str = $style;

        $script = '<script type="text/javaScript">
            function users()
            {        
                var myRadio = document.getElementsByName("option");
                var len = myRadio.length;
                var myValue = "";
                for(var i = 0; i <= len-1; i++){
                    if(myRadio[i].checked){
                        myValue = myRadio[i].value;
                    }
                }
                var pars = "module=messaging&action=listusers&option="+myValue;
                new Ajax.Autocompleter("input_username", "userDiv", "index.php", {parameters: pars});
            }
        </script>';
        $str .= $script;

        // language items
        $errLabel = $this->objLanguage->languageText('mod_messaging_errban', 'messaging');
        $banLabel = $this->objLanguage->languageText('mod_messaging_ban', 'messaging');
        $userLabel = $this->objLanguage->languageText('mod_messaging_worduser', 'messaging');
        $typeLabel = $this->objLanguage->languageText('mod_messaging_bantype', 'messaging');
        $tempLabel = $this->objLanguage->languageText('mod_messaging_temp', 'messaging');
        $indefLabel = $this->objLanguage->languageText('mod_messaging_indefinitely', 'messaging');
        $lengthLabel = $this->objLanguage->languageText('mod_messaging_banlength', 'messaging');
        $submitLabel = $this->objLanguage->languageText('mod_messaging_wordsubmit', 'messaging');
        $cancelLabel = $this->objLanguage->languageText('mod_messaging_wordcancel', 'messaging');
        $nameLabel = $this->objLanguage->languageText('mod_messaging_firstname', 'messaging');
        $surnameLabel = $this->objLanguage->languageText('mod_messaging_surname', 'messaging');
                
        $objHeader = new htmlheading();
        $objHeader->str = $banLabel;
        $objHeader->type = 1;
        $heading = $objHeader->show();

        // get data
        $roomId = $this->getSession('chat_room_id');
        
        $objRadio = new radio('option');
        $objRadio->addOption('firstname', '&nbsp;'.$nameLabel);
        $objRadio->addOption('surname', '&nbsp;'.$surnameLabel);
        $objRadio->setSelected('firstname');
        $objRadio->extra = ' onchange="javascript:
            var myInput = document.getElementById(\'input_username\');
            myInput.value = \'\';
            myInput.focus();"';
        $choiceRadio = $objRadio->show();
        
        $objInput = new textinput('username', '', '', 50);
        $objInput->extra = ' onkeyup="javascript:
            users()"';
        $userInput = $objInput->show();
        
        $objInput = new textinput('userId', '', 'hidden', '');
        $userIdInput = $objInput->show();
        
        $objLayer = new layer();
        $objLayer->id = 'userDiv';
        $objLayer->cssClass = 'autocomplete';
        $userDiv = $objLayer->show();
        
        $objTable = new htmltable();
        $objTable->cellspacing = '2';
        $objTable->cellpadding = '2';
        $objTable->startRow();
        $objTable->addCell($choiceRadio);
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($userInput.$userDiv.$userIdInput);
        $objTable->endRow();
        $userTable = $objTable->show();
        
        $userFeature = $this->objFeaturebox->show($userLabel, $userTable);
        
        $objRadio = new radio('type');
        $objRadio->addOption(0, '&nbsp;'.$tempLabel);
        $objRadio->addOption(1, '&nbsp;'.$indefLabel);
        $objRadio->setSelected(0);
        $objRadio->setBreakSpace('<br />');
        $objRadio->extra = ' onclick="javascript:
            var lDiv = document.getElementById(\'lengthDiv\');
            if(this.value == 0){
                lDiv.style.visibility = \'visible\';
                lDiv.style.display = \'block\';
            }else{
                lDiv.style.visibility = \'hidden\';
                lDiv.style.display = \'none\';
            }"';
        $typeRadio = $objRadio->show();
        
        $typeFeature = $this->objFeaturebox->show($typeLabel, $typeRadio);
        
        $objDrop = new dropdown('length');
        $objDrop->addOption(1, '&nbsp;1 min');
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
            var el = document.getElementById(\'input_userId\');
            if(el.value == \'\'){
                alert(\''.$errLabel.'\');
                return false;
            }else{
                document.getElementById(\'form_ban\').submit();
            }"';
        $sendButton = $objButton->show();
        
        $objButton = new button('cancel', $cancelLabel);
        $objButton->extra = ' onclick="javascript:window.close()"';
        $cancelButton = $objButton->show();
        
        $objLayer = new layer();
        $objLayer->id = 'buttonDiv';
        $objLayer->addToStr($sendButton.'&nbsp;'.$cancelButton);
        $buttonDiv = $objLayer->show();
        
        $objForm = new form('ban', $this->uri(array(
            'action' => 'banuser',
            'roomId' => $roomId,
            )));
        $objForm->addToForm($userFeature);
        $objForm->addToForm($typeFeature);
        $objForm->addToForm($lengthDiv);
        $objForm->addToForm($buttonDiv);
        $banForm = $objForm->show();
        
        $objLayer = new layer();
        $objLayer->id = 'formDiv';
        $objLayer->padding = '10px';
        $objLayer->addToStr($heading.$banForm);
        $formDiv = $objLayer->show();
        $str .= $formDiv;
        
        return $str;
    }    
    
    /**
    * Method to show the list of users in the chat room
    *
    * @access public
    * @param string $option: The field to search
    * @param string $value: The value to search for
    * @return string $str: The output string
    */
    public function listUsers($option, $value)
    {
        // get data
        $roomId = $this->getSession('chat_room_id');
        $contextcode = $this->getSession('contextCode');
        $roomData = $this->dbRooms->getRoom($roomId);
        $userList = $this->dbUserlog->searchUsers($roomId, $option, $value);
        // language items
        $noMatchLabel = $this->objLanguage->languageText('mod_messaging_nomatch', 'messaging');
        
        foreach($userList as $user){
            $this->moderator = FALSE;
            if($roomData['room_type'] == 0){
                $isAdmin = $this->objUser->inAdminGroup($user['user_id']);
                if($isAdmin){
                    $this->moderator = TRUE;
                }
            }elseif($roomData['room_type'] == 1){
                if($roomData['owner_id'] == $user['user_id']){
                    $this->moderator = TRUE;
                }
            }elseif($roomData['room_type'] == 2){
                $isLecturer = $this->objUser->isContextLecturer($user['user_id'], $contextCode);
                if($isLecturer){
                    $this->moderator = TRUE;
                }
            }elseif($roomData['room_type'] == 3){
                //to do once workgroups is ported
            }
        }                    
        if(isset($user['ban_type']) == FALSE && $this->moderator == FALSE){
            $str = '<ul>';
            $str .= '<li onclick="javascript:
                document.getElementById(\'input_userId\').value=\''.$user['user_id'].'\'"><strong>';
            $str .= $this->objUser->fullname($user['user_id']);
            $str .= '</strong></li>';
            $str .= '</ul>';            
        }else{
            $str = '<ul><li><strong>'.$noMatchLabel.'</strong></li></ul>';    
        }
        echo $str;        
    }

    /**
    * Method to show the banned message div
    * 
    * @access public
    * @param string $userId: The id of the user to show the div to
    * @return string $str: The output string
    */
    public function getBanMsg($userId)
    {
        // get data
        $roomId = $this->getSession('chat_room_id');
        $bannedData = $this->dbBanned->isBanned($userId, $roomId);
        
        // language items
        if($bannedData != FALSE){
            $banType = $bannedData['ban_type'];
            if($banType == 1){
                $bannedLabel = $this->objLanguage->languageText('mod_messaging_isbannedindef', 'messaging');
            }else{
                $array = array(
                    'date' => $this->objDatetime->formatDate($bannedData['ban_stop']),
                );
                $bannedLabel = $this->objLanguage->code2Txt('mod_messaging_isbannedtemp', 'messaging', $array);
            }        
            $str = '<font class="error"><b>'.$bannedLabel.'</b></font>';       
        }else{
            $str = '';
        }
        echo $str;        
    }

    /**
    * Method to show the confirmed banned message
    * 
    * @access public
    * @param string $banType: The type of ban selected
    * @param string $userId: The id of the user banned
    * @return string $str: The output string
    */
    public function confirmBan($banType, $userId)
    {
        // language items
        $array = array(
            'name' => $this->objUser->fullname($userId),
        );        
        $tempLabel = $this->objLanguage->code2Txt('mod_messaging_confirmtemp', 'messaging', $array);
        $indefLabel = $this->objLanguage->code2Txt('mod_messaging_confirmindef', 'messaging', $array);
        $closeLabel = $this->objLanguage->languageText('mod_messaging_wordclose', 'messaging');
        $titleLabel = $this->objLanguage->languageText('mod_messaging_closetitle', 'messaging');
        
        $objHeader = new htmlheading();
        if($banType == 1){
            $objHeader->str = $indefLabel;
        }else{
            $objHeader->str = $tempLabel;
        }
        $objHeader->type = 3;
        $heading = $objHeader->show();
        $string = $heading;
        
        $objLink = new link('javascript:window.close()');
        $objLink->title = $titleLabel;
        $objLink->link = $closeLabel;
        $closeLink = $objLink->show();

        $objTable = new htmltable();
        $objTable->cellspacing = '2';
        $objTable->cellpadding = '2';
        $objTable->startRow();
        $objTable->addCell($closeLink, '', '', 'center', '', '');
        $objTable->endRow();
        $linkTable = $objTable->show();    
        $string .= $linkTable;
        
        $objLayer = new layer();
        $objLayer->addToStr($string);
        $objLayer->padding = '10px';
        $str = $objLayer->show();
        
        return $str;        
    }
    
    /**
    * Method to create the content for the unban user popup
    * 
    * @access public
    * @return string The template output string
    */
    public function unbanPopup()
    {
        $headerParams = $this->getJavascriptFile('selectall.js', 'htmlelements');
        $this->appendArrayVar('headerParams', $headerParams);
        $headerParams = $this->getJavascriptFile('new_sorttable.js', 'htmlelements');
        $this->appendArrayVar('headerParams', $headerParams);

        // language items
        $unbanLabel = $this->objLanguage->languageText('mod_messaging_unban', 'messaging');
        $noRecordsLabel = $this->objLanguage->languageText('mod_messaging_norecords', 'messaging');
        $nameLabel = $this->objLanguage->languageText('mod_messaging_firstname', 'messaging');
        $surnameLabel = $this->objLanguage->languageText('mod_messaging_surname', 'messaging');
        $selectLabel = $this->objLanguage->languageText('mod_messaging_selectall', 'messaging');
        $deselectLabel = $this->objLanguage->languageText('mod_messaging_deselectall', 'messaging');
        $selectTitleLabel = $this->objLanguage->languageText('mod_messaging_selectalltitle', 'messaging');
        $deselectTitleLabel = $this->objLanguage->languageText('mod_messaging_deselectalltitle', 'messaging');
        $errLabel = $this->objLanguage->languageText('mod_messaging_errunban', 'messaging');
        $submitLabel = $this->objLanguage->languageText('mod_messaging_wordsubmit', 'messaging');
        $cancelLabel = $this->objLanguage->languageText('mod_messaging_wordcancel', 'messaging');
        
        // heading
        $objHeader = new htmlheading();
        $objHeader->str = $unbanLabel;
        $objHeader->type = 1;
        $heading = $objHeader->show();
        $string = $heading;
        
        // get data
        $roomId = $this->getSession('chat_room_id');
        $bannedUsers = $this->dbBanned->listUsers($roomId);
        
        if($bannedUsers != FALSE){
            foreach($bannedUsers as $key=>$user){
                if($user['ban_type'] != 1){
                    unset($bannedUsers[$key]);
                }
            }
        }
        $bannedUsers = count($bannedUsers) >= 1 ? $bannedUsers : FALSE;
        
        if($bannedUsers != FALSE){
            $objTable = new htmltable();
            $objTable->cellspacing = 2;
            $objTable->cellpadding = 2;
            
            $objLink = new link('javascript:
    SetAllCheckBoxes(\'unban\',\'userId[]\',true);');
            $objLink->link = $selectLabel;
            $objLink->title = $selectTitleLabel;
            $selectLink = $objLink->show();
            
            $objLink = new link('javascript:
    SetAllCheckBoxes(\'unban\',\'userId[]\',false);');
            $objLink->link = $deselectLabel;
            $objLink->title = $deselectTitleLabel;
            $deselectLink = $objLink->show();

            $links = $selectLink.'&nbsp;|&nbsp;'.$deselectLink;
            $objTable->startRow();
            $objTable->addCell($links, '', '', 'center', '', 'colspan="3"');
            $objTable->endRow();
            $linksTable = $objTable->show();
            $string .= $linksTable;
        }
        
        $objTable = new htmltable();
        $objTable->cellpadding = 4;
        $objTable->id = 'userList';
        $objTable->css_class = 'sorttable';
        $objTable->row_attributes = 'name="row_'.$objTable->id.'"';
        $objTable->startRow();
        $objTable->addCell('', '10%', '', '', 'heading', '');
        $objTable->addCell($nameLabel, '45%', '', '', 'heading', '');
        $objTable->addCell($surnameLabel, '', '', '', 'heading', '');
        $objTable->endRow();
        
        if($bannedUsers == FALSE){
            $objTable->startRow();
            $objTable->addCell($noRecordsLabel, '', '', '', 'noRecordsMessage', 'colspan="3"');
            $objTable->endRow();
        }else{
            foreach($bannedUsers as $user){
                $userId = $user['user_id'];
                $name = $this->objUser->getFirstname($user['user_id']);
                $surname = $this->objUser->getSurname($user['user_id']);
                $banType = $user['ban_type'];
                
                $objCheck = new checkbox('userId[]');
                $objCheck->setValue($userId);
                $userIdCheck = $objCheck->show();
                
                if($banType == 1){
                    $objTable->startRow();
                    $objTable->addCell($userIdCheck, '10%', '', 'center', '', '');
                    $objTable->addCell($name, '45%', '', '', '', '');
                    $objTable->addCell($surname, '', '', '', '', '');
                    $objTable->endRow();
                }
            }
        }
        $usersTable = $objTable->show();

        $objButton = new button('send', $submitLabel);
        $objButton->extra = ' onclick="javascript:
            var elChk = document.getElementsByName(\'userId[]\');
            var elChkValue = false;
            for(var i = 0; i &lt; elChk.length; i++){
                if(elChk[i].checked == true){
                    elChkValue = true;
                }
            }
            if(elChkValue){
                document.getElementById(\'form_unban\').submit();
            }else{
                alert(\''.$errLabel.'\');
            }"';
        $sendButton = $objButton->show();
        
        $objButton = new button('cancel', $cancelLabel);
        $objButton->extra = ' onclick="javascript:window.close()"';
        $cancelButton = $objButton->show();
        
        $objForm = new form('unban', $this->uri(array(
            'action' => 'unbanusers',
        )));
        $objForm->addToForm($usersTable);
        $objForm->addToForm('<br />'.$sendButton.'&nbsp;'.$cancelButton);
        $unbanForm = $objForm->show();
        $string .= $unbanForm;
        
        $objLayer = new layer();
        $objLayer->padding = '10px';
        $objLayer->addToStr($string);
        $str = $objLayer->show();
        
        return $str;        
    }

    /**
    * Method to show the confirmed unbanned message
    * 
    * @access public
    * @param string $users: The list of users unbanned
    * @return string $str: The output string
    */
    public function confirmUnban($users)
    {
        // get data
        $usersList = explode('|', $users);
        
        // language items
        $singleLabel = $this->objLanguage->code2Txt('mod_messaging_confirmunban1', 'messaging');
        $multipleLabel = $this->objLanguage->code2Txt('mod_messaging_confirmunban2', 'messaging');
        $closeLabel = $this->objLanguage->languageText('mod_messaging_wordclose', 'messaging');
        $titleLabel = $this->objLanguage->languageText('mod_messaging_closetitle', 'messaging');
        
        $objHeader = new htmlheading();
        if(count($usersList) > 1){
            $objHeader->str = $multipleLabel;
        }else{
            $objHeader->str = $singleLabel;
        }
        $objHeader->type = 3;
        $heading = $objHeader->show();
        $string = $heading;
        
        $objLink = new link('javascript:window.close()');
        $objLink->title = $titleLabel;
        $objLink->link = $closeLabel;
        $closeLink = $objLink->show();

        $objTable = new htmltable();
        $objTable->cellspacing = '2';
        $objTable->cellpadding = '2';
        foreach($usersList as $user){
            $name = $this->objUser->fullname($user);
            
            $objTable->startRow();
            $objTable->addCell($name, '', '', '', '', '');
            $objTable->endRow();
        }
        $objTable->startRow();
        $objTable->addCell($closeLink, '', '', 'center', '', '');
        $objTable->endRow();
        $linkTable = $objTable->show();    
        $string .= $linkTable;
        
        $objLayer = new layer();
        $objLayer->addToStr($string);
        $objLayer->padding = '10px';
        $str = $objLayer->show();
        
        return $str;        
    }
    
}
?>