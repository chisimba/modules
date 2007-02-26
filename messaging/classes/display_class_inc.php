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
    * @var object $objUser: The user class of the security module
    * @access private
    */
    private $objUser;
     
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
    * @var object $objLanguage: The language class of the language module
    * @access private
    */
    private $objLanguage;
     
    /**
    * @var object $objIcon: The geticon class of the htmlelements module
    * @access private
    */
    public $objIcon;
     
    /**
    * @var object $objDatetime: The datetime class of the utilities module
    * @access private
    */
    public $objDatetime;
     
    /**
    * @var object $objWorkgroup: The dbworkgroup class in the workgroup module
    * @access public
    */
//    public $objWorkgroup;

    /**
    * @var object $objContext: The dbcontexr class in the context module
    * @access public
    */
    public $objContext;

    /**
    * @var object $dbUserlog: The dbuserlog class in the messaging module
    * @access public
    */
    public $dbUserlog;

    /**
    * @var object $objFeaturebox: The featurebox class in the navigation module
    * @access public
    */
    public $objFeaturebox;

    /**
    * Method to construct the class
    *
    * @access public
    * @return
    */
    public function init()
    {   
        $this->loadClass('htmlheading', 'htmlelements');
        $this->loadClass('htmltable', 'htmlelements');
        $this->loadClass('link', 'htmlelements');
        $this->loadClass('textinput', 'htmlelements');
        $this->loadClass('textarea', 'htmlelements');
        $this->loadClass('radio', 'htmlelements');
        $this->loadClass('form', 'htmlelements');
        $this->loadClass('button', 'htmlelements');
        $this->loadClass('windowpop','htmlelements');
        $this->loadClass('layer','htmlelements');
        $this->loadClass('tabbedbox', 'htmlelements');

        $this->objLanguage = $this->getObject('language','language');
        $this->objUser = $this->getObject('user', 'security');
        $this->objIcon = $this->newObject('geticon', 'htmlelements');
        $this->objDatetime = $this->getObject('datetime', 'utilities');
        $this->objContext = $this->getObject('dbcontext', 'context');
        $this->dbUserlog = $this->getObject('dbuserlog', 'messaging');
        $this->objFeaturebox = $this->getObject('featurebox', 'navigation');

        $this->userId = $this->objUser->userId(); 
        $this->isLecturer = $this->objUser->isContextLecturer();       
    }

    /**
    * Method to create the chat room list template
    *
    * @access public
    * @param array|boolean $rooms The data from the chat rooms database table or FALSE if no data
    * @return string $str The template output string
    **/
    public function roomList($rooms, $userRooms)
    {
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
                        'action'=>'readmore',
                        'room_id'=>$room['id']
                    )));
                    $objPopup->set('linktext','[...'.$moreLabel.'...]');
                    $objPopup->set('width','600');
                    $objPopup->set('height','500');
                    $objPopup->set('left','100');
                    $objPopup->set('top','100');
                    $objPopup->set('scrollbars', 'yes');
                    $objPopup->putJs(); // you only need to do this once per page
                    $morePopup = $objPopup->show();
                    
                    $roomDesc = substr($roomDesc, 0, 255).'&nbsp;&nbsp;'.$morePopup;
                }
                $roomType = $room['room_type'];
                $ownerId = $room['owner_id'];
                $dateCreated = explode(' ', $room['date_created']);
                $date = $this->objDatetime->formatDate($dateCreated[0]);
                
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
    * @param array $roomData The array containing the chat room data
    * @return string $str The template output string
    */
    public function addRoom($mode = 'add', $roomData = NULL)
    {
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
        
        $objHeader = new htmlHeading();
        if($mode == 'add'){
            $objHeader->str = $addLabel;
        }else{
            $objHeader->str = $editLabel;
        }
        $objHeader->type = 1;
        $header = $objHeader->show();        
        $str = $header;
        
        if($mode == 'add'){
            $roomId = '';
            $roomType = 1;
            $roomName = '';
            $roomDesc = '';
            $textOnly = 0;
            $disabled = 0;
        }else{
            $roomId = $roomData['id'];
            $roomType = $roomData['room_type'];
            $roomName = $roomData['room_name'];
            $roomDesc = $roomData['room_desc'];
            $textOnly = $roomData['text_only'];
            $disabled = $roomData['disabled'];
        }
        
        $objInput = new textinput('roomId', $roomId, 'hidden');
        $roomIdInput = $objInput->show();
        
        $objRadio = new radio('room_type');
        $objRadio->addOption(1, $privateLabel);
        if($this->isLecturer){
            $objRadio->addOption(2, ucfirst($contextLabel));
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
        $objRadio->addOption(0, $entitiesLabel);
        $objRadio->addOption(1, $textLabel);
        $objRadio->setSelected($textOnly);
        $objRadio->setBreakSpace('<br />');
        $displayRadio = $objRadio->show();
        
        $objRadio = new radio('disabled');
        $objRadio->addOption(0, $enabledLabel);
        $objRadio->addOption(1, $disabledLabel);
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
    * @param array $roomData The array containing the chat room data
    * @return string $str The template output string
    */
    public function readMore($roomData)
    {
        $nameLabel = $this->objLanguage->languageText('mod_messaging_wordname', 'messaging');
        $descLabel = $this->objLanguage->languageText('mod_messaging_worddesscription', 'messaging');
        $closeLabel = $this->objLanguage->languageText('mod_messaging_wordclose', 'messaging');
        $closeTitleLabel = $this->objLanguage->languageText('mod_messaging_closetitle', 'messaging');
        
        $string = $this->objFeaturebox->show($nameLabel, $roomData['room_name']);
        $string .= $this->objFeaturebox->show($descLabel, $roomData['room_desc']);
        
        $objLink = new link('javascript:this.close();');
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
        $array = array(
            'room' => $roomData['room_name'],
        );
        $heading = $this->objLanguage->code2Txt('mod_messaging_room', 'messaging', $array);
        $sendLabel = $this->objLanguage->languageText('mod_messaging_wordsend', 'messaging');
        $clearLabel = $this->objLanguage->languageText('mod_messaging_wordclear', 'messaging');
        
        $objHeader = new htmlHeading();
        $objHeader->str = ucfirst($heading);
        $objHeader->type = 1;
        $header = $objHeader->show();        
        $str = $header;
        
        $objLayer = new layer();
        $objLayer->id = 'chatroom';
        $objLayer->height = '300px';
        $objLayer->border = '1px solid black';
        $chatLayer = $objLayer->show();
        $str .= $chatLayer;
        
        $objText = new textarea('chat');
        $chatText = $objText->show();
        
        $objButton = new button('send', $sendLabel);
        $objButton->setToSubmit();
        $sendButton = $objButton->show();
        
        $objButton = new button('clear', $clearLabel);
        $objButton->extra = 'onclick="javascript: 
            var element = document.getElementById(\'input_chat\');
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
        $str .= $chatTable;
        
        return $str;
    }
    
    /**
    * Method to create the active users list
    * 
    * @access public
    * @param array $users The list of users in the active chat room
    * @param array $bannedUsers The list of users banned from the active chat room
    * @return string $str The template output string
    */
    public function getUsers($users, $bannedUsers)
    {
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
                    $this->objIcon->setIcon('failed');
                    $this->objIcon->title = 'User banned';
                    $icon = $this->objIcon->show();
               }else{
                    $this->objIcon->title = 'Click to unban user';
                    $icon = $this->objIcon->getLinkedIcon($this->uri(array('action' => 'unban')),'failed');
                }
            }else{
                $this->objIcon->title = 'Click to ban user';
                $icon = $this->objIcon->getLinkedIcon($this->uri(array('action' => 'ban', 'roomId' => $user['room_id'])),'ok');
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
    public function showSmileys()
    {
        $script = '<script type="text/javaScript">
            var namelist = new Array("alien", "angel", "angry", "anticipation", "approve", "big_grin", "big_smile", "blush", "boom", "clown", "confused", "cool", "crying", "dead", "evil", "evil_eye", "exclamation", "flower", "geek", "kiss", "martian", "moon", "ogle", "ouch", "peace", "question", "rainbow", "raise_eyebrows", "raspberry", "roll_eyes", "sad", "shy", "sleepy", "smile", "stern", "surprise", "thoughtful", "thumbs_down", "thumbs_up", "unsure", "up_yours", "very_angry", "wink", "worried");
            
            var codelist = new Array("[A51]", "[0:-)]", "[>:-(]", "[8-)]", "[^-)]", "[:-D]", "[:-]]", "[:-I]", "[<@>]", "[:o)]", "[<:-/]", "[B-)]", "[!-(]", "[xx-P]", "[}:-)]", "[};-)]", "[!]", "[F]", "[G]", "[:-x]", "[M]", "[B]", "[8-P]", "[P-(]", "[V]", "[?]", "[((]", "[E-)]", "[:-P]", "[8-]]", "[:-(]", "[8-/]", "[zzz]", "[:-)]", "[>:-.]", "[8-o]", "[>8-/]", "[-]", "[+]", "[:-/]", "[@#$%]", "[>:-0]", "[;-)]", "[8-(]");
            
            Event.observe(window, "load", init_smiley, false);
        
            function init_smiley(){
                for(var i = 0; i <= namelist.length-1; i++){
                    Event.observe(namelist[i], "click", addSmiley, false);                    
                }
            }
            
            function addSmiley()
            {
                var element = opener.document.getElementById("input_chat");
                for(i = 0; i <= namelist.length-1; i++){
                    if(namelist[i] == this.id){
                        if(element.value == ""){
                            element.value = codelist[i];
                        }else{
                            element.value = element.value + " " + codelist[i];
                        }
                    }
                }
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
            'boom' => '[ <@> ]',
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
            'moon' => '[ B ]',
            'ogle' => '[ 8-P ]',
            'ouch' => '[ P-( ]',
            'peace' => '[ V ]',
            'question' => '[ ? ]',
            'rainbow' => '[ (( ]',
            'raise_eyebrows'=>'[ E-) ]',
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
                
                $objTable->addCell('<div id="'.$smiley.'" style="cursor: pointer;">'.$icon.'</div>', '12.5%', '', 'center', '', '');
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
}
?>