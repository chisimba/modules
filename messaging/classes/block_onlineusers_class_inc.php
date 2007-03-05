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
class block_onlineusers extends object
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

   /*
    * @var object $objLanguage: The language class in the language module
    * @access private
    */
    private $objLanguage;

   /*
    * @var object $dbRooms: The dbrooms database class in the messgaing module
    * @access private
    */
    private $dbRooms;

    /*
    * @var string $title: The title of the block
    * @access public
    */
    public $title;

    /*
    * @var boolean $moderator: TRUE if the user is a moderator for this chat room
    * @access private
    */
    private $moderator;

     /**
    * Constructor for the class
    * 
    * @access public
    * @return
    */
    public function init()
    {
        // load html element classes
        $this->loadClass('layer', 'htmlelements');
        $this->loadClass('windowpop','htmlelements');
        $this->objIcon = $this->getObject('geticon', 'htmlelements');

        // system classes
        $this->objUser = $this->getObject('user', 'security');
        $this->userId = $this->objUser->userId();
        $this->objLanguage = $this->getObject('language', 'language');
        
        // messaging classes
        $this->dbRooms = $this->getObject('dbrooms', 'messaging');

        // language elements
        $title = $this->objLanguage->languageText('mod_messaging_userschatting', 'messaging');
        $label = $this->objLanguage->languageText('mod_messaging_userlist', 'messaging');  
        $help = $this->objLanguage->languageText('mod_messaging_helpclick', 'messaging');
        $listLabel = $this->objLanguage->languageText('mod_messaging_list', 'messaging');
              
        //Set moderator status
        $roomId = $this->getSession('chat_room_id');
        
        $roomData = $this->dbRooms->getRoom($roomId);
        $this->moderator = FALSE;
        if($roomData['room_type'] == 0){
            $isAdmin = $this->objUser->inAdminGroup($this->userId);
            if($isAdmin){
                $this->moderator = TRUE;
            }
        }elseif($roomData['room_type'] == 1){
            if($roomData['owner_id'] == $this->userId){
                $this->moderator = TRUE;
            }
        }elseif($roomData['room_type'] == 2){
            $isLecturer = $this->objUser->isContextLecturer();
            if($isLecturer){
                $this->moderator = TRUE;
            }
        }elseif($roomData['room_type'] == 3){
            //to do once workgroups is ported
        }
        
        // set up help icon
        $this->objIcon->setIcon('help_small');
        $this->objIcon->align = 'top';
        $this->objIcon->title = $help;
        if($this->moderator){
            $this->objIcon->extra = ' onclick="javascript:
                alert(\''.$label.' '.$listLabel.'\')"';
        }else{
            $this->objIcon->extra = ' onclick="javascript:
                alert(\''.$label.'\')"';            
        }
        $helpIcon = '<a href="#">'.$this->objIcon->show().'</a>';
        
        // set up title
        $this->title = $title.'&nbsp;'.$helpIcon;
    }

    /**
    * Method to output a block with information on how help works
    *
    * @access public
    * @return string $str: The output string
    */
    public function show()
	{
        // javascript
        $script = '<script type="text/javaScript">
            Event.observe(window, "load", init_users, false);
            var formDiv = document.getElementById("formDiv");
            var bannedDiv = document.getElementById("bannedDiv");
            hide_div(bannedDiv);
            
            function init_users(){
                var url = "index.php";
                var target = "listDiv";
                var pars = "module=messaging&action=getusers";
                var myAjax = new Ajax.Updater(target, url, {method: "get", parameters: pars, onComplete: users_timer});
            }

            function users_timer(){
                var bannedEl = document.getElementById("input_banned");
                if(bannedEl.value == "Y"){
                    var useridEl = document.getElementById("input_userId");
                    
                    var url = "index.php";
                    var target = "bannedDiv";
                    var pars = "module=messaging&action=getbanmsg&userId="+useridEl.value;
                    var myAjax = new Ajax.Updater(target, url, {method: "get", parameters: pars});
                    show_div(bannedDiv);
                    hide_div(formDiv);
                }else{
                    hide_div(bannedDiv);
                    show_div(formDiv);
                }                
                setTimeout("init_users()", 3000);
            }
            
            function show_div(myDiv){
                myDiv.style.visibility = "visible";
                myDiv.style.display = "block";
            }
            
            function hide_div(myDiv){
                myDiv.style.visibility = "hidden";
                myDiv.style.display = "none";
            }
        </script>';
        $str = $script;

        // language items
        $banLabel = $this->objLanguage->languageText('mod_messaging_ban', 'messaging');
        $unbanLabel = $this->objLanguage->languageText('mod_messaging_unban', 'messaging');
        $banMsgLabel = $this->objLanguage->languageText('mod_messaging_banmsg', 'messaging');
        $unbanMsgLabel = $this->objLanguage->languageText('mod_messaging_unbanmsg', 'messaging');
        
        // popup link to ban users
        $objPopup = new windowpop();
        $objPopup->title = $banMsgLabel;
        $objPopup->set('location',$this->uri(array(
            'action' => 'banpopup',
        )));
        $objPopup->set('linktext', $banLabel);
        $objPopup->set('width', '500');
        $objPopup->set('height', '520');
        $objPopup->set('left', '100');
        $objPopup->set('top', '100');
        $objPopup->set('scrollbars', 'no');
        $objPopup->putJs(); // you only need to do this once per page
        $banLink = $objPopup->show();

        // popup link to unban users
        $objPopup = new windowpop();
        $objPopup->title = $unbanMsgLabel;
        $objPopup->set('location',$this->uri(array(
            'action' => 'unbanpopup',
        )));
        $objPopup->set('linktext', $unbanLabel);
        $objPopup->set('width', '500');
        $objPopup->set('height', '520');
        $objPopup->set('left', '100');
        $objPopup->set('top', '100');
        $objPopup->set('scrollbars', 'no');
        $unbanLink = $objPopup->show();
        
        if($this->moderator){
            $str .= $banLink.'&nbsp;|&nbsp;'.$unbanLink.'<br />';
        }
        
        // display layer for online user list
        $objLayer = new layer();
        $objLayer->id = 'listDiv';
        $userLayer = $objLayer->show();
        $str .= $userLayer;
        
        return $str;
    }
}
?>