<?php

if (!$GLOBALS['kewl_entry_point_run'])
    die("you cannot view directly");

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
 * Module for demoing mvc arch in chisimba
 *
 * @author jcse1
 */
class dbproducts extends dbtable {

    function init() {
        parent::init("tbl_unesco_oer_products.sql");
    }
    
    function getProductTitle($title) {
        $sql = "select * from tbl_unesco_oer_products where title = '$title'";

        return $this->getArray($sql);
    }

    function getProducts($start,$end) {
        $sql = "select * from tbl_unesco_oer_products limit $start,$end";

        return $this->getArray($sql);
    }

}

?>
