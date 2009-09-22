<?php
/**
 * Events controller class
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
 * @package   events
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
 * Events controller class
 *
 * Class to control the Events module.
 *
 * @category  Chisimba
 * @package   events
 * @author    Paul Scott <pscott@uwc.ac.za>
 * @copyright 2009 Paul Scott
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za
 */

class events extends controller
{
    public $objLanguage;
    public $objSysConfig;
    public $objUser;
    public $objConfig;
    public $objOps;
    public $objCurl;
    
    /**
     * Initialises the instance variables.
     *
     * @access public
     */
    public function init()
    {
        try {
            $this->requiresLogin();
            $this->objLanguage  = $this->getObject ( 'language', 'language' );
            $this->objConfig    = $this->getObject('altconfig', 'config');
            // Get the sysconfig variables for the Jabber user to set up the connection.
            $this->objSysConfig = $this->getObject ( 'dbsysconfig', 'sysconfig' );
            $this->objUser      = $this->getObject('user', 'security');
            $this->objCurl      = $this->getObject('curlwrapper', 'utilities');
            $this->objDbEvents  = $this->getObject('dbevents');
            $this->objAJTags    = $this->getObject('ajaxtags', 'tagging');
            $this->objOps       = $this->getObject('eventsops');
            $this->objCookie    = $this->getObject('cookie', 'utilities');
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
            case NULL:
            
            case 'main' :
                return 'main_tpl.php';
                break;

            case 'callbackuri' :
                // decide what nextAction to take
                $this->nextAction('view');
                break;

            case 'view' :
                echo "View";
                break;

            case 'showsignin' :
                echo $this->objOps->showSignInBox();
                break;

            case 'showsignup' :
                echo $this->objOps->showSignUpBox();
                break;

            case 'invitefriend' :
                echo $this->objOps->showInviteForm();
                break;

            case 'changelocation' :
                return 'location_tpl.php';
                break;

            case 'setlocation' :
                $locstr = $this->getParam('geotag');
                $locarr = explode(",", $locstr);
                $lat = trim($locarr[0]);
                $lon = trim($locarr[1]);
                $this->objOps->findNearby($lat, $lon);
                $this->nextAction('');
                break;

            case 'eventadd' :
                $geotag = $this->getParam('geotag', NULL);
                $eventname = $this->getParam('eventname', NULL);
                $eventcat = $this->getParam('eventcategory', NULL);
                $venuename = $this->getParam('venuename', NULL);
                $startdatetime = $this->getParam('startdatetime', NULL);
                $enddatetime = $this->getParam('enddatetime', NULL);
                $eventurl = $this->getParam('eventurl', NULL);
                $ticketurl = $this->getParam('ticketurl', NULL);
                $ticketprice = $this->getParam('ticketprice', NULL);
                $ticketfree = $this->getParam('ticketfree', NULL);
                $description = $this->getParam('description', NULL);
                $personal = $this->getParam('personal', NULL);
                $tags = $this->getParam('tags', NULL);
                $selfpromo = $this->getParam('selfpromotion', NULL);
                // check that stuff is not NULL
                if($geotag == NULL || $eventname == NULL || $eventcat == NULL || $description == NULL) {
                    $message = $this->getObject('timeoutmessage', 'htmlelements');
                    $message->setMessage( $this->objLanguage->languageText("mod_events_requiredfieldsmissing", "events" ) );
                    $this->setVarByRef('message', $message);
                    return 'main_tpl.php';
                }
                if($selfpromo == 'on') {
                    // organizer thing
                    $canbringothers = $this->getParam('canbringothers', NULL);
                    $yestheycan = $this->getParam('yestheycan', NULL);
                    $howmany = $this->getParam('howmany', NULL);
                    $orgarr = array('userid' => $userid, 'canbringothers' => $canbringothers, 'numberguests' => $yestheycan, 'limitedto' => $howmany);
                }

                // split the geotag into geo lat and lon
                $geoarr = explode(",", $geotag);
                $geolat = trim($geoarr[0]);
                $geolon = trim($geoarr[1]);

                
                
                break;

            case 'addvenue' :
                // add the venue - fields needed are: $venuename, $venueaddress, $venuecity, $venuezip, $venuephone, $venueurl, $venuedescription, $private = 0 
                break;

            default:
                $this->nextAction('');
                break;
        }
    }

    public function requiresLogin() {
        return FALSE;
    }
}
?>