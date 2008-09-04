<?php
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
 * @version   CVS: $Id: $
 * @link      http://avoir.uwc.ac.za
 * @see       xmpphp
 */

class im extends controller
{

	public $objImOps;
	public $objUser;
	public $objLanguage;

	public $conn;
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
			//Create an instance of the language object
			$this->objLanguage = $this->getObject("language", "language");
			$this->conn = new XMPPHP_XMPP('talk.google.com', 5222, 'fsiu123', 'fsiu2008', 'xmpphp', 'gmail.com', $printlog=TRUE, $loglevel=XMPPHP_Log::LEVEL_INFO);
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
			case null:
				echo "booyakasha!";
				
				break;

			case 'sendmessage':
				try {
					$this->conn->connect();
					$this->conn->processUntil('session_start');
					$this->conn->presence();
					$this->conn->message('pscott209@gmail.com', $this->objUser->userName()." says: Hello you!");
					$this->conn->disconnect();
				} catch(customException $e) {
					customException::cleanUp();
					exit;
				}
				break;

			case 'messagehandler':
				$this->conn->autoSubscribe();
				try {
					$this->conn->connect();
					while(!$this->conn->isDisconnected()) {
						$payloads = $this->conn->processUntil(array('message', 'presence', 'end_stream', 'session_start'));
						foreach($payloads as $event) {
							$pl = $event[1];
							switch($event[0]) {
								case 'message':
									echo "Message from: {$pl['from']}<br />";
									echo $pl['body'] . "<br />";
									echo "<hr />";
									$this->conn->message($pl['from'], $body="Thanks for sending me \"{$pl['body']}\".", $type=$pl['type']);
									if($pl['body'] == 'quit') $this->conn->disconnect();
									if($pl['body'] == 'break') $this->conn->send("</end>");
									break;
								case 'presence':
									print "Presence: {$pl['from']} [{$pl['show']}] {$pl['status']}\n";
									break;
								case 'session_start':
									print "Session Start\n";
									$this->conn->getRoster();
									$this->conn->presence($status="Hello!");
									break;
							}
						}
					}
				} catch(customException $e) {
					customException::cleanUp();
					exit;
				}
				break;
				
			default:
				die("unknown action");
				break;
		}
	}

}
