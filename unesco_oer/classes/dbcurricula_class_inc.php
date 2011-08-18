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
 * Description of dbcurricula_class_inc
 *
 * @author manie
 */
class dbcurricula extends dbtable
{

    function init() {
        parent::init("tbl_unesco_oer_curriculum");
    }

    function getCurricula($filter = NULL) {
        return $this->getAll($filter);
    }

    function addCurriculum($product_id, $title, $forward, $background, $description,$parentid) {
        $data = array(
            'product_id' => $product_id,
            'title' => $title,
            'forward'=> $forward,
            'background'=> $background,
            'introductory_description'=> $description,
            'parentid' => $parentid
        );

        return $this->insert($data);
    }

    function updateCurriculum($id, $product_id, $title, $forward, $background, $description){
         $data = array(
            'product_id' => $product_id,
            'title' => $title,
            'forward'=> $forward,
            'background'=> $background,
            'introductory_description'=> $description
        );

        return $this->update('id', $id, $data);
    }

    function getCurriculaByProductID($id) {
        $where = "where product_id='$id'";
        return $this->getCurricula($where);
    }

    function getCurriculumByID($id){
        return $this->getRow('id', $id);
    }

     function getCurriculaparent($id)
    {
        $sql = "select * from tbl_unesco_oer_curriculum where id = '$id'";

        return $this->getArray($sql);
    }

    function getCurriculabyparent($id)
    {
        $sql = "select * from tbl_unesco_oer_curriculum where parentid = '$id'";

        return $this->getArray($sql);
    }


}


?>
