<?php
/**
 * tribe controller class
 *
 * Class to control the tribe module
 *
 * PHP version 5
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the
 * Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 * @category  chisimba
 * @package   tribe
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2009 Paul Scott
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version
 * @link      http://avoir.uwc.ac.za
 */

class tribe extends controller {

    public $teeny;
    public $objDbPres;
    public $objDbMsgs;
    public $dbUsers;
    public $objImView;
    public $objAt;
    public $objGroups;
    public $objMembers;
    public $postJID;

    /**
     *
     * Standard constructor method to retrieve the action from the
     * querystring, and instantiate the user and lanaguage objects
     *
     */
    public function init() {
        try {
            $this->teeny = $this->getObject ( 'tiny', 'tinyurl' );

            // Include the needed libs from resources
            include ($this->getResourcePath ( 'XMPPHP/BOSH.php', 'im' ));

            $this->objUser = $this->getObject ( 'user', 'security' );
            $this->objUserParams = $this->getObject ( 'dbuserparamsadmin', 'userparamsadmin' );
            //Create an instance of the language object
            $this->objLanguage = $this->getObject ( 'language', 'language' );
            $this->objBack = $this->getObject ( 'background', 'utilities' );
            $this->objModules = $this->getObject ( 'modules', 'modulecatalogue' );
            $this->objDbTags = $this->getObject('dbtags', 'tagging');
            $this->objDbPres = $this->getObject('dbpresence');
            $this->objDbMsgs = $this->getObject('dbmsgs');
            $this->objImView = $this->getObject('viewer');
            $this->dbUsers = $this->getObject('dbusers');
            $this->objAt = $this->getObject('dbatreplies');
            $this->objGroups = $this->getObject('dbgroups');
            $this->objMembers = $this->getObject('dbgroupmembers');

            if ($this->objModules->checkIfRegistered ( 'twitter' )) {
                // Get other places to upstream content to
                $this->objTwitterLib = $this->getObject ( 'twitterlib', 'twitter' );
            }

            // Get the sysconfig variables for the Jabber user to set up the connection.
            $this->objSysConfig = $this->getObject ( 'dbsysconfig', 'sysconfig' );
            $this->jserver = $this->objSysConfig->getValue ( 'jabberserver', 'tribe' );
            $this->jport = $this->objSysConfig->getValue ( 'jabberport', 'tribe' );
            $this->juser = $this->objSysConfig->getValue ( 'jabberuser', 'tribe' );
            $this->jpass = $this->objSysConfig->getValue ( 'jabberpass', 'tribe' );
            $this->jclient = $this->objSysConfig->getValue ( 'jabberclient', 'tribe' );
            $this->jdomain = $this->objSysConfig->getValue ( 'jabberdomain', 'tribe' );

            $this->postJID = $this->juser."@".$this->jdomain;
            $this->conn = new XMPPHP_XMPP ( $this->jserver, intval ( $this->jport ), $this->juser, $this->jpass, $this->jclient, $this->jdomain, $printlog = FALSE, $loglevel = XMPPHP_Log::LEVEL_ERROR );

        } catch ( customException $e ) {
            customException::cleanUp ();
            exit ();
        }
    }

