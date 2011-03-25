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
class dbproducts extends dbtable
{

    function init()
    {
        parent::init("tbl_unesco_oer_products");
    }

    function getProductTitle($title)
    {
        $sql = "select * from tbl_unesco_oer_products where title = '$title'";

        return $this->getArray($sql);
    }

    function getFilteredProducts($filter) {
        $sql = "select * from tbl_unesco_oer_products where $filter";

        return $this->getArray($sql);
    }

     function getProducts($start,$end) {
        $sql = "select * from tbl_unesco_oer_products limit $start,$end";

        return $this->getArray($sql);
    }

    function addProduct($productArray)
    {
        //INSERT INTO `unesco_oer`.`tbl_unesco_oer_products` (`id`, `parent_id`, `title`, `creator`, `keywords`, `description`, `created_on`, `resource_type`, `content_type`, `format`, `source`, `theme`, `language`, `content`, `thumbnail`, `puid`) VALUES ('1', NULL, 'Model Curricula for Journalism Education', 'UNESCO Series on Journalism Education', 'Journalism', 'Model curricula for journalism education; UNESCO series on journalism education; 2009', CURDATE(), 'pdf', 'text', 'pdf', 'Products/Files', 'none', 'English', '??', 'Products/Thumbnails/Journalism Curriculum.png', NULL)
    }

    //TODO check the hierichal storage of data to make this more efficient
    function getNoOfAdaptations($parentId, $count)
    {
        $sql = "SELECT * FROM tbl_unesco_oer_products WHERE parent_id = '$parentId'";

        //Count the number of occurances
        while ($sql['parent_id'] != 'NULL') {
            $this->getNoOfAdaptations($sql['parent_id'], $count + 1);
            //$count++;
        }

        return $count;
    }

}
?>


<?php
// $parent is the parent of the children we want to see
// $level is increased when we go deeper into the tree,
//        used to display a nice indented tree

/*
function display_children($parent, $level) {
   // retrieve all children of $parent
   $result = mysql_query('SELECT title FROM tree '.
                          'WHERE parent="'.$parent.'";');

   // display each child
   while ($row = mysql_fetch_array($result)) {
       // indent and display the title of this child
       echo str_repeat('  ',$level).$row['title']."\n";

       // call this function again to display this
       // child's children
       display_children($row['title'], $level+1);
   }
}
 * *
 */
?>
