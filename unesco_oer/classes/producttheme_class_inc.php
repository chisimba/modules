<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of producttheme_class_inc
 *
 * @author support
 */
class producttheme extends object {/**
     * This is the name of the theme
     * @var <String>
     */
    private $_name;
    /**
     * This is the ID of the theme
     * @var <String>
     */
    private $_id;
  
    function init() {
        $this->_name = NULL;
        $this->_id = NULL;
    }

    function getName() {
        return $this->_name;
    }

    function getID() {
        return $this->_id;
    }

    function setID($id) {
        if (!empty($id)) {
            $this->_id = $id;
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function setName($name) {
        if (!empty($name)) {
            $this->_name = $name;
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
?>