    /**
     * Standard dispatch method to handle adding and saving
     * of comments
     *
     * @access public
     * @param void
     * @return void
     */
    public function dispatch() {
        $action = $this->getParam ( 'action' );
        switch ($action) {

//            default :
//                die ( "unknown action" );
//                break;

            case 'viewallajax' :
                $page = intval ( $this->getParam ( 'page', 0 ) );
                if ($page < 0) {
                    $page = 0;
                }
                $start = $page * 10;
                $msgs = $this->objDbMsgs->getRange($start, 10);
                $this->setVarByRef ( 'msgs', $msgs );

                header("Content-Type: text/html;charset=utf-8");
                return 'viewall_ajax_tpl.php';
                break;

            case NULL :
                $message = $this->getParam('message', NULL);
                $groupfail = $this->getParam('groupfail', NULL);
                //var_dump($groupfail); die();
                if($this->objUser->isLoggedIn()) {
                    $this->nextAction('myhome', array('message' => $message, 'groupfail' => $groupfail));
                }
                else {
                    $this->nextAction('viewall', array('message' => $message, 'groupfail' => $groupfail));
                }
                break;

            case 'viewall' :
                $groupfail = $this->getParam('groupfail', NULL);
                $message = $this->getParam('message', NULL);
                $this->setVarByRef ( 'groupfail', $groupfail );
                $this->setVarByRef ( 'message', $message );

                $count = $this->objDbMsgs->getRecordCount ();
                $pages = ceil ( $count / 10 );
                $this->setVarByRef ( 'pages', $pages );

                header("Content-Type: text/html;charset=utf-8");

                return 'viewall_tpl.php';
                break;

            case 'messagehandler':
                $this->handleMessages();
                break;

            case 'rss':
                echo $objImView->rssBox();
                break;

            case 'jbsearch':
                // search
                $term = $this->getParam('searchterm');
                $msgs = $this->objDbMsgs->keySearch($term);
                $this->setVarByRef('msgs', $msgs);

                return 'viewsearch_tpl.php';
                break;

            case 'viewmeme':
                $meme = $this->getParam('meme', NULL);
                $posts = $this->objDbTags->getPostsBySpecTag($meme, 'hashtag', 'tribe');
                foreach($posts as $post) {
                    $im = $this->objDbMsgs->getSingle($post['item_id']);
                    $msgs[] = $im[0];
                }
                $this->setVarByRef('msgs', $msgs);

                return 'viewsearch_tpl.php';
                break;

            case 'viewloc':
                $loc = $this->getParam('loc', NULL);
                $posts = $this->objDbTags->getPostsBySpecTag($loc, 'startag', 'tribe');
                foreach($posts as $post) {
                    $im = $this->objDbMsgs->getSingle($post['item_id']);
                    $msgs[] = $im[0];
                }
                $this->setVarByRef('msgs', $msgs);

                return 'viewsearch_tpl.php';
                break;

            case 'clouds':
                $cloud = $this->objImView->doTags();
                $this->setVarByRef('cloud', $cloud);
                return 'clouds_tpl.php';

                break;

            case 'sioc':
                $userid = $this->getParam('userid');
                $this->objSiocMaker = $this->getObject('siocmaker', 'siocexport');
                // site data
                $siocData = array();
			    $siocData['title'] = "Tribes";
			    $siocData['url'] = $this->uri(array('module' => 'tribe'));
			    $siocData['sioc_url'] = $this->uri(array('module' => 'tribe')).'#';
			    $siocData['encoding'] = "UTF-8";
			    $siocData['generator'] = $this->uri(array('module' => 'tribe', 'action' => 'sioc'));

			    // make the site data
			    $siteData = array();
			    $siteData['url'] = $this->uri(array('module' => 'tribe'));
			    $siteData['name'] = "Tribes";
			    $siteData['description'] = ''; //$this->objSysConfig->getValue ( 'jposterprofile', 'tribe' );

			    $fora = array();
			    $fora[0]['id'] = $userid;
			    $fora[0]['url'] = $this->uri(array('module' => 'tribe', 'userid' => $userid));

			    $users = array();
			    $user[0]['id'] = $userid;
			    $user[0]['url'] = $this->uri('');

			    $this->objSiocMaker->setSite($siteData);
			    $this->objSiocMaker->setFora($fora);
			    $this->objSiocMaker->setUsers($users);

			    $this->objSiocMaker->createForum($userid, $this->uri(array('module' => 'tribe', 'userid' => $userid)), $userid, 'Tribes', $this->objSysConfig->getValue ( 'jposterprofile', 'tribe' ));

			    $posts = $this->objDbMsgs->getAllPosts();

			    foreach($posts as $post) {
			        $p[] =  array('id' => $post['id'], 'url' => $this->uri ( array ('postid' => $post['id'], 'action' => 'viewsingle' ) ));
			    }
			    $this->objSiocMaker->forumPosts($p);

			    // user
			    $user = array();
			    $user['id'] = $userid;
			    $user['url'] = $this->uri('');
			    $user['name'] = $this->objUser->userName($userid);
			    $user['email'] = $this->objUser->email();
			    $user['homepage'] = $this->uri('');
			    $user['role'] = "Admin";

			    $this->objSiocMaker->createUser($user);

			    // posts
			    foreach($posts as $post) {
			        // get the tags for this post (meme)
			        $tags = $this->objDbTags->getPostTags($post['id'], 'tribe');
                    $this->objSiocMaker->createPost($this->uri ( array ('postid' => $post['id'], 'action' => 'viewsingle' ) ),
                                                    $post['msgtype'], strip_tags($post['msgbody']), $post['msgbody'], $post['datesent'],
                                                    $updated = "",
                                                    $tags,
                                                    $links = array()
                                                    );
			    }

			    echo $this->objSiocMaker->dumpSioc($siocData);
			    break;

            case 'addjid':
                $jid = $this->getParam('jid');
                $mode = $this->getParam('mode', NULL);
                if($mode == NULL) {
                    $this->dbUsers->addRecord($this->objUser->userid(), $jid);
                }
                else {
                    $this->dbUsers->updateJid($this->objUser->userId(), $jid);
                }
                $this->popMessage($jid, 'registerjid', NULL);
                $this->nextAction('');

                break;

            case 'changejid' :
                echo $this->objImView->showSignupBox('update');
                break;

            case 'addjidajax' :
                echo $this->objImView->showSignupBox('ajax');
                break;

            case 'myhome':
                $groupfail = $this->getParam('groupfail', NULL);
                $message = $this->getParam('message', NULL);
                $this->setVarByRef ( 'groupfail', $groupfail );
                $this->setVarByRef ( 'message', $message );
                $user = $this->getParam('user', $this->objUser->userName());
                $data = $this->objUser->lookupData($user);
                // get the posts of the user
                $userid = $data['userid'];

                $count = $this->objDbMsgs->getUserRecordCount ($userid);
                if($count == 0) {
                    // the user may be a group
                    $count = $this->objDbMsgs->getGroupRecordCount($user);
                    $groupname = $user;
                    $this->setVarByRef('groupname', $groupname);
                    // TODO: add in the group logic here
                }
                $pages = ceil ( $count / 10 );
                $this->setVarByRef ( 'pages', $pages );
                $this->setVarByRef ( 'userid', $userid );

                header("Content-Type: text/html;charset=utf-8");

                return 'viewuser_tpl.php';
                break;

            case 'viewuserajax' :
                $userid = $this->getParam('userid');
                $groupname = $this->getParam('groupname', NULL);
                $page = intval ( $this->getParam ( 'page', 0 ) );
                if ($page < 0) {
                    $page = 0;
                }
                $start = $page * 10;
                $msgs = $this->objDbMsgs->getUserRange($start, 10, $userid, $groupname);
                $this->setVarByRef ( 'msgs', $msgs );

                header("Content-Type: text/html;charset=utf-8");
                return 'viewuser_ajax_tpl.php';
                break;

            case 'creategroup' :
                $groupname = $this->getParam('groupname');
                $privacy = $this->getParam('privacy', 'public');
                // we need to do some validation here so that group names are kept sane. Validation on client side seems
                // busted when used in conjunction with the facebox thingy
                $result = ereg ("^[A-Za-z0-9]+$", $groupname );

                if($result == FALSE) {
                    // we have a space or something nasty in the groupname
                    $groupfail = 'TRUE';
                    $message = $this->objLanguage->languageText("mod_tribe_invalidgroupname", "tribe");
                    $this->setVarByRef('message', $message);
                    $this->setVarByRef('groupfail', $groupfail);
                    $this->nextAction('', array('message' => $message, 'groupfail' => $groupfail));
                    break;
                }
                $jid = $this->dbUsers->getJidfromUserId($this->objUser->userId());
                $insarr = array('groupname' => $groupname, 'privacy' => $privacy);
                $code = $this->objGroups->addRecord($insarr, $jid);
                if($code === 1) {
                    log_debug("only valid users allowed to create groups");
                    $groupfail = 'TRUE';
                    $message = $this->objLanguage->languageText("mod_tribe_onlyvalidusersgroup", "tribe");
                    $this->setVarByRef('message', $message);
                    $this->setVarByRef('groupfail', $groupfail);
                    $this->nextAction('', array('message' => $message, 'groupfail' => $groupfail));
                }
                elseif($code === 2) {
                    log_debug("A Group by the name $groupname already exists");
                    $groupfail = 'TRUE';
                    $message = $this->objLanguage->languageText("mod_tribe_groupexists", "tribe");
                    $this->setVarByRef('message', $message);
                    $this->setVarByRef('groupfail', $groupfail);
                    $this->nextAction('', array('message' => $message, 'groupfail' => $groupfail));
                }
                elseif($code === 3) {
                    $groupfail = 'FALSE';
                    log_debug("Group $groupname has been created");
                    $this->popMessage($jid, 'groupcreated', $groupname);
                    // create a system user so that we don't get a user conflict with groups
                    // This is a crappy way of doing it, but best I can come up with for now... Ideas welcome!
                    $objUA = $this->getObject('useradmin_model2', 'security');
                    $objUA->addUser($objUA->generateUserId(), $groupname, rand(0, 56000), 'mr', $groupname, $groupname, 'fake@tribemodule.chisimba', 'M', "ZA", '', '', 'useradmin', '0');
                    $message = $this->objLanguage->languageText("mod_tribe_yourgroup", "tribe")." ".$groupname." ".$this->objLanguage->languageText("mod_tribe_hasbeencreated", "tribe");
                    $this->setVarByRef('message', $message);
                    $this->setVarByRef('groupfail', $groupfail);
                    $this->nextAction('',array('message' => $message, 'groupfail' => $groupfail));
                }
                else {
                    die("unknown code");
                }
                break;

            case 'creategrpform' :
                echo $this->objImView->createGroupBox();
                break;

            case 'leavegroup' :
                $userid = $this->objUser->userId();
                $groupid = $this->getParam('groupid');
                $this->objMembers->removePerson($userid, $groupid);

                $this->nextAction('');
                break;

            case 'joingroup' :
                // join  a group
                $groupid = $this->getParam('groupid');
                $userid = $this->objUser->userId();
                $jid  = $this->dbUsers->getJidfromUserId($userid);
                $this->objMembers->addRecord($userid, $groupid, $jid);

                $this->nextAction('');
                break;

            case 'deletegroup' :


                break;

            case 'mygroups' :

                break;

            case 'bigdata' :

                $x = 0;
                $end = 2000000;
                while($x < $end) {
                    $users = array('1', '247703', '9679090513', '3458090513');
                    $randid = array_rand($users, 1);
                    $tim = $this->objDbMsgs->now();
                    $datarr = array('userid' => $users[$randid], 'msgtype' => 'chat', 'msgfrom' => 'test', 'msgbody' => "Numba: ".rand(0, 100000), 'datesent' => $tim);
                    $this->objDbMsgs->insert ( $datarr, 'tbl_tribe_msgs' );
                    echo $x."... <br />";
                    $x++;

                }
                die();

            default :
                die ( "unknown action" );
                break;
        }
    }

