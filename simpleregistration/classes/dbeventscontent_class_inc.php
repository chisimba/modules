<?php



/*
 * Responsible for insterting, updating and deleting events content table
 */
// security check - must be included in all scripts
if (!$GLOBALS['kewl_entry_point_run']){
    die('You cannot view this page directly');
}
class dbeventscontent extends dbTable{

    public function init()
    {
        parent::init('tbl_simpleregistrationcontent');  //super
        $this->table = 'tbl_simpleregistrationcontent';
        $this->objUser = $this->getObject ( 'user', 'security' );

    }


/**
 *stores the event details
 * @param <type> $eventid
 * @param <type> $eventtitle
 * @param <type> $venue
 * @param <type> $content
 * @param <type> $leftitle1
 * @param <type> $leftitle2
 * @param <type> $footer
 * @return <type>
 */
    public function addEventContent(
        $eventid,
        $venue,
        $content,
        $leftitle1,
        $leftitle2,
        $footer,
        $emailcontact,
        $emailsubject,
        $emailname,
        $emailsubject,
        $emailcontent,
        $emailattachments,
        $staffreg,
        $visitorreg
    ){

        $data = array(
            'event_id'=>$eventid,
            'event_timevenue' => $venue,
            'event_content' => $content,
            'event_lefttitle1'=>$leftitle1,
            'event_lefttitle2'=>$leftitle2,
            'event_footer'=>$footer,
            'event_emailcontact'=>$emailcontact,
            'event_emailsubject'=>$emailsubject,
            'event_emailname'=>$emailname,
            'event_emailcontent'=>$emailcontent,
            'event_emailattachments'=>$emailattachments,
            'event_staffreg'=>$staffreg,
            'event_visitorreg'=>$visitorreg
        );
        print_r($data);
        return $this->insert($data);

    }

    public function updateEventContent(
        $eventid,
        $venue,
        $content,
        $leftitle1,
        $leftitle2,
        $footer,
        $emailcontact,
        $emailsubject,
        $emailname,
        $emailsubject,
        $emailcontent,
        $emailattachments,
        $staffreg,
        $visitorreg
    ){

        $data = array(
            'event_timevenue' => $venue,
            'event_content' => $content,
            'event_lefttitle1'=>$leftitle1,
            'event_lefttitle2'=>$leftitle2,
            'event_footer'=>$footer,
            'event_emailcontact'=>$emailcontact,
            'event_emailsubject'=>$emailsubject,
            'event_emailname'=>$emailname,
            'event_emailcontent'=>$emailcontent,
            'event_emailattachments'=>$emailattachments,
            'event_staffreg'=>$staffreg,
            'event_visitorreg'=>$visitorreg
        );
        return $this->update('event_id',$eventid, $data);;

    }
    /**
     *get the content
     * @param <type> $eventid
     * @return <type>
     */
    public function getEventContent($eventid){
        $row=$this->getRow('event_id', $eventid);
        return $row;
    }

}
?>
