<?php

/**
 * Location controller class
 * 
 * Class to control the Location module
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
 * @package   location
 * @author    Charl van Niekerk <charlvn@charlvn.za.net>
 * @copyright 2008 Charl van Niekerk
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za
 * @see       http://fireeagle.yahoo.net
 */

class location extends controller
{
	protected $objSysConfig;
	protected $feKey;
	protected $feSecret;
	protected $objUserParams;
	protected $feToken;
	protected $feTokenSecret;

	/**
	 *
	 * Standard constructor method to retrieve the action from the
	 * querystring, and instantiate the user and lanaguage objects
	 *
	 */
	public function init()
	{
		// Load resources
		include $this->getResourcePath('oauth/OAuth.php', 'utilities');
		include $this->getResourcePath('fireeagle/fireeagle.php');

		// Set the json global for use in fireeagle.php
		$GLOBALS['json'] = $this->getObject('json', 'utilities');

		// Read system configuration
		$this->objSysConfig = $this->getObject('dbsysconfig', 'sysconfig');
		$this->feKey = $this->objSysConfig->getValue('fireeaglekey', 'location');
		$this->feSecret = $this->objSysConfig->getValue('fireeaglesecret', 'location');

		// Read user configuration
		$this->objUserParams = $this->getObject('dbuserparamsadmin', 'userparamsadmin');
		$this->feToken = $this->objUserParams->getValue('Fire Eagle Token');
		$this->feTokenSecret = $this->objUserParams->getValue('Fire Eagle Token Secret');
	}

	/**
	 * Standard dispatch method to handle adding and saving
	 * of comments
	 *
	 * @access public
	 * @param void
	 * @return void
	*/
	public function dispatch()
	{
		$action = $this->getParam('action');
		switch ($action) {
			case 'start':
				$fireeagle = new FireEagle($this->feKey, $this->feSecret);
				$token = $fireeagle->getRequestToken();
				$_SESSION['request_token'] = $token['oauth_token'];
				$_SESSION['request_secret'] = $token['oauth_token_secret'];
				header('Location: ' . $fireeagle->getAuthorizeURL($token['oauth_token']));
				exit;
			case 'callback':
				if ($_GET['oauth_token'] != $_SESSION['request_token']) {
					die('Token mismatch');
				}
				$fireeagle = new FireEagle($this->feKey, $this->feSecret, $_SESSION['request_token'], $_SESSION['request_secret']);
				$token = $fireeagle->getAccessToken();
				$this->objUserParams->setItem('Fire Eagle Token', $token['oauth_token']);
				$this->objUserParams->setItem('Fire Eagle Token Secret', $token['oauth_token_secret']);
				$this->nextAction(null, null, 'location');
				break;
			default:
				if ($this->feToken && $this->feTokenSecret) {
					$fireeagle = new FireEagle($this->feKey, $this->feSecret, $this->feToken, $this->feTokenSecret);
					$location = $fireeagle->user();
					header('Content-Type: text/plain');
					print_r($location);
				} else {
					$this->nextAction('start', null, 'location');
				}
		}
	}
}

?>
