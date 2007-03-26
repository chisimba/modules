<?php
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run'])
{
	die("You cannot view this page directly");
}
// end security check

/**
* The class to display a list of online users in a block
*
* @author Kevin Cyster
*/
class block_contextchat extends object
{
    /*
    * @var object $objIcon: The geticon class in the htmlelements module
    * @access private
    */
    private $objIcon;

    /*
    * @var object $objUser: The user class in the security module
    * @access private
    */
    private $objUser;

    /*
    * @var string $userId: The user id of the current user
    * @access private
    */
    private $userId;

    /**
    * @var object $objContext: The dbcontexr class in the context module
    * @access private
    */
    private $objContext;

    /**
    * @var string $contextCode: The context code if the user is in a context
    * @access public
    */
    public $contextCode;

    /*
    * @var object $objLanguage: The language class in the language module
    * @access private
    */
    private $objLanguage;

    /**
    * @var object $dbRooms: The dbrooms class in the messaging module
    * @access private
    */
    private $dbRooms;

    /**
    * @var object $dbMessages: The dbmessages class in the messaging module
    * @access private
    */
    private $dbMessages;

    /*
    * @var string $title: The title of the block
    * @access public
    */
    public $title;

     /**
    * Constructor for the class
    * 
    * @access public
    * @return
    */
    public function init()
    {
        // load html element classes
        $this->loadClass('link', 'htmlelements');
        $this->loadClass('layer', 'htmlelements');
        $this->loadClass('textarea', 'htmlelements');
        $this->loadClass('button', 'htmlelements');
        $this->loadClass('form', 'htmlelements');
        $this->loadClass('htmltable', 'htmlelements');
        $this->objIcon = $this->getObject('geticon', 'htmlelements');

        // system classes
        $this->objUser = $this->getObject('user', 'security');
        $this->userId = $this->objUser->userId();
        $this->fullname = $this->objUser->fullname($this->userId);
        $this->username = $this->objUser->username($this->userId);
        $this->objLanguage = $this->getObject('language', 'language');
        
        // messaging classes
        $this->dbRooms = $this->getObject('dbrooms', 'messaging');
        $this->dbMessages = $this->getObject('dbmessages', 'messaging');
        
        // set up data
        $roomData = $this->dbRooms->getContextRoom();
        $this->setSession('chat_room_id', $roomData['id']);
        $counter = $this->dbMessages->getMessageCount($roomData['id']);
        $this->setSession('message_counter', $counter);
        $array = array(
            'name' => $this->fullname,
        );
        $message = $this->objLanguage->code2Txt('mod_messaging_userenter', 'messaging', $array);
        $this->dbMessages->addChatMessage($message, TRUE);
        
        // set up title
        $this->title = $roomData['room_name'];
    }

    /**
    * Method to output a block with online users
    *
    * @access public
    * @return string $str: The output string
    */
    public function show()
	{
        // add scriptaculous js libraries
        $this->objScriptaculous =& $this->getObject('scriptaculous', 'ajaxwrapper');
        $this->objScriptaculous->show();

        // add messaging module js library
        $headerParams = $this->getJavascriptFile('messaging.js', 'messaging');
        $this->appendArrayVar('headerParams', $headerParams);

        $body = 'onload="javascript:jsOnloadChat(\'context\');" onunload="javascript:clearTimeout(chatTimer);"';
        $this->setVar('bodyParams', $body);

        // language items
        $sendLabel = $this->objLanguage->languageText('mod_messaging_wordsend', 'messaging');
        $clearLabel = $this->objLanguage->languageText('mod_messaging_wordclear', 'messaging');
        $sendingLabel = $this->objLanguage->languageText('mod_messaging_sending', 'messaging');
        
        // display layer for chat
        $objLayer = new layer();
        $objLayer->id = 'chatDiv';
        $objLayer->height = '175px';
        $objLayer->background_color = 'white';
        $objLayer->border = '1px solid black';
        $objLayer->overflow = 'auto';
        $chatLayer = $objLayer->show();
        $str = $chatLayer;
        
        // chat input area
        $objText = new textarea('message', '', '', '');
        $objText->extra = ' onkeyup="javascript:jsTrapKeys(event, this.value);"';
        $chatText = $objText->show();
        
        // chat loading icon
        $this->objIcon->setIcon('loading_bar');
        $this->objIcon->extra = ' style="width: 90%"';
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
        $objTable->addCell($iconLayer.$chatText, '', '', '', '' ,'');
        $objTable->endRow();
        $objTable->startRow();
        $objTable->addCell($sendButton.'&nbsp;'.$clearButton, '', 'center', '', '' ,'');
        $objTable->endRow();
        $chatTable = $objTable->show();
        $string = $chatTable;
        
        $objLayer = new layer();
        $objLayer->id = 'sendDiv';
        $objLayer->addToStr($string);
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
}
?>