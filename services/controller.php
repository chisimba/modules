<?php
/**
 * Services controller class
 *
 * Class to control the events module
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
 * @package   services
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
 * Services controller class
 *
 * Class to control the Events module.
 *
 * @category  Chisimba
 * @package   services
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2009 Paul Scott
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za
 */

class services extends controller
{
    public $objLanguage;
    public $objSysConfig;
    public $objUser;
    public $objConfig;
    public $objOps;
    public $objCurl;
    public $ip2Country;
    public $objWashout;
    public $objTeeny;
    public $objSocial;

    /**
     * Initialises the instance variables.
     *
     * @access public
     */
    public function init()
    {
        try {
            $this->requiresLogin();
            $this->objLanguage   = $this->getObject ( 'language', 'language' );
            $this->objConfig     = $this->getObject('altconfig', 'config');
            // Get the sysconfig variables for the Jabber user to set up the connection.
            $this->objSysConfig  = $this->getObject ( 'dbsysconfig', 'sysconfig' );
            $this->objUser       = $this->getObject('user', 'security');
            $this->objCurl       = $this->getObject('curlwrapper', 'utilities');
            $this->ip2Country    = $this->getObject('iptocountry', 'utilities');
            $this->objWashout    = $this->getObject('washout', 'utilities');
            $this->objTeeny      = $this->getObject ( 'tiny', 'tinyurl');
            $this->objDbEvents   = $this->getObject('dbevents', 'events');
            $this->objEventOps   = $this->getObject('eventsops', 'events');
            $this->objEventsUtils = $this->getObject('eventsutils', 'events');
        }
        catch ( customException $e ) {
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
            case 'ipcountryinfo' :
                $ip = $this->getIpAddr();
                $ccode = $this->ip2Country->getCountryByIP($ip);
                $country = $this->ip2Country->getCountryNameByIp($ip);
                // get the country info from the service if it doesn't exist.
                $countryinfo = $this->objDbEvents->metroGetCountryInfo($ccode);
                $countryinfo = $countryinfo[0];
                header("Content-Type: application/json");
                echo json_encode($countryinfo);
                break;

            case 'getsharebar' :
                $url = $this->getParam('url', NULL);
                $title = $this->getParam('title', NULL);
                $text = $this->getParam('text', "Interesting site");
                $text = $text." ";
                $this->objShare = $this->getObject('share', 'toolbar');
                $this->objShare->setupService($url, $title, $text);
                echo $this->objShare->show();
                break;
            
            case 'vieweventjson' :
                $eventid = $this->getParam('eventid', NULL);
                $eventdata = $this->objDbEvents->getEventInfo($eventid);
                header("Content-Type: application/json");
                echo $eventdata;
                break;
            
            case 'viewlocation' :
                $lat = $this->getParam('lat');
                $lon = $this->getParam('lon');
                echo $this->objEventOps->viewLocMap($lat, $lon);
                break;
                
            case 'placeweather' :
                $lat = $this->getParam('lat');
                $lon = $this->getParam('lon');
                echo $this->objEventOps->showPlaceWeatherBox($lat, $lon);
                break;
                
            case 'geowikipedia' :
                $lat = $this->getParam('lat');
                $lon = $this->getParam('lon');
                $ret = json_encode($this->objEventsUtils->array2object($this->objEventOps->findNearbyWikipedia($lat, $lon)));
                header("Content-Type: application/json");
                echo $ret;
                break; 

            case NULL:

            default:
                echo "Please supply a valid request!" ;
                break;
        }
    }

    public function requiresLogin() {
        return FALSE;
    }
    
    /**
     * Grabs the client IP address
     *
     * This function should be used to grab IP addresses, even those behind proxies, to gather data from
     *
     * @return string $ip
     */
    public function getIpAddr() {
        if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}
?>
