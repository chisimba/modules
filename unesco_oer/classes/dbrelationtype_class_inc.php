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
 * Description of dbrelationtype_class_inc
 *
 * @author manie
 */
class dbrelationtype extends dbtable
{
    function init()
    {
        parent::init('tbl_unesco_oer_relation_types');
    }

    /**This function adds a new relation description to the relation types
     * table
     *
     * @param <type> $discription
     */
    function addRelation($discription)
    {
        $insertArray = array(
            'description' => $discription
        );
        $this->insert($insertArray);
    }

    /**This function returns a relations description given it's id
     *
     * @param <type> $id
     */
    function getDescriptionByID($id)
    {
        $sql = "select * from $this->_tableName where id = '$id'";
        $relations = $this->getArray($sql);
        return $relations[0]['description'];
    }

    /**This function returns all relation types
     *
     */
    function getRelationTypes($filter = NULL){
        return $this->getAll($filter);
    }
}
?>
