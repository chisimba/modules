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

class dbproductratings extends dbtable {

    function init() {
        parent::init("tbl_unesco_oer_product_ratings");
    }

    function getMostRatedProducts() {
        $sql = "SELECT product_id, ROUND(AVG(score), 0) AS avg_score FROM tbl_unesco_oer_product_ratings GROUP BY product_id ORDER BY avg_score DESC LIMIT 3";

        return $this->getArray($sql);
    }

        function addRating($product_id, $rating) {
        $data = array(
            'product_id' => $product_id,
            'score' => $rating,
        );

        $this->insert($data);
    }

}
?>