    /**
     * Overide the login object in the parent class
     *
     * @param  void
     * @return bool
     * @access public
     */
    public function requiresLogin($action) {
        return FALSE;
    }

    public function handleMessages() {
        log_debug("Starting messagehandler");


        // This is a looooong running task... Lets use the background class to handle it
        //check the connection status
        $status = $this->objBack->isUserConn ();
        //keep the user connection alive even if the browser is closed
        $callback = $this->objBack->keepAlive ();


        // Now the code is backrounded and cannot be aborted! Be careful now...
        $this->conn->autoSubscribe ();
        try {
            $this->conn->connect ();
            while ( ! $this->conn->isDisconnected () ) {
                $payloads = $this->conn->processUntil ( array ('message', 'presence', 'end_stream', 'session_start', 'reply' ) );
                foreach ( $payloads as $event ) {
                    $pl = $event [1];

                    switch ($event [0]) {
                        case  'reply':
                            log_debug("reply to message...");
                            //log_debug($pl);
                            break;

                        case 'message' :
                            switch ($pl ['body']) {
                                // administrative functions that only the owner should be able to do
                                case 'quit' :
                                    $poster = explode('/', $pl['from']);
                                    $poster = $poster[0];
                                    $this->conn->disconnect ();
                                    die();
                                    break;

                                case 'break' :
                                    $poster = explode('/', $pl['from']);
                                    $poster = $poster[0];
                                    $this->conn->send ( "</end>" );
                                    break;

                                case 'subscribe':
                                    $poster = explode('/', $pl['from']);
                                    $poster = $poster[0];
                                    //$this->objDbSubs->addRecord($poster);
                                    // send a message saying that you are now subscribed back
                                    $this->conn->message($pl['from'], $this->objLanguage->languageText('mod_tribe_subscribed', 'tribe'));
                                    continue;

                                case 'unsubscribe':
                                    $poster = explode('/', $pl['from']);
                                    $poster = $poster[0];
                                    // remove the JID to the subscribers table
                                    //$this->objDbSubs->inactiveRecord($poster);
                                    // send a message saying that you are now unsubscribed back
                                    $this->conn->message($pl['from'], $this->objLanguage->languageText('mod_tribe_unsubscribed', 'tribe'));
                                    continue;

                                case 'NULL' :
                                    continue;
                            }
                            // Send a response message
                            if ($pl ['body'] != "" && $pl ['body'] != "subscribe" && $pl ['body'] != "unsubscribe" && $pl ['body'] != "quit" && $pl ['body'] != "break") {
                                // Bang the array into a table to keep a record of it.
                                $poster = explode('/', $pl['from']);
                                $poster = $poster[0];

                                // send a message to the poster
                                $this->conn->message($pl['from'], $this->objLanguage->languageText('mod_tribe_msgadded', 'tribe'));
                                // check for any @user tags and send the message to them too
                                $fwd = $this->objImView->getAtTagsArr($pl['body']);
                                if(is_array($fwd)) {
                                    foreach($fwd as $f) {
                                        // check if the @ tag is a group name
                                        if($this->objGroups->groupExists($f)) {
                                            // get the group info and id
                                            $info = $this->objGroups->getGroupInfo($f);
                                            $add = $this->objDbMsgs->addRecord ( $pl, $info['groupname'] );
                                            // get the userid of all members
                                            $list = $this->objMembers->getAllUsers($info['id']);
                                            foreach($list as $member) {
                                                // send on the message
                                                $memberjid = $this->dbUsers->getJidFromUserId($member['userid']);
                                                if($memberjid != NULL) {
                                                    $poster = $this->dbUsers->getUsernamefromJid($poster);
                                                    $this->conn->message($memberjid, "@".$poster." says: ".$pl['body']);
                                                }
                                            }
                                        }
                                        else {
                                            $add = $this->objDbMsgs->addRecord ( $pl );
                                            // @ user is not a group, just a regular person
                                            $uid = $this->dbUsers->getJidfromUsername($f);
                                            $toid = $this->dbUsers->getUserIdfromJid($uid);
                                            $fromid = $this->dbUsers->getUserIdfromJid($poster);
                                            $atarr = array('toid' => $toid, 'fromid' => $fromid, 'msgid' => $add, 'tribegroup' => '');
                                            // add the at reply to the atreplies table
                                            $this->objAt->addRecord($atarr);
                                            // send to user
                                            if($uid != NULL) {
                                                $poster = $this->dbUsers->getUsernamefromJid($poster);
                                                $this->conn->message($uid, "@".$poster." says: ".$pl['body']);
                                            }
                                            else {
                                                $this->conn->message($pl['from'], "Invalid user!");
                                            }
                                        }
                                    }
                                }
                            }
                            break;

                        case 'presence' :
                            // Update the table presence info
                            $this->objDbPres->updatePresence ( $pl );
                            break;
                        case 'session_start' :
                            $this->conn->getRoster ();
                            $this->conn->presence ( $status = $this->objLanguage->languageText ( 'mod_im_presgreeting', 'im' ) );
                            break;

                    }
                }
            }
        } catch ( customException $e ) {
            customException::cleanUp ();
            exit ();
        }
        // OK something went wrong, make sure the sysadmin knows about it!
        $email = $this->objConfig->getsiteEmail ();
        $call2 = $this->objBack->setCallBack ( $email, $this->objLanguage->languageText ( 'mod_im_msgsubject', 'im' ), $this->objLanguage->languageText ( 'mod_im_callbackmsg', 'im' ) );
        break;

    }

    public function  popMessage($jid, $type, $groupname) {
        $conn = new XMPPHP_XMPP ( $this->jserver, intval ( $this->jport ), $this->juser, $this->jpass, $this->jclient, $this->jdomain, $printlog = FALSE, $loglevel = XMPPHP_Log::LEVEL_ERROR );
        if($type == 'groupcreated') {
            $msg = $this->objLanguage->languageText("mod_tribe_yourgroup", "tribe")." ".$groupname." ".$this->objLanguage->languageText("mod_tribe_hasbeencreated", "tribe");
        }

        if($type == 'registerjid') {
            $msg = $this->objLanguage->languageText("mod_tribe_jidregistered", "tribe")." ".$this->postJID;
        }

        $conn->connect();
        $conn->processUntil('session_start');
        $conn->presence();
        $conn->message($jid, $msg);
        $conn->disconnect();

        return;
    }
}
?>