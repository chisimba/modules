<?php
/**
 * Twitterizer controller class
 *
 * Class to control the Twitterizer module
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
 * @package   Twitterizer
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2009 Paul Scott
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za
 */

// security check - must be included in all scripts
if (!
/**
 * Description for $GLOBALS
 * @global unknown $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS['kewl_entry_point_run']) {
    die("You cannot view this page directly");
}
// end security check

/**
 * Twitterizer controller class
 *
 * Class to control the Twitterizer module.
 *
 * @category  Chisimba
 * @package   Twitterizer
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2009 Paul Scott
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za
 */

class twitterizer extends controller
{
    public $objLanguage;
    public $objSysConfig;
    public $objUser;
    public $objConfig;
    public $objOps;
    public $objDbTweets;

    /**
     * Initialises the instance variables.
     *
     * @access public
     */
    public function init()
    {
        try {
            $this->requiresLogin();
            $this->objLanguage = $this->getObject ( 'language', 'language' );
            $this->objConfig = $this->getObject('altconfig', 'config');
            $this->objOps = $this->getObject('tweetops');
            $this->objDbTweets = $this->getObject('dbtweets');
            // Get the sysconfig variables for the Jabber user to set up the connection.
            $this->objSysConfig = $this->getObject ( 'dbsysconfig', 'sysconfig' );
            $this->objUser = $this->getObject('user', 'security');
            if(!file_exists($this->objConfig->getcontentBasePath()."tracking")) {
                $this->objOps->createTrackFile();
            }
        } catch ( customException $e ) {
            customException::cleanUp ();
            exit ();
        }
    }

    /**
     * Standard dispatch method
     *
     * @access public
     * @param void
     * @return void
     */
    public function dispatch() {
        $action = $this->getParam ( 'action' );
        switch ($action) {

        	case 'json_gettweets':
        		echo $this->objOps->getJsonTweets($this->getParam('start', 0), $this->getParam('limit', 20));
        		exit(0);
        		break;
        		
            case 'viewallajax' :
                $page = intval ( $this->getParam ( 'page', 0 ) );
                if ($page < 0) {
                    $page = 0;
                }
                $start = $page * 20;
                $msgs = $this->objDbTweets->getRange($start, 20);

                $this->setVarByRef ( 'msgs', $msgs );

                header("Content-Type: text/html;charset=utf-8");
                return 'viewall_ajax_tpl.php';
                break;

            case NULL:
            case 'viewall' :
                $count = $this->objDbTweets->getMsgRecordCount ();
                $pages = ceil ( $count / 20 );
                $this->setVarByRef ( 'pages', $pages );

                header("Content-Type: text/html;charset=utf-8");
                return 'main_tpl.php';
                return 'viewall_tpl.php';
                break;

            case 'viewsingle':
                $id = $this->getParam('postid');
                $res = $this->objDbTweets->getSingle($id);
                $this->setVarByRef('results', $res);
                
                return 'viewsingle_tpl.php';
                break;

            case 'sioc':
                $userid = 1; //$this->getParam('userid');
                $this->objSiocMaker = $this->getObject('siocmaker', 'siocexport');
                // site data
                $siocData = array();
                $siocData['title'] = $this->objConfig->getSiteName();
                $siocData['url'] = $this->uri(array('module' => 'twitterizer'));
                $siocData['sioc_url'] = $this->uri(array('module' => 'twitterizer')).'#';
                $siocData['encoding'] = "UTF-8";
                $siocData['generator'] = $this->uri(array('module' => 'twitterizer', 'action' => 'sioc'));

                // make the site data
                $siteData = array();
                $siteData['url'] = $this->uri(array('module' => 'twitterizer'));
                $siteData['name'] = $this->objConfig->getSiteName();
                $siteData['description'] = $this->objConfig->getSiteName();

                $fora = array();
                $fora[0]['id'] = $userid;
                $fora[0]['url'] = $this->uri(array('module' => 'twitterizer', 'userid' => $userid));

                $users = array();
                $user[0]['id'] = 1;
                $user[0]['url'] = $this->uri('');

                $this->objSiocMaker->setSite($siteData);
                $this->objSiocMaker->setFora($fora);
                $this->objSiocMaker->setUsers($users);

                $this->objSiocMaker->createForum($userid, $this->uri(array('module' => 'twitterizer', 'userid' => 1)), $userid, $this->objConfig->getSiteName(), $this->objConfig->getSiteName());

                $posts = $this->objDbTweets->getAllPosts();

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
                    $tweet = str_replace('&', '&amp;', $post['tweet']);
                    $this->objSiocMaker->createPost($this->uri ( array ('postid' => $post['id'], 'action' => 'viewsingle' ) ),
                                                    $tweet, strip_tags($tweet), $tweet, $post['createdat'],
                                                    $updated = "",
                                                    $tags = array(),
                                                    $links = array()
                                                    );
                }

                echo $this->objSiocMaker->dumpSioc($siocData);
                break;

            case 'connect' :
                $this->objOps->getData();
                //$this->nextAction('');
                break;

            case 'search' :
                $term = $this->getParam('searchterm');
                $results = $this->objDbTweets->searcTable($term);
                $this->setVarByRef('results', $results); 
                header("Content-Type: text/html;charset=utf-8");
                
                return 'viewsingle_tpl.php';
                break;

            default:
                echo file_get_contents($file = $this->objConfig->getcontentBasePath()."tracking");
                break;
        }
    }

    public function requiresLogin() {
        return FALSE;
    }
}
?>