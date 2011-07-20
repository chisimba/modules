<?php
/* 
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
 */



/**
 * Description of dbcalendar_class_inc
 *
 * @author manie
 */
class dbcalendar extends dbtable
{
    function init() {
        parent::init('tbl_unesco_oer_calendar');
    }

    function getCalendars($filter = NULL) {
        return $this->getAll($filter);
    }

    function addCalendar($title, $curriculum_id){
        $data = array (
            'title' => $title,
            'curriculum_id' => $curriculum_id
        );

        return $this-> insert($data);
    }

    function updateCalendar($id, $title, $curriculum_id){
        $data = array (
            'title' => $title,
            'curriculum_id' => $curriculum_id
        );

        return $this->update('id', $id, $data);
    }

    function getCalendarByID($id){
        return $this->getRow('id', $id);
    }

    function getCalendarsByCurriculumID($id) {
        $where = "where curriculum_id='$id'";
        return $this->getCalendars($where);
    }
}
?>
