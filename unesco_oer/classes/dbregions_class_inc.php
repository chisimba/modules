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
 * Description of dbregions_class_inc
 *
 * @author manie
 */
class dbregions extends dbtable {

    public function init() {
        parent::init('tbl_unesco_oer_regions');
    }

    public function getRegionByID($id) {
        return $this->getRow('id', $id);
    }

    public function addRegion($region) {
        $this->insert(array('region'=>$region));
    }
}
?>
