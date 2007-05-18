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
    * @var object $objTimeOut: The timeoutMessage class of the htmlelements module
    * @access private
    */
    private $objTimeOut;
     
    /**
    * @var object $objPopup: The windowpop class of the htmlelements module
    * @access private
    */
    private $objPopup;
     
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
    * @var object $objWash: The washout class in the utilities module
    * @access public
    */
    public $objWash;

    /**
    * @var object $objModule: The modules class in the modulecatalogue module
    * @access public
    */
    public $objModule;

    /**
    * @var object $dbMessaging: The dbmessaging class in the messaging module
    * @access private
    */
    private $dbMessaging;

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
        $this->loadClass('layer','htmlelements');
        $this->loadClass('iframe', 'htmlelements');
        $this->objIcon = $this->newObject('geticon', 'htmlelements');
        $this->objPopup = $this->getObject('windowpop','htmlelements');
        $this->objFeaturebox = $this->getObject('featurebox', 'navigation');
        $this->objPopupcal = $this->newObject('datepickajax', 'popupcalendar');
        $this->objTimeOut = $this->newObject('timeoutMessage','htmlelements');
        
        // syatem classes
        $this->loadClass('tabbedbox', 'htmlelements');
        $this->objLanguage = $this->getObject('language','language');
        $this->objUser = $this->getObject('user', 'security');
        $this->objDatetime = $this->getObject('dateandtime', 'utilities');
        $this->objContext = $this->getObject('dbcontext', 'context');
        $this->userId = $this->objUser->userId(); 
        $this->isLecturer = $this->objUser->isContextLecturer();  
        $this->contextCode = $this->objContext->getContextCode();
        $this->objWash = $this->getObject('washout', 'utilities');
        $this->objModule = $this->getObject('modules', 'modulecatalogue');
        
        // messaging classes     
        $this->dbMessaging = $this->getObject('dbmessaging', 'messaging');
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
        
        //$str = $this->divShowIm();
        // get data
        $rooms = $this->dbMessaging->listChatRooms($this->contextCode);
        $userRooms = $this->dbMessaging->listUserPrivateRooms($this->userId);
                
        // headings
        $objHeader = new htmlheading();
        $objHeader->str = $chatHeading;
        $objHeader->type = 1;
        $header = $objHeader->show();        
        $str = $header;
        
        $objHeader = new htmlheading();
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
                    'textOnly' => $room['text_only'],
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
                
                $activeUsers = $this->dbMessaging->listRoomUsers($room['id']);
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
        $submitLabel = $this->objLanguage->languageText('mod_messaging_createroom', 'messaging');
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
        $objHeader = new htmlheading();
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
            $roomData = $this->dbMessaging->getChatRoom($roomId);
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
        $submitButton = $objButton->show();

        // cancel button
        $objButton = new button('cancelbutton', $cancelLabel);
        $objButton->extra = ' onclick="javascript:
            $(\'form_cancelform\').submit();"';
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
            'button' => 'submit',
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
            action' => 'submitroom',
            'button' => 'cancel'
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
        $roomData = $this->dbMessaging->getChatRoom($roomId);
        
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
    * @param string $counter: The number of messages on the database
    * @return string $str The template output string
    */
    public function tplChatRoom($counter)
    {
        // language items
        $sendingLabel = $this->objLanguage->languageText('mod_messaging_sending', 'messaging');
        $sendLabel = $this->objLanguage->languageText('mod_messaging_wordsend', 'messaging');
        $clearLabel = $this->objLanguage->languageText('mod_messaging_wordclear', 'messaging');
        $inviteLabel = $this->objLanguage->languageText('mod_messaging_invite', 'messaging');
        $removeLabel = $this->objLanguage->languageText('mod_messaging_remove', 'messaging');
        $inviteTitleLabel = $this->objLanguage->languageText('mod_messaging_invitetitle', 'messaging');
        $removeTitleLabel = $this->objLanguage->languageText('mod_messaging_removetitle', 'messaging');
        $logLabel = $this->objLanguage->languageText('mod_messaging_logs', 'messaging');
        $logTitleLabel = $this->objLanguage->languageText('mod_messaging_logtitle', 'messaging');
        $windowLabel = $this->objLanguage->languageText('mod_messaging_clearwindow', 'messaging');
        $winTitleLabel = $this->objLanguage->languageText('mod_messaging_cleartitle', 'messaging');
        $progressLabel = $this->objLanguage->languageText('mod_messaging_clearloading', 'messaging');
                
        // get data
        $roomId = $this->getSession('chat_room_id');
        $roomData = $this->dbMessaging->getChatRoom($roomId);
        
        // heading
        $heading = $roomData['room_name'];
        $objHeader = new htmlheading();
        $objHeader->str = ucfirst($heading);
        $objHeader->type = 1;
        $header = $objHeader->show();        
        $str = $header;
        
        $objInput = new textinput('counter', $counter, 'hidden');
        $counterInput = $objInput->show();
        $str .= $counterInput;
        
        // banned notice div
        $objLayer = new layer();
        $objLayer->id = 'bannedDiv';
        $objLayer->padding = '10px';
        $objLayer->border = '1px solid red';
        $objLayer->display = 'none';
        $bannedLayer = $objLayer->show();
        $str .= $bannedLayer;
        
        // popup links
        // popup link to invite users
        $objPopup = new windowpop();
        $objPopup->title = $inviteTitleLabel;
        $objPopup->set('location',$this->uri(array(
            'action' => 'invitepopup',
        )));
        $objPopup->set('linktext', $inviteLabel);
        $objPopup->set('width', '500');
        $objPopup->set('height', '250');
        $objPopup->set('left', '100');
        $objPopup->set('top', '100');
        $objPopup->set('scrollbars', 'no');
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
            
        if($roomData['room_type'] == 1 && $roomData['owner_id'] == $this->userId){
            $leftLinks = $inviteLink.'&nbsp;|&nbsp;'.$removeLink;
        }else{
            $leftLinks = '';
        }
            
        $this->objIcon->setIcon('loader');
        $this->objIcon->title = $progressLabel;
        $loadingIcon = $this->objIcon->show();
        
        $objLayer = new layer();
        $objLayer->id = 'loadDiv';
        $objLayer->display = 'none';
        $objLayer->addToStr($loadingIcon);
        $loadingLayer = $objLayer->show();
        
        // link to clear window
        $objLink = new link('#');
        $objLink->extra = ' onclick="javascript:jsClearWindow();"';
        $objLink->link = $windowLabel;
        $objLink->title = $winTitleLabel;
        $clearLink = $objLink->show();
               
        // popup link to invite users
        $objPopup = new windowpop();
        $objPopup->title = $logTitleLabel;
        $objPopup->set('location',$this->uri(array(
            'action' => 'logs',
        )));
        $objPopup->set('linktext', $logLabel);
        $objPopup->set('width', '500');
        $objPopup->set('height', '240');
        $objPopup->set('left', '100');
        $objPopup->set('top', '100');
        $objPopup->set('scrollbars', 'no');
        $logLink = $objPopup->show();

        $rightLinks = $clearLink.'&nbsp;|&nbsp;'.$logLink;
        
        // table to display popup links
        $objTable = new htmltable();
        $objTable->cellspacing = 2;
        $objTable->cellpadding = 2;
        $objTable->startRow();
        $objTable->addCell('<nobr>'.$leftLinks.'</nobr>', '25%', '', 'left', '', '');
        $objTable->addCell($loadingLayer, '', '', 'right', '', '');
        $objTable->addCell('<nobr>'.$rightLinks.'</nobr>', '25%', '', 'right', '', '');
        $objTable->endRow();
        $linkTable = $objTable->show();
        $str .= $linkTable;
        
        // main chat display
        $objLayer = new layer();
        $objLayer->id = 'chatDiv';
        $objLayer->height = '300px';
        $objLayer->border = '1px solid black';
        $objLayer->padding = '10px';
        $objLayer->overflow = 'auto';
        $chatLayer = $objLayer->show();
        $str .= $chatLayer;
               
        // chat input area
        $objText = new textarea('message');
        $objText->extra = ' onkeyup="javascript:jsTrapKeys(event, this.value);"';
        $chatText = $objText->show();
        
        // chat loading icon
        $this->objIcon->setIcon('loading_bar');
        $this->objIcon->extra = 'style="width: 100%; height: 20px"';
        $this->objIcon->title = $sendingLabel;
        $sendingIcon = $this->objIcon->show();
        
        $objLayer = new layer();
        $objLayer->display = 'none';
        $objLayer->addToStr($sendingIcon);
        $objLayer->id = 'iconDiv';
        $iconLayer = $objLayer->show();
        
        // chat send button
        $objButton = new button('send', $sendLabel);
        $objButton->extra = ' onclick="javascript:jsSendMessage();"';
        $sendButton = $objButton->show();
        
        // chat clear button
        $objButton = new button('clear', $clearLabel);
        $objButton->extra = 'onclick="javascript: 
            var el_Message = $(\'input_message\');
            el_Message.value = \'\';
            el_Message.focus();
        "';
        $clearButton = $objButton->show();
        
        // main table
        $objTable = new htmltable();
        $objTable->cellspacing = '2';
        $objTable->cellpadding = '2';
        $objTable->startRow();
        $objTable->addCell($iconLayer.$chatText, '40%', '', '', '' ,'');
        $objTable->addCell($sendButton.'<br />'.$clearButton, '', 'center', 'left', '' ,'');
        $objTable->endRow();
        $chatTable = $objTable->show();
        $string = $chatTable;

        // main display div
        $objLayer = new layer();
        $objLayer->id = 'sendDiv';
        $objLayer->addToStr($chatTable);
        $sendLayer = $objLayer->show();
        $str .= $sendLayer;
        
        $objIframe = new iframe();
        $objIframe->id = 'chatIframe';
        $objIframe->frameborder = '0';
        $objIframe->height = 0;
        $objIframe->width = 0;
        $objIframe->src = $this->uri(array(
            'action' => 'chatform'
        ));
        $objIframe = $objIframe->show();
        $str .= $objIframe;

        return $str;
    }
    
    /**
    * Method to display the chat form in a hidden iframe
    *
    * @access public
    * @return string $str: The output string
    */
    public function tplChatForm()
    {
        // hidden text area
        $objText = new textarea('msg');
        $msgText = $objText->show();
        
        // hidden form
        $objForm = new form('chat', $this->uri(array(
            'action' => 'sendchat'
        )));
        $objForm->addToForm($msgText);
        $chatForm = $objForm->show();        
        $str = $chatForm;
        
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
        $indefLabel = $this->objLanguage->languageText('mod_messaging_banindef', 'messaging');
        $warnedLabel = $this->objLanguage->languageText('mod_messaging_warned', 'messaging');
        $warnLabel = $this->objLanguage->languageText('mod_messaging_warn', 'messaging');
        $banLabel = $this->objLanguage->languageText('mod_messaging_ban', 'messaging');
        $unbanLabel = $this->objLanguage->languageText('mod_messaging_unban', 'messaging');
        
        // get data
        $roomId = $this->getSession('chat_room_id');
        $isModerator = $this->getSession('is_moderator');
        $users = $this->dbMessaging->listRoomUsers($roomId);

        $banned = 'N';
        $type = '';
        $date = '';

        $objTable = new htmltable();
        $objTable->cellspacing = '2';
        $objTable->cellpadding = '2';
        if(!empty($users)){
            foreach($users as $user){
                $name = $user['firstname'].' '.$user['surname'];
                if($user['bannedid'] == NULL){                
                    $this->objIcon->setIcon('green_bullet');
                    $this->objIcon->title = $activeLabel;
                    $statusIcon = $this->objIcon->show();
                
                    if($isModerator && $user['userid'] != $this->userId){
                        // popup link for warn/ban user
                        $this->objPopup->windowPop();
                        $this->objPopup->title = $warnLabel;
                        $this->objPopup->set('location', $this->uri(array(
                            'action'=> 'banpopup',
                            'name'=> $name,
                            'userId'=> $user['userid'],
                        )));
                        $this->objPopup->set('linktext', $name);
                        $this->objPopup->set('width', '500');
                        $this->objPopup->set('height', '525');
                        $this->objPopup->set('left', '100');
                        $this->objPopup->set('top', '100');
                        $this->objPopup->set('scrollbars', 'yes');
                        $namePopup = $this->objPopup->show();
                    }else{
                        $namePopup = $name;
                    }                
                }else{
                    if($user['userid'] == $this->userId){
                        $banned = 'Y';
                        $type = $user['ban_type'];
                        $date = $user['ban_stop'];
                    }
                    if($user['ban_type'] == 2){
                        $this->objIcon->setIcon('yellow_bullet');   
                        $this->objIcon->title = $warnedLabel;
                        $statusIcon = $this->objIcon->show();    
    
                        if($isModerator && $user['userid'] != $this->userId){
                            // popup link for ban user
                            $this->objPopup->windowPop();
                            $this->objPopup->title = $banLabel;
                            $this->objPopup->set('location', $this->uri(array(
                                'action'=> 'banpopup',
                                'name'=> $name,
                                'logId'=> $user['logid'],
                                'bannedId' => $user['bannedid'],
                            )));
                            $this->objPopup->set('linktext', $name);
                            $this->objPopup->set('width', '500');
                            $this->objPopup->set('height', '505');
                            $this->objPopup->set('left', '100');
                            $this->objPopup->set('top', '100');
                            $this->objPopup->set('scrollbars', 'yes');
                            $namePopup = $this->objPopup->show();
                        }else{
                            $namePopup = $name;
                        }
                    }elseif($user['ban_type'] == 1){
                        $this->objIcon->setIcon('red_bullet');
                        $this->objIcon->title = $indefLabel;
                        $statusIcon = $this->objIcon->show();    
    
                        if($isModerator && $user['userid'] != $this->userId){
                            // popup link for warn/ban user
                            $this->objPopup->windowPop();
                            $this->objPopup->title = $unbanLabel;
                            $this->objPopup->set('location', $this->uri(array(
                                'action'=>'unbanpopup',
                                'name'=> $name,
                                'bannedId' => $user['bannedid'],
                            )));
                            $this->objPopup->set('linktext', $name);
                            $this->objPopup->set('width', '500');
                            $this->objPopup->set('height', '225');
                            $this->objPopup->set('left', '100');
                            $this->objPopup->set('top', '100');
                            $this->objPopup->set('scrollbars', 'yes');
                            $namePopup = $this->objPopup->show();
                        }else{
                            $namePopup = $name;
                        }
                    }else{
                        $array = array(
                            'date' => $this->objDatetime->formatDate($user['ban_stop']),
                        );
                        $tempLabel = $this->objLanguage->code2Txt('mod_messaging_bantemp', 'messaging', $array);
                        $this->objIcon->setIcon('red_bullet');
                        $this->objIcon->title = $tempLabel;
                        $statusIcon = $this->objIcon->show();    
                    
                        $dateNow = strtotime(date('Y-m-d H:i:s'));
                        $banStop = strtotime($user['ban_stop']);
                        if($dateNow >= $banStop){
                            $this->dbMessaging->deleteBannedUser($user['bannedid']);
                            $array = array(
                                    'user' => $name,
                            );
                            $message = $this->objLanguage->code2Txt('mod_messaging_unbantempmsg', 'messaging', $array);                    
                            $this->dbMessaging->addChatMessage($message, TRUE);             
                        }
                        $namePopup = $name;
                    }                
                }            
                $objTable->startRow();
                $objTable->addCell($namePopup);
                $objTable->addCell($statusIcon);
                $objTable->endRow();
            }
            // hidden input used for banned notice
            $objInput = new textinput('banned', $banned, 'hidden');
            $bannedInput = $objInput->show();

            // hidden input used for banned notice
            $objInput = new textinput('type', $type, 'hidden');
            $typeInput = $objInput->show();

            // hidden input used for banned notice
            $objInput = new textinput('date', $date, 'hidden');
            $dateInput = $objInput->show();

            $objTable->startRow();
            $objTable->addCell($bannedInput.$typeInput.$dateInput, '', '', '', '', ''); 
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
        // language items
        $title = $this->objLanguage->languageText('mod_messaging_wordsmileys', 'messaging');
        $smileyLabel = $this->objLanguage->languageText('mod_messaging_smileys', 'messaging');  
        $closeLabel = $this->objLanguage->languageText('mod_messaging_wordclose', 'messaging');
        $closeTitleLabel = $this->objLanguage->languageText('mod_messaging_closetitle', 'messaging');

        // list of smiley icons and their codes
        $list = array(
            'alien' => '>-)',
            'angel' => 'O:-)',
            'angry' => 'X-(',
            'applause' => '=D>',
            'black_eye' => 'b-(',
            'bye' => ':-[',
            'cheeky' => ':-P',
            'chicken' => '~:>',
            'clown' => ':o)',
            'confused' => ':-/',
            'cool' => 'B-)',
            'cowboy' => '<):-)>',
            'crazy' => '8-}',
            'cry' => ':-((',
            'dance_of_joy' => '/:-D/',
            'doh' => '#-o',
            'drool' => '=P~',
            'embarrassed' => ':"->',
            'evil' => '>:-)',
            'frustrated' => ':-L',
            'grin' => ':-D',
            'hug' => '>:-D<',
            'hypnotised' => '@-)',
            'idea' => '*-:-)',
            'kiss' => ':-*',
            'laugh' => ':-))',
            'love' => ':-x',
            'nerd' => ':-B',
            'not_talking' => '[-(',
            'praying' => '[-o<',
            'raise_eyebrow' => '/:-)',
            'roll_eyes' => '8-|',
            'rose' => '@};-',
            'sad' => ':-(',
            'shame_on_you' => '[-X',
            'shocked' => ':-O',
            'shy' => ';;-)',
            'sick' => ':-&',
            'skull' => '8-X',
            'sleeping' => 'I-)',
            'smile' => ':-)',
            'straight_face' => ':-|',
            'thinking' => ':-?',
            'tired' => '(:-|',
            'victory' => ':-)>-',
            'whistle' => ':-"',
            'wink' => ';-)',
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
                
                $objTable->addCell('<div id="'.$smiley.'" style="cursor: pointer;" onclick="jsInsertPopupSmiley(this.id)">'.$icon.'</div>', '12.5%', '', 'center', '', '');
                $objTable->addCell('<nobr>'.htmlentities($code).'</nobr>', '', '', 'center', '', '');
            }
            $objTable->endRow();
        }
        $objTable->startRow();
        $objTable->addCell('<br />'.$closeLink, '', '', 'center', '', 'colspan="8"');
        $objTable->endRow();
        $smileyTable = $objTable->show();
        $string = $smileyTable;

        // feature box
        $content = $this->objFeaturebox->show($title, $string);

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
    * @param string $counter: The number of messages on the database
    * @param string $mode: The way in which the chat must be displayed
    * @return string The template output string
    */
    public function divGetChat($counter, $mode = NULL)
    {
        // language items
        $systemLabel = $this->objLanguage->languageText('mod_messaging_wordsystem', 'messaging');
        
        // get data
        $roomId = $this->getSession('chat_room_id');
        $roomData = $this->dbMessaging->getChatRoom($roomId);
        //$counter = $this->getSession('message_counter');
        $messages = $this->dbMessaging->getChatMessages($roomId, $counter);
        
        // ordered list of chat messages
        $str = '';
        if($messages != FALSE){
//            $str = '<ul id="chat">';
            foreach($messages as $message){
//                $str .= '<li>';
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
                $str = '<strong>['.$name;
                if($mode == 'context'){
                    $str .= ']:</strong><br />';
                }else{
                    $str .= '&#160;-&#160;'.$date.']:</strong><br />';
                }
                if($roomData['text_only'] == 1){
                    $str .= nl2br($message['message']);
                }else{
                    $str .= nl2br($this->objWash->parseText($message['message']));
                }
                //$str .= nl2br($message['message']);
//                $str .= '</li>';
                $str .= '<br />';
            }
//            $str .= '</ul>';
            $count = count($messages);
        }else{
            $count = 0;
        }
        // hidden input to hold the number of list entries
        $objInput = new textinput('count', $count, 'hidden');
        $countInput = $objInput->show();
        $str .= $countInput;

        echo $str;
    }    

    /**
    * Method to create the content for the banned user popup
    * 
    * @access public
    * @param string $name: The name of the user
    * @param string $userId: The user id of the user
    * @param string $bannedId: The id of the banned user record
    * @return string The template output string
    */
    public function popBan($name, $userId, $bannedId)
    {
        // language items
        $warnLabel = $this->objLanguage->languageText('mod_messaging_warntitle', 'messaging');
        $banLabel = $this->objLanguage->languageText('mod_messaging_bantitle', 'messaging');
        $userLabel = $this->objLanguage->languageText('mod_messaging_worduser', 'messaging');
        $typeLabel = $this->objLanguage->languageText('mod_messaging_wordtype', 'messaging');
        $tempLabel = $this->objLanguage->languageText('mod_messaging_temp', 'messaging');
        $indefLabel = $this->objLanguage->languageText('mod_messaging_indefinitely', 'messaging');
        $warningLabel = $this->objLanguage->languageText('mod_messaging_warning', 'messaging');
        $lengthLabel = $this->objLanguage->languageText('mod_messaging_banlength', 'messaging');
        $submitLabel = $this->objLanguage->languageText('mod_messaging_wordsubmit', 'messaging');
        $cancelLabel = $this->objLanguage->languageText('mod_messaging_wordcancel', 'messaging');
        $reasonLabel = $this->objLanguage->languageText('mod_messaging_reason', 'messaging');
        $errBanLabel = $this->objLanguage->languageText('mod_messaging_errbanreason', 'messaging');
        $errWarnLabel = $this->objLanguage->languageText('mod_messaging_errwarnreason', 'messaging');
                
        // heading
        $objHeader = new htmlheading();
        if($bannedId != NULL){
            $objHeader->str = $banLabel;
        }else{
            $objHeader->str = $warnLabel;
        }
        $objHeader->type = 1;
        $heading = $objHeader->show();

        // user search feature box
        $userFeature = $this->objFeaturebox->show($userLabel, $name);
        
        // ban reason text area
        $objText = new textarea('reason');
        $reasonText = $objText->show();
        
        // ban reason feature box
        $reasonFeature = $this->objFeaturebox->show($reasonLabel, $reasonText);
        
        // ban type radio
        $objRadio = new radio('type');
        if($bannedId == NULL){
            $objRadio->addOption(2, '&nbsp;'.$warningLabel);
            $objRadio->setSelected(2);
        }else{            
            $objRadio->setSelected(1);            
        }
        $objRadio->addOption(1, '&nbsp;'.$indefLabel);
        $objRadio->addOption(0, '&nbsp;'.$tempLabel);
        $objRadio->setBreakSpace('<br />');
        if($bannedId == NULL){
            $objRadio->setSelected(2);
        }else{            
            $objRadio->setSelected(1);            
        }
        $objRadio->extra = ' onclick="javascript:jsBanLengthDiv(this)"';
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
        $objDrop->addOption(5, '&nbsp;5 min');
        $objDrop->addOption(10, '&nbsp;10 min');
        $objDrop->addOption(15, '&nbsp;15 min');
        $objDrop->addOption(30, '&nbsp;30 min');
        $objDrop->addOption(45, '&nbsp;45 min');
        $objDrop->addOption(60, '&nbsp;60 min');
        $objDrop->extra = ' style="width: 30%;"';
        $lengthDrop = $objDrop->show();
        
        // ban length feature box
        $this->objFeaturebox->id = 'lengthFeature';
        $lengthFeature = $this->objFeaturebox->show($lengthLabel, $lengthDrop);

        // ban length div
        $objLayer = new layer();
        $objLayer->id = 'lengthDiv';
        $objLayer->floating = 'right';
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
        $objButton->setToSubmit();
        $objButton->extra = 'onclick="javascript:return jsValidateBan(\''.$errWarnLabel.'\', \''.$errBanLabel.'\');"';
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
            'name' => $name,
            'userId' => $userId,
            'bannedId' => $bannedId,
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
        $str = $formDiv;
        
        return $str;
    }    
    
    /**
    * Method to show the banned message div
    * 
    * @access public
    * @param string $type: The type of ban
    * @param string $date: The date the temp ban expires
    * @return string $str: The output string
    */
    public function divBanMsg($type, $date)
    {
        // language items
        if($type == 2){
            $bannedLabel = $this->objLanguage->languageText('mod_messaging_iswarned', 'messaging');
        }elseif($type == 1){
            $bannedLabel = $this->objLanguage->languageText('mod_messaging_isbannedindef', 'messaging');
        }else{
            $array = array(
                'date' => $this->objDatetime->formatDate($date),
            );
            $bannedLabel = $this->objLanguage->code2Txt('mod_messaging_isbannedtemp', 'messaging', $array);
        }        
        $str = '<font class="error"><b>'.$bannedLabel.'</b></font>';       
        echo $str;        
    }

    /**
    * Method to show the confirmed banned message
    * 
    * @access public
    * @param string $banType: The type of ban selected
    * @param string $name: The name of the user banned
    * @return string $str: The output string
    */
    public function popConfirmBan($banType, $name)
    {
        // resize the window
        $body = 'window.resizeTo(500,180); setTimeout("window.close()", 6000)';
        $this->appendArrayVar('bodyOnLoad', $body);

        // language items
        $array = array(
            'name' => $name,
        );        
        $warnLabel = $this->objLanguage->code2Txt('mod_messaging_confirmwarn', 'messaging', $array);
        $tempLabel = $this->objLanguage->code2Txt('mod_messaging_confirmtemp', 'messaging', $array);
        $indefLabel = $this->objLanguage->code2Txt('mod_messaging_confirmindef', 'messaging', $array);
        $closeLabel = $this->objLanguage->languageText('mod_messaging_wordclose', 'messaging');
        $titleLabel = $this->objLanguage->languageText('mod_messaging_closetitle', 'messaging');
        
        if($banType == 2){
            $confirmLabel = $warnLabel;
        }elseif($banType == 1){
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
    * @param string $name: The name of the user to unban
    * @param string $bannedId: The id of the ban record
    * @return string The template output string
    */
    public function popUnban($name, $bannedId)
    {
        // language items
        $unbanLabel = $this->objLanguage->languageText('mod_messaging_unbantitle', 'messaging');
        $userLabel = $this->objLanguage->languageText('mod_messaging_worduser', 'messaging');
        $submitLabel = $this->objLanguage->languageText('mod_messaging_wordsubmit', 'messaging');
        $cancelLabel = $this->objLanguage->languageText('mod_messaging_wordcancel', 'messaging');
        
        // heading
        $objHeader = new htmlheading();
        $objHeader->str = $unbanLabel;
        $objHeader->type = 1;
        $heading = $objHeader->show();
        $string = $heading;
        
        // user feature box
        $userFeature = $this->objFeaturebox->show($userLabel, $name);        

        // submit button
        $objButton = new button('send', $submitLabel);
        $objButton->setToSubmit();
        $sendButton = $objButton->show();
        
        // cancel button
        $objButton = new button('cancel', $cancelLabel);
        $objButton->extra = ' onclick="javascript:window.close()"';
        $cancelButton = $objButton->show();
        
        // unban form
        $objForm = new form('unban', $this->uri(array(
            'action' => 'unbanusers',
            'bannedId' => $bannedId,
            'name' => $name,
        )));
        $objForm->addToForm($userFeature);
        $objForm->addToForm($sendButton.'&nbsp;'.$cancelButton);
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
    * @param string $name: The name of the user being unbanned
    * @return string $str: The output string
    */
    public function popConfirmUnban($name)
    {
        // resize the window
        $body = 'window.resizeTo(500,180); setTimeout("window.close()", 6000)';
        $this->appendArrayVar('bodyOnLoad', $body);

        // language items
        $closeLabel = $this->objLanguage->languageText('mod_messaging_wordclose', 'messaging');
        $titleLabel = $this->objLanguage->languageText('mod_messaging_closetitle', 'messaging');
        
        // confirm feature box
        $array = array(
            'user' => $name,
        );
        $unbanLabel = $this->objLanguage->code2Txt('mod_messaging_confirmunban', 'messaging', $array);
        
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
        $string = $this->objFeaturebox->show($unbanLabel, $linkTable);
        
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
            var el_Username = $(\'input_username\');
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
        $objButton->extra = ' onclick="javascript:return jsValidateInvite(\''.$errInviteLabel.'\')"';
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
        $roomUsersList = $this->dbMessaging->listPrivateRoomUsers($roomId);
        $searchList = $this->dbMessaging->searchUsers($option, $value);
     
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
                    $(\'input_userId\').value=\''.$user['userid'].'\'"><strong>';
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
    * @param string $userId: The id of the user invited
    * @return string $str: The output string
    */
    public function popConfirmInvite($userId)
    {
        // resize the window
        $body = 'window.resizeTo(500,200); setTimeout("window.close()", 6000)';
        $this->appendArrayVar('bodyOnLoad', $body);

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
        $roomUsers = $this->dbMessaging->listPrivateRoomUsers($roomId);

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
        $objButton->extra = ' onclick="javascript:return jsValidateRemove(\''.$errLabel.'\')"';
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
        // resize the window
        $body = 'window.resizeTo(500,300); setTimeout("window.close()", 6000)';
        $this->appendArrayVar('bodyOnLoad', $body);

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
        $objHeader = new htmlheading();
        $objHeader->str = $heading;
        $objHeader->type = 1;
        $header = $objHeader->show();
        $string = $header;
        
        // log type radio
        $objRadio = new radio('type');
        $objRadio->addOption(1, '&nbsp;'.$completeLabel);
        $objRadio->addOption(2, '&nbsp;'.$abridgedLabel);
        $objRadio->setSelected(1);
        $objRadio->setBreakSpace('<br />');
        $objRadio->extra = ' onclick="javascript:jsLogDateDiv(this);"';
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
        $objLayer->display = 'none';
        $objLayer->addToStr($periodFeature);
        $periodLayer = $objLayer->show();

        // submit button
        $objButton = new button('send', $submitLabel);
        $objButton->setToSubmit();
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
        $objForm->extra = ' onsubmit="javascript:return jsValidateDate(\''.$errStartLabel.'\', \''.$errEndLabel.'\', \''.$errDateLabel.'\')"';
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
    public function popChatLog($type, $start = NULL, $end = NUL, $mode = 'display')
    {
        if($mode == 'display'){
            $body = 'window.resizeTo(650,650)';
            $this->appendArrayVar('bodyOnLoad', $body);
        }
        
        // get data
        $roomId = $this->getSession('chat_room_id');
        $roomData = $this->dbMessaging->getChatRoom($roomId);
        if($type == 1){
            $array = array(
                'name' => $roomData['room_name'],
            );
            $heading = $this->objLanguage->code2Txt('mod_messaging_logcomplete', 'messaging', $array);
            $chatData = $this->dbMessaging->getChatMessages($roomId, 0);
        }else{
            $array = array(
                'name' => $roomData['room_name'],
                'start' => '<nobr>'.$this->objDatetime->formatDate($start).'</nobr>',
                'end' => '<nobr>'.$this->objDatetime->formatDate($end).'</nobr>',
            );
            $heading = $this->objLanguage->code2Txt('mod_messaging_logabridged', 'messaging', $array);
            $chatData = $this->dbMessaging->getChatPeriod($roomId, $start, $end);
        }
        
        // language items
        $saveLabel = $this->objLanguage->languageText('mod_messaging_save', 'messaging');
        $saveTitleLabel = $this->objLanguage->languageText('mod_messaging_savetitle', 'messaging');
        $fileLabel = $this->objLanguage->languageText('mod_messaging_filename', 'messaging');
        $closeLabel = $this->objLanguage->languageText('mod_messaging_wordclose', 'messaging');        
        $closeTitleLabel = $this->objLanguage->languageText('mod_messaging_closetitle', 'messaging'); 
        $systemLabel = $this->objLanguage->languageText('mod_messaging_wordsystem', 'messaging');       
       
        // heading
        $objHeader = new htmlheading();
        $objHeader->str = $heading;
        $objHeader->type = 3;
        $header = $objHeader->show();
        
        if($mode == 'display'){
            $objLink = new link($this->uri(array(
                'action' => 'savelog',
                'type' => $type,
                'start' => $start,
                'end' => $end,
                'mode' => 'save',
            )));
            $objLink->link = $saveLabel;
            $objLink->title = $saveTitleLabel;
            $saveLink = $objLink->show();
        }else{
            $saveLink = '';
        }
        
        // chat output
        if($chatData != FALSE){
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
                if($roomData['text_only'] == 1){
                    $string .= nl2br($line['message']);
                }else{
                    $string .= nl2br($this->objWash->parseText($line['message']));
                }
                $string .= '</li>';
            }
            $string .= '</ul>';
        }else{
            $string = '';
        }
        
        $objLayer = new layer();
        $objLayer->addToStr($string);
        $objLayer->border = '1px solid black';
        if($type == 1){
            $objLayer->height = '455px';
        }else{
            $objLayer->height = '425px';
        }
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
        $objLayer->addToStr($header.$saveLink.$chatDiv.$closeTable);
        $objLayer->height = '630px';
        $objLayer->overflow = 'auto';
        $objLayer->padding = '10px';
        $str = $objLayer->show();
        
        return $str;        
    }   

    /**
    * Method to display the IM icon in a div that send ajax requests
    *
    * @access public
    * @return string $str: The output string
    */
    public function divShowIM()
    {
        // language items
        $imLabel = $this->objLanguage->languageText('mod_messaging_im', 'messaging');
        $imTitleLabel = $this->objLanguage->languageText('mod_messaging_imtitle', 'messaging');
        
        // show only if messaging registered
        if($this->objModule->checkIfRegistered('messaging')){
            $this->objScriptaculous =& $this->getObject('scriptaculous', 'ajaxwrapper');
            $this->objScriptaculous->show();
            
            // ajax to check for IM
            $script = '<script type="text/javaScript">
                Event.observe(window, "load", jsGetIm, false);                
                var imTimer;
                function jsGetIm(){
                    var url = "index.php";
                    var pars = "module=messaging&action=getim";
                    var myAjax = new Ajax.Request(url, {method: "post", parameters: pars});
                    imTimer = setTimeout("jsGetIm()", 3000);
                }
            </script>';
            $string = $script;
            
            // IM Icon
            $this->objIcon->title = $imTitleLabel;
            $this->objIcon->setIcon('instantmessaging', 'gif', 'icons/modules');
            $imIcon = $this->objIcon->show();
                        
            // popup link to ban users
            $objPopup = new windowpop();
            //$objPopup->title = $imTitleLabel;
            $objPopup->set('location',$this->uri(array(
                'action' => 'im',
            )));
            $objPopup->set('linktext', $imIcon);
            $objPopup->set('width', '500');
            $objPopup->set('height', '450');
            $objPopup->set('left', '100');
            $objPopup->set('top', '100');
            $objPopup->set('scrollbars', 'no');
            $objPopup->putJs(); // you only need to do this once per page
            $imLink = $objPopup->show();
            $string .= $imLink;
            
            // div to float the im icon
            $objLayer = new layer();
            $objLayer->id = 'imDiv';
            $objLayer->floating = 'right';
            $objLayer->addToStr($string);
            $imDiv = $objLayer->show();
            $str = $imDiv;  
        }else{
            $str = '';
        }
        return $str;       
    }

    /**
    * Method to display the instant messaging popup
    *
    * @access public
    * @return string $str: The output string
    */
    public function popSendIM()
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
        
        // language items
        $imLabel = $this->objLanguage->languageText('mod_messaging_im', 'messaging');
        $userLabel = $this->objLanguage->languageText('mod_messaging_worduser', 'messaging');
        $submitLabel = $this->objLanguage->languageText('mod_messaging_wordsubmit', 'messaging');
        $cancelLabel = $this->objLanguage->languageText('mod_messaging_wordcancel', 'messaging');
        $usernameLabel = $this->objLanguage->languageText('word_username');
        $nameLabel = $this->objLanguage->languageText('mod_messaging_firstname', 'messaging');
        $surnameLabel = $this->objLanguage->languageText('mod_messaging_surname', 'messaging');
        $errImUserLabel = $this->objLanguage->languageText('mod_messaging_errimuser', 'messaging');
        $messageLabel = $this->objLanguage->languageText('mod_messaging_wordmessage', 'messaging');
        $settingsLabel = $this->objLanguage->languageText('mod_messaging_settings', 'messaging');
        $settingsTitleLabel = $this->objLanguage->languageText('mod_messaging_settingstitle', 'messaging');
                               
        // heading
        $objHeader = new htmlheading();
        $objHeader->str = $imLabel;
        $objHeader->type = 1;
        $heading = $objHeader->show();

        // popup link to invite users
        $objPopup = new windowpop();
        $objPopup->title = $settingsTitleLabel;
        $objPopup->set('location',$this->uri(array(
            'action' => 'imsettings',
        )));
        $objPopup->set('linktext', $settingsLabel);
        $objPopup->set('width', '500');
        $objPopup->set('height', '250');
        $objPopup->set('left', '100');
        $objPopup->set('top', '100');
        $objPopup->set('scrollbars', 'no');
        $settingsLink = $objPopup->show();

        // name/surname radio
        $objRadio = new radio('option');
        $objRadio->addOption('firstname', '&nbsp;'.$nameLabel);
        $objRadio->addOption('surname', '&nbsp;'.$surnameLabel);
        $objRadio->addOption('username', '&nbsp;'.$usernameLabel);
        $objRadio->setBreakSpace('table');
        $objRadio->setSelected('firstname');
        $objRadio->extra = ' onchange="javascript:
            var el_Username = $(\'input_value\');
            el_Username.value = \'\';
            el_Username.focus();"';
        $choiceRadio = $objRadio->show();
        
        // user search input
        $objInput = new textinput('value', '', '', 50);
        $objInput->extra = ' onkeydown="javascript:
            jsImUserList()"';
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

        // message text area
        $objText = new textarea('message');
        $objText->extra = ' style="width: 95%;"';
        $messageText = $objText->show();
        
        $messageFeature = $this->objFeaturebox->show($messageLabel, $messageText);
        
        // submit button
        $objButton = new button('send', $submitLabel);
        $objButton->extra = ' onclick="javascript:return jsValidateUser(\''.$errImUserLabel.'\')"';
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
        $objForm = new form('im', $this->uri(array(
            'action' => 'sendim',
            )));
        $objForm->addToForm($userFeature);
        $objForm->addToForm($messageFeature);
        $objForm->addToForm($buttonDiv);
        $sendForm = $objForm->show();

        // main display div
        $objLayer = new layer();
        $objLayer->id = 'mainDiv';
        $objLayer->padding = '10px';
        $objLayer->addToStr($heading.$settingsLink.$sendForm);
        $mainDiv = $objLayer->show();
        $str .= $mainDiv;

        return $str;
    } 
       
    /**
    * Method to show the list of users to send im to
    *
    * @access public
    * @param string $option: The field to search
    * @param string $value: The value to search for
    * @return string $str: The output string
    */
    public function divGetImUsers($option, $value)
    {
        // get data
        $searchList = $this->dbMessaging->searchUsers($option, $value);
     
        // language items
        $noMatchLabel = $this->objLanguage->languageText('mod_messaging_nomatch', 'messaging');
        
        // check to see if user is owner or if already a member of the room
        if($searchList != FALSE){
            foreach($searchList as $key=>$user){
                if($user['userid'] == $this->userId){
                    //unset($searchList[$key]);                                        
                }
            }
        }

        // ordered list of search results
        if(!empty($searchList)){            
            $str = '<ul>';
            foreach($searchList as $user){
                $str .= '<li onclick="javascript:
                    $(\'input_userId\').value=\''.$user['userid'].'\'"><strong>';
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
    * Method to show the confirmed instant message popup
    * 
    * @access public
    * @param string $userId: The id of the user receiving the im
    * @return string $str: The output string
    */
    public function popConfirmIm($userId)
    {
        $body = 'window.resizeTo(500,200); setTimeout("window.close()", 6000)';
        $this->appendArrayVar('bodyOnLoad', $body);

        // language items
        $array = array(
            'user' => $this->objUser->fullname($userId),
        );        
        $confirmLabel = $this->objLanguage->code2Txt('mod_messaging_confirmim', 'messaging', $array);
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
    * Method to show the instant message in a popup
    * 
    * @access public
    * @return string $str: The output string
    */
    public function popGetIM()
    {
        // get data
        $imData = $this->dbMessaging->getInstantMessage();
        
        $str = '';
        if($imData != FALSE){
            foreach($imData as $message){
                $str .= $message['message'];
            }    
        }
        return $str;
    }
    
    /**
    * Method to display the instant messaging popup
    *
    * @access public
    * @param bool $update: TRUE if the settings have been updated FALSE if not
    * @return string $str: The output string
    */
    public function popSetIM($update = FALSE)
    {
        // language items
        $imSettingsLabel = $this->objLanguage->languageText('mod_messaging_imsettings', 'messaging');
        $imLabel = $this->objLanguage->languageText('mod_messaging_im', 'messaging');
        $imTitleLabel = $this->objLanguage->languageText('mod_messaging_imtitle', 'messaging');
        $settingsLabel = $this->objLanguage->languageText('mod_messaging_imsettings', 'messaging');
        $deliveryLabel = $this->objLanguage->languageText('mod_messaging_delivery', 'messaging');
        $anyLabel = $this->objLanguage->languageText('mod_messaging_anytime', 'messaging');
        $logonLabel = $this->objLanguage->languageText('mod_messaging_logon', 'messaging');
        $timeLabel = $this->objLanguage->languageText('mod_messaging_time', 'messaging');
        $intervalLabel = $this->objLanguage->languageText('mod_messaging_interval', 'messaging');
        $submitLabel = $this->objLanguage->languageText('mod_messaging_wordsubmit', 'messaging');
        $cancelLabel = $this->objLanguage->languageText('mod_messaging_wordcancel', 'messaging');
                               
        // get data
        $settingData= $this->dbMessaging->getUserSettings();
        if(!empty($settingData)){
            $delivery = $settingData['delivery_type'];
            $interval = $settingData['time_interval'];            
        }else{
            $delivery = 0;
            $interval = '';
        }
        
        // heading
        $objHeader = new htmlheading();
        $objHeader->str = $imSettingsLabel;
        $objHeader->type = 1;
        $heading = $objHeader->show();
        
        // confirmation message
        if($update){
            $array = array(
                'date' => '<nobr>'.$this->objDatetime->formatDate(date('Y-m-d H:i:s')).'</nobr>',
            );
            $confimUpdateLabel = $this->objLanguage->code2Txt('mod_messaging_update', 'messaging', $array);
            $this->objTimeOut->init();
            $this->objTimeOut->setMessage($confimUpdateLabel);
            $this->objTimeOut->setTimeout(3000);
            $msg = '<b>'.$this->objTimeOut->show().'</b><br />';            
        }else{
            $msg = '';
        }

        // popup link to invite users
        $objPopup = new windowpop();
        $objPopup->title = $imTitleLabel;
        $objPopup->set('location',$this->uri(array(
            'action' => 'im',
        )));
        $objPopup->set('linktext', $imLabel);
        $objPopup->set('width', '500');
        $objPopup->set('height', '250');
        $objPopup->set('left', '100');
        $objPopup->set('top', '100');
        $objPopup->set('scrollbars', 'no');
        $sendLink = $objPopup->show();

        // delivery type radio
        $objRadio = new radio('delivery');
        $objRadio->addOption('0', '&nbsp;'.$anyLabel);
        $objRadio->addOption('1', '&nbsp;'.$logonLabel);
        $objRadio->addOption('2', '&nbsp;'.$timeLabel);
        $objRadio->setBreakSpace('<br />');
        $objRadio->setSelected($delivery);
        $objRadio->extra = ' onchange="javascript:jsIntervalDiv(this);"';
        $deliveryRadio = $objRadio->show();
        
        // delivery feature box
        $deliveryFeature = $this->objFeaturebox->show($deliveryLabel, $deliveryRadio);

        $objLayer = new layer();
        $objLayer->id = 'deliveryDiv';
        $objLayer->addToStr($deliveryFeature);
        $deliveryDiv = $objLayer->show();

        // interval dropdown
        $objDrop = new dropdown('interval');
        $objDrop->addOption('5', '&nbsp; 5 min');
        $objDrop->addOption('10', '&nbsp;10 min');
        $objDrop->addOption('15', '&nbsp;15 min');
        $objDrop->addOption('30', '&nbsp;30 min');
        $objDrop->addOption('45', '&nbsp;45 min');
        $objDrop->addOption('60', '&nbsp;60 min');
        $objDrop->setSelected($interval);
        $objDrop->extra = 'style="width: 20%;"';
        $intervalDrop = $objDrop->show();        
        
        // ionterval feature box
        $intervalFeature = $this->objFeaturebox->show($intervalLabel, $intervalDrop);
        
        $objLayer = new layer();
        $objLayer->id = 'intervalDiv';
        if($delivery != 2){
            $objLayer->display = 'none';
        }
        $objLayer->addToStr($intervalFeature);
        $intervalDiv = $objLayer->show();

        // submit button
        $objButton = new button('send', $submitLabel);
        $objButton->setToSubmit();
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
        $objForm = new form('im', $this->uri(array(
            'action' => 'submitsettings',
            )));
        $objForm->addToForm($deliveryDiv);
        $objForm->addToForm($intervalDiv);
        $objForm->addToForm($buttonDiv);
        $settngsForm = $objForm->show();

        // main display div
        $objLayer = new layer();
        $objLayer->id = 'mainDiv';
        $objLayer->padding = '10px';
        $objLayer->addToStr($heading.$msg.$sendLink.$settngsForm);
        $mainDiv = $objLayer->show();
        $str = $mainDiv;

        return $str;
    } 
}
?>