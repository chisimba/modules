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
    public $userId;

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
    * @access public
    */
    public $moderator;

     /**
    * Constructor for the class
    */
    public function init()
    {
        // load html element classes
        $this->loadClass('layer', 'htmlelements');
        $this->loadClass('link', 'htmlelements');
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
        $banLabel = $this->objLanguage->languageText('mod_messaging_list', 'messaging');
              
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
                alert(\''.$label.' '.$banLabel.'\')"';
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
    */
    public function show()
	{
        $script = '<script type="text/javaScript">
            Event.observe(window, "load", init_users, false);
    
            function init_users(){
                var url = "index.php";
                var target = "listDiv";
                var pars = "module=messaging&action=getusers&moderator='.$this->moderator.'";
                var myAjax = new Ajax.Updater(target, url, {method: "get", parameters: pars, onComplete: users_timer});
            }

            function users_timer(){
                setTimeout("init_users()", 5000);
            }
            
            function unban_user(bannedId){
                var url = "index.php";
                var pars = "module=messaging&action=unbanuser&bannedId=" + bannedId;
                var myAjax = new Ajax.Request(url, {method: "get", parameters: pars});
            }
        </script>';
        $str = $script;

        $objLayer = new layer();
        $objLayer->id = 'listDiv';
        $userLayer = $objLayer->show();
        $str .= $userLayer;
        
        return $str;
    }
}
?>