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

class dbcomments extends dbtable {

    function init() {
        parent::init("tbl_unesco_oer_comments");
    }

    function getMostCommented($totalReturn = 1) {
        $sql = "SELECT product_id, COUNT(*) as total_comments
                FROM tbl_unesco_oer_comments GROUP BY product_id
                ORDER BY total_comments DESC LIMIT 0,$totalReturn";
        return $this->getArray($sql);
    }

    function addComment($product_id, $user_id, $comment) {
        $data = array(
            'product_id' => $product_id,
            'user_id' => $user_id,
            'product_comment' => $comment,
            'created_on' => $this->now(),
        );

        $this->insert($data);
    }

    function getComment($productID){

        $sql = "SELECT product_comment from tbl_unesco_oer_comments where product_id = '$productID'";
        return $this->getArray($sql);


    }

    function getTotalcomments($productID)
    {
        $sql = "SELECT * FROM tbl_unesco_oer_comments where product_id = '$productID'";
        $count = $this->getArray($sql);

        return count($count);
    }

}

?>
