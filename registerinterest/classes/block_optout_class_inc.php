<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class block_optout extends object {

    /**
     *
     * @access public
     * @var object the romove link
     */
    var $objLink;

    /**
     *
     * @access public
     * @var string the  block's title 
     */
    var $title;

    /**
     *
     * @access public
     * @var object the database object
     */
    var $objDb;

    function init() {
        $this->objDb = $this->getObject('dbregisterinterest', 'registerinterest');
        $this->loadClass('link', 'htmlelements');
        $this->objLanguage = $this->getObject('language', 'language');
        $this->title = $this->objLanguage->languageText('word_unsubscribe', 'system');
    }

    function buildform() {
        $id = $this->getParam('id', NULL);
        if (!empty($id) && strlen($id) == 32) {
            $this->objLink = $this->getObject('link', 'htmlelements');
            $this->objLink->href = $this->uri(array('action' => 'optout', 'id' => $id, 'remove' => 't'));
            $heading = '<h3>' . $this->objLanguage->languageText('mod_registerinterest_optoutconfirm', 'registerinterest') . '</h3>';
            $this->objLink->link = $this->objLanguage->languageText('mod_registerinterest_removemsg', 'registerinterest');
            return $heading . $this->objLink->show();
        } else {
            return header('location: index.php');
        }
    }

    function show() {
        return $this->buildform();
    }

}

?>