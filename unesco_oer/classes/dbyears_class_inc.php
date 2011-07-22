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
 * Description of dbyears_class_inc
 *
 * @author manie
 */
class dbyears extends dbtable
{
    function init() {
        parent::init('tbl_unesco_oer_years');
    }

    function getYears($filter = NULL) {
        return $this->getAll($filter);
    }

    function addYear($year, $calendar_id){
        $data = array (
            'year' => $year,
            'calendar_id' => $calendar_id
        );

        return $this-> insert($data);
    }

    function updateYear($id, $year, $calendar_id){
        $data = array (
            'year' => $year,
            'calendar_id' => $calendar_id
        );

        return $this->update('id', $id, $data);
    }

    function getYearByID($id){
        return $this->getRow('id', $id);
    }

    function getYearsByCalendarID($id) {
        $where = "where calendar_id='$id'";
        return $this->getYears($where);
    }
}
?>
