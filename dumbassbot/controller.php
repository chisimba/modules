<?php
/**
 * dumbass bot controller class
 *
 * Class to control the dumbass bot module
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
 * @package   dumbassbot
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2009 Paul Scott
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version
 * @link      http://avoir.uwc.ac.za
 * @see       xmpphp
 */

class dumbassbot extends controller {
    
    public $objSysConfig;
    public $jserver;
    public $jport;
    public $juser;
    public $jpass;
    public $jclient;
    public $jdomain;
    public $conn;

    public function init() {
        
        $this->objSysConfig = $this->getObject ( 'dbsysconfig', 'sysconfig' );
        $this->jserver = $this->objSysConfig->getValue ( 'jabberserver', 'dumbassbot' );
        $this->jport = $this->objSysConfig->getValue ( 'jabberport', 'dumbassbot' );
        $this->juser = $this->objSysConfig->getValue ( 'jabberuser', 'dumbassbot' );
        $this->jpass = $this->objSysConfig->getValue ( 'jabberpass', 'dumbassbot' );
        $this->jclient = $this->objSysConfig->getValue ( 'jabberclient', 'dumbassbot' );
        $this->jdomain = $this->objSysConfig->getValue ( 'jabberdomain', 'dumbassbot' );

        include ($this->getResourcePath ( 'XMPPHP/XMPP.php', 'im' ));
        $this->conn = new XMPPHP_XMPP ( $this->jserver, intval ( $this->jport ), $this->juser, 
                                        $this->jpass, $this->jclient, $this->jdomain, 
                                        $printlog = FALSE, $loglevel = XMPPHP_Log::LEVEL_ERROR );
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

            case 'messagehandler' :
                $this->conn->autoSubscribe ();
                try {
                    $this->conn->connect ();
                    while ( ! $this->conn->isDisconnected () ) {
                        $payloads = $this->conn->processUntil ( array ('message', 'presence', 'end_stream', 'session_start' ) ); //array ('message', 'presence', 'end_stream', 'session_start', 'reply' )
                        foreach ( $payloads as $event ) {
                            $pl = $event [1];

                            switch ($event [0]) {
                                case 'message' :
                                    switch ($pl ['body']) {
                                        default :
                                            $this->conn->message($pl['from'], "I don't understand your request!");
                                            break;

                                        case 'hello' :
                                            $this->conn->message($pl['from'], "Hello! ".$pl['from']);
                                            break;
                                    }
                            }
                        }
                    }
                }
                catch ( customException $e ) {
                    customException::cleanUp ();
                    exit ();
                }
                break;

         }
    }
}
?>