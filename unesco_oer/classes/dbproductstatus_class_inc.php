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
 * Description of dbproductstatus_class_inc
 *
 * @author manie
 */
class dbproductstatus extends dbtable
{
    function init()
    {
        parent::init('tbl_unesco_oer_product_status');
    }

    /**This function adds a new status to the product status
     * table
     *
     * @param <type> $discription
     */
    function addStatus($status)
    {
        $insertArray = array(
            'status' => $status
        );
        $this->insert($insertArray);
    }

    /**This function returns a status given it's id
     *
     * @param <type> $id
     */
    function getStatusByID($id)
    {
        $sql = "select * from $this->_tableName where id = '$id'";
        $relations = $this->getArray($sql);
        return $relations[0]['status'];
    }

    /**This function returns all statuses' available, with an optional filter
     *
     */
    function getAllStatuses($filter = NULL){
        return $this->getAll($filter);
    }
}

?>
