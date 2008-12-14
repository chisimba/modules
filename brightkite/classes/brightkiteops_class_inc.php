<?php

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
 * Library class to provide easy access to the Brightkite API.
 *
 * @author    Charl van Niekerk <charlvn@charlvn.za.net>
 * @copyright GNU/GPL, AVOIR
 * @package   brightkite
 * @access    public
 * @version   $Id$
 */
class brightkiteops extends object
{
    /**
     * Standard constructor to load the necessary resources
     * and populate the new object's instance variables
     */
    public function init()
    {
    }

    public function getCheckins($user)
    {
        $checkins = array();
        $xml = new SimpleXMLElement("http://brightkite.com/people/$user/objects.xml?filters=checkins", null, TRUE);
        foreach ($xml->checkin as $checkin) {
            $checkins[] = $checkin->place;
        }
        return $checkins;
    }

    public function getNotes($user)
    {
        $notes = array();
        $xml = new SimpleXMLElement("http://brightkite.com/people/$user/objects.xml?filters=notes", null, TRUE);
        foreach ($xml->note as $note) {
            $notes[] = $note->body;
        }
        return $notes;
    }
}

?>
