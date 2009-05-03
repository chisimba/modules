<?php
/**
 * Yahoo API controller class
 *
 * Controller class for the Yahoo API controller module
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
 * @category  Chisimba
 * @package   yapi
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2009 Paul Scott
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id: $
 * @link      http://avoir.uwc.ac.za
 */
// security check - must be included in all scripts
if (! /**
 * Description for $GLOBALS
 * @global entry point $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS ['kewl_entry_point_run']) {
    die ( "You cannot view this page directly" );
}
// end security check


/**
 * Yahoo API controller class
 *
 * Yahoo API controller class
 *
 * @category  Chisimba
 * @package   yapi
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2008 Paul Scott
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   Release: @package_version@
 * @link      http://avoir.uwc.ac.za
 */
class yapi extends controller {
    public $objLanguage;
    public $objConfig;
    public $objSysConfig;

    private $ysession;
    private $consumerKey;
    private $consumerKeySecret;
    private $applicationId;

    public $yuser;
    public $profile;
    public $presence;
    public $connections;
    public $updates;
    public $connectionUpdates;


    public function init() {
        include ($this->getResourcePath ( 'lib/Yahoo.inc', 'yapi' ));
        $this->objLanguage = $this->getObject('language', 'language');
        $this->objConfig = $this->getObject('altconfig', 'config');
        // Get the sysconfig variables for the Yahoo API user to set up the connection.
        $this->objSysConfig = $this->getObject ( 'dbsysconfig', 'sysconfig' );

        // All these need to come from dbsysconfig...
        // Make sure you obtain application keys before continuing by visiting:
        // http://developer.yahoo.com/dashboard/

        // Your consumer key goes here.
        $this->consumerKey = $this->objSysConfig->getValue('consumer_key', 'yapi');

        // Your consumer key secret goes here.
        $this->consumerKeySecret = $this->objSysConfig->getValue('consumer_secret', 'yapi');

        // Your application ID goes here.
        $this->applicationId = $this->objSysConfig->getValue('application_id', 'yapi');

        // Get a session first. If the viewer isn't sessioned yet, this call
        // will redirect them to log in and authorize your application to
        $this->ysession = YahooSession::requireSession($this->consumerKey, $this->consumerKeySecret,
        $this->applicationId);

        // Get the currently sessioned user. That means the user who is
        // currently viewing this page.
        $this->yuser = $this->ysession->getSessionedUser();

        // Load the profile for the current user.
        $this->profile = $this->yuser->loadProfile();

        // Fetch the presence for the current user.
        $this->presence = $this->yuser->getPresence();

        // Access the connection list for the current user.
        $start = 0; $count = 100; $total = 0;
        $this->connections = $this->yuser->getConnections($start, $count, $total);

        // Retrieve the updates for the current user.
        $this->updates = $this->yuser->listUpdates();

        // Retrieve the updates for the connections of the current user.
        $this->connectionUpdates = $this->yuser->listConnectionUpdates();
    }

    public function dispatch($action = NULL) {
        switch ($action) {
            case "view" :
                // Do nothing
                // TODO: do something here...
                break;

            default :
                echo "Profile: ".$this->print_html($this->profile)."<br />";
                echo "Presence ".$this->print_html($this->presence)."<br />";
                echo "Connections: ".$this->print_html($this->connections)."<br />";
                echo "<h2>Updates</h2>"."<br />";
                echo "<h3>Yours</h3>"."<br />";
                echo $this->print_html($this->updates)."<br />";
                echo "<h3>Your Connections</h3>"."<br />";
                echo $this->print_html($this->connectionUpdates)."<br />";

                die();
                break;
        }
    }

    /**
     * A simple method that implements print_r/var_dump in a HTML friendly way.
     */
    public function print_html($object) {
        return str_replace(array(" ", "\n"), array("&nbsp;", "<br>"), htmlentities(print_r($object, true), ENT_COMPAT, "UTF-8"));
    }
}
?>