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

            case 'viewall' :
            case NULL :
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
                $msgs = $this->objDbIm->keySearch($term);
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
                $posts = $this->objDbTags->getPostsBySpecTag($loc, 'attag', 'tribe');
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
			    $siteData['description'] = ''; //$this->objSysConfig->getValue ( 'jposterprofile', 'jabberblog' );

			    $fora = array();
			    $fora[0]['id'] = $userid;
			    $fora[0]['url'] = $this->uri(array('module' => 'tribe', 'userid' => $userid));

			    $users = array();
			    $user[0]['id'] = $userid;
			    $user[0]['url'] = $this->uri('');

			    $this->objSiocMaker->setSite($siteData);
			    $this->objSiocMaker->setFora($fora);
			    $this->objSiocMaker->setUsers($users);

			    $this->objSiocMaker->createForum($userid, $this->uri(array('module' => 'tribe', 'userid' => $userid)), $userid, 'Tribes', $this->objSysConfig->getValue ( 'jposterprofile', 'jabberblog' ));

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
                $this->dbUsers->addRecord($this->objUser->userid(), $jid);
                $this->nextAction('');

                break;

            case 'myhome':
                $user = $this->getParam('user', null);
                $data = $this->objUser->lookupData($user);
                // get the posts of the user
                $userid = $data['userid'];

                $count = $this->objDbMsgs->getUserRecordCount ($userid);
                $pages = ceil ( $count / 10 );
                $this->setVarByRef ( 'pages', $pages );
                $this->setVarByRef ( 'userid', $userid );

                header("Content-Type: text/html;charset=utf-8");

                return 'viewuser_tpl.php';
                break;

            case 'viewuserajax' :
                $userid = $this->getParam('userid');
                $page = intval ( $this->getParam ( 'page', 0 ) );
                if ($page < 0) {
                    $page = 0;
                }
                $start = $page * 10;
                $msgs = $this->objDbMsgs->getUserRange($start, 10, $userid);
                $this->setVarByRef ( 'msgs', $msgs );

                header("Content-Type: text/html;charset=utf-8");
                return 'viewuser_ajax_tpl.php';
                break;

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

        /*
        // This is a looooong running task... Lets use the background class to handle it
        //check the connection status
        $status = $this->objBack->isUserConn ();
        //keep the user connection alive even if the browser is closed
        $callback = $this->objBack->keepAlive ();
        */

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
                            log_debug($pl);
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
                                    $this->conn->message($pl['from'], $this->objLanguage->languageText('mod_jabberblog_subscribed', 'tribe'));
                                    continue;

                                case 'unsubscribe':
                                    $poster = explode('/', $pl['from']);
                                    $poster = $poster[0];
                                    // remove the JID to the subscribers table
                                    //$this->objDbSubs->inactiveRecord($poster);
                                    // send a message saying that you are now unsubscribed back
                                    $this->conn->message($pl['from'], $this->objLanguage->languageText('mod_jabberblog_unsubscribed', 'tribe'));
                                    continue;

                                case 'NULL' :
                                    continue;
                            }
                            // Send a response message
                            if ($pl ['body'] != "" && $pl ['body'] != "subscribe" && $pl ['body'] != "unsubscribe" && $pl ['body'] != "quit" && $pl ['body'] != "break") {
                                // Bang the array into a table to keep a record of it.
                                $poster = explode('/', $pl['from']);
                                $poster = $poster[0];
                                $add = $this->objDbMsgs->addRecord ( $pl );
                                // send a message to the poster
                                $this->conn->message($pl['from'], $this->objLanguage->languageText('mod_jabberblog_msgadded', 'jabberblog'));
                                // check for any @user tags and send the message to them too
                                $fwd = $this->objImView->getAtTagsArr($pl['body']);
                                if(is_array($fwd)) {
                                    foreach($fwd as $f) {
                                        // lookup user jid and userid etc
                                        $uid = $this->dbUsers->getJidfromUsername($f);
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

                                /* send out a mass message to the subscribers
                                $active = $this->objDbSubs->getActive();
                                foreach ($active as $user) {
                                    $this->conn->message($user['jid'], $pl['body']." ".$this->uri(''));
                                } */
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
        //$call2 = $this->objBack->setCallBack ( $email, $this->objLanguage->languageText ( 'mod_im_msgsubject', 'im' ), $this->objLanguage->languageText ( 'mod_im_callbackmsg', 'im' ) );
        break;

    }
}
?>