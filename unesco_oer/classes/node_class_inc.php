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
 * Description of node_class_inc
 *
 * @author manie
 */
class node extends object
{
    /**Name
     *
     * @var <type>
     */
    private $_name;

    /**Description
     *
     * @var <type>
     */
    private $_description;

    /**Type
     *
     * @var <type>
     */
    private $_type;


    ////////////////   constructor   ////////////////

    public function  init()
    {
        parent::init();
    }

    ////////////////   setters   ////////////////

    function setName($name)
    {
        $this->_name = $name;
    }

    function setDescription($description)
    {
        $this->_description = $description;
    }

    function setType($type)
    {
        $this->_type = $type;
    }

    ////////////////   getters   ////////////////

    function getName()
    {
        return $this->_name;
    }

    function getDescription()
    {
        return $this->_description;
    }

    function getType($type)
    {
        return $this->_type;
    }

    ////////////////   methods   ////////////////

    
}
?>
