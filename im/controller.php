<?php
//ini_set('error_reporting', 'E_ALL & ~E_NOTICE');
/**
 * IM controller class
 *
 * Class to control the IM module
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
 * @package   im
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2008 Paul Scott
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za
 * @see       xmpphp
 */

class im extends controller
{

    public $objImOps;
    public $objUser;
    public $objUserParams;
    public $userJid;
    public $objLanguage;
    public $objBack;

    public $objDbIm;
    public $conn;
    public $objDbImPres;

    public $objTwitterLib = NULL;

    public $objSysConfig;
    public $jserver;
    public $jport;
    public $juser;
    public $jpass;
    public $jclient;
    public $jdomain;

    public $objModules;

    /**
    *
    * Standard constructor method to retrieve the action from the
    * querystring, and instantiate the user and lanaguage objects
    *
    */
    public  function init()
    {
        try {
            // Include the needed libs from resources
            include($this->getResourcePath('XMPPHP/XMPP.php'));
            include($this->getResourcePath('XMPPHP/XMPPHP_Log.php'));
            $this->objImOps = $this->getObject('imops');
            $this->objUser =  $this->getObject("user", "security");
            $this->objUserParams = $this->getObject('dbuserparamsadmin', 'userparamsadmin');
            $this->userJid = $this->objUserParams->getValue('Jabber ID');
            //Create an instance of the language object
            $this->objLanguage = $this->getObject("language", "language");
            $this->objBack = $this->getObject('background', 'utilities');
            $this->objDbIm = $this->getObject('dbim');
            $this->objDbImPres = $this->getObject('dbimpresence');

            $this->objModules = $this->getObject('modules', 'modulecatalogue');

            if($this->objModules->checkIfRegistered('twitter'))
            {
                // Get other places to upstream content to
                $this->objTwitterLib = $this->getObject('twitterlib', 'twitter');
            }

            // Get the sysconfig variables for the Jabber user to set up the connection.
            $this->objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
            $this->jserver = $this->objSysConfig->getValue('jabberserver', 'im');
            $this->jport = $this->objSysConfig->getValue('jabberport', 'im');
            $this->juser = $this->objSysConfig->getValue('jabberuser', 'im');
            $this->jpass = $this->objSysConfig->getValue('jabberpass', 'im');
            $this->jclient = $this->objSysConfig->getValue('jabberclient', 'im');
            $this->jdomain = $this->objSysConfig->getValue('jabberdomain', 'im');

            $this->conn = new XMPPHP_XMPP($this->jserver, intval($this->jport), $this->juser, $this->jpass, $this->jclient, $this->jdomain, $printlog=FALSE, $loglevel=XMPPHP_Log::LEVEL_ERROR );
        }
        catch (customException $e)
        {
            customException::cleanUp();
            exit;
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
    public  function dispatch()
    {
        $action = $this->getParam('action');
        switch ($action) {
            case 'messageview':
                // echo "booyakasha!";
                $msgs = $this->objDbIm->getRange(0, 10);
                $this->setVarByRef('msgs', $msgs);
                return 'messageview_tpl.php';
                break;

            case 'viewallajax':
                $page = intval($this->getParam('page', 0));
                if ($page < 0) {
                    $page = 0;
                }
                $start = $page * 10;
                $msgs = $this->objDbIm->getRange($start, 10);
                $this->setVarByRef('msgs', $msgs);
                return 'viewall_ajax_tpl.php';
                break;
            case 'viewall':
            case NULL:
                $count = $this->objDbIm->getRecordCount();
                $pages = ceil($count / 10);
                $this->setVarByRef('pages', $pages);
                return 'viewall_tpl.php';
                break;

            case 'sendmessage':
                if ($this->userJid) {
                    $this->objImOps->sendMessage($this->userJid, 'Hope this works!');
                }
                break;

            case 'reply':

                $conn = new XMPPHP_XMPP($this->jserver, intval($this->jport), $this->juser, $this->jpass, $this->jclient, $this->jdomain, $printlog=FALSE, $loglevel=XMPPHP_Log::LEVEL_ERROR );
				$conn->connect();
                $conn->processUntil('session_start');
                $conn->message('pscott209@gmail.com', 'This is a test message!');
                $conn->disconnect();




                //$conn->connect();
                //$conn->processUntil('session_start');
                //$this->conn->message('wesleynitsckie@gmail.com', 'This is a test message!');
                //$conn->disconnect();


                //$msgId = $this->getParam('messageid');
                $replymessage = 'testing from jabber';//$this->getParam('myparam');
                //$contactName = 'wesleynitsckie@gmail.com';//$this->getParam('contactname');

                //add to database
                //$this->objDbIm->saveReply($msgId, $replymessage);

                //$this->setPageTemplate('');
			    //$this->setLayoutTemplate('');

                echo $replymessage;
                //break;

            case 'massmessage':
            $this->conn->connect();
            while(!$this->conn->isDisconnected()) {
                $counter = 0;
                $time_start = microtime(true);
                //while($counter < 5)
                //{
                  //  $to = 'pscott209@gmail.com';
                   // $this->conn->message($to, 'Test: '.$counter);
                    // $this->objImOps->sendMessage($to, 'Test: '.$counter);
                   // $counter++;
                   // echo $counter;
                //}
                $time_end = microtime(true);
                $time = $time_end - $time_start;
                $to = 'pscott209@gmail.com';
                $this->conn->message($to, 'Test took: '.$time.' seconds');
                //$this->objImOps->sendMessage($to, 'Test took: '.$time.' seconds');
            }
                break;

            case 'messagehandler':
                // This is a looooong running task... Lets use the background class to handle it
                //check the connection status
                $status = $this->objBack->isUserConn();
                //keep the user connection alive even if the browser is closed
                $callback = $this->objBack->keepAlive();
                // Now the code is backrounded and cannot be aborted! Be careful now...
                $this->conn->autoSubscribe();
                try {
                    $this->conn->connect();
                    while(!$this->conn->isDisconnected()) {
                        $payloads = $this->conn->processUntil(array('message', 'presence', 'end_stream', 'session_start', 'reply'));
                        foreach($payloads as $event) {
                            $pl = $event[1];
                            switch($event[0]) {
                                case 'message':
                                    //$this->objImOps->parseSysMessages($pl);
                                    switch ($pl['body'])
                                    {
                                        case 'quit':
                                            $this->conn->disconnect();
                                            break;
                                        case 'break':
                                            $this->conn->send("</end>");
                                            break;
                                        case 'latestblogs':
                                            if($this->objModules->checkIfRegistered('blog'))
                                            {
                                                $this->blogPosts = $this->getObject('blogposts', 'blog');
                                                $this->display = $this->blogPosts->showLastTenPostsStripped(5, FALSE);
                                                // send the results back
                                                $this->conn->message($pl['from'], $this->display);
                                            }
                                            else {
                                                $this->conn->message($pl['from'], "Blog is not installed on this server!");
                                            }
                                            break;
                                        case 'NULL':
                                            continue;
                                    }
                                    // Update Twitter
                                    if ($this->objTwitterLib && $pl['body']) {
                                        $this->objTwitterLib->updateStatus($pl['from'] . ': ' . $pl['body']);
                                    }
                                    // Send a response message
                                    if($pl['body'] != "")
                                    {
                                        // Bang the array into a table to keep a record of it.
                                        $this->objDbIm->addRecord($pl);
                                        //$this->conn->message($pl['from'], $body=$this->objLanguage->languageText('mod_im_msgadded', 'im'));
                                    }
                                    break;

                                case 'presence':
                                    // Update the table presence info
                                    $this->objDbImPres->updatePresence($pl);
                                    break;
                                case 'session_start':
                                    $this->conn->getRoster();
                                    $this->conn->presence($status=$this->objLanguage->languageText('mod_im_presgreeting', 'im'));
                                    break;
                                case 'reply':
                                    $this->conn->message('wesleynitsckie@gmail.com', $body='som etxtee');
                            }
                        }
                    }
                } catch(customException $e) {
                    customException::cleanUp();
                    exit;
                }
                // OK something went wrong, make sure the sysadmin knows about it!
                $email = $this->objConfig->getsiteEmail();
                $call2 = $this->objBack->setCallBack($email, $this->objLanguage->languageText('mod_im_msgsubject', 'im'), $this->objLanguage->languageText('mod_im_callbackmsg', 'im'));
                break;

            default:
                die("unknown action");
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
    public function requiresLogin($action)
    {
        $actionsRequiringLogin = array(
        'sendmessage'
        );
        if (in_array($action, $actionsRequiringLogin)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
