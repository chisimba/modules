<?php

/*
 * Responsible for insterting, updating and deleting events table
 */
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']) {
    die('You cannot view this page directly');
}

class dbevents extends dbTable {

    public function init() {
        parent::init('tbl_simpleregistrationevents');  //super
        $this->table = 'tbl_simpleregistrationevents';
        $this->objUser = $this->getObject('user', 'security');
    }

    /**
     * adds an event
     * @param <type> $eventtitle
     * @param <type> $eventdate
     * @return <type>
     */
    public function addEvent(
    $eventtitle, $shortname, $eventdate
    ) {
        $data = array(
            'event_title' => $eventtitle,
            'short_name' => $shortname,
            'event_date' => $eventdate,
            'userid' => $this->objUser->userid()
        );
        return $this->insert($data);
    }

    public function getEventIdByShortname($shortname) {
        $sql =
                "select id from " . $this->table . " where short_name = '" . $shortname . "'";
        $rows = $this->getArray($sql);
        return $rows;
    }

    /**
     * selects events created by me
     * @return <type>
     */
    public function getMyEvents() {
        $sql =
                "select * from " . $this->table . " where userid = '" . $this->objUser->userid() . "'";

        if ($this->objUser->isAdmin()) {
            $sql =
                    "select * from " . $this->table;
        }
        $rows = $this->getArray($sql);
        return $rows;
    }

}

?>
