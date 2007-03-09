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
    * @var object $objPopupcal: The datepickajax class in the popupcalendar module
    * @access private
    */
    private $objPopupcal;

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

    // TODO: Once workgroups has been ported
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
        $this->objPopupcal = $this->newObject('datepickajax', 'popupcalendar');
        
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
    * @return string $str: The output string
    **/
    public function tplRoomList()
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
        
        // add link
        $objLink = new link($this->uri(array(
            'action' => 'addroom'
        ), 'messaging'));
        $objLink->link = $addLabel;
        $objLink->title = $addTitleLabel;
        $addLink = $objLink->show(); 
        $str .= $addLink;
        
        // main table heading
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
        
        // check if user can enter listed chat rooms
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
        
        // main table
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
                
                // enter room link
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
                
                // read more link if applicable
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
                    // TODO: Once workgroups has been ported
                    $owner = 'to do - workgroup name';
                }
                       
                // edit link
                $objLink = new link($this->uri(array(
                    'action' => 'editroom',
                    'roomId' => $room['id'],
                ), 'messaging'));
                $objLink->link = $editLabel;
                $objLink->title = $editTitleLabel;
                $editLink = '<nobr>'.$objLink->show().'</nobr>';

                // exit link
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

        // exit link
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
    public function tplAddRoom($mode = 'add', $roomId = NULL)
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
        // TODO: Once workgroups has been ported
        //$workgroupLabel = $this->objLanguage->code2Txt('mod_messaging_workgrouproom', 'messaging');
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
        
        // room type radio
        $objRadio = new radio('room_type');
        $objRadio->addOption(1, '&nbsp;'.$privateLabel);
        if($this->isLecturer){
            $objRadio->addOption(2, '&nbsp;'.ucfirst($contextLabel));
        }
        // TODO: Once workgroups has been ported
        //$objRadio->addOption(3, ucfirst($workgroupLabel));
        $objRadio->setSelected($roomType);
        $objRadio->setBreakSpace('<br />');
        $typeRadio = $objRadio->show();
        
        // room name input
        $objInput = new textinput('room_name', $roomName);
        $nameInput = $objInput->show();
        
        // room description textarea
        $objText = new textarea('room_desc', $roomDesc);
        $descText = $objText->show();

        // text only/smileys radio
        $objRadio = new radio('text_only');
        $objRadio->addOption(0, '&nbsp;'.$entitiesLabel);
        $objRadio->addOption(1, '&nbsp;'.$textLabel);
        $objRadio->setSelected($textOnly);
        $objRadio->setBreakSpace('<br />');
        $displayRadio = $objRadio->show();
        
        // enabled/disabled radio
        $objRadio = new radio('disabled');
        $objRadio->addOption(0, '&nbsp;'.$enabledLabel);
        $objRadio->addOption(1, '&nbsp;'.$disabledLabel);
        $objRadio->setSelected($disabled);
        $objRadio->setBreakSpace('<br />');
        $statusRadio = $objRadio->show();
        
        // submit button
        $objButton = new button('submitbutton', $submitLabel);
        $objButton->setToSubmit();
        $objButton->extra = ' onclick="javascript:
            var el_Submitform = document.getElementById(\'form_submitform\');
            var el = document.createElement(\'input\');

            el.setAttribute(\'id\', \'input_button\');
            el.setAttribute(\'name\', \'button\');
            el.setAttribute(\'value\', \'submit\');
            el.setAttribute(\'type\', \'hidden\');
            
            el_Submitform.appendChild(el);"';
        $submitButton = $objButton->show();

        // cancel button
        $objButton = new button('cancelbutton', $cancelLabel);
        $objButton->extra = ' onclick="javascript:
            var el_Cancelform = document.getElementById(\'form_cancelform\');
            var el = document.createElement(\'input\');

            el.setAttribute(\'id\', \'input_button\');
            el.setAttribute(\'name\', \'button\');
            el.setAttribute(\'value\', \'cancel\');
            el.setAttribute(\'type\', \'hidden\');
            
            el_Cancelform.appendChild(el);
            el_Cancelform.submit();"';
        $cancelButton = $objButton->show();

        // feature boxes
        $typeBox = $this->objFeaturebox->show($typeLabel, $typeRadio);
        $nameBox = $this->objFeaturebox->show($nameLabel, $nameInput);
        $descBox = $this->objFeaturebox->show($descLabel, $descText);
        $inputBox = $this->objFeaturebox->show($inputLabel, $displayRadio);
        $statusBox = $this->objFeaturebox->show($statusLabel, $statusRadio);
        
        // main form
        $objForm = new form('submitform', $this->uri(array(
            'action' => 'submitroom',
            'roomId' => $roomId,
            'mode' => $mode,
        ), 'messaging'));
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
        
        // form for cancel button
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
    public function popReadMore($roomId)
    {
        // language items
        $nameLabel = $this->objLanguage->languageText('mod_messaging_wordname', 'messaging');
        $descLabel = $this->objLanguage->languageText('mod_messaging_worddesscription', 'messaging');
        $closeLabel = $this->objLanguage->languageText('mod_messaging_wordclose', 'messaging');
        $closeTitleLabel = $this->objLanguage->languageText('mod_messaging_closetitle', 'messaging');
        
        // get data
        $roomData = $this->dbRooms->getRoom($roomId);
        
        // feature boxes
        $string = $this->objFeaturebox->show($nameLabel, $roomData['room_name']);
        $string .= $this->objFeaturebox->show($descLabel, $roomData['room_desc']);
        
        // close link
        $objLink = new link('javascript:window.close();');
        $objLink->link = $closeLabel;
        $objLink->title = $closeTitleLabel;
        $closeLink = $objLink->show();

        // table to center close link
        $objTable = new htmltable();
        $objTable->cellspacing = '2';
        $objTable->cellpadding = '2';
        $objTable->startRow();
        $objTable->addCell($closeLink, '', '', 'center', '', '');
        $objTable->endRow();
        $string .= $objTable->show();

        // main display layer
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
    public function tplChatRoom()
    {
        // javascript
        $script = '<script type="text/javaScript">
            Event.observe(window, "load", jsGetChat, false);
            var chatTimer;
            
            function jsGetChat(){
                var url = "index.php";
                var target = "chatDiv";
                var pars = "module=messaging&action=getchat";
                var myAjax = new Ajax.Updater(target, url, {method: "post", parameters: pars, onComplete: jsChatTimer});
            }

            function jsChatTimer(){
                var el_ChatDiv = document.getElementById("chatDiv");
                el_ChatDiv.scrollTop = el_ChatDiv.scrollHeight
                chatTimer = setTimeout("jsGetChat()", 3000);
            }
            
            function jsSendChat(){
                var el_Message = document.getElementById("input_message");
                
                if(el_Message.value != ""){
                    var url = "index.php";
                    var target = "input_message";
                    var pars = "module=messaging&action=sendchat&chat=" + el_Message.value;
                    var myAjax = new Ajax.Updater(target, url, {method: "post", parameters: pars});                
                    el_Message.value = "";
                }                
                el_Message.focus();
            }
        </script>';
        $str = $script;
        
        // language items
        $sendLabel = $this->objLanguage->languageText('mod_messaging_wordsend', 'messaging');
        $clearLabel = $this->objLanguage->languageText('mod_messaging_wordclear', 'messaging');
        $inviteLabel = $this->objLanguage->languageText('mod_messaging_invite', 'messaging');
        $removeLabel = $this->objLanguage->languageText('mod_messaging_remove', 'messaging');
        $inviteTitleLabel = $this->objLanguage->languageText('mod_messaging_invitetitle', 'messaging');
        $removeTitleLabel = $this->objLanguage->languageText('mod_messaging_removetitle', 'messaging');
        $logLabel = $this->objLanguage->languageText('mod_messaging_logs', 'messaging');
        $logTitleLabel = $this->objLanguage->languageText('mod_messaging_logtitle', 'messaging');
                
        // get data
        $roomId = $this->getSession('chat_room_id');
        $roomData = $this->dbRooms->getRoom($roomId);
        
        // banned notice div
        $objLayer = new layer();
        $objLayer->id = 'bannedDiv';
        $objLayer->padding = '10px';
        $objLayer->border = '1px solid red';
        $bannedLayer = $objLayer->show();
        $str .= $bannedLayer;
        
        // heading
        $heading = $roomData['room_name'];
        $objHeader = new htmlHeading();
        $objHeader->str = ucfirst($heading);
        $objHeader->type = 1;
        $header = $objHeader->show();        
        $str .= $header;
        
        // popup links
        // popup link to invite users
        $objPopup = new windowpop();
        $objPopup->title = $inviteTitleLabel;
        $objPopup->set('location',$this->uri(array(
            'action' => 'invitepopup',
        )));
        $objPopup->set('linktext', $inviteLabel);
        $objPopup->set('width', '500');
        $objPopup->set('height', '500');
        $objPopup->set('left', '100');
        $objPopup->set('top', '100');
        $objPopup->set('scrollbars', 'no');
        $objPopup->putJs(); // you only need to do this once per page
        $inviteLink = $objPopup->show();

        // popup link to remove users
        $objPopup = new windowpop();
        $objPopup->title = $removeTitleLabel;
        $objPopup->set('location',$this->uri(array(
            'action' => 'removepopup',
        )));
        $objPopup->set('linktext', $removeLabel);
        $objPopup->set('width', '500');
        $objPopup->set('height', '500');
        $objPopup->set('left', '100');
        $objPopup->set('top', '100');
        $objPopup->set('scrollbars', 'no');
        $removeLink = $objPopup->show();
            
        // popup link to invite users
        $objPopup = new windowpop();
        $objPopup->title = $logTitleLabel;
        $objPopup->set('location',$this->uri(array(
            'action' => 'logs',
        )));
        $objPopup->set('linktext', $logLabel);
        $objPopup->set('width', '500');
        $objPopup->set('height', '500');
        $objPopup->set('left', '100');
        $objPopup->set('top', '100');
        $objPopup->set('scrollbars', 'no');
        $objPopup->putJs(); // you only need to do this once per page
        $logLink = $objPopup->show();

        if($roomData['room_type'] == 1 && $roomData['owner_id'] == $this->userId){
            $links = $inviteLink.'&nbsp;|&nbsp;'.$removeLink;
        }else{
            $links = '';
        }
            
        // table to display popup links
        $objTable = new htmltable();
        $objTable->cellspacing = 2;
        $objTable->cellpadding = 2;
        $objTable->startRow();
        $objTable->addCell($links, '', '', 'left', '', '');
        $objTable->addCell($logLink, '', '', 'right', '', '');
        $objTable->endRow();
        $linkTable = $objTable->show();
        $str .= $linkTable;
        
        // main chat display
        $objLayer = new layer();
        $objLayer->id = 'chatDiv';
        $objLayer->height = '300px';
        $objLayer->border = '1px solid black';
        $objLayer->overflow = 'auto';
        $chatLayer = $objLayer->show();
        $str .= $chatLayer;
        
        // caht input area
        $objText = new textarea('message');
        $chatText = $objText->show();
        
        // chat send button
        $objButton = new button('send', $sendLabel);
        $objButton->extra = ' onclick="javascript:
            jsSendChat();"';
        $sendButton = $objButton->show();
        
        // chat clear button
        $objButton = new button('clear', $clearLabel);
        $objButton->extra = 'onclick="javascript: 
            var el_Message = document.getElementById(\'input_message\');
            el_Message.value = \'\';
            el_Message.focus();
        "';
        $clearButton = $objButton->show();
        
        // main table
        $objTable = new htmltable();
        $objTable->cellspacing = '2';
        $objTable->cellpadding = '2';
        $objTable->startRow();
        $objTable->addCell($chatText, '', '', '', '' ,'');
        $objTable->addCell($sendButton.'<br />'.$clearButton, '', 'center', '', '' ,'');
        $objTable->endRow();
        $chatTable = $objTable->show();
        $string = $chatTable;
        
        // chat send form 
        $objForm = new form('chat', $this->uri(array(
            'action' => 'sendchat'
        )));
        $objForm->addToForm($string);
        $chatForm = $objForm->show();

        // main display div
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
    public function divGetOnlineUsers()
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
                // check users banned status and ban type
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
                // set up user icons
                if($isBanned){
                    // check if temp ban must be removed and do so
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
            // hidden input used for banned notice
            $objInput = new textinput('banned', $banned, 'hidden');
            $bannedInput = $objInput->show();

            // hidden input used for banned notice
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
    public function popSmileys()
    {
        // javascript
        $this->setVar('pageSuppressXML', TRUE);
        $script = '<script type="text/javaScript">
            var arrNames = new Array("alien" ,"angel", "angry", "applause", "black_eye", "bye", "cheeky", "chicken", "clown", "confused", "cool", "cowboy", "crazy", "cry", "dance_of_joy", "doh", "drool", "embarrassed", "evil", "frustrated", "grin", "hug", "hypnotised", "idea", "kiss", "laugh", "love", "nerd", "not_talking", "praying", "raise_eyebrow", "roll_eyes", "rose", "sad", "shame_on_you", "shocked", "shy", "sick", "skull", "sleeping", "smile", "straight_face", "thinking", "tired", "victory", "whistle", "wink", "worried");
            
            var arrCodes = new Array(">-)", "0:)", "X-(", "=D>", "b-(", ":\"(", ":p", "~:>", ":o)", ":-/", "B-)", "<):)", "8-}", ":((", "\\\:D/", "#-o", "=P~", ":\">", ">:)", ":-L", ":D", ">:D<", "@-)", "*-:)", ":*", ":))", ":x", ":-B", "[-(", "[-o<", "/:)", "8-|", "@};-", ":(", "[-X", ":O", ";;)", ":-&", "8-X", "I-)", ":)", ":|", ":-?", "(:|", ":)>-", ":-\"", ";)", ":-s");
            
            function jsAddSmiley(el_id)
            {
                var el_Message = opener.document.getElementById("input_message");
                for(i = 0; i <= arrNames.length-1; i++){
                        if(arrNames[i] == el_id){
                        if(el_Message.value == ""){
                            el_Message.value = arrCodes[i];
                        }else{
                            el_Message.value = el_Message.value + " " + arrCodes[i];
                        }
                    }
                }
                window.close();
                el_Message.focus();
            }
        </script>';
        $content = $script;

        // language items
        $title = $this->objLanguage->languageText('mod_messaging_wordsmileys', 'messaging');
        $smileyLabel = $this->objLanguage->languageText('mod_messaging_smileys', 'messaging');  
        $closeLabel = $this->objLanguage->languageText('mod_messaging_wordclose', 'messaging');
        $closeTitleLabel = $this->objLanguage->languageText('mod_messaging_closetitle', 'messaging');

        // list of smiley icons and their codes
        $list = array(
            'alien' => '>-)',
            'angel' => '0:)',
            'angry' => 'X-(',
            'applause' => '=D>',
            'black_eye' => 'b-(',
            'bye' => ':"(',
            'cheeky' => ':p',
            'chicken' => '~:>',
            'clown' => ':o)',
            'confused' => ':-/',
            'cool' => 'B-)',
            'cowboy' => '<):)',
            'crazy' => '8-}',
            'cry' => ':((',
            'dance_of_joy' => '\:D/',
            'doh' => '#-o',
            'drool' => '=P~',
            'embarrassed' => ':">',
            'evil' => '>:)',
            'frustrated' => ':-L',
            'grin' => ':D',
            'hug' => '>:D<',
            'hypnotised' => '@-)',
            'idea' => '*-:)',
            'kiss' => ':*',
            'laugh' => ':))',
            'love' => ':x',
            'nerd' => ':-B',
            'not_talking' => '[-(',
            'praying' => '[-o<',
            'raise_eyebrow' => '/:)',
            'roll_eyes' => '8-|',
            'rose' => '@};-',
            'sad' => ':(',
            'shame_on_you' => '[-X',
            'shocked' => ':O',
            'shy' => ';;)',
            'sick' => ':-&',
            'skull' => '8-X',
            'sleeping' => 'I-)',
            'smile' => ':)',
            'straight_face' => ':|',
            'thinking' => ':-?',
            'tired' => '(:|',
            'victory' => ':)>-',
            'whistle' => ':-"',
            'wink' => ';)',
            'worried' => ':-s',
        );
        
        // close link
        $objLink = new link('javascript:window.close();');
        $objLink->link = $closeLabel;
        $objLink->title = $closeTitleLabel;
        $closeLink = $objLink->show();
        
        // main table
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
                
                $objTable->addCell('<div id="'.$smiley.'" style="cursor: pointer;" onclick="jsAddSmiley(this.id)">'.$icon.'</div>', '12.5%', '', 'center', '', '');
                $objTable->addCell('<nobr><font class="warning"><b>'.htmlentities($code).'</b></font></nobr>', '', '', 'center', '', '');
            }
            $objTable->endRow();
        }
        $objTable->startRow();
        $objTable->addCell('<br />'.$closeLink, '', '', 'center', '', 'colspan="8"');
        $objTable->endRow();
        $smileyTable = $objTable->show();
        $string = $smileyTable;

        // feature box
        $content .= $this->objFeaturebox->show($title, $string);

        // main display div
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
    * @param string $mode: The way in which the chat must be displayed
    * @return string The template output string
    */
    public function divGetChat($mode = NULL)
    {
        // language items
        $systemLabel = $this->objLanguage->languageText('mod_messaging_wordsystem', 'messaging');
        
        // get data
        $roomId = $this->getSession('chat_room_id');
        $counter = $this->getSession('message_counter');
        $messages = $this->dbMessages->getChatMessages($roomId, $counter);
        
        // ordered list of chat messages
        $str = '';
        if($messages != FALSE){
            $str = '<ul>';
            foreach($messages as $message){
                $str .= '<li>';
                $userId = $message['sender_id'];
                if($userId == 'system'){
                    $name = $systemLabel;   
                }else{
                    if($mode == 'context'){              
                        $name = $this->objUser->username($userId);
                    }else{
                        $name = $this->objUser->fullname($userId);
                    }   
                }
                $date = $this->objDatetime->formatDate($message['date_created']);
                $str .= '<strong>['.$name;
                if($mode == 'context'){
                    $str .= ']:</strong><br />';
                }else{
                    $str .= '&nbsp;-&nbsp;'.$date.']:</strong><br />';
                }
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
    public function popBan()
    {
        // javascript to determine div height amongst other things
        $headerParams = $this->getJavascriptFile('x.js', 'htmlelements');
        $this->appendArrayVar('headerParams', $headerParams);

         // style
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

        // javascript
        $script = '<script type="text/javaScript">
            function jsBanUserList()
            {        
                var el_option = document.getElementsByName("option");
                var len = el_option.length;
                var myValue = "";
                for(var i = 0; i <= len-1; i++){
                    if(el_option[i].checked){
                        myValue = el_option[i].value;
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
        $reasonLabel = $this->objLanguage->languageText('mod_messaging_reason', 'messaging');
        $errReasonLabel = $this->objLanguage->languageText('mod_messaging_errreason', 'messaging');
                
        // heading
        $objHeader = new htmlheading();
        $objHeader->str = $banLabel;
        $objHeader->type = 1;
        $heading = $objHeader->show();

        // get data
        $roomId = $this->getSession('chat_room_id');
        
        // name/surname radio
        $objRadio = new radio('option');
        $objRadio->addOption('firstname', '&nbsp;'.$nameLabel);
        $objRadio->addOption('surname', '&nbsp;'.$surnameLabel);
        $objRadio->setBreakSpace('table');
        $objRadio->setSelected('firstname');
        $objRadio->extra = ' onchange="javascript:
            var el_Username = document.getElementById(\'input_username\');
            el_Username.value = \'\';
            el_Username.focus();"';
        $choiceRadio = $objRadio->show();
        
        // user search input
        $objInput = new textinput('username', '', '', 50);
        $objInput->extra = ' onkeyup="javascript:
            jsBanUserList()"';
        $userInput = $objInput->show();
        
        // hidden user search selection input
        $objInput = new textinput('userId', '', 'hidden', '');
        $userIdInput = $objInput->show();
        
        // user search results display div
        $objLayer = new layer();
        $objLayer->id = 'userDiv';
        $objLayer->cssClass = 'autocomplete';
        $userDiv = $objLayer->show();
        
        // user search table
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
        
        // user search feature box
        $userFeature = $this->objFeaturebox->show($userLabel, $userTable);
        
        // ban reason text area
        $objText = new textarea('reason');
        $reasonText = $objText->show();
        
        // ban reason feature box
        $reasonFeature = $this->objFeaturebox->show($reasonLabel, $reasonText);
        
        // ban type radio
        $objRadio = new radio('type');
        $objRadio->addOption(1, '&nbsp;'.$indefLabel);
        $objRadio->addOption(0, '&nbsp;'.$tempLabel);
        $objRadio->setSelected(1);
        $objRadio->setBreakSpace('<br />');
        $objRadio->extra = ' onclick="javascript:
            var el_TypeFeature = document.getElementById(\'typeFeature\');
            var el_LengthFeature = document.getElementById(\'lengthFeature\');
            var el_TypeDiv = document.getElementById(\'typeDiv\');
            var el_LengthDiv = document.getElementById(\'lengthDiv\');
            if(this.value == 0){
                el_TypeDiv.style.width = \'49%\';
                el_LengthDiv.style.width = \'49%\';
                el_LengthDiv.style.height = \'126px\';
                el_LengthDiv.style.visibility = \'visible\';
                el_LengthDiv.style.display = \'block\';
                xHeight(el_LengthFeature, xHeight(el_TypeFeature));
            }else{
                el_TypeDiv.style.width = \'100%\';
                el_LengthDiv.style.visibility = \'hidden\';
                el_LengthDiv.style.display = \'none\';
            }"';
        $typeRadio = $objRadio->show();
        
        // ban type feature box
        $this->objFeaturebox->id = 'typeFeature';
        $typeFeature = $this->objFeaturebox->show($typeLabel, $typeRadio);
        
        // ban type div
        $objLayer = new layer();
        $objLayer->id = 'typeDiv';
        $objLayer->width = '100%';
        $objLayer->floating = 'left';
        $objLayer->addToStr($typeFeature);
        $typeDiv = $objLayer->show();
        
        // ban length radio
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
        
        // ban length feature box
        $this->objFeaturebox->id = 'lengthFeature';
        $lengthFeature = $this->objFeaturebox->show($lengthLabel, $lengthDrop);

        // ban length div
        $objLayer = new layer();
        $objLayer->id = 'lengthDiv';
        $objLayer->floating = 'right';
        $objLayer->visibility = 'hidden';
        $objLayer->display = 'none';
        $objLayer->addToStr($lengthFeature);
        $lengthDiv = $objLayer->show();
        
        // ban div
        $objLayer = new layer();
        $objLayer->id = 'banDiv';
        $objLayer->addToStr($typeDiv.$lengthDiv);
        $banDiv = $objLayer->show();
        
        // submit button
        $objButton = new button('send', $submitLabel);
        $objButton->extra = ' onclick="javascript:
            var el_UserId = document.getElementById(\'input_userId\');
            var el_Username = document.getElementById(\'input_username\');
            var el_Reason = document.getElementById(\'input_reason\');
            if(el_UserId.value == \'\'){
                alert(\''.$errLabel.'\');
                el_Username.value = \'\';
                el_Username.focus();
                return false;
            }else{
                if(el_Reason.value == \'\'){
                    alert(\''.$errReasonLabel.'\');
                    el_Reason.focus();
                    return false;
                }
                document.getElementById(\'form_ban\').submit();
            }"';
        $sendButton = $objButton->show();
        
        // cancel button
        $objButton = new button('cancel', $cancelLabel);
        $objButton->extra = ' onclick="javascript:window.close()"';
        $cancelButton = $objButton->show();
        
        // button div
        $objLayer = new layer();
        $objLayer->id = 'buttonDiv';
        $objLayer->addToStr($sendButton.'&nbsp;'.$cancelButton);
        $buttonDiv = $objLayer->show();
        
        // ban form
        $objForm = new form('ban', $this->uri(array(
            'action' => 'banuser',
            'roomId' => $roomId,
        )));
        $objForm->addToForm($userFeature);
        $objForm->addToForm($reasonFeature);
        $objForm->addToForm($banDiv);
        $objForm->addToForm($buttonDiv);
        $banForm = $objForm->show();
        
        // main display div
        $objLayer = new layer();
        $objLayer->id = 'formDiv';
        $objLayer->padding = '10px';
        $objLayer->addToStr($heading.$banForm);
        $formDiv = $objLayer->show();
        $str .= $formDiv;
        
        return $str;
    }    
    
    /**
    * Method to show the search results for users to ban
    *
    * @access public
    * @param string $option: The field to search
    * @param string $value: The value to search for
    * @return string $str: The output string
    */
    public function divGetBannedUsers($option, $value)
    {
        // get data
        $roomId = $this->getSession('chat_room_id');
        $contextcode = $this->getSession('contextCode');
        $roomData = $this->dbRooms->getRoom($roomId);
        $userList = $this->dbUserlog->searchUsers($roomId, $option, $value);

        // language items
        $noMatchLabel = $this->objLanguage->languageText('mod_messaging_nomatch', 'messaging');
        
        if($userList != FALSE){
            foreach($userList as $key=>$user){
                // check if moderator of already banned
                if($roomData['room_type'] == 0){
                    $isAdmin = $this->objUser->inAdminGroup($user['user_id']);
                    if($isAdmin){
                        unset($userList[$key]);
                    }
                }elseif($roomData['room_type'] == 1){
                    if($roomData['owner_id'] == $user['user_id']){
                        unset($userList[$key]);
                    }
                }elseif($roomData['room_type'] == 2){
                    $isLecturer = $this->objUser->isContextLecturer($user['user_id'], $contextCode);
                    if($isLecturer){
                        unset($userList[$key]);
                    }
                }elseif($roomData['room_type'] == 3){
                //TODO: Once workgroups is has been ported
                }elseif(isset($user['ban_type'])){
                    unset($userList[$key]);
                }
            }    
        }
        
        // ordered list of search results
        if(!empty($userList)){
            $str = '<ul>';
            foreach($userList as $user){
                $str .= '<li onclick="javascript:
                    document.getElementById(\'input_userId\').value=\''.$user['user_id'].'\'"><strong>';
                $str .= $this->objUser->fullname($user['user_id']);
                $str .= '</strong></li>';
            }
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
    public function divBanMsg($userId)
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
    public function popConfirmBan($banType, $userId)
    {
        // language items
        $array = array(
            'name' => $this->objUser->fullname($userId),
        );        
        $tempLabel = $this->objLanguage->code2Txt('mod_messaging_confirmtemp', 'messaging', $array);
        $indefLabel = $this->objLanguage->code2Txt('mod_messaging_confirmindef', 'messaging', $array);
        $closeLabel = $this->objLanguage->languageText('mod_messaging_wordclose', 'messaging');
        $titleLabel = $this->objLanguage->languageText('mod_messaging_closetitle', 'messaging');
        
        if($banType == 1){
            $confirmLabel = $indefLabel;
        }else{
            $confirmLabel = $tempLabel;
        }

        // close link
        $objLink = new link('javascript:window.close()');
        $objLink->title = $titleLabel;
        $objLink->link = $closeLabel;
        $closeLink = $objLink->show();

        // main table
        $objTable = new htmltable();
        $objTable->cellspacing = '2';
        $objTable->cellpadding = '2';
        $objTable->startRow();
        $objTable->addCell($closeLink, '', '', 'center', '', '');
        $objTable->endRow();
        $linkTable = $objTable->show();    
        
        // confirm feature box
        $string = $this->objFeaturebox->show($confirmLabel, $linkTable);
        
        // main display div
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
    public function popUnban()
    {
        // javascript files to select all users and to sort user table
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
        
        // remove temp banned users
        if($bannedUsers != FALSE){
            foreach($bannedUsers as $key=>$user){
                if($user['ban_type'] != 1){
                    unset($bannedUsers[$key]);
                }
            }
        }
        $bannedUsers = count($bannedUsers) >= 1 ? $bannedUsers : FALSE;
        
        // select all/Deselect all links
        if($bannedUsers != FALSE){
            // select all link
            $objLink = new link('javascript:
    SetAllCheckBoxes(\'unban\',\'userId[]\',true);');
            $objLink->link = $selectLabel;
            $objLink->title = $selectTitleLabel;
            $selectLink = $objLink->show();
            
            // deselect all link
            $objLink = new link('javascript:
    SetAllCheckBoxes(\'unban\',\'userId[]\',false);');
            $objLink->link = $deselectLabel;
            $objLink->title = $deselectTitleLabel;
            $deselectLink = $objLink->show();

            $links = $selectLink.'&nbsp;|&nbsp;'.$deselectLink;

            // links table
            $objTable = new htmltable();
            $objTable->cellspacing = 2;
            $objTable->cellpadding = 2;            
            $objTable->startRow();
            $objTable->addCell($links, '', '', '', '', 'colspan="3"');
            $objTable->endRow();
            $linksTable = $objTable->show();
            $string .= $linksTable;
        }
        
        // main table
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
                
                // select user check box
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

        // submit button
        $objButton = new button('send', $submitLabel);
        $objButton->extra = ' onclick="javascript:
            var el_checkbox = document.getElementsByName(\'userId[]\');
            var myValue = false;
            for(var i = 0; i &lt; el_checkbox.length; i++){
                if(el_checkbox[i].checked == true){
                    myValue = true;
                }
            }
            if(myValue){
                document.getElementById(\'form_unban\').submit();
            }else{
                alert(\''.$errLabel.'\');
            }"';
        $sendButton = $objButton->show();
        
        // cancel button
        $objButton = new button('cancel', $cancelLabel);
        $objButton->extra = ' onclick="javascript:window.close()"';
        $cancelButton = $objButton->show();
        
        // unban form
        $objForm = new form('unban', $this->uri(array(
            'action' => 'unbanusers',
        )));
        $objForm->addToForm($usersTable);
        $objForm->addToForm('<br />'.$sendButton.'&nbsp;'.$cancelButton);
        $unbanForm = $objForm->show();
        $string .= $unbanForm;
        
        // main display div
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
    public function popConfirmUnban($users)
    {
        // get data
        $usersList = explode('|', $users);
        
        // language items
        $singleLabel = $this->objLanguage->code2Txt('mod_messaging_confirmunban1', 'messaging');
        $multipleLabel = $this->objLanguage->code2Txt('mod_messaging_confirmunban2', 'messaging');
        $closeLabel = $this->objLanguage->languageText('mod_messaging_wordclose', 'messaging');
        $titleLabel = $this->objLanguage->languageText('mod_messaging_closetitle', 'messaging');
        
        if(count($usersList) > 1){
            $confirmLabel = $multipleLabel;
        }else{
            $confirmLabel = $singleLabel;
        }
        
        // close link
        $objLink = new link('javascript:window.close()');
        $objLink->title = $titleLabel;
        $objLink->link = $closeLabel;
        $closeLink = $objLink->show();

        // main table
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

        // confirm feature box
        $string = $this->objFeaturebox->show($confirmLabel, $linkTable);
        
        // main display div
        $objLayer = new layer();
        $objLayer->addToStr($string);
        $objLayer->padding = '10px';
        $str = $objLayer->show();
        
        return $str;        
    }   

    /**
    * Method to create the content for the invite user popup
    * 
    * @access public
    * @return string The template output string
    */
    public function popInvite()
    {
        // css style
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

        // javascript
        $script = '<script type="text/javaScript">
            function jsInviteUserList()
            {        
                var el_option = document.getElementsByName("option");
                var len = el_option.length;
                var myValue = "";
                for(var i = 0; i <= len-1; i++){
                    if(el_option[i].checked){
                        myValue = el_option[i].value;
                    }
                }
                var pars = "module=messaging&action=invitelist&option="+myValue;
                new Ajax.Autocompleter("input_username", "userDiv", "index.php", {parameters: pars});
            }
        </script>';
        $str .= $script;

        // language items
        $inviteLabel = $this->objLanguage->languageText('mod_messaging_invite', 'messaging');
        $userLabel = $this->objLanguage->languageText('mod_messaging_worduser', 'messaging');
        $submitLabel = $this->objLanguage->languageText('mod_messaging_wordsubmit', 'messaging');
        $cancelLabel = $this->objLanguage->languageText('mod_messaging_wordcancel', 'messaging');
        $nameLabel = $this->objLanguage->languageText('mod_messaging_firstname', 'messaging');
        $surnameLabel = $this->objLanguage->languageText('mod_messaging_surname', 'messaging');
        $errInviteLabel = $this->objLanguage->languageText('mod_messaging_errinvite', 'messaging');
                
        // heading
        $objHeader = new htmlheading();
        $objHeader->str = $inviteLabel;
        $objHeader->type = 1;
        $heading = $objHeader->show();

        // name/surname radio
        $objRadio = new radio('option');
        $objRadio->addOption('firstname', '&nbsp;'.$nameLabel);
        $objRadio->addOption('surname', '&nbsp;'.$surnameLabel);
        $objRadio->setBreakSpace('table');
        $objRadio->setSelected('firstname');
        $objRadio->extra = ' onchange="javascript:
            var el_Username = document.getElementById(\'input_username\');
            el_Username.value = \'\';
            el_Username.focus();"';
        $choiceRadio = $objRadio->show();
        
        // user search input
        $objInput = new textinput('username', '', '', 50);
        $objInput->extra = ' onkeyup="javascript:
            jsInviteUserList()"';
        $userInput = $objInput->show();
        
        // hidden user search selection input
        $objInput = new textinput('userId', '', 'hidden', '');
        $userIdInput = $objInput->show();
        
        // search results display div
        $objLayer = new layer();
        $objLayer->id = 'userDiv';
        $objLayer->cssClass = 'autocomplete';
        $userDiv = $objLayer->show();

        // user table
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
        
        // user feature box
        $userFeature = $this->objFeaturebox->show($userLabel, $userTable);

        // submit button
        $objButton = new button('send', $submitLabel);
        $objButton->extra = ' onclick="javascript:
            var el_UserId = document.getElementById(\'input_userId\');
            var el_Username = document.getElementById(\'input_username\');
            if(el_UserId.value == \'\'){
                alert(\''.$errInviteLabel.'\');
                el_Username.value = \'\';
                el_Username.focus();
                return false;
            }else{
                document.getElementById(\'form_invite\').submit();
            }"';
        $sendButton = $objButton->show();
        
        // cancel button
        $objButton = new button('cancel', $cancelLabel);
        $objButton->extra = ' onclick="javascript:window.close()"';
        $cancelButton = $objButton->show();
        
        $objLayer = new layer();
        $objLayer->id = 'buttonDiv';
        $objLayer->addToStr($sendButton.'&nbsp;'.$cancelButton);
        $buttonDiv = $objLayer->show();

        // invite form
        $objForm = new form('invite', $this->uri(array(
            'action' => 'inviteuser',
            )));
        $objForm->addToForm($userFeature);
        $objForm->addToForm($buttonDiv);
        $inviteForm = $objForm->show();

        // main display div
        $objLayer = new layer();
        $objLayer->id = 'formDiv';
        $objLayer->padding = '10px';
        $objLayer->addToStr($heading.$inviteForm);
        $formDiv = $objLayer->show();
        $str .= $formDiv;

        return $str;
    } 
       
    /**
    * Method to show the list of users to be invited
    *
    * @access public
    * @param string $option: The field to search
    * @param string $value: The value to search for
    * @return string $str: The output string
    */
    public function divGetInviteUsers($option, $value)
    {
        // get data
        $roomId = $this->getSession('chat_room_id');
        $roomUsersList = $this->dbUsers->listRoomUsers($roomId);
        $searchList = $this->dbUsers->searchUsers($option, $value);
     
        // language items
        $noMatchLabel = $this->objLanguage->languageText('mod_messaging_nomatch', 'messaging');
        
        // check to see if user is owner or if already a member of the room
        if($searchList != FALSE){
            foreach($searchList as $key=>$user){
                foreach($roomUsersList as $roomUser){
                    if($user['userid'] == $roomUser['user_id']){
                        unset($searchList[$key]);
                    }elseif($user['userid'] == $this->userId){
                        unset($searchList[$key]);
                    }                    
                }
            }
        }

        // ordered list of search results
        if(!empty($searchList)){            
            $str = '<ul>';
            foreach($searchList as $user){
                $str .= '<li onclick="javascript:
                    document.getElementById(\'input_userId\').value=\''.$user['userid'].'\'"><strong>';
                $str .= $this->objUser->fullname($user['userid']);
                $str .= '</strong></li>';
            }           
            $str .= '</ul>'; 
        }else{
            $str = '<ul><li><strong>'.$noMatchLabel.'</strong></li></ul>';    
        }
        echo $str;        
    }

    /**
    * Method to show the confirmed invitation message
    * 
    * @access public
    * @param string $userId: The id of the user banned
    * @return string $str: The output string
    */
    public function popConfirmInvite($userId)
    {
        // language items
        $array = array(
            'user' => $this->objUser->fullname($userId),
        );        
        $confirmLabel = $this->objLanguage->code2Txt('mod_messaging_confirminvite', 'messaging', $array);
        $closeLabel = $this->objLanguage->languageText('mod_messaging_wordclose', 'messaging');
        $titleLabel = $this->objLanguage->languageText('mod_messaging_closetitle', 'messaging');
        
        // close link
        $objLink = new link('javascript:window.close()');
        $objLink->title = $titleLabel;
        $objLink->link = $closeLabel;
        $closeLink = $objLink->show();

        // close link table
        $objTable = new htmltable();
        $objTable->cellspacing = '2';
        $objTable->cellpadding = '2';
        $objTable->startRow();
        $objTable->addCell($closeLink, '', '', 'center', '', '');
        $objTable->endRow();
        $linkTable = $objTable->show();    
        
        // confirm feature box
        $string = $this->objFeaturebox->show($confirmLabel, $linkTable);
        
        // main display div
        $objLayer = new layer();
        $objLayer->addToStr($string);
        $objLayer->padding = '10px';
        $str = $objLayer->show();
        
        return $str;        
    }
    /**
    * Method to create the content for the remove user popup
    * 
    * @access public
    * @return string The template output string
    */
    public function popRemove()
    {
        // javascript files to select all users and to sort user table
        $headerParams = $this->getJavascriptFile('selectall.js', 'htmlelements');
        $this->appendArrayVar('headerParams', $headerParams);
        $headerParams = $this->getJavascriptFile('new_sorttable.js', 'htmlelements');
        $this->appendArrayVar('headerParams', $headerParams);

        // language items
        $removeLabel = $this->objLanguage->languageText('mod_messaging_remove', 'messaging');
        $noRecordsLabel = $this->objLanguage->languageText('mod_messaging_norecords', 'messaging');
        $nameLabel = $this->objLanguage->languageText('mod_messaging_firstname', 'messaging');
        $surnameLabel = $this->objLanguage->languageText('mod_messaging_surname', 'messaging');
        $selectLabel = $this->objLanguage->languageText('mod_messaging_selectall', 'messaging');
        $deselectLabel = $this->objLanguage->languageText('mod_messaging_deselectall', 'messaging');
        $selectTitleLabel = $this->objLanguage->languageText('mod_messaging_selectalltitle', 'messaging');
        $deselectTitleLabel = $this->objLanguage->languageText('mod_messaging_deselectalltitle', 'messaging');
        $errLabel = $this->objLanguage->languageText('mod_messaging_errremove', 'messaging');
        $submitLabel = $this->objLanguage->languageText('mod_messaging_wordsubmit', 'messaging');
        $cancelLabel = $this->objLanguage->languageText('mod_messaging_wordcancel', 'messaging');
        
        // heading
        $objHeader = new htmlheading();
        $objHeader->str = $removeLabel;
        $objHeader->type = 1;
        $heading = $objHeader->show();
        $string = $heading;
        
        // get data
        $roomId = $this->getSession('chat_room_id');
        $roomUsers = $this->dbUsers->listRoomUsers($roomId);

        // remove room owner
        if($roomUsers != FALSE){
            foreach($roomUsers as $key=>$user){
                if($user['user_id'] == $this->userId){
                    unset($roomUsers[$key]);
                }
            }
        }
        
        // select all/deselect all links
        if($roomUsers != FALSE){
            // select all link
            $objLink = new link('javascript:
    SetAllCheckBoxes(\'remove\',\'userId[]\',true);');
            $objLink->link = $selectLabel;
            $objLink->title = $selectTitleLabel;
            $selectLink = $objLink->show();
            
            // deselect all link
            $objLink = new link('javascript:
    SetAllCheckBoxes(\'remove\',\'userId[]\',false);');
            $objLink->link = $deselectLabel;
            $objLink->title = $deselectTitleLabel;
            $deselectLink = $objLink->show();

            $links = $selectLink.'&nbsp;|&nbsp;'.$deselectLink;

            // link table
            $objTable = new htmltable();
            $objTable->cellspacing = 2;
            $objTable->cellpadding = 2;
            $objTable->startRow();
            $objTable->addCell($links, '', '', '', '', 'colspan="3"');
            $objTable->endRow();
            $linksTable = $objTable->show();
            $string .= $linksTable;
        }
        
        // main table
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
        
        if($roomUsers == FALSE){
            $objTable->startRow();
            $objTable->addCell($noRecordsLabel, '', '', '', 'noRecordsMessage', 'colspan="3"');
            $objTable->endRow();
        }else{
            foreach($roomUsers as $user){
                $userId = $user['user_id'];
                $name = $this->objUser->getFirstname($user['user_id']);
                $surname = $this->objUser->getSurname($user['user_id']);
                
                // select user checkbox
                $objCheck = new checkbox('userId[]');
                $objCheck->setValue($userId);
                $userIdCheck = $objCheck->show();
                
                $objTable->startRow();
                $objTable->addCell($userIdCheck, '10%', '', 'center', '', '');
                $objTable->addCell($name, '45%', '', '', '', '');
                $objTable->addCell($surname, '', '', '', '', '');
                $objTable->endRow();
            }
        }
        $usersTable = $objTable->show();

        // submit button
        $objButton = new button('send', $submitLabel);
        $objButton->extra = ' onclick="javascript:
            var el_checkbox = document.getElementsByName(\'userId[]\');
            var myValue = false;
            for(var i = 0; i &lt; el_checkbox.length; i++){
                if(el_checkbox[i].checked == true){
                    myValue = true;
                }
            }
            if(myValue){
                document.getElementById(\'form_remove\').submit();
            }else{
                alert(\''.$errLabel.'\');
            }"';
        $sendButton = $objButton->show();
        
        // cancel button
        $objButton = new button('cancel', $cancelLabel);
        $objButton->extra = ' onclick="javascript:window.close()"';
        $cancelButton = $objButton->show();
        
        // remove form
        $objForm = new form('remove', $this->uri(array(
            'action' => 'removeusers',
        )));
        $objForm->addToForm($usersTable);
        $objForm->addToForm('<br />'.$sendButton.'&nbsp;'.$cancelButton);
        $unbanForm = $objForm->show();
        $string .= $unbanForm;
        
        // main display layer
        $objLayer = new layer();
        $objLayer->padding = '10px';
        $objLayer->addToStr($string);
        $str = $objLayer->show();
        
        return $str;        
    }

    /**
    * Method to show the confirmed removal message
    * 
    * @access public
    * @param string $users: The list of users removed
    * @return string $str: The output string
    */
    public function popConfirmRemove($users)
    {
        // get data
        $usersList = explode('|', $users);
        
        // language items
        $singleLabel = $this->objLanguage->code2Txt('mod_messaging_confirmremove1', 'messaging');
        $multipleLabel = $this->objLanguage->code2Txt('mod_messaging_confirmremove2', 'messaging');
        $closeLabel = $this->objLanguage->languageText('mod_messaging_wordclose', 'messaging');
        $titleLabel = $this->objLanguage->languageText('mod_messaging_closetitle', 'messaging');
        
        if(count($usersList) > 1){
            $confirmLabel = $multipleLabel;
        }else{
            $confirmLabel = $singleLabel;
        }
        
        // close link
        $objLink = new link('javascript:window.close()');
        $objLink->title = $titleLabel;
        $objLink->link = $closeLabel;
        $closeLink = $objLink->show();

        // main table
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

        // confirm feature box
        $string = $this->objFeaturebox->show($confirmLabel, $linkTable);
        
        // main display div
        $objLayer = new layer();
        $objLayer->addToStr($string);
        $objLayer->padding = '10px';
        $str = $objLayer->show();
        
        return $str;        
    }   

    /**
    * Method to show the logs template
    * 
    * @access public
    * @return string $str: The output string
    */
    public function popLogs()
    {
        // language items
        $heading = $this->objLanguage->languageText('mod_messaging_logs', 'messaging');
        $typeLabel = $this->objLanguage->languageText('mod_messaging_logtype', 'messaging');
        $completeLabel = $this->objLanguage->languageText('mod_messaging_complete', 'messaging');
        $abridgedLabel = $this->objLanguage->languageText('mod_messaging_abridged', 'messaging');
        $periodLabel = $this->objLanguage->languageText('mod_messaging_period', 'messaging');
        $startLabel = $this->objLanguage->languageText('mod_messaging_start', 'messaging');
        $endLabel = $this->objLanguage->languageText('mod_messaging_end', 'messaging');
        $submitLabel = $this->objLanguage->languageText('mod_messaging_wordsubmit', 'messaging');        
        $cancelLabel = $this->objLanguage->languageText('mod_messaging_wordcancel', 'messaging');        
        $errStartLabel = $this->objLanguage->languageText('mod_messaging_errstart', 'messaging');
        $errEndLabel = $this->objLanguage->languageText('mod_messaging_errend', 'messaging');
        $errDateLabel = $this->objLanguage->languageText('mod_messaging_errdate', 'messaging');
        
        // heading
        $objHeading = new htmlheading();
        $objHeading->str = $heading;
        $objHeading->type = 1;
        $header = $objHeading->show();
        $string = $header;
        
        // log type radio
        $objRadio = new radio('type');
        $objRadio->addOption(1, '&nbsp;'.$completeLabel);
        $objRadio->addOption(2, '&nbsp;'.$abridgedLabel);
        $objRadio->setSelected(1);
        $objRadio->setBreakSpace('<br />');
        $objRadio->extra = ' onchange="javascript:
            var el_DateDiv = document.getElementById(\'dateDiv\');
            if(this.value == 2){
                el_DateDiv.style.visibility = \'visible\';
                el_DateDiv.style.display = \'block\';
            }else{
                el_DateDiv.style.visibility = \'hidden\';
                el_DateDiv.style.display = \'none\';
            }" onsubmit="alert(\'here\');"';
        $typeRadio = $objRadio->show();
        
        // Log type featurebox
        $typeFeature = $this->objFeaturebox->show($typeLabel, $typeRadio);
        
        // date inputs
        $startField = $this->objPopupcal->show('start', 'yes', 'no', '');
        $endField = $this->objPopupcal->show('end', 'yes', 'no', '');
        
        // date table
        $objTable = new htmltable();
        $objTable->startRow();
        $objTable->addCell($startLabel);
        $objTable->addCell($startField);
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($endLabel);
        $objTable->addCell($endField);
        $objTable->endRow();
        $dateTable = $objTable->show();
        
        $periodFeature = $this->objFeaturebox->show($periodLabel, $dateTable);
        
        // date div
        $objLayer = new layer();
        $objLayer->id = 'dateDiv';
        $objLayer->visibility = 'hidden';
        $objLayer->display = 'none';
        $objLayer->addToStr($periodFeature);
        $periodLayer = $objLayer->show();

        // submit button
        $objButton = new button('send', $submitLabel);
        $objButton->extra = ' onclick="javascript:
            var el_Type = document.getElementsByName(\'type\');
            var el_InputStart = document.getElementById(\'input_start\');
            var el_InputEnd = document.getElementById(\'input_end\');
            var el_Log = document.getElementById(\'form_log\');
            var len = el_Type.length;
            if(el_Type[1].checked){
                if(el_InputStart.value == \'\'){
                    alert(\''.$errStartLabel.'\');
                }else{
                    if(el_InputEnd.value == \'\'){
                        alert(\''.$errEndLabel.'\');
                    }else{
                        var startString = el_InputStart.value;
                        var arrStartDateAndTime = startString.split(\' \');
                        var arrStartDate = arrStartDateAndTime[0].split(\'-\');
                        var arrStartTime = arrStartDateAndTime[1].split(\':\');
                        var startDate = new Date();
                        startDate.setYear(arrStartDate[0]);
                        startDate.setMonth(arrStartDate[1]-1);
                        startDate.setDate(arrStartDate[2]);
                        startDate.setHours(arrStartTime[0]-1);
                        startDate.setMinutes(arrStartTime[1]);
                        
                        var endString = el_InputEnd.value;
                        var arrEndDateAndTime = endString.split(\' \');
                        var arrEndDate = arrEndDateAndTime[0].split(\'-\');
                        var arrEndTime = arrEndDateAndTime[1].split(\':\');
                        var endDate = new Date();
                        endDate.setYear(arrEndDate[0]);
                        endDate.setMonth(arrEndDate[1]-1);
                        endDate.setDate(arrEndDate[2]);
                        endDate.setHours(arrEndTime[0]-1);
                        endDate.setMinutes(arrEndTime[1]);

                        if(endDate &lt; startDate){
                            alert(\''.$errDateLabel.'\'); 
                        }else{
                            el_Log.submit();                                                
                        }
                    }
                }                    
            }else{
                el_Log.submit();
            }"';
        $sendButton = $objButton->show();
        
        // cancel button
        $objButton = new button('cancel', $cancelLabel);
        $objButton->extra = ' onclick="javascript:window.close()"';
        $cancelButton = $objButton->show();
        
        // remove form
        $objForm = new form('log', $this->uri(array(
            'action' => 'getlog',
        )));
        $objForm->addToForm($typeFeature);
        $objForm->addToForm($periodLayer);
        $objForm->addToForm($sendButton.'&nbsp;'.$cancelButton);
        $logForm = $objForm->show();
        $string .= $logForm;
        
        // main display div
        $objLayer = new layer();
        $objLayer->addToStr($string);
        $objLayer->padding = '10px';
        $str = $objLayer->show();
        
        return $str;        
    }   
 
    /**
    * Method to show the logs template
    * 
    * @access public
    * @param string $type: The type of log to generate
    * @param string $start: The start date for an abridged log
    * @param string $end: The end date for an abridged log
    * @return string $str: The output string
    */
    public function popChatLog($type, $start = NULL, $end = NUL)
    {
        $body = 'window.resizeTo(650,650)';
        $this->appendArrayVar('bodyOnLoad', $body);
        
        // get data
        $roomId = $this->getSession('chat_room_id');
        $roomData = $this->dbRooms->getRoom($roomId);
        if($type == 1){
            $array = array(
                'name' => $roomData['room_name'],
            );
            $heading = $this->objLanguage->code2Txt('mod_messaging_logcomplete', 'messaging', $array);
            $chatData = $this->dbMessages->getChatMessages($roomId, 0);
        }else{
            $array = array(
                'name' => $roomData['room_name'],
                'start' => '<nobr>'.$this->objDatetime->formatDate($start).'</nobr>',
                'end' => '<nobr>'.$this->objDatetime->formatDate($end).'</nobr>',
            );
            $heading = $this->objLanguage->code2Txt('mod_messaging_logabridged', 'messaging', $array);
            $chatData = $this->dbMessages->getChatPeriod($roomId, $start, $end);
        }
        
        // language items
        $saveLabel = $this->objLanguage->languageText('mod_messaging_save', 'messaging');
        $saveTiteLabel = $this->objLanguage->languageText('mod_messaging_savetitle', 'messaging');
        $fileLabel = $this->objLanguage->languageText('mod_messaging_filename', 'messaging');
        $closeLabel = $this->objLanguage->languageText('mod_messaging_wordclose', 'messaging');        
        $closeTitleLabel = $this->objLanguage->languageText('mod_messaging_closetitle', 'messaging'); 
        $systemLabel = $this->objLanguage->languageText('mod_messaging_wordsystem', 'messaging');       
       
        // heading
        $objHeading = new htmlheading();
        $objHeading->str = $heading;
        $objHeading->type = 1;
        $header = $objHeading->show();
        
        // chat output
        $string = '<ul>';
        foreach($chatData as $line){
            if($line['sender_id'] == 'system'){
                $name = $systemLabel;
            }else{
                $name = $this->objUser->fullname($line['sender_id']);
            }
            $date = $this->objDatetime->formatDate($line['date_created']);
            $string .= '<li>';
            $string .= '<strong>['.$name.'&nbsp;-&nbsp;'.$date.']</strong><br />';
            $string .= nl2br($line['message']);
            $string .= '</li>';
        }
        $string .= '</ul>';
        
        $objLayer = new layer();
        $objLayer->addToStr($string);
        $objLayer->border = '1px solid black';
        $objLayer->height = '435px';
        $objLayer->overflow = 'auto';
        $chatDiv = $objLayer->show();

        $objLink = new link('javascript:window:close();');
        $objLink->link = $closeLabel;
        $objLink->title = $closeTitleLabel;
        $closeLink = $objLink->show();
        
        $objTable = new htmltable();
        $objTable->startRow();
        $objTable->addCell($closeLink, '', '', 'center', '', '');
        $objTable->endRow();
        $closeTable = $objTable->show();
        
        // main display div
        $objLayer = new layer();
        $objLayer->addToStr($header.$chatDiv.$closeTable);
        $objLayer->height = '630px';
        $objLayer->overflow = 'auto';
        $objLayer->padding = '10px';
        $str = $objLayer->show();
        
        return $str;        
    }   
}
?>