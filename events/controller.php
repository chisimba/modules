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
    public $objDbTags;
    public $objUtils;

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
            $this->objDbTags    = $this->getObject('dbtags', 'tagging');
            $this->objUtils     = $this->getObject('eventsutils');
            // $this->setPageTemplate('null_page_tpl.php');
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
                $locarr = $this->objOps->findNearby($lat, $lon);
                if($this->objCookie->exists('events_location') ) {
                    $this->objCookie->cookiedelete('events_location');
                    $this->objCookie->cookiedelete('events_latlon');
                    $this->objCookie->cookiedelete('events_geoid');
                    $this->objCookie->set( 'events_location', $locarr->name, time()+60*60*24*30);
                    $this->objCookie->set( 'events_latlon', $locarr->lat."|".$locarr->lng, time()+60*60*24*30);
                }
                else {
                    $this->objCookie->set( 'events_location', $locarr->name, time()+60*60*24*30);
                    $this->objCookie->set( 'events_latlon', $locarr->lat."|".$locarr->lng, time()+60*60*24*30);
                }
                $this->nextAction('');
                break;

            case 'eventadd' :
                $eventname = $this->getParam('eventname', NULL);
                $eventcat = $this->getParam('eventcategory', NULL);
                $venuename = $this->getParam('venuename', NULL);
                $startdatetime = $this->getParam('startdatetime', NULL);
                // split to start date and time
                $startarr = explode(" ", $startdatetime);
                if(is_array($startarr) && !empty($startarr) && $startarr[0] != '') {
                    $startdate = trim($startarr[0]);
                    $starttime = trim($startarr[1]);
                }
                else {
                    $startdate = NULL;
                    $starttime = NULL;
                }
                $enddatetime = $this->getParam('enddatetime', NULL);
                $endarr = explode(" ", $enddatetime);
                if(is_array($endarr) && !empty($endarr) && $endarr[0] != '') {
                    $enddate = trim($endarr[0]);
                    $endtime = trim($endarr[1]);
                }
                else {
                    $enddate = NULL;
                    $endtime = NULL;
                }
                $eventurl = $this->getParam('eventurl', NULL);
                $ticketurl = $this->getParam('ticketurl', NULL);
                $ticketprice = $this->getParam('ticketprice', NULL);
                $ticketfree = $this->getParam('ticketfree', NULL);
                $description = $this->getParam('description', NULL);
                $personal = $this->getParam('personal', NULL);
                $tags = $this->getParam('tags', NULL);
                $selfpromo = $this->getParam('selfpromotion', NULL);
                // check that stuff is not NULL
                if($eventname == NULL || $eventcat == NULL || $description == NULL) {
                    $message = $this->getObject('timeoutmessage', 'htmlelements');
                    $message->setMessage( $this->objLanguage->languageText("mod_events_requiredfieldsmissing", "events" ) );
                    $this->setVarByRef('message', $message);
                    return 'main_tpl.php';
                }

                // Add the event to the database, the event will be updated by the venue script afterwards with the venue info
                $insarr =  array('userid' => $this->objUser->userId(), 'name' => $eventname, 'venue_id' => NULL, 'category_id' => $eventcat,
                                 'start_date' => $startdate, 'end_date' => $enddate, 'start_time' =>  $starttime, 'end_time' => $endtime,
                                 'description' => $description, 'url' => $eventurl, 'personal' => $personal, 'selfpromotion' => $selfpromo,
                                 'ticket_url' => $ticketurl, 'ticket_price' => $ticketprice, 'ticket_free' => $ticketfree, 'creationtime' => time(),);
                // insert the event info
                $eventret = $this->objDbEvents->addEventArray($insarr);
                $tagarray = explode(",", $tags);
                // send the tags to the tags database
                $this->objDbTags->insertTags($tagarray, $this->objUser->userId(), $eventret, 'events', $this->uri(''), NULL);
                if($selfpromo == 'on') {
                    // organizer thing
                    $canbringothers = $this->getParam('canbringothers', NULL);
                    $yestheycan = $this->getParam('yestheycan', NULL);
                    $howmany = $this->getParam('howmany', NULL);
                    $orgarr = array('userid' => $this->objUser->userId(), 'event_id' => $eventret, 'canbringothers' => $canbringothers, 'numberguests' => $yestheycan, 'limitedto' => $howmany);
                    $this->objDbEvents->addEventPromo($orgarr);
                }

                // now we can check if the venue has been defined before or not and get some details there...
                $venuelist = $this->objDbEvents->venueCheckExists($venuename);
                $venlist = $this->objOps->formatVenues($venuelist);
                $venueform = $this->objOps->venueSelector($venlist, $eventret);
                $this->setVarByRef('venueform', $venueform);
                $this->setVarByRef('eventid', $eventret);
                return 'venue_tpl.php';
                break;

            case 'addvenue' :
                echo $this->objOps->addEditVenueForm();
                break;

            case 'venueselect' :
                $venueid = $this->getParam('venue_radio');
                $eventid = $this->getParam('input_eventid');
                $this->objDbEvents->updateEventWithVenueId($eventid, $venueid);
                $this->nextAction('');
                break;

            case 'test' : 
                var_dump($this->objDbEvents->metroSearch('V', 'ZA', 'Western Cape', 'South '));
                break;

            case 'savevenue' :
                $eventid = $this->getParam('input_eventid');
                $venuename = $this->getParam('venuename');
                $venueaddress = $this->getParam('venueaddress');
                $city = $this->getParam('city');
                $zip = $this->getParam('zip');
                $phone = $this->getParam('phone');
                $url = $this->getParam('url');
                if($url == 'http://') {
                    $url = '';
                }
                $description = $this->getParam('venuedescription');
                $private = $this->getParam('private');
                if($private == 'on') {
                    $private = 1;
                }
                else {
                    $private = 0;
                }
                $geo = $this->getParam('geotag');
                $geo = explode(",", $geo);
                $lat = trim($geo[0]);
                $lon = trim($geo[1]);
                // This is always an insert as it is always new. 
                $insarr = array('userid' => $this->objUser->userId(), 'venuename' => $venuename, 'venueaddress' => $venueaddress, 
                                'city' => $city, 'zip' => $zip, 'phone' => $phone, 'url' => $url, 'venuedescription' => $description, 
                                'geolat' => $lat, 'geolon' => $lon, 'privatevenue' => $private);
                $venueid = $this->objDbEvents->venueAddArray($insarr);
                $this->objDbEvents->updateEventWithVenueId($eventid, $venueid);
                // do a lookup and see where this place is, then fill out heirarchy to country scale (or more?)
                $locarr = $this->objOps->findNearby($lat, $lon);
                $heir = $this->objOps->getHeiracrchy($locarr->geonameId);
                foreach($heir as $h) {
                    // insert record to db with venue id as key
                    $h->venueid = $venueid;
                    $h->userid = $this->objUser->userId();
                    // we now need to convert the object to an array for dbTable::insert();
                    $h = $this->objUtils->object2array($h);
                    $this->objDbEvents->venueInsertHeirarchy($h);
                }
                $this->nextAction('');
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